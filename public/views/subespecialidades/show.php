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
                    <h1 class="text-center text-primary">Información de la Subespecialidad</h1>
                </div>
                <div class="col-lg-1"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-3">
                        <div class="col-12 col-lg-12">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill border border-dark-subtle"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control rounded-end-pill border border-dark-subtle" name="nombre" id="nombre" value="<?php echo $data[0]['nombre_SE']; ?>" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12">
                            <label for="codigo" class="form-label">Código:</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill border border-dark-subtle"><i class="fa-solid fa-code"></i></span>
                                <input type="text" class="form-control rounded-end-pill border border-dark-subtle" name="codigo" id="codigo" value="<?php echo $data[0]['codigo']; ?>" maxlength="20" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12">
                            <label class="mt-2"><i class="fas fa-stethoscope me-2"></i>Especialidades necesarias para subespecialidad</label>
                            <div id="especialidades-container">

                                <?php $j = 1;
                                foreach ($data as $i => $value) { ?>
                                    <div class="row g-3 align-items-center mt-2">
                                        <div class="col-lg-12">
                                            <label class="form-label">Especialidad <?php echo $j; ?>:</label>
                                            <input type="text" class="form-control rounded-pill" value="<?php echo $value['nombre_E']; ?>" disabled />
                                        </div>
                                    </div>
                                <?php $j++;
                                } ?>
                            </div>
                        </div>
                        <div class="col-12 mb-5">
                            <label for="descripcion" class="form-label">Descripción de la Especialidad:</label>
                            <textarea name="descripcion" class="form-control rounded-4 border border-dark-subtle" id="descripcion" cols="1" rows="8" maxlength="1000" style="resize: none;" disabled><?php echo $data[0]['descripcion']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "./public/views/layouts/footer.php"; ?>