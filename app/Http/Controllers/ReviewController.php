<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new review.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trailer_id' => 'required|exists:trailers,id',
            'content' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = Review::create([
            'user_id' => Auth::id(),
            'trailer_id' => $validated['trailer_id'],
            'content' => $validated['content'],
            'rating' => $validated['rating'],
        ]);

        return response()->json([
            'message' => 'Review submitted successfully.',
            'review' => $review,
        ], 201);
    }

    /**
     * Retrieve all reviews for a specific trailer.
     */
    public function index($trailer_id)
    {
        $reviews = Review::where('trailer_id', $trailer_id)->get();

        return response()->json($reviews);
    }
}
