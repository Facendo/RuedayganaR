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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Cal+Sans&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&family=Rubik+Mono+One&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/favicon-32x32.png')}}">

    <title>Panel de Administrador</title>
    <style>
        body{
            background: rgb(35, 35, 35);
        }
    </style>
</head>
<body>
    
<div class="cont_modal" id="pago-modal">
    <div class="x_modal">
        <img src="{{asset('img/x.png')}}" alt="" >
    </div>
    <div class="container_reg">
        <div class="cont_form">
            <form action="{{route('pago.update')}}" method="POST" class="cont_form" enctype="multipart/form-data" id="edit-payment-form">
                @csrf
                @method('PUT') 
                <input type="hidden" name="id_pago" id="id_pago_edit">
                
                <h2 class="header">EDITAR PAGO</h2>
                <div class="cont_input">
                    <div class="content_form">
                        <label for="monto_edit">MONTO:</label>
                        <input type="number" placeholder="Edicion de monto" id="monto_edit" name="monto_edit" class="input_form" required>
                        <label for="cantidad_edit">CANTIDAD_COMPRA:</label>
                        <input type="number" placeholder="Cantidad comprada" id="cantidad_edit" name="cantidad_edit" class="input_form" required>
                        <button type="submit" class="button submit_btn">Editar Pago</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="cont_modal" id="sorteo-modal">
    <div class="x_modal">
        <img src="{{asset('img/x.png')}}" alt="" >
    </div>
    <div class="container_reg">
        <div class="cont_form">
            <form action="{{route('sorteo.actualizar')}}" method="POST" class="cont_form" id="edit-sorteo-form">
                @csrf
                @method('PUT') 
                <h2 class="header">EDITAR SORTEO</h2>
                <input type="hidden" name="id_sorteo" id="id_sorteo_edit">
                <div class="cont_input">
                    <div class="content_form">
                        <label for="sorteo_nombre_edit">Nombre:</label>
                        <input type="text" name="sorteo_nombre" id="sorteo_nombre_edit" placeholder="Nombre del sorteo" class="input_form" required>
                        <label for="sorteo_descripcion_edit">Descripción:</label>
                        <input type="text" name="sorteo_descripcion" id="sorteo_descripcion_edit" placeholder="Descripcion del sorteo" class="input_form" required>
                        <label for="precio_boleto_bs_edit">Precio boleto (bs):</label>
                        <input type="text" name="precio_boleto_bs" id="precio_boleto_bs_edit" placeholder="Precio del boleto en bolivares" class="input_form" required>
                        <label for="precio_boleto_dolar_edit">Precio boleto (dolar):</label>
                        <input type="text" name="precio_boleto_dolar" id="precio_boleto_dolar_edit" placeholder="Precio del boleto en dolares" class="input_form" required>
                        <button type="submit" class="button submit_btn">Actualizar Sorteo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>





<nav id="menu" class="menu">
    <h2 class="titulo">Panel administrador</h2>
</nav>


<div class="contenedor_desp">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class='button'>Cerrar Sesión</button>
        </form>
        <br>
        <a href="{{route('admin.showticket')}}" class="button">Tickets</a>
    </div>


<div id="section_ventas_admin" class="container section_ventas">
    <h2 class="section_subtitle">Tabla de pagos de boletos</h2>
    <div class="container_table">
        <table id="table_gestion" class="table_gestion">
            <thead>
                <tr>
                    <th>Cedula</th>
                    <th>Telefono</th>
                    <th>Referencia</th>
                    <th>Comprobante</th>
                    <th>Monto</th>
                    <th>Cantidad de Tickets</th>
                    <th>Fecha pago</th>
                    <th>Metodo de pago</th>
                    <th>Estado de pago</th>
                    <th>Acciones</th>
                    <th>Tickets</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagos as $pago)
                <tr>
                    <td>{{ $pago->cedula_cliente }}</td>
                    <td>{{ $pago->nro_telefono }}</td>
                    <td>{{ $pago->referencia }} </td>
                    <td>
                        <form action="{{route('admin.showcomprobante')}}" method="POST" enctype="multipart/form-data">
                            @csrf 
                            <input type="hidden" name="id_pago" value="{{$pago->id_pago}}">
                            <button class="button" type="submit">Referencia</button>
                        </form>
                    </td>
                    <td>{{ $pago->monto }}</td>
                    <td>{{ $pago->cantidad_de_tickets }}</td>
                    <td>{{ $pago->fecha_pago }}</td>
                    <td>{{ $pago->metodo_de_pago}}</td>
                    <td>{{ $pago->estado_pago }}</td>
                    <td>
                        <div class="button btn_modal"
                             data-modal="pago"
                             data-id="{{$pago->id_pago}}"
                             data-monto="{{$pago->monto}}"
                             data-cantidad="{{$pago->cantidad_de_tickets}}">
                             Editar
                        </div>
                        <div>
                            <form action="{{route('pago.destroy', $pago->id_pago)}}" method="POST" class="form-eliminar">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id_pago" value="{{$pago->id_pago}}">
                                <button class="button" type="submit">Eliminar</button>
                            </form>
                        </div>
                    </td>
                    <td>
                        @if ($pago->estado_pago == 'Confirmado')
                            <label class="button" disabled>Asignado</label>
                        @else
                            <a href="{{route('ticket.index',['id_pago'=>$pago->id_pago])}}" class="button">Asignar</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="pagination_container">
    {{ $pagos->links() }}
</div>
<br><br><br><br><br><br><br><br><br><br>

<h2 class="section_subtitle">REGISTRAR SORTEO</h2>
<div class="container_reg">
    <div class="cont_form">
        <form action="{{route('sorteo.store')}}" class="form_reg_sorteo form content_form" method="POST" enctype="multipart/form-data">
            <div class="header">
                <h1>Registra sorteo</h1>
            </div>
            @csrf
            <label for="sorteo_nombre">Nombre:</label>
            <input type="text" name="sorteo_nombre" id="sorteo_nombre" placeholder="Nombre del sorteo" class="input_form" required>
            <label for="sorteo_fecha_inicio">Fecha de inicio:</label>
            <input type="date" name="sorteo_fecha_inicio" id="sorteo_fecha_inicio" placeholder="Fecha de inicio del sorteo" class="input_form" required>
            <label for="sorteo_fecha_fin">Fecha de fin:</label>
            <input type="date" name="sorteo_fecha_fin" id="sorteo_fecha_fin" placeholder="Fecha de fin del sorteo" class="input_form" required>
            <label for="sorteo_descripcion">Descripcion:</label>
            <input type="text" name="sorteo_descripcion" id="sorteo_descripcion" placeholder="Descripcion del sorteo" class="input_form" required>
            <label for="precio_boleto_bs">Precio boleto (bs):</label>
            <input type="text" name="precio_boleto_bs" id="precio_boleto_bs" placeholder="Precio del boleto en bolivares" class="input_form" required>
            <label for="precio_boleto_dolar">Precio boleto (dolar):</label>
            <input type="text" name="precio_boleto_dolar" id="precio_boleto_dolar" placeholder="Precio del boleto en dolares" class="input_form" required>
            <label for="sorteo_imagen" class="file">Imagen:</label>
            <input type="file" name="sorteo_imagen" id="sorteo_imagen" placeholder="Imagen del sorteo" class="input_file" accept="image/*" required>
            <br>
            <button type="submit" class="btn_reg_sorteo button submit_btn">Registrar sorteo</button>
        </form>
    </div>
</div>

<div id="section_ventas_admin" class="container">
    <h2 class="section_subtitle">TABLA DE SORTEOS</h2>
    <div class="container_table">
        <table id="table_gestion" class="table_gestion">
            <thead>
                <tr>
                    <th>ID sorteo</th>
                    <th>Nombre del sorteo</th>
                    <th>Descripcion</th>
                    <th>Precio Boleto</th>
                    <th>Fecha de inicio</th>
                    <th>Fecha de fin</th>
                    <th>Tickets Disponibles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sorteos as $sorteo)
                <tr>
                    <td>{{ $sorteo->id_sorteo }}</td>
                    <td>{{ $sorteo->sorteo_nombre }}</td>
                    <td>{{ $sorteo->sorteo_descripcion }}</td>
                    <td>{{ $sorteo->precio_boleto_bs }} bs - {{ $sorteo->precio_boleto_dolar }} $</td>
                    <td>{{ $sorteo->sorteo_fecha_inicio }}</td>
                    <td>{{ $sorteo->sorteo_fecha_fin }}</td>
                    @php
                       $numeros_disponibles = json_decode($sorteo->numeros_disponibles, true);
                       $cantidad_disponible = count($numeros_disponibles);
                    @endphp
                    <td>{{$cantidad_disponible}}</td>
                    <td>
                        <form action={{route('sorteo.cambio_estado',$sorteo->id_sorteo)}} method="POST">
                            @csrf
                            @method('PUT')
                            @if ($sorteo->sorteo_activo == 1)
                                <button type="submit" class="button">Desactivar</button>
                            @else
                                <button type="submit" class="button">Activar</button>
                            @endif
                        </form>

                            <div class="button btn_modal">
                                <a href="{{route('ruletas.creacion',$sorteo->id_sorteo)}}">Agregar Ruleta</a>
                            </div>
                        
                        <div class="button btn_modal"
                             data-modal="sorteo"
                             data-id="{{ $sorteo->id_sorteo }}"
                             data-nombre="{{ $sorteo->sorteo_nombre }}"
                             data-descripcion="{{ $sorteo->sorteo_descripcion }}"
                             data-precio-bs="{{ $sorteo->precio_boleto_bs }}"
                             data-precio-dolar="{{ $sorteo->precio_boleto_dolar }}">
                             Editar
                        </div>
                        <div>
                            <form action="{{route('sorteo.destroy', $sorteo->id_sorteo)}}" method="POST" class="form-eliminar">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id_sorteo" value="{{$sorteo->id_sorteo}}">
                                <button class="button" type="submit">Eliminar</button>
                            </form>
                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<div id="section_ventas_admin" class="container">
    <h2 class="section_subtitle">TABLA DE RULETAS</h2>
    <div class="container_table">
        <table id="table_gestion" class="table_gestion">
            <thead>
                <tr>
                    <th>Nombre Ruleta</th>
                    <th>Cantidad de Oportunidades</th>
                    <th>Número de Ranuras</th>
                    <th>Condicional de Oportunidades</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($Ruletas as $ruleta)
                <tr>
                    <td>{{ $ruleta->nombre }}</td>
                    <td>{{ $ruleta->cantidad_de_opotunidades_por_dar }}</td>
                    <td>{{ $ruleta->nro_ranuras}}</td>
                    <td>{{ $ruleta->condicional_oportunidades}}</td>
                    <td>
                        <div class="button btn_modal">
                            <a href="{{route('ruletas.editar', $ruleta->id_ruleta)}}">Editar Ruleta</a>
                        </div>
                        <div class="button btn_modal">
                        <a href="{{route('ranuras.creacion',$ruleta->id_ruleta)}}">Gestion de Ranuras</a>
                        </div>
                        <form action={{route('ruleta.cambio_estado',$ruleta->id_ruleta)}} method="POST">
                            @csrf
                            @method('PUT')
                            @if ($ruleta->activo == 1)
                                <button type="submit" class="button">Desactivar</button>
                            @else
                                <button type="submit" class="button">Activar</button>
                            @endif
                        </form>
                        <form action={{route('ruleta.destroy')}} method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" value="{{$ruleta->id_ruleta}}" name="id_ruleta">
                            <button type="submit" class="button">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="section_ventas_admin" class="container">
    <h2 class="section_subtitle">HISTORICOS DE LAS RULETAS</h2>
    <div class="container_table">
        <table id="table_gestion" class="table_gestion">
            <thead>
                <tr>
                    <th>Nombre Ruleta</th>
                    <th>Cedula del Jugador</th>
                    <th>Nombre del Jugador</th>
                    <th>Nro de Contacto</th>
                    <th>Descripcion</th>
                    <th>Fecha y Hora</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historicos as $historico)
                <tr>
                    <td>{{ $historico->nombre_ruleta }}</td>
                    <td>{{ $historico->cedula_jugador }}</td>
                    <td>{{ $historico->nombre_jugador }}</td>
                    <td>{{ $historico->telefono }}</td>
                    <td>{{ $historico->descripcion }}</td>
                    <td>{{ $historico->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
                <div class="pagination_container">
            {{ $historicos->links() }}
        </div>
            </tbody>
            
        </table>
        
    </div>
</div>
        



<h2 class="section_subtitle">Asignar Premios</h2>
<div class="container_reg">
    <div class="cont_form">
        <form action="{{route('premio.store')}}" class="form_reg_sorteo form content_form" method="POST" enctype="multipart/form-data">
            <div class="header">
                <h1>Asigna premios</h1>
            </div>
            @csrf
            <div>
                <label for="opcion">Sorteos</label>
                <select id="Sorteo" name="id_sorteo" class="input_select">
                    @foreach ($sorteos as $sorteo)
                    <option value="{{$sorteo->id_sorteo}}" class="input_option">{{$sorteo->sorteo_nombre}}</option>
                    @endforeach
                </select>
            </div>
            <label for="premio_nombre">Nombre del premio:</label>
            <input type="text" name="premio_nombre" id="premio_nombre" placeholder="Nombre premio" class="input_form">
            <label for="premio_descripcion">Descripcion del premio:</label>
            <input type="text" name="premio_descripcion" id="premio_descripcion" placeholder="Descripcion premio" class="input_form">
            <label for="premio_imagen" class="file">Imagen del premio:</label>
            <input type="file" name="premio_imagen" id="premio_imagen" placeholder="Imagen de premio" class="input_file">
            <br>
            <button type="submit" class="btn_reg_sorteo button submit_btn">Registrar Premio</button>
        </form>
    </div>
</div>
<br><br><br><br>

<script>
    // Seleccionamos los elementos del modal de pagos
    const pagoModal = document.getElementById('pago-modal');
    const pagoIdInput = document.getElementById('id_pago_edit');
    const montoInput = document.getElementById('monto_edit');
    const cantidadInput = document.getElementById('cantidad_edit');

    // Seleccionamos los elementos del modal de sorteos
    const sorteoModal = document.getElementById('sorteo-modal');
    const sorteoIdInput = document.getElementById('id_sorteo_edit');
    const sorteoNombreInput = document.getElementById('sorteo_nombre_edit');
    const sorteoDescripcionInput = document.getElementById('sorteo_descripcion_edit');
    const precioBsInput = document.getElementById('precio_boleto_bs_edit');
    const precioDolarInput = document.getElementById('precio_boleto_dolar_edit');
    const sorteoForm = document.getElementById('edit-sorteo-form');

    // Seleccionamos todos los botones que abren modales
    const botones = document.querySelectorAll('.btn_modal');

    // Función genérica para abrir un modal
    function openModal(modalElement, event) {
        if (event) {
            event.preventDefault();
        }
        modalElement.style.display = 'block';
        setTimeout(() => {
            modalElement.style.transform = 'translateX(0)';
        }, 10);
    }

    // Función genérica para cerrar cualquier modal
    function closeModal(modalElement) {
        modalElement.style.transform = 'translateX(110%)';
        setTimeout(() => {
            modalElement.style.display = 'none';
        }, 500);
    }

    // Manejador de eventos para todos los botones de modal
    botones.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const modalType = btn.dataset.modal;

            if (modalType === 'pago') {
                const pagoId = btn.dataset.id;
                const monto = btn.dataset.monto;
                const cantidad = btn.dataset.cantidad;

                pagoIdInput.value = pagoId;
                montoInput.value = monto;
                cantidadInput.value = cantidad;

                openModal(pagoModal, e);
            } else if (modalType === 'sorteo') {
                const sorteoId = btn.dataset.id;
                const nombre = btn.dataset.nombre;
                const descripcion = btn.dataset.descripcion;
                const precioBs = btn.dataset.precioBs;
                const precioDolar = btn.dataset.precioDolar;

                sorteoIdInput.value = sorteoId;
                sorteoNombreInput.value = nombre;
                sorteoDescripcionInput.value = descripcion;
                precioBsInput.value = precioBs;
                precioDolarInput.value = precioDolar;
                
                // Actualiza la acción del formulario con el ID del sorteo
                

                openModal(sorteoModal, e);
            }
        });
    });

    // Manejadores para cerrar los modales
    document.querySelectorAll('.x_modal').forEach(closeBtn => {
        closeBtn.addEventListener('click', () => {
            // Encuentra el modal padre y lo cierra
            const modalToClose = closeBtn.closest('.cont_modal');
            closeModal(modalToClose);
        });
    });

    window.addEventListener('click', (event) => {
        if (event.target === pagoModal) {
            closeModal(pagoModal);
        } else if (event.target === sorteoModal) {
            closeModal(sorteoModal);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {

    // ... (Tu código de modales y otros scripts) ...
    
    // Selecciona todos los formularios con la clase 'form-eliminar'
    const eliminarForms = document.querySelectorAll('.form-eliminar');

    eliminarForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            // Muestra un cuadro de diálogo de confirmación
            const confirmacion = confirm('¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.');

            // Si el usuario presiona 'Cancelar', previene el envío del formulario
            if (!confirmacion) {
                e.preventDefault();
            }
        });
    });
});
</script>
</body>
</html>