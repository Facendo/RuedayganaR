document.addEventListener("DOMContentLoaded", () => {
    const iconsPagoDiv = document.querySelector(".icons_pago");
    const contPagoCompraDiv = document.querySelector(".data_p");
    const metodoPagoInput = document.getElementById("metodo_pago_seleccionado");

    // Asegúrate de que estas variables estén definidas en tu plantilla Blade
    // Por ejemplo:
    // <script> window.AppConfig = { copyIconUrl: '...', successIconUrl: '...', errorIconUrl: '...' }; </script>
    const copyIconUrl = window.AppConfig.copyIconUrl;
    const successIconUrl = window.AppConfig.successIconUrl;
    const errorIconUrl = window.AppConfig.errorIconUrl;

    const detallesDePago = {
        "Pago movil Banesco": [
            { label: "Banco", data: "0134" },
            { label: "C.I.", data: "28.407.272" },
            { label: "Tlf", data: "0424-8676344" },
        ],
        "Pago movil Banplus": [
            { label: "Banco", data: "0174" },
            { label: "C.I.", data: "28.588.823" },
            { label: "Tlf", data: "0412-9425624" },
        ],
        Zinli: [
            { label: "Nombre", data: "Jesus Melean" },
            { label: "Correo", data: "rocktoyonyo@gmail.com" },
        ],
        Binance: [
            { label: "Nombre", data: "Jesus Melean" },
            { label: "Correo", data: "rocktoyonyo@gmail.com" },
            { label: "ID", data: "163593375" },
        ],
        Zelle: [
            { label: "Nombre", data: "Jaider Brito" },
            { label: "Numero", data: "+1 615-755-3258" },
        ],
    };

    function createCopyableData(labelText, dataValue) {
        return `<p class="data">${labelText}: <span class="copyable-text">${dataValue}</span> <img src="${copyIconUrl}" class="copy-icon" data-text="${dataValue}" alt="Copiar" title="Copiar al portapapeles"></p>`;
    }

    function mostrarDetallesDePago(metodo) {
        let contenidoAMostrar = "";

        if (detallesDePago[metodo]) {
            contenidoAMostrar += `<h3>${metodo}</h3>`;
            detallesDePago[metodo].forEach((item) => {
                contenidoAMostrar += createCopyableData(item.label, item.data);
            });
            // Agrega el mensaje especial para Zelle si es el método seleccionado
            if (metodo === "Zelle") {
                contenidoAMostrar += `<p class="data"><b>Importante</b>: Colocar en Asunto: pago</p>`;
            }
        } else {
            contenidoAMostrar = `<p>Por favor, selecciona un método de pago para ver los detalles.</p>`;
        }

        contPagoCompraDiv.innerHTML = contenidoAMostrar;
        metodoPagoInput.value = metodo;
        addCopyListeners();
    }

    function addCopyListeners() {
        const copyIcons = contPagoCompraDiv.querySelectorAll(".copy-icon");
        copyIcons.forEach((icon) => {
            icon.addEventListener("click", (event) => {
                const textToCopy = event.target.dataset.text;
                navigator.clipboard
                    .writeText(textToCopy)
                    .then(() => {
                        const originalSrc = icon.src;
                        const originalTitle = icon.title;
                        icon.src = successIconUrl;
                        icon.title = "¡Copiado!";
                        setTimeout(() => {
                            icon.src = originalSrc;
                            icon.title = originalTitle;
                        }, 1500);
                    })
                    .catch((err) => {
                        console.error("Error al copiar el texto: ", err);
                        const originalSrc = icon.src;
                        const originalTitle = icon.title;
                        icon.src = errorIconUrl;
                        icon.title = "Error al copiar";
                        setTimeout(() => {
                            icon.src = originalSrc;
                            icon.title = originalTitle;
                        }, 1500);
                    });
            });
        });
    }

    iconsPagoDiv.addEventListener("click", (event) => {
        if (event.target.tagName === "IMG") {
            const selectedMetodo = event.target.dataset.metodo;
            mostrarDetallesDePago(selectedMetodo);

            const allImages = iconsPagoDiv.querySelectorAll("img");
            allImages.forEach((img) => img.classList.remove("selected"));
            event.target.classList.add("selected");
        }
    });

    // Estado inicial
    mostrarDetallesDePago("default");
});
