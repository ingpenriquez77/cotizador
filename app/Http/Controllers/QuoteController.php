<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('client')->latest()->paginate(10);
        return view('quotes.index', compact('quotes'));
    }

    public function create()
    {
        $clients = Client::orderBy('business_name')->get();
        $products = Product::orderBy('name')->get();

        // Generador de folio consecutivo dinámico (Ej: COT-2026-001)
        $nextId = Quote::max('id') + 1;
        $folio = 'COT-' . date('Y') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('quotes.create', compact('clients', 'products', 'folio'));
    }

    public function store(Request $request)
    {
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
                $unitPrice  = $cost * (1 + ($margin / 100));
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

            // Opcional: IVA del 16% (puedes ajustar o volverlo opcional)
            $tax   = $subtotal * 0.16;
            $total = $subtotal + $tax;

            $quote->update([
                'subtotal' => $subtotal,
                'tax'      => $tax,
                'total'    => $total,
            ]);
        });

        return redirect()->route('quotes.index')->with('success', 'Cotización guardada exitosamente.');
    }

    public function show($id)
    {
        $quote = Quote::with(['client', 'items.product'])->findOrFail($id);

        // Por el momento puedes retornar una vista vacía o DD para confirmar que funciona
        return view('quotes.show', compact('quote'));
    }

    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->items()->delete();
        $quote->delete();

        return redirect()->route('quotes.index')->with('success', 'Cotización eliminada correctamente.');
    }
}
