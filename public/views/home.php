<?php require_once "./public/views/layouts/header.php"; ?>

<link rel="stylesheet" href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/home.css">

<!-- Main Content -->
<div class="main-content mb-5" id="mainContentt">
    <!-- Main Dashboard Content -->
    <div class="container-fluid mt-4">
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card stat-card border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Médicos</h6>
                                <h3 class="mb-0" id="totalMedicos">0</h3>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-user-md"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card stat-card border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Especialidades</h6>
                                <h3 class="mb-0" id="totalEspecialidades">0</h3>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card stat-card border-start border-warning border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Subespecialidades</h6>
                                <h3 class="mb-0" id="totalSubespecialidades">0</h3>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- More Charts and Info -->
        <div class="row g-4 mt-4 mb-5">
            <!-- Recent Activity -->
            <div class="col-lg-4">
                <div class="chart-container">
                    <h5 class="mb-4">Actividad Reciente</h5>
                    <div class="recent-activity" id="recentActivity">
                        <!-- Las actividades se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </div>
            <!-- Chart 2: Médicos por Especialidad -->
            <div class="col-lg-4">
                <div class="chart-container">
                    <h5 class="mb-4">Top 5 Especialidades con más Médicos</h5>
                    <canvas id="medicosPorEspecialidadChart"></canvas>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="col-lg-4">
                <div class="chart-container h-100">
                    <h5 class="mb-4">Estados de Médicos</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success rounded-circle me-3" style="width: 15px; height: 15px;"></div>
                        <div class="flex-grow-1">
                            <small>Activos</small>
                        </div>
                        <span class="badge bg-success" id="medicosActivos">0</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle me-3" style="width: 15px; height: 15px;"></div>
                        <div class="flex-grow-1">
                            <small>Desincorporados</small>
                        </div>
                        <span class="badge bg-primary" id="medicosDesincorporados">0</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning rounded-circle me-3" style="width: 15px; height: 15px;"></div>
                        <div class="flex-grow-1">
                            <small>Jubilados</small>
                        </div>
                        <span class="badge bg-warning" id="medicosJubilados">0</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger rounded-circle me-3" style="width: 15px; height: 15px;"></div>
                        <div class="flex-grow-1">
                            <small>Fallecidos</small>
                        </div>
                        <span class="badge bg-danger" id="medicosFallecidos">0</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info rounded-circle me-3" style="width: 15px; height: 15px;"></div>
                        <div class="flex-grow-1">
                            <small>Traslado</small>
                        </div>
                        <span class="badge bg-info" id="medicosTraslado">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="row g-4">
            <!-- Chart 1: Médicos por Municipio -->
            <div class="col-lg-12">
                <div class="chart-container">
                    <h5 class="mb-4">Distribución de Médicos por Municipio</h5>
                    <canvas id="medicosPorEstadoChart"></canvas>
                </div>
            </div>
        </div>



    </div>
</div>

<?php require_once "./public/views/layouts/footer.php"; ?>

<script>
    // Datos de ejemplo para las gráficas y estadísticas
    const dashboardData = JSON.parse(<?php echo $dashboardDataJSON; ?>);
</script>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/home.js"></script>