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
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); /* Sombra basada en el color principal */
            outline: none;
        }
        .slot-info {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #ecfdf5; /* Fondo verde claro */
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
            grid-column: span 2; /* Ocupa más espacio en desktop */
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
        <p>Utiliza este formulario para configurar las ranuras de una ruleta existente. **Asegúrate de saber el ID de la Ruleta a la que pertenecen**.</p>
        <div class="form-group">
            <label for="id_ruleta_global">ID de la Ruleta a Configurar</label>
            <input type="number" id="id_ruleta_global" placeholder="Ingresa el ID de la ruleta (Ej: 42)" required>
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
    const slotContainer = document.getElementById('slotContainer');
    const addSlotBtn = document.getElementById('addSlotBtn');
    const slotForm = document.getElementById('slotForm');
    const ranurasTitle = document.getElementById('ranuras-title');
    const ruletaIdInput = document.getElementById('id_ruleta_global');
    const hiddenRuletaId = document.getElementById('hidden_id_ruleta');
    let globalSlotCounter = 0; // Usado para el número visible (Ranura #1, #2, etc.)

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
            event.preventDefault(); // Detener el envío si no hay ranuras
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
        const originalColor = submitBtn.style.backgroundColor;
        
        submitBtn.textContent = message;
        submitBtn.style.backgroundColor = 'var(--danger)'; 
        
        setTimeout(() => {
            submitBtn.textContent = originalText;
            submitBtn.style.backgroundColor = originalColor || 'var(--primary)'; // Vuelve al color original
        }, 2000);
    }

    /**
     * Actualiza el contador en el título de la sección de ranuras.
     */
    function updateSlotTitle() {
        // Re-enumerar las ranuras visibles después de agregar o eliminar
        const items = slotContainer.querySelectorAll('.slot-item');
        let visibleCount = 0;
        items.forEach((item, index) => {
            visibleCount++;
            const header = item.querySelector('.slot-header h4');
            if (header) {
                header.textContent = `Ranura #${index + 1}`;
            }
        });

        ranurasTitle.textContent = `Ranuras Actuales: ${visibleCount} Ranuras Agregadas`;
        globalSlotCounter = visibleCount; // Sincroniza el contador con el número real de elementos
        
        // Muestra u oculta el placeholder
        const placeholder = slotContainer.querySelector('p');
        if (visibleCount === 0) {
            if (!placeholder) {
                const newPlaceholder = document.createElement('p');
                newPlaceholder.style.textAlign = 'center';
                newPlaceholder.style.color = '#6b7280';
                newPlaceholder.textContent = 'Presiona "Agregar Ranura" para empezar a configurar las opciones de la ruleta.';
                slotContainer.appendChild(newPlaceholder);
            }
        } else if (placeholder) {
            placeholder.remove();
        }
    }

    /**
     * Crea un nuevo bloque de formulario para una ranura (slot).
     * @param {number} index - El índice único de la ranura (timestamp) para la clave del array en PHP.
     */
    function createSlotElement(index) {
        const slotDiv = document.createElement('div');
        slotDiv.classList.add('slot-item');
        slotDiv.dataset.index = index;
        
        // La clave 'ranuras[${index}][campo]' es lo que hace que Laravel reciba un array de objetos.
        slotDiv.innerHTML = `
            <div class="slot-header">
                <h4>Ranura #Nuevo</h4>
                <button type="button" class="btn btn-danger" onclick="removeSlot(this)">X</button>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_color">Color</label>
                <input type="color" id="ranura_${index}_color" name="ranuras[${index}][color]" value="#2e86de" required>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_type">Tipo (Type)</label>
                <input type="text" id="ranura_${index}_type" name="ranuras[${index}][type]" placeholder="Ej: Premio, Descuento, Vacío" required>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_texto">Texto</label>
                <input type="text" id="ranura_${index}_texto" name="ranuras[${index}][texto]" placeholder="Texto en la ruleta" required>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_rate">Tasa (Rate) / Probabilidad</label>
                <input type="number" id="ranura_${index}_rate" name="ranuras[${index}][Rate]" value="10" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="ranura_${index}_dir_imagen">Imagen de Ranura (Opcional)</label>
                <!-- Archivo de ranura. Nombre: ranuras[INDEX][dir_imagen] -->
                <input type="file" id="ranura_${index}_dir_imagen" name="ranuras[${index}][dir_imagen]" accept="image/*">
            </div>

            <div class="form-group checkbox-group">
                <!-- Si se marca, se envía '1'. Si no, Laravel lo interpreta como null, por lo que debes usar el operador ?? 0 en el controlador. -->
                <input type="checkbox" id="ranura_${index}_blocked" name="ranuras[${index}][Blocked]" value="1">
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
            slotItem.remove();
            updateSlotTitle(); // Re-actualiza el título y los números de ranura
        }
    };

    // Listener para el botón de agregar ranura
    addSlotBtn.addEventListener('click', () => {
        // Usar la marca de tiempo para asegurar un índice único y evitar conflictos en el array de Laravel
        const uniqueIndex = Date.now(); 
        createSlotElement(uniqueIndex);
    });
    
    // Al cargar, aseguramos que el título esté correcto
    window.onload = updateSlotTitle;
</script>
</body>
</html>
