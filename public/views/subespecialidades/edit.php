<?php require_once "./public/views/layouts/header.php"; ?>

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <div class="row justify-content-center">
                <div class="col-lg-1"></div>
                <div class="col-lg-1 mb-3 mb-lg-0">
                    <a href="/SIMA/subespecialidades">
                        <button type="button" class="btn btn-sm btn-primary btn-lock" id="botonregresar">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </a>
                </div>
                <div class="col-lg-9 mb-4">
                    <h1 class="text-center text-primary">Actualización de la Subespecialidad</h1>
                </div>
                <div class="col-lg-1"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form action="/SIMA/subespecialidades-update" method="POST" id="form" novalidate>
                        <input type="text" name="id_subespecialidad" value="<?php echo $data[0]['id_subespecialidad']; ?>" hidden>
                        <div class="row g-3">
                            <div class="col-12 col-lg-12">
                                <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-pill border border-dark-subtle"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" class="form-control rounded-end-pill border border-dark-subtle" name="nombre" id="nombre" value="<?php echo $data[0]['nombre_SE']; ?>" required>
                                </div>
                            </div>
                            <div class="col-12 col-lg-12">
                                <label for="codigo" class="form-label">Código: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-pill border border-dark-subtle"><i class="fa-solid fa-code"></i></span>
                                    <input type="text" class="form-control rounded-end-pill border border-dark-subtle" name="codigo" id="codigo" value="<?php echo $data[0]['codigo']; ?>" maxlength="20" required>
                                </div>
                                <div style="text-align: right; font-size: 0.85em; color: #6c757d; margin-top: 5px;">
                                    <span id="contador2">0</span>/20 caracteres
                                </div>
                            </div>

                            <div class="col-12 col-lg-12">
                                <label><i class="fas fa-stethoscope me-2"></i>Selección de especialidades</label>
                                <div class="alert alert-info mt-2">
                                    <i class="fas fa-info-circle me-2"></i>
                                    La subespecialidad debe depender minimo 1 especialidad o hasta <?php echo count($especialidades); ?> especialidades.
                                </div>
                                <div id="especialidades-container">

                                    <?php foreach ($dataERPSEC as $i => $value1) {
                                        $contador++;
                                    ?>

                                        <div class="specialty-item" id="especialidad-<?php echo $contador; ?>">
                                            <div class="row g-3 align-items-center mt-2">
                                                <div class="col-lg-12">
                                                    <label class="form-label">Especialidad <span class="required-asterisk text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <select class="form-select especialidad-select rounded-start-pill" name="especialidades[]" required onchange="actualizarOpcionesEspecialidades()">
                                                            <option value="" selected disabled>- Seleccione -</option>
                                                            <?php foreach ($especialidades as $j => $value2) { ?>
                                                                <option value="<?php echo $value2['id_especialidad']; ?>" <?php echo $value2['id_especialidad'] == $value1['id_especialidad'] ? 'selected' : ''; ?>><?php echo $value2['nombre']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <button type="button" class="btn btn-danger rounded-end-pill" onclick="eliminarEspecialidad(<?php echo $contador; ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-success rounded-pill" id="btn-agregar-especialidad">
                                        <i class="fas fa-plus me-1"></i> Agregar Especialidad
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="descripcion" class="form-label">Descripción de la Especialidad: <span class="text-danger">*</span></label>
                                <textarea name="descripcion" class="form-control rounded-4 border border-dark-subtle" id="descripcion" cols="1" rows="8" maxlength="1000" style="resize: none;" required><?php echo $data[0]['descripcion']; ?></textarea>
                                <div style="text-align: right; font-size: 0.85em; color: #6c757d; margin-top: 5px;">
                                    <span id="contador1">0</span>/1000 caracteres
                                </div>
                            </div>
                            <div class="col-12 pt-3 mb-5">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-warning rounded-pill">
                                            <i class="fa-regular fa-pen-to-square mx-2"></i>
                                            Actualizar Subespecialidad
                                            <i class="fa-regular fa-pen-to-square mx-2"></i>
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
    </div>
</div>

<?php require_once "./public/views/layouts/footer.php"; ?>

<script>
    let especialidadCount = <?php echo $contador; ?>;
    var especialidadesD = JSON.parse(<?php echo $especialidadesJ ?>);
    // Carga de la tabla index
    const especialidades = [];
    especialidadesD.forEach((elemento, index) => {
        especialidades.push({
            id: elemento.id_especialidad,
            nombre: elemento.nombre
        });
    });

    document.addEventListener('DOMContentLoaded', (evnt) => {
        contador1.textContent = descripcion.value.length;
        contador2.textContent = codigo.value.length;
    })
</script>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/subespecialidades.js"></script>

<script>
    actualizarOpcionesEspecialidades();
</script>