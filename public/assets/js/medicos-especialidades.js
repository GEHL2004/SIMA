const select = document.getElementById("select-especialidades");
const tabla = document.getElementById("tabla");
let cantMedicos = 0;

document.addEventListener("DOMContentLoaded", (e) => {
    const datos = {
        id_tabla: "#tabla",
        data: [],
        columns: [
            {
                data: "contador",
                title: "#",
                className: "text-center td-datatable",
            },
            {
                data: "cedula",
                title: "Cédula",
                className: "text-center td-datatable",
            },
            {
                data: "numero_colegio",
                title: "N° Colégio",
                className: "text-center td-datatable",
            },
            {
                data: "nombres_allidos",
                title: "Nombres y Apellidos",
                className: "text-center td-datatable",
            },
            {
                data: "telefono",
                title: "Teléfono",
                className: "text-center td-datatable",
            },
            {
                data: "correo",
                title: "Correo",
                className: "text-center td-datatable",
            },
            {
                data: "direccion",
                title: "Dirección",
                className: "text-center td-datatable",
            },
        ],
    };

    cargar_tabla(datos);
});

function filtrado_especialidades() {
    let id_especialidad = select.value;
    console.log(id_especialidad);
    fetch(`/SIMA/medicos-ver-reporte-especialidades/${id_especialidad}`, {
        method: "GET",
        headers: {
            "content-type": "application/json",
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            let dataD = JSON.parse(data);
            cantMedicos = dataD.length;
            console.log(dataD);
            let table = $("#tabla").DataTable();
            table.clear().draw();
            table.rows.add(dataD).draw();
        });
}

function descargarPDF() {
    console.log(cantMedicos);
    if (cantMedicos <= 0) {
        warning(
            "Tabla vacía",
            "Tiene que haber por lo menos un medico como resultado de la busqueda.",
        );
        return;
    }
    let id_especialidad = select.value;
    url = "/SIMA/medicos-generar-reporte-especialidades/" + id_especialidad;
    window.open(url, "_blank");
}
