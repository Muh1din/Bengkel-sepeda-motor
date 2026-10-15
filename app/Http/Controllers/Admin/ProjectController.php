<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProjectController extends Controller
{
    /**
     * Menampilkan daftar proyek.
     */
    public function index(Request $request)
    {
        $projects = Project::with('category')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('vehicle_name', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Menampilkan form tambah proyek.
     */
    public function create()
    {
        $categories = ProjectCategory::orderBy('name')->get();

        return view('admin.projects.create', compact('categories'));
    }

    /**
     * Membuat slug proyek yang unik.
     */
    private function generateProjectSlug(
        string $title,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($title) ?: 'project';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Project::where('slug', $slug)
            ->when(
                $ignoreId !== null,
                fn($query) => $query->where('id', '!=', $ignoreId)
            )
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Aturan validasi data proyek.
     */
    private function projectRules(bool $isUpdate = false): array
    {
        return [
            'project_category_id' => [
                'required',
                'integer',
                'exists:project_categories,id',
            ],
            'title' => ['required', 'string', 'max:150'],
            'vehicle_name' => ['required', 'string', 'max:100'],
            'summary' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'completed_at' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,published'],

            'cover' => [
                $isUpdate ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'gallery' => ['nullable', 'array', 'max:5'],
            'gallery.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'before' => ['nullable', 'array', 'max:5'],
            'before.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'after' => ['nullable', 'array', 'max:5'],
            'after.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['required', 'integer', 'distinct'],
        ];
    }

    /**
     * Menyimpan proyek baru.
     */
    public function store(Request $request, ImageService $imageService)
    {
        $validated = $request->validate($this->projectRules());

        $uploadedPaths = [];

        try {
            DB::transaction(function () use (
                $validated,
                $imageService,
                &$uploadedPaths
            ) {
                $project = Project::create([
                    'project_category_id' => $validated['project_category_id'],
                    'title' => $validated['title'],
                    'slug' => $this->generateProjectSlug(
                        $validated['title']
                    ),
                    'vehicle_name' => $validated['vehicle_name'],
                    'summary' => $validated['summary'],
                    'description' => $validated['description'] ?? null,
                    'completed_at' => $validated['completed_at'] ?? null,
                    'status' => $validated['status'],
                ]);

                $imageGroups = [
                    'cover' => [$validated['cover']],
                    'gallery' => $validated['gallery'] ?? [],
                    'before' => $validated['before'] ?? [],
                    'after' => $validated['after'] ?? [],
                ];

                foreach ($imageGroups as $type => $images) {
                    foreach ($images as $index => $image) {
                        $path = $imageService->storeAsWebp(
                            $image,
                            "projects/{$type}"
                        );

                        $uploadedPaths[] = $path;

                        $project->images()->create([
                            'image_path' => $path,
                            'image_type' => $type,
                            'sort_order' => $index,
                        ]);
                    }
                }
            });
        } catch (Throwable $exception) {
            $this->deleteStoredFiles($uploadedPaths);

            Log::error('Failed to create portfolio project.', [
                'exception' => $exception->getMessage(),
            ]);

            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            return back()
                ->withInput($request->except([
                    'cover',
                    'gallery',
                    'before',
                    'after',
                ]))
                ->withErrors([
                    'cover' => 'Proyek atau gambar gagal disimpan. Periksa log Laravel untuk mengetahui penyebabnya.',
                ]);
        }

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Proyek dan gambar berhasil disimpan.');
    }

    /**
     * Menampilkan form edit proyek.
     */
    public function edit(Project $project)
    {
        $project->load([
            'category',
            'images' => fn($query) => $query
                ->orderBy('image_type')
                ->orderBy('sort_order'),
        ]);

        $categories = ProjectCategory::orderBy('name')->get();

        return view(
            'admin.projects.edit',
            compact('project', 'categories')
        );
    }

    /**
     * Memperbarui proyek dan gambar.
     */
    public function update(
        Request $request,
        Project $project,
        ImageService $imageService
    ) {
        $validated = $request->validate($this->projectRules(true));

        $uploadedPaths = [];
        $oldPathsToDelete = [];

        try {
            DB::transaction(function () use (
                $request,
                $validated,
                $project,
                $imageService,
                &$uploadedPaths,
                &$oldPathsToDelete
            ) {
                // Pastikan gambar yang akan dihapus milik proyek ini.
                $removeImageIds = collect(
                    $validated['remove_images'] ?? []
                )->unique()->values();

                if ($removeImageIds->isNotEmpty()) {
                    $imagesToRemove = $project->images()
                        ->whereIn('id', $removeImageIds)
                        ->where('image_type', '!=', 'cover')
                        ->get();

                    // Jangan izinkan menghapus gambar proyek lain.
                    if ($imagesToRemove->count() !== $removeImageIds->count()) {
                        throw ValidationException::withMessages([
                            'remove_images' => 'Gambar yang dipilih tidak valid atau bukan milik proyek ini.',
                        ]);
                    }

                    foreach ($imagesToRemove as $image) {
                        $oldPathsToDelete[] = $image->image_path;
                        $image->delete();
                    }
                }

                // Perbarui data utama proyek.
                // Slug lama dipertahankan agar URL publik tidak berubah.
                $project->update([
                    'project_category_id' => $validated['project_category_id'],
                    'title' => $validated['title'],
                    'vehicle_name' => $validated['vehicle_name'],
                    'summary' => $validated['summary'],
                    'description' => $validated['description'] ?? null,
                    'completed_at' => $validated['completed_at'] ?? null,
                    'status' => $validated['status'],
                ]);

                // Ganti cover hanya jika admin mengunggah cover baru.
                if ($request->hasFile('cover')) {
                    $oldCovers = $project->images()
                        ->where('image_type', 'cover')
                        ->get();

                    foreach ($oldCovers as $oldCover) {
                        $oldPathsToDelete[] = $oldCover->image_path;
                        $oldCover->delete();
                    }

                    $path = $imageService->storeAsWebp(
                        $validated['cover'],
                        'projects/cover'
                    );

                    $uploadedPaths[] = $path;

                    $project->images()->create([
                        'image_path' => $path,
                        'image_type' => 'cover',
                        'sort_order' => 0,
                    ]);
                }

                // Tambahkan gambar baru ke setiap kelompok.
                $imageGroups = [
                    'gallery' => $validated['gallery'] ?? [],
                    'before' => $validated['before'] ?? [],
                    'after' => $validated['after'] ?? [],
                ];

                foreach ($imageGroups as $type => $images) {
                    $nextSortOrder = (int) $project->images()
                        ->where('image_type', $type)
                        ->max('sort_order') + 1;

                    foreach ($images as $index => $image) {
                        $path = $imageService->storeAsWebp(
                            $image,
                            "projects/{$type}"
                        );

                        $uploadedPaths[] = $path;

                        $project->images()->create([
                            'image_path' => $path,
                            'image_type' => $type,
                            'sort_order' => $nextSortOrder + $index,
                        ]);
                    }
                }
            });
        } catch (Throwable $exception) {
            $this->deleteStoredFiles($uploadedPaths);

            Log::error('Failed to update portfolio project.', [
                'project_id' => $project->id,
                'exception' => $exception->getMessage(),
            ]);

            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            return back()
                ->withInput($request->except([
                    'cover',
                    'gallery',
                    'before',
                    'after',
                ]))
                ->withErrors([
                    'cover' => 'Proyek atau gambar gagal diperbarui. Periksa log Laravel untuk mengetahui penyebabnya.',
                ]);
        }

        // Hapus file lama setelah transaksi database berhasil.
        $this->deleteStoredFiles($oldPathsToDelete);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    /**
     * Menghapus proyek beserta seluruh gambarnya.
     */
    public function destroy(Project $project)
    {
        $imagePaths = $project->images()
            ->pluck('image_path')
            ->all();

        try {
            DB::transaction(function () use ($project) {
                // Record project_images akan ikut terhapus
                // melalui foreign key ON DELETE CASCADE.
                $project->delete();
            });
        } catch (Throwable $exception) {
            Log::error('Failed to delete portfolio project.', [
                'project_id' => $project->id,
                'exception' => $exception->getMessage(),
            ]);

            return back()->with(
                'error',
                'Proyek gagal dihapus. Silakan periksa log Laravel.'
            );
        }

        // File baru dihapus setelah penghapusan database berhasil.
        $this->deleteStoredFiles($imagePaths);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Proyek dan gambar berhasil dihapus.');
    }

    /**
     * Menghapus file dari public storage.
     */
    private function deleteStoredFiles(array $paths): void
    {
        foreach (array_unique($paths) as $path) {
            try {
                Storage::disk('public')->delete($path);
            } catch (Throwable $exception) {
                Log::warning('Failed to delete portfolio image file.', [
                    'path' => $path,
                    'exception' => $exception->getMessage(),
                ]);
            }
        }
    }
}
