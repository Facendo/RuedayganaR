<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>Gestión Dinámica de Ranuras (Slots)</title>
   
</head>
<body>

<div class="container">
    
    <h1>Gestión de Ranuras (Slots)</h1>

    <!-- El formulario usa POST y multipart/form-data para enviar el array de ranuras y archivos -->
    <!-- RECUERDA: Debes reemplazar "/tu-ruta-de-guardado-ranuras" con la URL real de tu controlador. -->
    <form id="slotForm" method="POST" action="/tu-ruta-de-guardado-ranuras" enctype="multipart/form-data">
        @csrf

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
            <button type="submit" class="button submit_btn" id="submitBtn">
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
                <select id="ranura_${index}_type" name="ranuras[${index}][type]" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="premio_menor">Premio Menor</option>
                    <option value="premio_mayor">Premio Mayor</option>
                    <option value="intentar_de_nuevo">Intentar de Nuevo</option>
                    <option value="bancarrota">Bancarrota</option>
                </select>
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
