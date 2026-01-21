<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Suscripciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .alert-section {
            margin-bottom: 30px;
        }
        .alert-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }
        .alert-vencida {
            background: #fee2e2;
            color: #991b1b;
        }
        .alert-por-vencer {
            background: #fef3c7;
            color: #92400e;
        }
        .suscripcion-item {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .suscripcion-nombre {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .suscripcion-info {
            font-size: 14px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        .badge-mensual {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-anual {
            background: #dcfce7;
            color: #166534;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">🔔 Recordatorio de Suscripciones</h1>
        <p style="margin: 10px 0 0 0;">Sistema de Gestión - Cliché</p>
    </div>

    <div class="content">
        <p>Hola Administrador,</p>
        <p>Este es un recordatorio automático sobre el estado de las suscripciones de la empresa.</p>

        @if($suscripcionesVencidas->isNotEmpty())
            <div class="alert-section">
                <div class="alert-title alert-vencida">
                    ⚠️ Suscripciones Vencidas ({{ $suscripcionesVencidas->count() }})
                </div>
                @foreach($suscripcionesVencidas as $suscripcion)
                    <div class="suscripcion-item">
                        <div class="suscripcion-nombre">
                            {{ $suscripcion->nombre_servicio }}
                            <span class="badge badge-{{ $suscripcion->periodicidad }}">
                                {{ ucfirst($suscripcion->periodicidad) }}
                            </span>
                        </div>
                        <div class="suscripcion-info">
                            📅 Venció: {{ $suscripcion->fecha_vencimiento->format('d/m/Y') }}
                            (hace {{ abs($suscripcion->dias_restantes) }} días)<br>
                            💰 Costo: ${{ number_format($suscripcion->costo, 2) }}<br>
                            📂 Categoría: {{ $suscripcion->categoria->nombre }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($suscripcionesPorVencer->isNotEmpty())
            <div class="alert-section">
                <div class="alert-title alert-por-vencer">
                    ⏰ Suscripciones Por Vencer ({{ $suscripcionesPorVencer->count() }})
                </div>
                @foreach($suscripcionesPorVencer as $suscripcion)
                    <div class="suscripcion-item">
                        <div class="suscripcion-nombre">
                            {{ $suscripcion->nombre_servicio }}
                            <span class="badge badge-{{ $suscripcion->periodicidad }}">
                                {{ ucfirst($suscripcion->periodicidad) }}
                            </span>
                        </div>
                        <div class="suscripcion-info">
                            📅 Vence: {{ $suscripcion->fecha_vencimiento->format('d/m/Y') }}
                            (en {{ $suscripcion->dias_restantes }} días)<br>
                            💰 Costo: ${{ number_format($suscripcion->costo, 2) }}<br>
                            📂 Categoría: {{ $suscripcion->categoria->nombre }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <p style="margin-top: 30px;">
            <strong>Recomendación:</strong> Revisa las suscripciones vencidas y procede con las renovaciones necesarias para evitar interrupciones en los servicios.
        </p>

        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/suscripciones') }}" style="display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Ver Todas las Suscripciones
            </a>
        </p>
    </div>

    <div class="footer">
        <p>Este es un correo automático generado por el sistema de gestión.<br>
        No responder a este correo.</p>
    </div>
</body>
</html>
