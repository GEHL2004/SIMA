<?php require_once "./public/views/layouts/header.php"; ?>

<link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/show-medicos.css">

<div class="container-fluid content-inner py-0">
    <div class="row">
        <div class="col-lg-12">
            <!-- Botón de regreso -->
            <div class="mb-3">
                <a href="/SIMA/medicos" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver al listado
                </a>
            </div>

            <div class="card">
                <!-- Cabecera con foto y datos básicos -->
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <!-- Foto del médico -->
                        <div class="col-auto">
                            <div class="medico-foto-perfil">
                                <?php if (!empty($medico['nombre_foto'])): ?>
                                    <img src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] . '/assets/images/medicos/' . $medico['nombre_foto']; ?>"
                                        alt="Foto de <?php echo htmlspecialchars($medico['nombres'] . ' ' . $medico['apellidos']); ?>"
                                        class="img-thumbnail">
                                <?php else: ?>
                                    <div class="no-foto-placeholder">
                                        <i class="fas fa-user-md fa-3x"></i>
                                        <span>Sin foto</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Información básica -->
                        <div class="col">
                            <h1 class="h3 mb-1"><?php echo htmlspecialchars($medico['nombres'] . ' ' . $medico['apellidos']); ?></h1>
                            <p class="mb-1">
                                <strong>Cédula:</strong> <?php echo htmlspecialchars($medico['cedula']); ?>
                                <?php if (!empty($medico['rif'])): ?>
                                    | <strong>RIF:</strong> <?php echo htmlspecialchars($medico['rif']); ?>
                                <?php endif; ?>
                                <?php if (!empty($medico['impre'])): ?>
                                    | <strong>IMPRE:</strong> <?php echo htmlspecialchars($medico['impre']); ?>
                                <?php endif; ?>
                            </p>
                            <p class="mb-0">
                                <strong>Estado:</strong>
                                <span class="badge <?php echo $medico['estado'] == 1 ? 'bg-success' : ($medico['estado'] == 2 ? 'bg-warning' : ($medico['estado'] == 3 ? 'bg-info' : ($medico['estado'] == 4 ? 'bg-danger' : 'bg-secondary'))); ?>">
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
                                </span>
                            </p>
                        </div>

                        <!-- Botones de acción -->
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a class="btn btn-warning" href="/SIMA/medicos-edit/<?php echo $medico['id_medico']; ?>">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </a>
                                <a class="btn btn-info" href="/SIMA/medicos-generar-reporte-individual/<?php echo $medico['id_medico']; ?>" target="_blank">
                                    <i class="fas fa-print me-1"></i> Imprimir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cuerpo con pestañas -->
                <div class="card-body">
                    <!-- Pestañas -->
                    <ul class="nav nav-tabs" id="medicoTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-personal-tab" data-bs-toggle="tab" data-bs-target="#info-personal" type="button" role="tab">
                                <i class="fas fa-user me-1"></i> Información Personal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="info-profesional-tab" data-bs-toggle="tab" data-bs-target="#info-profesional" type="button" role="tab">
                                <i class="fas fa-graduation-cap me-1"></i> Información Profesional
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="especialidades-subespecialidades-tab" data-bs-toggle="tab" data-bs-target="#especialidades-subespecialidades" type="button" role="tab">
                                <i class="fas fa-stethoscope me-1"></i> Especialidades
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cursos-tab" data-bs-toggle="tab" data-bs-target="#cursos" type="button" role="tab">
                                <i class="fas fa-certificate me-1"></i> Cursos y Diplomados
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="documentos-tab" data-bs-toggle="tab" data-bs-target="#documentos" type="button" role="tab">
                                <i class="fas fa-file-alt me-1"></i> Documentos
                                <?php if (!empty($documentos_medico)): ?>
                                    <span class="badge bg-secondary ms-1"><?php echo count($documentos_medico); ?></span>
                                <?php endif; ?>
                            </button>
                        </li>
                    </ul>

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content p-3 border border-top-0 rounded-bottom" id="medicoTabsContent">

                        <!-- Pestaña 1: Información Personal -->
                        <div class="tab-pane fade show active" id="info-personal" role="tabpanel">
                            <div class="row">
                                <!-- Columna izquierda -->
                                <div class="col-lg-6">
                                    <h5 class="mb-3 text-primary"><i class="fas fa-id-card me-2"></i>Datos Personales</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="auto">Nombres:</th>
                                            <td><?php echo htmlspecialchars($medico['nombres']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Apellidos:</th>
                                            <td><?php echo htmlspecialchars($medico['apellidos']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Cédula:</th>
                                            <td><?php echo htmlspecialchars($medico['cedula']); ?></td>
                                        </tr>
                                        <?php if (!empty($medico['rif'])): ?>
                                            <tr>
                                                <th>RIF:</th>
                                                <td><?php echo htmlspecialchars($medico['rif']); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($medico['impre'])): ?>
                                            <tr>
                                                <th>IMPRE:</th>
                                                <td><?php echo htmlspecialchars($medico['impre']); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <th>Nacionalidad:</th>
                                            <td><?php echo htmlspecialchars($medico['nacionalidad']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Fecha de Nacimiento:</th>
                                            <td><?php echo date('d/m/Y', strtotime($medico['fecha_nacimiento'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Lugar de Nacimiento:</th>
                                            <td><?php echo htmlspecialchars($medico['lugar_nacimiento']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tipo de Sangre:</th>
                                            <td><?php echo htmlspecialchars($medico['tipo_sangre']); ?></td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Columna derecha -->
                                <div class="col-lg-6">
                                    <h5 class="mb-3 text-primary"><i class="fas fa-address-book me-2"></i>Contacto y Ubicación</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="auto">Teléfono:</th>
                                            <td><?php echo htmlspecialchars($medico['telefono']); ?></td>
                                        </tr>
                                        <?php if (!empty($medico['correo'])): ?>
                                            <tr>
                                                <th>Correo Electrónico:</th>
                                                <td>
                                                    <a href="mailto:<?php echo htmlspecialchars($medico['correo']); ?>?subject=Colegio de Medicos del Estado Aragua&body=Hola,%20este%20es%20el%20cuerpo%20del%20mensaje">
                                                        <?php echo htmlspecialchars($medico['correo']); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <th>Municipio:</th>
                                            <td>
                                                <?php
                                                $nombre_municipio = '';
                                                foreach ($municipios as $municipio) {
                                                    if ($municipio['id_municipio'] == $medico['id_municipio']) {
                                                        $nombre_municipio = $municipio['nombre_municipio'];
                                                        break;
                                                    }
                                                }
                                                echo htmlspecialchars($nombre_municipio);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Parroquia:</th>
                                            <td>
                                                <?php
                                                $nombre_parroquia = '';
                                                if (isset($parroquias) && is_array($parroquias)) {
                                                    foreach ($parroquias as $parroquia) {
                                                        if ($parroquia['id_parroquia'] == $medico['id_parroquia']) {
                                                            $nombre_parroquia = $parroquia['nombre_parroquia'];
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo htmlspecialchars($nombre_parroquia);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Dirección:</th>
                                            <td><?php echo nl2br(htmlspecialchars($medico['direccion'])); ?></td>
                                        </tr>
                                    </table>

                                    <!-- Deportes -->
                                    <?php if (isset($deportes_medico) && !empty($deportes_medico)): ?>
                                        <h5 class="mt-4 mb-2 text-primary"><i class="fas fa-futbol me-2"></i>Deportes que Practica</h5>
                                        <div class="deportes-container">
                                            <?php foreach ($deportes_medico as $deporte): ?>
                                                <span class="badge bg-info me-1 mb-1"><?php echo htmlspecialchars($deporte['nombre']); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña 2: Información Profesional -->
                        <div class="tab-pane fade" id="info-profesional" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 class="mb-3 text-primary"><i class="fas fa-briefcase me-2"></i>Datos Profesionales</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="35%">Número de Colegio:</th>
                                            <td><?php echo htmlspecialchars($medico['numero_colegio']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Matrícula del Ministerio:</th>
                                            <td><?php echo htmlspecialchars($medico['matricula_ministerio']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Universidad de Graduación:</th>
                                            <td><?php echo htmlspecialchars($medico['universidad_graduado']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Fecha de Egreso:</th>
                                            <td><?php echo date('d/m/Y', strtotime($medico['fecha_egreso_universidad'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Fecha de Inscripción:</th>
                                            <td><?php echo date('d/m/Y', strtotime($medico['fecha_incripcion'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Grado Académico:</th>
                                            <td>
                                                <?php
                                                $grado_academico = '';
                                                if (isset($grados) && is_array($grados)) {
                                                    foreach ($grados as $grado) {
                                                        if ($grado['id_grado_academico'] == $medico['id_grado_academico']) {
                                                            $grado_academico = $grado['nombre_grado'];
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo htmlspecialchars($grado_academico);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Lugar de Trabajo:</th>
                                            <td><?php echo nl2br(htmlspecialchars($medico['lugar_de_trabajo'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Registrado por:</th>
                                            <td>
                                                <?php
                                                $nombre_creador = 'Sistema';
                                                if (isset($usuario_creador) && !empty($usuario_creador)) {
                                                    $nombre_creador = htmlspecialchars(str_replace('_', ' ', $usuario_creador['nombres'] . ' ' . $usuario_creador['apellidos']));
                                                }
                                                echo $nombre_creador;
                                                ?>
                                                <small class="text-muted d-block">
                                                    Fecha de registro: <?php echo date('d/m/Y', strtotime($medico['creado_el'])); ?>
                                                </small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña 3: Especialidades -->
                        <div class="tab-pane fade" id="especialidades-subespecialidades" role="tabpanel">
                            <?php
                            // Combinar especialidades y subespecialidades
                            $todas_especialidades = array_merge(
                                $especialidades_medicos ?? [],
                                $subespecialidades_medicos ?? []
                            );

                            // Ordenar por fecha de obtención (más reciente primero)
                            usort($todas_especialidades, function ($a, $b) {
                                return strtotime($b['fecha_obtencion']) - strtotime($a['fecha_obtencion']);
                            });
                            ?>
                            <?php if (!empty($todas_especialidades)) { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Nombre</th>
                                                <th>Universidad de Obtención</th>
                                                <th>Fecha de Obtención</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($todas_especialidades as $esp) { ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge <?php echo $esp['tipo'] == 'especialidad' ? 'bg-primary' : 'bg-purple'; ?>">
                                                            <?php echo $esp['tipo'] == 'especialidad' ? 'Especialidad' : 'Subespecialidad'; ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($esp['nombre']); ?></td>
                                                    <td><?php echo htmlspecialchars($esp['universidad_obtenido']); ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($esp['fecha_obtencion'])); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Resumen -->
                                <div class="row mt-4">
                                    <div class="col-lg-4">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h3 class="card-title text-primary"><?php echo count($especialidades_medicos ?? []); ?></h3>
                                                <p class="card-text">Especialidades</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h3 class="card-title text-purple"><?php echo count($subespecialidades_medicos ?? []); ?></h3>
                                                <p class="card-text">Subespecialidades</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h3 class="card-title text-success"><?php echo count($todas_especialidades); ?></h3>
                                                <p class="card-text">Total</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Este médico no tiene especialidades o subespecialidades registradas.
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Pestaña 4: Cursos y Diplomados -->
                        <div class="tab-pane fade" id="cursos" role="tabpanel">
                            <div class="row">
                                <!-- Cursos -->
                                <div class="col-lg-6">
                                    <h5 class="mb-3 text-success"><i class="fas fa-certificate me-2"></i>Cursos</h5>
                                    <?php if (!empty($cursos_medico)): ?>
                                        <div class="list-group">
                                            <?php foreach ($cursos_medico as $curso): ?>
                                                <div class="list-group-item">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1"><?php echo htmlspecialchars($curso['nombre']); ?></h6>
                                                        <small><?php echo date('d/m/Y', strtotime($curso['fecha_obtencion'])); ?></small>
                                                    </div>
                                                    <p class="mb-1"><small class="text-muted">Universidad: <?php echo htmlspecialchars($curso['universidad_obtenido']); ?></small></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-light">
                                            No hay cursos registrados.
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Diplomados -->
                                <div class="col-lg-6">
                                    <h5 class="mb-3 text-warning"><i class="fas fa-award me-2"></i>Diplomados</h5>
                                    <?php if (!empty($diplomados_medico)): ?>
                                        <div class="list-group">
                                            <?php foreach ($diplomados_medico as $diplomado): ?>
                                                <div class="list-group-item">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1"><?php echo htmlspecialchars($diplomado['nombre']); ?></h6>
                                                        <small><?php echo date('d/m/Y', strtotime($diplomado['fecha_obtencion'])); ?></small>
                                                    </div>
                                                    <p class="mb-1"><small class="text-muted">Universidad: <?php echo htmlspecialchars($diplomado['universidad_obtenido']); ?></small></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-light">
                                            No hay diplomados registrados.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Resumen -->
                            <div class="row mt-4">
                                <div class="col-lg-6">
                                    <div class="card text-center border-success">
                                        <div class="card-body">
                                            <h3 class="card-title text-success"><?php echo count($cursos_medico ?? []); ?></h3>
                                            <p class="card-text">Cursos</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card text-center border-warning">
                                        <div class="card-body">
                                            <h3 class="card-title text-warning"><?php echo count($diplomados_medico ?? []); ?></h3>
                                            <p class="card-text">Diplomados</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña 5: Documentos -->
                        <div class="tab-pane fade" id="documentos" role="tabpanel">
                            <?php if (!empty($documentos_medico)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th>Nombre del Documento</th>
                                                <th width="20%">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($documentos_medico as $index => $documento): ?>
                                                <?php
                                                // Obtener la extensión del archivo
                                                $extension = pathinfo($documento['nombre_documento'], PATHINFO_EXTENSION);
                                                $extension = strtolower($extension);

                                                // Determinar el icono según la extensión
                                                $icono = 'fa-file';
                                                if (in_array($extension, ['pdf'])) {
                                                    $icono = 'fa-file-pdf';
                                                } elseif (in_array($extension, ['doc', 'docx'])) {
                                                    $icono = 'fa-file-word';
                                                } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                    $icono = 'fa-file-image';
                                                }
                                                ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas <?php echo $icono; ?> fa-2x text-primary me-3"></i>
                                                            <div>
                                                                <div class="fw-bold"><?php echo htmlspecialchars($documento['nombre_documento']); ?></div>
                                                                <small class="text-muted">ID: <?php echo $documento['id_documento']; ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="<?php echo $documento['ruta_archivo']; ?>"
                                                                class="btn btn-sm btn-primary"
                                                                target="_blank"
                                                                title="Ver documento">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="<?php echo $documento['ruta_archivo']; ?>"
                                                                class="btn btn-sm btn-success"
                                                                download
                                                                title="Descargar">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                            <button type="button"
                                                                class="btn btn-sm btn-info"
                                                                onclick="verDetalleDocumento(<?php echo $documento['id_documento']; ?>)"
                                                                title="Información">
                                                                <i class="fas fa-info-circle"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Estadísticas -->
                                <div class="row mt-4">
                                    <div class="col-lg-4">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h3 class="card-title text-primary"><?php echo count($documentos_medico); ?></h3>
                                                <p class="card-text">Documentos totales</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h3 class="card-title text-success">PDF</h3>
                                                <p class="card-text">Documentos PDF</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h3 class="card-title text-info">Varios</h3>
                                                <p class="card-text">Otros formatos</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón para descargar todos -->
                                <!-- <div class="text-center mt-4">
                                    <button type="button" class="btn btn-lg btn-success" id="btn-descargar-todos">
                                        <i class="fas fa-download me-2"></i> Descargar Todos los Documentos
                                    </button>
                                    <small class="d-block text-muted mt-2">
                                        Se descargará un archivo ZIP con todos los documentos del médico
                                    </small>
                                </div> -->
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Este médico no tiene documentos registrados.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Pie de página -->
                <div class="card-footer text-muted">
                    <div class="row">
                        <div class="col">
                            <small>
                                <i class="fas fa-calendar me-1"></i>
                                Última actualización: <?php echo date('d/m/Y H:i', strtotime($medico['creado_el'])); ?>
                            </small>
                        </div>
                        <div class="col-auto">
                            <small>ID del Médico: <?php echo $medico['id_medico']; ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles del documento -->
<div class="modal fade" id="documentoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Información del Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="documentoInfo">
                    <!-- La información se cargará aquí -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" class="btn btn-primary" id="btnVerDocumentoModal" target="_blank">
                    <i class="fas fa-eye me-1"></i> Ver
                </a>
                <a href="#" class="btn btn-success" id="btnDescargarDocumentoModal" download>
                    <i class="fas fa-download me-1"></i> Descargar
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once "./public/views/layouts/footer.php"; ?>

<!-- JavaScript -->
<script>
    document.getElementById('overlay').remove();
    document.getElementById('spinner-container').remove();
    // Datos del médico para uso en JavaScript
    const medicoData = <?php echo json_encode($medico); ?>;
    const documentosData = <?php echo json_encode($documentos_medico ?? []); ?>;

    // Función para ver detalles del documento
    function verDetalleDocumento(idDocumento) {
        // Buscar el documento
        const documento = documentosData.find(doc => doc.id_documento == idDocumento);

        if (documento) {
            // Formatear nombre para mostrar sin extensiones
            const nombreArchivo = documento.nombre_documento;
            const nombreSinExt = nombreArchivo.replace(/\.[^/.]+$/, ""); // Remover extensión
            const extension = nombreArchivo.split('.').pop().toUpperCase();

            // Calcular fecha simulada (en un caso real, esto vendría de la BD)
            const fechaSubida = new Date();
            fechaSubida.setDate(fechaSubida.getDate() - Math.floor(Math.random() * 30)); // Hace 0-30 días

            // Crear contenido del modal
            const contenido = `
            <div class="row">
                <div class="col-lg-12 text-center mb-3">
                    <i class="fas fa-file-alt fa-4x text-primary"></i>
                </div>
                <div class="col-lg-12">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Nombre:</th>
                            <td>${nombreSinExt}</td>
                        </tr>
                        <tr>
                            <th>Formato:</th>
                            <td><span class="badge bg-info">${extension}</span></td>
                        </tr>
                        <tr>
                            <th>ID del documento:</th>
                            <td>${documento.id_documento}</td>
                        </tr>
                        <tr>
                            <th>ID del médico:</th>
                            <td>${medicoData.id_medico}</td>
                        </tr>
                    </table>
                </div>
            </div>
        `;

            // Insertar contenido
            document.getElementById('documentoInfo').innerHTML = contenido;

            // Configurar enlaces
            document.getElementById('btnVerDocumentoModal').href = documento.ruta_archivo;
            document.getElementById('btnDescargarDocumentoModal').href = documento.ruta_archivo;

            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('documentoModal'));
            modal.show();
        }
    }

    // Función para descargar todos los documentos (simulación)
    document.getElementById('btn-descargar-todos')?.addEventListener('click', function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Descargar todos los documentos?',
                text: 'Se descargará un archivo ZIP con todos los documentos del médico.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, descargar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Preparando descarga...',
                        text: 'Estamos comprimiendo los documentos, esto puede tardar unos momentos.',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });

                    // Simular proceso de compresión
                    setTimeout(() => {
                        // En un caso real, aquí se haría una petición al servidor
                        // para generar y descargar el ZIP

                        // Simulación de éxito
                        Swal.fire({
                            title: '¡Listo!',
                            text: 'El archivo ZIP está listo para descargar.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });

                        // En un caso real, aquí se descargaría el archivo
                        // window.location.href = '/SIMA/medicos/descargar-documentos/' + medicoData.id_medico;
                    }, 2000);
                }
            });
        } else {
            alert('Funcionalidad de descarga múltiple - En desarrollo');
        }
    });

    // Inicializar tooltips de Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Ajustar altura del navbar
        const iq_navbar_header = document.getElementById("iq-navbar-header");
        if (iq_navbar_header) {
            iq_navbar_header.style.height = "15px";
        }

        // Guardar en localStorage la última pestaña activa
        const medicoTabs = document.getElementById('medicoTabs');
        if (medicoTabs) {
            medicoTabs.addEventListener('shown.bs.tab', function(event) {
                const activeTab = event.target.getAttribute('data-bs-target');
                localStorage.setItem('medicoActiveTab', activeTab);
            });

            // Recuperar pestaña activa si existe
            const savedTab = localStorage.getItem('medicoActiveTab');
            if (savedTab) {
                const tab = document.querySelector(`[data-bs-target="${savedTab}"]`);
                if (tab) {
                    new bootstrap.Tab(tab).show();
                }
            }
        }
    });

    // Función para imprimir solo la información del médico
    function imprimirInformacion() {
        // Ocultar elementos que no queremos imprimir
        const elementosOcultar = document.querySelectorAll('.no-print');
        elementosOcultar.forEach(el => el.classList.add('d-print-none'));

        // Mostrar solo la pestaña activa
        document.querySelectorAll('.tab-pane').forEach(pane => {
            if (!pane.classList.contains('active')) {
                pane.classList.add('d-print-none');
            }
        });

        // Imprimir
        window.print();

        // Restaurar elementos
        elementosOcultar.forEach(el => el.classList.remove('d-print-none'));
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('d-print-none');
        });
    }
</script>

<!-- CSS adicional -->
<style>
    /* Estilos para la vista del médico */
    .medico-foto-perfil {
        width: 150px;
        height: 150px;
        border-radius: 10px;
        overflow: hidden;
        border: 3px solid white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .medico-foto-perfil img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-foto-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .no-foto-placeholder i {
        margin-bottom: 10px;
    }

    /* Mejoras para las pestañas */
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 20px;
    }

    .nav-tabs .nav-link:hover {
        border-bottom: 3px solid #dee2e6;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 3px solid #0d6efd;
        background-color: transparent;
    }

    /* Estilos para tablas */
    .table th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
    }

    /* Estilos para badges personalizados */
    .badge.bg-purple {
        background-color: #6f42c1 !important;
    }

    /* Estilos para documentos */
    .documento-acciones .btn {
        padding: 5px 10px;
        font-size: 0.875rem;
    }

    /* Estilos para impresión */
    @media print {
        .no-print {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .tab-content {
            border: none !important;
        }

        .btn {
            display: none !important;
        }

        .nav-tabs {
            display: none !important;
        }

        .tab-pane {
            display: block !important;
            opacity: 1 !important;
        }
    }
</style>