// ui.js

const modal = document.querySelector(".cont_modal_rulet");
const container = document.querySelector(".container_r");
const cedulaInput = document.getElementById("cedula");
const idSorteoInput = document.querySelector(
    '.form_rulet input[name="id_sorteo"]'
);

const FULL_ROUNDS = 5;
const TARGET_SLOT_ANGLE = 30;

/**
 * 🔄 NUEVA FUNCIÓN: Genera dinámicamente las ranuras (slots) de la ruleta.
 * @param {Array<{nombre_premio: string, index: number}>} premios - Lista de premios a mostrar.
 */
function createWheelSlots(premios) {
    if (!container) return;

    container.innerHTML = "";

    const totalSlots = premios.length;
    const slotAngle = 360 / totalSlots;

    premios.forEach((premio, index) => {
        const slot = document.createElement("div");

        slot.classList.add("slot-style", `slot-${index + 1}`);

        const rotationAngle = index * slotAngle;

        slot.style.transform = `rotate(${rotationAngle}deg)`;

        const innerText = document.createElement("span");
        innerText.textContent = premio.nombre_premio;

        slot.appendChild(innerText);
        container.appendChild(slot);
    });

    container.setAttribute("data-slots", totalSlots);
}

/**
 * @param {Object} data
 */
export function openModal(data = {}) {
    modal.style.transform = "translateX(0)";
    modal.style.display = "block";

    // ✅ CAMBIO: Llama a la función para crear ranuras dinámicas
    if (
        data.ruleta &&
        data.ruleta.premios &&
        Array.isArray(data.ruleta.premios)
    ) {
        createWheelSlots(data.ruleta.premios);
    }

    const nombreRuleta = document.querySelector(".nombre_ruleta");
    const nombreJugador = document.querySelector(".nombre_jugador p");
    const girosDisponibles = document.querySelector(".cont_cant_op p");
    const mensajeResult = document.querySelector(".mensaje_result p");

    if (nombreRuleta && data.ruleta.nombre) {
        nombreRuleta.textContent = data.ruleta.nombre;
    }

    if (nombreJugador && data.cliente.nombre) {
        nombreJugador.textContent = data.cliente.nombre;
    }

    if (girosDisponibles && data.cliente.oportunidades !== undefined) {
        girosDisponibles.textContent = data.cliente.oportunidades;
    }

    if (mensajeResult && data.mensaje_inicial) {
        mensajeResult.textContent = data.mensaje_inicial;
    }

    const spinForm = document.querySelector(".content_ruleta form");
    if (spinForm) {
        spinForm.querySelector('input[name="id_sorteo"]').value = idSorteoInput
            ? idSorteoInput.value
            : "";

        spinForm.querySelector('input[name="cedula"]').value =
            cedulaInput.value.trim();
    }
}

export function closeModal() {
    modal.style.transform = "translateX(110%)";
    setTimeout(() => {
        modal.style.display = "none";
    }, 500);
}

/**
 * @param {Object} data - Datos de la API con el resultado del giro.
 * @returns {Promise<void>} Una promesa que se resuelve al terminar la animación.
 */
export function animateRuleta(data) {
    // ✅ CAMBIO: Accede al número de ranuras real
    const totalSlots = container.children.length;

    let newRotation = FULL_ROUNDS * 360 + TARGET_SLOT_ANGLE;
    container.style.transform = "rotate(-" + newRotation + "deg)";

    return new Promise((resolve) => {
        setTimeout(() => {
            resolve();
        }, 5000);
    });
}
