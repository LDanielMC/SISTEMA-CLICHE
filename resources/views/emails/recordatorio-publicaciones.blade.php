<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Publicaciones</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 650px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #e91e63;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .header h1 {
            color: #e91e63;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 10px 0 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .section-title.atrasadas {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
        .section-title.hoy {
            background-color: #fff3e0;
            color: #e65100;
            border-left: 4px solid #e65100;
        }
        .section-title.manana {
            background-color: #e3f2fd;
            color: #1565c0;
            border-left: 4px solid #1565c0;
        }
        .publicacion {
            background-color: #fafafa;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .publicacion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .cliente {
            font-weight: bold;
            color: #333;
            font-size: 15px;
        }
        .plataforma {
            display: inline-block;
            background-color: #e91e63;
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 12px;
        }
        .formato {
            color: #666;
            font-size: 13px;
        }
        .fecha-atrasada {
            color: #c62828;
            font-size: 12px;
            font-weight: bold;
        }
        .copy-preview {
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
            color: #555;
            margin-top: 8px;
            border-left: 3px solid #e91e63;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #888;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            background-color: #e91e63;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: center;
        }
        .summary-item {
            display: inline-block;
            margin: 0 15px;
            text-align: center;
        }
        .summary-number {
            font-size: 28px;
            font-weight: bold;
        }
        .summary-label {
            font-size: 12px;
            color: #666;
        }
        .atrasadas-num { color: #c62828; }
        .hoy-num { color: #e65100; }
        .manana-num { color: #1565c0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📅 Recordatorio de Publicaciones</h1>
            <p>{{ now()->format('d/m/Y') }} - Cliché Marketing Digital</p>
        </div>

        <div class="summary">
            @if(count($publicacionesAtrasadas) > 0)
            <div class="summary-item">
                <div class="summary-number atrasadas-num">{{ count($publicacionesAtrasadas) }}</div>
                <div class="summary-label">Atrasadas</div>
            </div>
            @endif
            <div class="summary-item">
                <div class="summary-number hoy-num">{{ count($publicacionesHoy) }}</div>
                <div class="summary-label">Para Hoy</div>
            </div>
            <div class="summary-item">
                <div class="summary-number manana-num">{{ count($publicacionesManana) }}</div>
                <div class="summary-label">Para Mañana</div>
            </div>
        </div>

        @if(count($publicacionesAtrasadas) > 0)
        <div class="section">
            <div class="section-title atrasadas">
                ⚠️ Publicaciones Atrasadas ({{ count($publicacionesAtrasadas) }})
            </div>
            @foreach($publicacionesAtrasadas as $pub)
            <div class="publicacion">
                <div class="publicacion-header">
                    <span class="cliente">{{ $pub->cliente_nombre }}</span>
                    <span class="plataforma">{{ $pub->plataforma_nombre }}</span>
                </div>
                <div class="formato">{{ $pub->formato_nombre }}</div>
                <div class="fecha-atrasada">📆 Fecha programada: {{ \Carbon\Carbon::parse($pub->fecha)->format('d/m/Y') }}</div>
                @if($pub->copy)
                <div class="copy-preview">{{ Str::limit($pub->copy, 100) }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        @if(count($publicacionesHoy) > 0)
        <div class="section">
            <div class="section-title hoy">
                🔔 Para Hoy ({{ count($publicacionesHoy) }})
            </div>
            @foreach($publicacionesHoy as $pub)
            <div class="publicacion">
                <div class="publicacion-header">
                    <span class="cliente">{{ $pub->cliente_nombre }}</span>
                    <span class="plataforma">{{ $pub->plataforma_nombre }}</span>
                </div>
                <div class="formato">{{ $pub->formato_nombre }}</div>
                @if($pub->copy)
                <div class="copy-preview">{{ Str::limit($pub->copy, 100) }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        @if(count($publicacionesManana) > 0)
        <div class="section">
            <div class="section-title manana">
                📋 Para Mañana ({{ count($publicacionesManana) }})
            </div>
            @foreach($publicacionesManana as $pub)
            <div class="publicacion">
                <div class="publicacion-header">
                    <span class="cliente">{{ $pub->cliente_nombre }}</span>
                    <span class="plataforma">{{ $pub->plataforma_nombre }}</span>
                </div>
                <div class="formato">{{ $pub->formato_nombre }}</div>
                @if($pub->copy)
                <div class="copy-preview">{{ Str::limit($pub->copy, 100) }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ route('calendario.index') }}" class="btn">Ver Calendario de Publicaciones</a>
        </div>

        <div class="footer">
            <p>Este es un correo automático generado por el sistema SGI de Cliché Marketing Digital.</p>
            <p>© {{ date('Y') }} Cliché Marketing Digital</p>
        </div>
    </div>
</body>
</html>
