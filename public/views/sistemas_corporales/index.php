<?php require_once "./public/views/layouts/header.php"; ?>
<?php
use App\Config\PermisosHelper;

// Verificar permisos para mostrar/ocultar botones
$puedeRegistrar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_SISTEMAS_CORPORALES, PermisosHelper::REGISTRAR);
$puedeActualizar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_SISTEMAS_CORPORALES, PermisosHelper::ACTUALIZAR);
$puedeEliminar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_SISTEMAS_CORPORALES, PermisosHelper::ELIMINAR);
?>

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="text-center mb-2 mt-3">
                <h1 class="text-primary">Listado de Sistemas Corporales</h1>
            </div>

            <div class="row justify-content-center mb-2 mt-3">
                <div class="col-lg-10">
                    <div class="card mb-2 mt-3">
                        <div class="card-body bg-light-subtle">
                            <div class="d-flex justify-content-lg-start justify-content-center align-items-center">
                            <?php if ($puedeRegistrar): ?>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalRegistro">
                                    <i class="fa-solid fa-user-plus"></i> Añadir Sistema Corporal
                                </button>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="Sistemas-Corporales">
                <div class="row justify-content-center">
                    <div class="col-11 table-responsive">
                        <table id="tabla" class="table table-sm table-bordered mb-0" style="width: auto !important;">
                            <thead>
                                <tr>
                                    <th width="3%" scope="col" class="text-center bg-body-tertiary">#</th>
                                    <th class="text-center bg-body-tertiary" scope="col">Nombre</th>
                                    <th width="10%" class="text-center bg-body-tertiary" scope="col">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal para registrar un sistema corporal -->

            <div class="modal fade" id="ModalRegistro" tabindex="-1" aria-labelledby="ModalRegistroLabel" aria-hidden="true">
                <form action="/SIMA/sistemas-corporales-store" method="POST" id="form-store">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="ModalRegistroLabel">Registro del Sistema Corporal</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <label for="nombre" class="mb-1">Nombre del Sistema Corporal:</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre-store" placeholder="Sistema Corporal X....">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <label for="nombre" class="mb-1">Descripción del Sistema Corporal:</label>
                                        <textarea class="form-control" name="descripcion" id="descripcion-store" cols="1" rows="5" style="resize: none;" maxlength="500" placeholder="Descripción X...."></textarea>
                                        <div style="text-align: right; font-size: 0.85em; color: #6c757d; margin-top: 5px;">
                                            <span id="contador-store">0</span>/500 caracteres
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="submit" class="btn btn-success btn-sm">Registrar Sistema Corporal</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal para actualizar el sistema corporal -->

            <div class="modal fade" id="ModalActualizacion" tabindex="-1" aria-labelledby="ModalActualizacionLabel" aria-hidden="true">
                <form action="/SIMA/sistemas-corporales-update" method="POST" id="form-update">
                    <input type="text" name="id_sistema_corporal" id="id_sistema_corporal" hidden>
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="ModalRegistroLabel">Actualización del Sistema Corporal</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <label for="nombre" class="mb-1">Nombre del Sistema Corporal:</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre-update" placeholder="Sistema Corporal X....">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <label for="nombre" class="mb-1">Descripción del Sistema Corporal:</label>
                                        <textarea class="form-control" name="descripcion" id="descripcion-update" cols="1" rows="5" style="resize: none;" maxlength="500" placeholder="Descripción X...."></textarea>
                                        <div style="text-align: right; font-size: 0.85em; color: #6c757d; margin-top: 5px;">
                                            <span id="contador-update">0</span>/500 caracteres
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="submit" class="btn btn-warning btn-sm">Actualizar Sistema Corporal</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal de ver información del sistema corporal -->

            <div class="modal fade" id="ModalVer" tabindex="-1" aria-labelledby="ModalVerLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="ModalRegistroLabel">Información del Sistema Corporal</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <label for="nombre" class="mb-1">Nombre del Sistema Corporal:</label>
                                    <input type="text" class="form-control" id="nombre-ver" disabled>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <label for="nombre" class="mb-1">Descripción del Sistema Corporal:</label>
                                    <textarea class="form-control" id="descripcion-ver" cols="1" rows="5" style="resize: none;" disabled></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once "./public/views/layouts/footer.php"; ?>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/sistemas_corporales.js"></script>

<script>
    var dataD = JSON.parse(<?php echo $dataJ ?>);
    // Permisos del usuario para JavaScript
    var puedeActualizar = <?php echo $puedeActualizar ? 'true' : 'false'; ?>;
    var puedeEliminar = <?php echo $puedeEliminar ? 'true' : 'false'; ?>;
    // Carga de la tabla index
    var data = [];
    var i = 0;
    dataD.forEach((elemento, index) => {
        let acciones = '<div class="btn-group" role="group" aria-label="Basic example">';
        
        // Botón Editar
            acciones += `<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#ModalActualizacion" id-sistema-corporal="${elemento['id_sistema_corporal']}" nombre="${elemento['nombre']}" descripcion="${elemento['descripcion']}" ${puedeActualizar ? '' : 'disabled'}>
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>`;
        
        // Botón Ver (siempre visible)
        acciones += `<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ModalVer" nombre="${elemento['nombre']}" descripcion="${elemento['descripcion']}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>`;
        
        // Botón Eliminar
            acciones += `<button type="button" class="btn btn-danger btn-sm" onclick="eliminar(${elemento['id_sistema_corporal']})" ${puedeEliminar ? '' : 'disabled'}>
                    <i class="fa-solid fa-trash"></i>
                </button>`;
        
        acciones += '</div>';

        data[i] = {
            contador: i + 1,
            Nombre: elemento["nombre"],
            Acciones: acciones,
        };
        i++;
    });

    const datos = {
        id_tabla: "#tabla",
        data: data,
        columns: [{
                data: "contador",
                title: "#",
                className: "text-center",
            },
            {
                data: "Nombre",
                title: "Nombre",
                className: "text-center td-datatable",
            },
            {
                data: "Acciones",
                title: "Acciones",
                className: "text-center",
            },
        ],
    };

    cargar_tabla(datos);
</script>

<?php if (!empty($_SESSION['mensaje'])) {
    echo $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} ?>