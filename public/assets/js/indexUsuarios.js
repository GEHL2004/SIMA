var data = [];
let i = 0;
let nivel = "";
let estado = "";

if (nivel_acceso == 1) {
    dataD.forEach((elemento, index) => {
        if (elemento["nivel"] == 1) {
            nivel = "Super Administrador(a)";
        } else if (elemento["nivel"] == 2) {
            nivel = "Administrador(a)";
        } else if (elemento["nivel"] == 3) {
            nivel = "Coordinador(a)";
        } else if (elemento["nivel"] == 4) {
            nivel = "Secretario(a)";
        }

        if (elemento["estado"] == -2) {
            acciones = `
            <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="/SIMA/usuarios-edit/${
                                elemento["id_usuario"]
                            }"}>
                                <button type="button" class="btn btn-warning btn-sm">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            </a>
                            <a href="/SIMA/usuarios-show/${
                                elemento["id_usuario"]
                            }"}>
                                <button type="button" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </a>
                            <button type="button" class="btn btn-success btn-sm" onclick="pregunta1(1, ${
                                elemento["id_usuario"]
                            })">
                                <i class="fa-solid fa-unlock"></i>
                            </button>
                                <button type="button" class="btn btn-danger btn-sm" ${
                                    elemento["id_usuario"] == id_usuario
                                        ? "disabled"
                                        : ""
                                } onclick="pregunta2(1, ${
                elemento["id_usuario"]
            });">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                        </div>`;
        } else {
            acciones = `<div class="btn-group" role="group" aria-label="Basic example">
                            <a href="/SIMA/usuarios-edit/${
                                elemento["id_usuario"]
                            }"}>
                                <button type="button" class="btn btn-warning btn-sm">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            </a>
                            <a href="/SIMA/usuarios-show/${
                                elemento["id_usuario"]
                            }"}>
                                <button type="button" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </a>
                                <button type="button" class="btn btn-danger btn-sm" ${
                                    elemento["id_usuario"] == id_usuario
                                        ? "disabled"
                                        : ""
                                } onclick="pregunta2(1, ${
                elemento["id_usuario"]
            });">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                        </div>`;
        }

        if (elemento["estado"] == -1) {
            estado = "Bloqueado";
        } else if (elemento["estado"] == 0) {
            estado = "Deshabilitado";
        } else if (elemento["estado"] == 1) {
            estado = "Habilitado";
        } else {
            estado = "Desconocido";
        }

        data[i] = {
            contador: i + 1,
            Nombres_y_Apellidos: elemento["nombres_apellidos"],
            Usuario: elemento["nombre_user"],
            Nivel_de_Acceso: nivel,
            Estado: estado,
            Acciones: acciones,
        };
        nivel = "";
        estado = "";
        i++;
    });
} else {
    dataD.forEach((elemento, index) => {
        if (elemento["nivel"] == 1) {
            nivel = "Super Administrador(a)";
        } else if (elemento["nivel"] == 2) {
            nivel = "Administrador(a)";
        } else if (elemento["nivel"] == 3) {
            nivel = "Coordinador(a)";
        } else if (elemento["nivel"] == 4) {
            nivel = "Secretario(a)";
        }

        if (elemento["estado"] == -2) {
            acciones = `
            <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-warning btn-sm" id-usuario="${
                                elemento["id_usuario"]
                            }" id-nivel="${
                elemento["nivel"]
            }" onclick="actualizarUsuario(this);">
                            <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <a href="/SIMA/usuarios-show/${
                                elemento["id_usuario"]
                            }"}>
                                <button type="button" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </a>
                            <button type="button" class="btn btn-success btn-sm" onclick="pregunta1(1, ${
                                elemento["id_usuario"]
                            })">
                                <i class="fa-solid fa-unlock"></i>
                            </button>
                                <button type="button" class="btn btn-danger btn-sm" ${
                                    elemento["id_usuario"] == id_usuario
                                        ? "disabled"
                                        : ""
                                } onclick="pregunta2(1, ${
                elemento["id_usuario"]
            });">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                        </div>`;
        } else {
            acciones = `<div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-warning btn-sm" id-usuario="${
                                elemento["id_usuario"]
                            }" id-nivel="${
                elemento["nivel"]
            }" onclick="actualizarUsuario(this);">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <a href="/SIMA/usuarios-show/${
                                elemento["id_usuario"]
                            }"}>
                                <button type="button" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </a>
                                <button type="button" class="btn btn-danger btn-sm" ${
                                    elemento["id_usuario"] == id_usuario
                                        ? "disabled"
                                        : ""
                                } onclick="pregunta2(1, ${
                elemento["id_usuario"]
            });">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                        </div>`;
        }

        if (elemento["estado"] == -1) {
            estado = "Bloqueado";
        } else if (elemento["estado"] == 0) {
            estado = "Deshabilitado";
        } else if (elemento["estado"] == 1) {
            estado = "Habilitado";
        } else {
            estado = "Desconocido";
        }

        data[i] = {
            contador: i + 1,
            Nombres_y_Apellidos: elemento["nombres_apellidos"],
            Usuario: elemento["nombre_user"],
            Nivel_de_Acceso: nivel,
            Estado: estado,
            Acciones: acciones,
        };
        nivel = "";
        estado = "";
        i++;
    });
}

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
            data: "Nombres_y_Apellidos",
            title: "Nombres y Apellidos",
            className: "text-center td-datatable",
        },
        {
            data: "Usuario",
            title: "Usuario",
            className: "text-center",
        },
        {
            data: "Nivel_de_Acceso",
            title: "Nivel de Acceso",
            className: "text-center",
        },
        {
            data: "Estado",
            title: "Estado",
            className: "text-center",
        },
        {
            data: "Acciones",
            title: "Acciones",
            className: "text-center",
        },
    ],
};

cargar_tabla(datos);

function pregunta1(estado, id_usuario) {
    Swal.fire({
        title: "Pregunta",
        text: "¿Está seguro de desbloquear este usuario?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Si, estoy seguro!",
        confirmButtonColor: "#28A745",
        cancelButtonText: "No, no estoy seguro.",
        cancelButtonColor: "#d33",
        reverseButtons: "true",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href =
                "/SIMA/update-estado/" + estado + "_" + id_usuario;
        }
    });
}

function pregunta2(tipo, id) {
    if (tipo == 1) {
        Swal.fire({
            icon: "question",
            title: "Está seguro de eliminar este usuario?",
            showDenyButton: true,
            confirmButtonText: "Si",
            confirmButtonColor: "#28A745",
            denyButtonText: "No",
            reverseButtons: "true",
        }).then((respuesta) => {
            if (respuesta.isConfirmed) {
                window.location.href = "/SIMA/usuarios-delete/" + id;
            }
        });
    }
}

async function actualizarUsuario(button) {
    let idUsuario = button.getAttribute("id-usuario");
    let nivelUsuario = button.getAttribute("id-nivel");
    const { value: nivel } = await Swal.fire({
        title: "Actualización de nivel de usuario",
        input: "select",
        confirmButtonText: "Actualizar",
        confirmButtonColor: "#198754",
        cancelButtonText: "Cancelar",
        cancelButtonColor: "#A0A5AB",
        reverseButtons: "true",
        inputOptions: {
            1: "Super Administrador(a)",
            2: "Administrador(a)",
            3: "Coordinador(a)",
            4: "Secretario(a)",
        },
        inputPlaceholder: "- Seleccione -",
        inputValue: nivelUsuario,
        showCancelButton: true,
        inputValidator: (value) => {
            return new Promise((resolve) => {
                if (value != "") {
                    resolve();
                } else {
                    resolve("Debe seleccionar un nivel para actualizar.");
                }
            });
        },
    });
    if (nivel) {
        window.location.href =
            "/SIMA/usuarios-update-nivel/" + nivel + "_" + idUsuario;
    }
}

async function accion_a_tomar(id_solicitud) {
    const { value: motivo } = await Swal.fire({
        input: "textarea",
        inputLabel: "Ingrese el motivó",
        inputPlaceholder: "Motivó por el cual se niega la solicitud...",
        inputAttributes: {
            "aria-label": "Motivó por el cual se niega la solicitud",
        },
        showCancelButton: true,
        confirmButtonText: "Enviar",
        confirmButtonColor: "#28A745",
    });
    if (motivo) {
        window.location.href = `/SIMA/solicitud_registro_usuario_denegada/${
            id_solicitud + "_" + motivo
        }`;
    } else if (motivo == "") {
        warning(
            "Campo Vacío",
            "Debe rellenar el campo de motivó para continuar"
        );
    }
}
