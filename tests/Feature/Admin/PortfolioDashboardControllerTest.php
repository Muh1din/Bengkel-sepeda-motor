<?php

namespace Tests\Feature\Admin;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        $role = Role::firstOrCreate([
            'name' => 'Admin',
        ]);

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    private function createCategory(): ProjectCategory
    {
        return ProjectCategory::create([
            'name' => 'Motorcycle Repaint',
            'slug' => 'motorcycle-repaint',
        ]);
    }

    /**
     * Membuat proyek langsung untuk kebutuhan testing.
     */
    /**
     * Membuat proyek langsung untuk kebutuhan testing.
     */
    /**
     * Membuat proyek langsung untuk kebutuhan testing.
     */
    private function createProject(
        int $categoryId,
        string $status = 'draft'
    ): Project {
        $uniqueId = uniqid();

        return Project::create([
            'project_category_id' => $categoryId,
            'title' => 'Test Project ' . $uniqueId,
            'slug' => 'test-project-' . $uniqueId,
            'vehicle_name' => 'Honda CBR 250RR',
            'summary' => 'Ringkasan proyek untuk kebutuhan testing.',
            'description' => 'Deskripsi proyek untuk kebutuhan testing.',
            'status' => $status,
        ]);
    }

    /**
     * Admin dapat membuka dashboard portofolio.
     */
    public function test_admin_can_access_portfolio_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->get(route('admin.portfolio.dashboard'));

        $response->assertOk();
        $response->assertViewIs('admin.portofolio.dashboard');
    }

    /**
     * Dashboard menampilkan statistik proyek dengan benar.
     */
    public function test_dashboard_displays_correct_project_statistics(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        foreach (range(1, 3) as $i) {
            $this->createProject($category->id, 'published');
        }

        foreach (range(1, 2) as $i) {
            $this->createProject($category->id, 'draft');
        }

        $response = $this->actingAs($admin)
            ->get(route('admin.portfolio.dashboard'));

        $response->assertOk();
        $response->assertViewHas('totalProjects', 5);
        $response->assertViewHas('publishedProjects', 3);
        $response->assertViewHas('draftProjects', 2);
        $response->assertViewHas('totalCategories', 1);
    }

    /**
     * Dashboard hanya menampilkan lima proyek terbaru.
     */
    public function test_dashboard_displays_only_five_recent_projects(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        foreach (range(1, 7) as $i) {
            $this->createProject($category->id);
        }

        $response = $this->actingAs($admin)
            ->get(route('admin.portfolio.dashboard'));

        $response->assertOk();

        $response->assertViewHas('recentProjects', function ($projects) {
            return $projects->count() === 5;
        });
    }

    /**
     * Proyek terbaru memiliki relasi kategori yang dimuat.
     */
    public function test_recent_projects_include_their_categories(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        foreach (range(1, 3) as $i) {
            $this->createProject($category->id);
        }

        $response = $this->actingAs($admin)
            ->get(route('admin.portfolio.dashboard'));

        $response->assertOk();

        $response->assertViewHas('recentProjects', function ($projects) {
            return $projects->every(
                fn($project) => $project->relationLoaded('category')
                    && $project->category !== null
            );
        });
    }
}
