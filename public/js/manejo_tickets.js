

document.addEventListener('DOMContentLoaded', function() {
    const sumaBtn = document.getElementById('suma');
    const restaBtn = document.getElementById('resta');
    const cantBoletosDisplay = document.querySelector('.cant_boletos');
    const montoDisplay = document.querySelector('.monto');
    const selectorTickets = document.querySelectorAll('.selector_ticket');
    let cantidadBoletos = 0;
    const sorteoData = document.getElementById('sorteo-data');

    const precioBoletoD = parseFloat(sorteoData.getAttribute('data-precio-dolar'));
    const precioBoletoB = parseFloat(sorteoData.getAttribute('data-precio-bs'));

    const cantTicketInput = document.getElementById('cantidad_de_tickets');
    const montoTicketsInput = document.getElementById('monto');

    

    function actualizarMonto() {
        const totalPagarD = cantidadBoletos * precioBoletoD;
        const totalPagarB =  cantidadBoletos * precioBoletoB
        montoDisplay.textContent = `Total: $${totalPagarD.toFixed(2)} ----- bs${totalPagarB.toFixed(2)}`; 
        cantTicketInput.value = cantidadBoletos;
        montoTicketsInput.value = cantidadBoletos * precioBoletoB;
    }

    

    selectorTickets.forEach(select =>{
        select.addEventListener('click', function() {
            const cantidadSeleccionada = parseInt(this.textContent);
            cantidadBoletos  += cantidadSeleccionada;
            cantBoletosDisplay.textContent = cantidadBoletos;
            actualizarMonto();
        });
    })
    

    sumaBtn.addEventListener('click', function() {
        cantidadBoletos++;
        cantBoletosDisplay.textContent = cantidadBoletos;
        actualizarMonto();
    });

    restaBtn.addEventListener('click', function() {
        if (cantidadBoletos > 0) {
            cantidadBoletos--;
            cantBoletosDisplay.textContent = cantidadBoletos;
            actualizarMonto();
        }
    });

   
    cantBoletosDisplay.textContent = cantidadBoletos;
    actualizarMonto();

});