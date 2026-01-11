<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ str_pad($cotizacion->id_cotizacion, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { margin: 0cm 0cm; }

        body{
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin-top: 4.3cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2.6cm;
            color:#333;
            font-size: 12px;
            background:#fefefe;
            line-height: 1.4;
        }

        /* =========================
           HEADER (ACOMODADO)
        ========================= */
        header{
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 3.7cm;
            background:#fefefe;
            border-bottom: 3px solid #0149a8;
        }

        .header-wrap{
            padding: 0.55cm 2cm 0.2cm 2cm; 
        }

        .header-table{
            width: 100%;
            border-collapse: collapse;
        }

        .brand-col{
            width: 60%;
            vertical-align: middle;
        }

        .meta-col-h{
            width: 40%;
            vertical-align: middle;
            text-align: right;
        }

        .brand-row{
            display: inline-block;
            white-space: nowrap;
        }

        .logo-img{
            max-height: 2.15cm; 
            width: auto;
            vertical-align: middle;
        }

        .brand-text{
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
            line-height: 1.15;
            max-width: 9cm; 
        }

        .brand-name{
            font-size: 14px;
            font-weight: bold;
            color:#0149a8;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .brand-sub{
            font-size: 10px;
            color:#555;
            margin-top: 3px;
        }

        .doc-tag{
            display: inline-block;
            background:#0149a8;
            color:#fff;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .6px;
            line-height: 1;           
            margin-top: 2px;          
        }

        .header-accent{
            margin-top: 0.2cm; 
            height: 0.18cm;
            background:#85d7ff;
        }

        /* =========================
           FOOTER 
        ========================= */
        footer{
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background:#fefefe;
            border-top: 1px solid #85d7ff;
            text-align: center;
            padding-top: 10px;
            font-size: 10px;
            color:#555;
        }

        .footer-company{
            font-weight: bold;
            color:#0149a8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .page-number:before {
            content: counter(page);
        }

        /* =========================
           TOP CARD 
        ========================= */
        .top-card{
            border: 1px solid #ffffff;
            background:#ffffff;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 14px; 
        }

        .top-grid{
            width: 100%;
            border-collapse: collapse;
        }

        .top-left{
            width: 62%;
            vertical-align: top;
            padding-right: 14px;
        }

        .top-right{
            width: 38%;
            vertical-align: top;
        }

        .mini-label{
            font-size: 9px;
            color:#8a8a8a;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .client-name{
            font-size: 14px;
            font-weight: bold;
            color:#000;
            margin-bottom: 2px;
        }

        .client-details{
            font-size: 11px;
            color:#555;
        }

        .summary-wrap{
            text-align: right; 
        }

        .meta-box{
            display: inline-block;
            text-align: left;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #e7eef6;
            background:#fff;
            min-width: 200px; 
        }

        .meta-row{
            font-size: 10.5px;
            color:#666;
            margin-top: 4px;
            line-height: 1.25;
        }

        .meta-row b{ color:#111; }

        /* =========================
           PROYECTO 
        ========================= */
        .project-box{
            background:#ffffff;
            border-left: 6px solid #0149a8;
            padding: 14px 18px;
            margin-bottom: 18px; 
            border-radius: 0px 10px 10px 0px;
        }

        .project-label{
            font-size: 10px;
            font-weight: bold;
            color:#0149a8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .project-title{
            font-size: 18px;
            font-weight: bold;
            color:#111;
            line-height: 1.2;
        }

        .project-intro{
            margin-top: 8px;
            font-size: 11px;
            color:#4a4a4a;
            font-style: normal light;
            text-align: justify
        }

        /* --- TABLA DE PARTIDAS --- */
        .items-table{
            width:100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th{
            background-color: #0149a8;
            color:#fff;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: normal;
            border-bottom: 2px solid #00337a;
        }

        .items-table td{
            padding: 12px 10px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 11px;
            color:#333;
            vertical-align: top;
        }

        .items-table tr:nth-child(even){ background-color:#fcfcfc; }

        .text-right{ text-align: right; }
        .text-center{ text-align: center; }
        .font-bold{ font-weight: bold; }
        .text-blue{ color:#0149a8; }
        


        /* --- TOTALES --- */
        .totals-box { width: 40%; float:right; margin-top: 10px; }
        .totals-table { width:100%; border-collapse: collapse; }
        .totals-table td { padding: 6px 10px; text-align: right; }
        .total-label { color:#666; font-size: 12px; }
        .total-number { font-size:12px; font-weight: bold; color:#333; }
        .grand-total-row { background:#0149a8; color:#fff; }
        .grand-total-row td { padding: 10px; font-size: 16px; font-weight: bold; }

        /* --- NOTAS --- */
        .notes-section{
            margin-top: 2cm;
            border-left: 4px solid #85d7ff;
            padding-left: 15px;
            padding-top: 0px;
            page-break-inside: avoid;
        }
        .notes-header{
            font-size: 11px;
            font-weight: bold;
            color:#0149a8;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .notes-body{
            font-size: 10px;
            color:#555;
            line-height: 1.5;
            white-space: pre-line;
        }

        .clearfix::after{
            content:"";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header>
        <div class="header-wrap">
            <table class="header-table">
                <tr>
                    <td class="brand-col">
                        <span class="brand-row">
                            <img src="{{ public_path('img/logo-cliche.png') }}" class="logo-img" alt="Logo Cliché">
                            <span class="brand-text">
                                <div class="brand-name">Cliché Marketing Digital</div>
                                <div class="brand-sub">Estrategia digital que impulsa tu negocio</div>
                            </span>
                        </span>
                    </td>
                    <td class="meta-col-h">
                        <div class="doc-tag">Cotización</div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="header-accent"></div>
    </header>

    <!-- FOOTER -->
    <footer>
        <div class="footer-company">CLICHÉ MARKETING DIGITAL</div>
        <div>Estrategia digital que impulsa tu negocio | Cuernavaca, Morelos | cliche.marketingd@gmail.com</div>
        <div style="margin-top:5px; color:#aaa; font-size:8px;">Página <span class="page-number"></span></div>
    </footer>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="clearfix">

        <!-- INFO INICIAL -->
        <div class="top-card">
            <table class="top-grid">
                <tr>
                    <td class="top-left">
                        <div class="mini-label">Cliente</div>
                        <div class="client-name">{{ $cotizacion->cliente->nombre }} {{ $cotizacion->cliente->apellido_paterno }}</div>
                        <div class="client-details">{{ $cotizacion->cliente->empresa }}</div>
                        <div class="client-details">{{ $cotizacion->cliente->correo }}</div>
                    </td>

                    <td class="top-right">
                        <div class="summary-wrap">
                            <div class="meta-box">
                                <div class="meta-row">Folio: <b>#{{ str_pad($cotizacion->id_cotizacion, 5, '0', STR_PAD_LEFT) }}</b></div>
                                <div class="meta-row">Emisión: <b>{{ $cotizacion->fecha->format('d/m/Y') }}</b></div>
                                <div class="meta-row">Vence: <b>{{ $cotizacion->fecha->addDays($cotizacion->vencimiento_dias)->format('d/m/Y') }}</b></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- PROYECTO -->
        <div class="project-box">
            <div class="project-label">Proyecto</div>
            <div class="project-title">{{ $cotizacion->titulo_cotizacion }}</div>
            @if($cotizacion->texto_introduccion)
                {{-- {!! nl2br(e(...)) !!} para respetar saltos de línea --}}
                <div class="project-intro">
                    {!! nl2br(e($cotizacion->texto_introduccion)) !!}
                </div>
            @endif
        </div>

        <!-- 3. TABLA DE PRODUCTOS -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">#</th>
                    <th style="width: 45%;">Descripción</th>
                    <th style="width: 10%; text-align: center;">Cant.</th>
                    <th style="width: 15%; text-align: right;">Precio U.</th>
                    <th style="width: 10%; text-align: right;">IVA</th>
                    <th style="width: 15%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cotizacion->detalles as $index => $item)
                <tr>
                    <td class="text-center" style="color: #888;">{{ $index + 1 }}</td>
                    <td>
                        <strong style="color: #000;">{{ $item->titulo }}</strong>
                        @if($item->descripcion)
                            <br><span style="color: #666; font-size: 10px;">{!! nl2br(e($item->descripcion)) !!}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->cantidad }}</td>
                    <td class="text-right">${{ number_format($item->precio_unitario, 2) }}</td>
                    <td class="text-right" style="color: #888;">${{ number_format($item->iva, 2) }}</td>
                    <td class="text-right font-bold text-blue">${{ number_format($item->precio_total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- 4. TOTALES (Con ISR) -->
        <div class="clearfix">
            <div class="totals-box">
                <table class="totals-table">
                    <tr>
                        <td class="total-label">Subtotal</td>
                        <td class="total-number">${{ number_format($cotizacion->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="total-label">IVA Total</td>
                        <td class="total-number">${{ number_format($cotizacion->iva_total, 2) }}</td>
                    </tr>

                    {{-- ✅ AQUI SE AGREGA LA FILA DE ISR --}}
                    @if($cotizacion->porcentaje_isr > 0)
                    <tr>
                        {{-- '+ 0' elimina ceros decimales innecesarios (ej. 10.00 -> 10) --}}
                        <td class="total-label">ISR ({{ $cotizacion->porcentaje_isr + 0 }}%)</td>
                        <td class="total-number">${{ number_format($cotizacion->retencion_isr, 2) }}</td>
                    </tr>
                    @endif

                    <tr><td colspan="2" style="height: 5px;"></td></tr>
                    <tr class="grand-total-row">
                        <td>TOTAL</td>
                        <td>${{ number_format($cotizacion->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- 5. NOTAS -->
        @if($cotizacion->notas)
        <div class="notes-section">
            <div class="notes-header">Notas y Condiciones</div>
            <div class="notes-body">{{ $cotizacion->notas }}</div>
        </div>
        @endif

    </div>
</body>
</html>