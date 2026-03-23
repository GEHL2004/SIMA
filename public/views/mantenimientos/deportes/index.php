<?php require_once "./public/views/layouts/header.php"; 

use App\Config\PermisosHelper;

// Verificar permisos para mostrar/ocultar botones
$puedeRegistrar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_DEPORTES, PermisosHelper::REGISTRAR);
$puedeActualizar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_DEPORTES, PermisosHelper::ACTUALIZAR);
$puedeEliminar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_DEPORTES, PermisosHelper::ELIMINAR);
$puedeHabilitar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_DEPORTES, PermisosHelper::HABILITAR);

?>

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="text-center mb-2 mt-3">
                <h1 class="text-primary">Listado de Deportes</h1>
            </div>
            <div class="row justify-content-center mb-3">
                <div class="col-lg-10">
                    <div class="card mb-3">
                        <div class="card-body bg-light-subtle">
                            <div class="d-flex justify-content-end align-items-center">
                                <div>
                                    <button type="button" class="btn btn-success btn-sm align-middle" data-bs-toggle="modal" data-bs-target="#modalCreate" <?php echo $puedeRegistrar ? '' : 'disabled'; ?>>
                                        <i class="fa-solid fa-plus"></i> Nuevo
                                    </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-11 table-responsive">
                            <table id="tabla" class="table table-sm table-bordered mb-0" style="width: auto !important;">
                                <thead>
                                    <tr>
                                        <th width="3%" scope="col" class="text-center bg-body-tertiary">#</th>
                                        <th scope="col" class="text-center bg-body-tertiary">Nombre</th>
                                        <th scope="col" class="text-center bg-body-tertiary">Categoria</th>
                                        <th width="10%" class="text-center bg-body-tertiary" scope="col" id="columnaaccion2">Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Registro -->
    <div class="modal modal-lg fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true" novalidate>
        <form action="/SIMA/deportes-store" method="POST" id="form-store">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalCreateLabel">Registro de Deporte</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="nombre">Nombre:</label>
                                    <input type="text" class="form-control my-1" name="nombre" id="nombre-create">
                                </div>
                                <div class="mb-2">
                                    <label for="categoria">Categoria:</label>
                                    <select class="form-select my-1" name="categoria" id="categoria-create">
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="Individual">Individual</option>
                                        <option value="Colectivo">Colectivo</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="es_olimpico">Es olímpico:</label>
                                    <select class="form-select my-1" name="es_olimpico" id="es_olimpico-create">
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="popularidad">Popularidad:</label>
                                    <select class="form-select my-1" name="popularidad" id="popularidad-create">
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="Alta">Alta</option>
                                        <option value="Media">Media</option>
                                        <option value="Baja">Baja</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="deporte_nacional">Es deporte nacional:</label>
                                    <select class="form-select my-1" name="deporte_nacional" id="deporte_nacional-create">
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="cerrar-modal-create">Cerrar</button>
                        <button type="submit" class="btn btn-success">Registrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal de Actualización -->
    <div class="modal modal-lg fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <form action="/SIMA/deportes-update" method="POST" id="form-update">
            <input type="text" name="id-deporte" id="id-deporte-edit" hidden>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalEditLabel">Actualización de Deporte</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="nombre">Nombre:</label>
                                    <input type="text" class="form-control my-1" name="nombre" id="nombre-edit">
                                </div>
                                <div class="mb-2">
                                    <label for="categoria">Categoria:</label>
                                    <select class="form-select my-1" name="categoria" id="categoria-edit">
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="Individual">Individual</option>
                                        <option value="Colectivo">Colectivo</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="es_olimpico">Es olímpico:</label>
                                    <select class="form-select my-1" name="es_olimpico" id="es_olimpico-edit">
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="popularidad">Popularidad:</label>
                                    <select class="form-select my-1" name="popularidad" id="popularidad-edit">
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="Alta">Alta</option>
                                        <option value="Media">Media</option>
                                        <option value="Baja">Baja</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="deporte_nacional">Es deporte nacional:</label>
                                    <select class="form-select my-1" name="deporte_nacional" id="deporte_nacional-edit">
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                    </select>
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
                        <h1 class="modal-title fs-5" id="modalShowLabel">Ver Deporte</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="nombre">Nombre:</label>
                                    <input type="text" class="form-control my-1" id="nombre-show" disabled>
                                </div>
                                <div class="mb-2">
                                    <label for="categoria">Categoria:</label>
                                    <select class="form-select my-1" id="categoria-show" disabled>
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="Individual">Individual</option>
                                        <option value="Colectivo">Colectivo</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="es_olimpico">Es olímpico:</label>
                                    <select class="form-select my-1" id="es_olimpico-show" disabled>
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="popularidad">Popularidad:</label>
                                    <select class="form-select my-1" id="popularidad-show" disabled>
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="Alta">Alta</option>
                                        <option value="Media">Media</option>
                                        <option value="Baja">Baja</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="deporte_nacional">Es deporte nacional:</label>
                                    <select class="form-select my-1" id="deporte_nacional-show" disabled>
                                        <option value="" selected disabled>- Seleccione -</option>
                                        <option value="1">Si</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="cerrar-modal-show">Cerrar</button>
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
    var puedeEliminar = <?php echo $puedeEliminar ? 'true' : 'false'; ?>;
    var puedeHabilitar = <?php echo $puedeHabilitar ? 'true' : 'false'; ?>;
</script>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/deportes.js"></script>

<?php if (!empty($_SESSION['mensaje'])) {
    echo $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} ?>