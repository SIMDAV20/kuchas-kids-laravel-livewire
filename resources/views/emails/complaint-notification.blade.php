<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f7f6; padding-bottom: 40px; }
        .main-table { width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; border-collapse: collapse; overflow: hidden; margin-top: 40px; border: 1px solid #e1e1e1; }
        .header { background-color: #2563eb; color: #ffffff; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .section-header { border-bottom: 2px solid #2563eb; margin-bottom: 20px; padding-bottom: 5px; }
        .section-title { font-size: 16px; font-weight: bold; color: #2563eb; text-transform: uppercase; margin: 0; }
        .field-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .label { font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: bold; padding-bottom: 4px; }
        .value { font-size: 15px; color: #1e293b; font-weight: 600; padding-bottom: 15px; }
        .type-badge { background-color: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; padding: 10px; border-radius: 4px; font-weight: bold; text-align: center; }
        .message-box { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 6px; color: #334155; font-size: 14px; line-height: 1.6; }
        .footer { padding: 25px; text-align: center; font-size: 12px; color: #94a3b8; background-color: #fafafa; }
    </style>
</head>
<body>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="main-table" width="600" cellpadding="0" cellspacing="0">
                    <!-- CABECERA -->
                    <tr>
                        <td class="header">
                            <h1 style="margin:0; font-size:24px;">Libro de Reclamaciones</h1>
                            <p style="margin:5px 0 0; font-size:14px; opacity:0.9;">Registro de Reclamo Virtual</p>
                        </td>
                    </tr>

                    <!-- CONTENIDO -->
                    <tr>
                        <td class="content">
                            <!-- SECCIÓN 1: DATOS DEL CONSUMIDOR -->
                            <div class="section-header">
                                <p class="section-title">1. Datos del Consumidor</p>
                            </div>
                            <table class="field-table" width="100%">
                                <tr>
                                    <td colspan="2">
                                        <div class="label">Nombres completos:</div>
                                        <div class="value">{{ $data['fullName'] }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <div class="label">DNI / CE:</div>
                                        <div class="value">{{ $data['documentId'] }}</div>
                                    </td>
                                    <td width="50%">
                                        <div class="label">Teléfono:</div>
                                        <div class="value">{{ $data['phone'] }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="label">Email:</div>
                                        <div class="value">{{ $data['email'] }}</div>
                                    </td>
                                </tr>
                            </table>

                            <!-- SECCIÓN 2: DETALLE DEL RECLAMO -->
                            <div class="section-header">
                                <p class="section-title">2. Detalle del Reclamo o Queja</p>
                            </div>
                            <table class="field-table" width="100%">
                                <tr>
                                    <td width="40%" valign="middle">
                                        <div class="type-badge">
                                            {{ strtoupper($data['type']) }}
                                        </div>
                                    </td>
                                    <td width="60%" style="padding-left: 20px;">
                                        @if($data['orderNumber'])
                                            <div class="label">Número de pedido:</div>
                                            <div class="value">{{ $data['orderNumber'] }}</div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-top: 15px;">
                                        <div class="label">Hechos reclamados:</div>
                                        <div class="message-box">
                                            {{ $data['description'] }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- SECCIÓN 3: DETALLE DEL PEDIDO -->
                            <div class="section-header">
                                <p class="section-title">3. Detalle del Pedido del Consumidor</p>
                            </div>
                            <div class="label">Lo que solicita:</div>
                            <div class="message-box" style="border-left: 4px solid #2563eb;">
                                {{ $data['consumerRequest'] }}
                            </div>
                        </td>
                    </tr>

                    <!-- PIE DE PÁGINA -->
                    <tr>
                        <td class="footer">
                            Atención: Se debe dar respuesta a este registro en un plazo máximo de 15 días hábiles conforme a ley.
                            <br><br>
                            © {{ date('Y') }} {{ config('app.name') }} - Gestión de Reclamaciones
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
