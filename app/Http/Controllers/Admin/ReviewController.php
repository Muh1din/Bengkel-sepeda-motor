<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['customer', 'booking'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($query) use ($search) {
                    $query->whereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%");
                    })->orWhereHas('booking', function ($bookingQuery) use ($search) {
                        $bookingQuery->where('booking_code', 'like', "%{$search}%");
                    });
                });
            })
            ->when($request->filled('rating'), function ($query) use ($request) {
                $query->where('rating', $request->rating);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalReviews = Review::count();

        $pendingReviews = Review::where('status', 'pending')->count();

        $publishedReviews = Review::where('status', 'published')->count();

        $averageRating = Review::avg('rating');

        return view('admin.reviews.index', compact(
            'reviews',
            'totalReviews',
            'pendingReviews',
            'publishedReviews',
            'averageRating'
        ));
    }

    public function publish(Review $review)
    {
        $review->update([
            'status' => 'published',
        ]);

        return back()->with('success', 'Ulasan berhasil dipublikasikan.');
    }

    public function hide(Review $review)
    {
        $review->update([
            'status' => 'hidden',
        ]);

        return back()->with('success', 'Ulasan berhasil disembunyikan.');
    }
}
