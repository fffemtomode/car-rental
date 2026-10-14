<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Car $car)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'text' => 'required|string|max:2000',
        ]);

        $car->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'text' => $validated['text'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Дякуємо! Відгук буде опубліковано після перевірки модератором.');
    }
}
