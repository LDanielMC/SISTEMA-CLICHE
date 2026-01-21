<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-bottom: 2px solid #e9ecef; }
        .content { padding: 30px 20px; background-color: #ffffff; }
        .button { display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nuevo Brief Asignado</h2>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $brief->cliente->nombre }}</strong>,</p>
            
            <p>Se te ha asignado un nuevo formulario (Brief) para completar. Tu retroalimentación es muy importante para nosotros.</p>
            
            <div style="background-color: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h3 style="margin-top: 0;">{{ $brief->titulo }}</h3>
                <p>{{ $brief->descripcion }}</p>
            </div>

            <p>Por favor, tómate unos minutos para responder las preguntas haciendo clic en el siguiente botón:</p>
            
            <center>
                <a href="{{ $brief->form_url }}" class="button" target="_blank">
                    📝 Responder Formulario
                </a>
            </center>
            
            <p style="margin-top: 30px;">Si el botón no funciona, puedes copiar y pegar el siguiente enlace en tu navegador:</p>
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
