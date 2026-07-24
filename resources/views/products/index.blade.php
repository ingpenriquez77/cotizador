@extends('layouts.adminlte')

@section('title', 'Catálogo de Productos')
@section('page_title', 'Productos')

@section('content')
<style>
    .card-custom {
        border: none;
        border-top: 3px solid #8b5cf6;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .badge-price-suggested {
        background-color: #dcfce7;
        color: #15803d;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid #bbf7d0;
    }
    .badge-price-neto {
        background-color: #f3f4f6;
        color: #374151;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }
    .badge-brand {
        background-color: #f3f4f6;
        color: #4b5563;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
    }
    .brand-logo-img {
        width: 26px;
        height: 26px;
        object-fit: contain;
        border-radius: 4px;
        background-color: #ffffff;
        padding: 2px;
        border: 1px solid #e5e7eb;
    }
    .table-custom th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #4b5563;
        font-weight: 700;
        border-bottom: 2px solid #f3f4f6;
    }
    .btn-purple {
        background-color: #1e1b4b;
        color: white;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 6px;
    }
    .btn-purple:hover {
        background-color: #312e81;
        color: white;
    }
    .btn-action-edit {
        border: 1px solid #0284c7;
        color: #0284c7;
        padding: 2px 6px;
        border-radius: 4px;
    }
    .btn-action-edit:hover {
        background-color: #0284c7;
        color: white;
    }
    .btn-action-delete {
        border: 1px solid #ef4444;
        color: #ef4444;
        padding: 2px 6px;
        border-radius: 4px;
    }
    .btn-action-delete:hover {
        background-color: #ef4444;
        color: white;
    }
    .btn-action-link {
        border: 1px solid #8b5cf6;
        color: #8b5cf6;
        padding: 2px 8px;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 4px;
    }
    .btn-action-link:hover {
        background-color: #8b5cf6;
        color: white;
    }
</style>

<!-- Tag Administrador Global -->
<div class="d-flex justify-content-end mb-3">
    <span class="badge bg-dark px-3 py-2 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">
        <i class="bi bi-globe me-1"></i> Administrador Global
    </span>
</div>

<!-- Alertas de éxito -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card card-custom">
    <div class="card-body p-4">

        <!-- Header Tarjeta -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex align-items-center">
                <div class="p-2 text-white rounded-3 me-2" style="background-color: #8b5cf6;">
                    <i class="bi bi-box-seam fs-5"></i>
                </div>
                <h5 class="fw-bold mb-0 text-dark">Catálogo de Productos e Insumos</h5>
            </div>

            <div class="d-flex align-items-center gap-2 w-100 w-md-auto" style="max-width: 500px;">
                <!-- Filtro Instantáneo -->
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="tableSearch" class="form-control border-start-0 ps-0" placeholder="Buscar por producto, marca...">
                </div>

                <!-- Botón Nuevo -->
                <button type="button" class="btn btn-purple text-nowrap" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
                </button>
            </div>
        </div>

        <!-- Tabla Productos -->
        <div class="table-responsive">
            <table class="table table-custom align-middle" id="dataTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>PRODUCTO / DESCRIPCIÓN</th>
                        <th>MARCA</th>
                        <th>COSTO BASE</th>
                        <th>PRECIO VENTA</th>
                        <th class="text-center">PROVEEDOR</th>
                        <th class="text-end">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="fw-bold text-dark">{{ $product->id }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $product->name }}</div>
                            @if($product->description)
                                <small class="text-muted d-block text-truncate" style="max-width: 300px;">{{ $product->description }}</small>
                            @endif
                        </td>
                        <td>
                            @if($product->brand)
                                @php
                                    // Mapeo con URLs directas completas con protocolo HTTPS
                                    $brandDomains = [
                                        'amd'       => 'amd.com',
                                        'asus'      => 'asus.com',
                                        'xpg'       => 'xpg.com',
                                        'kingston'  => 'kingston.com',
                                        'acteck'    => 'acteck.com',
                                        'logitech'  => 'logitech.com',
                                        'ec line'   => 'eclinepos.com',
                                        'evotec'    => 'grupoevotec.com.mx',
                                        'vorago'    => 'voragolive.com',
                                        'eleventa'  => 'eleventa.com',
                                    ];

                                    $brandKey = strtolower(trim($product->brand));
                                    $domain = $brandDomains[$brandKey] ?? null;

                                    // Si no está en el catálogo fijo, extraer dinámicamente el dominio del link del proveedor si existe
                                    if (!$domain && $product->supplier_link) {
                                        $parsedUrl = parse_url($product->supplier_link);
                                        $domain = $parsedUrl['host'] ?? null;
                                    }
                                @endphp

                                <div class="d-inline-flex align-items-center gap-2">
                                    @if($domain)
                                        {{-- Carga garantizada por HTTPS mediante Google Favicons API --}}
                                        <img src="https://www.google.com/s2/favicons?domain=https://{{ $domain }}&sz=64"
                                             alt="{{ $product->brand }}"
                                             class="brand-logo-img shadow-sm"
                                             style="width: 20px; height: 20px; object-fit: contain;"
                                             onerror="this.remove();">
                                    @endif
                                    <span class="badge-brand">{{ $product->brand }}</span>
                                </div>
                            @else
                                <span class="badge-brand">N/A</span>
                            @endif
                        </td>
                        <td class="fw-medium text-dark">${{ number_format($product->cost_price, 2) }}</td>
                        <td>
                            @if($product->has_margin)
                                <span class="badge-price-suggested" title="Incluye 30% de Utilidad">
                                    ${{ number_format($product->suggested_sale_price, 2) }} <small class="fw-bold">(+30%)</small>
                                </span>
                            @else
                                <span class="badge-price-neto" title="Precio Neto (Sin Utilidad)">
                                    ${{ number_format($product->suggested_sale_price, 2) }} <small class="fw-normal text-muted">(Neto)</small>
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($product->supplier_link)
                                <a href="{{ $product->supplier_link }}" target="_blank" class="btn btn-action-link text-decoration-none">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Link
                                </a>
                            @else
                                <span class="text-muted small">Sin enlace</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <!-- Botón Editar -->
                            <button class="btn btn-action-edit me-1"
                                    title="Editar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $product->id }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>

                            <!-- Botón Eliminar -->
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desea eliminar este producto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action-delete" title="Eliminar">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- MODAL EDITAR PRODUCTO -->
                    <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-pencil-square text-primary me-2"></i>Editar Producto #{{ $product->id }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('products.update', $product->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body text-start p-4">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-medium">Nombre del Producto / Insumo *</label>
                                                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-medium">Marca</label>
                                                <input type="text" name="brand" class="form-control" value="{{ $product->brand }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-medium">Precio de Costo Base ($) *</label>
                                                <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ $product->cost_price }}" required>
                                            </div>
                                            <div class="col-md-6 d-flex align-items-center">
                                                <div class="form-check form-switch mt-3">
                                                    <input class="form-check-input" type="checkbox" name="has_margin" value="1" id="hasMargin{{ $product->id }}" {{ $product->has_margin ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-medium" for="hasMargin{{ $product->id }}">
                                                        Aplicar margen de utilidad (+30%)
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-medium">Enlace al Proveedor</label>
                                                <input type="url" name="supplier_link" class="form-control" value="{{ $product->supplier_link }}">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-medium">Especificaciones / Descripción</label>
                                                <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-purple px-4">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i> No se encontraron productos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación con Bootstrap 5 -->
        @if($products->hasPages())
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-2">
                <small class="text-muted">
                    Mostrando {{ $products->firstItem() }} a {{ $products->lastItem() }} de {{ $products->total() }} registros
                </small>
                <div>
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif

    </div>
</div>

<!-- MODAL NUEVO PRODUCTO -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-box-seam-fill me-2" style="color: #8b5cf6;"></i>Nuevo Producto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Nombre del Producto / Insumo *</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej. Cámara IP 1080p" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Marca</label>
                            <input type="text" name="brand" class="form-control" placeholder="Ej. Hikvision, Cisco, Servicio">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Precio de Costo Base ($) *</label>
                            <input type="number" step="0.01" name="cost_price" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="has_margin" value="1" id="hasMarginCreate" checked>
                                <label class="form-check-label fw-medium" for="hasMarginCreate">
                                    Aplicar margen de utilidad (+30%)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Enlace al Proveedor (Opcional)</label>
                            <input type="url" name="supplier_link" class="form-control" placeholder="https://proveedor.com/producto">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Especificaciones / Descripción</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Detalles o características técnicas opcionales"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-purple px-4">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script Filtrado Instantáneo -->
<script>
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#dataTable tbody tr');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });
</script>
@endsection
