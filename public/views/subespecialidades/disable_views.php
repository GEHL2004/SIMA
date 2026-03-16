<?php require_once "./public/views/layouts/header.php"; ?>
<?php
use App\Config\PermisosHelper;

// Verificar permisos para mostrar/ocultar botones
$puedeHabilitar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_SUBESPECIALIDADES, PermisosHelper::HABILITAR);
$puedeEliminar = PermisosHelper::tienePermiso(PermisosHelper::MODULO_SUBESPECIALIDADES, PermisosHelper::ELIMINAR);
?>

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <div class="row justify-content-center">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-1 mb-3 mb-lg-0">
                        <a href="/SIMA/subespecialidades">
                            <button type="button" class="btn btn-sm btn-primary btn-lock" id="botonregresar">
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                        </a>
                    </div>
                    <div class="col-lg-9 mb-4">
                        <h1 class="text-center text-primary">Subespecialidades Deshabilitadas</h1>
                    </div>
                    <div class="col-lg-1"></div>
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
        function enable(id_subespecialidad) {
            Swal.fire({
                icon: "question",
                title: "Está seguro de habilitar está subespecialidad?",
                showDenyButton: true,
                confirmButtonText: "Si",
                confirmButtonColor: "#28A745",
                denyButtonText: "No",
                reverseButtons: "true",
            }).then((respuesta) => {
                if (respuesta.isConfirmed) {
                    window.location.href = "/SIMA/subespecialidades-enable/" + id_subespecialidad;
                }
            });
        };

        function eliminar(id_subespecialidad) {
            Swal.fire({
                icon: "question",
                title: "Está seguro de eliminar está subespecialidad?",
                showDenyButton: true,
                confirmButtonText: "Si",
                confirmButtonColor: "#28A745",
                denyButtonText: "No",
                reverseButtons: "true",
            }).then((respuesta) => {
                if (respuesta.isConfirmed) {
                    window.location.href = "/SIMA/subespecialidades-delete/" + id_subespecialidad;
                }
            });
        };

        document.addEventListener('DOMContentLoaded', () => {

            var dataD = JSON.parse(<?php echo $dataJ; ?>);
            var id_usuario = <?php echo $_SESSION['id_usuario']; ?>;
            var nivel_acceso = <?php echo $_SESSION['nivel_acceso']; ?>;
            
            // Permisos del usuario para JavaScript
            var puedeHabilitar = <?php echo $puedeHabilitar ? 'true' : 'false'; ?>;
            var puedeEliminar = <?php echo $puedeEliminar ? 'true' : 'false'; ?>;

            var data = [];
            var i = 0;
            dataD.forEach((elemento, index) => {
                let acciones = '<div class="btn-group" role="group" aria-label="Basic example">';
                
                // Botón Habilitar
                if (puedeHabilitar) {
                    acciones += `<button type="button" class="btn btn-success btn-sm" onclick="enable(${ elemento['id_subespecialidad'] });">
                        <i class="fa-solid fa-check"></i>
                    </button>`;
                }
                
                // Botón Eliminar
                if (puedeEliminar) {
                    acciones += `<button type="button" class="btn btn-danger btn-sm" onclick="eliminar(${ elemento['id_subespecialidad'] });">
                        <i class="fa-solid fa-trash"></i>
                    </button>`;
                }
                
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
        })
    </script>

    <?php if (!empty($_SESSION['mensaje'])) {
        echo $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
    } ?>