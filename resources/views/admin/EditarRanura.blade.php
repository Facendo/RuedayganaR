<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Dinámica de Ranuras (Slots)</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        :root {
            --primary: #10b981; /* Verde para esta gestión */
            --secondary: #3b82f6;
            --bg-light: #f9fafb;
            --bg-dark: #ffffff;
            --border: #e5e7eb;
            --shadow: rgba(0, 0, 0, 0.05);
            --danger: #ef4444;
            --success: #10b981;
            --info: #fcd34d; /* Amarillo para carga/edición */
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
            max-width: 900px;
            background-color: var(--bg-dark);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px var(--shadow);
            border: 1px solid var(--border);
        }
        h1, h2 {
            color: #1f2937;
            border-bottom: 2px solid var(--border);
            padding-bottom: 10px;
            margin-top: 0;
        }
        h1 {
            color: var(--primary);
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
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
            outline: none;
        }
        .slot-info {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #ecfdf5;
            border-radius: 10px;
            border: 1px solid var(--primary);
        }
        .slot-container {
            border: 2px dashed var(--border);
            padding: 20px 15px;
            border-radius: 10px;
        }
        .slot-item {
            background-color: var(--bg-light);
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 5px solid var(--secondary);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            position: relative;
        }
        .slot-item.editing {
             border-left: 5px solid var(--info); /* Borde amarillo para ranuras cargadas */
        }
        .slot-header {
            grid-column: 1 / -1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 5px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 10px;
        }
        .slot-header h4 {
            margin: 0;
            color: var(--secondary);
            font-size: 1.1em;
            display: flex;
            align-items: center;
        }
        .slot-id-tag {
            background-color: var(--info);
            color: #3b82f6;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            margin-left: 10px;
            font-size: 0.8em;
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
            background-color: var(--secondary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        .btn-secondary {
            background-color: var(--primary);
            color: white;
        }
        .btn-secondary:hover {
            background-color: #059669;
        }
        .btn-info {
            background-color: var(--info);
            color: #1f2937;
            margin-left: 10px;
        }
        .btn-info:hover {
            background-color: #f59e0b;
        }
        .btn-danger {
            background-color: #ef4444;
            color: white;
            padding: 5px 10px;
        }
        .btn-danger:hover {
            background-color: #dc2626;
        }
        .footer-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            align-items: center;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            grid-column: span 2;
        }
        .checkbox-group label {
            margin-left: 10px;
            margin-bottom: 0;
        }
        input[type="color"] {
            height: 38px;
            padding: 4px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }
            .slot-item {
                grid-template-columns: 1fr;
            }
            .checkbox-group {
                grid-column: span 1; 
            }
            .footer-buttons {
                flex-direction: column-reverse;
                gap: 15px;
                align-items: stretch;
            }
            .action-buttons {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }
            .btn-secondary, .btn-primary, .btn-info {
                width: 100%;
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    
    <h1>Gestión de Ranuras (Slots)</h1>

    <div class="slot-info">
        <p>Utiliza este formulario para configurar (agregar o editar) las ranuras de una ruleta existente. **Asegúrate de ingresar el ID de la Ruleta**.</p>
        <div class="form-group">
            <label for="id_ruleta_global">ID de la Ruleta a Configurar</label>
            <div style="display: flex; gap: 10px;">
                <input type="number" id="id_ruleta_global" placeholder="Ingresa el ID de la ruleta (Ej: 42)" required>
                <button type="button" class="btn btn-info" id="loadSlotsBtn" title="Simula cargar datos desde el servidor">
                    Cargar Ranuras
                </button>
            </div>
        </div>
    </div>
    
    <!-- El formulario usa POST y multipart/form-data para enviar el array de ranuras y archivos -->
    <!-- RECUERDA: Debes reemplazar "/tu-ruta-de-guardado-ranuras" con la URL real de tu controlador. -->
    <form id="slotForm" method="POST" action="/tu-ruta-de-guardado-ranuras" enctype="multipart/form-data">
        
        <!-- IMPORTANTE PARA LARAVEL: Incluir el token CSRF. Si usas Blade, sería @csrf -->
        <input type="hidden" name="_token" value="REEMPLAZAR_CON_CSRF_TOKEN">

        <!-- Campo Oculto para enviar el ID de la Ruleta con cada envío -->
        <input type="hidden" id="hidden_id_ruleta" name="id_ruleta" value=""> 

        <h2 id="ranuras-title">Ranuras Actuales: 0 Ranuras Agregadas</h2>
        
        <div id="slotContainer" class="slot-container">
            <!-- Las ranuras dinámicas se agregarán aquí -->
            <p style="text-align: center; color: #6b7280;">Presiona "Agregar Ranura" o "Cargar Ranuras" (si existen) para empezar.</p>
        </div>
        
        <div class="footer-buttons">
            <div class="action-buttons">
                <button type="button" class="btn btn-secondary" id="addSlotBtn">
                    + Agregar Nueva Ranura
                </button>
            </div>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                Guardar / Actualizar Todas las Ranuras
            </button>
        </div>
    </form>
</div>

<script>
    const slotContainer = document.getElementById('slotContainer');
    const addSlotBtn = document.getElementById('addSlotBtn');
    const loadSlotsBtn = document.getElementById('loadSlotsBtn');
    const slotForm = document.getElementById('slotForm');
    const ranurasTitle = document.getElementById('ranuras-title');
    const ruletaIdInput = document.getElementById('id_ruleta_global');
    const hiddenRuletaId = document.getElementById('hidden_id_ruleta');

    // Sincroniza el ID visible con el campo oculto antes de enviar
    ruletaIdInput.addEventListener('input', (e) => {
        hiddenRuletaId.value = e.target.value;
    });

    /**
     * Valida que exista el ID de la ruleta y al menos una ranura antes de enviar el formulario.
     */
    slotForm.addEventListener('submit', function(event) {
        const slotCount = slotContainer.querySelectorAll('.slot-item').length;
        const ruletaId = hiddenRuletaId.value;

        if (!ruletaId) {
            event.preventDefault(); 
            showErrorFeedback('❌ Ingresa el ID de la Ruleta primero!');
            console.error('ERROR: Debes ingresar el ID de la ruleta a configurar.');
            return;
        }

        if (slotCount === 0) {
            event.preventDefault(); 
            showErrorFeedback('❌ ¡Agrega al menos una Ranura!');
            console.error('ERROR: Debes agregar al menos una ranura (slot) antes de guardar.');
        }
    });
    
    /**
     * Muestra retroalimentación visual de error en el botón de submit.
     * @param {string} message - El mensaje de error.
     */
    function showErrorFeedback(message) {
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.textContent;
        const originalBgColor = submitBtn.style.backgroundColor;
        
        submitBtn.textContent = message;
        submitBtn.style.backgroundColor = 'var(--danger)'; 
        
        setTimeout(() => {
            submitBtn.textContent = originalText;
            // Si el color original no estaba seteado, usa el color primario por defecto
            submitBtn.style.backgroundColor = originalBgColor || 'var(--secondary)'; 
        }, 2000);
    }

    /**
     * Limpia y remueve todos los slots del contenedor.
     */
    function clearSlotContainer() {
        slotContainer.innerHTML = ''; // Limpia todo el contenido dinámico
        updateSlotTitle(); // Asegura que el contador se resetee y el placeholder se muestre
    }


    /**
     * Actualiza el contador en el título y re-enumera las ranuras visibles.
     */
    function updateSlotTitle() {
        // Re-enumerar las ranuras visibles después de agregar o eliminar
        const items = slotContainer.querySelectorAll('.slot-item');
        let visibleCount = 0;
        items.forEach((item, index) => {
            visibleCount++;
            const header = item.querySelector('.slot-header h4');
            const idElement = item.querySelector('.slot-id-tag');
            
            if (header) {
                // Actualiza el número visible y mantiene el ID si existe
                header.innerHTML = `Ranura #${index + 1}${idElement ? idElement.outerHTML : ''}`;
            }
        });

        ranurasTitle.textContent = `Ranuras Actuales: ${visibleCount} Ranuras Agregadas`;
        
        // Muestra u oculta el placeholder
        if (visibleCount === 0) {
            const newPlaceholder = document.createElement('p');
            newPlaceholder.style.textAlign = 'center';
            newPlaceholder.style.color = '#6b7280';
            newPlaceholder.textContent = 'Presiona "Agregar Ranura" o "Cargar Ranuras" (si existen) para empezar.';
            slotContainer.appendChild(newPlaceholder);
        } else {
            const placeholder = slotContainer.querySelector('p');
            if (placeholder) {
                placeholder.remove();
            }
        }
    }

    /**
     * Crea un nuevo bloque de formulario para una ranura (slot).
     * @param {number} index - El índice único (timestamp o id) para la clave del array en PHP.
     * @param {Object} [data={}] - Datos opcionales de una ranura existente para precargar.
     */
    function createSlotElement(index, data = {}) {
        const slotDiv = document.createElement('div');
        
        // Si hay data.id, es una edición, añadimos la clase 'editing'
        const isEditing = !!data.id;
        slotDiv.classList.add('slot-item');
        if (isEditing) {
            slotDiv.classList.add('editing');
        }

        slotDiv.dataset.index = index;
        
        // Asignar valores por defecto o los valores cargados
        const id = data.id || '';
        const color = data.color || '#2e86de';
        const type = data.type || '';
        const texto = data.texto || '';
        const rate = data.Rate || 10;
        const blocked = data.Blocked || 0;
        
        // Elemento visual para el ID
        const idTag = id ? `<span class="slot-id-tag">ID: ${id}</span>` : '';

        // La clave 'ranuras[${index}][campo]' es lo que hace que Laravel reciba un array de objetos.
        slotDiv.innerHTML = `
            <div class="slot-header">
                <h4>Ranura #Nuevo ${idTag}</h4>
                <button type="button" class="btn btn-danger" onclick="removeSlot(this)">X</button>
            </div>
            
            <!-- Campo oculto ID: Si existe, es edición. Si está vacío, es creación. -->
            <input type="hidden" id="ranura_${index}_id" name="ranuras[${index}][id]" value="${id}"> 

            <div class="form-group">
                <label for="ranura_${index}_color">Color</label>
                <input type="color" id="ranura_${index}_color" name="ranuras[${index}][color]" value="${color}" required>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_type">Tipo (Type)</label>
                <input type="text" id="ranura_${index}_type" name="ranuras[${index}][type]" value="${type}" placeholder="Ej: Premio, Descuento, Vacío" required>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_texto">Texto</label>
                <input type="text" id="ranura_${index}_texto" name="ranuras[${index}][texto]" value="${texto}" placeholder="Texto en la ruleta" required>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_rate">Tasa (Rate) / Probabilidad</label>
                <input type="number" id="ranura_${index}_rate" name="ranuras[${index}][Rate]" value="${rate}" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_dir_imagen">Imagen de Ranura (Opcional) ${isEditing ? '(Mantener para no cambiar)' : ''}</label>
                <!-- Archivo de ranura. Nombre: ranuras[INDEX][dir_imagen] -->
                <input type="file" id="ranura_${index}_dir_imagen" name="ranuras[${index}][dir_imagen]" accept="image/*">
            </div>

            <div class="form-group checkbox-group">
                <!-- Si se marca, se envía '1'. -->
                <input type="checkbox" id="ranura_${index}_blocked" name="ranuras[${index}][Blocked]" value="1" ${blocked == 1 ? 'checked' : ''}>
                <label for="ranura_${index}_blocked">Bloqueada (Blocked)</label>
            </div>
        `;
        
        slotContainer.appendChild(slotDiv);
        updateSlotTitle();
    }

    // Función global para remover una ranura
    window.removeSlot = function(buttonElement) {
        const slotItem = buttonElement.closest('.slot-item');
        if (slotItem) {
            // Nota: En un entorno real, si la ranura tiene ID, deberías preguntar
            // al usuario si desea ELIMINAR el registro de la base de datos
            // o simplemente quitarlo de la lista para no guardarlo.
            slotItem.remove();
            updateSlotTitle(); 
        }
    };
    
    // --- LÓGICA DE CARGA DE DATOS (MOCK) ---
    
    // Datos de ejemplo simulando una respuesta de Laravel para una ruleta con ID 101
    const mockExistingSlots = [
        { id: 1, color: '#ff6961', type: 'Premio Mayor', texto: '¡Gana un Coche!', Rate: 5, Blocked: 0, dir_imagen: null },
        { id: 2, color: '#f8f38d', type: 'Descuento', texto: '20% Off', Rate: 20, Blocked: 0, dir_imagen: 'ruta/desc.png' },
        { id: 3, color: '#a79dff', type: 'Vacío', texto: 'Intenta de Nuevo', Rate: 75, Blocked: 0, dir_imagen: null },
    ];
    
    /**
     * Simula la carga de datos existentes desde el backend.
     */
    loadSlotsBtn.addEventListener('click', () => {
        const ruletaId = ruletaIdInput.value;
        if (!ruletaId) {
            showErrorFeedback('⚠️ Ingresa el ID de la Ruleta para cargar.');
            return;
        }

        // Aquí iría tu llamada fetch/axios a la ruta:
        // /api/ruletas/${ruletaId}/slots
        
        // Simulamos la respuesta
        clearSlotContainer();
        mockExistingSlots.forEach(slot => {
            // Usamos el ID real de la base de datos como índice temporal, 
            // ya que son únicos y persistentes.
            createSlotElement(slot.id, slot); 
        });
        
        // Feedback visual
        ruletaIdInput.style.borderColor = 'var(--info)';
        ruletaIdInput.style.boxShadow = '0 0 0 3px rgba(252, 211, 77, 0.4)';
        setTimeout(() => {
            ruletaIdInput.style.borderColor = 'var(--border)';
            ruletaIdInput.style.boxShadow = 'none';
        }, 1500);

        console.log(`Simulación: Ranuras cargadas para la Ruleta ID: ${ruletaId}`);
    });
    
    // --- LÓGICA DE AGREGAR NUEVO ---

    // Listener para el botón de agregar ranura
    addSlotBtn.addEventListener('click', () => {
        // Usar la marca de tiempo para asegurar un índice único temporal para nuevos slots
        const uniqueIndex = Date.now(); 
        createSlotElement(uniqueIndex);
    });
    
    // Al cargar, aseguramos que el título esté correcto
    window.onload = updateSlotTitle;
</script>
</body>
</html>
