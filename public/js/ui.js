// ui.js

const modal = document.querySelector(".cont_modal_rulet");
const container = document.querySelector(".container_r");
const cedulaInput = document.getElementById("cedula");
const idSorteoInput = document.querySelector(
    '.form_rulet input[name="id_sorteo"]'
);
const girosDisponibles = document.querySelector(".cont_cant_op p");

const mensajeResult = document.getElementById("result_rulet");
const mensajeContainResult = document.querySelector(".mensaje_result");

const FULL_ROUNDS = 5;
// const TARGET_SLOT_ANGLE = 90;

let ruletaState = {};

/**

 * @param {Object} data 
 */
export function openModal(data = {}) {
    ruletaState = data;
    modal.style.display = "block";

    setTimeout(() => {
        modal.style.transform = "translateX(0)";
    }, 200);

    const nombreRuleta = document.querySelector(".nombre_ruleta");
    const nombreJugador = document.querySelector(".nombre_jugador p");
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

let hasShownZeroGirosAlert = false;

export function animateRuleta(data) {
    const spinBtn = document.getElementById("spin");
    const emergentWindow = document.querySelector(".emergent_window");
    const close = document.querySelector(".close_window");
    const msg = document.querySelector(".message_alert_window");

    if (data.oportunidades_cliente <= 0 && hasShownZeroGirosAlert) {
        msg.textContent =
            "No tienes giros disponibles. Compra más tickets para obtener más intentos.";

        emergentWindow.style.display = "block";

        setTimeout(() => {
            emergentWindow.style.transform = "scale(1)";
        }, 10);

        if (spinBtn) {
            spinBtn.disabled = false;
        }

        close.onclick = () => {
            emergentWindow.style.transform = "scale(0)";
            setTimeout(() => {
                emergentWindow.style.display = "none";
            }, 300);
        };

        return Promise.resolve(null);
    }

    console.log("datos de animacion", data);
    const cantRanuras = ruletaState.ranuras.length;
    const precision = 360 / cantRanuras;

    const MINIMO = 2;
    const MAXIMO = precision - 1; //

    const randomizador =
        Math.floor(Math.random() * (MAXIMO - MINIMO + 1)) + MINIMO;
    const angle = data.angle;
    let newRotation = FULL_ROUNDS * 360 + angle + randomizador;
    const finalStop = angle + randomizador;
    container.style.transform = "rotate(-" + newRotation + "deg)";

    setTimeout(() => {
        // 3. Actualización de UI y Alerta Tras el Último Giro
        girosDisponibles.textContent = data.oportunidades_cliente;
        mensajeResult.textContent = data.premio;
        mensajeContainResult.style.backgroundColor = data.color;

        // La ventana emergente aparece si el conteo es 0 (último giro)
        if (data.oportunidades_cliente <= 0 && !hasShownZeroGirosAlert) {
            msg.textContent =
                "¡Último giro completado! No tienes más intentos disponibles.";
            emergentWindow.style.display = "block";
            setTimeout(() => {
                emergentWindow.style.transform = "scale(1)";
            }, 10);

            if (spinBtn) {
                spinBtn.disabled = false;
            }

            close.onclick = () => {
                emergentWindow.style.transform = "scale(0)";
                setTimeout(() => {
                    emergentWindow.style.display = "none";
                }, 300);
            };

            hasShownZeroGirosAlert = true;
        }
    }, 5100);

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

    const radius = 50;
    const center = "50% 50%";

    const getCoords = (angle) => {
        const rad = (angle - 90) * (Math.PI / 180);
        const x = (radius * Math.cos(rad) + 50).toFixed(4);
        const y = (radius * Math.sin(rad) + 50).toFixed(4);
        return `${x}% ${y}%`;
    };

    // Limpia las ranuras anteriores
    while (container_rulet.firstChild) {
        container_rulet.removeChild(container_rulet.firstChild);
    }

    ranuraData.forEach((ranura, cont) => {
        const startAngle = cont * sizeSlot;
        const endAngle = (cont + 1) * sizeSlot;
        const centerAngle = startAngle + sizeSlot / 2;

        const elementRanura = document.createElement("div");
        elementRanura.classList.add("ranura_rulet");

        const coord1 = getCoords(startAngle);
        const coord2 = getCoords(endAngle);
        elementRanura.style.backgroundColor = ranura.color;
        elementRanura.style.clipPath = `polygon(${center}, ${coord1}, ${coord2})`;

        const textWrapper = document.createElement("div");
        textWrapper.classList.add("slot-text-wrapper");

        const rotationAngle = centerAngle - 90;
        textWrapper.style.transform = `rotate(${rotationAngle}deg)`;

        const textContent = document.createElement("div");
        textContent.classList.add("slot-text");
        textContent.textContent = ranura.texto;

        textContent.style.transform = `rotate(${-rotationAngle}deg)`;

        textWrapper.appendChild(textContent);
        container_rulet.appendChild(elementRanura);
        container_rulet.appendChild(textWrapper);
    });
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
