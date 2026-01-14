<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Evento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .alert {
            background-color: #fef3cd;
            border-left: 4px solid #f0ad4e;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .event-details {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            width: 120px;
            color: #6c757d;
        }
        .detail-value {
            flex: 1;
            color: #212529;
        }
        .participants {
            margin-top: 15px;
        }
        .participant {
            padding: 8px 12px;
            background-color: #e7f3ff;
            border-radius: 4px;
            margin: 5px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Recordatorio de Evento</h1>
        </div>
        
        <div class="content">
            <div class="alert">
                <strong>⏰ Tu evento comienza en {{ $tiempoAntes }}</strong>
            </div>
            
            <h2 style="color: #667eea; margin-top: 0;">{{ $evento->titulo }}</h2>
            
            <div class="event-details">
                <div class="detail-row">
                    <div class="detail-label">📅 Fecha:</div>
                    <div class="detail-value">{{ $evento->fecha->format('d/m/Y') }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">🕐 Hora:</div>
                    <div class="detail-value">
                        {{ date('H:i', strtotime($evento->hora_inicio)) }} - {{ date('H:i', strtotime($evento->hora_fin)) }}
                    </div>
                </div>
                
                @if($evento->lugar)
                <div class="detail-row">
                    <div class="detail-label">📍 Lugar:</div>
                    <div class="detail-value">{{ $evento->lugar }}</div>
                </div>
                @endif
                
                @if($evento->cliente)
                <div class="detail-row">
                    <div class="detail-label">👤 Cliente:</div>
                    <div class="detail-value">{{ $evento->cliente->nombre }}</div>
                </div>
                @endif
                
                @if($evento->notas)
                <div class="detail-row">
                    <div class="detail-label">📝 Notas:</div>
                    <div class="detail-value">{{ $evento->notas }}</div>
                </div>
                @endif
            </div>
            
            @if($evento->participantes->count() > 0)
            <div class="participants">
                <h3 style="color: #6c757d; font-size: 16px;">👥 Participantes:</h3>
                @foreach($evento->participantes as $participante)
                <div class="participant">
                    <strong>{{ $participante->nombre }}</strong>
                    <br>
                    <small>{{ $participante->correo }}</small>
                </div>
                @endforeach
            </div>
            @endif
            
            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/eventos" class="button">
                    Ver en Calendario
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>Este es un recordatorio automático del sistema de gestión de eventos.</p>
            <p>{{ config('app.name') }} &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
