<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;

class ProjectController extends Controller
{
    public function index()
    {
        $categories = ProjectCategory::orderBy('name')->get();

        $projects = Project::with(['category', 'images'])
            ->where('status', 'published')
            ->when(request('category'), function ($query, $categorySlug) {
                $query->whereHas('category', function ($query) use ($categorySlug) {
                    $query->where('slug', $categorySlug);
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('app.projects.index', compact('categories', 'projects'));
    }

    public function show(string $slug)
    {
        $project = Project::with(['category', 'images'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('app.projects.show', compact('project'));
    }
}
