<?php

namespace App\Http\Controllers;

class DealHistoryController extends Controller
{
    public function index()
    {
        $deals = auth()->user()->deals()->with('car')->latest()->get();

        return view('deals.history', compact('deals'));
    }
}
