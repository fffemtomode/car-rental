<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;

class CarCalendarController extends Controller
{
    public function index(Car $car)
    {
        $bookedDates = $car->bookedDates();

        $periods = $car->deals()
            ->where('type', 'rental')
            ->whereIn('status', ['pending', 'confirmed'])
            ->with('user')
            ->orderBy('start_date')
            ->get();

        return view('admin.cars.calendar', compact('car', 'bookedDates', 'periods'));
    }
}
