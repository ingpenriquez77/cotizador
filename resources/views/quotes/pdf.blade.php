<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización {{ $quote->folio }}</title>
    <style>
        @page {
            margin: 30px 35px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #2b2b2b;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
            padding-bottom: 15px;
            border-bottom: 2px solid #0d6efd;
        }
        .folio-title {
            font-size: 20px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 4px;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .fw-bold {
            font-weight: bold;
        }
        .client-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 10px 12px;
            margin: 15px 0;
        }
        .client-title {
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 10px;
        }
        .items-table {
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .items-table th {
            background-color: #f1f3f5;
            color: #333;
            text-align: left;
            padding: 7px 8px;
            font-size: 10px;
            border-bottom: 1px solid #ced4da;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #e9ecef;
        }
        .totals-table {
            width: 45%;
            float: right;
            margin-top: 10px;
        }
        .totals-table td {
            padding: 6px 8px;
        }
        .total-amount {
            font-size: 14px;
            color: #198754;
            font-weight: bold;
        }
        .notes-box {
            clear: both;
            margin-top: 30px;
            padding: 10px 12px;
            border-left: 3px solid #0d6efd;
            background-color: #f8f9fa;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <!-- Encabezado -->
    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                <div class="folio-title">COTIZACIÓN</div>
                <div>Folio: <strong>{{ $quote->folio }}</strong></div>
            </td>
            <td class="text-end" style="width: 50%;">
                <div><strong>Fecha Emisión:</strong> {{ \Carbon\Carbon::parse($quote->issue_date)->format('d/m/Y') }}</div>
                <div><strong>Válida Hasta:</strong> {{ $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('d/m/Y') : 'N/A' }}</div>
            </td>
        </tr>
    </table>

    <!-- Cliente -->
    <div class="client-box">
        <div class="client-title">Datos del Cliente</div>
        <strong>Razón Social:</strong> {{ $quote->client->business_name ?? 'N/A' }}<br>
        <strong>Contacto:</strong> {{ $quote->client->contact_name ?? 'N/A' }} |
        <strong>Teléfono:</strong> {{ $quote->client->phone ?? 'N/A' }} |
        <strong>Correo:</strong> {{ $quote->client->email ?? 'N/A' }}
    </div>

    <!-- Tabla de Conceptos -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 55%;">Concepto / Descripción</th>
                <th class="text-center" style="width: 10%;">Cant.</th>
                <th class="text-end" style="width: 15%;">P. Unitario</th>
                <th class="text-end" style="width: 15%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->concept }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-end fw-bold">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totales (Sin IVA, Precio Final Directo) -->
    <table class="totals-table">
        <tr>
            <td class="fw-bold" style="font-size: 13px;">TOTAL:</td>
            <td class="text-end total-amount">${{ number_format($quote->subtotal, 2) }}</td>
        </tr>
    </table>

    <!-- Notas y Condiciones -->
    @if($quote->notes)
        <div class="notes-box">
            <strong style="color: #333;">Notas y Condiciones Comerciales:</strong><br>
            {!! nl2br(e($quote->notes)) !!}
        </div>
    @endif

</body>
</html>
