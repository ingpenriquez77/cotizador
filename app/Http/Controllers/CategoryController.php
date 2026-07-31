<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Muestra el listado de categorías.
     */
    public function index()
    {
        // Se remueve withCount() para compatibilidad directa con MongoDB
        $categories = Category::orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Almacena una nueva categoría.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'icon'        => 'nullable|string|max:100',
            'is_optional' => 'nullable|boolean',
        ]);

        $validatedData['is_optional'] = $request->boolean('is_optional');

        Category::create($validatedData);

        return redirect()->route('categories.index')->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Actualiza la categoría especificada.
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'icon'        => 'nullable|string|max:100',
            'is_optional' => 'nullable|boolean',
        ]);

        $validatedData['is_optional'] = $request->boolean('is_optional');

        $category->update($validatedData);

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Elimina una categoría.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'No es posible eliminar la categoría porque tiene productos asociados.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoría eliminada correctamente.');
    }
}