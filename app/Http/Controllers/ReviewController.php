<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'car'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Відгук опубліковано.');
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);
        return back()->with('success', 'Відгук відхилено.');
    }
}
