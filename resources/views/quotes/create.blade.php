@extends('layouts.adminlte')

@section('title', 'Nueva Cotización')
@section('page_title', 'Crear Cotización')

@section('content')
<form action="{{ route('quotes.store') }}" method="POST" id="quoteForm">
    @csrf
    <div class="row">
        <!-- Encabezado de la Cotización -->
        <div class="col-md-12 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Folio</label>
                            <input type="text" name="folio" class="form-control fw-bold text-primary" value="{{ $folio }}" readonly>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Cliente *</label>
                            <select name="client_id" class="form-select" required>
                                <option value="">-- Seleccionar Cliente --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->business_name }} (Contacto: {{ $client->contact_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Fecha Emisión *</label>
                            <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Válida Hasta</label>
                            <input type="date" name="valid_until" class="form-control" value="{{ date('Y-m-d', strtotime('+15 days')) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agregar Ítems -->
        <div class="col-md-12 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-cart-plus me-2 text-primary"></i>Conceptos y Productos</h6>
                    <div class="d-flex gap-2" style="max-width: 400px;">
                        <select id="productSelect" class="form-select form-select-sm">
                            <option value="">-- Seleccionar Producto del Catálogo --</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}"
                                        data-name="{{ $prod->name }}"
                                        data-cost="{{ $prod->cost_price }}"
                                        data-margin="{{ $prod->has_margin ? 30 : 0 }}">
                                    {{ $prod->name }} (${{ number_format($prod->cost_price, 2) }})
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary btn-sm text-nowrap" id="btnAddProduct">
                            <i class="bi bi-plus-lg me-1"></i> Agregar
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Concepto / Descripción</th>
                                    <th style="width: 100px;">Cant.</th>
                                    <th style="width: 130px;">Costo Base ($)</th>
                                    <th style="width: 120px;">Utilidad (%)</th>
                                    <th style="width: 130px;">P. Unitario ($)</th>
                                    <th style="width: 140px;">Subtotal ($)</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsContainer">
                                {{-- Los ítems dinámicos se renderizan aquí --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Totales y Notas -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <label class="form-label fw-bold">Notas o Condiciones Comerciales</label>
                    <textarea name="notes" class="form-control" rows="4" placeholder="Ej. Precios sujetos a cambio sin previo aviso. Tiempo de entrega: 3 a 5 días hábiles."></textarea>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-bold" id="lblSubtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">IVA (16%):</span>
                        <span class="fw-bold" id="lblTax">$0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fs-5 fw-bold text-dark mb-3">
                        <span>Total:</span>
                        <span id="lblTotal" class="text-success">$0.00</span>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                        <i class="bi bi-save me-1"></i> Guardar Cotización
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- JS Dinámico Bidireccional --}}
<script>
let itemIndex = 0;

// Evita submit accidental con la tecla Enter
document.getElementById('quoteForm').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
        if (e.target.id === 'productSelect') {
            document.getElementById('btnAddProduct').click();
        }
    }
});

document.getElementById('btnAddProduct').addEventListener('click', function() {
    let select = document.getElementById('productSelect');
    let option = select.options[select.selectedIndex];

    if (!option.value) return;

    let pId = option.value;
    let pName = option.dataset.name;
    let pCost = parseFloat(option.dataset.cost);
    let pMargin = parseFloat(option.dataset.margin);

    addRow(pId, pName, pCost, pMargin);
    select.value = '';
});

function addRow(pId, name, cost, margin) {
    let container = document.getElementById('itemsContainer');
    let tr = document.createElement('tr');
    tr.id = `row_${itemIndex}`;

    let initialUnitPrice = cost * (1 + (margin / 100));

    tr.innerHTML = `
        <td>
            <input type="hidden" name="items[${itemIndex}][product_id]" value="${pId}">
            <input type="text" name="items[${itemIndex}][concept]" class="form-control form-control-sm" value="${name}" required>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][quantity]" class="form-control form-control-sm qty-input" value="1" min="1" oninput="recalculate(this)" required>
        </td>
        <td>
            <input type="number" step="0.01" name="items[${itemIndex}][cost_price]" class="form-control form-control-sm cost-input" value="${cost.toFixed(2)}" oninput="recalculate(this)" required>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="number" step="0.1" name="items[${itemIndex}][margin_percentage]" class="form-control form-control-sm margin-input" value="${margin}" oninput="recalculate(this)" required>
                <span class="input-group-text">%</span>
            </div>
        </td>
        <td>
            <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="form-control form-control-sm unit-price-input" value="${initialUnitPrice.toFixed(2)}" oninput="updateFromUnitPrice(this)" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm subtotal-display bg-light fw-bold" readonly>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="removeRow(${itemIndex})">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;

    container.appendChild(tr);
    itemIndex++;
    updateTotals();
}

function removeRow(index) {
    document.getElementById(`row_${index}`).remove();
    updateTotals();
}

// 1. Cuando cambias Cantidad, Costo Base o Utilidad (%) -> calcula Precio Unitario
function recalculate(element) {
    let row = element ? element.closest('tr') : null;

    if (row) {
        let cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        let margin = parseFloat(row.querySelector('.margin-input').value) || 0;

        let unitPrice = cost * (1 + (margin / 100));
        row.querySelector('.unit-price-input').value = unitPrice.toFixed(2);
    }

    updateTotals();
}

// 2. Cuando editas directamente el Precio Unitario ($) -> calcula Utilidad (%)
function updateFromUnitPrice(element) {
    let row = element.closest('tr');
    let cost = parseFloat(row.querySelector('.cost-input').value) || 0;
    let unitPrice = parseFloat(element.value) || 0;

    if (cost > 0) {
        let margin = ((unitPrice / cost) - 1) * 100;
        row.querySelector('.margin-input').value = margin.toFixed(2);
    }

    updateTotals();
}

// 3. Recalcula Subtotales por fila e Importes Generales de la Cotización
function updateTotals() {
    let rows = document.querySelectorAll('#itemsContainer tr');
    let grandSubtotal = 0;

    rows.forEach(row => {
        let qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        let unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;

        let rowSubtotal = unitPrice * qty;
        row.querySelector('.subtotal-display').value = '$' + rowSubtotal.toFixed(2);

        grandSubtotal += rowSubtotal;
    });

    let tax = grandSubtotal * 0.16;
    let total = grandSubtotal + tax;

    document.getElementById('lblSubtotal').innerText = '$' + grandSubtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('lblTax').innerText = '$' + tax.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('lblTotal').innerText = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}
</script>
@endsection
