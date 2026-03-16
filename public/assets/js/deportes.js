const nombre = document.getElementById('nombre');
const categoria = document.getElementById('categoria');
const es_olimpico = document.getElementById('es_olimpico');
const popularidad = document.getElementById('popularidad');
const deporte_nacional = document.getElementById('deporte_nacional');

function eliminar(id_deporte) {
    Swal.fire({
        icon: "question",
        title: "Está seguro de eliminar este deporte?",
        showDenyButton: true,
        confirmButtonText: "Si",
        confirmButtonColor: "#28A745",
        denyButtonText: "No",
        reverseButtons: "true",
    }).then((respuesta) => {
        if (respuesta.isConfirmed) {
            // console.log("/SIMA/especialidades-eliminar/" + id_deporte);
            window.location.href = "/SIMA/deportes-delete/" + id_deporte;
        }
    });
}

function valdiarCampos(tipo){
    let nombre = tipo == 1 ? 'nombre-edit' : 'nombre-create';
    let categoria = tipo == 1 ? 'categoria-edit' : 'categoria-create';
    let es_olimpico = tipo == 1 ? 'es_olimpico-edit' : 'es_olimpico-create';
    let popularidad = tipo == 1 ? 'popularidad-edit' : 'popularidad-create';
    let deporte_nacional = tipo == 1 ? 'deporte_nacional-edit' : 'deporte_nacional-create';
    if(validar_campo_vacio(nombre)){
        warning('Campo vacío', 'El nombre se encuentra vacio, por favor rellene lo para continuar.');
        document.getElementById(nombre).focus();
        return false;
    } else if(validar_campo_vacio(categoria)){
        warning('Campo vacío', 'No se ah seleccionado ninguna categoria, por favor rellene lo para continuar.');
        document.getElementById(categoria).focus();
        return false;
    } else if(validar_campo_vacio(es_olimpico)){
        warning('Campo vacío', 'No se ah seleccionado si el deporte es olímpico, por favor rellene lo para continuar.');
        document.getElementById(es_olimpico).focus();
        return false;
    } else if(validar_campo_vacio(popularidad)){
        warning('Campo vacío', 'No se ah seleccionado la popularidad del deporte, por favor rellene lo para continuar.');
        document.getElementById(popularidad).focus();
        return false;
    } else if(validar_campo_vacio(deporte_nacional)){
        warning('Campo vacío', 'No se ah seleccionado si el deporte es nacional, por favor rellene lo para continuar.');
        document.getElementById(deporte_nacional).focus();
        return false;
    }
    return true;
}

document.getElementById('form-store').addEventListener('submit', e => {
    e.preventDefault();
    let tipo = e.target.querySelector('input[id="id-deporte-edit"]') ? 1 : 2;
    if(valdiarCampos(tipo)){
        e.target.submit();
    }
});

document.getElementById('form-update').addEventListener('submit', e => {
    e.preventDefault();
    let tipo = e.target.querySelector('input[id="id-deporte-edit"]') ? 1 : 2;
    if(valdiarCampos(tipo)){
        e.target.submit();
    }
});

function cargar_modal(button, llamado){
    let data = JSON.parse(button.getAttribute('data'));
    let id_deporte = button.getAttribute('id-deporte');
    let nombre = llamado == 1 ? document.getElementById('nombre-edit') : document.getElementById('nombre-show');
    let categoria = llamado == 1 ? document.getElementById('categoria-edit') : document.getElementById('categoria-show');
    let es_olimpico = llamado == 1 ? document.getElementById('es_olimpico-edit') : document.getElementById('es_olimpico-show');
    let popularidad = llamado == 1 ? document.getElementById('popularidad-edit') : document.getElementById('popularidad-show');
    let deporte_nacional = llamado == 1 ? document.getElementById('deporte_nacional-edit') : document.getElementById('deporte_nacional-show');
    let id_deporte_edit = llamado == 1 ? document.getElementById('id-deporte-edit') : {value: ''};
    id_deporte_edit.value = id_deporte;
    nombre.value = data.nombre;
    categoria.value = data.categoria;
    es_olimpico.value = data.es_olimpico;
    popularidad.value = data.popularidad;
    deporte_nacional.value = data.deporte_nacional;
}

function limpiar_modal(){
    let id_deporte = document.getElementById('id-deporte-edit') ?? {value: ''};
    let nombre_edit = document.getElementById('nombre-edit') ?? {value: ''};
    let categoria_edit = document.getElementById('categoria-edit') ?? {value: ''};
    let es_olimpico_edit = document.getElementById('es_olimpico-edit') ?? {value: ''};
    let popularidad_edit = document.getElementById('popularidad-edit') ?? {value: ''};
    let deporte_nacional_edit = document.getElementById('deporte_nacional-edit') ?? {value: ''};
    let nombre_create = document.getElementById('nombre-create') ?? {value: ''};
    let categoria_create = document.getElementById('categoria-create') ?? {value: ''};
    let es_olimpico_create = document.getElementById('es_olimpico-create') ?? {value: ''};
    let popularidad_create = document.getElementById('popularidad-create') ?? {value: ''};
    let deporte_nacional_create = document.getElementById('deporte_nacional-create') ?? {value: ''};
    let nombre_show = document.getElementById('nombre-show') ?? {value: ''};
    let categoria_show = document.getElementById('categoria-show') ?? {value: ''};
    let es_olimpico_show = document.getElementById('es_olimpico-show') ?? {value: ''};
    let popularidad_show = document.getElementById('popularidad-show') ?? {value: ''};
    let deporte_nacional_show = document.getElementById('deporte_nacional-show') ?? {value: ''};
    id_deporte.value = '';
    nombre_edit.value = '';
    categoria_edit.value = '';
    es_olimpico_edit.value = '';
    popularidad_edit.value = '';
    deporte_nacional_edit.value = '';
    nombre_create.value = '';
    categoria_create.value = '';
    es_olimpico_create.value = '';
    popularidad_create.value = '';
    deporte_nacional_create.value = '';
    nombre_show.value = '';
    categoria_show.value = '';
    es_olimpico_show.value = '';
    popularidad_show.value = '';
    deporte_nacional_show.value = '';
}

document.addEventListener("DOMContentLoaded", () => {
    var data = [];
    var i = 0;  
    dataD.forEach((elemento, index) => {
        acciones = `
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit" data='${elemento.data}' id-deporte="${elemento["id_deporte"]}" onclick="cargar_modal(this, 1);" ${puedeActualizar ? '' : 'disabled'}>
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalShow" data='${elemento.data}' id-deporte="${elemento["id_deporte"]}" onclick="cargar_modal(this, 2);">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminar(${elemento["id_deporte"]});" ${elemento["conteo_de_medicos"] > 0 ? "eliminard" : ""} ${puedeEliminar ? '' : 'disabled'}>
                    <i class="fa-solid fa-x"></i>
                </button>
            </div>`;

        data[i] = {
            contador: i + 1,
            Nombre: elemento["nombre"],
            Categoria: elemento["categoria"],
            Acciones: acciones,
        };
        i++;
    });

    const datos = {
        id_tabla: "#tabla",
        data: data,
        columns: [
            {
                data: "contador",
                title: "#",
                className: "text-center",
            },
            {
                data: "Nombre",
                title: "Nombre",
                className: "text-center td-datatable",
            },
            {
                data: "Categoria",
                title: "Categoria",
                className: "text-center td-datatable",
            },
            {
                data: "Acciones",
                title: "Acciones",
                className: "text-center",
            },
        ],
    };

    cargar_tabla(datos);

    document.getElementById('cerrar-modal-create').addEventListener('click', limpiar_modal);
    document.getElementById('cerrar-modal-edit').addEventListener('click', limpiar_modal);
    document.getElementById('cerrar-modal-show').addEventListener('click', limpiar_modal);
});