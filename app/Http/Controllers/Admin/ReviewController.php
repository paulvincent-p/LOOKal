<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('business')->latest()->paginate(20);

        return view('admin.reviews', compact('reviews'));
    }

    public function toggle(Review $review)
    {
        $review->update(['status' => $review->status === 'visible' ? 'hidden' : 'visible']);

        return back()->with('status', 'Review is now '.$review->status.'.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('status', 'Review deleted.');
    }
}
