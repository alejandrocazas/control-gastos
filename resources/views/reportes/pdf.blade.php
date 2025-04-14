<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte Financiero</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .periodo { font-size: 14px; color: #555; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .total { font-weight: bold; background-color: #f8f9fa; }
        .ingresos { color: #28a745; }
        .gastos { color: #dc3545; }
        .footer { margin-top: 30px; font-size: 12px; text-align: right; }
        .page-break { page-break-after: always; }
        .gastos-detallados { margin-top: 30px; }
        .pagination { page-break-inside: avoid; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Reporte Financiero</div>
        <div class="periodo">
            Reporte {{ ucfirst($data['tipo_reporte']) }}<br>
            Del {{ \Carbon\Carbon::parse(request('fecha_inicio'))->format('d/m/Y') }} 
            al {{ \Carbon\Carbon::parse(request('fecha_fin'))->format('d/m/Y') }}
        </div>
    </div>

    <!-- Tabla principal del reporte -->
    <table>
        <thead>
            <tr>
                <th>Periodo</th>
                <th>Ingresos</th>
                <th>Gastos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['detalle'] as $item)
            <tr>
                <td>{{ $item['periodo'] ?? $item['fecha'] }}</td>
                <td class="ingresos">Bs. {{ number_format($item['ingresos'], 2) }}</td>
                <td class="gastos">Bs. {{ number_format($item['gastos'], 2) }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td>Total</td>
                <td class="ingresos">Bs. {{ number_format($data['total_ingresos'], 2) }}</td>
                <td class="gastos">Bs. {{ number_format($data['total_gastos'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Detalle de gastos -->
    @if(isset($data['gastos_detallados']) && !empty($data['gastos_detallados']))
    <div class="gastos-detallados">
        @foreach($data['gastos_detallados'] as $periodo => $gastos)
            <h3>{{ $periodo }}</h3>
            <table class="pagination">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th style="text-align: right;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gastos as $gasto)
                    <tr>
                        <td>{{ $gasto['descripcion'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($gasto['fecha'])->format('d/m/Y') }}</td>
                        <td style="text-align: right; color: #dc3545;">Bs. {{ number_format($gasto['monto'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr style="font-weight: bold;">
                        <td colspan="2">Total</td>
                        <td style="text-align: right;">Bs. {{ number_format(collect($gastos)->sum('monto'), 2) }}</td>
                    </tr>
                </tbody>
            </table>
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
    @endif

    <div class="footer">
        Generado el: {{ now()->setTimezone('America/La_Paz')->format('d/m/Y H:i:s') }}<br>
        Página <span class="page-number"></span> de <span class="page-count"></span><br>
        Sistema de Control de Gastos
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Arial");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
            
            // Actualizar números de página en el footer
            $pdf->page_text(500, $y, "Página {$PAGE_NUM} de {$PAGE_COUNT}", $font, $size);
        }
    </script>
</body>
</html>