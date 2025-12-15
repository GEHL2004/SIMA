<?php require_once "./public/views/layouts/header.php"; ?>

<style>
    .form-card {
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .step-indicator {
        font-size: 0.9rem;
    }

    .step-indicator .step {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
    }

    .step-indicator .step.active {
        background-color: #0d6efd;
        color: white;
    }

    .step-indicator .step.completed {
        background-color: #198754;
        color: white;
    }

    .section-title {
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 25px;
        color: #2c3e50;
    }

    .specialty-item {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid #0d6efd;
    }

    .phone-input-group {
        display: flex;
        gap: 10px;
    }

    .phone-input-group .telefono-inicio {
        flex: 0.13;
    }

    .phone-input-group .telefono-restante {
        flex: 1;
    }

    .form-section {
        display: none;
    }

    .form-section.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .btn-add-specialty {
        margin-top: 10px;
    }

    .remove-specialty-btn {
        margin-top: 32px;
    }

    .required-asterisk {
        color: #dc3545;
    }

    .form-text-help {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .cedula-valid {
        border-color: #198754 !important;
    }

    .cedula-invalid {
        border-color: #dc3545 !important;
    }
</style>

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-lg-12 col-lg-12">

            <div class="card form-card">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="/SIMA/medicos">
                                <button type="button" class="btn btn-sm btn-light btn-lock" id="botonregresar">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </button>
                            </a>
                        </div>
                        <div>
                            <h2 class="h4 mb-0 text-white"> <i class="fas fa-user-md me-2"></i> Registro de Médico</h2>
                        </div>
                        <div class="step-indicator">
                            <span class="step active" id="step1-indicator">1</span>
                            <span class="step" id="step2-indicator">2</span>
                            <span class="step" id="step3-indicator">3</span>
                            <span class="step" id="step4-indicator">4</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Formulario Principal -->
                    <form action="/SIMA/medicos-store" method="POST" id="medicoForm" enctype="multipart/form-data">

                        <!-- Paso 1: Información Personal -->
                        <div class="form-section active" id="step1">
                            <h3 class="section-title"><i class="fas fa-user me-2"></i>Información Personal</h3>

                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label for="nombres" class="form-label">Nombres <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese los nombres.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="apellidos" class="form-label">Apellidos <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese los apellidos.
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="cedula" class="form-label">Cédula <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="cedula" name="cedula" required minlength="7" maxlength="9" pattern="\d{7,9}">
                                    <div class="invalid-feedback" id="cedula-feedback">
                                        La cédula debe tener entre 7 y 9 dígitos numéricos.
                                    </div>
                                    <div class="valid-feedback" id="cedula-valid-feedback">
                                        Formato de cédula válido.
                                    </div>
                                    <div class="form-text-help">
                                        Ingrese entre 7 y 9 dígitos (solo números)
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="required-asterisk">*</span></label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                                    <div class="invalid-feedback">
                                        Por favor seleccione la fecha de nacimiento.
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="tipo_sangre" class="form-label">Tipo de Sangre <span class="required-asterisk">*</span></label>
                                    <select class="form-select" id="tipo_sangre" name="tipo_sangre" required>
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione el tipo de sangre.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="correo" class="form-label">Correo Electrónico <span class="required-asterisk">*</span></label>
                                    <input type="email" class="form-control" id="correo" name="correo" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese un correo electrónico válido.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label">Teléfono <span class="required-asterisk">*</span></label>
                                    <div class="phone-input-group">
                                        <input type="text" class="form-control telefono-inicio" id="telefono_inicio" name="telefono_inicio" placeholder="0412" maxlength="4" required>
                                        <input type="text" class="form-control telefono-restante" id="telefono_restante" name="telefono_restante" placeholder="1234567" maxlength="7" required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Por favor complete el número de teléfono.
                                    </div>
                                    <div class="form-text-help">
                                        Ejemplo: 0412 1234567
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="id_municipio" class="form-label">Municipio <span class="required-asterisk">*</span></label>
                                    <select class="form-select" id="id_municipio" name="id_municipio" required onchange="traerParroquias(this);">
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <?php foreach ($municipios as $clave => $valor) { ?>
                                            <option value="<?php echo $valor['id_municipio']; ?>"><?php echo $valor['nombre_municipio']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione el municipio.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="id_parroquia" class="form-label">Parroquia <span class="required-asterisk">*</span></label>
                                    <select class="form-select" id="id_parroquia" name="id_parroquia" required disabled>
                                        <option value="">Primero seleccione un municipio</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione la parroquia.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="direccion" class="form-label">Dirección <span class="required-asterisk">*</span></label>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="3" required></textarea>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la dirección.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="nombre_foto" class="form-label">Foto del Médico</label>
                                    <input type="file" class="form-control" id="nombre_foto" name="nombre_foto" accept="image/*">
                                    <div class="form-text-help">
                                        Formatos aceptados: JPG, PNG, GIF (Max 2MB)
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <div></div> <!-- Espacio vacío para alinear a la derecha -->
                                <button type="button" class="btn btn-primary" onclick="nextStep(2)" id="btn-next-step1">
                                    Siguiente <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Paso 2: Información Profesional -->
                        <div class="form-section" id="step2">
                            <h3 class="section-title"><i class="fas fa-graduation-cap me-2"></i>Información Profesional</h3>

                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label for="numero_colegio" class="form-label">Número de Colegio <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="numero_colegio" name="numero_colegio" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el número de colegio.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="matricula_ministerio" class="form-label">Matrícula del Ministerio <span class="required-asterisk">*</span></label>
                                    <input type="number" class="form-control" id="matricula_ministerio" name="matricula_ministerio" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la matrícula del ministerio.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="universidad_graduado" class="form-label">Universidad de Graduación <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="universidad_graduado" name="universidad_graduado" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la universidad.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="fecha_egreso_universidad" class="form-label">Fecha de Egreso <span class="required-asterisk">*</span></label>
                                    <input type="date" class="form-control" id="fecha_egreso_universidad" name="fecha_egreso_universidad" required>
                                    <div class="invalid-feedback">
                                        Por favor seleccione la fecha de egreso.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="fecha_incripcion" class="form-label">Fecha de Inscripción <span class="required-asterisk">*</span></label>
                                    <input type="date" class="form-control" id="fecha_incripcion" name="fecha_incripcion" required>
                                    <div class="invalid-feedback">
                                        Por favor seleccione la fecha de inscripción.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="id_grado_academico" class="form-label">Grado Académico</label>
                                    <select class="form-select" id="id_grado_academico" name="id_grado_academico">
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <?php foreach ($grados as $clave => $valor) { ?>
                                            <option value="<?php echo $valor['id_grado_academico']; ?>"><?php echo $valor['nombre_grado']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="lugar_de_trabajo" class="form-label">Lugar de Trabajo <span class="required-asterisk">*</span></label>
                                    <textarea class="form-control" id="lugar_de_trabajo" name="lugar_de_trabajo" rows="2" required></textarea>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el lugar de trabajo.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="estado" name="estado" value="1" checked>
                                        <label class="form-check-label" for="estado">
                                            Médico Activo
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                    <i class="fas fa-arrow-left me-1"></i> Anterior
                                </button>
                                <button type="button" class="btn btn-primary" onclick="nextStep(3)" id="btn-next-step2">
                                    Siguiente <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Paso 3: Especialidades -->
                        <div class="form-section" id="step3">
                            <h3 class="section-title"><i class="fas fa-stethoscope me-2"></i>Especialidades</h3>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                El médico puede tener 0, 1 o hasta 10 especialidades. Cada especialidad debe incluir la fecha de obtención.
                            </div>

                            <div id="especialidades-container">
                                <!-- Las especialidades se agregarán aquí dinámicamente -->
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-success" id="btn-agregar-especialidad">
                                    <i class="fas fa-plus me-1"></i> Agregar Especialidad
                                </button>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="nextStep(4)" id="btn-next-step3">
                                        Siguiente <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Paso 4: Documentos y Resumen -->
                        <div class="form-section" id="step4">
                            <h3 class="section-title"><i class="fas fa-file-alt me-2"></i>Documentos y Resumen</h3>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light py-2">
                                            <h5 class="mb-0">Documentos del Médico</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="documentos-container">
                                                <!-- Los documentos se agregarán aquí dinámicamente -->
                                            </div>

                                            <div class="mt-3">
                                                <div class="mb-3">
                                                    <button type="button" class="btn btn-outline-primary" id="btn-agregar-documento-unico">
                                                        <i class="fas fa-plus me-1"></i> Agregar Documento Individual
                                                    </button>
                                                </div>

                                                <div>
                                                    <label class="form-label">O agregar múltiples documentos:</label>
                                                    <input type="file" class="form-control" id="documentos-multiples" name="documentos_multiples[]" multiple>
                                                    <div class="form-text-help">
                                                        Seleccione múltiples archivos manteniendo presionada la tecla Ctrl (Windows) o Cmd (Mac)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light py-2">
                                            <h5 class="mb-0">Resumen del Registro</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <h6>Información Personal:</h6>
                                                <p id="resumen-nombre" class="mb-1"><strong>Nombre:</strong> <span class="text-muted">No ingresado</span></p>
                                                <p id="resumen-cedula" class="mb-1"><strong>Cédula:</strong> <span class="text-muted">No ingresada</span></p>
                                                <p id="resumen-correo" class="mb-1"><strong>Correo:</strong> <span class="text-muted">No ingresado</span></p>
                                                <p id="resumen-municipio" class="mb-1"><strong>Municipio:</strong> <span class="text-muted">No seleccionado</span></p>
                                            </div>

                                            <div class="mb-3">
                                                <h6>Información Profesional:</h6>
                                                <p id="resumen-colegio" class="mb-1"><strong>N° Colegio:</strong> <span class="text-muted">No ingresado</span></p>
                                                <p id="resumen-universidad" class="mb-1"><strong>Universidad:</strong> <span class="text-muted">No ingresada</span></p>
                                                <p id="resumen-especialidades" class="mb-1"><strong>Especialidades:</strong> <span class="text-muted">0</span></p>
                                            </div>

                                            <div class="mb-3">
                                                <h6>Documentos:</h6>
                                                <p id="resumen-documentos-individuales" class="mb-1"><strong>Documentos individuales:</strong> <span class="text-muted">0</span></p>
                                                <p id="resumen-documentos-multiples" class="mb-1"><strong>Archivos múltiples:</strong> <span class="text-muted">0</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep(4)">
                                    <i class="fas fa-arrow-left me-1"></i> Anterior
                                </button>
                                <div>
                                    <button type="button" class="btn btn-outline-danger me-2" onclick="resetForm()">
                                        <i class="fas fa-redo me-1"></i> Limpiar Todo
                                    </button>
                                    <button type="submit" class="btn btn-success" id="btn-submit-form">
                                        <i class="fas fa-save me-1"></i> Registrar Médico
                                    </button>
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
    var especialidadesD = JSON.parse(<?php echo $especialidadesJ ?>);
    // Carga de la tabla index
    const especialidades = [];
    especialidadesD.forEach((elemento, index) => {
        especialidades.push({id: elemento.id_especialidad, nombre: elemento.nombre});
    });
</script>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/medicos-create.js"></script>