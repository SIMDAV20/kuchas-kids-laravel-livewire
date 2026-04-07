<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f1f5f9; color: #1e293b; line-height: 1.5; margin: 0; padding: 0; }
        .container { max-width: 650px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        .header { background-color: #2563eb; padding: 25px; text-align: center; color: white; border-bottom: 4px solid #1d4ed8; }
        .content { padding: 35px; }
        .section-title { font-size: 16px; font-weight: 800; color: #2563eb; text-transform: uppercase; border-bottom: 2px solid #2563eb/20; padding-bottom: 8px; margin-bottom: 20px; }
        .grid { display: grid; grid-template-cols: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .field { margin-bottom: 18px; }
        .label { font-weight: bold; color: #64748b; font-size: 13px; text-transform: uppercase; display: block; margin-bottom: 4px; }
        .value { color: #1e293b; font-size: 15px; font-weight: 600; }
        .message-box { background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px dashed #cbd5e1; color: #334155; font-size: 14px; white-space: pre-wrap; margin-top: 10px; }
        .footer { padding: 25px; text-align: center; font-size: 12px; color: #94a3b8; background-color: #f8fafc; border-top: 1px solid #e2e8f0; }
        .highlight { font-weight: 800; color: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size:24px;">Libro de Reclamaciones</h1>
            <p style="margin:5px 0 0; opacity:0.9;">Registro de {{ $data['type'] }} Virtual</p>
        </div>
        <div class="content">
            <div class="section-title">1. Datos del Consumidor</div>
            <div style="margin-bottom: 30px;">
                <div class="field">
                    <span class="label">Nombres completos:</span>
                    <span class="value">{{ $data['fullName'] }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <div class="field" style="flex:1;">
                        <span class="label">DNI / CE:</span>
                        <span class="value">{{ $data['documentId'] }}</span>
                    </div>
                    <div class="field" style="flex:1;">
                        <span class="label">Teléfono:</span>
                        <span class="value">{{ $data['phone'] }}</span>
                    </div>
                </div>
                <div class="field">
                    <span class="label">Email:</span>
                    <span class="value">{{ $data['email'] }}</span>
                </div>
            </div>

            <div class="section-title">2. Detalle del Reclamo o Queja</div>
            <div style="margin-bottom: 30px;">
                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div class="field" style="background: #eff6ff; padding: 10px 20px; border-radius: 6px; border: 1px solid #bfdbfe;">
                        <span class="label" style="color:#2563eb">Tipo:</span>
                        <span class="value highlight" style="font-size: 18px;">{{ strtoupper($data['type']) }}</span>
                    </div>
                    @if($data['orderNumber'])
                    <div class="field">
                        <span class="label">Número de pedido:</span>
                        <span class="value">{{ $data['orderNumber'] }}</span>
                    </div>
                    @endif
                </div>
                <div class="field">
                    <span class="label">Hechos reclamados:</span>
                    <div class="message-box">{{ $data['description'] }}</div>
                </div>
            </div>

            <div class="section-title">3. Detalle del Pedido del Consumidor</div>
            <div class="field">
                <span class="label">Lo que solicita:</span>
                <div class="message-box" style="border-left: 4px solid #2563eb;">{{ $data['consumerRequest'] }}</div>
            </div>
        </div>
        <div class="footer">
            Atención: Se debe dar respuesta a este registro en un plazo máximo de 15 días hábiles conforme a ley.
            <br><br>
            © {{ date('Y') }} {{ config('app.name') }} - Gestión de Reclamaciones
        </div>
    </div>
</body>
</html>
