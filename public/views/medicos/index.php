<?php require_once "./public/views/layouts/header.php"; ?>
<?php
use App\Config\PermisosHelper;

// Verificar permisos para mostrar/ocultar botones
$puedeRegistrar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_MEDICOS, PermisosHelper::REGISTRAR);
$puedeActualizar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_MEDICOS, PermisosHelper::ACTUALIZAR);
$puedeEliminar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_MEDICOS, PermisosHelper::ELIMINAR);
?>

<div class="conatiner-fluid content-inner py-3">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="row mb-4 align-items-center">
                <div class="col">
                    <h2 class="fw-bold text-primary">
                        <i class="fa-solid fa-user-doctor me-2"></i>Gestión de Médicos
                    </h2>
                </div>
                <div class="col-auto">
                    <?php if ($puedeRegistrar): ?>
                        <a href="/SIMA/medicos-create" class="btn btn-success btn-lg shadow-sm">
                            <i class="fa-solid fa-plus-circle me-2"></i>Registrar Médico
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0" style="width: auto !important;" id="tabla">
                    <thead>
                        <tr>
                            <th width="3%" scope="col" class="text-center bg-body-tertiary">#</th>
                            <th scope="col" class="text-center bg-body-tertiary">Nombres y Apellidos</th>
                            <th width="20%" scope="col" class="text-center bg-body-tertiary">N° de Colegio</th>
                            <th width="20%" scope="col" class="text-center bg-body-tertiary">Estado</th>
                            <th width="15%" scope="col" class="text-center bg-body-tertiary">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once "./public/views/layouts/footer.php"; ?>

<script>
    var dataD = JSON.parse(<?php echo $dataJ ?>);
    // Permisos del usuario para JavaScript
    var puedeActualizar = <?php echo $puedeActualizar ? 'true' : 'false'; ?>;
    var puedeEliminar = <?php echo $puedeEliminar ? 'true' : 'false'; ?>;
</script>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/medicos-index.js"></script>

<?php if (!empty($_SESSION['mensaje'])) {
    echo $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} ?>