<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Deal::with(['user', 'car'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('car_id')) {
            $query->where('car_id', $request->car_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $deals = $query->paginate(15)->withQueryString();

        $totalConfirmedValue = Deal::whereIn('status', ['confirmed', 'completed'])->sum('total_price');

        $monthStart = now()->startOfMonth();
        $monthDealsCount = Deal::where('created_at', '>=', $monthStart)->count();
        $monthDealsValue = Deal::whereIn('status', ['confirmed', 'completed'])->where('created_at', '>=', $monthStart)->sum('total_price');

        $cars = \App\Models\Car::orderBy('brand')->get();

        return view('admin.deals.index', compact('deals', 'totalConfirmedValue', 'cars', 'monthDealsCount', 'monthDealsValue'));
    }

    public function export(Request $request)
    {
        $query = Deal::with(['user', 'car'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('car_id')) {
            $query->where('car_id', $request->car_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $deals = $query->get();

        $filename = 'deals_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($deals) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM для коректної кирилиці в Excel

            fputcsv($file, ['ID', 'Дата', 'Клієнт', 'Телефон', 'Авто', 'Тип', 'Статус', 'Вартість (грн)']);

            foreach ($deals as $deal) {
                fputcsv($file, [
                    $deal->id,
                    $deal->created_at->format('d.m.Y H:i'),
                    $deal->user->name,
                    $deal->user->phone ?? '',
                    $deal->car->brand . ' ' . $deal->car->model,
                    $deal->type_label,
                    $deal->status_label,
                    $deal->total_price,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
