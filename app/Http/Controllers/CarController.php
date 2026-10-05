<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::query();

        if ($request->filled('brand')) {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }
        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        if ($request->filled('available_from') && $request->filled('available_to')) {
            $from = $request->available_from;
            $to = $request->available_to;

            $query->whereDoesntHave('deals', function ($q) use ($from, $to) {
                $q->where('type', 'rental')
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where(function ($q2) use ($from, $to) {
                        $q2->whereBetween('start_date', [$from, $to])
                            ->orWhereBetween('end_date', [$from, $to])
                            ->orWhere(function ($q3) use ($from, $to) {
                                $q3->where('start_date', '<=', $from)->where('end_date', '>=', $to);
                            });
                    });
            });
        }

        $cars = $query->orderByRaw("status = 'available' desc")->paginate(9)->withQueryString();

        return view('cars.index', compact('cars'));
    }

    public function show(Car $car)
    {
        return view('cars.show', compact('car'));
    }
}
