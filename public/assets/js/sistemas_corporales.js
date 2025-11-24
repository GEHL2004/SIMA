// Selección de los elementos
const nombre_store = document.getElementById("nombre-store");
const descripcion_store = document.getElementById("descripcion-store");
const contador_store = document.getElementById("contador-store");
const form_store = document.getElementById("form-store");
const nombre_update = document.getElementById("nombre-update");
const descripcion_update = document.getElementById("descripcion-update");
const contador_update = document.getElementById("contador-update");
const form_update = document.getElementById("form-update");
const id_sistema_corporal = document.getElementById("id_sistema_corporal");
const nombre_ver = document.getElementById("nombre-ver");
const descripcion_ver = document.getElementById("descripcion-ver");

// Agregamos el evento 'input' (se dispara al escribir, borrar o pegar)

descripcion_store.addEventListener("input", function () {
    contador_store.textContent = this.value.length;
});

descripcion_update.addEventListener("input", function () {
    contador_update.textContent = this.value.length;
});

// Funcionamiento de la Modal de Registro

form_store.addEventListener('submit', function(envt){
    envt.preventDefault();
    if(validar_campo_vacio('nombre-store')){
        warning('Campo vacío', 'El campo de nombre del sistema corporal se encuentra vacío, rellene lo para continuar por favor.');
        nombre_store.focus();
        return ;
    } else if(validar_campo_vacio('descripcion-store')){
        warning('Campo vacío', 'El campo de descripción del sistema corporal se encuentra vacío, rellene lo para continuar por favor.');
        descripcion_store.focus();
        return ;
    } else if(descripcion_store.value.length <=0 || descripcion_store.value.length >=501){
        warning('Cantidad de caracteres inválida', 'La descripción del sistema corporal es menor o excede los parametros permitidos, corrijala para continuar.');
        descripcion_store.focus();
        return ;
    } else {
        this.submit();
    }
});

// Funcionamiento de la Modal de Actualiación

const ModalActualizacion = document.getElementById('ModalActualizacion')
if (ModalActualizacion) {
  ModalActualizacion.addEventListener('show.bs.modal', event => {
    id_sistema_corporal.value = '';
    nombre_update.value = '';
    descripcion_update.value = '';
    const button = event.relatedTarget;
    let A_id_sistema_corporal = button.getAttribute('id-sistema-corporal');
    let A_nombre = button.getAttribute('nombre');
    let A_descripcion = button.getAttribute('descripcion');
    id_sistema_corporal.value = A_id_sistema_corporal;
    nombre_update.value = A_nombre;
    descripcion_update.value = A_descripcion;
    contador_update.textContent = descripcion_update.value.length;
  });
}

form_update.addEventListener('submit', function(envt){
    envt.preventDefault();
    if(validar_campo_vacio('nombre-update')){
        warning('Campo vacío', 'El campo de nombre del sistema corporal se encuentra vacío, rellene lo para continuar por favor.');
        nombre_update.focus();
        return ;
    } else if(validar_campo_vacio('descripcion-update')){
        warning('Campo vacío', 'El campo de descripción del sistema corporal se encuentra vacío, rellene lo para continuar por favor.');
        descripcion_update.focus();
        return ;
    } else if(descripcion_update.value.length <=0 || descripcion_update.value.length >=501){
        warning('Cantidad de caracteres inválida', 'La descripción del sistema corporal es menor o excede los parametros permitidos, corrijala para continuar.');
        descripcion_update.focus();
        return ;
    } else {
        this.submit();
    }
});

// Funcionamiento de la Modal de Ver Informacion del sistema corporal

const ModalVer = document.getElementById('ModalVer')
if (ModalVer) {
  ModalVer.addEventListener('show.bs.modal', event => {
    nombre_ver.value = '';
    descripcion_ver.value = '';
    const button = event.relatedTarget;
    let A_nombre = button.getAttribute('nombre');
    let A_descripcion = button.getAttribute('descripcion');
    nombre_ver.value = A_nombre;
    descripcion_ver.value = A_descripcion;
  });
}

// Funcionamiento de Eliminar el sistema corporal

function eliminar(id_sistema_corporal) {
    Swal.fire({
        icon: "question",
        title: "Está seguro de eliminar este Sistema Corporal?",
        showDenyButton: true,
        confirmButtonText: "Si",
        confirmButtonColor: "#28A745",
        denyButtonText: "No",
        reverseButtons: "true",
    }).then((respuesta) => {
        if (respuesta.isConfirmed) {
            window.location.href = "/SIMA/sistemas-corporales-delete/" + id_sistema_corporal;
        }
    });
}