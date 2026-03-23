<?php require_once "./public/views/layouts/header.php";

use App\Config\PermisosHelper;

// Verificar permisos para mostrar/ocultar botones
$puedeRegistrar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_RECONOCIMIENTOS, PermisosHelper::REGISTRAR);
$puedeActualizar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_RECONOCIMIENTOS, PermisosHelper::ACTUALIZAR);

?>



<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-lg-12 col-lg-12">
            <div class="text-center mb-5 mt-3">
                <h1 class="text-primary">Listado de Reconocimientos</h1>
            </div>

            <div class="row mb-2">
                <div class="row justify-content-center">
                    <div class="col-11 table-responsive">
                        <table id="tabla" class="table table-sm table-bordered mb-0" style="width: auto !important;">
                            <thead class="align-middle">
                                <tr>
                                    <th width="3%" scope="col" class="text-center bg-body-tertiary">#</th>
                                    <th width="30%" scope="col" class="text-center bg-body-tertiary">Nombre del Médico</th>
                                    <th scope="col" class="text-center bg-body-tertiary">Cant. de Reconocimientos Recibidos</th>
                                    <th scope="col" class="text-center bg-body-tertiary">Cant. de Reconocimientos Faltantes</th>
                                    <th width="10%" class="text-center bg-body-tertiary" scope="col" id="columnaaccion2">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-3 m-0 p-0"></div>
        <div class="col-lg-6 d-flex justify-content-center">
            <button type="buttom" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#listadoModal">
                <i class="fa-solid fa-clipboard-list me-2"></i> Obtener Listado <i class="fa-solid fa-clipboard-list me-2"></i>
            </button>
        </div>
        <div class="col-lg-3 m-0 p-0"></div>
    </div>
</div>

<!-- Modal de generar listado -->
<div class="modal modal-md fade" id="listadoModal" tabindex="-1" aria-labelledby="listadoModalLabel" aria-hidden="true">
    <form action="/SIMA/medicos-reconocimientos-listado" method="POST">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="listadoModalLabel">Generar listado de reconocimientos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <label for="estado" class="mb-1">Estado:</label>
                        <select name="estado" id="estado" class="form-select mb-3">
                            <option value="faltantes" seleted>No reconocidos</option>
                            <option value="entregados">Reconocidos</option>
                        </select>
                    </div>
                    <div>
                        <label for="tipo_reconocimiento" class="mb-1">Tipo de reconocimiento:</label>
                        <select name="tipo_reconocimiento" id="tipo_reconocimiento" class="form-select mb-3">
                            <option value="1" seleted>30 años</option>
                            <option value="2">40 años</option>
                            <option value="3">50 años</option>
                            <option value="4">60 años</option>
                        </select>
                    </div>
                    <div>
                        <label for="cantidad" class="mb-1">Cantidad de medicos a buscar: <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control mb-1" min="0" max="100" value="30" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="fa-regular fa-circle-xmark m-0 me-2"></i>Cerrar</button>
                    <button type="submit" class="btn btn-success">Generar listado</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal de Actualización -->
<div class="modal modal-lg fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <form action="/SIMA/medicos-reconocimientos-update" method="POST" id="form-update">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditLabel">Actualización de reconocimientos otorgados</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="info-medico" class="mb-3"></div>
                    <!-- Reconocimiento 30 años -->
                    <!-- Datos del médico -->
                    <div class="card mb-4 border-0 bg-primary-subtle">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-3">
                                <i class="fa-solid fa-user-doctor"></i>
                                Datos Profesionales
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="fecha-egreso" class="form-label fw-medium">
                                        <i class="fa-solid fa-calendar-days me-1"></i>
                                        Fecha de Egreso
                                    </label>
                                    <input type="date" class="form-control" id="fecha-egreso"
                                        placeholder="YYYY-MM-DD" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">
                                        <i class="fa-solid fa-clock-rotate-left me-1"></i>
                                        Años de Ejercicio
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white" id="anios-ejercicio-icon">
                                            <i class="fa-solid fa-star text-warning"></i>
                                        </span>
                                        <input type="text" class="form-control fw-bold" id="anios-ejercicio"
                                            value="29 años" readonly aria-describedby="anios-ejercicio-icon">
                                    </div>
                                    <div class="form-text">
                                        <i class="fa-solid fa-circle-info me-1"></i>
                                        Calculado desde la fecha de egreso hasta hoy
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline visual de reconocimientos (corregido) -->
                    <div class="card mb-4 border-0">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold mb-3">
                                <i class="fa-solid fa-trophy me-2"></i>
                                Progresión de Reconocimientos
                            </h6>

                            <!-- Barra de progreso por hitos (corregida) -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">0 años</span>
                                    <span class="small fw-bold" id="anios-actuales">49 años</span>
                                    <span class="small">60+ años</span>
                                </div>

                                <!-- Contenedor de la barra de progreso con marcadores de hitos -->
                                <div class="position-relative" style="height: 40px;">
                                    <!-- Barra de fondo -->
                                    <div class="progress w-100" style="height: 20px; position: absolute; top: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            id="barra-progreso-principal"
                                            style="width: 0%;"
                                            aria-valuenow="49" aria-valuemin="0" aria-valuemax="60">
                                        </div>
                                    </div>

                                    <!-- Marcadores de hitos (corregido) -->
                                    <div class="position-absolute w-100 d-flex justify-content-between px-1"
                                        style="top: 5px; height: 20px; pointer-events: none;">
                                        <!-- Hito 30 años - 50% de la barra -->
                                        <div class="d-flex flex-column align-items-center" style="margin-left:25%;">
                                            <div class="bg-white border border-primary rounded-circle"
                                                style="width: 12px; height: 12px; margin-top: 30px;"></div>
                                            <span class="small mt-1 text-primary fw-bold">30</span>
                                        </div>

                                        <!-- Hito 40 años - 66.66% de la barra -->
                                        <div class="d-flex flex-column align-items-center" style="margin-right: 4%;">
                                            <div class="bg-white border border-warning rounded-circle"
                                                style="width: 12px; height: 12px; margin-top: 30px;"></div>
                                            <span class="small mt-1 text-warning fw-bold">40</span>
                                        </div>

                                        <!-- Hito 50 años - 83.33% de la barra -->
                                        <div class="d-flex flex-column align-items-center" style="margin-right: 2%;">
                                            <div class="bg-white border border-success rounded-circle"
                                                style="width: 12px; height: 12px; margin-top: 30px;"></div>
                                            <span class="small mt-1 text-success fw-bold">50</span>
                                        </div>

                                        <!-- Hito 60 años - 100% de la barra -->
                                        <div class="d-flex flex-column align-items-center" style="margin-right: 0;">
                                            <div class="bg-white border border-info rounded-circle"
                                                style="width: 12px; height: 12px; margin-top: 30px;"></div>
                                            <span class="small mt-1 text-info fw-bold">60</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reconocimientos con indicador de elegibilidad -->
                    <!-- Reconocimiento 30 años -->
                    <div class="card mb-3 border-0" id="card-30-anos">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-7">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="reconocimiento-30-anos">
                                            <label class="form-check-label fw-semibold" for="reconocimiento-30-anos">
                                                Reconocimiento de 30 años
                                            </label>
                                        </div>
                                        <span class="badge bg-success-subtle text-success-emphasis elegibilidad-badge"
                                            id="elegibilidad-30">
                                            <i class=" me-1"></i>Elegible
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white" id="label-30-fecha">
                                            <i class="fa-regular fa-calendar"></i>
                                        </span>
                                        <input type="date" class="form-control"
                                            id="fecha-30-anos"
                                            aria-label="Fecha de otorgamiento"
                                            aria-describedby="label-30-fecha"
                                            max="<?php echo date('Y-m-d'); ?>"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reconocimiento 40 años -->
                    <div class="card mb-3 border-0" id="card-40-anos">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-7">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="reconocimiento-40-anos">
                                            <label class="form-check-label fw-semibold" for="reconocimiento-40-anos">
                                                Reconocimiento de 40 años
                                            </label>
                                        </div>
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis elegibilidad-badge"
                                            id="elegibilidad-40">
                                            <i class="fa-solid fa-hourglass-half me-1"></i>Faltan 11 años
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white" id="label-40-fecha">
                                            <i class="fa-regular fa-calendar"></i>
                                        </span>
                                        <input type="date" class="form-control"
                                            id="fecha-40-anos"
                                            aria-label="Fecha de otorgamiento"
                                            aria-describedby="label-40-fecha"
                                            max="<?php echo date('Y-m-d'); ?>"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reconocimiento 50 años -->
                    <div class="card mb-3 border-0" id="card-50-anos">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-7">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="reconocimiento-50-anos">
                                            <label class="form-check-label fw-semibold" for="reconocimiento-50-anos">
                                                Reconocimiento de 50 años
                                            </label>
                                        </div>
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis elegibilidad-badge"
                                            id="elegibilidad-50">
                                            <i class="fa-solid fa-hourglass-half me-1"></i>Faltan 21 años
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white" id="label-50-fecha">
                                            <i class="fa-regular fa-calendar"></i>
                                        </span>
                                        <input type="date" class="form-control"
                                            id="fecha-50-anos"
                                            aria-label="Fecha de otorgamiento"
                                            aria-describedby="label-50-fecha"
                                            max="<?php echo date('Y-m-d'); ?>"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reconocimiento 60 años -->
                    <div class="card mb-3 border-0" id="card-60-anos">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-7">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="reconocimiento-60-anos">
                                            <label class="form-check-label fw-semibold" for="reconocimiento-60-anos">
                                                Reconocimiento de 60 años
                                            </label>
                                        </div>
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis elegibilidad-badge"
                                            id="elegibilidad-60">
                                            <i class="fa-solid fa-hourglass-half me-1"></i>Faltan 31 años
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white" id="label-60-fecha">
                                            <i class="fa-regular fa-calendar"></i>
                                        </span>
                                        <input type="date" class="form-control"
                                            id="fecha-60-anos"
                                            aria-label="Fecha de otorgamiento"
                                            aria-describedby="label-60-fecha"
                                            max="<?php echo date('Y-m-d'); ?>"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="cerrar-modal-edit">Cerrar</button>
                    <button type="submit" class="btn btn-warning">Actualización</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal de Ver -->
<div class="modal modal-lg fade" id="modalShow" tabindex="-1" aria-labelledby="modalShowLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalShowLabel">
                    <i class="fa-solid fa-eye me-2"></i>
                    Ver reconocimientos otorgados
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Información del médico -->
                <div id="info-medico-show" class="mb-4"></div>

                <!-- Resumen de reconocimientos -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-primary-subtle">
                            <div class="card-body text-center">
                                <h6 class="card-title text-primary mb-2">
                                    <i class="fa-solid fa-trophy me-1"></i>
                                    Reconocimientos Recibidos
                                </h6>
                                <span class="display-6 fw-bold text-primary" id="recibidos-count">0</span>
                                <span class="text-primary-emphasis ms-2">otorgados</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-warning-subtle">
                            <div class="card-body text-center">
                                <h6 class="card-title text-warning mb-2">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    Años de Ejercicio
                                </h6>
                                <span class="display-6 fw-bold text-warning" id="anios-ejercicio-show">0</span>
                                <span class="text-warning-emphasis ms-2">años</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline de reconocimientos -->
                <div class="card mb-4 border-0">
                    <div class="card-body">
                        <h6 class="card-title fw-semibold mb-3">
                            <i class="fa-solid fa-chart-line me-2 text-secondary"></i>
                            Progresión de Reconocimientos
                        </h6>

                        <!-- Barra de progreso -->
                        <div class="mb-2 pb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">0 años</span>
                                <span class="small fw-bold" id="anios-actuales-show">0 años</span>
                                <span class="small">60+ años</span>
                            </div>
                            <div class="progress w-100" style="height: 20px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    id="barra-progreso-show"
                                    style="width: 0%;"
                                    aria-valuenow="49" aria-valuemin="0" aria-valuemax="60">
                                </div>
                            </div>
                            <!-- Marcadores de hitos (corregido) -->
                            <div class="position-absolute w-100 d-flex justify-content-between px-1"
                                style="top: 80px; height: 20px; pointer-events: none;">
                                <!-- Hito 30 años - 50% de la barra -->
                                <div class="d-flex flex-column align-items-center" style="margin-left:21%;">
                                    <div class="bg-white border border-primary rounded-circle"
                                        style="width: 12px; height: 12px; margin-top: 30px;"></div>
                                    <span class="small mt-1 text-primary fw-bold">30</span>
                                </div>

                                <!-- Hito 40 años - 66.66% de la barra -->
                                <div class="d-flex flex-column align-items-center" style="margin-left: 2%;">
                                    <div class="bg-white border border-warning rounded-circle"
                                        style="width: 12px; height: 12px; margin-top: 30px;"></div>
                                    <span class="small mt-1 text-warning fw-bold">40</span>
                                </div>

                                <!-- Hito 50 años - 83.33% de la barra -->
                                <div class="d-flex flex-column align-items-center" style="margin-right: 2%; margin-left: 2%;">
                                    <div class="bg-white border border-success rounded-circle"
                                        style="width: 12px; height: 12px; margin-top: 30px;"></div>
                                    <span class="small mt-1 text-success fw-bold">50</span>
                                </div>

                                <!-- Hito 60 años - 100% de la barra -->
                                <div class="d-flex flex-column align-items-center" style="margin-right: 6%;">
                                    <div class="bg-white border border-info rounded-circle"
                                        style="width: 12px; height: 12px; margin-top: 30px;"></div>
                                    <span class="small mt-1 text-info fw-bold">60</span>
                                </div>
                            </div>




                            <!-- <div class="d-flex justify-content-between mt-1">
                                <span class="badge bg-primary-subtle text-primary-emphasis">30 años</span>
                                <span class="badge bg-warning-subtle text-warning-emphasis">40 años</span>
                                <span class="badge bg-success-subtle text-success-emphasis">50 años</span>
                                <span class="badge bg-info-subtle text-info-emphasis">60 años</span>
                            </div> -->
                        </div>
                    </div>
                </div>



                <!-- Lista de reconocimientos -->
                <h6 class="fw-semibold mb-3">
                    <i class="fa-solid fa-list-check me-2 text-secondary"></i>
                    Detalle de Reconocimientos
                </h6>

                <!-- Reconocimiento 30 años -->
                <div class="card mb-3 border-start border-primary border-4" id="card-show-30">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary rounded-pill p-2">
                                        <i class="fa-solid fa-trophy"></i>
                                    </span>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">30 años</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span class="badge bg-light text-dark p-2 w-100" id="estado-show-30">
                                    <i class="fa-regular fa-hourglass me-1"></i>
                                    Cargando...
                                </span>
                            </div>
                            <div class="col-md-3 text-end">
                                <span class="fw-bold" id="fecha-show-30">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reconocimiento 40 años -->
                <div class="card mb-3 border-start border-warning border-4" id="card-show-40">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-warning rounded-pill p-2">
                                        <i class="fa-solid fa-trophy"></i>
                                    </span>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">40 años</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span class="badge bg-light text-dark p-2 w-100" id="estado-show-40">
                                    <i class="fa-regular fa-hourglass me-1"></i>
                                    Cargando...
                                </span>
                            </div>
                            <div class="col-md-3 text-end">
                                <span class="fw-bold" id="fecha-show-40">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reconocimiento 50 años -->
                <div class="card mb-3 border-start border-success border-4" id="card-show-50">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success rounded-pill p-2">
                                        <i class="fa-solid fa-trophy"></i>
                                    </span>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">50 años</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span class="badge bg-light text-dark p-2 w-100" id="estado-show-50">
                                    <i class="fa-regular fa-hourglass me-1"></i>
                                    Cargando...
                                </span>
                            </div>
                            <div class="col-md-3 text-end">
                                <span class="fw-bold" id="fecha-show-50">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reconocimiento 60 años -->
                <div class="card mb-3 border-start border-info border-4" id="card-show-60">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-info rounded-pill p-2">
                                        <i class="fa-solid fa-trophy"></i>
                                    </span>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">60 años</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span class="badge bg-light text-dark p-2 w-100" id="estado-show-60">
                                    <i class="fa-regular fa-hourglass me-1"></i>
                                    Cargando...
                                </span>
                            </div>
                            <div class="col-md-3 text-end">
                                <span class="fw-bold" id="fecha-show-60">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="cerrar-modal-show">
                    <i class="fa-regular fa-circle-xmark me-1"></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

</div>

<?php require_once "./public/views/layouts/footer.php"; ?>

<script>
    var dataD = JSON.parse(<?php echo $dataJ; ?>);
    var id_usuario = <?php echo $_SESSION['id_usuario']; ?>;
    var nivel_acceso = <?php echo $_SESSION['nivel_acceso']; ?>;
    // Permisos del usuario para JavaScript
    var puedeActualizar = <?php echo $puedeActualizar ? 'true' : 'false'; ?>;
</script>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/reconocimientos.js"></script>

<?php if (!empty($_SESSION['mensaje'])) {
    echo $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} ?>