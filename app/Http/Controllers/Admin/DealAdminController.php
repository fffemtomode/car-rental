<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealAdminController extends Controller
{
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('car')) {
            [$brand, $model] = array_pad(explode('|', $request->car, 2), 2, null);
            $query->whereHas('car', function ($q) use ($brand, $model) {
                $q->where('brand', $brand)->where('model', $model);
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->applyFilters(Deal::with(['user', 'car'])->latest(), $request);

        $deals = $query->paginate(15)->withQueryString();

        $totalConfirmedValue = Deal::whereIn('status', ['confirmed', 'completed'])->sum('total_price');

        $monthStart = now()->startOfMonth();
        $monthDealsCount = Deal::where('created_at', '>=', $monthStart)->count();
        $monthDealsValue = Deal::whereIn('status', ['confirmed', 'completed'])->where('created_at', '>=', $monthStart)->sum('total_price');

        $cars = Car::select('brand', 'model')->distinct()->orderBy('brand')->orderBy('model')->get();

        return view('admin.deals.index', compact('deals', 'totalConfirmedValue', 'cars', 'monthDealsCount', 'monthDealsValue'));
    }

    public function export(Request $request)
    {
        $query = $this->applyFilters(Deal::with(['user', 'car'])->latest(), $request);
        $deals = $query->get();

        $totalValue = $deals->whereIn('status', ['confirmed', 'completed'])->sum('total_price');

        $filename = 'deals_' . now()->format('Y-m-d_His') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $html = view('admin.deals.export', compact('deals', 'totalValue'))->render();

        return response($html, 200, $headers);
    }
}
