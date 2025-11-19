var div = document.getElementById("contenedor");
const FECHA_ACTUAL = new Date().toISOString().split('T')[0];

function crearCampos() {
    let tipo_filtrado = document.getElementById("tipo_filtrado").value;
    if (tipo_filtrado == 0) {
        filtrado_general();
    } else if (tipo_filtrado == 1) {
        campos_filtrado_usuarios();
    } else if (tipo_filtrado == 2) {
        campos_filtrado_rango_fechas();
    }
}

function filtrado_general() {
    div.innerHTML = "";
    fetch(`/SIMA/auditoria-filtrar-general`, {
        method: "PUT",
        headers: {
            "content-type": "application/json",
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            let dataD = JSON.parse(data);
            console.log(dataD);
            let table = $("#tabla").DataTable();
            table.clear().draw();
            table.rows.add(dataD).draw();
        });
}

function campos_filtrado_usuarios() {
    div.innerHTML = "";
    fetch(`/SIMA/usuarios-getData`, {
        method: "PUT",
        headers: {
            "content-type": "application/json",
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            dataDECODE = JSON.parse(data);
            console.log(data);
            let select = `<div>
                    </div>
                    <div class="col-6">
                        <div class="input-group mt-3">
                            <span class="input-group-text">Seleccione un usuario:</span>
                            <select class="form-select bg-body-secondary" id="filtrado_usuarios" onchange="filtrado_usuarios();">
                                <option value="0" selected>- Todos los usuarios -</option>`;
            for (let i in dataDECODE) {
                console.log(dataDECODE);
                select += `<option value="${dataDECODE[i].id_usuario}">${dataDECODE[i].nombre_user}</option>`;
            }
            select += `</select>
                    </div>
                        </div>
                        <div>
                        </div>`;
            div.innerHTML = select;
        });
}

function filtrado_usuarios() {
    let id_usuario = document.getElementById("filtrado_usuarios").value;
    const tabla = document.getElementById("tabla");
    console.log(id_usuario);
    fetch(`/SIMA/auditoria-filtrar-usuario/${id_usuario}`, {
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
            console.log(dataD);
            let table = $("#tabla").DataTable();
            table.clear().draw();
            table.rows.add(dataD).draw();
        });
}

function campos_filtrado_rango_fechas() {
    div.innerHTML = "";
    div.innerHTML = `<div>
                    </div>
                    <div class="col-9">
                        <div class="input-group mt-3">
                            <span class="input-group-text">Fecha de inicio:</span>
                            <input type="date" id="fecha_inicio" class="form-control bg-body-secondary">
                            <span class="input-group-text">Fecha de fin:</span>
                            <input type="date" id="fecha_final" class="form-control bg-body-secondary">
                            <button type="button" class="btn btn-outline-primary" id="button-addon2" onclick="filtrado_rango_fechas();">Filtrar</button>
                        </div>
                    </div>
                    <div>
                    </div>`;
    let fecha_inicio = document.getElementById("fecha_inicio");
    let fecha_fin = document.getElementById("fecha_final");
    fecha_inicio.setAttribute('max', FECHA_ACTUAL);
    fecha_fin.setAttribute('max', FECHA_ACTUAL);
}

function filtrado_rango_fechas() {
    let fecha_inicio = document.getElementById("fecha_inicio").value;
    let fecha_fin = document.getElementById("fecha_final").value;
    const tabla = document.getElementById("tabla");
    if (fecha_inicio == "") {
        warning("Campo vacío", "No se ah seleccionado una fecha de inicio.");
    } else if (fecha_fin == "") {
        warning("Campo vacío", "No se ah seleccionado una fecha de fin.");
    } else if (fecha_inicio > fecha_fin) {
        warning(
            "Valor inválido",
            "La fecha de inicio no puede ser posterior a la fecha final."
        );
    } else {
        console.log(fecha_inicio + "_" + fecha_fin);
        fetch(
            `/SIMA/auditoria-filtrar-fechas/${
                fecha_inicio + "_" + fecha_fin
            }`,
            {
                method: "GET",
                headers: {
                    "content-type": "application/json",
                },
            }
        )
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                let dataD = JSON.parse(data);
                console.log(dataD);
                let table = $("#tabla").DataTable();
                table.clear().draw();
                table.rows.add(dataD).draw();
            });
    }
}
