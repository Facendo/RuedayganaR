
//     document.addEventListener('DOMContentLoaded', () => {

//     const cedulaInput = document.getElementById('cedula');
//     const nombreInput = document.getElementById('nombre_y_apellido');
//     const telefonoInput = document.getElementById('telefono');
//     const correoInput = document.getElementById('correo');
//     const formCompra = document.getElementById('reg_compra');

//     const saveUserToLocalStorage = () => {
//         const newUser = {
//             cedula: cedulaInput.value,
//             nombre: nombreInput.value,
//             telefono: telefonoInput.value,
//             correo: correoInput.value
//         };

//         const usuariosGuardados = localStorage.getItem('listaUsuarios');
//         let listaUsuarios = usuariosGuardados ? JSON.parse(usuariosGuardados) : [];

//         const usuarioIndex = listaUsuarios.findIndex(u => u.cedula === newUser.cedula);

//         if (usuarioIndex > -1) {
//             listaUsuarios[usuarioIndex] = newUser;
//         } else {
//             listaUsuarios.push(newUser);
//         }

//         localStorage.setItem('listaUsuarios', JSON.stringify(listaUsuarios));
//     };

//     cedulaInput.addEventListener('input', () => {
//         const cedula = cedulaInput.value;
//         const usuariosGuardados = localStorage.getItem('listaUsuarios');
//         const listaUsuarios = usuariosGuardados ? JSON.parse(usuariosGuardados) : [];
//         const usuarioEncontrado = listaUsuarios.find(u => u.cedula === cedula);

//         if (usuarioEncontrado) {
//             nombreInput.value = usuarioEncontrado.nombre;
//             telefonoInput.value = usuarioEncontrado.telefono;
//             correoInput.value = usuarioEncontrado.correo;
//         } else {
//             nombreInput.value = '';
//             telefonoInput.value = '';
//             correoInput.value = '';
//         }
//     });

//     formCompra.addEventListener('submit', (e) => {

//         e.preventDefault();
//         saveUserToLocalStorage();
//         e.target.submit();
//     });
// });

