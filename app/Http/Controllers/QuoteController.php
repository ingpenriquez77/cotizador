<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('client')->latest()->paginate(10);
        return view('quotes.index', compact('quotes'));
    }

    public function create()
    {
        // Protección de rol: Solo Administrador
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para crear cotizaciones.');
        }

        $clients = Client::orderBy('business_name')->get();
        $products = Product::orderBy('name')->get();

        // Generador de folio consecutivo dinámico (Ej: COT-2026-001)
        $nextId = Quote::max('id') + 1;
        $folio = 'COT-' . date('Y') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('quotes.create', compact('clients', 'products', 'folio'));
    }

    public function store(Request $request)
    {
        // Protección de rol: Solo Administrador
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para crear cotizaciones.');
        }

        $request->validate([
            'client_id'  => 'required|exists:clients,id',
            'folio'      => 'required|unique:quotes,folio',
            'issue_date' => 'required|date',
            'items'      => 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;

            $quote = Quote::create([
                'folio'       => $request->folio,
                'client_id'   => $request->client_id,
                'status'      => 'borrador',
                'issue_date'  => $request->issue_date,
                'valid_until' => $request->valid_until,
                'notes'       => $request->notes,
                'subtotal'    => 0,
                'tax'         => 0,
                'total'       => 0,
            ]);

            foreach ($request->items as $item) {
                $qty        = (int) $item['quantity'];
                $cost       = (float) $item['cost_price'];
                $margin     = (float) $item['margin_percentage'];

                // Precio unitario derivado del costo + % de margen
                $unitPrice  = (float) $item['unit_price'];
                $itemSub    = $unitPrice * $qty;
                $subtotal  += $itemSub;

                $quote->items()->create([
                    'product_id'        => $item['product_id'] ?? null,
                    'concept'           => $item['concept'],
                    'quantity'          => $qty,
                    'cost_price'        => $cost,
                    'margin_percentage' => $margin,
                    'unit_price'        => $unitPrice,
                    'subtotal'          => $itemSub,
                ]);
            }

            // Sin IVA: subtotal = total
            $quote->update([
                'subtotal' => $subtotal,
                'tax'      => 0,
                'total'    => $subtotal,
            ]);
        });

        return redirect()->route('quotes.index')->with('success', 'Cotización guardada exitosamente.');
    }

    public function show($id)
    {
        // Permitido para todos los usuarios autenticados
        $quote = Quote::with(['client', 'items.product'])->findOrFail($id);
        return view('quotes.show', compact('quote'));
    }

    public function destroy($id)
    {
        // Protección de rol: Solo Administrador
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para eliminar cotizaciones.');
        }

        $quote = Quote::findOrFail($id);
        $quote->items()->delete();
        $quote->delete();

        return redirect()->route('quotes.index')->with('success', 'Cotización eliminada correctamente.');
    }

    public function edit(Quote $quote)
    {
        // Protección de rol: Solo Administrador
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para editar cotizaciones.');
        }

        $quote->load('items');
        $clients = Client::all();
        $products = Product::all();

        return view('quotes.edit', compact('quote', 'clients', 'products'));
    }

    public function update(Request $request, Quote $quote)
    {
        // Protección de rol: Solo Administrador
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para editar cotizaciones.');
        }

        $request->validate([
            'client_id'  => 'required',
            'issue_date' => 'required|date',
            'items'      => 'required|array|min:1',
        ]);

        $quote->update([
            'client_id'   => $request->client_id,
            'issue_date'  => $request->issue_date,
            'valid_until' => $request->valid_until,
            'notes'       => $request->notes,
        ]);

        $quote->items()->delete();

        $grandSubtotal = 0;

        foreach ($request->items as $item) {
            $cost      = (float) $item['cost_price'];
            $margin    = (float) $item['margin_percentage'];
            $unitPrice = (float) $item['unit_price'];
            $qty       = (int) $item['quantity'];

            $subtotal       = $unitPrice * $qty;
            $grandSubtotal += $subtotal;

            $quote->items()->create([
                'product_id'        => $item['product_id'] ?? null,
                'concept'           => $item['concept'],
                'quantity'          => $qty,
                'cost_price'        => $cost,
                'margin_percentage' => $margin,
                'unit_price'        => $unitPrice,
                'subtotal'          => $subtotal,
            ]);
        }

        $quote->update([
            'subtotal' => $grandSubtotal,
            'tax'      => 0,
            'total'    => $grandSubtotal,
        ]);

        return redirect()->route('quotes.show', $quote->id)
            ->with('success', 'Cotización actualizada correctamente.');
    }

    public function pdf(Quote $quote)
    {
        // Permitido para todos los usuarios autenticados
        $quote->load('items', 'client');
        $pdf = Pdf::loadView('quotes.pdf', compact('quote'));
        return $pdf->stream('Cotizacion_' . $quote->folio . '.pdf');
    }
}
