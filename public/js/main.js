// main.js

import { checkCedula, spinRuleta } from "./api.js";
import { openModal, closeModal, animateRuleta } from "./ui.js";

document.addEventListener("DOMContentLoaded", () => {
    // Referencias a elementos comunes
    const form = document.querySelector(".form_rulet");
    const closeButton = document.querySelector(".x_modal_rulet");
    const submitBtn = document.querySelector(".submit_btn");
    const spinBtn = document.getElementById("spin");

    // Referencias a inputs para obtener valores
    const cedulaInput = document.getElementById("cedula");
    const idSorteoInput = document.querySelector(
        '.form_rulet input[name="id_sorteo"]'
    );

    // Evento: Cerrar modal
    closeButton.addEventListener("click", closeModal);
    window.addEventListener("click", (event) => {
        const modal = document.querySelector(".cont_modal_rulet");
        if (event.target === modal) {
            closeModal();
        }
    });

    // Evento: Formulario de verificación de cédula
    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // ¡VITAL! Evita la recarga.

            const cedula = cedulaInput.value.trim();
            const idSorteo = idSorteoInput ? idSorteoInput.value : null;

            if (cedula === "") {
                alert("Ingrese su cédula.");
                return;
            }

            submitBtn.disabled = true;

            checkCedula(cedula, idSorteo)
                .then((data) => {
                    console.log("Verificación exitosa:", data);
                    openModal(data);
                })
                .catch((error) => {
                    console.error("Error en verificación:", error);
                    alert("Hubo un error o su cédula no es válida.");
                })
                .finally(() => {
                    submitBtn.disabled = false;
                });
        });
    }

    // Evento: Botón Spin de la ruleta
    if (spinBtn) {
        spinBtn.onclick = function (event) {
            event.preventDefault(); // ¡VITAL! Evita el envío del formulario de spin.

            spinBtn.disabled = true;

            const cedula = document.querySelector(
                '.content_ruleta input[name="cedula"]'
            ).value;
            const idSorteo = document.querySelector(
                '.content_ruleta input[name="id_sorteo"]'
            ).value;

            spinRuleta(idSorteo, cedula)
                .then((data) => {
                    return animateRuleta(data);
                })
                .then((finalStop) => {
                    const container = document.querySelector(".container_r");
                    container.style.transition = "none";
                    container.style.transform = "rotate(-" + finalStop + "deg)";

                    setTimeout(() => {
                        container.style.transition =
                            "transform 5s cubic-bezier(0.25, 0.1, 0.25, 1)";
                        spinBtn.disabled = false;
                    }, 50);
                })
                .catch((error) => {
                    console.error("Error al girar:", error);
                    alert("Fallo la conexión al girar la ruleta.");
                    spinBtn.disabled = false;
                });
        };
    }
});
