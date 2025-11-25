<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo de Confirmación</title>
</head>
<body style="margin: 0; padding: 0;">
    <table cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; font-family: Arial, sans-serif; font-size: 16px;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <h2 style="color: #333333; margin: 0;">¡Gracias por participar, {{ $nombre }}!</h2>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 0 20px;">
                <p style="margin: 0; line-height: 1.5;">Tu número de ticket asignado es:</p>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 10px 20px;">
                <table cellpadding="0" cellspacing="0" style="width: 85%; background-color: rgb(220, 220, 200); border-radius: 10px;">
                    <tr>
                        <td align="center" style="padding: 15px;">
                            @foreach ($numeros as $numero)
                                <h1 style="color: gold; margin: 5px 0; display: inline-block;">-{{ $numero }}</h1>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px 20px 5px;">
                <p style="margin: 0; line-height: 1.5; font-weight: bold;">¡NO BORRES ESTE CORREO ELECTRÓNICO!</p>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 5px 20px;">
                <p style="margin: 0; line-height: 1.5;">Alguna duda, comuníquese a nuestras redes sociales.</p>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px 20px 5px;">
                <p style="margin: 0; line-height: 1.5;">Te deseamos la mejor de las suertes. Gracias por apoyar nuestros sorteos. ¡Recuerda seguirnos en Instagram para estar pendiente de los ganadores!</p>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 5px 20px 20px;">
                <p style="margin: 0; line-height: 1.5;">¡Te deseamos mucha suerte y bendiciones! ✨</p>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px 20px 40px; border-top: 1px solid #eeeeee;">
                <p style="color: gray; margin: 0;">— Rueda y Gana con Nosotros</p>
            </td>
        </tr>
    </table>
</body>
</html>