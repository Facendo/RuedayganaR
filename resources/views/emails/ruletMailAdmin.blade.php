<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALERTA: Nuevo Ganador Asignado</title>
</head>
<body style="margin: 0; padding: 0;">
    <table cellpadding="0" cellspacing="0" width="100%" style="background-color: #f0f0f0; font-family: Arial, sans-serif; font-size: 16px;">
        <tr>
            <td align="center" style="padding: 30px 0 10px;">
                <h2 style="color: #D32F2F; margin: 0; font-size: 26px;">🔴 ALERTA: NUEVO GANADOR ASIGNADO</h2>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 0 20px;">
                <p style="margin: 0; line-height: 1.5; color: #333333;">Se ha registrado un nuevo ganador en la ruleta. A continuación, los detalles para el contacto y gestión del premio.</p>
            </td>
        </tr>
        
        <tr>
            <td align="center" style="padding: 20px 20px 10px;">
                <table cellpadding="0" cellspacing="0" style="width: 85%; background-color: #ffffff; border: 2px solid #D32F2F; border-radius: 10px; padding: 10px;">
                    <tr>
                        <td style="padding: 15px;">
                            <h3 style="color: #333333; margin-top: 0; margin-bottom: 15px;">Detalles del Ganador:</h3>
                            
                            <p style="margin: 0 0 10px; font-size: 18px; color: #333333;">
                                <strong>👤 Nombre:</strong> {{ $nombre }}
                            </p>
                            
                            <!-- AÑADIDOS -->
                            <p style="margin: 0 0 10px; font-size: 18px; color: #333333;">
                                <strong>💳 Cédula:</strong> {{ $cedula_cliente }}
                            </p>
                            
                            <p style="margin: 0 0 15px; font-size: 18px; color: #333333;">
                                <strong>📞 Contacto (Teléfono):</strong> {{ $contacto }}
                            </p>
                            <!-- FIN AÑADIDOS -->

                            <hr style="border: 0; border-top: 1px solid #eeeeee; margin: 15px 0;">

                            <p style="margin: 0 0 10px; font-size: 20px; color: #333333;">
                                <strong>🎁 Premio Asignado:</strong> <span style="font-weight: bold; color: #D32F2F;">{{ $premio }}</span>
                            </p>
                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td align="center" style="padding: 30px 20px 5px;">
                <p style="margin: 0; line-height: 1.5; font-weight: bold; color: #333333;">Acción Requerida:</p>
                <p style="margin: 5px 0 0; line-height: 1.5; color: #666666;">
                    Por favor, ingrese al panel de administración para verificar los datos y proceder con la gestión de la entrega del premio.
                </p>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px 20px 20px; border-top: 1px solid #cccccc;">
                <p style="color: gray; margin: 0; font-size: 12px;">Notificación automática del sistema de sorteos.</p>
            </td>
        </tr>
    </table>
</body>
</html>