<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forms</title>
</head>
<body>

@if (session('success'))
    <div class="message_success" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="message_error" role="alert">
        <strong >¡Ups!</strong>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
    </div>
 @endif

 <div id="sorteo-data" data-precio-dolar="{{ $sorteo->precio_boleto_dolar }}" data-precio-bs="{{ $sorteo->precio_boleto_bs }}"></div>



@php
    $numeros_disponibles = json_decode($sorteo->numeros_disponibles, true);
    $cantidad_disponible = count($numeros_disponibles);
@endphp


<section id="compra" class="container container_compra">
    
    <form action="{{route("cliente.store")}}" method="POST" class="cont_form" enctype="multipart/form-data">

    <div class="header">
        <h1>Seleccionar los tickets</h1>
    </div>
    @csrf
    <input type="hidden" id="id_sorteo" name="id_sorteo" value="{{$sorteo->id_sorteo}}" required>
    <div class="contador_tickets">
        <div class="container_tick">
            <button class="selector_ticket">5 tickets</button>
            <button class="selector_ticket">10 tickets</button>
            <button class="selector_ticket">20 tickets</button>
            <button class="selector_ticket">50 tickets</button>
        </div>

        <div class="cont_counter">
            <button class="counter_btn" id="resta">-</button>
            <div class="counter_value cant_boletos">0</div>
            <button class="counter_btn" id="suma">+</button>
        </div>

        <h3 class="monto"></h3>

    </div>

    <div class="cont_input">
        <h2>Datos de compra</h2>

        <div class="content_form">
            <label for="cedula">Cedula:</label>
            <input type="text" placeholder="cedula" id="cedula" name="cedula" required>
            <label for="nombre_y_apellido">Nombre y Apellido:</label>
            <input type="text" placeholder="nombre y apellido" id="nombre_y_apellido" name="nombre_y_apellido" required>
            <label for="telefono">Telefono:</label>
            <input type="text" placeholder="telefono" id="telefono" name="telefono" required>
            <label for="correo">Correo:</label>
            <input type="text" placeholder="correo" id="correo" name="correo" required>

            <label for="metodo_pago">METODO DE PAGO</label>
            <div class="icons_pago">
                <img src="banesco_logo.png" alt="Pago Móvil">
                <img src="banplus_logo.png" alt="Pago movil">
                <img src="binance_logo.png" alt="Zelle">
            </div>

            <input type="hidden" id="cantidad_de_tickets" name="cantidad_de_tickets" class="input_form"  required min="1" max="{{$cantidad_disponible}}">
            <input type="hidden"  id="monto" name="monto" class="input_form" required>

            <label for="referencia">Referencia de pago:</label>
            <input type="text" placeholder="referencia de pago" id="referencia" name="referencia" class="input_form" required>
            <label for="fecha_de_pago">Fecha de pago:</label>
            <input type="date" placeholder="fecha de pago" id="fecha_de_pago" name="fecha_de_pago" class="input_form" required>

            <label for="comprobante">SUBIR COMPROBANTE DE PAGO</label>
            <div class="carga_comprobante">
                <input type="file" id="imagen_comprobante" name="imagen_comprobante" accept="image/png, image/jpeg, image/jpg" class="input_file"  required>
            </div>
            
            <button class="submit_btn button">ENVIAR</button>
        </div>

        <div class="image_section">
            <img src="rueda.jpg" alt="Rueda y Gana con Nosotros">
        </div>
    </div>
    
    </form>

</section>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inputImagen = document.getElementById('imagen_comprobante');
        const mensajeCarga = document.getElementById('mensajeCargaImagen');
        const miFormulario = document.querySelector('.form'); 

        
        inputImagen.addEventListener('change', () => {
            if (inputImagen.files.length > 0) {
                const fileName = inputImagen.files[0].name;
                mensajeCarga.textContent = `Archivo seleccionado: ${fileName}. Listo para subir.`;
                mensajeCarga.style.display = 'block'; 
                mensajeCarga.style.color = '#3498db'; 
            } else {
                mensajeCarga.textContent = ''; 
                mensajeCarga.style.display = 'none'; 
            }
        });

    
        miFormulario.addEventListener('submit', () => {
            
            if (inputImagen.files.length > 0) {
                mensajeCarga.textContent = 'Subiendo comprobante... Por favor, espera.';
                mensajeCarga.style.display = 'block';
                mensajeCarga.style.color = '#e67e22'; 
            }

        });

    });
</script>
    

<script src="{{asset('js/manejo_tickets.js')}}"></script>

</body>
</html>