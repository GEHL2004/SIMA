// Selección de los elementos
const nombre_store = document.getElementById("nombre-store");
const codigo_store = document.getElementById("codigo-store");
const contador_store = document.getElementById("contador-store");
const form_store = document.getElementById("form-store");
const nombre_update = document.getElementById("nombre-update");
const codigo_update = document.getElementById("codigo-update");
const contador_update = document.getElementById("contador-update");
const form_update = document.getElementById("form-update");
const id_tipo_practica = document.getElementById("id_tipo_practica");
const nombre_ver = document.getElementById("nombre-ver");
const codigo_ver = document.getElementById("codigo-ver");

// Agregamos el evento 'input' (se dispara al escribir, borrar o pegar)

codigo_store.addEventListener("input", function () {
    contador_store.textContent = this.value.length;
});

codigo_update.addEventListener("input", function () {
    contador_update.textContent = this.value.length;
});

// Funcionamiento de la Modal de Registro

form_store.addEventListener('submit', function(envt){
    envt.preventDefault();
    if(validar_campo_vacio('nombre-store')){
        warning('Campo vacío', 'El campo de nombre de la tipo de practica se encuentra vacío, rellene lo para continuar por favor.');
        nombre_store.focus();
        return ;
    } else if(validar_campo_vacio('codigo-store')){
        warning('Campo vacío', 'El campo de código de la tipo de practica se encuentra vacío, rellene lo para continuar por favor.');
        codigo_store.focus();
        return ;
    } else if(codigo_store.value.length <=0 || codigo_store.value.length >=11){
        warning('Cantidad de caracteres inválida', 'La código de la tipo de practica es menor o excede los parametros permitidos, corrijala para continuar.');
        codigo_store.focus();
        return ;
    } else {
        this.submit();
    }
});

// Funcionamiento de la Modal de Actualiación

const ModalActualizacion = document.getElementById('ModalActualizacion')
if (ModalActualizacion) {
  ModalActualizacion.addEventListener('show.bs.modal', event => {
    id_tipo_practica.value = '';
    nombre_update.value = '';
    codigo_update.value = '';
    const button = event.relatedTarget;
    let A_id_tipo_practica = button.getAttribute('id-tipo-practica');
    let A_nombre = button.getAttribute('nombre');
    let A_codigo = button.getAttribute('codigo');
    id_tipo_practica.value = A_id_tipo_practica;
    nombre_update.value = A_nombre;
    codigo_update.value = A_codigo;
    contador_update.textContent = codigo_update.value.length;
  });
}

form_update.addEventListener('submit', function(envt){
    envt.preventDefault();
    if(validar_campo_vacio('nombre-update')){
        warning('Campo vacío', 'El campo de nombre de la tipo de practica se encuentra vacío, rellene lo para continuar por favor.');
        nombre_update.focus();
        return ;
    } else if(validar_campo_vacio('codigo-update')){
        warning('Campo vacío', 'El campo de código de la tipo de practica se encuentra vacío, rellene lo para continuar por favor.');
        codigo_update.focus();
        return ;
    } else if(codigo_update.value.length <=0 || codigo_update.value.length >=11){
        warning('Cantidad de caracteres inválida', 'La código de la tipo de practica es menor o excede los parametros permitidos, corrijala para continuar.');
        codigo_update.focus();
        return ;
    } else {
        this.submit();
    }
});

// Funcionamiento de la Modal de Ver Informacion de la Tipo de Practica

const ModalVer = document.getElementById('ModalVer')
if (ModalVer) {
  ModalVer.addEventListener('show.bs.modal', event => {
    nombre_ver.value = '';
    codigo_ver.value = '';
    const button = event.relatedTarget;
    let A_nombre = button.getAttribute('nombre');
    let A_codigo = button.getAttribute('codigo');
    nombre_ver.value = A_nombre;
    codigo_ver.value = A_codigo;
  });
}

// Funcionamiento de Eliminar Tipo de Practica

function eliminar(id_tipo_practica) {
    Swal.fire({
        icon: "question",
        title: "Está seguro de eliminar esta Tipo de Practica?",
        showDenyButton: true,
        confirmButtonText: "Si",
        confirmButtonColor: "#28A745",
        denyButtonText: "No",
        reverseButtons: "true",
    }).then((respuesta) => {
        if (respuesta.isConfirmed) {
            window.location.href = "/SIMA/tipos-practicas-delete/" + id_tipo_practica;
        }
    });
}