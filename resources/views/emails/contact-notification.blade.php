<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f8fafc; color: #1e293b; line-height: 1.5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        .header { background-color: #ec4899; padding: 20px; text-align: center; color: white; }
        .content { padding: 30px; }
        .field { margin-bottom: 20px; }
        .label { font-weight: bold; color: #ec4899; font-size: 14px; text-transform: uppercase; display: block; margin-bottom: 5px; }
        .value { color: #334155; font-size: 16px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; background-color: #f1f5f9; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nuevo Mensaje de Contacto</h2>
        </div>
        <div class="content">
            <div class="field">
                <span class="label">Nombre:</span>
                <span class="value">{{ $data['contact'] }}</span>
            </div>
            <div class="field">
                <span class="label">Correo:</span>
                <span class="value">{{ $data['email'] }}</span>
            </div>
            <div class="field">
                <span class="label">Teléfono:</span>
                <span class="value">{{ $data['phone'] }}</span>
            </div>
            <div class="field">
                <span class="label">Mensaje:</span>
                <div class="value" style="white-space: pre-wrap; background: #f8fafc; padding: 15px; border-radius: 4px; border-left: 4px solid #ec4899;">{{ $data['message'] }}</div>
            </div>
        </div>
        <div class="footer">
            Este es un mensaje automático enviado desde el sistema de contacto de {{ config('app.name') }}.
        </div>
    </div>
</body>
</html>
