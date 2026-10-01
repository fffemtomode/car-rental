<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientNote;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'client')->withCount('deals');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $clients = $query->orderByDesc('deals_count')->paginate(15)->withQueryString();

        return view('admin.clients.index', compact('clients'));
    }

    public function show(User $client)
    {
        $deals = $client->deals()->with('car')->latest()->get();
        $notes = $client->clientNotes()->with('author')->latest()->get();

        return view('admin.clients.show', compact('client', 'deals', 'notes'));
    }

    public function storeNote(Request $request, User $client)
    {
        $request->validate(['note' => 'required|string|max:2000']);

        $client->clientNotes()->create([
            'created_by' => auth()->id(),
            'note' => $request->note,
        ]);

        return back()->with('success', 'Нотатку додано.');
    }
}
