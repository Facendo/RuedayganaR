<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de la Ruleta</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        :root {
            --primary: #3b82f6;
            --bg-light: #f9fafb;
            --bg-dark: #ffffff;
            --border: #e5e7eb;
            --shadow: rgba(0, 0, 0, 0.05);
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .container {
            width: 100%;
            max-width: 600px; /* Ancho ajustado para formulario simple */
            background-color: var(--bg-dark);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px var(--shadow);
            border: 1px solid var(--border);
        }
        h1 {
            color: #1f2937;
            border-bottom: 2px solid var(--border);
            padding-bottom: 10px;
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4b5563;
        }
        input:not([type="checkbox"]), select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }
        .section-ruleta {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #eff6ff;
            border-radius: 10px;
        }
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, opacity 0.3s;
        }
        .btn-primary {
            background-color: var(--primary);
            color: white;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        .footer-buttons {
            margin-top: 30px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- El formulario usa POST y multipart/form-data para enviar la data de la Ruleta -->
    <!-- RECUERDA: Debes reemplazar "/tu-ruta-de-guardado" con la URL real de tu controlador en Laravel. -->
    <form id="ruletaForm" method="POST" action="{{ route('ruletas.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="section-ruleta">
            <h1>Configuración de la Ruleta</h1>
            
            <div class="form-group">
                <label for="id_sorteo">ID del Sorteo</label>
                <input type="number" id="id_sorteo" name="id_sorteo" placeholder="Ej: 101" required>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre de la Ruleta</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Ruleta de Premios Diarios" required>
            </div>
            
            <div class="form-group">
                <label for="ruleta_type">Tipo de Ruleta</label>
                <input type="text" id="ruleta_type" name="type" placeholder="Ej: Clasica, Multi-nivel" required>
            </div>

            <div class="form-group">
                <label for="cant_oportunidades">Cantidad de Oportunidades por Dar</label>
                <input type="number" id="cant_oportunidades" name="cantidad_de_opotunidades_por_dar" value="1" min="0" required>
            </div>

            <div class="form-group">
                <label for="nro_ranuras">Número de Ranuras (Solo Referencia)</label>
                <input type="number" id="nro_ranuras" name="nro_ranuras" value="8" min="1" required>
            </div>

            <div class="form-group">
                <label for="dir_imagen_ruleta">Imagen de la Ruleta (Opcional)</label>
                <input type="file" id="dir_imagen_ruleta" name="dir_imagen" accept="image/*">
            </div>

            <div class="form-group">
                <label for="condicional_oportunidades">Condicional Oportunidades (Valor)</label>
                <input type="number" id="condicional_oportunidades" name="Condicional_Oportunidades" value="0" min="0" required>
            </div>
        </div>
        
        <div class="footer-buttons">
            <button type="submit" class="btn btn-primary" id="submitBtn">
                Guardar Configuración de Ruleta
            </button>
        </div>
    </form>
</div>

<!-- Script de la ruleta simplificado, ya no necesita lógica de ranuras -->
<script>
    // No se necesita JavaScript complejo ya que la creación de ranuras dinámicas ha sido eliminada.
</script>
</body>
</html>
