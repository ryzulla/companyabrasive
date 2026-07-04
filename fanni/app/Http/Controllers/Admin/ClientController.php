<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::when($request->filled('q'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            })
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'logo'  => ['required', 'image', 'max:2048'],
            'order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('clients', 'public');
        }

        Client::create($data);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'logo'  => ['nullable', 'image', 'max:2048'],
            'order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('logo')) {
            if ($client->logo) {
                Storage::disk('public')->delete($client->logo);
            }
            $data['logo'] = $request->file('logo')->store('clients', 'public');
        } else {
            unset($data['logo']);
        }

        $client->update($data);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(Client $client)
    {
        if ($client->logo) {
            Storage::disk('public')->delete($client->logo);
        }
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Klien berhasil dihapus.');
    }
}
