<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteShipped;

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
        $nextId = Quote::count() + 1;
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
            'client_id'  => 'required',
            'folio'      => 'required',
            'issue_date' => 'required|date',
            'items'      => 'required|array|min:1',
        ]);

        // 1. Calculamos primero el subtotal de todos los items
        $subtotal = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $qty       = (int) $item['quantity'];
            $cost      = (float) $item['cost_price'];
            $margin    = (float) $item['margin_percentage'];
            $unitPrice = (float) $item['unit_price'];
            $itemSub   = $unitPrice * $qty;
            $subtotal += $itemSub;

            $itemsData[] = [
                'product_id'        => $item['product_id'] ?? null,
                'concept'           => $item['concept'],
                'quantity'          => $qty,
                'cost_price'        => $cost,
                'margin_percentage' => $margin,
                'unit_price'        => $unitPrice,
                'subtotal'          => $itemSub,
            ];
        }

        // 2. Creación directa del documento Quote (Sin DB::transaction)
        $quote = Quote::create([
            'folio'       => $request->folio,
            'client_id'   => $request->client_id,
            'status'      => 'borrador',
            'issue_date'  => $request->issue_date,
            'valid_until' => $request->valid_until,
            'notes'       => $request->notes,
            'subtotal'    => $subtotal,
            'tax'         => 0,
            'total'       => $subtotal,
        ]);

        // 3. Crear o adjuntar los items de la cotización
        if (method_exists($quote, 'items')) {
            foreach ($itemsData as $item) {
                $quote->items()->create($item);
            }
        }

        return redirect()->route('quotes.index')->with('success', 'Cotización guardada exitosamente.');
    }

    public function show($id)
    {
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
        
        if (method_exists($quote, 'items')) {
            $quote->items()->delete();
        }
        
        $quote->delete();

        return redirect()->route('quotes.index')->with('success', 'Cotización eliminada correctamente.');
    }

    public function edit(Quote $quote)
    {
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
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para editar cotizaciones.');
        }

        $request->validate([
            'client_id'  => 'required',
            'issue_date' => 'required|date',
            'items'      => 'required|array|min:1',
        ]);

        $grandSubtotal = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $cost      = (float) $item['cost_price'];
            $margin    = (float) $item['margin_percentage'];
            $unitPrice = (float) $item['unit_price'];
            $qty       = (int) $item['quantity'];
            $subtotal  = $unitPrice * $qty;

            $grandSubtotal += $subtotal;

            $itemsData[] = [
                'product_id'        => $item['product_id'] ?? null,
                'concept'           => $item['concept'],
                'quantity'          => $qty,
                'cost_price'        => $cost,
                'margin_percentage' => $margin,
                'unit_price'        => $unitPrice,
                'subtotal'          => $subtotal,
            ];
        }

        $quote->update([
            'client_id'   => $request->client_id,
            'issue_date'  => $request->issue_date,
            'valid_until' => $request->valid_until,
            'notes'       => $request->notes,
            'subtotal'    => $grandSubtotal,
            'tax'         => 0,
            'total'       => $grandSubtotal,
        ]);

        if (method_exists($quote, 'items')) {
            $quote->items()->delete();
            foreach ($itemsData as $item) {
                $quote->items()->create($item);
            }
        }

        return redirect()->route('quotes.show', $quote->id)
            ->with('success', 'Cotización actualizada correctamente.');
    }

    public function pdf(Quote $quote)
    {
        $quote->load('items', 'client');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('quotes.pdf', compact('quote'));
        return $pdf->stream('Cotizacion_' . $quote->folio . '.pdf');
    }

    public function sendEmail(Quote $quote)
    {
        // Cargar la relación del cliente para obtener su email
        $quote->load('client');

        if (!$quote->client || !$quote->client->email) {
            return redirect()->back()->with('error', 'El cliente no tiene un correo electrónico registrado.');
        }

        try {
            // Enviar correo
            Mail::to($quote->client->email)->send(new QuoteShipped($quote));

            // Actualizar estatus a "enviada"
            $quote->update(['status' => 'enviada']);

            return redirect()->back()->with('success', 'Cotización enviada exitosamente a ' . $quote->client->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al enviar el correo: ' . $e->getMessage());
        }
    }
}