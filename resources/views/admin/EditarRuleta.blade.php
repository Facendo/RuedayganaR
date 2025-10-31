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
    <title>Edición de Configuración de la Ruleta</title>
    
</head>

<style>
    .id-display {
            background-color: rgb(254, 180, 3);
            color: #1f2937;
            font-size: 0.9em;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: 6px;
        }
</style>

<body>

<br><br><br><br><br><br>

<div class="container_reg">
    <div class="cont_form">
        <form id="ruletaEditForm" class="content_form" method="POST" action="{{ route('ruletas.update', $ruleta->id_ruleta) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        
            <h2 class="section_subtitle">
                Edición de Ruleta
                <span class="id-display">ID: {{ $ruleta->id_ruleta }}</span>
            </h1>

            <input type="hidden" name="id" value="{{ $ruleta->id_ruleta }}" required>
            
            <div class="cont_input">
                <label for="id_sorteo">ID del Sorteo</label>
                <input type="number" class="input_form" id="id_sorteo" name="id_sorteo" value="{{ old('id_sorteo', $ruleta->id_sorteo) }}"  required>
    
                <label for="nombre">Nombre de la Ruleta</label>
                <input type="text" class="input_form" id="nombre" name="nombre" value="{{ old('nombre', $ruleta->nombre) }}"  required>

                <label for="cant_oportunidades">Cantidad de Oportunidades por Dar</label>
                <input type="number" class="input_form" id="cant_oportunidades" name="cantidad_de_opotunidades_por_dar" value="{{ $ruleta->cantidad_de_opotunidades_por_dar }}" min="0" required>

                <label for="dir_imagen_ruleta">Imagen de la Ruleta (Opcional) <small>(dejar vacío para no cambiar)</small></label>
                <input type="file" class="input_form" id="dir_imagen_ruleta" name="dir_imagen" accept="image/*">

                <label for="condicional_oportunidades">Condicional Oportunidades (Valor)</label>
                <input type="number" class="input_form" id="condicional_oportunidades" name="Condicional_Oportunidades" value="{{ old('condicional_oportunidades', $ruleta->condicional_oportunidades) }}" min="0" required>
            </div>
        
            <button type="submit" class="button submit_btn" id="submitBtn">
                Actualizar Configuración de Ruleta
            </button>
        
    </form>
    </div>
</div>

</body>
</html>