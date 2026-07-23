@extends('layouts.adminlte')

@section('title', 'Detalle de Cotización ' . $quote->folio)
@section('page_title', ' Cotización ' . $quote->folio)

@section('content')
<div class="row">
    <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('quotes.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver al Listado
        </a>
        <div>
            <!-- Botón PDF con icono de Hoja -->
            <a href="{{ route('quotes.pdf', $quote->id) }}" target="_blank" class="btn btn-danger btn-sm me-1">
                <i class="bi bi-file-earmark-pdf me-1"></i> PDF
            </a>

            {{-- Solo el Administrador puede editar --}}
            @if(auth()->user()->isAdmin())
                <a href="{{ route('quotes.edit', $quote->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil me-1"></i> Editar
                </a>
            @endif
        </div>
    </div>

    <!-- Encabezado de la Cotización -->
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="row border-bottom pb-3 mb-3">
                    <div class="col-sm-6">
                        <h4 class="text-primary fw-bold mb-1">{{ $quote->folio }}</h4>
                        <span class="badge bg-{{ $quote->status == 'borrador' ? 'warning' : ($quote->status == 'enviada' ? 'info' : 'success') }} fs-6">
                            {{ ucfirst($quote->status) }}
                        </span>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                        <p class="mb-1 text-muted"><strong>Fecha Emisión:</strong> {{ \Carbon\Carbon::parse($quote->issue_date)->format('d/m/Y') }}</p>
                        <p class="mb-0 text-muted"><strong>Válida Hasta:</strong> {{ $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                </div>

                <!-- Datos del Cliente -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-person me-1 text-primary"></i> Datos del Cliente</h6>
                        <ul class="list-unstyled mb-0 text-muted">
                            <li class="text-dark fw-bold fs-6">{{ $quote->client->business_name ?? 'Cliente N/A' }}</li>
                            <li><strong>Contacto:</strong> {{ $quote->client->contact_name ?? 'N/A' }}</li>
                            <li><strong>Teléfono:</strong> {{ $quote->client->phone ?? 'N/A' }}</li>
                            <li><strong>Correo:</strong> {{ $quote->client->email ?? 'N/A' }}</li>
                            @if(!empty($quote->client->rfc))
                                <li><strong>RFC:</strong> {{ $quote->client->rfc }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Ítems / Conceptos -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-list-check me-1 text-primary"></i> Conceptos Cotizados</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Concepto / Descripción</th>
                                <th class="text-center">Cant.</th>

                                {{-- Columnas reservadas exclusivamente para el Administrador --}}
                                @if(auth()->user()->isAdmin())
                                    <th class="text-end">Costo Base</th>
                                    <th class="text-center">Utilidad</th>
                                @endif

                                <th class="text-end">P. Unitario</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quote->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $item->concept }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>

                                    {{-- Valores reservados exclusivamente para el Administrador --}}
                                    @if(auth()->user()->isAdmin())
                                        <td class="text-end text-muted">${{ number_format($item->cost_price, 2) }}</td>
                                        <td class="text-center"><span class="badge bg-light text-dark border">{{ $item->margin_percentage }}%</span></td>
                                    @endif

                                    <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end fw-bold">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->isAdmin() ? 7 : 5 }}" class="text-center py-3 text-muted">
                                        Sin conceptos en esta cotización.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Notas y Desglose de Totales -->
    <div class="col-md-7 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-file-text me-1 text-primary"></i> Notas y Condiciones</h6>
                <p class="text-muted mb-0" style="white-space: pre-line;">
                    {{ $quote->notes ?? 'Sin condiciones o notas adicionales.' }}
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-5 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center fs-4 fw-bold text-dark">
                    <span>Total:</span>
                    <span class="text-success">${{ number_format($quote->subtotal, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, .main-sidebar, .main-footer {
        display: none !important;
    }
    .content-wrapper {
        margin-left: 0 !important;
        background: white !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
