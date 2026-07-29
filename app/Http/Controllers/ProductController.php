<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search'));

        $products = Product::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                // Sintaxis nativa y segura para regex en mongodb/laravel-mongodb
                $q->where('name', 'regex', "/{$search}/i")
                  ->orWhere('brand', 'regex', "/{$search}/i")
                  ->orWhere('description', 'regex', "/{$search}/i");
            });
        })->latest()->paginate(10)->appends(['search' => $search]);

        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'brand'         => 'nullable|string|max:100',
            'cost_price'    => 'required|numeric|min:0',
            'has_margin'    => 'nullable|boolean',
            'supplier_link' => 'nullable|url|max:255',
            'description'   => 'nullable|string',
        ]);

        $validatedData['has_margin'] = $request->boolean('has_margin');

        Product::create($validatedData);

        return redirect()->route('products.index')->with('success', 'Producto registrado correctamente.');
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'brand'         => 'nullable|string|max:100',
            'cost_price'    => 'required|numeric|min:0',
            'has_margin'    => 'nullable|boolean',
            'supplier_link' => 'nullable|url|max:255',
            'description'   => 'nullable|string',
        ]);

        $validatedData['has_margin'] = $request->boolean('has_margin');

        $product->update($validatedData);

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente.');
    }
}