<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0149a8; padding: 25px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h2 { color: #ffffff; margin: 0; font-size: 20px; }
        .header p { color: #85d7ff; margin: 5px 0 0; font-size: 13px; }
        .content { padding: 30px 25px; background-color: #ffffff; border: 1px solid #e5e7eb; border-top: none; }
        .project-box { background-color: #f0f7ff; border-left: 4px solid #0149a8; padding: 15px 18px; border-radius: 0 8px 8px 0; margin: 20px 0; }
        .project-title { font-size: 16px; font-weight: bold; color: #0149a8; margin: 0; }
        .meta-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .meta-table td { padding: 6px 10px; font-size: 13px; }
        .meta-label { color: #6b7280; width: 40%; }
        .meta-value { color: #111; font-weight: bold; }
        .total-box { background-color: #0149a8; color: #fff; padding: 15px 20px; border-radius: 8px; text-align: center; margin: 20px 0; }
        .total-box .amount { font-size: 28px; font-weight: bold; }
        .total-box .label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #85d7ff; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #6c757d; border-top: 1px solid #e5e7eb; }
        .footer .company { font-weight: bold; color: #0149a8; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Cotización #{{ str_pad($cotizacion->id_cotizacion, 5, '0', STR_PAD_LEFT) }}</h2>
            <p>Cliché Marketing Digital</p>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $cotizacion->cliente->nombre }} {{ $cotizacion->cliente->apellido_paterno }}</strong>,</p>
            
            <p>Adjunto encontrarás la cotización correspondiente al siguiente proyecto:</p>
            
            <div class="project-box">
                <p class="project-title">{{ $cotizacion->titulo_cotizacion }}</p>
            </div>

            <table class="meta-table">
                <tr>
                    <td class="meta-label">Folio:</td>
                    <td class="meta-value">#{{ str_pad($cotizacion->id_cotizacion, 5, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Fecha de emisión:</td>
                    <td class="meta-value">{{ $cotizacion->fecha->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Vigencia:</td>
                    <td class="meta-value">{{ $cotizacion->vencimiento_dias }} días (hasta {{ $cotizacion->fecha->addDays($cotizacion->vencimiento_dias)->format('d/m/Y') }})</td>
                </tr>
            </table>

            <div class="total-box">
                <div class="label">Total de la cotización</div>
                <div class="amount">${{ number_format($cotizacion->total, 2) }} MXN</div>
            </div>

            <p style="font-size: 13px; color: #555;">
                Por favor revisa el documento PDF adjunto para ver el desglose completo de los servicios cotizados. 
                Si tienes alguna duda o deseas realizar modificaciones, no dudes en contactarnos.
            </p>
        </div>
        
        <div class="footer">
            <p class="company">Cliché Marketing Digital</p>
            <p>Estrategia digital que impulsa tu negocio</p>
            <p style="margin-top: 10px; font-size: 11px; color: #9ca3af;">Este es un mensaje automático generado por el sistema.</p>
        </div>
    </div>
</body>
</html>
