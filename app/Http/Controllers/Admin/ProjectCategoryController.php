<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectCategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori proyek.
     */
    public function index()
    {
        $categories = ProjectCategory::withCount('projects')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.project-categories.index', compact('categories'));
    }

    /**
     * Menampilkan form tambah kategori.
     */
    public function create()
    {
        return view('admin.project-categories.create');
    }

    /**
     * Menyimpan kategori baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:project_categories,name',
            ],
        ]);

        ProjectCategory::create([
            'name' => $validated['name'],
            'slug' => $this->generateSlug($validated['name']),
        ]);

        return redirect()
            ->route('admin.project-categories.index')
            ->with('success', 'Kategori proyek berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit kategori.
     */
    public function edit(ProjectCategory $projectCategory)
    {
        return view(
            'admin.project-categories.edit',
            compact('projectCategory')
        );
    }

    /**
     * Memperbarui kategori.
     */
    public function update(
        Request $request,
        ProjectCategory $projectCategory
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('project_categories', 'name')
                    ->ignore($projectCategory->id),
            ],
        ]);

        $projectCategory->update([
            'name' => $validated['name'],
            'slug' => $this->generateSlug(
                $validated['name'],
                $projectCategory->id
            ),
        ]);

        return redirect()
            ->route('admin.project-categories.index')
            ->with('success', 'Kategori proyek berhasil diperbarui.');
    }

    /**
     * Menghapus kategori.
     */
    public function destroy(ProjectCategory $projectCategory)
    {
        if ($projectCategory->projects()->exists()) {
            return redirect()
                ->route('admin.project-categories.index')
                ->with(
                    'error',
                    'Kategori tidak dapat dihapus karena masih digunakan oleh proyek.'
                );
        }

        $projectCategory->delete();

        return redirect()
            ->route('admin.project-categories.index')
            ->with('success', 'Kategori proyek berhasil dihapus.');
    }

    /**
     * Membuat slug kategori yang unik.
     */
    private function generateSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug ?: 'category';

        $slug = $baseSlug;
        $counter = 1;

        while (
            ProjectCategory::where('slug', $slug)
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
}
