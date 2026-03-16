<?php

$grados = $datos['grados'] ?? [];
$especialidades_medicos = $datos['especialidades_medicos'] ?? [];
$subespecialidades_medicos = $datos['subespecialidades_medicos'] ?? [];
$medico = $datos['medico'] ?? [];
$documentos_medico = $datos['documentos_medico'] ?? [];
$cursos_medico = $datos['cursos_medico'] ?? [];
$diplomados_medico = $datos['diplomados_medico'] ?? [];
$deportes_medico = $datos['deportes_medico'] ?? [];
$municipios = $datos['municipios'] ?? [];
$parroquias = $datos['parroquias'] ?? [];
$usuario_creador = $datos['usuario_creador'] ?? [];

?>

<?php include_once "header.php"; ?>

<!-- Estilos personalizados base para los reportes verticales -->
<link href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/reporte-base-vertical.css" rel="stylesheet">

<style>
    .medico-foto-perfil {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #dee2e6;
    }

    .medico-foto-perfil img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-foto-placeholder {
        width: 100%;
        height: 100%;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }

    .badge.bg-purple {
        background-color: #6f42c1 !important;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .header-reporte {
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .fecha-reporte {
        font-size: 0.9em;
        color: #6c757d;
    }
</style>

<!-- CABECERA DEL REPORTE -->
<div class="row header-reporte">
    <div class="col-lg-12 text-center mb-3">
        <h2 class="mb-1 text-primary">Ficha Médico - Reporte Individual</h2>
        <div class="fecha-reporte">
            Generado el: <?php echo date('d/m/Y h:i:s A'); ?> / Generado por: <?php echo htmlspecialchars($_SESSION['nombres_apellidos']); ?>
        </div>
    </div>
</div>

<!-- INFORMACIÓN BÁSICA -->
<div class="row mb-4">
    <div class="col-lg-12 text-center">
        <div class="medico-foto-perfil mb-3 mx-auto">
            <?php if (!empty($medico['nombre_foto'])): ?>
                <img src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] . '/assets/images/medicos/' . $medico['nombre_foto']; ?>"
                    alt="Foto del médico"
                    class="img-fluid">
            <?php else: ?>
                <div class="no-foto-placeholder">
                    <i class="fas fa-user-md fa-2x mb-2"></i>
                    <span>Sin foto</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-12">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Nombre Completo:</th>
                    <td><?php echo htmlspecialchars($medico['nombres'] . ' ' . $medico['apellidos']); ?></td>
                </tr>
                <tr>
                    <th>Cédula de Identidad:</th>
                    <td><?php echo htmlspecialchars($medico['cedula']); ?></td>
                </tr>
                <tr>
                    <th>Número de Colegio:</th>
                    <td><?php echo htmlspecialchars($medico['numero_colegio']); ?></td>
                </tr>
                <tr>
                    <th>Estado:</th>
                    <td>
                        <?php
                        $estados = [
                            1 => 'Activo',
                            2 => 'Desincorporado',
                            3 => 'Jubilado',
                            4 => 'Fallecido',
                            5 => 'Traslado'
                        ];
                        $estado_text = $estados[$medico['estado']] ?? 'Desconocido';
                        $estado_class = $medico['estado'] == 1 ? 'badge-success' : ($medico['estado'] == 2 ? 'badge-warning' : ($medico['estado'] == 3 ? 'badge-info' : ($medico['estado'] == 4 ? 'badge-danger' : 'badge-secondary')));
                        ?>
                        <span class="badge <?php echo $estado_class; ?>"><?php echo $estado_text; ?></span>
                    </td>
                </tr>
                <tr>
                    <th>Fecha de Registro:</th>
                    <td><?php echo date('d/m/Y', strtotime($medico['creado_el'])); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- SECCIÓN 1: INFORMACIÓN PERSONAL -->
<div class="card mb-4" width="100%">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-user me-2"></i>INFORMACIÓN PERSONAL</h5>
    </div>
    <div class="card-body">
        <table class="table  table-bordered">
            <tr>
                <th width="40%">Nacionalidad:</th>
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
            <tr>
                <th width="40%">Teléfono:</th>
                <td><?php echo htmlspecialchars($medico['telefono']); ?></td>
            </tr>
            <tr>
                <th>Correo Electrónico:</th>
                <td><?php echo htmlspecialchars($medico['correo']); ?></td>
            </tr>
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

        <?php if (isset($deportes_medico) && !empty($deportes_medico)): ?>
            <div class="mt-3">
                <strong>Deportes que practica:</strong>
                <?php foreach ($deportes_medico as $deporte): ?>
                    <span class="badge badge-info mr-1 mt-1"><?php echo htmlspecialchars($deporte['nombre']); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- SECCIÓN 2: INFORMACIÓN PROFESIONAL -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>INFORMACIÓN PROFESIONAL</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Numero de IMPRE:</th>
                <td><?php echo htmlspecialchars($medico['impre']); ?></td>
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
        </table>
    </div>
</div>

<!-- SECCIÓN 3: ESPECIALIDADES -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-stethoscope me-2"></i>ESPECIALIDADES Y SUBESPECIALIDADES</h5>
    </div>
    <div class="card-body">
        <?php
        $todas_especialidades = array_merge(
            $especialidades_medicos ?? [],
            $subespecialidades_medicos ?? []
        );
        ?>

        <?php if (!empty($todas_especialidades)): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center font-weight-bolder" width="20%">Tipo</th>
                            <th class="text-center font-weight-bolder">Nombre</th>
                            <th class="text-center font-weight-bolder" width="30%">Universidad</th>
                            <th class="text-center font-weight-bolder" width="15%">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($todas_especialidades as $esp): ?>
                            <tr>
                                <td class="text-center">
                                    <span class="text-white badge <?php echo $esp['tipo'] == 'especialidad' ? 'badge-primary' : ''; ?>" style="<?php echo $esp['tipo'] != 'especialidad' ? 'background-color: #422E77;' : ''; ?>">
                                        <?php echo $esp['tipo'] == 'especialidad' ? 'Especialidad' : 'Subespecialidad'; ?>
                                    </span>
                                </td>
                                <td class="text-center"><?php echo htmlspecialchars($esp['nombre']); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($esp['universidad_obtenido']); ?></td>
                                <td class="text-center"><?php echo date('d/m/Y', strtotime($esp['fecha_obtencion'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


            <table class="table table-borderless">
                <tbody class="align-middle">
                    <tr class="text-center">
                        <td>
                            <div class="border rounded mx-5 mb-2 p-2 text-primary">
                                <h3><strong><?php echo count($especialidades_medicos ?? []); ?></strong></h3>
                                <strong>Especialidades</strong>
                            </div>
                        </td>
                        <td>
                            <div class="border rounded mx-5 mb-2 p-2" style="color:#422E77;">
                                <h3><strong> <?php echo count($subespecialidades_medicos ?? []); ?></strong></h3>
                                <strong>Subespecialidades</strong>
                            </div>
                        </td>
                        <td>
                            <div class="border rounded mx-5 mb-2 p-2 text-success">
                                <h3><strong><?php echo count($todas_especialidades); ?></strong></h3>
                                <strong>Total</strong>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        <?php else: ?>
            <div class="alert alert-light mb-0">
                <i class="fas fa-info-circle mr-2"></i>
                No tiene especialidades registradas.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- SECCIÓN 4: CURSOS Y DIPLOMADOS -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>CURSOS Y DIPLOMADOS</h5>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tbody class="align-middle">
                <tr class="text-center">
                    <td>
                        <div>
                            <h6 class="text-success border-bottom pb-2">
                                <strong><i class="fas fa-certificate mr-2"></i>Cursos (<?php echo count($cursos_medico ?? []); ?>)</strong>
                            </h6>
                            <?php if (!empty($cursos_medico)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($cursos_medico as $curso): ?>
                                        <div class="list-group-item px-0">
                                            <strong><?php echo htmlspecialchars($curso['nombre']); ?></strong>
                                            <div class="text-muted small">
                                                <?php echo htmlspecialchars($curso['universidad_obtenido']); ?> -
                                                <?php echo date('d/m/Y', strtotime($curso['fecha_obtencion'])); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No hay cursos registrados.</p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <h6 class="text-info border-bottom pb-2">
                            <strong><i class="fas fa-award mr-2"></i>Diplomados (<?php echo count($diplomados_medico ?? []); ?>)</strong>
                        </h6>
                        <?php if (!empty($diplomados_medico)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($diplomados_medico as $diplomado): ?>
                                    <div class="list-group-item px-0">
                                        <strong><?php echo htmlspecialchars($diplomado['nombre']); ?></strong>
                                        <div class="text-muted small">
                                            <?php echo htmlspecialchars($diplomado['universidad_obtenido']); ?> -
                                            <?php echo date('d/m/Y', strtotime($diplomado['fecha_obtencion'])); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No hay diplomados registrados.</p>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- SECCIÓN 5: DOCUMENTOS -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>DOCUMENTOS REGISTRADOS</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($documentos_medico)): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center font-weight-bolder" width="5%">#</th>
                            <th class="text-center font-weight-bolder">Nombre del Documento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documentos_medico as $index => $documento): ?>
                            <tr>
                                <td class="text-center"><?php echo $index + 1; ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($documento['nombre_documento']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-3">
                <div class="badge badge-primary p-2">
                    Total de documentos: <?php echo count($documentos_medico); ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-light mb-0">
                <i class="fas fa-info-circle mr-2"></i>
                No tiene documentos registrados.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once "footer.php"; ?>