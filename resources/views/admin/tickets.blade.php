<!DOCTYPE html>
<html lang="en">
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

    <title>Manejo de Tickets</title>

</head>
<body>


<style>
        :root {
            --dark-orange: #e57200;
            --orange: #ff9100;
            --selected_red: #db322cff;
            --light-orange: #ffe0b2;
            --dark-blue: #001f3f;
            --blue: #007bff;
            --light-grey: #f0f0f0;
            --text-color: #333;
        }

        body {
            background-color: #232323; /* Dark background from your CSS */
            color: #fff; /* White text for dark background */
            font-family: 'Poppins', sans-serif;
        }

        .container_tickets {
            max-width: 900px;
            margin: 20px auto;
            padding: 30px;
            background-color: #646464; /* Dark gray from your CSS */
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .section_subtitle {
            font-size: 2rem;
            color: var(--orange);
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .section_subtile{
            color: var(--orange);
        }

        p {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: #fff;
        }

        .numeros_tickets {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }

        .checkbox_ticket {
            display: none;
        }

        .lbl_check {
            display: block;
            padding: 15px 5px;
            border-radius: 8px;
            background-color: #8c8c8c;
            border: 2px solid #8c8c8c;
            color: #fff;
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            user-select: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .checkbox_ticket:checked + .lbl_check {
            background-color: var(--selected_red);
            border-color: var(--selected_red);
            color: var(--text-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(255, 145, 0, 0.3);
        }

        .lbl_check:hover {
            background-color: var(--orange);
            border-color: var(--orange);
            color: var(--text-color);
        }

        #mensaje_validacion {
            margin-top: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .botones_acciones button, .button {
            background-color: var(--dark-orange);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin: 15px 5px;
        }

        .botones_acciones button:disabled, .button:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .botones_acciones button:hover:not(:disabled), .button:hover:not(:disabled) {
            background-color: #c95c00;
            transform: translateY(-2px);
        }
        
        .cont_form {
            background-color: #464646; /* Lighter dark grey */
            color: #fff;
        }
        
        .cont_form h2 {
            color: var(--orange);
        }
        
        .cont_form .input_form, .cont_form .input_select {
            background: #646464;
            color: #fff;
            border-color: #646464;
        }
        
        .cont_form .input_form::placeholder {
            color: #ddd;
        }
        
        .cont_form .input_select option {
            background-color: #464646;
            color: #fff;
        }

        .form{
            padding: 20px;
        }

        
    </style>

 <nav id="menu" class="menu">
     <h2 class="titulo">Gestion de Tickets</h2>
 </nav>

    <section class="container">
           
        <div class="section_tickets">
            
            

            <div class="container_tickets">
                
                <h2 class="section_subtitle">Selecciona los tickets para el sorteo {{$sorteo->sorteo_nombre}}</h2>
                <p>Tickets comprados: <span id="cantidad_comprada">{{ $pago->cantidad_de_tickets }}</span></p>
                <p>Tickets seleccionados: <span id="contador_seleccionados">0</span></p>
                <p id="mensaje_validacion" style="color: red;"></p>
                <form id="form_seleccionar_tickets" class="form_tickets">
                    <div class="numeros_tickets">
                        @csrf
                        @php
                            $numeros_disponibles = json_decode($sorteo->numeros_disponibles);
                        @endphp
                        @foreach ($numeros_disponibles as $numero )

                            <input type="checkbox" name="numeros[]" value="{{$numero}}" id="numero_{{$numero}}" class="input_checkbox checkbox_ticket" onchange="actualizarContador()">
                            <label for="numero_{{$numero}}" class="button lbl_check">{{$numero}}</label>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="cont_tickets_selector">

                <h2 class="section_subtile">GESTIONA TICKETS</h2>

                <div>
                <form action="{{route('ticket.store')}}" method="POST" class="form" id="form">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id_sorteo" value="{{$sorteo->id_sorteo}}">
                    <input type="hidden" name="cedula_cliente" value="{{$cliente->cedula}}">
                    <input type="hidden" name="nombre_cliente" value="{{$cliente->nombre_y_apellido}}">
                    <input type="hidden" name="telefono_cliente" value="{{$cliente->telefono}}">
                    <input type="hidden" name="correo_cliente" value="{{$cliente->correo}}">
                    <input type="hidden" name="id_pago" value="{{$pago->id_pago}}">
                    <input type="hidden" name="numeros_seleccionados" id="numeros_seleccionados">
                    <button type="button" onclick="enviarTickets()" id="boton_enviar_tickets" class="button">Generar Tickets Seleccionados</button>
                     
                </form>
            </div>

                <h3 class="sub_inp">Bloquear Numero</h3>
                <div class="cont_form">
                    <form action="{{route('ticket.bloquear',$sorteo)}}" class="content_form form" method="POST" enctype="multipart/form-data">
                        <h2>Bloquear Tickets</h2>
                        @csrf
                        @method('PUT')

                        <input type="number" name="numero_a_bloquear" id="numero_a_bloquear" placeholder="Bloquear Numero" class="input_form" min="0" max="9999">

                        <br>

                        <button type="submit" class="submit_btn">Bloquear Numero</button>
                    </form>
                </div>

                <br><br><br><br><br><br><br><br><br><br>

                <div class="cont_form">
                    <form action="{{route('ticket.desbloquear',$sorteo)}}" class="content_form form" method="post">
                        @csrf
                        @method('PUT')
                        <h3 class="sub_inp">Desbloquear tickets</h3>
                        <select name="numero_a_desbloquear" id="numero_a_desbloquear" class="input_select">
                            @php
                                $numeros_ganadores = json_decode($sorteo->numeros_ganadores);
                            @endphp
                            @if ($numeros_ganadores != null)
                                @foreach($numeros_ganadores as $numero)
                                    <option value="{{$numero}}" class="input_option">{{$numero}}</option>
                                @endforeach
                            @else
                                <option value="" class="input_option">No hay numeros ganadores</option>
                            @endif
                        </select>
                        <button type="submit" class="submit_btn">Desbloquear</button>
                    </form>
                </div>

                <br><br><br><br><br><br><br><br><br><br>


                <div class="cont_form">
                    <form action="{{route('ticket.store')}}" class="content_form form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_sorteo" value="{{$sorteo->id_sorteo}}">
                        <input type="hidden" name="cedula_cliente" value="{{$cliente->cedula}}">
                        <input type="hidden" name="nombre_cliente" value="{{$cliente->nombre_y_apellido}}">
                        <input type="hidden" name="telefono_cliente" value="{{$cliente->telefono}}">
                        <input type="hidden" name="correo_cliente" value="{{$cliente->correo}}">
                        <input type="hidden" name="id_pago" value="{{$pago->id_pago}}">
                        <input type="hidden" name="numeros_seleccionados" id="numeros_seleccionados" value="aleatorio">
                        <h3 class="sub_inp">Generar tickets aleatorios</h3>
                        <button class="submit_btn" >Generar</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        const cantidadComprada = parseInt(document.getElementById('cantidad_comprada').textContent);
        const contadorSeleccionadosElement = document.getElementById('contador_seleccionados');
        const mensajeValidacionElement = document.getElementById('mensaje_validacion');
        const botonEnviarTickets = document.getElementById('boton_enviar_tickets');
        const checkboxes = document.querySelectorAll('.checkbox_ticket');
        const numerosDisponiblesJSON = `{!! json_encode($sorteo->numeros_disponibles) !!}`;
        const numerosDisponibles = JSON.parse(numerosDisponiblesJSON);

        function actualizarContador() {
            const seleccionados = document.querySelectorAll('input[name="numeros[]"]:checked').length;
            contadorSeleccionadosElement.textContent = seleccionados;

            if (seleccionados > cantidadComprada) {
                mensajeValidacionElement.textContent = 'Has excedido la cantidad de tickets comprados.';
                botonEnviarTickets.disabled = true;
            } else if (seleccionados < cantidadComprada) {
                mensajeValidacionElement.textContent = 'Debes seleccionar la cantidad exacta de tickets comprados.';
                botonEnviarTickets.disabled = true;
            } else {
                mensajeValidacionElement.textContent = '';
                botonEnviarTickets.disabled = false;
            }
        }

        function enviarTickets() {
            const seleccionados = document.querySelectorAll('input[name="numeros[]"]:checked').length;
            if (seleccionados === cantidadComprada) {
                const checkboxes = document.querySelectorAll('input[name="numeros[]"]:checked');
                const numerosSeleccionados = Array.from(checkboxes).map(checkbox => checkbox.value);
                document.getElementById('numeros_seleccionados').value = JSON.stringify(numerosSeleccionados);
                document.getElementById('form').submit();
            } else {
                alert('Por favor, selecciona la cantidad exacta de tickets comprados.');
            }
        }

        function generarTicketAleatorio() {
           

            if (numerosDisponibles && numerosDisponibles.length > 0) {
                const ticketsAleatorios = [];
                                const indicesUsados = new Set(); // Para evitar duplicados

                                while (ticketsAleatorios.length < cantidadComprada && indicesUsados.size < numerosDisponibles.length) {
                                    const indiceAleatorio = Math.floor(Math.random() * numerosDisponibles.length);
                                    if (!indicesUsados.has(indiceAleatorio)) {
                                        ticketsAleatorios.push(numerosDisponibles[indiceAleatorio]);
                                        indicesUsados.add(indiceAleatorio);
                                        const checkbox = document.getElementById(`numero_${numerosDisponibles[indiceAleatorio]}`);
                                        if (checkbox) {
                                            checkbox.checked = true;
                                        }
                                    }
                                }
                                actualizarContador(); // Actualizar el contador después de marcar los checkboxes
                            } else {
                                alert('No hay números de tickets disponibles para generar.');            }

                        }

                        // Inicializar el contador al cargar la página
                        actualizarContador();
    </script>

</body>
</html>