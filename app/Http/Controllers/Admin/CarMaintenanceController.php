<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarMaintenanceController extends Controller
{
    public function index(Car $car)
    {
        $logs = $car->maintenanceLogs()->latest('date')->get();
        return view('admin.cars.maintenance', compact('car', 'logs'));
    }

    public function store(Request $request, Car $car)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'date' => 'required|date',
            'mileage_at_service' => 'nullable|integer|min:0',
            'note' => 'nullable|string',
        ]);

        $car->maintenanceLogs()->create($validated);

        return redirect()->route('admin.cars.maintenance', $car)->with('success', 'Запис ТО додано.');
    }
}
