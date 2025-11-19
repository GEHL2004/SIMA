<?php include_once "./public/views/layouts/header.php"; ?>

<div class="container-lg my-4">

    <div class="row justify-content-center ">
        <div class="col-lg-1"></div>
        <div class="col-lg-1 mb-3 mb-lg-0">
            <a href="/SIMA/usuarios">
                <button type="button" class="btn btn-warning-subtle btn-lock" id="botonregresar">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </a>
        </div>
        <div class="col-lg-9 mb-4">
            <h1 class="text-center text-warning"><i class="fa-solid fa-user-plus me-2"></i> Actualizacion de Usuario <i class="fa-solid fa-user-plus ms-2"></i></h1>
        </div>
        <div class="col-lg-1"></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form action="/SIMA/usuarios-update" method="POST" enctype="multipart/form-data" id="form" novalidate>
                <input type="text" id="id_usuario" name="id_usuario" value="<?php echo $data[0]['id_usuario']; ?>" hidden>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="cedula" class="form-label">Cédula (V/E-12345678) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-id-card"></i></span>
                            <input type="text" class="form-control rounded-end-pill" name="cedula" id="cedula" value='<?php echo $data[0]['cedula']; ?>' placeholder="V-12345678" required>
                            <div class="invalid-feedback">
                                Formato de Cédula inválido (ej: V-12345678).
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-user"></i></span>
                            <input type="text" class="form-control rounded-end-pill" name="nombres" id="nombres" value='<?php echo $data[0]['nombres']; ?>' required>
                            <div class="invalid-feedback">
                                Este campo es obligatorio.
                            </div>
                        </div>

                    </div>

                    <div class="col-12 col-lg-6">
                        <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-user"></i></span>
                            <input type="text" class="form-control rounded-end-pill" name="apellidos" id="apellidos" value='<?php echo $data[0]['apellidos']; ?>' required>
                            <div class="invalid-feedback">
                                Este campo es obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="nombre_user" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-user-tag"></i></span>
                            <input type="text" class="form-control rounded-end-pill" name="nombre_user" id="nombre_user" value='<?php echo $data[0]['nombre_user']; ?>' required>
                            <div class="invalid-feedback">
                                Este campo es obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="password_user" class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" class="form-control rounded-end-pill" name="password_user" id="password_user" value='<?php echo $data[0]['password_user']; ?>' required>
                            <div class="invalid-feedback">
                                La contraseña no cumple con los requisitos de seguridad.
                            </div>
                        </div>
                        <div id="password_feedback" class="form-text text-muted">
                            Debe tener 6-16 caracteres, Mayúscula, minúscula, número y un carácter especial.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="respuesta_secreta" class="form-label">Nivel de Usuario: <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-user-gear"></i></span>
                            <select class="form-select rounded-end-pill" name="nivel" id="nivel" required>
                                <option value="" selected disabled>- Seleccione -</option>
                                <option value="1" <?php echo $data[0]['nivel'] == 1 ? 'selected' : ''; ?>>Super Administrador(a)</option>
                                <option value="2" <?php echo $data[0]['nivel'] == 2 ? 'selected' : ''; ?>>Administrador(a)</option>
                                <option value="3" <?php echo $data[0]['nivel'] == 3 ? 'selected' : ''; ?>>Coordinador(a)</option>
                                <option value="4" <?php echo $data[0]['nivel'] == 4 ? 'selected' : ''; ?>>Secretario(a)</option>
                            </select>
                            <div class="invalid-feedback">
                                Este campo es obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="pregunta_secreta" class="form-label">Pregunta Secreta <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-question-circle"></i></span>
                            <input type="text" class="form-control rounded-end-pill" name="pregunta_secreta" id="pregunta_secreta" value='<?php echo $data[0]['pregunta_secreta']; ?>' required>
                            <div class="invalid-feedback">
                                Este campo es obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="respuesta_secreta" class="form-label">Respuesta Secreta <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-key"></i></span>
                            <input type="text" class="form-control rounded-end-pill" name="respuesta_secreta" id="respuesta_secreta" value='<?php echo $data[0]['respuesta_secreta']; ?>' required>
                            <div class="invalid-feedback">
                                Este campo es obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="col-12 pt-3">
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6 d-flex justify-content-center">
                                <button type="submit" class="btn btn-warning-subtle rounded-pill">
                                    <i class="fa-solid fa-user-edit mx-2"></i>
                                    Actualizar usuario
                                    <i class="fa-solid fa-user-edit mx-2"></i>
                                </button>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once "./public/views/layouts/footer.php"; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form');
        // const fotoFile = document.getElementById('foto_file');
        const vistaPreviaFoto = document.getElementById('vistaPreviaFoto');
        const passwordInput = document.getElementById('password_user');
        const cedulaInput = document.getElementById('cedula');
        const nombresInpuy = document.getElementById('nombres');
        const apellidosInput = document.getElementById('apellidos');
        const nombre_userInput = document.getElementById('nombre_user');
        const nivelSelect = document.getElementById('nivel');
        const pregunta_secretaInput = document.getElementById('pregunta_secreta');
        const respuesta_secretaInput = document.getElementById('respuesta_secreta');

        // =============================================================
        // 1. Manejo de la Vista Previa de la Foto y Validación de Formato
        // =============================================================

        // function validarImagen(input) {
        //     const file = input.files[0];
        //     const formatosPermitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];

        //     if (file) {
        //         // Validación de formato
        //         if (!formatosPermitidos.includes(file.type)) {
        //             input.classList.add('is-invalid');
        //             vistaPreviaFoto.src = "<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] ?>/assets/images/ImageDefault.svg";
        //             return;
        //         }

        //         // Si es válido, mostrar vista previa
        //         input.classList.remove('is-invalid');
        //         input.classList.add('is-valid');
        //         const reader = new FileReader();
        //         reader.onload = function(e) {
        //             vistaPreviaFoto.src = e.target.result;
        //         };
        //         reader.readAsDataURL(file);
        //     } else {
        //         // Si se quita el archivo
        //         vistaPreviaFoto.src = "<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] ?>/assets/images/ImageDefault.svg";
        //         input.classList.remove('is-valid');
        //         input.classList.add('is-invalid'); // Lo marcamos como inválido si está vacío (requerido)
        //     }
        // }

        // fotoFile.addEventListener('change', function() {
        //     validarImagen(this);
        // });

        // fotoFile.addEventListener('blur', function() {
        //     validarImagen(this);
        // });

        // =============================================================
        // 2. Validación Dinámica de Contraseña (onblur)
        // =============================================================
        passwordInput.addEventListener('blur', function() {
            const password = this.value;
            const esInsegura = validar_contraseña(password); // true si falla algún requisito

            if (esInsegura) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        // =============================================================
        // 3. Validación Dinámica de Cédula (onblur)
        // Formato Venezolano: [V|E]-dddddddd
        // =============================================================
        cedulaInput.addEventListener('blur', function() {
            const cedula = this.value.toUpperCase();
            // Regex: Inicia con V o E, guión opcional, 7 a 9 dígitos.
            const regexCedula = /^[VE]-\d{7,9}$/;

            if (regexCedula.test(cedula)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });

        // =============================================================
        // 4. Validación campo vacío de Nombres (onblur)
        // =============================================================
        nombresInpuy.addEventListener('blur', function() {
            const nombre = this.value;
            if (nombre != "") {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });

        // =============================================================
        // 5. Validación campo vacío de Apellidos (onblur)
        // =============================================================
        apellidosInput.addEventListener('blur', function() {
            const apellido = this.value;
            if (apellido != "") {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });

        // =============================================================
        // 6. Validación campo vacío de Nombre de Usuario (onblur)
        // =============================================================
        nombre_userInput.addEventListener('blur', function() {
            const nombre_user = this.value;
            if (nombre_user != "") {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });

        // =============================================================
        // 7. Validación campo vacío de Nivel de Usuario (onblur)
        // =============================================================
        nivelSelect.addEventListener('blur', function() {
            const nivel = this.value;
            if (nivel != "") {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });

        // =============================================================
        // 8. Validación campo vacío de Pregunta Secreta (onblur)
        // =============================================================
        pregunta_secretaInput.addEventListener('blur', function() {
            const pregunta_secreta = this.value;
            if (pregunta_secreta != "") {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });

        // =============================================================
        // 9. Validación campo vacío de Respuesta Secreta (onblur)
        // =============================================================
        respuesta_secretaInput.addEventListener('blur', function() {
            const respuesta_secreta = this.value;
            if (respuesta_secreta != "") {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });


        // =============================================================
        // 10. Validación Dinámica de Correo (onblur)
        // =============================================================
        // correoInput.addEventListener('blur', function() {
        //     const correo = this.value;
        //     // Regex básica de correo
        //     const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        //     if (regexCorreo.test(correo)) {
        //         this.classList.remove('is-invalid');
        //         this.classList.add('is-valid');
        //     } else {
        //         this.classList.add('is-invalid');
        //         this.classList.remove('is-valid');
        //     }
        // });

        // =============================================================
        // 11. Validación General de Formulario (al enviar)
        // =============================================================
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            event.stopPropagation();

            // Ejecutar todas las validaciones necesarias antes de enviar
            let formularioValido = true;

            // 1. Validar campos vacíos y marcar con is-invalid (Bootstrap)
            Array.from(form.elements).forEach(element => {
                if (element.required && element.value.trim() === '') {
                    element.classList.add('is-invalid');
                    element.classList.remove('is-valid');
                    element.focus();
                    formularioValido = false;
                } else if (element.required) {
                    element.classList.remove('is-invalid');
                    element.classList.add('is-valid');
                }
            });

            // 2. Re-ejecutar validación de seguridad (Cédula, Correo, Contraseña, Foto)
            // Simulamos el blur para que se muestre el feedback final
            passwordInput.dispatchEvent(new Event('blur'));
            cedulaInput.dispatchEvent(new Event('blur'));
            // correoInput.dispatchEvent(new Event('blur'));
            // fotoFile.dispatchEvent(new Event('change'));
            // fotoFile.dispatchEvent(new Event('blur'));
            nombresInpuy.dispatchEvent(new Event('blur'));
            apellidosInput.dispatchEvent(new Event('blur'));
            nombre_userInput.dispatchEvent(new Event('blur'));
            nivelSelect.dispatchEvent(new Event('blur'));
            pregunta_secretaInput.dispatchEvent(new Event('blur'));
            respuesta_secretaInput.dispatchEvent(new Event('blur'));


            // Si hay algún campo marcado como is-invalid, el formulario no es válido
            if (form.querySelector('.is-invalid') || !formularioValido) {
                warning('Campos vacíos o incorrectos', 'Por favor, complete y corrija todos los campos marcados.');
            } else {
                // El formulario es válido y listo para ser enviado al backend
                form.submit();
            }
        }, false);
    });
</script>

<?php if (!empty($_SESSION['mensaje'])) {
    echo $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} ?>