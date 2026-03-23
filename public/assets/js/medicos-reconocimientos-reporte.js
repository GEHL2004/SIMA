const tabla = document.getElementById("tabla");

document.addEventListener("DOMContentLoaded", (e) => {
    const datos = {
        id_tabla: "#tabla",
        data: data,
        columns: [
            {
                data: "contador",
                title: "#",
                className: "text-center td-datatable",
            },
            {
                data: "nombres_apellidos",
                title: "Nombre del médico",
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
                data: "años_transcurridos_graduado",
                title: "Años de graduado",
                className: "text-center td-datatable",
            }
        ],
    };

    cargar_tabla(datos);
});

function descargarPDF() {
    url = "/SIMA/medicos-reconocimientos-generar-reporte/" + tipo_reconocimiento + '_' + intervalo + '_' + cantidad + '_' + estado;
    window.open(url, "_blank");
}