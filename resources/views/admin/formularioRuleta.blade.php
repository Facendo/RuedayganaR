<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
    <link rel="stylesheet" href="{{asset('css/cards.css')}}">
    <link rel="stylesheet" href="{{asset('css/forms.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>Configuración de la Ruleta</title>

</head>
<body>
    <br><br><br><br><br><br>

<div class="container_reg">
    <div class="cont_form">
    <!-- El formulario usa POST y multipart/form-data para enviar la data de la Ruleta -->
    <!-- RECUERDA: Debes reemplazar "/tu-ruta-de-guardado" con la URL real de tu controlador en Laravel. -->
    <form id="ruletaForm" method="POST" class="content_form" action="{{ route('ruletas.store') }}" enctype="multipart/form-data">
        <h2 class="header">Creacion de ruleta</h2>
        @csrf
        <div class="cont_input">
                
                
                <input type="hidden" class="input_form" id="id_sorteo" name="id_sorteo" placeholder="Ej: 101" value="{{$id_sorteo}}">


            
                <label for="nombre">Nombre de la Ruleta</label>
                <input type="text" class="input_form" id="nombre" name="nombre" placeholder="Ej: Ruleta de Premios Diarios" required>
            
                <label for="ruleta_type">Tipo de Ruleta</label>
                <input type="text" class="input_form" id="ruleta_type" name="type" placeholder="Ej: Clasica, Multi-nivel" required>
    
                <label for="cant_oportunidades">Cantidad de Oportunidades por Dar</label>
                <input type="number" class="input_form" id="cant_oportunidades" name="cantidad_de_opotunidades_por_dar" value="1" min="0" required>
            
                <label for="dir_imagen_ruleta">Imagen de la Ruleta (Opcional)</label>
                <input type="file" class="input_form" id="dir_imagen_ruleta" name="dir_imagen" accept="image/*">
            
                <label for="condicional_oportunidades">Condicional Oportunidades (Valor)</label>
                <input type="number" class="input_form" id="condicional_oportunidades" name="Condicional_Oportunidades" value="0" min="0" required>

        </div>
        
        
            <button type="submit" class="button submit_btn" id="submitBtn">
                Guardar Configuración de Ruleta
            </button>
        
    </form>
</div>
</div>

<!-- Script de la ruleta simplificado, ya no necesita lógica de ranuras -->
<script>
    // No se necesita JavaScript complejo ya que la creación de ranuras dinámicas ha sido eliminada.
</script>
</body>
</html>
