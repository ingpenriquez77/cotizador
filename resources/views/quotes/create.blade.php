@extends('layouts.adminlte')

@section('title', 'Nueva Cotización')
@section('page_title', 'Configurador de Cotización')

@section('content')
<style>
    /* Estilos Cyberpuerta PC Builder */
    .quote-card {
        border-radius: 10px;
        border: 1px solid #e3e8ee;
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
        background: #fff;
    }
    .accordion-button:not(.collapsed) {
        background-color: #f0f7ff;
        color: #0d6efd;
        font-weight: 600;
    }
    .accordion-button {
        font-weight: 600;
        color: #334155;
        padding: 1rem;
    }
    .accordion-item {
        border: 1px solid #e2e8f0;
        border-radius: 8px !important;
        overflow: hidden;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }
    .sticky-summary {
        position: -webkit-sticky;
        position: sticky;
        top: 15px;
        z-index: 10;
    }
    .summary-box {
        background-color: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    .table-items th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        background-color: #f8fafc;
    }
    .client-badge {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
    }
    .main-sidebar {
        pointer-events: none !important;
        opacity: 0.5 !important;
    }
</style>

<form action="{{ route('quotes.store') }}" method="POST" id="quoteForm">
    @csrf
    <div class="row g-4">
        
        <!-- COLUMNA IZQUIERDA: Datos del cliente y Selección por Categorías (70%) -->
        <div class="col-lg-7">
            
            <!-- 1. Datos Principales (Folio, Cliente, Fechas) -->
            <div class="quote-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-text text-primary me-2"></i>Información del Cliente</h5>
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 fs-6 border border-primary-subtle">
                        Folio: {{ $folio }}
                    </span>
                    <input type="hidden" name="folio" value="{{ $folio }}">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">SELECCIONAR CLIENTE *</label>
                        <select name="client_id" id="selectClient" class="form-select" required>
                            <option value="">-- Buscar Empresa / Cliente --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" 
                                        data-contact="{{ $client->contact_name ?? $client->contacto ?? 'N/A' }}"
                                        data-phone="{{ $client->phone ?? $client->telefono ?? 'N/A' }}"
                                        data-email="{{ $client->email ?? $client->correo ?? 'N/A' }}"
                                        data-address="{{ $client->address ?? $client->direccion ?? 'N/A' }}"
                                        {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->business_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">FECHA EMISIÓN *</label>
                        <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">VÁLIDA HASTA</label>
                        <input type="date" name="valid_until" class="form-control" value="{{ date('Y-m-d', strtotime('+15 days')) }}">
                    </div>
                </div>

                <!-- Tarjeta Dinámica de Datos del Cliente Seleccionado -->
                <div class="client-badge p-3 mt-3" id="clientDetailsRow" style="display: none;">
                    <div class="row g-2 text-dark small">
                        <div class="col-md-6">
                            <i class="bi bi-person me-1 text-primary"></i> <strong>Contacto:</strong> <span id="clientContact">---</span>
                        </div>
                        <div class="col-md-6">
                            <i class="bi bi-telephone me-1 text-primary"></i> <strong>Teléfono:</strong> <span id="clientPhone">---</span>
                        </div>
                        <div class="col-md-6">
                            <i class="bi bi-envelope me-1 text-primary"></i> <strong>Email:</strong> <span id="clientEmail">---</span>
                        </div>
                        <div class="col-md-6">
                            <i class="bi bi-geo-alt me-1 text-primary"></i> <strong>Dirección:</strong> <span id="clientAddress">---</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Acordeón de Categorías de Productos Estilo Cyberpuerta -->
            <div class="quote-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-cpu text-primary me-2"></i>Configurador de Componentes</h5>
                        <small class="text-muted">Despliega una categoría para seleccionar los productos a cotizar</small>
                    </div>
                </div>

                @php
                    // Agrupar los productos por ID de categoría de MongoDB
                    $groupedProducts = $products->groupBy(function($prod) {
                        return $prod->category_id ? (string)$prod->category_id : 'unassigned';
                    });
                @endphp

                <div class="accordion" id="categoryAccordion">
                    @foreach($categories as $category)
                        @php
                            $prods = $groupedProducts->get((string)$category->id, collect());
                            $slug = Str::slug($category->name);
                            $icon = $category->icon ?? 'bi-box-seam';
                        @endphp

                        @if($prods->isNotEmpty())
                            <div class="accordion-item category-card" id="cat_card_{{ $category->id }}">
                                <h2 class="accordion-header" id="heading_{{ $slug }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $slug }}" aria-expanded="false" aria-controls="collapse_{{ $slug }}">
                                        <i class="bi {{ $icon }} fs-5 me-3 text-primary"></i> 
                                        <span class="category-title fw-bold">{{ $category->name }}</span>
                                        @if($category->is_optional)
                                            <small class="text-muted ms-1">(Opcional)</small>
                                        @endif
                                        <span class="badge bg-light text-secondary border ms-auto me-2 small">{{ $prods->count() }} opción(es)</span>
                                    </button>
                                </h2>
                                <div id="collapse_{{ $slug }}" class="accordion-collapse collapse" aria-labelledby="heading_{{ $slug }}" data-bs-parent="#categoryAccordion">
                                    <div class="accordion-body bg-light">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-md-9">
                                                <select class="form-select category-select" id="select_{{ $slug }}">
                                                    <option value="">-- Seleccionar {{ $category->name }} --</option>
                                                    @foreach($prods as $prod)
                                                        <option value="{{ $prod->id }}"
                                                                data-name="{{ $prod->name }}"
                                                                data-cost="{{ $prod->cost_price }}"
                                                                data-margin="{{ $prod->has_margin ? 20 : 0 }}"
                                                                data-category-id="{{ $category->id }}">
                                                            {{ $prod->name }} — ${{ number_format($prod->cost_price, 2) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-primary w-100 fw-bold btn-add-category" data-select-id="select_{{ $slug }}">
                                                    <i class="bi bi-plus-circle me-1"></i> Agregar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- 3. Notas y Condiciones -->
            <div class="quote-card p-4">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-chat-left-text text-primary me-2"></i>Notas y Términos Comerciales</h6>
                <textarea name="notes" class="form-control" rows="3" placeholder="Ej. Tiempo de entrega: 3 a 5 días hábiles. Precios sujetos a cambio sin previo aviso. Incluye garantía de 1 año."></textarea>
            </div>

        </div>

        <!-- COLUMNA DERECHA: Lista de Productos Seleccionados y Resumen de Cotización (50%) -->
        <div class="col-lg-5">
            <div class="sticky-summary">
                
                <!-- Tabla de Productos Cotizados (Carrito Cyberpuerta) -->
                <div class="quote-card p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-cart3 text-primary me-2"></i>Lista de Selección</h6>
                        <span class="badge bg-secondary" id="itemsCount">0 componentes</span>
                    </div>

                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                        <table class="table table-hover align-middle table-items mb-0" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th style="width: 60px;" class="text-center">Cant.</th>
                                    <th style="width: 90px;">Costo ($)</th>
                                    <th style="width: 75px;">Util (%)</th>
                                    <th style="width: 95px;">P. Unit ($)</th>
                                    <th style="width: 95px;">Subtotal</th>
                                    <th style="width: 30px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsContainer">
                                {{-- Ítems agregados dinámicamente --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Resumen de Costos y Acciones -->
                <div class="quote-card p-4">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">Resumen Económico</h6>
                    
                    <div class="summary-box p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Costo Base:</span>
                            <span class="fw-semibold text-secondary" id="lblTotalBase">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success small fw-semibold"><i class="bi bi-graph-up-arrow me-1"></i>Ganancia estimada:</span>
                            <span class="fw-bold text-success" id="lblTotalProfit">$0.00</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold text-dark fs-6">Precio Final:</span>
                            <span id="lblTotal" class="fs-4 fw-extrabold text-primary">$0.00</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm py-2">
                            <i class="bi bi-check-circle me-1"></i> Guardar Cotización
                        </button>
                        <a href="{{ route('quotes.index') }}" class="btn btn-outline-secondary fw-semibold">
                            Cancelar y volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</form>

<script>
let itemIndex = 0;

document.addEventListener("DOMContentLoaded", function() {
    document.body.classList.add('sidebar-collapse');

    // Manejador para cargar la información del cliente
    const selectClient = document.getElementById('selectClient');
    if (selectClient) {
        selectClient.addEventListener('change', function() {
            updateClientDetails(this);
        });

        if (selectClient.value) {
            updateClientDetails(selectClient);
        }
    }

    // Evento para agregar productos desde los acordeones
    document.querySelectorAll('.btn-add-category').forEach(button => {
        button.addEventListener('click', function() {
            const selectId = this.getAttribute('data-select-id');
            const select = document.getElementById(selectId);
            const option = select.options[select.selectedIndex];

            if (!option.value) return;

            let pId = option.value;
            let pName = option.dataset.name;
            let pCost = parseFloat(option.dataset.cost);
            let pMargin = parseFloat(option.dataset.margin);
            let catId = option.dataset.categoryId;

            // 1. Agregar fila a la tabla
            addRow(pId, pName, pCost, pMargin, catId);
            
            // 2. Ocultar la categoría seleccionada (Estilo Cyberpuerta)
            if (catId) {
                let catCard = document.getElementById(`cat_card_${catId}`);
                if (catCard) {
                    let collapseEl = catCard.querySelector('.accordion-collapse');
                    if (collapseEl && collapseEl.classList.contains('show')) {
                        let bsCollapse = bootstrap.Collapse.getInstance(collapseEl) || new bootstrap.Collapse(collapseEl);
                        bsCollapse.hide();
                    }
                    catCard.style.display = 'none';
                }
            }

            select.value = ''; // Limpiar selección
        });
    });
});

function updateClientDetails(selectElement) {
    const option = selectElement.options[selectElement.selectedIndex];
    const detailsRow = document.getElementById('clientDetailsRow');

    if (selectElement.value && option) {
        document.getElementById('clientContact').innerText = option.dataset.contact || 'N/A';
        document.getElementById('clientPhone').innerText = option.dataset.phone || 'N/A';
        document.getElementById('clientEmail').innerText = option.dataset.email || 'N/A';
        document.getElementById('clientAddress').innerText = option.dataset.address || 'N/A';

        detailsRow.style.display = 'block';
    } else {
        detailsRow.style.display = 'none';
        document.getElementById('clientContact').innerText = '---';
        document.getElementById('clientPhone').innerText = '---';
        document.getElementById('clientEmail').innerText = '---';
        document.getElementById('clientAddress').innerText = '---';
    }
}

function addRow(pId, name, cost, margin, catId) {
    let container = document.getElementById('itemsContainer');
    let tr = document.createElement('tr');
    tr.id = `row_${itemIndex}`;
    tr.setAttribute('data-category-id', catId || '');

    let profitAmount = cost * (margin / 100);
    let initialUnitPrice = cost + profitAmount;

    tr.innerHTML = `
        <td>
            <input type="hidden" name="items[${itemIndex}][product_id]" value="${pId}">
            <input type="text" name="items[${itemIndex}][concept]" class="form-control form-control-sm border-0 bg-transparent fw-semibold p-0" value="${name}" title="${name}" required>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][quantity]" class="form-control form-control-sm text-center qty-input p-1" value="1" min="1" oninput="recalculate(this)" required>
        </td>
        <td>
            <input type="number" step="0.01" name="items[${itemIndex}][cost_price]" class="form-control form-control-sm cost-input p-1" value="${cost.toFixed(2)}" oninput="recalculate(this)" required>
        </td>
        <td>
            <input type="number" step="0.1" name="items[${itemIndex}][margin_percentage]" class="form-control form-control-sm margin-input p-1" value="${margin}" oninput="recalculate(this)" required>
        </td>
        <td>
            <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="form-control form-control-sm unit-price-input fw-semibold p-1" value="${initialUnitPrice.toFixed(2)}" oninput="updateFromUnitPrice(this)" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm subtotal-display bg-light fw-bold border-0 text-primary p-1" readonly>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-link text-danger p-0 border-0" onclick="removeRow(${itemIndex}, '${catId}')">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </td>
    `;

    container.appendChild(tr);
    itemIndex++;
    updateTotals();
}

function removeRow(index, catId) {
    let row = document.getElementById(`row_${index}`);
    if (row) row.remove();

    // Mostrar de nuevo la categoría en el configurador si ya no hay productos de ella
    if (catId) {
        let remainingWithSameCat = document.querySelectorAll(`#itemsContainer tr[data-category-id="${catId}"]`);
        if (remainingWithSameCat.length === 0) {
            let catCard = document.getElementById(`cat_card_${catId}`);
            if (catCard) {
                catCard.style.display = 'block';
            }
        }
    }

    updateTotals();
}

function recalculate(element) {
    let row = element ? element.closest('tr') : null;

    if (row) {
        let cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        let margin = parseFloat(row.querySelector('.margin-input').value) || 0;

        let profitAmount = cost * (margin / 100);
        let unitPrice = cost + profitAmount;

        row.querySelector('.unit-price-input').value = unitPrice.toFixed(2);
    }

    updateTotals();
}

function updateFromUnitPrice(element) {
    let row = element.closest('tr');
    let cost = parseFloat(row.querySelector('.cost-input').value) || 0;
    let unitPrice = parseFloat(element.value) || 0;

    let margin = cost > 0 ? ((unitPrice / cost) - 1) * 100 : 0;

    row.querySelector('.margin-input').value = margin.toFixed(2);

    updateTotals();
}

function updateTotals() {
    let rows = document.querySelectorAll('#itemsContainer tr');
    let grandBaseCost = 0;
    let grandProfit = 0;
    let grandTotal = 0;

    rows.forEach(row => {
        let qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        let cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        let unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;

        let profitPerUnit = unitPrice - cost;
        let rowSubtotal = unitPrice * qty;

        row.querySelector('.subtotal-display').value = '$' + rowSubtotal.toFixed(2);

        grandBaseCost += (cost * qty);
        grandProfit += (profitPerUnit * qty);
        grandTotal += rowSubtotal;
    });

    document.getElementById('itemsCount').innerText = `${rows.length} componentes`;
    document.getElementById('lblTotalBase').innerText = '$' + grandBaseCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('lblTotalProfit').innerText = '$' + grandProfit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('lblTotal').innerText = '$' + grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}
</script>
@endsection