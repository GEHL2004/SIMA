async function verificarUsuario() {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    Swal.fire({
        icon: "question",
        title: "Ingrese su correo electronico",
        input: "email",
        inputAttributes: {
            autocapitalize: "off",
        },
        inputPlaceholder: "Correo electronico",
        showCancelButton: true,
        confirmButtonText: "Verificar",
        confirmButtonColor: "#198754",
        cancelButtonText: "Cancelar",
        cancelButtonColor: "#A0A5AB",
        reverseButtons: "true",
        showLoaderOnConfirm: true,
        inputValidator: (value) => {
            if (!value) {
                return "Debe ingresar un correo electronico para continuar.";
            } else if (!regex.test(value)) {
                return `Correo inválido: ${value}`;
            }
        },
        preConfirm: async (usuario) => {
            try {
                const url = `/SIMA/rucuperarPassword1/${usuario}`;
                const response = await fetch(url);
                if (!response.ok) {
                    return Swal.showValidationMessage(
                        `${JSON.stringify(await response.json())} `
                    );
                }
                return response.json();
            } catch (error) {
                Swal.showValidationMessage(
                    `Error al validar usuario: ${error} `
                );
            }
        },
        allowOutsideClick: () => !Swal.isLoading(),
    }).then((result) => {
        if (result.isConfirmed) {
            let dataDB = JSON.parse(result.value);
            if (dataDB.bool) {
                if (Object.keys(dataDB.data).length > 0) {
                    preguntaSecreta(dataDB);
                } else {
                    error(
                        "Si datos",
                        "No hay datos de este usuario en el sistema SISPRE."
                    );
                }
            } else {
                error(
                    "Usuario no encontrado",
                    "El nombre de usuario ingresado no se encuentra registrado en el sistema SISPRE."
                );
            }
        }
    });
}

async function preguntaSecreta(dataDB) {
    const { value: respuesta } = await Swal.fire({
        icon: "question",
        title: "Responda la siguiente pregunta.",
        text: dataDB.data.pregunta_secreta,
        input: "text",
        showCancelButton: true,
        confirmButtonText: "Validar respuesta",
        confirmButtonColor: "#198754",
        cancelButtonText: "Cancelar",
        cancelButtonColor: "#A0A5AB",
        reverseButtons: "true",
        inputValidator: (value) => {
            if (!value) {
                return "La pregunta debe ser respondida de forma obligatoria para avanzar.";
            }
        },
    });
    if (respuesta) {
        if (dataDB.data.respuesta_secreta === respuesta) {
            setTimeout(() => cambioPassword(dataDB), 2100);
            success("Respuesta correcta.");
        } else {
            error(
                "Respuesta incorrecta.",
                "La respuesta ingresada no coincide con la respuesta secreta registrada en el sistema SISPRE."
            );
        }
    }
}

async function cambioPassword(dataDB) {
    const { value: password } = await Swal.fire({
        icon: "info",
        title: "Ingrese la nueva contraseña.",
        input: "text",
        showCancelButton: true,
        confirmButtonText: "Cambiar contraseña",
        confirmButtonColor: "#198754",
        cancelButtonText: "Cancelar",
        cancelButtonColor: "#A0A5AB",
        reverseButtons: "true",
        inputValidator: (value) => {
            if (validar_contraseña(value)) {
                return "La contraseña no cumple las reglas. debe contener mínimo 6 caracteres y máximo 16 caracteres, debe poseer mínimo una mayúscula, una minúscula, números y caracteres especiales.";
            }
        },
    });
    if (password) {
        const formData = new FormData();
        formData.append("id_usuario", dataDB.data.id_usuario);
        formData.append("password", password);
        fetch("/SIMA/rucuperarPassword2", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.json())
            .then((dataJSON) => {
                let dataD = JSON.parse(dataJSON);
                if (dataD.bool) {
                    success("Contraseña cambiada correctamente.");
                } else {
                    error(
                        "Error al cambiar contraseña.",
                        "Ocurrió un error al intentar cambiar la contraseña."
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }
}
