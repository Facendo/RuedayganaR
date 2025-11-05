// ui.js

const modal = document.querySelector(".cont_modal_rulet");
const container = document.querySelector(".container_r");
const cedulaInput = document.getElementById("cedula");
const idSorteoInput = document.querySelector(
    '.form_rulet input[name="id_sorteo"]'
);

const FULL_ROUNDS = 5;
// const TARGET_SLOT_ANGLE = 90;

let ruletaState = {};

/**

 * @param {Object} data 
 */
export function openModal(data = {}) {
    ruletaState = data;
    modal.style.transform = "translateX(0)";
    modal.style.display = "block";

    const nombreRuleta = document.querySelector(".nombre_ruleta");
    const nombreJugador = document.querySelector(".nombre_jugador p");
    const girosDisponibles = document.querySelector(".cont_cant_op p");
    const mensajeResult = document.querySelector(".mensaje_result p");

    generateRuleta(data.ranuras);

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
    console.log("Datos usados para animar:", data);

    const cantRanuras = ruletaState.ranuras.length;
    const precision = 360 / cantRanuras;

    const randomizador = Math.floor(Math.random() * (precision - 2 + 1)) + 2;

    console.log("random: ", randomizador);

    const angle = data.angle;

    let newRotation = FULL_ROUNDS * 360 + angle + randomizador;

    const finalStop = angle + randomizador;

    container.style.transform = "rotate(-" + newRotation + "deg)";

    return new Promise((resolve) => {
        setTimeout(() => {
            resolve(finalStop);
        }, 5000);
    });
}

export function generateRuleta(ranuraData) {
    const container_rulet = document.querySelector(".container_r");

    if (!container_rulet) {
        console.error("El contenedor '.container_r' no fue encontrado.");
        return;
    }

    const totalRanuras = ranuraData.length;
    const sizeSlot = 360 / totalRanuras;

    // El radio se toma como 50 para usar coordenadas de porcentaje (0% a 100%)
    const radius = 50;
    const center = "50% 50%"; // El centro del círculo (esquina superior izquierda 0%, 0%)

    // --- FUNCIÓN AUXILIAR DE TRIGONOMETRÍA MEJORADA ---
    const getCoords = (angle) => {
        const rad = (angle - 90) * (Math.PI / 180);
        const x = (radius * Math.cos(rad) + 50).toFixed(4);
        const y = (radius * Math.sin(rad) + 50).toFixed(4);
        console.log("Valores x= ", x);
        console.log("Valores y= ", y);
        return `${x}% ${y}%`;
    };
    // ----------------------------------------------------

    // Limpia las ranuras anteriores
    while (container_rulet.firstChild) {
        container_rulet.removeChild(container_rulet.firstChild);
    }

    ranuraData.forEach((ranura, cont) => {
        const elementRanura = document.createElement("div");
        elementRanura.classList.add("ranura_rulet");

        // Calcular el ángulo de inicio, fin y CENTRAL
        const startAngle = cont * sizeSlot;
        const endAngle = (cont + 1) * sizeSlot;
        const centerAngle = startAngle + sizeSlot / 2; // Ángulo central para el texto

        // 1. Aplicar el recorte (el resto de tu lógica de ranura)
        const coord1 = getCoords(startAngle);
        const coord2 = getCoords(endAngle);
        elementRanura.style.backgroundColor = ranura.color;
        elementRanura.style.clipPath = `polygon(${center}, ${coord1}, ${coord2})`;

        const textWrapper = document.createElement("div");
        // ... Aplicar clase y calcular rotación (centerAngle)
        textWrapper.style.transform = `rotate(${centerAngle}deg)`;
        textWrapper.classList.add("slot-text-wrapper");

        // **Crear un span interno para el texto** (esencial para la doble transformación)
        const textContent = document.createElement("span");
        textContent.textContent = ranura.texto;
        textWrapper.appendChild(textContent);

        // ¡LA CLAVE! El texto debe ser hijo directo del contenedor principal
        container_rulet.appendChild(textWrapper);

        container_rulet.appendChild(elementRanura); // La ranura se queda aquí
    });

    const halfSlot = sizeSlot / 2; // Ejemplo: Si sizeSlot es 90, halfSlot es 45

    // Aplica una rotación antihoraria (negativa) para que el centro de la
    // primera ranura se alinee con el punto de 0 grados (arriba).
    // container_rulet.style.transform = `rotate(-${halfSlot}deg)`;
}

// export function generateRuleta(ranuraData) {
//     const container_rulet = document.querySelector(".container_r");

//     const totalRanuras = ranuraData.length;
//     const sizeSlot = 360 / totalRanuras;
//     const skewValue = 90 - sizeSlot;

//     // console.log(skewValue);
//     // console.log(totalRanuras);
//     // console.log(sizeSlot);

//     while (container_rulet.firstChild) {
//         container_rulet.removeChild(container_rulet.firstChild);
//     }

//     ranuraData.forEach((ranura, cont) => {
//         const elementRanura = document.createElement("div");
//         elementRanura.classList.add("ranura_rulet");
//         const position = cont * sizeSlot;
//         elementRanura.style.backgroundColor = ranura.color;
//         elementRanura.style.transform = `rotate(${position}deg)`;

//         //  skewY(${skewValue}deg)

//         const textWrapper = document.createElement("div");
//         textWrapper.classList.add("slot-text-wrapper");
//         textWrapper.textContent = ranura.texto;

//         elementRanura.appendChild(textWrapper);
//         container_rulet.appendChild(elementRanura);
//     });
// }
