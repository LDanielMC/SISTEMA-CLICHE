<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <thead>
            <tr>
                <td colspan="16" style="text-align: center; font-size: 20px; font-weight: bold; height: 40px; vertical-align: middle;">
                    CLICHÉ MARKETING DIGITAL - REPORTE DE EFECTIVIDAD
                </td>
            </tr>
            <tr>
                <td colspan="16" style="text-align: center; font-size: 12px; color: #555555;">
                    Generado el: {{ date('d/m/Y H:i') }}
                </td>
            </tr>
            <tr>
                <td colspan="16" style="text-align: center; font-size: 12px; color: #555555;">
                    Rango: {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <td colspan="16"></td>
            </tr>
            <tr>
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 250px;">Cliente</th>
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 80px;">Historial Total</th>
                
                {{-- Conteos Periodo --}}
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 80px;">Emitidas</th>
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 80px;">Aceptadas</th>
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 80px;">Rechazadas</th>
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 80px;">Pendientes</th>
                
                {{-- KPIs --}}
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 90px;">Efectividad (Cant)</th>
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 90px;">Efectividad ($)</th>
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 110px;">Ticket Promedio</th>

                {{-- Montos Generales --}}
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 120px;">Monto Emitido</th>
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 120px;">Monto Rechazado</th>
                <th style="background-color: #0149a8; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 120px;">Monto Pendiente</th>

                {{-- Desglose Ganancias (Aceptadas) --}}
                <th style="background-color: #15803d; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 120px;">Subtotal (Ganancia)</th>
                <th style="background-color: #15803d; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 100px;">IVA (Ganancia)</th>
                <th style="background-color: #15803d; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 100px;">ISR (Ganancia)</th>
                <th style="background-color: #15803d; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; width: 120px;">Total (Ganancia)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reporte as $row)
                <tr>
                    <td style="border: 1px solid #000000; vertical-align: middle;">{{ $row['cliente'] }}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle; background-color: #f3f4f6;">{{ $row['historial_total'] }}</td>
                    
                    {{-- Conteos --}}
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">{{ $row['emitidas'] }}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle; font-weight: bold; color: #15803d;">{{ $row['aceptadas'] }}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle; color: #b91c1c;">{{ $row['rechazadas'] }}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle; color: #a16207;">{{ $row['pendientes'] }}</td>

                    {{-- KPIs --}}
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle; {{ $row['efectividad'] >= 50 ? 'color: #166534; font-weight: bold;' : ($row['efectividad'] > 0 ? 'color: #854d0e;' : 'color: #991b1b;') }}">
                        {{ $row['efectividad'] }}%
                    </td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle; {{ $row['efectividad_monetaria'] >= 50 ? 'color: #166534; font-weight: bold;' : ($row['efectividad_monetaria'] > 0 ? 'color: #854d0e;' : 'color: #991b1b;') }}">
                        {{ $row['efectividad_monetaria'] }}%
                    </td>
                    <td style="border: 1px solid #000000; text-align: right; vertical-align: middle;">${{ number_format($row['ticket_promedio'], 2) }}</td>

                    {{-- Montos --}}
                    <td style="border: 1px solid #000000; text-align: right; vertical-align: middle;">${{ number_format($row['monto_emitido'], 2) }}</td>
                    <td style="border: 1px solid #000000; text-align: right; vertical-align: middle; color: #b91c1c;">${{ number_format($row['monto_rechazado'], 2) }}</td>
                    <td style="border: 1px solid #000000; text-align: right; vertical-align: middle; color: #a16207;">${{ number_format($row['monto_pendiente'], 2) }}</td>

                    {{-- Ganancias --}}
                    <td style="border: 1px solid #000000; text-align: right; vertical-align: middle;">${{ number_format($row['ganancia_subtotal'], 2) }}</td>
                    <td style="border: 1px solid #000000; text-align: right; vertical-align: middle;">${{ number_format($row['ganancia_iva'], 2) }}</td>
                    <td style="border: 1px solid #000000; text-align: right; vertical-align: middle;">${{ number_format($row['ganancia_isr'], 2) }}</td>
                    <td style="border: 1px solid #000000; text-align: right; vertical-align: middle; font-weight: bold; color: #166534; background-color: #dcfce7;">${{ number_format($row['monto_aceptado'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="background-color: #e5e7eb; font-weight: bold; border: 1px solid #000000;">TOTALES</td>
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ $reporte->sum('historial_total') }}</td>
                
                {{-- Totales Conteos --}}
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: center; border: 1px solid #000000;">{{ $reporte->sum('emitidas') }}</td>
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: center; border: 1px solid #000000; color: #15803d;">{{ $reporte->sum('aceptadas') }}</td>
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: center; border: 1px solid #000000; color: #b91c1c;">{{ $reporte->sum('rechazadas') }}</td>
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: center; border: 1px solid #000000; color: #a16207;">{{ $reporte->sum('pendientes') }}</td>
                
                {{-- Totales KPIs --}}
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: center; border: 1px solid #000000;">
                    {{ $reporte->sum('emitidas') > 0 ? round(($reporte->sum('aceptadas') / $reporte->sum('emitidas')) * 100, 1) : 0 }}%
                </td>
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: center; border: 1px solid #000000;">
                    {{ $reporte->sum('monto_emitido') > 0 ? round(($reporte->sum('monto_aceptado') / $reporte->sum('monto_emitido')) * 100, 1) : 0 }}%
                </td>
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: right; border: 1px solid #000000;">
                    ${{ $reporte->sum('aceptadas') > 0 ? number_format($reporte->sum('monto_aceptado') / $reporte->sum('aceptadas'), 2) : '0.00' }}
                </td>

                {{-- Totales Montos --}}
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: right; border: 1px solid #000000;">${{ number_format($reporte->sum('monto_emitido'), 2) }}</td>
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: right; border: 1px solid #000000; color: #b91c1c;">${{ number_format($reporte->sum('monto_rechazado'), 2) }}</td>
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: right; border: 1px solid #000000; color: #a16207;">${{ number_format($reporte->sum('monto_pendiente'), 2) }}</td>

                {{-- Totales Ganancias --}}
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: right; border: 1px solid #000000;">${{ number_format($reporte->sum('ganancia_subtotal'), 2) }}</td>
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: right; border: 1px solid #000000;">${{ number_format($reporte->sum('ganancia_iva'), 2) }}</td>
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: right; border: 1px solid #000000;">${{ number_format($reporte->sum('ganancia_isr'), 2) }}</td>
                <td style="background-color: #e5e7eb; font-weight: bold; text-align: right; border: 1px solid #000000; color: #166534; background-color: #dcfce7;">${{ number_format($reporte->sum('monto_aceptado'), 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
