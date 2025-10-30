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
            }
            .btn-secondary, .btn-primary {
                width: 100%;
                margin-top: 0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    
    <h1>Gestión de Ranuras (Slots)</h1>

    <div class="slot-info">
        <p>Estás configurando las ranuras para la Ruleta con el ID: 
           <strong id="display_ruleta_id">{{ $id_ruleta ?? 'N/A (Crea una nueva)' }}</strong>.</p>
        <p>Modifica, elimina o agrega nuevas ranuras a continuación.</p>
    </div>
    
    <form id="slotForm" method="POST" action="{{route('ranuras.store')}}" enctype="multipart/form-data">
        @csrf
        
        <input type="hidden" id="hidden_id_ruleta" name="id_ruleta" value="{{ $id_ruleta ?? '' }}"> 

        <h2 id="ranuras-title">Ranuras Actuales: 0 Ranuras Agregadas</h2>
        
        <div id="slotContainer" class="slot-container">
            <p style="text-align: center; color: #6b7280;">Presiona "Agregar Ranura" para empezar a configurar las opciones de la ruleta.</p>
        </div>
        
        <div class="footer-buttons">
            <button type="button" class="btn btn-secondary" id="addSlotBtn">
                + Agregar Ranura
            </button>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                Guardar Todas las Ranuras
            </button>
        </div>
    </form>
</div>

<script>
    // =================================================================================
    // 💡 INYECCIÓN DE DATOS DESDE BLADE (PHP)
    // ---------------------------------------------------------------------------------
    const initialRanuras = @json($ranuras ?? []); 
    const initialRuletaId = '{{ $id_ruleta ?? '' }}'; 
    // =================================================================================

    const slotContainer = document.getElementById('slotContainer');
    const addSlotBtn = document.getElementById('addSlotBtn');
    const slotForm = document.getElementById('slotForm');
    const ranurasTitle = document.getElementById('ranuras-title');
    const hiddenRuletaId = document.getElementById('hidden_id_ruleta');
    const submitBtn = document.getElementById('submitBtn');
    
    let deletedSlotIds = []; 
    // 🌟 CORRECCIÓN CLAVE 1: Contador para nuevas ranuras. Usamos un prefijo 'new_' y un número.
    let newSlotCounter = 0; 

    /**
     * Valida que exista el ID de la ruleta (ya cargado) y al menos una acción (ranura o eliminación) antes de enviar.
     */
    slotForm.addEventListener('submit', function(event) {
        const slotCount = slotContainer.querySelectorAll('.slot-item').length;
        const ruletaId = hiddenRuletaId.value; 

        if (!ruletaId) {
            event.preventDefault(); 
            showErrorFeedback('❌ Error: El ID de la Ruleta no está definido. (Intenta recargar).');
            return;
        }

        if (slotCount === 0 && deletedSlotIds.length === 0) {
            event.preventDefault(); 
            showErrorFeedback('❌ Agrega al menos una Ranura o ingresa una Ruleta válida!');
            return;
        }
        
        // Añadir el campo oculto con los IDs a eliminar (CRUCIAL para el controlador)
        if (deletedSlotIds.length > 0) {
            const hiddenDeletedInput = document.createElement('input');
            hiddenDeletedInput.type = 'hidden';
            hiddenDeletedInput.name = 'deleted_ids';
            hiddenDeletedInput.value = deletedSlotIds.join(',');
            slotForm.appendChild(hiddenDeletedInput);
        }
        
        submitBtn.textContent = 'Guardando...';
        submitBtn.disabled = true;
    });
    
    /**
     * Muestra retroalimentación visual de error en el botón de submit.
     */
    function showErrorFeedback(message) {
        // ... (código de showErrorFeedback sin cambios)
        const originalText = 'Guardar Todas las Ranuras'; 
        const originalColor = submitBtn.style.backgroundColor;
        
        submitBtn.textContent = message;
        submitBtn.style.backgroundColor = 'var(--danger)'; 
        
        setTimeout(() => {
            submitBtn.textContent = originalText;
            submitBtn.style.backgroundColor = originalColor || 'var(--primary)';
            submitBtn.disabled = false;
        }, 3000);
    }

    /**
     * Actualiza el contador en el título y re-enumera las ranuras visibles.
     */
    function updateSlotTitle() {
        // ... (código de updateSlotTitle sin cambios, excepto que usa .slot-item)
        const items = slotContainer.querySelectorAll('.slot-item');
        let visibleCount = 0;
        
        items.forEach((item, index) => {
            visibleCount++;
            const header = item.querySelector('.slot-header h4');
            if (header) {
                // Re-numeración visible
                header.textContent = `Ranura #${index + 1}`;
            }
        });

        ranurasTitle.textContent = `Ranuras Actuales: ${visibleCount} Ranuras Agregadas`;
        
        const placeholder = slotContainer.querySelector('p');
        if (visibleCount === 0) {
            if (!placeholder) {
                const newPlaceholder = document.createElement('p');
                newPlaceholder.style.cssText = 'text-align: center; color: #6b7280;';
                newPlaceholder.textContent = 'Presiona "Agregar Ranura" para empezar a configurar las opciones de la ruleta.';
                slotContainer.appendChild(newPlaceholder);
            }
        } else if (placeholder) {
            placeholder.remove();
        }
    }

    /**
     * Crea un nuevo bloque de formulario para una ranura (slot) con o sin datos iniciales.
     * @param {string|number} uniqueKey - La clave única (ID de DB o 'new_X') para la clave del array en PHP.
     * @param {object} [slotData={}] - Datos opcionales para precargar (si es edición).
     */
    function createSlotElement(uniqueKey, slotData = {}) {
        const isExisting = !!slotData.id_ranura;
        
        // 🌟 CORRECCIÓN CLAVE 2: Usamos el uniqueKey proporcionado para la nomenclatura
        const index = uniqueKey; 
        
        const slotDiv = document.createElement('div');
        slotDiv.classList.add('slot-item');
        slotDiv.dataset.index = index;
        slotDiv.dataset.id = slotData.id_ranura || 'new'; 

        // Valores por defecto
        const defaults = {
            id_ranura: isExisting ? slotData.id_ranura : '', 
            color: slotData.color || '#2e86de',
            type: slotData.type || '',
            texto: slotData.texto || '',
            rate: slotData.rate || 10,
            // Aseguramos que 'blocked' sea un valor numérico para la comparación en JS
            blocked: slotData.blocked || 0, // Usamos 'Blocked' del backend
            dir_imagen: slotData.dir_imagen || '' 
        };
        
        const imageHtml = defaults.dir_imagen 
            ? `<p style="font-size: 0.85em; color: var(--success); margin-top: -5px;">
                  Imagen actual: <a href="${defaults.dir_imagen}" target="_blank">Ver</a> (Sube un archivo para reemplazarla)
               </p>`
            : '';

        slotDiv.innerHTML = `
            <div class="slot-header">
                <h4>Ranura #${isExisting ? 'Editando' : 'Nuevo'}</h4>
                <button type="button" class="btn btn-danger" onclick="removeSlot(this)">X</button>
            </div>
            
            <input type="hidden" name="ranuras[${index}][id_ranura]" value="${defaults.id_ranura}">
            
            <div class="form-group">
                <label for="ranura_${index}_color">Color</label>
                <input type="color" id="ranura_${index}_color" name="ranuras[${index}][color]" value="${defaults.color}" required>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_type">Tipo (Type)</label>
                <select id="ranura_${index}_type" name="ranuras[${index}][type]" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="premio_menor" ${defaults.type === 'premio_menor' ? 'selected' : ''}>Premio Menor</option>
                    <option value="premio_mayor" ${defaults.type === 'premio_mayor' ? 'selected' : ''}>Premio Mayor</option>
                    <option value="intentar_de_nuevo" ${defaults.type === 'intentar_de_nuevo' ? 'selected' : ''}>Intentar de Nuevo</option>
                    <option value="bancarrota" ${defaults.type === 'bancarrota' ? 'selected' : ''}>Bancarrota</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_texto">Texto (Opcional)</label>
                <input type="text" id="ranura_${index}_texto" name="ranuras[${index}][texto]" placeholder="Texto en la ruleta" value="${defaults.texto}" >
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_rate">Tasa (Rate) / Probabilidad</label>
                <input type="number" id="ranura_${index}_rate" name="ranuras[${index}][rate]" value="${defaults.rate}" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_dir_imagen">Imagen de Ranura (Opcional)</label>
                ${imageHtml}
                <input type="file" id="ranura_${index}_dir_imagen" name="ranuras[${index}][dir_imagen]" accept="image/*">
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="ranura_${index}_blocked" name="ranuras[${index}][blocked]" value="1" ${defaults.blocked == 1 ? 'checked' : ''}>
                <label for="ranura_${index}_blocked">Bloqueada (Blocked)</label>
            </div>
        `;
        
        slotContainer.appendChild(slotDiv);
        updateSlotTitle();
    }

    // Función global para remover una ranura
    window.removeSlot = function(buttonElement) {
        // ... (código de removeSlot sin cambios)
        const slotItem = buttonElement.closest('.slot-item');
        if (slotItem) {
            const slotId = slotItem.dataset.id;
            
            if (slotId && slotId !== 'new') {
                deletedSlotIds.push(slotId);
            }
            
            slotItem.remove();
            updateSlotTitle(); 
        }
    };

    // Listener para el botón de agregar ranura
    addSlotBtn.addEventListener('click', () => {
        // 🌟 CORRECCIÓN CLAVE 3: Generamos una clave alfanumérica única para las inserciones
        newSlotCounter++;
        const uniqueKey = `new_${newSlotCounter}`; 
        createSlotElement(uniqueKey);
    });
    
    /**
     * Función que inicializa el formulario con los datos recibidos de Blade ($ranuras).
     */
    function initializeForm() {
        const displayIdElement = document.getElementById('display_ruleta_id');
        if (displayIdElement && initialRuletaId) {
            displayIdElement.textContent = initialRuletaId;
        }

        // 2. Renderizar las ranuras existentes
        if (initialRanuras.length > 0) {
            slotContainer.innerHTML = ''; // Limpia el placeholder inicial
            initialRanuras.forEach(slotData => {
                // 🌟 CORRECCIÓN CLAVE 4: Usamos directamente el ID de la base de datos como clave
                createSlotElement(slotData.id_ranura, slotData);
            });
        }
        
        updateSlotTitle();
    }
    
    // 💡 Ejecutar la inicialización al cargar la ventana
    window.onload = initializeForm; 
</script>
</body>
</html>