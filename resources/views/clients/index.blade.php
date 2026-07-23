@extends('layouts.adminlte')

@section('title', 'Clientes')
@section('page_title', 'Clientes')

@section('content')
<style>
    .card-custom {
        border: none;
        border-top: 3px solid #8b5cf6;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .badge-status-active {
        background-color: #22c55e;
        color: white;
        font-weight: 500;
        font-size: 0.75rem;
        padding: 4px 12px;
        border-radius: 50rem;
    }
    .badge-status-inactive {
        background-color: #ef4444;
        color: white;
        font-weight: 500;
        font-size: 0.75rem;
        padding: 4px 12px;
        border-radius: 50rem;
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
</style>

<!-- Tag Administrador Global -->
<div class="d-flex justify-content-end mb-3">
    <span class="badge bg-dark px-3 py-2 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">
        <i class="bi bi-globe me-1"></i> Administrador Global
    </span>
</div>

<div class="card card-custom">
    <div class="card-body p-4">

        <!-- Header Tarjeta -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex align-items-center">
                <div class="p-2 text-white rounded-3 me-2" style="background-color: #8b5cf6;">
                    <i class="bi bi-person-square fs-5"></i>
                </div>
                <h5 class="fw-bold mb-0 text-dark">Catálogo de Clientes</h5>
            </div>

            <div class="d-flex align-items-center gap-2 w-100 w-md-auto" style="max-width: 500px;">
                <!-- Filtro Instantáneo -->
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="tableSearch" class="form-control border-start-0 ps-0" placeholder="Buscar cliente o contacto...">
                </div>

                <!-- Botón Nuevo -->
                <button type="button" class="btn btn-purple text-nowrap" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg me-1"></i> Nuevo Cliente
                </button>
            </div>
        </div>

        <!-- Tabla Clientes -->
        <div class="table-responsive">
            <table class="table table-custom align-middle" id="dataTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>NOMBRE DE EMPRESA / CLIENTE</th>
                        <th>CONTACTO</th>
                        <th>TELÉFONO</th>
                        <th>CORREO</th>
                        <th class="text-center">ESTADO</th>
                        <th class="text-end">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td class="fw-bold text-dark">{{ $client->id }}</td>
                        <td class="text-uppercase fw-semibold text-dark">{{ $client->business_name }}</td>
                        <td>{{ $client->contact_name ?? '-' }}</td>
                        <td>{{ $client->phone ?? '-' }}</td>
                        <td>{{ $client->email ?? '-' }}</td>
                        <td class="text-center">
                            @if(($client->status ?? 'activo') == 'activo')
                                <span class="badge-status-active">Activo</span>
                            @else
                                <span class="badge-status-inactive">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <!-- Botón Editar -->
                            <button class="btn btn-action-edit me-1"
                                    title="Editar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $client->id }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>

                            <!-- Botón Eliminar -->
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desea eliminar este registro?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action-delete" title="Eliminar">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- MODAL EDITAR CLIENTE -->
                    <div class="modal fade" id="editModal{{ $client->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-pencil-square text-primary me-2"></i>Editar Cliente #{{ $client->id }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('clients.update', $client->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body text-start p-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Nombre / Empresa *</label>
                                            <input type="text" name="business_name" class="form-control" value="{{ $client->business_name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Persona de Contacto *</label>
                                            <input type="text" name="contact_name" class="form-control" value="{{ $client->contact_name }}" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-medium">Teléfono *</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $client->phone }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-medium">Estado</label>
                                                <select name="status" class="form-select">
                                                    <option value="activo" {{ ($client->status ?? 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                                                    <option value="inactivo" {{ ($client->status ?? '') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Correo Electrónico</label>
                                            <input type="email" name="email" class="form-control" value="{{ $client->email }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Dirección Física</label>
                                            <textarea name="address" class="form-control" rows="2">{{ $client->address }}</textarea>
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
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i> No se encontraron registros.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-end mt-3">
            {{ $clients->links() }}
        </div>

    </div>
</div>

<!-- MODAL NUEVO CLIENTE -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-plus-fill me-2" style="color: #8b5cf6;"></i>Nuevo Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre de la Empresa / Cliente *</label>
                        <input type="text" name="business_name" class="form-control" placeholder="Ej: Modelorama Zapata" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Persona de Contacto *</label>
                        <input type="text" name="contact_name" class="form-control" placeholder="Ej: Juani" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Teléfono *</label>
                            <input type="text" name="phone" class="form-control" placeholder="6677976114" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Estado</label>
                            <select name="status" class="form-select">
                                <option value="activo" selected>Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Dirección Física</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Calle, Número, Colonia..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-purple px-4">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script Filtrado -->
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
