function cargar_tabla(datos) {
    try {
        var table = $(datos["id_tabla"]).DataTable({
            data: datos["data"] ,
            columns: datos["columns"],
            autoWidth: false,
            language: {
                search: "Buscar: _INPUT_",
                searchPlaceholder: "Buscar por aquí...",
                lengthMenu: "Mostrar _MENU_ Registros por página",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                zeroRecords: "Sin resultados...",
                emptyTable: "No hay datos disponibles...",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior",
                },
            },
            order: []
        });
        $(table.table().body()).addClass("align-middle");
        // $(table.table().node()).addClass("table-striped");
    } catch (error) {
        console.error(error);
        console.log(datos);
        table = $(datos["id_tabla"]).DataTable({
            columns: datos["columns"],
        });
    }
}
