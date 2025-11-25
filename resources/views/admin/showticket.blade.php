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
    <title>Panel de tickets</title>
</head>
<body>
    
        <br>
        <section id="compra" class="container container_compra">
        <div class="cont_form cont_form_compra">
            
            <form action="{{route('admin.ticket')}}" method="GET" class="form" >
                <div>
                    <label for="filtro">Buscar por:</label>
                    <select id="filtro" name="filtro" class="input_form"  required>
                        <option value="cedula">Cedula del cliente</option>
                        <option value="numero" class="input_option">Numero de Ticket</option>
                        <option value="cliente" class="input_option">Nombre del Cliente</option>
                    </select>
                </div>
            <label>
            Buscar 
            </label>
            <input type="text" class="input_form" id="contenido" name="contenido">
            <button class="button btn_modal" type="submit">Buscar</button>
            </form>
        </div>
        </section>
    

    <div id="section_ventas_admin" class="container">
        <h2 class="section_subtitle">TABLA DE TICKETS CREADOS</h2>
        <div class="container_table">
            <table id="table_gestion" class="table_gestion">
                <thead>
                    <tr>
                        <th>ID Ticket</th>
                        <th>Token de Ticket</th>
                        <th>Cedula del Cliente</th>
                        <th>Nombre del Cliente</th>
                        <th>Telefono Cliente</th>
                        <th>Numeros Comprados</th>
                        <th>Fecha y hora de creacion</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id_ticket}}</td>
                        <td>{{ $ticket->ticket_token}}</td>
                        <td>{{ $ticket->cedula_cliente}}</td>
                        <td>{{ $ticket->nombre_cliente}}</td>
                        <td>{{ $ticket->telefono_cliente}}</td>
                        <!-- @php
                            $numeros_comprados = json_decode($ticket->numeros_seleccionados, true);
                            $numeros_comprados = implode("-", $numeros_comprados);
                        @endphp -->
                        <td><div class="btn submit_btn">Mostrar</div></td>
                        <td>{{ $ticket->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="cont_modal">

        <div class="x_modal">
            <img src="{{asset('img/x.png')}}" alt="" >
        </div>
        <div class="ventana_tickets">
            <h2>Aqui puede ver sus tickets</h2>
            <div class="contenedor_tickets">
                <h2 class="data_tickets_modal"></h2>
                <p class="mostrar_data"></p>
            </div>
        </div>
    </div>

    <script>

        (function() {
            const ticketsData = @json($tickets);

            const modal = document.querySelector('.cont_modal');
            const closeButton = document.querySelector('.x_modal');
            const openButtons = document.querySelectorAll('.btn'); 
            
            const muestra = document.querySelector('.mostrar_data');
            const nombre = document.querySelector('.data_tickets_modal');

            function openModal(event) {
                if (event) {
                    event.preventDefault(); 
                }
                
                modal.style.display = 'block';
                setTimeout(() => {
                    modal.style.transform = 'translateX(0)';
                }, 10);
            }

            function closeModal() {
                modal.style.transform = 'translateX(110%)';
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 500);
            }

            openButtons.forEach(button => {
                button.addEventListener('click', (event) => {
                    const row = event.target.closest('tr');

                    const ticketId = row.querySelector('td:nth-child(1)').textContent;

                    const ticket = ticketsData.find(t => t.id_ticket == ticketId);

                    if (ticket) {
                        nombre.textContent = `Ticket de ${ticket.nombre_cliente}`;
                        const numeros = JSON.parse(ticket.numeros_seleccionados).join('-');
                        muestra.textContent = `${numeros}`;
                    }

                    openModal(event);
                });
            });

            closeButton.addEventListener('click', closeModal);

            window.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

    })();

    </script>


<br><br><br><br>
        
</body>
</html>