<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;

class DealAdminController extends Controller
{
    public function index()
    {
        $deals = Deal::with(['user', 'car'])->latest()->paginate(15);
        return view('admin.deals.index', compact('deals'));
    }
}
