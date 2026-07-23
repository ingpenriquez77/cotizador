<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'brand'         => 'AMD',
                'name'          => 'Procesador AMD Ryzen 3 3200G',
                'description'   => 'Procesador AMD Ryzen 3 3200G con AMD Radeon Graphics, Socket AM4, 4GHz, 4 Núcleos, 4MB Caché - Incluye Disipador',
                'cost_price'    => 1949.00,
                'has_margin'    => true,
                'supplier_link' => 'https://www.cyberpuerta.mx/Computo-Hardware/Componentes/Procesadores/Procesadores-para-PC/Procesador-AMD-Ryzen-3-3200G-con-AMD-Radeon-Graphics-Socket-AM4-4GHz-4-Nucleos-4MB-Cache-Incluye-Disipador.html',
            ],
            [
                'brand'         => 'ASUS',
                'name'          => 'Tarjeta Madre ASUS PRIME A520M-K',
                'description'   => 'Tarjeta Madre ASUS PRIME A520M-K, Micro-ATX, Socket AM4, AMD A520, 64GB DDR4, HDMI para AMD',
                'cost_price'    => 839.00,
                'has_margin'    => true,
                'supplier_link' => 'https://www.cyberpuerta.mx/Computo-Hardware/Componentes/Tarjetas-Madre/Tarjeta-Madre-ASUS-PRIME-A520M-K-Micro-ATX-Socket-AM4-AMD-A520-64GB-DDR4-HDMI-para-AMD.html',
            ],
            [
                'brand'         => 'XPG',
                'name'          => 'Memoria RAM XPG GAMMIX D35 DDR4 8GB',
                'description'   => 'Memoria RAM XPG GAMMIX D35 DDR4, 3200MHz, 8GB, CL16, XMP',
                'cost_price'    => 1189.00,
                'has_margin'    => true,
                'supplier_link' => 'https://www.cyberpuerta.mx/Computo-Hardware/Memorias-RAM-y-Flash/Memorias-RAM-para-PC/Memoria-RAM-XPG-GAMMIX-D35-DDR4-3200MHz-8GB-CL16-XMP.html',
            ],
            [
                'brand'         => 'Kingston',
                'name'          => 'SSD Kingston SNV3S NVMe 500GB',
                'description'   => 'SSD Kingston SNV3S NVMe, 500GB, M.2, 3000 MB/s Escritura, 5000 MB/s Lectura, PCI Express 4.0',
                'cost_price'    => 1809.00,
                'has_margin'    => true,
                'supplier_link' => 'https://www.cyberpuerta.mx/Computo-Hardware/Discos-Duros-SSD-NAS/SSD/SSD-Kingston-SNV3S-NVMe-500GB-M-2-3000-MB-s-Escritura-5000-MB-s-Lectura-PCI-Express-4-0-1.html',
            ],
            [
                'brand'         => 'Acteck',
                'name'          => 'Gabinete Acteck Performance II GI215',
                'description'   => 'Gabinete Acteck Performance II GI215, Micro-Tower, Micro-ATX/Mini-ITX, USB 2.0, con Fuente de 500W, sin Ventiladores Instalados, Negro',
                'cost_price'    => 475.00,
                'has_margin'    => true,
                'supplier_link' => 'https://www.cyberpuerta.mx/Computo-Hardware/Componentes/Gabinetes/Gabinete-Acteck-Performance-II-GI215-Micro-Tower-Micro-ATX-Mini-ITX-USB-2-0-con-Fuente-de-500W-sin-Ventiladores-Instalados-Negro.html',
            ],
            [
                'brand'         => 'Acteck',
                'name'          => 'Monitor Acteck Captive Lite CL215 LCD 21.5"',
                'description'   => 'Monitor Acteck Captive Lite CL215 LCD 21.5", 1920x1080 Full HD, 60Hz, HDMI, Bocinas Integradas, Negro',
                'cost_price'    => 749.00,
                'has_margin'    => true,
                'supplier_link' => 'https://www.cyberpuerta.mx/Computo-Hardware/Monitores/Monitores/Monitor-Acteck-Captive-Lite-CL215-LCD-21-5-1920x1080-Full-HD-60Hz-HDMI-Bocinas-Integradas-Negro.html',
            ],
            [
                'brand'         => 'Logitech',
                'name'          => 'Kit de Teclado y Mouse Logitech MK120',
                'description'   => 'Kit de Teclado y Mouse Logitech MK120, Alámbrico, USB, Negro, Español',
                'cost_price'    => 309.00,
                'has_margin'    => true,
                'supplier_link' => 'https://www.cyberpuerta.mx/Computo-Hardware/Dispositivos-de-Entrada/Kits-de-Teclado-y-Mouse/Kit-de-Teclado-y-Mouse-Logitech-MK120-Alambrico-USB-Negro-Espanol-2.html',
            ],
            [
                'brand'         => 'EC Line',
                'name'          => 'Cajón de Dinero EC Line EC-G5100-II-GREY',
                'description'   => 'Cajón de Dinero EC Line EC-G5100-II-GREY, Negro',
                'cost_price'    => 799.00,
                'has_margin'    => true,
                'supplier_link' => 'https://www.cyberpuerta.mx/Punto-de-Venta-POS/Cajones-de-dinero/Cajon-de-Dinero-EC-Line-EC-G5100-II-GREY-Negro.html',
            ],
            [
                'brand'         => 'Evotec',
                'name'          => 'Impresora de Tickets Evotec EV-3005',
                'description'   => 'Evotec EV-3005 Impresora de Tickets, Térmica Directa, USB, 203 x 203 DPI',
                'cost_price'    => 859.00,
                'has_margin'    => true,
                'supplier_link' => 'https://www.cyberpuerta.mx/Punto-de-Venta-POS/Impresoras-de-Tickets/Evotec-EV-3005-Impresora-de-Tickets-Termica-Directa-USB-203-x-203-DPI.html',
            ],
            [
                'brand'         => 'Vorago',
                'name'          => 'No Break Vorago UPS-301 Offline 800 VA',
                'description'   => 'No Break Vorago UPS-301 Offline, 480W, 800 VA, Entrada 110V - 120V, Salida 110V - 120V',
                'cost_price'    => 949.00,
                'has_margin'    => true,
                'supplier_link' => 'https://www.cyberpuerta.mx/Energia/Proteccion-Contra-Descargas/No-Break-UPS/No-Break-UPS/No-Break-Vorago-UPS-301-Offline-480W-800-VA-Entrada-110V-120V-Salida-110V-120V.html',
            ],
            [
                'brand'         => 'eleventa',
                'name'          => 'eleventa Punto de Venta MonoCaja',
                'description'   => 'eleventa Punto de Venta MonoCaja (Licencia Anual)',
                'cost_price'    => 1499.00,
                'has_margin'    => false, // Licencia a precio sugerido fijo
                'supplier_link' => 'https://eleventa.com/comprar',
            ],
            [
                'brand'         => 'Servicio',
                'name'          => 'Capacitación (10 Horas)',
                'description'   => 'Capacitación de uso del sistema (10 Horas)',
                'cost_price'    => 3000.00,
                'has_margin'    => false,
                'supplier_link' => null,
            ],
            [
                'brand'         => 'Servicio',
                'name'          => 'Instalación',
                'description'   => 'Servicio de instalación y configuración de equipos',
                'cost_price'    => 500.00,
                'has_margin'    => false,
                'supplier_link' => null,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
