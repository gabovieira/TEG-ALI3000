<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        @page {
            size: letter;
            margin: 0.5in;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            line-height: 1.1;
            color: #000;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 10in;
        }
        
        .header {
            border: 2px solid #000;
            padding: 10px;
            margin-bottom: 15px;
            position: relative;
            page-break-inside: avoid;
        }
        
        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .logo-section {
            display: table-cell;
            width: 15%;
            vertical-align: top;
            padding-right: 10px;
        }
        
        .logo {
            max-width: 60px;
            border: 1px solid #000;
            padding: 3px;
        }
        
        .company-info {
            display: table-cell;
            width: 60%;
            vertical-align: top;
            padding-right: 10px;
        }
        
        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 8px;
            line-height: 1.1;
        }
        
        .document-info {
            display: table-cell;
            width: 25%;
            vertical-align: top;
            text-align: right;
        }
        
        .document-type {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .document-number {
            font-size: 14px;
            font-weight: bold;
            color: #d32f2f;
            border: 1px solid #000;
            padding: 5px;
            margin-bottom: 5px;
        }
        
        .document-date {
            font-size: 9px;
        }
        
        .client-info {
            border-top: 1px solid #000;
            padding-top: 8px;
            margin-bottom: 10px;
        }
        
        .client-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .client-details {
            font-size: 9px;
            line-height: 1.3;
        }
        
        .payment-details {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .details-table th {
            border: 1px solid #000;
            padding: 8px;
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }
        
        .details-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 9px;
        }
        
        .description-col {
            text-align: left !important;
            width: 50%;
        }
        
        .amount-col {
            text-align: right !important;
            width: 15%;
        }
        
        .exchange-info {
            margin: 10px 0;
            font-size: 8px;
            text-align: center;
            font-style: italic;
        }
        
        .totals-section {
            margin-top: 15px;
            float: right;
            width: 300px;
            page-break-inside: avoid;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 5px 10px;
            font-size: 9px;
        }
        
        .totals-label {
            text-align: right;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }
        
        .totals-amount-bs {
            text-align: right;
            border-bottom: 1px solid #ccc;
            width: 80px;
        }
        
        .totals-amount-usd {
            text-align: right;
            border-bottom: 1px solid #ccc;
            width: 80px;
        }
        
        .total-final {
            font-weight: bold;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            background-color: #f0f0f0;
        }
        
        .footer-info {
            clear: both;
            margin-top: 15px;
            font-size: 7px;
            line-height: 1.1;
            page-break-inside: avoid;
        }
        
        .footer-line {
            border-top: 1px solid #000;
            margin: 10px 0;
        }
        
        .signatures {
            margin-top: 20px;
            display: table;
            width: 100%;
            page-break-inside: avoid;
        }
        
        .signature {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin: 30px 0 5px 0;
        }
        
        .signature-name {
            font-weight: bold;
            font-size: 9px;
        }
        
        .signature-title {
            font-size: 8px;
            color: #666;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(200, 200, 200, 0.3);
            z-index: -1;
            font-weight: bold;
        }
        
        .stamp-area {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: 80px;
            height: 50px;
            border: 1px dashed #ccc;
            text-align: center;
            padding-top: 15px;
            font-size: 6px;
            color: #999;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    @if($pago->estado === 'confirmado')
    <div class="watermark">CONFIRMADO</div>
    @elseif($pago->estado === 'procesado')
    <div class="watermark">PROCESADO</div>
    @endif

    <!-- Header -->
    <div class="header">
        <div class="header-top">
            <div class="logo-section">
                @php
                    $logoPath = public_path('assets/img/logoalipdf.jpg');
                    $logoBase64 = '';
                    
                    if (file_exists($logoPath) && is_readable($logoPath)) {
                        $logoData = file_get_contents($logoPath);
                        if ($logoData !== false) {
                            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode($logoData);
                        }
                    }
                @endphp
                
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="ALI3000 Logo" style="max-width: 80px; max-height: 60px; border: 1px solid #ddd;">
                @else
                    <img src="{{ public_path('assets/img/logoalipdf.jpg') }}" alt="ALI3000 Logo" style="max-width: 80px; max-height: 60px; border: 1px solid #ddd;">
                @endif
            </div>
            
            <div class="company-info">
                <div class="company-name">ALI3000 CONSULTORES, C.A.</div>
                <div class="company-details">
                    RIF: J-4050882 • Teléfono: (0212) 4555500<br>
                    Urb. La Urbina, Calle 13-1, Residencias Villa Urbina<br>
                    Torre A, Piso 6, Apto. 64-A • Caracas, Venezuela<br>
                    Email: info@ali3000.com • www.ali3000.com
                </div>
            </div>
            
            <div class="document-info">
                <div class="document-type">COMPROBANTE</div>
                <div class="document-number">Nº {{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="document-date">
                    FECHA: {{ ($pago->fecha_pago ?? $pago->created_at)->format('d/m/Y') }}<br>
                    PERÍODO: {{ $pago->nombre_quincena }}
                </div>
            </div>
        </div>
        
        <div class="client-info">
            <div class="client-label">CONSULTOR:</div>
            <div class="client-details">
                <strong>{{ $pago->consultor->primer_nombre }} {{ $pago->consultor->primer_apellido }}</strong><br>
                Email: {{ $pago->consultor->email }}<br>
                @if($pago->detalles->count() > 0)
                    Empresas: @foreach($pago->detalles as $detalle){{ $detalle->empresa->nombre }}@if(!$loop->last), @endif @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="payment-details">
        <table class="details-table">
            <thead>
                <tr>
                    <th class="description-col">DESCRIPCIÓN</th>
                    <th>CANTIDAD</th>
                    <th class="amount-col">MONTO FACTURA</th>
                </tr>
            </thead>
            <tbody>
                @if($pago->detalles->count() > 0)
                    @foreach($pago->detalles as $detalle)
                    <tr>
                        <td class="description-col">{{ $detalle->empresa->nombre }} - HONORARIOS PROFESIONALES CONSULTORÍA</td>
                        <td>{{ number_format($detalle->horas, 1) }}</td>
                        <td class="amount-col">
                            {{ number_format($detalle->subtotal ?? $detalle->monto_empresa_divisas, 2) }}<br>
                            <small>{{ number_format(($detalle->subtotal ?? $detalle->monto_empresa_divisas) * $pago->tasa_cambio, 2) }}</small>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="description-col">HONORARIOS PROFESIONALES CONSULTORÍA {{ strtoupper($pago->nombre_quincena) }}</td>
                        <td>1</td>
                        <td class="amount-col">
                            {{ number_format($pago->monto_total ?? $pago->monto_base_divisas ?? 0, 2) }}<br>
                            <small>{{ number_format(($pago->monto_total ?? $pago->monto_base_divisas ?? 0) * $pago->tasa_cambio, 2) }}</small>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Exchange Rate Info -->
    <div class="exchange-info">
        Factura pagadera a su moneda USD contra valor de tasa BCV de día {{ $pago->fecha_tasa_bcv ? $pago->fecha_tasa_bcv->format('d-m-Y') : date('d-m-Y') }} Bs. {{ number_format($pago->tasa_cambio ?? 0, 4) }} / USD
    </div>

    <!-- Totals Section -->
    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td></td>
                <td style="text-align: center; font-weight: bold; font-size: 8px;">Monto Factura</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center; font-weight: bold; font-size: 8px; border-bottom: 1px solid #000;">Bs</td>
                <td style="text-align: center; font-weight: bold; font-size: 8px; border-bottom: 1px solid #000;">$</td>
            </tr>
            <tr>
                <td class="totals-label">BASE IMPONIBLE</td>
                <td class="totals-amount-bs">{{ number_format(($pago->monto_total ?? $pago->monto_base_divisas ?? 0) * ($pago->tasa_cambio ?? 0), 2) }}</td>
                <td class="totals-amount-usd">{{ number_format($pago->monto_total ?? $pago->monto_base_divisas ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="totals-label">IVA {{ number_format($pago->iva_porcentaje ?? 16, 0) }}% SOBRE BASE IMPONIBLE</td>
                <td class="totals-amount-bs">{{ number_format(($pago->iva_monto ?? $pago->iva_divisas ?? 0) * ($pago->tasa_cambio ?? 0), 2) }}</td>
                <td class="totals-amount-usd">{{ number_format($pago->iva_monto ?? $pago->iva_divisas ?? 0, 2) }}</td>
            </tr>
            <tr class="total-final">
                <td class="totals-label">TOTAL A PAGAR</td>
                <td class="totals-amount-bs">{{ number_format((($pago->monto_total ?? $pago->monto_base_divisas ?? 0) + ($pago->iva_monto ?? $pago->iva_divisas ?? 0)) * ($pago->tasa_cambio ?? 0), 2) }}</td>
                <td class="totals-amount-usd">{{ number_format(($pago->monto_total ?? $pago->monto_base_divisas ?? 0) + ($pago->iva_monto ?? $pago->iva_divisas ?? 0), 2) }}</td>
            </tr>
            @if(($pago->islr_monto ?? $pago->islr_divisas ?? 0) > 0)
            <tr>
                <td class="totals-label">MENOS: ISLR {{ number_format($pago->islr_porcentaje ?? 3, 0) }}%</td>
                <td class="totals-amount-bs">{{ number_format(($pago->islr_monto ?? $pago->islr_divisas ?? 0) * ($pago->tasa_cambio ?? 0), 2) }}</td>
                <td class="totals-amount-usd">{{ number_format($pago->islr_monto ?? $pago->islr_divisas ?? 0, 2) }}</td>
            </tr>
            <tr class="total-final">
                <td class="totals-label">NETO A PAGAR</td>
                <td class="totals-amount-bs">{{ number_format(($pago->monto_neto ?? $pago->total_menos_islr_divisas ?? 0) * ($pago->tasa_cambio ?? 0), 2) }}</td>
                <td class="totals-amount-usd">{{ number_format($pago->monto_neto ?? $pago->total_menos_islr_divisas ?? 0, 2) }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Payment Information -->
    @if($pago->datosBancarios || $pago->referencia_bancaria)
    <div class="footer-info" style="clear: both; margin-top: 20px;">
        <div class="footer-line"></div>
        <strong>INFORMACIÓN DE PAGO:</strong><br>
        @if($pago->referencia_bancaria)
            Referencia: {{ $pago->referencia_bancaria }}<br>
        @endif
        @if($pago->fecha_pago)
            Fecha de Pago: {{ $pago->fecha_pago->format('d/m/Y') }}<br>
        @endif
        @if($pago->datosBancarios)
            Cuenta donde se pagó: {{ $pago->datosBancarios->banco }} • 
            Número de Cuenta: {{ $pago->datosBancarios->numero_cuenta }} ({{ $pago->datosBancarios->tipo_cuenta === 'ahorro' ? 'Ahorro' : 'Corriente' }})<br>
            Titular de la Cuenta: {{ $pago->datosBancarios->titular }} • {{ $pago->datosBancarios->cedula_rif }}
        @endif
    </div>
    @endif

    <!-- Signatures -->
    <div class="signatures">
        <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $pago->procesador ? $pago->procesador->primer_nombre . ' ' . $pago->procesador->primer_apellido : 'ADMINISTRADOR' }}</div>
            <div class="signature-title">Procesado por</div>
        </div>
        <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $pago->consultor->primer_nombre }} {{ $pago->consultor->primer_apellido }}</div>
            <div class="signature-title">Recibido por</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-info">
        <div class="footer-line"></div>
        Este Documento va sin Tachadura ni Enmiendas<br><br>
        <strong>ALI3000 CONSULTORES, C.A.</strong> RIF: J-4050882 • Teléfono: (0212) 4555500<br>
        Urb. La Urbina, Calle 13-1, Residencias Villa Urbina, Torre A, Piso 6, Apto. 64-A<br>
        Caracas, Venezuela • Correspondiente a la FORMA LIBRE • N° de Control desde el N° 000001 hasta el N° 001000<br>
        Fecha de Impresión: {{ date('d/m/Y') }} • Registro Control Fiscal<br><br>
        <strong>Estado del pago: {{ strtoupper($pago->estado) }}</strong> | Generado el: {{ date('d/m/Y H:i:s') }}
    </div>

    <!-- Stamp Area -->
    <div class="stamp-area">
        SELLO<br>
        EMPRESA
    </div>
</body>
</html>