<?php

namespace Tests\Feature\Home;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Halaman daftar proyek berhasil ditampilkan.
     */
    public function test_project_index_page_can_be_opened(): void
    {
        $response = $this->get(route('projects.index'));

        $response->assertOk();
        $response->assertViewIs('app.projects.index');

        $response->assertViewHas('categories');
        $response->assertViewHas('projects');
    }

    /**
     * Hanya proyek berstatus published yang ditampilkan.
     */
    public function test_index_only_displays_published_projects(): void
    {
        $categoryId = $this->createCategory();

        $publishedProjectId = $this->createProject(
            $categoryId,
            'published'
        );

        $this->createProject($categoryId, 'draft');

        $response = $this->get(route('projects.index'));

        $response->assertOk();

        $response->assertViewHas('projects', function ($projects) use (
            $publishedProjectId
        ) {
            return $projects->total() === 1
                && $projects->getCollection()->contains(
                    fn($project) => $project->id === $publishedProjectId
                )
                && $projects->getCollection()->every(
                    fn($project) => $project->status === 'published'
                );
        });
    }

    /**
     * Filter kategori hanya menampilkan proyek dari kategori terpilih.
     */
    public function test_index_can_filter_projects_by_category(): void
    {
        $categoryA = $this->createCategory('Repaint', 'repaint');
        $categoryB = $this->createCategory('Restorasi', 'restorasi');

        $projectA = $this->createProject(
            $categoryA,
            'published',
            'Project Repaint'
        );

        $this->createProject(
            $categoryB,
            'published',
            'Project Restorasi'
        );

        $response = $this->get(
            route('projects.index', ['category' => 'repaint'])
        );

        $response->assertOk();

        $response->assertViewHas('projects', function ($projects) use (
            $projectA
        ) {
            return $projects->total() === 1
                && $projects->getCollection()->contains(
                    fn($project) => $project->id === $projectA
                );
        });
    }

    /**
     * Daftar proyek menggunakan pagination maksimal sembilan item per halaman.
     */
    public function test_index_paginates_projects_nine_per_page(): void
    {
        $categoryId = $this->createCategory();

        for ($i = 1; $i <= 11; $i++) {
            $this->createProject(
                $categoryId,
                'published',
                "Project {$i}"
            );
        }

        $response = $this->get(route('projects.index'));

        $response->assertOk();

        $response->assertViewHas('projects', function ($projects) {
            return $projects->total() === 11
                && $projects->perPage() === 9
                && $projects->count() === 9;
        });
    }

    /**
     * Pagination mempertahankan query string kategori.
     */
    public function test_index_preserves_category_filter_in_pagination_links(): void
    {
        $categoryId = $this->createCategory(
            'Repaint',
            'repaint'
        );

        for ($i = 1; $i <= 11; $i++) {
            $this->createProject(
                $categoryId,
                'published',
                "Repaint Project {$i}"
            );
        }

        $response = $this->get(
            route('projects.index', ['category' => 'repaint'])
        );

        $response->assertOk();

        $response->assertViewHas('projects', function ($projects) {
            return $projects->total() === 11
                && $projects->perPage() === 9
                && str_contains(
                    $projects->nextPageUrl() ?? '',
                    'category=repaint'
                );
        });
    }

    /**
     * Detail proyek published berhasil ditampilkan.
     */
    public function test_published_project_detail_can_be_opened(): void
    {
        $categoryId = $this->createCategory();

        $projectId = $this->createProject(
            $categoryId,
            'published',
            'Published Project'
        );

        $slug = DB::table('projects')
            ->where('id', $projectId)
            ->value('slug');

        $response = $this->get(
            route('projects.show', ['slug' => $slug])
        );

        $response->assertOk();
        $response->assertViewIs('app.projects.show');

        $response->assertViewHas('project', function ($project) use (
            $projectId
        ) {
            return $project->id === $projectId
                && $project->status === 'published';
        });
    }

    /**
     * Proyek draft tidak dapat diakses melalui halaman detail publik.
     */
    public function test_draft_project_detail_returns_not_found(): void
    {
        $categoryId = $this->createCategory();

        $projectId = $this->createProject(
            $categoryId,
            'draft'
        );

        $slug = DB::table('projects')
            ->where('id', $projectId)
            ->value('slug');

        $response = $this->get(
            route('projects.show', ['slug' => $slug])
        );

        $response->assertNotFound();
    }

    /**
     * Slug yang tidak ditemukan menghasilkan 404.
     */
    public function test_nonexistent_project_slug_returns_not_found(): void
    {
        $response = $this->get(
            route('projects.show', ['slug' => 'project-tidak-ada'])
        );

        $response->assertNotFound();
    }

    /**
     * Membuat kategori proyek.
     */
    private function createCategory(
        string $name = 'Test Category',
        ?string $slug = null
    ): int {
        $slug ??= 'test-category-' . uniqid();

        return DB::table('project_categories')->insertGetId([
            'name' => $name,
            'slug' => $slug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Membuat proyek dengan kategori yang valid.
     */
    private function createProject(
        int $categoryId,
        string $status,
        ?string $title = null
    ): int {
        $title ??= 'Test Project ' . uniqid();

        return DB::table('projects')->insertGetId([
            'project_category_id' => $categoryId,
            'title' => $title,
            'slug' => 'test-project-' . uniqid(),
            'vehicle_name' => 'Test Motorcycle',
            'summary' => 'Test project summary',
            'description' => 'Test project description',
            'status' => $status,
            'completed_at' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
