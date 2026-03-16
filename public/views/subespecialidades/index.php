<?php require_once "./public/views/layouts/header.php"; ?>
<?php
use App\Config\PermisosHelper;

// Verificar permisos para mostrar/ocultar botones
$puedeRegistrar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_SUBESPECIALIDADES, PermisosHelper::REGISTRAR);
$puedeActualizar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_SUBESPECIALIDADES, PermisosHelper::ACTUALIZAR);
$puedeDeshabilitar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_SUBESPECIALIDADES, PermisosHelper::DESHABILITAR);
$puedeHabilitar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_SUBESPECIALIDADES, PermisosHelper::HABILITAR);
?>

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="text-center mb-2 mt-3">
                <h1 class="text-primary">Listado de Subespecialidades</h1>
            </div>

            <div class="row justify-content-center mb-3">
                <div class="col-lg-10">
                    <div class="card mb-3">
                        <div class="card-body bg-light-subtle">
                            <div class="d-flex justify-content-between align-items-center">
                                <div></div>
                                <div>
                                    <?php if ($puedeRegistrar): ?>
                                        <a href="/SIMA/subespecialidades-create">
                                            <button type="button" class="btn btn-success btn-sm align-middle">
                                                <i class="fa-solid fa-plus"></i> Nuevo
                                            </button>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div></div>
                                <div>
                                    <?php if ($puedeHabilitar): ?>
                                        <a href="/SIMA/subespecialidades-disable">
                                            <button type="button" class="btn btn-light btn-sm align-middle text-white" style="background-color: #052c65;">
                                                <i class="fa-solid fa-check"></i> Habilitar
                                            </button>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div></div>
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
                                    <th scope="col" class="text-center bg-body-tertiary">Código</th>
                                    <th width="10%" scope="col" class="text-center bg-body-tertiary">Estado</th>
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

<?php require_once "./public/views/layouts/footer.php"; ?>

<script>
    function disable(id_subespecialidad) {
        Swal.fire({
            icon: "question",
            title: "Está seguro de deshabilitar está subespecialidad?",
            showDenyButton: true,
            confirmButtonText: "Si",
            confirmButtonColor: "#28A745",
            denyButtonText: "No",
            reverseButtons: "true",
        }).then((respuesta) => {
            if (respuesta.isConfirmed) {
                // console.log("/SIMA/subespecialidades-disable/" + id_subespecialidad);
                window.location.href = "/SIMA/subespecialidades-disable/" + id_subespecialidad;
            }
        });
    };

    document.addEventListener('DOMContentLoaded', () => {

        var dataD = JSON.parse(<?php echo $dataJ; ?>);
        var id_usuario = <?php echo $_SESSION['id_usuario']; ?>;
        var nivel_acceso = <?php echo $_SESSION['nivel_acceso']; ?>;
        
        // Permisos del usuario para JavaScript
        var puedeActualizar = <?php echo $puedeActualizar ? 'true' : 'false'; ?>;
        var puedeDeshabilitar = <?php echo $puedeDeshabilitar ? 'true' : 'false'; ?>;

        var data = [];
        var i = 0;
        dataD.forEach((elemento, index) => {
            let acciones = '<div class="btn-group" role="group" aria-label="Basic example">';
            
            // Botón Editar
                acciones += `<a href="/SIMA/subespecialidades-edit/${elemento['id_subespecialidad']}" onclick="return ${puedeActualizar}" >
                    <button type="button" class="btn btn-warning btn-sm" ${puedeActualizar ? '' : 'disabled'} >
                        <i class="fa-regular fa-pen-to-square"></i>
                    </button>
                </a>`;
            
            // Botón Ver (siempre visible)
            acciones += `<a href="/SIMA/subespecialidades-show/${elemento['id_subespecialidad']}">
                <button type="button" class="btn btn-info btn-sm">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </a>`;
            
            // Botón Deshabilitar
                acciones += `<button type="button" class="btn btn-danger btn-sm" onclick="disable(${ elemento['id_subespecialidad'] });" ${elemento['conteo_de_medicos'] > 0 ? 'disabled' : ''} ${puedeDeshabilitar ? '' : 'disabled'}>
                    <i class="fa-solid fa-x"></i>
                </button>`;
            
            acciones += '</div>';

            data[i] = {
                contador: i + 1,
                Nombre: elemento["nombre"],
                Codigo: elemento["codigo"],
                Estado: elemento["activa"] == 1 ? 'Habilitada' : 'Deshabilitada',
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
                    data: "Estado",
                    title: "Estado",
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
    });
</script>

<?php if (!empty($_SESSION['mensaje'])) {
    echo $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} ?>