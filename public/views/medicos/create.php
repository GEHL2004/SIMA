<?php require_once "./public/views/layouts/header.php"; ?>

<link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/form-medicos.css">

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
                                    <label for="rif" class="form-label">RIF</label>
                                    <input type="text" class="form-control" id="rif" name="rif" maxlength="12" placeholder="J-12345678-9">
                                    <div class="invalid-feedback" id="rif-feedback">
                                        Formato inválido. Use: J-12345678-9 (letra J, V, E, G, P seguida de guión, 7-9 dígitos, guión y 1 dígito)
                                    </div>
                                    <div class="valid-feedback" id="rif-valid-feedback">
                                        Formato de RIF válido.
                                    </div>
                                    <div class="form-text-help">
                                        Formato: J-12345678-9 (opcional, letras válidas: J, V, E, G, P)
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="nacionalidad" class="form-label">Nacionalidad <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" value="Venezolano" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la nacionalidad.
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="lugar_nacimiento" class="form-label">Lugar de Nacimiento <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el lugar de nacimiento.
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
                                    <label for="correo" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="correo" name="correo">
                                    <div class="invalid-feedback">
                                        Por favor ingrese un correo electrónico válido.
                                    </div>
                                    <div class="form-text-help">
                                        Campo opcional
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
                                    <label for="impre" class="form-label">Número de IMPRE</label>
                                    <input type="text" class="form-control" id="impre" name="impre" maxlength="20" pattern="\d{6,20}">
                                    <div class="invalid-feedback" id="impre-feedback">
                                        El IMPRE debe tener entre 6 y 20 dígitos numéricos.
                                    </div>
                                    <div class="valid-feedback" id="impre-valid-feedback">
                                        Formato de IMPRE válido.
                                    </div>
                                    <div class="form-text-help">
                                        Ingrese entre 6 y 20 dígitos (solo números, campo opcional)
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
                                    <label for="id_grado_academico" class="form-label">Grado Académico <span class="required-asterisk">*</span></label>
                                    <select class="form-select" id="id_grado_academico" name="id_grado_academico">
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <?php foreach ($grados as $clave => $valor) { ?>
                                            <option value="<?php echo $valor['id_grado_academico']; ?>"><?php echo $valor['nombre_grado']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label for="estado" class="form-label">Estado del Médico <span class="required-asterisk">*</span></label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="1">Activo</option>
                                        <option value="2">Desincorporado</option>
                                        <option value="3">Jubilado</option>
                                        <option value="4">Fallecido</option>
                                        <option value="5">Traslado</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione el estado del médico.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="lugar_de_trabajo" class="form-label">Lugar de Trabajo <span class="required-asterisk">*</span></label>
                                    <textarea class="form-control" id="lugar_de_trabajo" name="lugar_de_trabajo" rows="2" required></textarea>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el lugar de trabajo.
                                    </div>
                                </div>

                                <!-- Campo oculto para el creador -->
                                <input type="hidden" id="id_creador" name="id_creador" value="<?php echo $_SESSION['id_usuario'] ?? 0; ?>">
                            </div>

                            <!-- Sección para Cursos -->
                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>Cursos</h5>
                                    <span class="badge bg-secondary" id="contador-cursos">0 cursos</span>
                                </div>

                                <div id="cursos-container">
                                    <!-- Los cursos se agregarán aquí dinámicamente -->
                                </div>

                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-outline-success" id="btn-agregar-curso">
                                        <i class="fas fa-plus me-1"></i> Agregar Curso
                                    </button>
                                </div>
                            </div>

                            <!-- Sección para Diplomados -->
                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fas fa-award me-2"></i>Diplomados</h5>
                                    <span class="badge bg-secondary" id="contador-diplomados">0 diplomados</span>
                                </div>

                                <div id="diplomados-container">
                                    <!-- Los diplomados se agregarán aquí dinámicamente -->
                                </div>

                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-outline-success" id="btn-agregar-diplomado">
                                        <i class="fas fa-plus me-1"></i> Agregar Diplomado
                                    </button>
                                </div>
                            </div>

                            <!-- Sección para Deportes -->
                            <div class="mt-4">
                                <h5 class="mb-3"><i class="fas fa-futbol me-2"></i>Deportes que Practica</h5>
                                <div class="row g-3">
                                    <div class="col-lg-12">
                                        <label for="deportes" class="form-label">Seleccionar Deportes</label>
                                        <select class="form-select" id="deportes" name="deportes[]" multiple size="4">
                                            <?php foreach ($deportes as $deporte) { ?>
                                                <option value="<?php echo $deporte['id_deporte']; ?>">
                                                    <?php echo $deporte['nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <div class="form-text-help">
                                            Mantenga presionada la tecla Ctrl (Windows) o Cmd (Mac) para seleccionar múltiples deportes
                                        </div>
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

                        <!-- Paso 3: Especialidades y Subespecialidades -->
                        <div class="form-section" id="step3">
                            <h3 class="section-title"><i class="fas fa-stethoscope me-2"></i>Especialidades y Subespecialidades</h3>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                El médico puede tener 0, 1 o hasta <?php echo count($especialiades_subespecialdiades); ?> especialidades/subespecialidades. Cada una debe incluir la fecha de obtención y el tipo.
                            </div>

                            <div id="especialidades-container">
                                <!-- Las especialidades/subespecialidades se agregarán aquí dinámicamente -->
                            </div>

                            <div class="">
                                <div class="row text-center">
                                    <div class="col-lg-4 my-lg-0 my-2">
                                        <button type="button" class="btn btn-success" id="btn-agregar-especialidad">
                                            <i class="fas fa-plus me-1"></i> Agregar Especialidad/Subespecialidad
                                        </button>

                                    </div>
                                    <div class="col-lg-4 my-lg-0 my-2">
                                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                                            <i class="fas fa-arrow-left me-1"></i> Anterior
                                        </button>
                                    </div>
                                    <div class="col-lg-4 my-lg-0 my-2">
                                        <button type="button" class="btn btn-primary" onclick="nextStep(4)" id="btn-next-step3">
                                            Siguiente <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>

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
                                                    <input type="file" class="form-control" id="documentos-multiples" name="documentos_multiples[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
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
                                                <p id="resumen-rif" class="mb-1"><strong>RIF:</strong> <span class="text-muted">No proporcionado</span></p>
                                                <p id="resumen-impre" class="mb-1"><strong>IMPRE:</strong> <span class="text-muted">No proporcionado</span></p>
                                                <p id="resumen-correo" class="mb-1"><strong>Correo:</strong> <span class="text-muted">No proporcionado</span></p>
                                                <p id="resumen-municipio" class="mb-1"><strong>Municipio:</strong> <span class="text-muted">No seleccionado</span></p>
                                            </div>

                                            <div class="mb-3">
                                                <h6>Información Profesional:</h6>
                                                <p id="resumen-colegio" class="mb-1"><strong>N° Colegio:</strong> <span class="text-muted">No ingresado</span></p>
                                                <p id="resumen-universidad" class="mb-1"><strong>Universidad:</strong> <span class="text-muted">No ingresada</span></p>
                                                <p id="resumen-especialidades" class="mb-1"><strong>Especialidades:</strong> <span class="text-muted">0</span></p>
                                                <p id="resumen-cursos" class="mb-1"><strong>Cursos:</strong> <span class="text-muted">0</span></p>
                                                <p id="resumen-diplomados" class="mb-1"><strong>Diplomados:</strong> <span class="text-muted">0</span></p>
                                                <p id="resumen-deportes" class="mb-1"><strong>Deportes:</strong> <span class="text-muted">0</span></p>
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
    const iq_navbar_header = document.getElementById("iq-navbar-header");
    iq_navbar_header.style.height = "15px";

    // Convertir al formato que necesita JavaScript
    const todasEspecialidades = JSON.parse(<?php echo $especialiades_subespecialdiadesJ; ?>);

    // Ordenar alfabéticamente para mejor búsqueda
    todasEspecialidades.sort((a, b) => a.nombre.localeCompare(b.nombre));
</script>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/form-medicos.js"></script>