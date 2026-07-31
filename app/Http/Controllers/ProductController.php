<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search'));

        // Carga ansiosa (eager loading) de la categoría para optimizar consultas
        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    // Sintaxis nativa y segura para regex en mongodb/laravel-mongodb
                    $q->where('name', 'regex', "/{$search}/i")
                      ->orWhere('brand', 'regex', "/{$search}/i")
                      ->orWhere('description', 'regex', "/{$search}/i");
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        // Cargar todas las categorías para los modales de Crear/Editar
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id'   => 'nullable|string',
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
            'category_id'   => 'nullable|string',
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

    /**
     * Realiza el Web Scraping para auto-completar la información del producto mediante una URL
     */
    /**
     * Realiza el Web Scraping para auto-completar la información del producto mediante una URL
     */
    /**
     * Realiza el Web Scraping para auto-completar la información del producto mediante una URL
     */
    public function scrapeUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        try {
            // Realizar petición simulando un navegador real
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'es-MX,es;q=0.9,en-US;q=0.8,en;q=0.7'
            ])->timeout(12)->get($request->url);

            if ($response->failed()) {
                return response()->json(['error' => 'No se pudo acceder a la URL del proveedor.'], 422);
            }

            $html = $response->body();

            // Cargar y parsear HTML con DOMDocument
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
            $xpath = new \DOMXPath($dom);
            libxml_clear_errors();

            // 1. Extraer Título (Producto)
            $title = '';
            $ogTitle = $xpath->query('//meta[@property="og:title"]/@content');
            if ($ogTitle->length > 0) {
                $title = $ogTitle->item(0)->nodeValue;
            } else {
                $h1Nodes = $xpath->query('//h1');
                if ($h1Nodes->length > 0) {
                    $title = $h1Nodes->item(0)->nodeValue;
                }
            }
            $cleanTitle = html_entity_decode(trim($title));

            // 2. Extraer Descripción
            $description = '';
            $ogDesc = $xpath->query('//meta[@property="og:description"]/@content');
            if ($ogDesc->length > 0) {
                $description = $ogDesc->item(0)->nodeValue;
            }

            // 3. Extraer Precio
            $price = 0.00;
            $cyberpuertaPriceNodes = $xpath->query('//*[contains(@class, "pdp-price-info__price")]//h2 | //*[contains(@class, "priceMain")]');
            
            if ($cyberpuertaPriceNodes->length > 0) {
                $rawPriceText = $cyberpuertaPriceNodes->item(0)->nodeValue;
                $cleanPrice = preg_replace('/[^\d.]/', '', str_replace(',', '', $rawPriceText));
                $price = floatval($cleanPrice);
            }

            if ($price <= 0) {
                $priceMeta = $xpath->query('//meta[@property="product:price:amount"]/@content | //meta[@property="og:price:amount"]/@content');
                if ($priceMeta->length > 0) {
                    $price = floatval($priceMeta->item(0)->nodeValue);
                }
            }

            // 4. Extraer Marca
            $brand = '';
            $brandMeta = $xpath->query('//meta[@property="product:brand"]/@content');
            if ($brandMeta->length > 0) {
                $brand = $brandMeta->item(0)->nodeValue;
            } else {
                if (preg_match('/(AMD|Intel|Asus|Kingston|Logitech|HP|Dell|Lenovo|Acer|MSI|Gigabyte|XPG|Adata|Corsair|Samsung|Acteck|Vorago|EC Line|Evotec)/i', $cleanTitle, $matches)) {
                    $brand = strtoupper($matches[0]);
                }
            }

            // 5. Inferencia Inteligente de Categoría por palabras clave
            $categoryId = null;
            $categories = Category::all();

            foreach ($categories as $cat) {
                // Generar palabras clave a buscar según el nombre de la categoría
                $keywords = [];
                $nameLower = mb_strtolower($cat->name);

                if (str_contains($nameLower, 'procesador')) $keywords = ['procesador', 'ryzen', 'core i3', 'core i5', 'core i7', 'core i9', 'athlon'];
                elseif (str_contains($nameLower, 'madre') || str_contains($nameLower, 'motherboard')) $keywords = ['tarjeta madre', 'motherboard', 'placa base'];
                elseif (str_contains($nameLower, 'ram')) $keywords = ['memoria ram', 'ddr4', 'ddr5', 'dimm', 'so-dimm'];
                elseif (str_contains($nameLower, 'almacenamiento') || str_contains($nameLower, 'ssd')) $keywords = ['ssd', 'disco duro', 'nvme', 'm.2'];
                elseif (str_contains($nameLower, 'gabinete')) $keywords = ['gabinete', 'chasis', 'case'];
                elseif (str_contains($nameLower, 'monitor')) $keywords = ['monitor', 'pantalla'];
                elseif (str_contains($nameLower, 'teclado') || str_contains($nameLower, 'mouse') || str_contains($nameLower, 'kit')) $keywords = ['kit de teclado', 'teclado', 'mouse', 'raton'];
                elseif (str_contains($nameLower, 'punto de venta') || str_contains($nameLower, 'pos')) $keywords = ['cajon de dinero', 'impresora de tickets', 'lector de codigo', 'punto de venta', 'miniprinter'];
                elseif (str_contains($nameLower, 'break') || str_contains($nameLower, 'ups')) $keywords = ['no break', 'ups', 'regulador'];
                else $keywords = [mb_strtolower($cat->name)];

                foreach ($keywords as $kw) {
                    if (str_contains(mb_strtolower($cleanTitle), $kw)) {
                        $categoryId = (string) $cat->id;
                        break 2;
                    }
                }
            }

            return response()->json([
                'success'     => true,
                'name'        => $cleanTitle,
                'brand'       => trim($brand),
                'description' => html_entity_decode(trim($description)),
                'cost_price'  => $price > 0 ? $price : null,
                'category_id' => $categoryId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al analizar la página: ' . $e->getMessage()
            ], 500);
        }
    }
}