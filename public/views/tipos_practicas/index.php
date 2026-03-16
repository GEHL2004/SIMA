<?php require_once "./public/views/layouts/header.php"; ?>
<?php

use App\Config\PermisosHelper;

// Verificar permisos para mostrar/ocultar botones
$puedeRegistrar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_TIPOS_PRACTICA, PermisosHelper::REGISTRAR);
$puedeActualizar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_TIPOS_PRACTICA, PermisosHelper::ACTUALIZAR);
$puedeEliminar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_TIPOS_PRACTICA, PermisosHelper::ELIMINAR);
?>

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="text-center mb-2 mt-3">
                <h1 class="text-primary">Listado de Tipos de Practica</h1>
            </div>

            <div class="row justify-content-center mb-2 mt-3">
                <div class="col-lg-10">
                    <div class="card mb-2 mt-3">
                        <div class="card-body bg-light-subtle">
                            <div class="d-flex justify-content-lg-start justify-content-center align-items-center">
                                <?php if ($puedeRegistrar): ?>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalRegistro">
                                        <i class="fa-solid fa-user-plus"></i> Añadir Tipo de Practica
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="tipos-de-practica">
                <div class="row justify-content-center">
                    <div class="col-11 table-responsive">
                        <table id="tabla" class="table table-sm table-bordered mb-0" style="width: auto !important;">
                            <thead>
                                <tr>
                                    <th width="3%" scope="col" class="text-center bg-body-tertiary">#</th>
                                    <th class="text-center bg-body-tertiary" scope="col">Nombre</th>
                                    <th class="text-center bg-body-tertiary" scope="col">Código</th>
                                    <th width="10%" class="text-center bg-body-tertiary" scope="col">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal para registrar una tipos de practicas -->

            <div class="modal fade" id="ModalRegistro" tabindex="-1" aria-labelledby="ModalRegistroLabel" aria-hidden="true">
                <form action="/SIMA/tipos-practicas-store" method="POST" id="form-store">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="ModalRegistroLabel">Registro de la Tipo de Practica</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <label for="nombre" class="mb-1">Nombre de la Tipo de Practica:</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre-store" placeholder="Tipo de Practica X....">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <label for="nombre" class="mb-1">Código de la Tipo de Practica:</label>
                                        <input type="text" class="form-control" name="codigo" id="codigo-store" maxlength="10" placeholder="Cod X....">
                                        <div style="text-align: right; font-size: 0.85em; color: #6c757d; margin-top: 5px;">
                                            <span id="contador-store">0</span>/10 caracteres
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="submit" class="btn btn-success btn-sm">Registrar Tipo de Practica</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal para actualizar la tipos de practicas -->

            <div class="modal fade" id="ModalActualizacion" tabindex="-1" aria-labelledby="ModalActualizacionLabel" aria-hidden="true">
                <form action="/SIMA/tipos-practicas-update" method="POST" id="form-update">
                    <input type="text" name="id_tipo_practica" id="id_tipo_practica">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="ModalRegistroLabel">Actualización de la Tipo de Practica</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <label for="nombre" class="mb-1">Nombre de la Tipo de Practica:</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre-update" placeholder="Tipo de Practica X....">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <label for="nombre" class="mb-1">Código de la Tipo de Practica:</label>
                                        <input type="text" class="form-control" name="codigo" id="codigo-update" maxlength="10" placeholder="Cod X....">
                                        <div style="text-align: right; font-size: 0.85em; color: #6c757d; margin-top: 5px;">
                                            <span id="contador-update">0</span>/10 caracteres
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="submit" class="btn btn-warning btn-sm">Actualizar Tipo de Practica</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal de ver información de la tipos de practicas -->

            <div class="modal fade" id="ModalVer" tabindex="-1" aria-labelledby="ModalVerLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="ModalRegistroLabel">Información de la Tipo de Practica</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <label for="nombre" class="mb-1">Nombre de la Tipo de Practica:</label>
                                    <input type="text" class="form-control" id="nombre-ver" disabled>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <label for="nombre" class="mb-1">Código de la Tipo de Practica:</label>
                                    <input type="text" class="form-control" id="codigo-ver" disabled>
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

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/tipos_practicas.js"></script>

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
        acciones += `<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#ModalActualizacion" id-tipo-practica="${elemento['id_tipo_practica']}" nombre="${elemento['nombre']}" codigo="${elemento['codigo']}" ${puedeActualizar ? '' : 'disabled'}>
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>`;

        // Botón Ver (siempre visible)
        acciones += `<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ModalVer" nombre="${elemento['nombre']}" codigo="${elemento['codigo']}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>`;

        // Botón Eliminar
        acciones += `<button type="button" class="btn btn-danger btn-sm" onclick="eliminar(${elemento['id_tipo_practica']})" ${puedeEliminar ? '' : 'disabled'}>
                    <i class="fa-solid fa-trash"></i>
                </button>`;

        acciones += '</div>';

        data[i] = {
            contador: i + 1,
            Nombre: elemento["nombre"],
            Codigo: elemento["codigo"],
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
                data: "Codigo",
                title: "Código",
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