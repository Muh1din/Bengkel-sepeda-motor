<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;

class PortfolioDashboardController extends Controller
{
    public function index()
    {
        $totalProjects = Project::count();

        $publishedProjects = Project::where('status', 'published')->count();

        $draftProjects = Project::where('status', 'draft')->count();

        $totalCategories = ProjectCategory::count();

        $recentProjects = Project::with('category')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.portofolio.dashboard', compact(
            'totalProjects',
            'publishedProjects',
            'draftProjects',
            'totalCategories',
            'recentProjects'
        ));
    }
}
