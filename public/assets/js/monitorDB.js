$(document).ready(function () {
    // Cargar datos iniciales
    cargarDatosIniciales();
});

// Función para mostrar detalles de estructura en filas expandibles
function formatEstructura(estructura) {
    if (!estructura || estructura.length === 0) {
        return '<div class="detail-structure">No hay información de estructura disponible.</div>';
    }

    let html =
        '<div class="detail-structure"><table class="table table-structure table-sm">' +
        '<thead><tr><th class="text-center">Campo</th><th class="text-center">Tipo</th><th class="text-center">Clave</th></tr></thead><tbody class="align-middle">';

    estructura.forEach(function (item) {
        html +=
            "<tr>" +
            '<td class="text-center">' +
            item.Field +
            "</td>" +
            '<td class="text-center">' +
            item.Type +
            "</td>" +
            '<td class="text-center">' +
            (item.Key || "-") +
            "</td>" +
            "</tr>";
    });

    html += "</tbody></table></div>";
    return html;
}

// Función para cargar los datos iniciales
function cargarDatosIniciales() {
    // Debug primero
    // console.log("mockData.tablas:", mockData.tablas);
    // console.log("mockData.servidor:", mockData.servidor);

    // Llenar información de conexión
    $("#servidor").text(mockData.conexion.servidor);
    $("#base_datos").text(mockData.conexion.base_datos);
    $("#driver").text(mockData.conexion.driver);

    // Llenar información del servidor
    $("#version_servidor").text(mockData.servidor.version_servidor);

    const estadoServidor = mockData.servidor.estado_servidor;
    $("#estado_servidor")
        .text(estadoServidor ? "Conectado" : "Desconectado")
        .removeClass("bg-success bg-danger")
        .addClass(estadoServidor ? "bg-success" : "bg-danger");

    $("#EstadoTitulo")
        .removeClass("bg-success bg-danger")
        .addClass(estadoServidor ? "bg-success" : "bg-danger");

    $("#timestamp_servidor").text(mockData.servidor.timestamp_servidor);

    const newIcon = estadoServidor
        ? '<i id="estado-servidor-icon" class="fas fa-check-circle text-success" data-icon="check-circle"></i>'
        : '<i id="estado-servidor-icon" class="fas fa-exclamation-circle text-danger" data-icon="exclamation-circle"></i>';

    $("#estado-servidor-icon").replaceWith(newIcon);

    // Llenar información del estado de la BD
    $("#tamaño_total_mb").text(
        parseFloat(mockData.estado_base_datos.tamaño_total_mb).toFixed(2) +
            " MB"
    );
    $("#total_tablas").text(mockData.estado_base_datos.total_tablas);

    // Configurar tabla de monitoreo
    const tablasArray = Object.entries(mockData.tablas).map(
        ([nombreTabla, datosTabla]) => {
            return {
                nombre_tabla: nombreTabla,
                informacion: datosTabla.informacion,
                estructura: datosTabla.estructura,
                cantidad_filas: datosTabla.total_registros,
                tamaño_datos: datosTabla.informacion?.tamaño || "NaN",
            };
        }
    );

    // console.log("tablasArray:", tablasArray);

    // Configurar tabla de monitoreo
    const tablasData = {
        id_tabla: "#tablasMonitoreo",
        data: tablasArray.map(function (tabla) {
            // console.log("Procesando tabla:", tabla);
            return [
                "",
                tabla.nombre_tabla,
                tabla.cantidad_filas,
                tabla.tamaño_datos,
                JSON.stringify(tabla.estructura),
            ];
        }),
        columns: [
            {
                className: "dt-control",
                orderable: false,
                data: null,
                defaultContent: '<i class="cursor-pointer"></i>',
            },
            {
                title: "Nombre de Tabla",
            },
            {
                title: "Cantidad de Filas",
                className: "text-center",
            },
            {
                title: "Tamaño de Datos",
                className: "text-center",
            },
            {
                data: 4,
                visible: false,
            }, // Columna oculta para estructura
        ],
    };

    // console.log("tablasData:", tablasData);

    cargar_tabla(tablasData);

    // Configurar tabla de procesos
    const procesosData = {
        id_tabla: "#tablaProcesos",
        data: mockData.procesos_activos.map(function (proceso) {
            return [
                proceso.Id,
                proceso.User,
                proceso.Host,
                proceso.db,
                proceso.Command,
                proceso.Time,
                proceso.State,
            ];
        }),
        columns: [
            {
                title: "Id",
                className: "text-center",
            },
            {
                title: "Usuario",
            },
            {
                title: "Host",
                className: "text-center",
            },
            {
                title: "Base de Datos",
                className: "text-center",
            },
            {
                title: "Comando",
                className: "text-center",
            },
            {
                title: "Tiempo",
                className: "text-center",
            },
            {
                title: "Estado",
                className: "text-center",
            },
        ],
    };

    cargar_tabla(procesosData);

    // Configurar tabla de variables
    const variablesData = {
        id_tabla: "#tablaVariables",
        data: mockData.variables_servidor.map(function (variable) {
            return [variable.Variable_name, variable.Value];
        }),
        columns: [
            {
                title: "Nombre de Variable",
                className: "text-center",
            },
            {
                title: "Valor",
                className: "text-center",
            },
        ],
    };

    cargar_tabla(variablesData);
}

// Evento para botón de actualización
$("#btnActualizar").on("click", function () {
    actualizarDatos();
});

// Función para simular actualización de datos
function actualizarDatos() {
    fetch(`/CORPOTUR/obtener-informacion-bd`, {
        method: "PUT",
        headers: {
            "content-type": "application/json",
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            let mockData = JSON.parse(data);
            // console.log(mockData);

            // Llenar información de conexión
            $("#servidor").text(mockData.conexion.servidor);
            $("#base_datos").text(mockData.conexion.base_datos);
            $("#driver").text(mockData.conexion.driver);

            // Llenar información del servidor
            $("#version_servidor").text(mockData.servidor.version_servidor);

            const estadoServidor = mockData.servidor.estado_servidor;
            $("#estado_servidor")
                .text(estadoServidor ? "Conectado" : "Desconectado")
                .removeClass("bg-success bg-danger")
                .addClass(estadoServidor ? "bg-success" : "bg-danger");

            $("#EstadoTitulo")
                .removeClass("bg-success bg-danger")
                .addClass(estadoServidor ? "bg-success" : "bg-danger");

            $("#timestamp_servidor").text(mockData.servidor.timestamp_servidor);

            const newIcon = estadoServidor
                ? '<i id="estado-servidor-icon" class="fas fa-check-circle text-success" data-icon="check-circle"></i>'
                : '<i id="estado-servidor-icon" class="fas fa-exclamation-circle text-danger" data-icon="exclamation-circle"></i>';

            $("#estado-servidor-icon").replaceWith(newIcon);

            // document.getElementById("estado-servidor-icon").setAttribute('data-icon', estadoServidor ? 'check-circle' : 'exclamation-circle');

            // Llenar información del estado de la BD
            $("#tamaño_total_mb").text(
                parseFloat(mockData.estado_base_datos.tamaño_total_mb).toFixed(
                    2
                ) + " MB"
            );
            $("#total_tablas").text(mockData.estado_base_datos.total_tablas);

            // Configurar tabla de monitoreo
            const tablasArray = Object.entries(mockData.tablas).map(
                ([nombreTabla, datosTabla]) => {
                    return {
                        nombre_tabla: nombreTabla,
                        informacion: datosTabla.informacion,
                        estructura: datosTabla.estructura,
                        cantidad_filas: datosTabla.total_registros,
                        tamaño_datos: datosTabla.informacion?.tamaño || "NaN",
                    };
                }
            );

            // Actualizar tabla de monitoreo
            let tablasMonitoreo = $("#tablasMonitoreo").DataTable();
            let dataTablasMonitoreo = tablasArray.map(function (tabla) {
                return [
                    "",
                    tabla.nombre_tabla,
                    tabla.cantidad_filas,
                    tabla.tamaño_datos,
                    JSON.stringify(tabla.estructura),
                ];
            });

            tablasMonitoreo.clear().draw();
            tablasMonitoreo.rows.add(dataTablasMonitoreo).draw();

            // Actualizar tabla de procesos
            let tablaProcesos = $("#tablaProcesos").DataTable();
            let dataTablaProcesos = mockData.procesos_activos.map(function (
                proceso
            ) {
                return [
                    proceso.Id,
                    proceso.User,
                    proceso.Host,
                    proceso.db,
                    proceso.Command,
                    proceso.Time,
                    proceso.State,
                ];
            });

            tablaProcesos.clear().draw();
            tablaProcesos.rows.add(dataTablaProcesos).draw();

            // Actualizar tabla de variables
            let tablaVariables = $("#tablaVariables").DataTable();
            let dataTablaVariables = mockData.variables_servidor.map(function (
                variable
            ) {
                return [variable.Variable_name, variable.Value];
            });

            tablaVariables.clear().draw();
            tablaVariables.rows.add(dataTablaVariables).draw();
        });
}

// Evento para expandir/contraer detalles de estructura
$("#tablasMonitoreo").on("click", "td.dt-control", function () {
    const tr = $(this).closest("tr");
    const table = $("#tablasMonitoreo").DataTable();
    const row = table.row(tr);

    if (row.child.isShown()) {
        // Cerrar esta fila
        row.child.hide();
        tr.removeClass("shown");
        $(this)
            .find("i")
            .removeClass("fa-chevron-up")
            .addClass("fa-chevron-down");
    } else {
        // Abrir esta fila y mostrar estructura
        const rowData = row.data();
        const estructura = JSON.parse(rowData[4]);
        row.child(formatEstructura(estructura)).show();
        tr.addClass("shown");
        $(this)
            .find("i")
            .removeClass("fa-chevron-down")
            .addClass("fa-chevron-up");
    }
});

// Evento para mostrar/ocultar input personalizado
$("#intervaloActualizacion").on("change", function () {
    const value = $(this).val();
    if (value === "custom") {
        $("#customInputContainer").css("display", "block");
    } else {
        $("#customInputContainer").css("display", "none");
        clearInterval(temporizador);
        temporizador = setInterval(actualizarDatos, value * 1000);
        console.log(
            `Actualización automática cada ${value} segundos seleccionada`
        );
    }
});

// Evento para validar segundos personalizados
$("#customSeconds").on("input", function () {
    const value = parseInt($(this).val());
    // Validar que sea un número
    if (isNaN(value)) {
        warning("Valor personalizado invalido", "Ingrese un número por favor");
        $(this).val(""); // Limpiar el input
        return;
    }
    // Validar rango
    if (value < 1) {
        error("ERROR", "El valor mínimo permitido es 1 segundo.");
        $(this).val(1);
        return;
    } else if (value > 43200) {
        error(
            "ERROR",
            "El valor máximo permitido es 43200 segundos (12 horas)."
        );
        $(this).val(43200);
        return;
    }
    // Si pasa todas las validaciones, cambiar el intervalo
    clearInterval(temporizador);
    temporizador = setInterval(actualizarDatos, value * 1000);
    console.log(`Actualización automática cada ${value} segundos configurada`);
});
