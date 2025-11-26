// main.js

import { checkCedula, spinRuleta, sendSecondData } from "./api.js";
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
            event.preventDefault();

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

    if (spinBtn) {
        spinBtn.onclick = function (event) {
            event.preventDefault();

            spinBtn.disabled = true;

            const cedula = document.querySelector(
                '.content_ruleta input[name="cedula"]'
            ).value;
            const idSorteo = document.querySelector(
                '.content_ruleta input[name="id_sorteo"]'
            ).value;

            spinRuleta(idSorteo, cedula)
                .then((data) => {
                    const contentToSend = data.correoContent;

                    if (
                        contentToSend &&
                        typeof contentToSend === "object" &&
                        Object.keys(contentToSend).length > 0
                    ) {
                        console.log(
                            "¡Premio detectado! Enviando datos de contacto (sendSecondData)..."
                        );
                        // No esperamos el resultado de sendSecondData para no frenar la animación.
                        // Solo logueamos el resultado.
                        sendSecondData(contentToSend)
                            .then((response) =>
                                console.log(
                                    "Envío de correos exitoso:",
                                    response
                                )
                            )
                            .catch((mailError) =>
                                console.error(
                                    "Error FATAL en el envío de correos:",
                                    mailError
                                )
                            );
                    } else {
                        console.log(
                            "No hay premios (bancarrota/reintento), no se hace la segunda petición."
                        );
                    }

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
                    const errorMessage = error.message.includes(
                        "No tienes oportunidades"
                    )
                        ? error.message
                        : "Hubo un error inesperado al girar.";

                    console.log(`Error: ${errorMessage}`);

                    spinBtn.disabled = false;
                });
        };
    }
});
