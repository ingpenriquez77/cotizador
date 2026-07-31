@extends('layouts.adminlte')

@section('title', 'Gestión de Categorías')
@section('page_title', 'Categorías de Productos')

@section('content')
<style>
    .card-custom {
        border: none;
        border-top: 3px solid #6366f1;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .icon-badge {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background-color: #e0e7ff;
        color: #4f46e5;
        font-size: 1.2rem;
    }
    .table-custom th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #4b5563;
        font-weight: 700;
        border-bottom: 2px solid #f3f4f6;
    }
    .btn-indigo {
        background-color: #4f46e5;
        color: white;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 6px;
    }
    .btn-indigo:hover {
        background-color: #4338ca;
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
</style>

<!-- Tag Administrador -->
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
                <div class="p-2 text-white rounded-3 me-2" style="background-color: #6366f1;">
                    <i class="bi bi-tags fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Categorías del Configurador</h5>
                    <small class="text-muted">Organiza los componentes e insumos para el generador de cotizaciones</small>
                </div>
            </div>

            <button type="button" class="btn btn-indigo text-nowrap ms-auto" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="bi bi-plus-lg me-1"></i> Nueva Categoría
            </button>
        </div>

        <!-- Tabla Categorías -->
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">ÍCONO</th>
                        <th>NOMBRE DE CATEGORÍA</th>
                        <th>TIPO / CONDICIÓN</th>
                        <th class="text-center">PRODUCTOS REGISTRADOS</th>
                        <th class="text-end">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>
                            <div class="icon-badge">
                                <i class="bi {{ $category->icon ?? 'bi-box-seam' }}"></i>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark fs-6">{{ $category->name }}</div>
                            <small class="text-muted">ID: {{ $category->id }}</small>
                        </td>
                        <td>
                            @if($category->is_optional)
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                    <i class="bi bi-info-circle me-1"></i> Opcional
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle px-2 py-1">
                                    <i class="bi bi-asterisk me-1"></i> Requerida
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border fs-6 px-3 py-1">
                                {{ $category->products_count ?? $category->products()->count() }} producto(s)
                            </span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-action-edit me-1"
                                    title="Editar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal{{ $category->id }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>

                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desea eliminar esta categoría?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action-delete" title="Eliminar">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- MODAL EDITAR CATEGORÍA -->
                    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-pencil-square text-primary me-2"></i>Editar Categoría
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-medium">Nombre de la Categoría *</label>
                                                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-medium">Ícono de Bootstrap (ej: bi-cpu, bi-memory)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white"><i class="bi {{ $category->icon ?? 'bi-box-seam' }}"></i></span>
                                                    <input type="text" name="icon" class="form-control" value="{{ $category->icon ?? 'bi-box-seam' }}" placeholder="bi-box-seam">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input" type="checkbox" name="is_optional" value="1" id="isOptionalEdit{{ $category->id }}" {{ $category->is_optional ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-medium" for="isOptionalEdit{{ $category->id }}">
                                                        Marcar como categoría opcional en el configurador
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-indigo px-4">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i> No hay categorías registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- MODAL NUEVA CATEGORÍA -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-tags-fill me-2" style="color: #6366f1;"></i>Nueva Categoría
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Nombre de la Categoría *</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej. Procesador, Tarjeta Madre" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Ícono Bootstrap Icons (Opcional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-box-seam"></i></span>
                                <input type="text" name="icon" class="form-control" placeholder="bi-cpu">
                            </div>
                            <small class="text-muted fs-7">Usa clases como <code>bi-cpu</code>, <code>bi-memory</code>, <code>bi-gpu-card</code>, <code>bi-hdd-rack</code>.</small>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_optional" value="1" id="isOptionalCreate">
                                <label class="form-check-label fw-medium" for="isOptionalCreate">
                                    Marcar como categoría opcional en el configurador
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-indigo px-4">Guardar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection