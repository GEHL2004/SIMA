// Cargar tabla del index

var data = [];
var i = 0;
var estado = "";
dataD.forEach((elemento, index) => {

    if(elemento['estado'] == 1){
        estado = 'Activo';
    } else if(elemento['estado'] == 2){
        estado = 'Desincorporado';
    } else if(elemento['estado'] == 3){
        estado = 'Jubilado';
    } else if(elemento['estado'] == 4){
        estado = 'Fallecido';
    } else if(elemento['estado'] == 5){
        estado = 'Traslado';
    } else if(elemento['estado'] == 6){
        estado = '';
    } else if(elemento['estado'] == 7){
        estado = '';
    }

    acciones = `
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="/SIMA/medicos-edit/${elemento["id_medico"]}"}>
                <button type="button" class="btn btn-warning btn-sm" style="border-top-right-radius: 0%; border-bottom-right-radius: 0%;">
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>
            </a>
            <a href="/SIMA/medicos-show/${elemento["id_medico"]}">
                <button type="button" class="btn btn-info btn-sm" style="border-radius: 0%;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </a>
            <button type="button" class="btn btn-danger btn-sm" onclick="eliminar(${elemento["id_medico"]});">
                <i class="fa-solid fa-x"></i>
            </button>
        </div>`;

    data[i] = {
        contador: i + 1,
        Nombres_Apellidos: elemento["nombres_apellidos"],
        NumColegio: elemento["numero_colegio"],
        Estado: estado,
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
            data: "Nombres_Apellidos",
            title: "Nombres y Apellidos",
            className: "text-center td-datatable",
        },
        {
            data: "NumColegio",
            title: "N° de Colegio",
            className: "text-center td-datatable",
        },
        {
            data: "Estado",
            title: "Estado",
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


// Funcionamiento de Eliminar Médico

function eliminar(id_medico) {
    Swal.fire({
        icon: "question",
        title: "Está seguro de eliminar este Médico?",
        showDenyButton: true,
        confirmButtonText: "Si, eliminar médico",
        confirmButtonColor: "#28A745",
        denyButtonText: "Cancelar",
        reverseButtons: "true",
    }).then((respuesta) => {
        if (respuesta.isConfirmed) {
            window.location.href = "/SIMA/medicos-delete/" + id_medico + "_0";
        }
    });
}

// Actualizar el estado del Médico

async function actualizarUsuario(button) {
    let id_medico = button.getAttribute("id-medico");
    let estado_medico = button.getAttribute("estado");
    const { value: estado } = await Swal.fire({
        title: "Actualización del estado del Médico",
        input: "select",
        confirmButtonText: "Actualizar",
        confirmButtonColor: "#198754",
        cancelButtonText: "Cancelar",
        cancelButtonColor: "#A0A5AB",
        reverseButtons: "true",
        inputOptions: {
            1: "Activo",
            2: "No activo",
        },
        inputPlaceholder: "- Seleccione -",
        inputValue: estado_medico,
        showCancelButton: true,
        inputValidator: (value) => {
            return new Promise((resolve) => {
                if (value != "") {
                    resolve();
                } else {
                    resolve("Debe seleccionar un estado para el Médico.");
                }
            });
        },
    });
    // if (estado) {
    //     window.location.href = "/SIMA/medicos-change-status/" + id_medico + "_" + estado;
    // }
    if (estado) {
        success('ESTADO ACTUALIZADO!!!!!!!!');
    }
}