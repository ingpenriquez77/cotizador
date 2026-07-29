<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search'));

        // Búsqueda insensible a mayúsculas/minúsculas usando Regex en MongoDB
        $clients = Client::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('business_name', 'regex', "/{$search}/i")
                  ->orWhere('contact_name', 'regex', "/{$search}/i")
                  ->orWhere('email', 'regex', "/{$search}/i")
                  ->orWhere('phone', 'regex', "/{$search}/i")
                  ->orWhere('rfc', 'regex', "/{$search}/i");
            });
        })->latest()->paginate(10)->appends(['search' => $search]);

        // Si es una petición AJAX (filtrado instantáneo sin recargar pantalla)
        if ($request->ajax()) {
            return view('clients.index', compact('clients'))->render();
        }

        return view('clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_name'  => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'email'         => 'nullable|email|max:255',
            'status'        => 'required|in:activo,inactivo',
            'address'       => 'nullable|string',
            'rfc'           => 'nullable|string|max:13',
        ]);

        Client::create($validatedData);

        return redirect()->route('clients.index')->with('success', 'Cliente registrado correctamente.');
    }

    public function update(Request $request, Client $client)
    {
        $validatedData = $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_name'  => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'email'         => 'nullable|email|max:255',
            'status'        => 'required|in:activo,inactivo',
            'address'       => 'nullable|string',
            'rfc'           => 'nullable|string|max:13',
        ]);

        $client->update($validatedData);

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente eliminado correctamente.');
    }
}