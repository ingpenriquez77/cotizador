@component('mail::message')
# Estimado/a {{ $quote->client->contact_name ?? $quote->client->business_name }}

Esperamos que se encuentre muy bien.

Adjunto a este correo encontrará la cotización **{{ $quote->folio }}** emitida el día {{ \Carbon\Carbon::parse($quote->issue_date)->format('d/m/Y') }}.

**Resumen de la cotización:**
* **Folio:** {{ $quote->folio }}
* **Monto Total:** ${{ number_format($quote->total, 2) }}
* **Válida hasta:** {{ $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('d/m/Y') : 'N/A' }}

Si tiene alguna duda o requiere algún ajuste, no dude en responder a este mensaje.

Atentamente,<br>
{{ config('app.name') }}
@endcomponent