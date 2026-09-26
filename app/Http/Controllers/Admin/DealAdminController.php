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

        $deals = $query->paginate(15)->withQueryString();

        $totalConfirmedValue = Deal::whereIn('status', ['confirmed', 'completed'])->sum('total_price');

        $cars = \App\Models\Car::orderBy('brand')->get();

        return view('admin.deals.index', compact('deals', 'totalConfirmedValue', 'cars'));
    }
}
