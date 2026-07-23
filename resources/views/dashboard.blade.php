@extends('layouts.adminlte')

@section('title', 'Dashboard')
@section('page_title', 'Panel de Control')

@section('content')
<div class="row">
    <!-- Tarjeta Muestra: Clientes -->
    <div class="col-md-4 col-sm-6 col-12 mb-3">
        <div class="card shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary-subtle text-primary p-3 rounded-3">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted fw-normal mb-1">Clientes Registrados</h6>
                        <h4 class="mb-0 fw-bold">{{ \App\Models\Client::count() ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta Muestra: Productos -->
    <div class="col-md-4 col-sm-6 col-12 mb-3">
        <div class="card shadow-sm border-0 border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success-subtle text-success p-3 rounded-3">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted fw-normal mb-1">Productos en Catálogo</h6>
                        <h4 class="mb-0 fw-bold">{{ \App\Models\Product::count() ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta Muestra: Cotizaciones -->
    <div class="col-md-4 col-sm-6 col-12 mb-3">
        <div class="card shadow-sm border-0 border-start border-purple border-4" style="border-color: #8b5cf6 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 p-3 rounded-3" style="background-color: #f3e8ff; color: #8b5cf6;">
                        <i class="bi bi-file-earmark-text fs-3"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted fw-normal mb-1">Cotizaciones Emitidas</h6>
                        <h4 class="mb-0 fw-bold">0</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-3">
    <div class="card-body text-center py-5">
        <i class="bi bi-rocket-takeoff text-primary display-4"></i>
        <h3 class="mt-3 fw-bold">¡Bienvenido al Cotizador!</h3>
        <p class="text-muted mb-0">El sistema está listo para comenzar a enlazar tus clientes con el motor de cotizaciones.</p>
    </div>
</div>
@endsection
