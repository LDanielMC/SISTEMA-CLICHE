<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #fff3cd; padding: 20px; text-align: center; border-bottom: 2px solid #ffeeba; }
        .content { padding: 30px 20px; background-color: #ffffff; }
        .button { display: inline-block; padding: 12px 24px; background-color: #ffc107; color: #212529; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🔔 Recordatorio de Brief Pendiente</h2>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $cliente->nombre }}</strong>,</p>
            
            <p>Notamos que aún no has completado el formulario (Brief) que te enviamos hace unos días.</p>
            <p>Tu respuesta es fundamental para que podamos avanzar con tu proyecto.</p>
            
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffc107;">
                <h3 style="margin-top: 0;">{{ $brief->titulo }}</h3>
            </div>

            <p>Por favor, complétalo lo antes posible:</p>
            
            <center>
                <a href="{{ $brief->form_url }}" class="button" target="_blank">
                    📝 Completar Formulario Ahora
                </a>
            </center>
            
            <p style="margin-top: 30px;">Enlace directo:</p>
            <p style="font-size: 13px; color: #6b7280; word-break: break-all;">
                <a href="{{ $brief->form_url }}">{{ $brief->form_url }}</a>
            </p>
        </div>
        
        <div class="footer">
            <p>Este es un mensaje automático del sistema de Cliché Marketing.</p>
        </div>
    </div>
</body>
</html>
