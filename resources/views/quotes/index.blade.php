@extends('layouts.adminlte')

@section('title', 'Cotizaciones')
@section('page_title', 'Listado de Cotizaciones')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark fw-bold">Cotizaciones</h5>
                <a href="{{ route('quotes.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i> Nueva Cotización
                </a>
            </div>
            <div class="card-body p-0">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Fecha Emisión</th>
                                <th>Total</th>
                                <th>Estatus</th>
                                <th class="text-end me-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quotes as $quote)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $quote->folio }}</td>
                                    <td>{{ $quote->client->business_name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($quote->issue_date)->format('d/m/Y') }}</td>
                                    <td class="fw-bold">${{ number_format($quote->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $quote->status == 'borrador' ? 'warning' : ($quote->status == 'enviada' ? 'info' : 'success') }}">
                                            {{ ucfirst($quote->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            {{-- Botón Ver --}}
                                            <a href="{{ route('quotes.show', $quote->id) }}" class="btn btn-outline-info" title="Ver Detalle">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            {{-- Botón Editar --}}
                                            <a href="{{ route('quotes.edit', $quote->id) }}" class="btn btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            {{-- Botón Eliminar --}}
                                            <form action="{{ route('quotes.destroy', $quote->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta cotización?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No hay cotizaciones registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($quotes->hasPages())
                <div class="card-footer bg-white">
                    {{ $quotes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
