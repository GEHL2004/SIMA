<?php require_once "./public/views/layouts/header.php"; ?>

<div class="container-fluid content-inner m-0 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 text-uppercase fw-bold">Resultados del Procesamiento</h2>
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver a cargar
        </a>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="p-3 border rounded bg-light text-center">
                <div class="text-muted small uppercase">Total Procesados</div>
                <div class="h3 fw-bold"><?php echo $resultados['resumen']['total_registros']; ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 border border-success rounded bg-success-subtle text-center text-success-emphasis">
                <div class="small uppercase">Registrados</div>
                <div class="h3 fw-bold"><?php echo $resultados['resumen']['medicos_registrados']; ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 border border-danger rounded bg-danger-subtle text-center text-danger-emphasis">
                <div class="small uppercase">No Registrados</div>
                <div class="h3 fw-bold"><?php echo $resultados['resumen']['medicos_no_registrados']; ?></div>
            </div>
        </div>
    </div>

    <?php if (!empty($resultados['registrados'])): ?>
    <div class="mb-5">
        <h5 class="text-success mb-3"><i class="bi bi-check-circle-fill me-2"></i>Médicos Registrados</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>RIF</th>
                        <th>Especialidad / Grado</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados['registrados'] as $medico): ?>
                    <tr>
                        <td class="fw-bold td-datatable"><?php echo $medico['data']['cedula']; ?></td>
                        <td class="td-datatable"><?php echo str_replace('_', ' ', $medico['data']['nombres']) . " " . str_replace('_', ' ', $medico['data']['apellidos']); ?></td>
                        <td class="td-datatable"><?php echo $medico['data']['rif']; ?></td>
                        <td class="td-datatable"><span class="badge text-bg-info"><?php echo $medico['data']['nombre_grado_academico']; ?></span></td>
                        <td class="td-datatable"><span class="badge text-bg-success">Éxito</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($resultados['no_registrados'])): ?>
    <div class="mb-5">
        <h5 class="text-danger mb-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>Registros Fallidos / Duplicados</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Motivo del Error</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados['no_registrados'] as $error): ?>
                    <tr>
                        <td class="text-danger fw-bold td-datatable"><?php echo $error['data']['cedula']; ?></td>
                        <td class="td-datatable"><?php echo str_replace('_', ' ', $error['data']['nombres']) . " " . str_replace('_', ' ', $error['data']['apellidos']); ?></td>
                        <td class="td-datatable">
                            <div class="text-danger small">
                                <strong><?php echo ucfirst($error['resultado']); ?>:</strong> 
                                <?php echo $error['mensaje']; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once "./public/views/layouts/footer.php"; ?>

<script>
    // Script básico para habilitar la validación visual de Bootstrap
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

<?php if (!empty($_SESSION['mensaje'])) {
    echo $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} ?>