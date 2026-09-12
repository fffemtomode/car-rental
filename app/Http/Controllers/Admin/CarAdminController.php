<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarAdminController extends Controller
{
    public function index()
    {
        $cars = Car::latest()->paginate(10);
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'engine' => 'nullable|string|max:255',
            'mileage' => 'nullable|integer|min:0',
            'price_per_day' => 'required|numeric|min:0',
            'buyout_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,rented,sold,maintenance',
            'photos.*' => 'nullable|image|max:4096',
        ]);

        $car = Car::create(collect($validated)->except('photos')->toArray());

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $photo) {
                $car->photos()->create([
                    'path' => $photo->store('cars', 'public'),
                    'position' => $i,
                ]);
            }
        }

        return redirect()->route('admin.cars.index')->with('success', 'Автомобіль додано.');
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'engine' => 'nullable|string|max:255',
            'mileage' => 'nullable|integer|min:0',
            'price_per_day' => 'required|numeric|min:0',
            'buyout_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,rented,sold,maintenance',
            'photos.*' => 'nullable|image|max:4096',
        ]);

        $car->update(collect($validated)->except('photos')->toArray());

        if ($request->hasFile('photos')) {
            $nextPosition = $car->photos()->max('position') + 1;
            foreach ($request->file('photos') as $i => $photo) {
                $car->photos()->create([
                    'path' => $photo->store('cars', 'public'),
                    'position' => $nextPosition + $i,
                ]);
            }
        }

        return redirect()->route('admin.cars.index')->with('success', 'Автомобіль оновлено.');
    }


    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('admin.cars.index')->with('success', 'Автомобіль видалено.');
    }
    public function destroyPhoto(\App\Models\CarPhoto $photo)
    {
        \Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return back()->with('success', 'Фото видалено.');
    }
    public function movePhoto(Request $request, \App\Models\CarPhoto $photo)
    {
        $direction = $request->input('direction');
        $photos = $photo->car->photos()->orderBy('position')->get();
        $index = $photos->search(fn ($p) => $p->id === $photo->id);

        if ($direction === 'left' && $index > 0) {
            $other = $photos[$index - 1];
        } elseif ($direction === 'right' && $index < $photos->count() - 1) {
            $other = $photos[$index + 1];
        } else {
            return back();
        }

        $tmp = $photo->position;
        $photo->position = $other->position;
        $other->position = $tmp;
        $photo->save();
        $other->save();

        return back()->with('success', 'Порядок фото оновлено.');
    }
}
