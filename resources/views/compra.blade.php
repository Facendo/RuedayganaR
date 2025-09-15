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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Cal+Sans&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&family=Rubik+Mono+One&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/favicon-32x32.png')}}">
    <title>Forms</title>
</head>
<body>

<script>
    window.AppConfig = {
        copyIconUrl: "{{ asset('img/copy.png') }}", // Ruta corregida
        successIconUrl: "{{ asset('img/like.png') }}", // Ruta corregida
        errorIconUrl: "{{ asset('img/dislike.png') }}" // Ruta corregida
    };
</script>
<script src="{{ asset('js/validate.js') }}"></script>
<script src="{{ asset('js/precargado.js') }}"></script>

<div id="session-messages" style="display:none;"
    data-success="{{ session('success') }}"
    data-error="{{ session('error') }}"
></div>

<div id="dynamic-message-container" class="message-container">
    {{-- El mensaje se insertará aquí con JavaScript --}}
</div>

 <div id="sorteo-data" data-precio-dolar="{{ $sorteo->precio_boleto_dolar }}" data-precio-bs="{{ $sorteo->precio_boleto_bs }}"></div>



@php
    $numeros_disponibles = json_decode($sorteo->numeros_disponibles, true);
    $cantidad_disponible = count($numeros_disponibles);
@endphp



<section id="compra" class="container container_compra">
    
    <div class="cont_form">

        <form action="{{route("cliente.store")}}" method="POST" class="cont_form" enctype="multipart/form-data" id="reg_compra">
            <input type="hidden" id="cantidad_de_tickets" name="cantidad_de_tickets" required>
            <input type="hidden" id="monto" name="monto" required>
            <div class="header">
                <h1>Seleccionar los tickets</h1>
            </div>
            @csrf
            <input type="hidden" id="id_sorteo" name="id_sorteo" value="{{$sorteo->id_sorteo}}" required>
            <div class="contador_tickets">
                <div class="container_tick">
                    <div class="selector_ticket">2 tickets</div>
                    <div class="selector_ticket">5 tickets</div>
                    <div class="selector_ticket">10 tickets</div>
                    <div class="selector_ticket">20 tickets</div>
                </div>

                <div class="cont_counter">
                    <div class="counter_btn" id="resta">-</div>
                    <div class="counter_value cant_boletos">0</div>
                    <div class="counter_btn" id="suma">+</div>
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

            <label>METODO DE PAGO</label>
            <div class="icons_pago">
                <img src="{{asset('img/banesco_logo.png')}}" alt="Pago Móvil Banesco" data-metodo="Pago movil Banesco" class="circle_pago">
                <img src="{{asset('img/banplus_logo.png')}}" alt="Pago Móvil Banplus" data-metodo="Pago movil Banplus" class="circle_pago">
                <img src="{{asset('img/zelle_logo.webp')}}" alt="Zelle" data-metodo="Zelle" class="circle_pago">
                <img src="{{asset('img/binance_logo.png')}}" alt="Binance" data-metodo="Binance" class="circle_pago">
                <img src="{{asset('img/zinli_logo.jpg')}}" alt="Zinli" data-metodo="Zinli" class="circle_pago">
            </div>
            
            <div class="data_p">
                <p>Por favor, selecciona un método de pago para ver los detalles.</p>
            </div>

            <input type="hidden" id="metodo_pago_seleccionado" name="metodo_pago_seleccionado" required>

            <label for="referencia">Referencia de pago:</label>
            <div class="data_p">
            <p>Advertencia! ingrese la referencia de pago completa. De lo contrario su pago no sera procesado.</p>
            </div>
            <input type="text" placeholder="referencia de pago" id="referencia" name="referencia" class="input_form" required>
            <label for="fecha_de_pago">Fecha de pago:</label>
            <input type="date" placeholder="fecha de pago" id="fecha_de_pago" name="fecha_de_pago" class="input_form" required>

            <label for="comprobante">SUBIR COMPROBANTE DE PAGO</label>
            <div class="carga_comprobante">
                <input type="file" id="imagen_comprobante" name="imagen_comprobante" accept="image/png, image/jpeg, image/jpg" class="input_file" required>
            </div>
            
                <button class="submit_btn button">ENVIAR</button>

                <div id="message"></div>
            </div>

            

                <div class="image_section">
                    <img src="{{asset('img/rueda.png')}}" alt="Rueda y Gana con Nosotros">
                </div>
            </div>
            
        </form>

    </div>

</section>

<script src="{{asset('js/data_pago.js')}}"></script>

<script src="{{asset('js/manejo_tickets.js')}}"></script>



<!-- <script>
    //Funcion para precargar los datos del cliente
    const clientes= json($clientes);

    inputCedula= document.getElementById('cedula');

   inputCedula.addEventListener('input', () => {
       const cedula = inputCedula.value;
       const cliente = clientes.find(c => c.cedula === cedula);
       if (cliente) {
           document.getElementById('nombre_y_apellido').value = cliente.nombre_y_apellido;
           document.getElementById('telefono').value = cliente.telefono;
           document.getElementById('correo').value = cliente.correo;
       }
   });
</script> -->


<script>
    
</script>

    <footer id="foot">

    <div class="container">
        <div class="contenido_foot">
            

            <div class="cont_foot foot2">
                <h2 class="slogan_footer">GRACIAS POR VISITAR</h2>
                <p class="text_footer">“Aquí no hay suerte, hay propósito.
Dios guía cada jugada y tú solo tienes que jugar pa’ ganar.
Bienvenido a donde los sueños se hacen realidad:
¡Rueda y Gana con Nosotros!”</p>
            </div>
            
            <div class="cont_foot foot3">
                <h2 class="slogan_footer">Redes Sociales</h2>
                <a href="https://www.instagram.com/carlitospaz0?igsh=czNscDg4dGxwejI3"><img src="{{asset('img/instagram.png')}}" alt="instagram.pnp" class="icon_contact"></a>
                <a href="https://www.tiktok.com/@enriquepaz.01?_t=ZM-8wKbc4qvL7v&_r=1"><img src="{{asset('img/tik-tok.png')}}" alt="tiktok.pnp" class="icon_contact"></a>
                <a href="https://api.whatsapp.com/send?phone=584248676344&text=Hola%2C%20Quisiera%20comunicarme%20con%20rueda%20y%20gana.com"><img src="{{asset('img/whatsapp.png')}}" alt="whatsapp.pnp" class="icon_contact"></a>
            </div>


            <div class="cont_foot foot4">
            <h2 class="slogan_footer">Consulte:</h2> <br> 

                <p class="text_footer">Antes de realizar alguna operacion, visite nuestros <br>
                     <a href="{{Route("terminos")}}" class="enlace"> Terminos y Condiciones</a> y <a href="{{Route("politica")}}"" class="enlace">Politicas de privacidad</a>
                </p>
                <br>
                <p class="text_footer">© 2025 Rueda y Gana con Nosotros. Todos los derechos reservados.</p>
                
            </div>

        </div>
    </div>

</footer>




</body>
</html>