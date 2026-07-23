@extends('layouts.adminlte')

@section('title', 'Editar Cotización')
@section('page_title', 'Editar Cotización #' . $quote->folio)

@section('content')
<form action="{{ route('quotes.update', $quote->id) }}" method="POST" id="quoteForm">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Encabezado -->
        <div class="col-md-12 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Folio</label>
                            <input type="text" class="form-control fw-bold text-primary" value="{{ $quote->folio }}" readonly>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Cliente *</label>
                            <select name="client_id" class="form-select" required>
                                <option value="">-- Seleccionar Cliente --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ $quote->client_id == $client->id ? 'selected' : '' }}>
                                        {{ $client->business_name }} (Contacto: {{ $client->contact_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Fecha Emisión *</label>
                            <input type="date" name="issue_date" class="form-control"
                                   value="{{ $quote->issue_date ? \Carbon\Carbon::parse($quote->issue_date)->format('Y-m-d') : date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Válida Hasta</label>
                            <input type="date" name="valid_until" class="form-control"
                                   value="{{ $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Ítems -->
        <div class="col-md-12 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-cart-plus me-2 text-primary"></i>Conceptos y Productos</h6>
                    <div class="d-flex gap-2" style="max-width: 400px;">
                        <select id="productSelect" class="form-select form-select-sm">
                            <option value="">-- Seleccionar Producto --</option>
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
                                    <th style="width: 80px;">Cant.</th>
                                    <th style="width: 120px;">Costo Base ($)</th>
                                    <th style="width: 110px;">Utilidad (%)</th>
                                    <th style="width: 120px;">Utilidad ($)</th>
                                    <th style="width: 130px;">P. Unitario ($)</th>
                                    <th style="width: 140px;">Subtotal ($)</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsContainer">
                                @foreach($quote->items as $index => $item)
                                    @php
                                        $profitAmount = $item->unit_price - $item->cost_price;
                                    @endphp
                                    <tr id="row_{{ $index }}">
                                        <td>
                                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                                            <input type="text" name="items[{{ $index }}][concept]" class="form-control form-control-sm" value="{{ $item->concept }}" required>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control form-control-sm qty-input" value="{{ $item->quantity }}" min="1" oninput="recalculate(this)" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[{{ $index }}][cost_price]" class="form-control form-control-sm cost-input" value="{{ number_format($item->cost_price, 2, '.', '') }}" oninput="recalculate(this)" required>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="number" step="0.1" name="items[{{ $index }}][margin_percentage]" class="form-control form-control-sm margin-input" value="{{ number_format($item->margin_percentage, 2, '.', '') }}" oninput="recalculate(this)" required>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm profit-amount-input bg-light text-success fw-bold" value="${{ number_format($profitAmount, 2, '.', '') }}" readonly>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[{{ $index }}][unit_price]" class="form-control form-control-sm unit-price-input" value="{{ number_format($item->unit_price, 2, '.', '') }}" oninput="updateFromUnitPrice(this)" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm subtotal-display bg-light fw-bold" value="${{ number_format($item->subtotal, 2, '.', '') }}" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="removeRow({{ $index }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
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
                    <textarea name="notes" class="form-control" rows="4">{{ $quote->notes }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Sin Utilidad (Costo Base):</span>
                        <span class="fw-bold" id="lblTotalBase">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-success fw-bold">Total Utilidad / Ganancia:</span>
                        <span class="fw-bold text-success" id="lblTotalProfit">$0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fs-5 fw-bold text-dark mb-3">
                        <span>Precio Final:</span>
                        <span id="lblTotal" class="text-primary">$0.00</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('quotes.show', $quote->id) }}" class="btn btn-outline-secondary w-50 fw-bold py-2">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary w-50 fw-bold py-2">
                            <i class="bi bi-save me-1"></i> Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Bloqueo visual del menú lateral durante captura/edición --}}
<style>
.main-sidebar {
    pointer-events: none !important;
    opacity: 0.5 !important;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.body.classList.add('sidebar-collapse');
});

let itemIndex = {{ count($quote->items) }};

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

    addRow(option.value, option.dataset.name, parseFloat(option.dataset.cost), parseFloat(option.dataset.margin));
    select.value = '';
});

function addRow(pId, name, cost, margin) {
    let container = document.getElementById('itemsContainer');
    let tr = document.createElement('tr');
    tr.id = `row_${itemIndex}`;

    let profitAmount = cost * (margin / 100);
    let initialUnitPrice = cost + profitAmount;

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
            <input type="text" class="form-control form-control-sm profit-amount-input bg-light text-success fw-bold" value="$${profitAmount.toFixed(2)}" readonly>
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

function recalculate(element) {
    let row = element ? element.closest('tr') : null;
    if (row) {
        let cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        let margin = parseFloat(row.querySelector('.margin-input').value) || 0;
        let profitAmount = cost * (margin / 100);
        let unitPrice = cost + profitAmount;

        row.querySelector('.profit-amount-input').value = '$' + profitAmount.toFixed(2);
        row.querySelector('.unit-price-input').value = unitPrice.toFixed(2);
    }
    updateTotals();
}

function updateFromUnitPrice(element) {
    let row = element.closest('tr');
    let cost = parseFloat(row.querySelector('.cost-input').value) || 0;
    let unitPrice = parseFloat(element.value) || 0;

    let profitAmount = unitPrice - cost;
    let margin = cost > 0 ? ((unitPrice / cost) - 1) * 100 : 0;

    row.querySelector('.margin-input').value = margin.toFixed(2);
    row.querySelector('.profit-amount-input').value = '$' + profitAmount.toFixed(2);

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

    document.getElementById('lblTotalBase').innerText = '$' + grandBaseCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('lblTotalProfit').innerText = '$' + grandProfit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('lblTotal').innerText = '$' + grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

document.addEventListener('DOMContentLoaded', updateTotals);
</script>
@endsection
