<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $products = Product::when($search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('brand', 'like', "%{$search}%")
                         ->orWhere('description', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'brand'         => 'nullable|string|max:100',
            'cost_price'    => 'required|numeric|min:0',
            'has_margin'    => 'nullable|boolean',
            'supplier_link' => 'nullable|url|max:255',
            'description'   => 'nullable|string',
        ]);

        $data = $request->all();
        $data['has_margin'] = $request->has('has_margin');

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Producto registrado correctamente.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'brand'         => 'nullable|string|max:100',
            'cost_price'    => 'required|numeric|min:0',
            'has_margin'    => 'nullable|boolean',
            'supplier_link' => 'nullable|url|max:255',
            'description'   => 'nullable|string',
        ]);

        $data = $request->all();
        $data['has_margin'] = $request->has('has_margin');

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente.');
    }
}
