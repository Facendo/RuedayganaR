document.addEventListener("DOMContentLoaded", function () {
    // Definición de todos los elementos del DOM
    const form = document.querySelector(".cont_form");
    const formCompra = document.getElementById("reg_compra");
    const cedulaInput = document.getElementById("cedula");
    const nombreInput = document.getElementById("nombre_y_apellido"); // Corregido
    const nombreApellidoInput = document.getElementById("nombre_y_apellido");
    const telefonoInput = document.getElementById("telefono");
    const correoInput = document.getElementById("correo");
    const cantidadTicketsInput = document.getElementById("cantidad_de_tickets");
    const montoInput = document.getElementById("monto");
    const metodoPagoSelect = document.getElementById("metodo_de_pago");
    const referenciaInput = document.getElementById("referencia");
    const fechaPagoInput = document.getElementById("fecha_de_pago");
    const imagenComprobanteInput =
        document.getElementById("imagen_comprobante");
    const mensajeCargaImagen = document.getElementById("mensajeCargaImagen");
    const messageElement = document.getElementById("message");
    const miFormulario = document.querySelector(".form"); // Corregido

    // Función para mostrar errores debajo del campo
    function showError(element, message) {
        let errorElement = element.nextElementSibling;
        if (
            !errorElement ||
            !errorElement.classList.contains("error-message")
        ) {
            errorElement = document.createElement("p");
            errorElement.classList.add("error-message");
            errorElement.style.color = "red";
            errorElement.style.fontSize = "0.8em";
            element.parentNode.insertBefore(errorElement, element.nextSibling);
        }
        errorElement.textContent = message;
    }

    // Lógica para guardar usuario en LocalStorage
    const saveUserToLocalStorage = () => {
        const newUser = {
            cedula: cedulaInput.value,
            nombre: nombreInput.value,
            telefono: telefonoInput.value,
            correo: correoInput.value,
        };

        const usuariosGuardados = localStorage.getItem("listaUsuarios");
        let listaUsuarios = usuariosGuardados
            ? JSON.parse(usuariosGuardados)
            : [];

        const usuarioIndex = listaUsuarios.findIndex(
            (u) => u.cedula === newUser.cedula
        );

        if (usuarioIndex > -1) {
            listaUsuarios[usuarioIndex] = newUser;
        } else {
            listaUsuarios.push(newUser);
        }

        localStorage.setItem("listaUsuarios", JSON.stringify(listaUsuarios));
    };

    // Lógica para rellenar campos al escribir la cédula
    cedulaInput.addEventListener("input", () => {
        const cedula = cedulaInput.value;
        const usuariosGuardados = localStorage.getItem("listaUsuarios");
        const listaUsuarios = usuariosGuardados
            ? JSON.parse(usuariosGuardados)
            : [];
        const usuarioEncontrado = listaUsuarios.find(
            (u) => u.cedula === cedula
        );

        if (usuarioEncontrado) {
            nombreInput.value = usuarioEncontrado.nombre;
            telefonoInput.value = usuarioEncontrado.telefono;
            correoInput.value = usuarioEncontrado.correo;
        } else {
            nombreInput.value = "";
            telefonoInput.value = "";
            correoInput.value = "";
        }
    });

    // Lógica para mostrar el nombre del archivo de imagen
    if (imagenComprobanteInput && mensajeCargaImagen) {
        imagenComprobanteInput.addEventListener("change", () => {
            if (imagenComprobanteInput.files.length > 0) {
                const fileName = imagenComprobanteInput.files[0].name;
                mensajeCargaImagen.textContent = `Archivo seleccionado: ${fileName}. Listo para subir.`;
                mensajeCargaImagen.style.display = "block";
                mensajeCargaImagen.style.color = "#3498db";
            } else {
                mensajeCargaImagen.textContent = "";
                mensajeCargaImagen.style.display = "none";
            }
        });
    }

    // Listener de SUBMIT unificado para la validación y el envío
    form.addEventListener("submit", function (event) {
        let isValid = true;

        // Limpiar todos los mensajes de error previos
        document
            .querySelectorAll(".error-message")
            .forEach((el) => el.remove());
        if (mensajeCargaImagen) {
            mensajeCargaImagen.style.display = "none";
        }
        if (messageElement) {
            messageElement.style.display = "none";
        }

        // --- VALIDACIONES DE CADA CAMPO ---
        const cedula = cedulaInput.value.trim();
        if (cedula === "") {
            showError(cedulaInput, "La cédula es obligatoria.");
            isValid = false;
        } else if (!/^\d{6,10}$/.test(cedula)) {
            showError(
                cedulaInput,
                "La cédula debe contener entre 6 y 10 dígitos numéricos."
            );
            isValid = false;
        }

        const nombreApellido = nombreApellidoInput.value.trim();
        if (nombreApellido === "") {
            showError(
                nombreApellidoInput,
                "El nombre y apellido son obligatorios."
            );
            isValid = false;
        } else if (nombreApellido.length < 3) {
            showError(
                nombreApellidoInput,
                "El nombre y apellido deben tener al menos 3 caracteres."
            );
            isValid = false;
        }

        const telefono = telefonoInput.value.trim();
        if (telefono === "") {
            showError(telefonoInput, "El teléfono es obligatorio.");
            isValid = false;
        } else if (!/^\d{10,15}$/.test(telefono)) {
            showError(
                telefonoInput,
                "El teléfono debe contener entre 10 y 15 dígitos numéricos."
            );
            isValid = false;
        }

        const correo = correoInput.value.trim();
        if (correo === "") {
            showError(correoInput, "El correo es obligatorio.");
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
            showError(
                correoInput,
                "Ingrese un formato de correo electrónico válido."
            );
            isValid = false;
        }

        const cantidadTickets = parseInt(cantidadTicketsInput.value);
        const maxTickets = parseInt(cantidadTicketsInput.getAttribute("max"));
        if (isNaN(cantidadTickets) || cantidadTickets < 1) {
            showError(
                cantidadTicketsInput,
                "Debe seleccionar al menos 1 ticket."
            );
            isValid = false;
        } else if (cantidadTickets > maxTickets) {
            showError(
                cantidadTicketsInput,
                `Solo quedan ${maxTickets} tickets disponibles.`
            );
            isValid = false;
        }

        const montoValue = montoInput.value.trim();
        const monto = parseFloat(montoValue);
        if (montoValue === "") {
            showError(montoInput, "El monto es obligatorio.");
            isValid = false;
        } else if (isNaN(monto) || monto <= 0) {
            showError(
                montoInput,
                "El monto no puede ser cero o un valor inválido."
            );
            isValid = false;
        }

        if (metodoPagoSelect && metodoPagoSelect.value === "n") {
            showError(metodoPagoSelect, "Debe seleccionar un método de pago.");
            isValid = false;
        }

        const referencia = referenciaInput.value.trim();
        if (referencia === "") {
            showError(referenciaInput, "La referencia de pago es obligatoria.");
            isValid = false;
        } else if (parseInt(referencia) <= 0) {
            showError(
                referenciaInput,
                "La referencia debe ser un número positivo."
            );
            isValid = false;
        }

        const fechaPago = fechaPagoInput.value;
        if (fechaPago === "") {
            showError(fechaPagoInput, "La fecha de pago es obligatoria.");
            isValid = false;
        }

        if (imagenComprobanteInput.files.length === 0) {
            if (mensajeCargaImagen) {
                mensajeCargaImagen.textContent =
                    "Debe subir un comprobante de pago.";
                mensajeCargaImagen.style.color = "red";
                mensajeCargaImagen.style.display = "block";
            }
            isValid = false;
        } else {
            const file = imagenComprobanteInput.files[0];
            const allowedTypes = ["image/png", "image/jpeg", "image/jpg"];
            const maxSizeMB = 5;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;

            if (!allowedTypes.includes(file.type)) {
                if (mensajeCargaImagen) {
                    mensajeCargaImagen.textContent =
                        "Tipo de archivo no permitido. Solo se aceptan PNG, JPEG, JPG.";
                    mensajeCargaImagen.style.color = "red";
                    mensajeCargaImagen.style.display = "block";
                }
                isValid = false;
            } else if (file.size > maxSizeBytes) {
                if (mensajeCargaImagen) {
                    mensajeCargaImagen.textContent = `El archivo es demasiado grande. Máximo ${maxSizeMB}MB.`;
                    mensajeCargaImagen.style.color = "red";
                    mensajeCargaImagen.style.display = "block";
                }
                isValid = false;
            }
        }

        // Si la validación falla, detenemos el envío del formulario
        if (!isValid) {
            event.preventDefault();
            if (messageElement) {
                messageElement.classList.remove("mesage_success");
                messageElement.classList.add("mesage_error");
                messageElement.textContent =
                    "Algunos datos son incorrectos, por favor revisa los campos.";
                messageElement.style.display = "block";
            }
            // Aquí es donde la ejecución se detiene
            return;
        }
        if (messageElement && isValid) {
            /*messageElement.classList.remove('mesage_error');
            messageElement.classList.add('mesage_success');
            messageElement.textContent = 'El formulario se ha enviado correctamente. Por favor, espere que su pago sea procesado.';
            messageElement.style.display = 'block';*/
            alert(
                "Los datos de pago se han enviado correctamente. Por favor, espere que su pago sea procesado. En breve llegarán sus números al correo registrado."
            );
        }

        // Si el formulario es válido, guardamos los datos en localStorage y se envía
        saveUserToLocalStorage();
        // Mostrar mensaje de carga de imagen si se ha seleccionado un archivo
        if (imagenComprobanteInput.files.length > 0) {
            mensajeCargaImagen.textContent =
                "Subiendo comprobante... Por favor, espera.";
            mensajeCargaImagen.style.display = "block";
            mensajeCargaImagen.style.color = "#e67e22";
        }
        // El formulario se enviará de forma natural
    });

    // Lógica para mostrar mensajes de sesión (éxito/error)
    const sessionMessages = document.getElementById("session-messages");
    const messageContainer = document.getElementById(
        "dynamic-message-container"
    );

    if (sessionMessages && messageContainer) {
        const successMessage = sessionMessages.dataset.success;
        const errorMessage = sessionMessages.dataset.error;
        let messageHTML = "";

        if (successMessage) {
            messageHTML = `<div class="mesage_success" role="alert"><span class="block sm:inline">${successMessage}</span></div>`;
        }

        if (errorMessage) {
            messageHTML = `<div class="mesage_error" role="alert"><strong>¡Ups!</strong> <span class="block sm:inline">${errorMessage}</span></div>`;
        }

        if (messageHTML) {
            messageContainer.innerHTML = messageHTML;
            setTimeout(() => {
                messageContainer.innerHTML = "";
            }, 5000);
        }
    }
});
