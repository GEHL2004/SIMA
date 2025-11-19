<?php include_once "./public/views/layouts/header.php"; ?>

<style>
    .card {
        --bs-card-cap-padding-y: 0;
        --bs-card-cap-padding-x: 0;
    }

    .card-header{
        padding: 5px !important;
    }
</style>

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <h1 class="mb-5 text-center fw-bolder text-primary">Monitoreo de Base de Datos</h1>

            <!-- Panel de Resumen (Cards) -->
            <div class="row mb-4">
                <!-- Card de Conexión -->
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 card-status">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-database me-2"></i>Información de Conexión
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Servidor:</span>
                                <span id="servidor"></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Base de datos:</span>
                                <span id="base_datos"></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Driver:</span>
                                <span id="driver"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de Estado del Servidor -->
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 card-status">
                        <div class="card-header text-white" id="EstadoTitulo">
                            <i class="fas fa-server me-2"></i>Estado del Servidor
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center mb-3">
                                <div class="status-icon text-black">
                                    <i id="estado-servidor-icon" class="fas"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Versión:</span>
                                <span id="version_servidor"></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Estado:</span>
                                <span id="estado_servidor" class="rounded-pill text-white px-2 py-1"></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Timestamp:</span>
                                <span id="timestamp_servidor"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de Estado de la BD -->
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 card-status">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-chart-bar me-2"></i>Estado de la Base de Datos
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center mb-3">
                                <div class="status-icon text-info">
                                    <i class="fas fa-table"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Tamaño total:</span>
                                <span id="tamaño_total_mb"></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total de tablas:</span>
                                <span id="total_tablas"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Controles de Actualización -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <i class="fas fa-cog me-2"></i>Controles de Actualización
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <button id="btnActualizar" class="btn btn-primary">
                                        <span id="spinner" class="spinner-container d-none">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        </span>
                                        <i class="fas fa-arrows-rotate me-2"></i>Actualizar Ahora
                                    </button>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="row">
                                        <div class="col-8">
                                            <label for="intervaloActualizacion" class="form-label">Intervalo de actualización:</label>
                                            <select id="intervaloActualizacion" class="form-select">
                                                <option value="1">Tiempo real (1s)</option>
                                                <option value="60">Cada minuto (1m)</option>
                                                <option value="300" selected>Cada 5 minutos (5m)</option>
                                                <option value="900">Cada 15 minutos (15m)</option>
                                                <option value="3600">Cada hora (1h)</option>
                                                <option value="43200">Cada 12 horas (12h)</option>
                                                <option value="custom">Personalizado</option>
                                            </select>
                                        </div>
                                        <div id="customInputContainer" class="col-4 custom-input-container">
                                            <label for="customSeconds" class="form-label">Segundos:</label>
                                            <input type="number" class="form-control" id="customSeconds" min="1" max="43200" value="60" onkeyup="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Tablas (Pestañas) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tablas-tab" data-bs-toggle="tab" data-bs-target="#tablas" type="button" role="tab" aria-controls="tablas" aria-selected="true">
                                        <i class="fas fa-table me-2"></i>Monitoreo de Tablas
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="procesos-tab" data-bs-toggle="tab" data-bs-target="#procesos" type="button" role="tab" aria-controls="procesos" aria-selected="false">
                                        <i class="fas fa-tasks me-2"></i>Procesos Activos
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="variables-tab" data-bs-toggle="tab" data-bs-target="#variables" type="button" role="tab" aria-controls="variables" aria-selected="false">
                                        <i class="fas fa-cogs me-2"></i>Variables del Servidor
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <!-- Tab de Monitoreo de Tablas -->
                                <div class="tab-pane fade show active" id="tablas" role="tabpanel" aria-labelledby="tablas-tab">
                                    <table id="tablasMonitoreo" class="table table-striped table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 30px;"></th>
                                                <th class="text-center">Nombre de Tabla</th>
                                                <th class="text-center">Cantidad de Filas</th>
                                                <th class="text-center">Tamaño de Datos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Se carga dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tab de Procesos Activos -->
                                <div class="tab-pane fade" id="procesos" role="tabpanel" aria-labelledby="procesos-tab">
                                    <table id="tablaProcesos" class="table table-striped table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Id</th>
                                                <th class="text-center">Usuario</th>
                                                <th class="text-center">Host</th>
                                                <th class="text-center">Base de Datos</th>
                                                <th class="text-center">Comando</th>
                                                <th class="text-center">Tiempo</th>
                                                <th class="text-center">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody class="align-middle">
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tab de Variables del Servidor -->
                                <div class="tab-pane fade" id="variables" role="tabpanel" aria-labelledby="variables-tab">
                                    <table id="tablaVariables" class="table table-striped table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nombre de Variable</th>
                                                <th class="text-center">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody class="align-middle">
                                            <!-- Se carga dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "./public/views/layouts/footer.php"; ?>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/monitorDB.js"></script>

<script>
    // Datos mock para simular la respuesta de la API
    var mockData = JSON.parse(<?php echo json_encode(json_encode($data)); ?>);
    var temporizador = setInterval(actualizarDatos, 300000);
</script>