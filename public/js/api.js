// api.js

export function checkCedula(cedula, idSorteo) {
    const formData = new FormData();
    formData.append("cedula", cedula);
    if (idSorteo) {
        formData.append("id_sorteo", idSorteo);
    }

    return fetch(window.APP_ROUTES.check, {
        // Usa la ruta inyectada
        method: "POST",
        headers: { "X-CSRF-TOKEN": window.APP_ROUTES.token }, // Usa el token inyectado
        body: formData,
    }).then((response) => {
        if (!response.ok) {
            throw new Error("Error de servidor: " + response.status);
        }
        return response.json();
    });
}

export function spinRuleta(idSorteo, cedula) {
    const formData = new FormData();
    formData.append("id_sorteo", idSorteo);
    formData.append("cedula", cedula);

    return fetch(window.APP_ROUTES.spin, {
        // Usa la ruta inyectada
        method: "POST",
        headers: { "X-CSRF-TOKEN": window.APP_ROUTES.token }, // Usa el token inyectado
        body: formData,
    }).then((response) => {
        if (!response.ok) {
            throw new Error("Error de servidor: " + response.status);
        }
        return response.json();
    });
}
