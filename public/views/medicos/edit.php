<?php require_once "./public/views/layouts/header.php"; ?>

<link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/form-medicos.css">

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-lg-12 col-lg-12">

            <div class="card form-card">
                <div class="card-header bg-warning text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="/SIMA/medicos">
                                <button type="button" class="btn btn-sm btn-light btn-lock" id="botonregresar">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </button>
                            </a>
                        </div>
                        <div>
                            <h2 class="h4 mb-0 text-white"> <i class="fas fa-user-edit me-2"></i> Actualizar Médico</h2>
                        </div>
                        <div class="step-indicator">
                            <span class="step active" id="step1-indicator">1</span>
                            <span class="step" id="step2-indicator">2</span>
                            <span class="step" id="step3-indicator">3</span>
                            <span class="step" id="step4-indicator">4</span>
                            <span class="step" id="step5-indicator">5</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Formulario Principal -->
                    <form action="/SIMA/medicos-update" method="POST" id="medicoForm" enctype="multipart/form-data">
                        <input type="hidden" name="id_medico" value="<?php echo $medico['id_medico']; ?>">

                        <!-- Paso 1: Información Personal -->
                        <div class="form-section active" id="step1">
                            <h3 class="section-title"><i class="fas fa-user me-2"></i>Información Personal</h3>

                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label for="nombres" class="form-label">Nombres <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo htmlspecialchars($medico['nombres'] ?? ''); ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese los nombres.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="apellidos" class="form-label">Apellidos <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($medico['apellidos'] ?? ''); ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese los apellidos.
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="cedula" class="form-label">Cédula <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="cedula" name="cedula" value="<?php echo htmlspecialchars($medico['cedula'] ?? ''); ?>" required minlength="7" maxlength="9" pattern="\d{7,9}">
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

                                <!-- AÑADIR: Campo RIF -->
                                <div class="col-lg-4">
                                    <label for="rif" class="form-label">RIF</label>
                                    <input type="text" class="form-control" id="rif" name="rif" maxlength="12" placeholder="J-12345678-9" value="<?php echo htmlspecialchars($medico['rif'] ?? ''); ?>">
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

                                <!-- AÑADIR: Campo Nacionalidad -->
                                <div class="col-lg-4">
                                    <label for="nacionalidad" class="form-label">Nacionalidad <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" value="<?php echo htmlspecialchars($medico['nacionalidad'] ?? 'Venezolano'); ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la nacionalidad.
                                    </div>
                                </div>

                                <!-- AÑADIR: Campo Lugar de Nacimiento -->
                                <div class="col-lg-6">
                                    <label for="lugar_nacimiento" class="form-label">Lugar de Nacimiento <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento" value="<?php echo htmlspecialchars($medico['lugar_nacimiento'] ?? ''); ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el lugar de nacimiento.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="required-asterisk">*</span></label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $medico['fecha_nacimiento'] ?? ''; ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor seleccione la fecha de nacimiento.
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="tipo_sangre" class="form-label">Tipo de Sangre <span class="required-asterisk">*</span></label>
                                    <select class="form-select" id="tipo_sangre" name="tipo_sangre" required>
                                        <option value="" disabled>- Seleccione -</option>
                                        <?php
                                        $tiposSangre = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                        foreach ($tiposSangre as $tipo) {
                                            $selected = ($medico['tipo_sangre'] ?? '') == $tipo ? 'selected' : '';
                                            echo "<option value='$tipo' $selected>$tipo</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione el tipo de sangre.
                                    </div>
                                </div>

                                <!-- MODIFICAR: Correo no es obligatorio en el registro -->
                                <div class="col-lg-4">
                                    <label for="correo" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($medico['correo'] ?? ''); ?>">
                                    <div class="invalid-feedback">
                                        Por favor ingrese un correo electrónico válido.
                                    </div>
                                    <div class="form-text-help">
                                        Campo opcional
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label class="form-label">Teléfono <span class="required-asterisk">*</span></label>
                                    <div class="phone-input-group">
                                        <?php
                                        $telefono = $medico['telefono'] ?? '';
                                        $telefono_inicio = substr($telefono, 0, 4);
                                        $telefono_restante = substr($telefono, 4);
                                        ?>
                                        <input type="text" class="form-control telefono-inicio" id="telefono_inicio" name="telefono_inicio" placeholder="0412" maxlength="4" value="<?php echo $telefono_inicio; ?>" required>
                                        <input type="text" class="form-control telefono-restante" id="telefono_restante" name="telefono_restante" placeholder="1234567" maxlength="7" value="<?php echo $telefono_restante; ?>" required>
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
                                        <option value="" disabled>- Seleccione -</option>
                                        <?php foreach ($municipios as $clave => $valor) {
                                            $selected = ($medico['id_municipio'] ?? '') == $valor['id_municipio'] ? 'selected' : '';
                                            echo "<option value='" . $valor['id_municipio'] . "' $selected>" . $valor['nombre_municipio'] . "</option>";
                                        } ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione el municipio.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="id_parroquia" class="form-label">Parroquia <span class="required-asterisk">*</span></label>
                                    <select class="form-select" id="id_parroquia" name="id_parroquia" required <?php echo empty($medico['id_parroquia']) ? 'disabled' : ''; ?>>
                                        <?php if (empty($medico['id_parroquia'])): ?>
                                            <option value="">Primero seleccione un municipio</option>
                                        <?php else: ?>
                                            <?php
                                            foreach ($parroquias as $parroquia) {
                                                $selected = ($medico['id_parroquia'] ?? '') == $parroquia['id_parroquia'] ? 'selected' : '';
                                                echo "<option value='" . $parroquia['id_parroquia'] . "' $selected>" . $parroquia['nombre_parroquia'] . "</option>";
                                            }
                                            ?>
                                        <?php endif; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione la parroquia.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="direccion" class="form-label">Dirección <span class="required-asterisk">*</span></label>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="3" required><?php echo htmlspecialchars($medico['direccion'] ?? ''); ?></textarea>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la dirección.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Foto del Médico</label>

                                    <?php if (!empty($medico['nombre_foto'])): ?>
                                        <div class="mb-3" id="foto-actual-container">
                                            <p class="mb-2"><strong>Foto actual:</strong></p>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <img src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] . '/assets/images/medicos/' . $medico['nombre_foto']; ?>"
                                                        alt="Foto actual"
                                                        class="img-thumbnail"
                                                        style="max-width: 150px; max-height: 150px;">
                                                </div>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="accion_foto" id="conservar_foto" value="conservar" checked>
                                                        <label class="form-check-label" for="conservar_foto">
                                                            Conservar foto actual
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="accion_foto" id="cambiar_foto" value="cambiar">
                                                        <label class="form-check-label" for="cambiar_foto">
                                                            Cambiar foto
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="accion_foto" id="eliminar_foto" value="eliminar">
                                                        <label class="form-check-label" for="eliminar_foto">
                                                            Eliminar foto
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="nueva-foto-container" style="display: none;">
                                            <input type="file" class="form-control" id="nombre_foto" name="nombre_foto" accept="image/*">
                                            <input type="hidden" name="foto_actual" value="<?php echo $medico['nombre_foto']; ?>">
                                            <div class="form-text-help">
                                                Formatos aceptados: JPG, PNG, GIF (Max 2MB)
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <input type="file" class="form-control" id="nombre_foto" name="nombre_foto" accept="image/*">
                                        <div class="form-text-help">
                                            Formatos aceptados: JPG, PNG, GIF (Max 2MB)
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <div></div>
                                <button type="button" class="btn btn-primary" onclick="nextStep(2)" id="btn-next-step1">
                                    Siguiente <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Paso 2: Información Profesional y Estado -->
                        <div class="form-section" id="step2">
                            <h3 class="section-title"><i class="fas fa-graduation-cap me-2"></i>Información Profesional</h3>

                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label for="numero_colegio" class="form-label">Número de Colegio <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="numero_colegio" name="numero_colegio" value="<?php echo htmlspecialchars($medico['numero_colegio'] ?? ''); ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el número de colegio.
                                    </div>
                                </div>

                                <!-- AÑADIR: Campo IMPRE -->
                                <div class="col-lg-6">
                                    <label for="impre" class="form-label">Número de IMPRE</label>
                                    <input type="text" class="form-control" id="impre" name="impre" maxlength="20" pattern="\d{6,20}" value="<?php echo htmlspecialchars($medico['impre'] ?? ''); ?>">
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
                                    <input type="number" class="form-control" id="matricula_ministerio" name="matricula_ministerio" value="<?php echo htmlspecialchars($medico['matricula_ministerio'] ?? ''); ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la matrícula del ministerio.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="universidad_graduado" class="form-label">Universidad de Graduación <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="universidad_graduado" name="universidad_graduado" value="<?php echo htmlspecialchars($medico['universidad_graduado'] ?? ''); ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la universidad.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="fecha_egreso_universidad" class="form-label">Fecha de Egreso <span class="required-asterisk">*</span></label>
                                    <input type="date" class="form-control" id="fecha_egreso_universidad" name="fecha_egreso_universidad" value="<?php echo $medico['fecha_egreso_universidad'] ?? ''; ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor seleccione la fecha de egreso.
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="fecha_incripcion" class="form-label">Fecha de Inscripción <span class="required-asterisk">*</span></label>
                                    <input type="date" class="form-control" id="fecha_incripcion" name="fecha_incripcion" value="<?php echo $medico['fecha_incripcion'] ?? ''; ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor seleccione la fecha de inscripción.
                                    </div>
                                </div>

                                <!-- MODIFICAR: Grado Académico ahora tiene nombre diferente -->
                                <div class="col-lg-6">
                                    <label for="id_grado_academico" class="form-label">Grado Académico <span class="required-asterisk">*</span></label>
                                    <select class="form-select" id="id_grado_academico" name="id_grado_academico" required>
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <?php foreach ($grados as $clave => $valor) {
                                            $selected = ($medico['id_grado_academico'] ?? '') == $valor['id_grado_academico'] ? 'selected' : '';
                                            echo "<option value='" . $valor['id_grado_academico'] . "' $selected>" . $valor['nombre_grado'] . "</option>";
                                        } ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione el grado académico.
                                    </div>
                                </div>

                                <!-- MODIFICAR: Estado como select como en el registro -->
                                <div class="col-lg-6">
                                    <label for="estado" class="form-label">Estado del Médico <span class="required-asterisk">*</span></label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <?php
                                        $estados = [
                                            1 => 'Activo',
                                            2 => 'Desincorporado',
                                            3 => 'Jubilado',
                                            4 => 'Fallecido',
                                            5 => 'Traslado'
                                        ];
                                        foreach ($estados as $value => $text) {
                                            $selected = ($medico['estado'] ?? '') == $value ? 'selected' : '';
                                            echo "<option value='$value' $selected>$text</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione el estado del médico.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="lugar_de_trabajo" class="form-label">Lugar de Trabajo <span class="required-asterisk">*</span></label>
                                    <textarea class="form-control" id="lugar_de_trabajo" name="lugar_de_trabajo" rows="2" required><?php echo htmlspecialchars($medico['lugar_de_trabajo'] ?? ''); ?></textarea>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el lugar de trabajo.
                                    </div>
                                </div>

                                <!-- Campo oculto para el creador/actualizador -->
                                <input type="hidden" id="id_creador" name="id_creador" value="<?php echo $_SESSION['id_usuario'] ?? 0; ?>">
                            </div>

                            <!-- AÑADIR: Sección para Cursos (como en el registro) -->
                            <?php 
                            // Asegurarse de que $cursos_medico esté definido
                            $cursos_medico = $cursos_medico ?? [];
                            ?>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>Cursos</h5>
                                    <span class="badge bg-secondary" id="contador-cursos"><?php echo count($cursos_medico); ?> curso(s)</span>
                                </div>

                                <div id="cursos-container">
                                    <!-- Los cursos existentes se cargarán con JavaScript -->
                                </div>

                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-outline-success" id="btn-agregar-curso">
                                        <i class="fas fa-plus me-1"></i> Agregar Curso
                                    </button>
                                </div>
                            </div>

                            <!-- AÑADIR: Sección para Diplomados (como en el registro) -->
                            <?php 
                            // Asegurarse de que $diplomados_medico esté definido
                            $diplomados_medico = $diplomados_medico ?? [];
                            ?>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fas fa-award me-2"></i>Diplomados</h5>
                                    <span class="badge bg-secondary" id="contador-diplomados"><?php echo count($diplomados_medico); ?> diplomado(s)</span>
                                </div>

                                <div id="diplomados-container">
                                    <!-- Los diplomados existentes se cargarán con JavaScript -->
                                </div>

                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-outline-success" id="btn-agregar-diplomado">
                                        <i class="fas fa-plus me-1"></i> Agregar Diplomado
                                    </button>
                                </div>
                            </div>

                            <!-- AÑADIR: Sección para Deportes (como en el registro) -->
                            <div class="mt-4">
                                <h5 class="mb-3"><i class="fas fa-futbol me-2"></i>Deportes que Practica</h5>
                                <div class="row g-3">
                                    <div class="col-lg-12">
                                        <label for="deportes" class="form-label">Seleccionar Deportes</label>
                                        <select class="form-select" id="deportes" name="deportes[]" multiple size="4">
                                            <?php 
                                            // Asegurarse de que $deportes_medico esté definido (IDs de deportes del médico)
                                            $deportes_medico_ids = $deportes_medico ?? [];
                                            foreach ($deportes as $deporte) { 
                                                $selected = in_array($deporte['id_deporte'], $deportes_medico_ids) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $deporte['id_deporte']; ?>" <?php echo $selected; ?>>
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
                                Puede agregar nuevas especialidades/subespecialidades o modificar las existentes.
                            </div>

                            <div id="especialidades-container">
                                <!-- Las especialidades existentes se precargarán con JavaScript -->
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success" id="btn-agregar-especialidad">
                                    <i class="fas fa-plus me-1"></i> Agregar Nueva Especialidad/Subespecialidad
                                </button>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                                    <i class="fas fa-arrow-left me-1"></i> Anterior
                                </button>
                                <button type="button" class="btn btn-primary" onclick="nextStep(4)" id="btn-next-step3">
                                    Siguiente <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Paso 4: Gestión de Documentos -->
                        <div class="form-section" id="step4">
                            <h3 class="section-title"><i class="fas fa-file-alt me-2"></i>Gestión de Documentos</h3>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Puede agregar nuevos documentos o gestionar los existentes.
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light py-2">
                                            <h5 class="mb-0">Nuevos Documentos</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Agregar nuevos documentos:</label>
                                                <input type="file" class="form-control" id="nuevos_documentos" name="nuevos_documentos[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                                                <div class="form-text-help">
                                                    Seleccione múltiples archivos manteniendo presionada la tecla Ctrl (Windows) o Cmd (Mac)
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header bg-light py-2">
                                            <h5 class="mb-0">Documentos Existentes</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="documentos-existente-container">
                                                <!-- Los documentos existentes se cargarán aquí -->
                                            </div>
                                            <div id="sin-documentos" class="text-center py-4">
                                                <p class="text-muted">No hay documentos registrados.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep(4)">
                                    <i class="fas fa-arrow-left me-1"></i> Anterior
                                </button>
                                <button type="button" class="btn btn-primary" onclick="nextStep(5)" id="btn-next-step4">
                                    Siguiente <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Paso 5: Resumen -->
                        <div class="form-section" id="step5">
                            <h3 class="section-title"><i class="fas fa-clipboard-check me-2"></i>Resumen de Cambios</h3>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light py-2">
                                            <h5 class="mb-0">Resumen de la Actualización</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <h6>Información Personal:</h6>
                                                <p id="resumen-nombre" class="mb-1"><strong>Nombre:</strong> <span class="text-muted"><?php echo htmlspecialchars($medico['nombres'] . ' ' . $medico['apellidos']); ?></span></p>
                                                <p id="resumen-cedula" class="mb-1"><strong>Cédula:</strong> <span class="text-muted"><?php echo htmlspecialchars($medico['cedula']); ?></span></p>
                                                <p id="resumen-rif" class="mb-1"><strong>RIF:</strong> <span class="text-muted"><?php echo !empty($medico['rif']) ? htmlspecialchars($medico['rif']) : 'No proporcionado'; ?></span></p>
                                                <p id="resumen-impre" class="mb-1"><strong>IMPRE:</strong> <span class="text-muted"><?php echo !empty($medico['impre']) ? htmlspecialchars($medico['impre']) : 'No proporcionado'; ?></span></p>
                                                <p id="resumen-correo" class="mb-1"><strong>Correo:</strong> <span class="text-muted"><?php echo !empty($medico['correo']) ? htmlspecialchars($medico['correo']) : 'No proporcionado'; ?></span></p>
                                                <p id="resumen-foto" class="mb-1"><strong>Foto:</strong> <span class="text-muted"><?php echo !empty($medico['nombre_foto']) ? 'Conservar actual' : 'Sin cambios'; ?></span></p>
                                            </div>

                                            <div class="mb-3">
                                                <h6>Información Profesional:</h6>
                                                <p id="resumen-colegio" class="mb-1"><strong>N° Colegio:</strong> <span class="text-muted"><?php echo htmlspecialchars($medico['numero_colegio']); ?></span></p>
                                                <p id="resumen-universidad" class="mb-1"><strong>Universidad:</strong> <span class="text-muted"><?php echo htmlspecialchars($medico['universidad_graduado']); ?></span></p>
                                                <p id="resumen-cursos" class="mb-1"><strong>Cursos:</strong> <span class="text-muted"><?php echo count($cursos_medico ?? []); ?></span></p>
                                                <p id="resumen-diplomados" class="mb-1"><strong>Diplomados:</strong> <span class="text-muted"><?php echo count($diplomados_medico ?? []); ?></span></p>
                                                <p id="resumen-deportes" class="mb-1"><strong>Deportes:</strong> <span class="text-muted"><?php echo count($deportes_medico_ids ?? []); ?></span></p>
                                                <p id="resumen-estado" class="mb-1"><strong>Estado:</strong> <span class="text-muted">
                                                        <?php
                                                        $estados = [
                                                            1 => 'Activo',
                                                            2 => 'Desincorporado',
                                                            3 => 'Jubilado',
                                                            4 => 'Fallecido',
                                                            5 => 'Traslado'
                                                        ];
                                                        echo $estados[$medico['estado']] ?? 'Desconocido';
                                                        ?>
                                                    </span></p>
                                            </div>

                                            <div class="mb-3">
                                                <h6>Especialidades/Subespecialidades:</h6>
                                                <div id="resumen-especialidades-detalle">
                                                    <!-- Detalle de especialidades -->
                                                </div>
                                                <p id="resumen-nuevas-especialidades" class="mb-1"><strong>Nuevas especialidades a agregar:</strong> <span class="text-muted">0</span></p>
                                            </div>

                                            <div class="mb-3">
                                                <h6>Documentos:</h6>
                                                <p id="resumen-documentos-conservar" class="mb-1"><strong>Documentos a conservar:</strong> <span class="text-muted">0</span></p>
                                                <p id="resumen-documentos-eliminar" class="mb-1"><strong>Documentos a eliminar:</strong> <span class="text-muted">0</span></p>
                                                <p id="resumen-nuevos-documentos" class="mb-1"><strong>Nuevos documentos:</strong> <span class="text-muted">0</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep(5)">
                                    <i class="fas fa-arrow-left me-1"></i> Anterior
                                </button>
                                <div>
                                    <button type="button" class="btn btn-outline-danger me-2" onclick="resetForm()">
                                        <i class="fas fa-redo me-1"></i> Cancelar Cambios
                                    </button>
                                    <button type="submit" class="btn btn-warning" id="btn-submit-form">
                                        <i class="fas fa-save me-1"></i> Actualizar Médico
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
    // Datos del médico para precargar
    const medicoData = <?php echo json_encode($medico); ?>;
    const especialidadesMedico = <?php echo json_encode($especialidades_medico ?? []); ?>;
    const cursosMedico = <?php echo json_encode($cursos_medico ?? []); ?>;
    const diplomadosMedico = <?php echo json_encode($diplomados_medico ?? []); ?>;
    const deportesMedico = <?php echo json_encode($deportes_medico ?? []); ?>;
    const documentosMedico = <?php echo json_encode($documentos_medico ?? []); ?>;

    console.table(medicoData);
    console.table(especialidadesMedico);
    console.table(cursosMedico);
    console.table(diplomadosMedico);
    console.table(deportesMedico);
    console.table(documentosMedico);

    // Datos de especialidades disponibles
    const todasEspecialidades = JSON.parse(<?php echo $especialiades_subespecialdiadesJ; ?>);
    todasEspecialidades.sort((a, b) => a.nombre.localeCompare(b.nombre));
</script>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/form-medicos-edit.js"></script>