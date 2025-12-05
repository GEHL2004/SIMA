<?php require_once "./public/views/layouts/header.php"; ?>

<style>
    .especialidad-container {
        opacity: 0;
        display: none;
        transition: opacity 0.3s ease-in-out;
    }

    .especialidad-container.visible {
        opacity: 1;
        display: block;
    }
</style>
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
                        <div class="col-12">
                            <label for="nombre" class="form-label">Nombre: </label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill border border-dark-subtle"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control rounded-end-pill border border-dark-subtle" name="nombre" id="nombre" value="<?php echo $data[0]['nombre_SE']; ?>" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="codigo" class="form-label">Código: </label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill border border-dark-subtle"><i class="fa-solid fa-code"></i></span>
                                <input type="text" class="form-control rounded-end-pill border border-dark-subtle" name="codigo" id="codigo" value="<?php echo $data[0]['codigo']; ?>" maxlength="20" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="RequiereEspecialidad" class="form-label">¿Requiere previa especialización?: </label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill border border-dark-subtle"><i class="fa-solid fa-list"></i></span>
                                <select class="form-select rounded-end-pill border border-dark-subtle" name="RequiereEspecialidad" id="RequiereEspecialidad" disabled>
                                    <option value="null" selected disabled>- Seleccione -</option>
                                    <option value="1" <?php echo $data[0]['requiere_especialidad_base'] == true ? 'selected' : ''; ?>>SI</option>
                                    <option value="0" <?php echo $data[0]['requiere_especialidad_base'] == false ? 'selected' : ''; ?>>NO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12 especialidad-container <?php echo $data[0]['requiere_especialidad_base'] == true ? 'visible' : ''; ?>" id="especialidadContainer">
                            <label for="especialidad" class="form-label">Especialidad requerida: </label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-pill border border-dark-subtle"><i class="fa-solid fa-list"></i></span>
                                <select class="form-select rounded-end-pill border border-dark-subtle" name="especialidad" id="especialidad" disabled>
                                    <option value="" selected disabled>- Seleccione -</option>
                                    <?php foreach ($especialidades as $i => $valor) { ?>
                                        <option value="<?php echo $valor['id_especialidad']; ?>" <?php echo $data[0]['id_especialidad'] == $valor['id_especialidad'] ? 'selected' : ''; ?>><?php echo $valor['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div id="noEspecialidadContainer" class="col-12 col-lg-12 especialidad-container <?php echo $data[0]['requiere_especialidad_base'] == false ? 'visible' : ''; ?>">
                            <div class="row">
                                <div class="col-12 col-lg-6 mt-3 px-2">
                                    <label for="categoria" class="form-label">Categoría:</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start-pill border border-dark-subtle"><i class="fa-solid fa-list"></i></span>
                                        <select class="form-select rounded-end-pill border border-dark-subtle" name="categoria" id="categoria" disabled>
                                            <option value="" selected disabled>- Seleccione -</option>
                                            <?php foreach ($categorias as $i => $valor) { ?>
                                                <option value="<?php echo $valor['id_categoria_especialidad']; ?>" <?php echo $data[0]['id_categoria_especialidad'] == $valor['id_categoria_especialidad'] ? 'selected' : ''; ?>><?php echo $valor['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 mt-3 px-2">
                                    <label for="tipo_practica" class="form-label">Tipo de Práctica:</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start-pill border border-dark-subtle"><i class="fa-solid fa-table-list"></i></span>
                                        <select class="form-select rounded-end-pill border border-dark-subtle" name="tipo_practica" id="tipo_practica" disabled>
                                            <option value="" selected disabled>- Seleccione -</option>
                                            <?php foreach ($tipos_practicas as $i => $valor) { ?>
                                                <option value="<?php echo $valor['id_tipo_practica']; ?>" <?php echo $data[0]['id_tipo_practica'] == $valor['id_tipo_practica'] ? 'selected' : ''; ?>><?php echo $valor['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 mt-3 px-2">
                                    <label for="sistema_corporal" class="form-label">Sistema Corporal:</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start-pill border border-dark-subtle"><i class="fa-solid fa-person"></i></span>
                                        <select class="form-select rounded-end-pill border border-dark-subtle" name="sistema_corporal" id="sistema_corporal" disabled>
                                            <option value="" selected disabled>- Seleccione -</option>
                                            <?php foreach ($sistemas_corporales as $i => $valor) { ?>
                                                <option value="<?php echo $valor['id_sistema_corporal']; ?>" <?php echo $data[0]['id_sistema_corporal'] == $valor['id_sistema_corporal'] ? 'selected' : ''; ?>><?php echo $valor['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-5">
                            <label for="descripcion" class="form-label">Descripción de la Especialidad: </label>
                            <textarea name="descripcion" class="form-control rounded-4 border border-dark-subtle" id="descripcion" cols="1" rows="8" maxlength="1000" style="resize: none;" disabled><?php echo $data[0]['nombre_SE']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "./public/views/layouts/footer.php"; ?>