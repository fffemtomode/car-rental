<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $this->notifyManagers($deal);

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

        $this->notifyManagers($deal);

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

        $this->notifyManagers($deal);

        return redirect()->route('deals.show', $deal)->with('success', 'Заявку на лізинг створено, графік платежів сформовано.');
    }

    public function confirm(Deal $deal)
    {
        $deal->update(['status' => 'confirmed']);

        $pdf = Pdf::loadView('pdf.contract', ['deal' => $deal]);
        $fileName = 'contract_' . $deal->id . '.pdf';
        $path = 'contracts/' . $fileName;

        \Storage::disk('public')->put($path, $pdf->output());

        Contract::create([
            'deal_id' => $deal->id,
            'file_path' => $path,
            'signed_at' => now(),
        ]);

        $deal->user->appNotifications()->create([
            'message' => "Вашу угоду #{$deal->id} підтверджено, договір сформовано.",
            'type' => 'deal_confirmed',
        ]);

        return redirect()->route('deals.show', $deal)->with('success', 'Угоду підтверджено, договір сформовано.');
    }

    private function notifyManagers(Deal $deal): void
    {
        User::whereIn('role', ['manager', 'admin'])->get()->each(function ($manager) use ($deal) {
            $manager->appNotifications()->create([
                'message' => "Нова заявка #{$deal->id} на {$deal->type} від {$deal->user->name}.",
                'type' => 'new_deal',
            ]);
        });
    }
}
