<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function createRental(Car $car)
    {
        return view('deals.create-rental', compact('car'));
    }

    public function storeRental(Request $request, Car $car)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $overlap = Deal::where('car_id', $car->id)
            ->where('type', 'rental')
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']]);
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['start_date' => 'Автомобіль вже заброньований на ці дати.']);
        }

        $days = (strtotime($validated['end_date']) - strtotime($validated['start_date'])) / 86400;
        $totalPrice = $days * $car->price_per_day;

        $deal = Deal::create([
            'user_id' => auth()->id(),
            'car_id' => $car->id,
            'type' => 'rental',
            'status' => 'pending',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('deals.show', $deal)->with('success', 'Заявку на оренду створено.');
    }

    public function show(Deal $deal)
    {
        return view('deals.show', compact('deal'));
    }
    public function createBuyout(Car $car)
    {
        return view('deals.create-buyout', compact('car'));
    }

    public function storeBuyout(Car $car)
    {
        if (!$car->buyout_price) {
            return back()->withErrors(['buyout' => 'Для цього автомобіля викуп недоступний.']);
        }

        $deal = Deal::create([
            'user_id' => auth()->id(),
            'car_id' => $car->id,
            'type' => 'buyout',
            'status' => 'pending',
            'total_price' => $car->buyout_price,
        ]);

        return redirect()->route('deals.show', $deal)->with('success', 'Заявку на викуп створено.');
    }
    public function createLeasing(Car $car)
    {
        return view('deals.create-leasing', compact('car'));
    }

    public function storeLeasing(Request $request, Car $car)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'leasing_months' => 'required|integer|min:1|max:60',
        ]);

        $deal = Deal::create([
            'user_id' => auth()->id(),
            'car_id' => $car->id,
            'type' => 'leasing',
            'status' => 'pending',
            'total_price' => $validated['amount'],
            'leasing_months' => $validated['leasing_months'],
        ]);

        $monthlyPayment = round($validated['amount'] / $validated['leasing_months'], 2);
        $startDate = now()->addMonth()->startOfMonth();

        for ($i = 0; $i < $validated['leasing_months']; $i++) {
            $deal->leasingSchedules()->create([
                'payment_date' => $startDate->copy()->addMonths($i),
                'amount' => $monthlyPayment,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('deals.show', $deal)->with('success', 'Заявку на лізинг створено, графік платежів сформовано.');
    }
}
