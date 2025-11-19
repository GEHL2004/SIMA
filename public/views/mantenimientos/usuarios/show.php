<?php include_once "./public/views/layouts/header.php"; ?>

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <div class="row justify-content-center ">
                <div class="col-lg-1"></div>
                <div class="col-lg-1 mb-3 mb-lg-0">
                    <a href="/SIMA/usuarios">
                        <button type="button" class="btn btn-primary btn-lock">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </a>
                </div>
                <div class="col-lg-9 mb-4">
                    <h1 class="text-center text-primary"><i class="fa-solid fa-circle-info me-2"></i> Información del Usuario <i class="fa-solid fa-circle-info ms-2"></i></h1>
                </div>
                <div class="col-lg-1"></div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="cedula" class="form-label">Cédula</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-id-card"></i></span>
                                <input type="text" class="form-control rounded-end-pill" name="cedula" id="cedula" value="<?php echo $data[0]['cedula']; ?>" disabled>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="nombres" class="form-label">Nombres</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control rounded-end-pill" name="nombres" id="nombres" value="<?php echo $data[0]['nombres']; ?>" disabled>
                            </div>

                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control rounded-end-pill" name="apellidos" id="apellidos" value="<?php echo $data[0]['apellidos']; ?>" disabled>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="nombre_user" class="form-label">Nombre de Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-user-tag"></i></span>
                                <input type="text" class="form-control rounded-end-pill" name="nombre_user" id="nombre_user" value="<?php echo $data[0]['nombre_user']; ?>" disabled>
                            </div>
                        </div>

                        <?php if ($_SESSION['nivel_acceso'] === 1) { ?>
                            <div class="col-12">
                                <label for="password_user" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-lock"></i></span>
                                    <input type="text" class="form-control rounded-end-pill" name="password_user" id="password_user" value="<?php echo $data[0]['password_user']; ?>" disabled>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="col-12">
                            <label for="respuesta_secreta" class="form-label">Nivel de Usuario:</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-user-gear"></i></span>
                                <select class="form-select rounded-end-pill" name="nivel" id="nivel" disabled>
                                    <option value="" disabled>- Seleccione -</option>
                                    <option value="1" <?php echo $data[0]['nivel'] == 1 ? 'selected' : ''; ?>>Super Administrador(a)</option>
                                    <option value="2" <?php echo $data[0]['nivel'] == 2 ? 'selected' : ''; ?>>Administrador(a)</option>
                                    <option value="3" <?php echo $data[0]['nivel'] == 3 ? 'selected' : ''; ?>>Coordinador(a)</option>
                                    <option value="4" <?php echo $data[0]['nivel'] == 4 ? 'selected' : ''; ?>>Secretario(a)</option>
                                </select>
                            </div>
                        </div>

                        <?php if ($_SESSION['nivel_acceso'] === 1) { ?>
                            <div class="col-12">
                                <label for="pregunta_secreta" class="form-label">Pregunta Secreta</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-question-circle"></i></span>
                                    <input type="text" class="form-control rounded-end-pill" name="pregunta_secreta" id="pregunta_secreta" value="<?php echo $data[0]['pregunta_secreta']; ?>" disabled>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="respuesta_secreta" class="form-label">Respuesta Secreta</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-pill"><i class="fa-solid fa-key"></i></span>
                                    <input type="text" class="form-control rounded-end-pill" name="respuesta_secreta" id="respuesta_secreta" value="<?php echo $data[0]['respuesta_secreta']; ?>" disabled>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "./public/views/layouts/footer.php"; ?>