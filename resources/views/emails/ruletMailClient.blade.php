<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Felicidades, Has Ganado!</title>
</head>
<body style="margin: 0; padding: 0;">
    <table cellpadding="0" cellspacing="0" width="100%" style="background-color: #f7f7f7; font-family: Arial, sans-serif; font-size: 16px;">
        <tr>
            <td align="center" style="padding: 30px 0 10px;">
                <h2 style="color: #4CAF50; margin: 0; font-size: 28px;">¡FELICIDADES {{ $nombre }}! 🎉</h2>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 0 20px;">
                <p style="margin: 0; line-height: 1.5; color: #333333;">La ruleta ha girado a tu favor. ¡Eres el afortunado ganador!</p>
            </td>
        </tr>
        
        <tr>
            <td align="center" style="padding: 20px 20px 10px;">
                <table cellpadding="0" cellspacing="0" style="width: 85%; background-color: #2196F3; border-radius: 10px; color: #ffffff;">
                    <tr>
                        <td align="center" style="padding: 20px;">
                            <p style="margin: 0 0 10px; font-size: 18px; font-weight: bold;">El Número Ganador es:</p>
                            <h1 style="color: #FFEB3B; margin: 5px 0; font-size: 48px;">{{ $numero_ganador }}</h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td align="center" style="padding: 10px 20px;">
                <table cellpadding="0" cellspacing="0" style="width: 85%; background-color: #ffffff; border: 2px solid #2196F3; border-radius: 10px; padding: 10px;">
                    <tr>
                        <td align="center" style="padding: 15px;">
                            <p style="margin: 0 0 10px; font-size: 18px; color: #333333;">Has ganado el siguiente premio:</p>
                            <h3 style="color: #E91E63; margin: 5px 0; font-size: 24px;">{{ $premio }}</h3>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td align="center" style="padding: 30px 20px 5px;">
                <p style="margin: 0; line-height: 1.5; font-weight: bold; color: #4CAF50;">¡Comunícate de inmediato!</p>
                <p style="margin: 5px 0 0; line-height: 1.5; color: #666666;">Por favor, contacta con nosotros a través de nuestras redes sociales o un canal de soporte para coordinar la entrega de tu premio.</p>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px 20px 40px; border-top: 1px solid #eeeeee; margin-top: 20px;">
                <p style="color: gray; margin: 0; font-size: 14px;">— El equipo de [Nombre de tu Ruleta/Sorteo]</p>
            </td>
        </tr>
    </table>
</body>
</html>