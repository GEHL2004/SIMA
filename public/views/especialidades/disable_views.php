<?php require_once "./public/views/layouts/header.php"; ?>

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <div class="row justify-content-center">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-1 mb-3 mb-lg-0">
                        <a href="/SIMA/especialidades">
                            <button type="button" class="btn btn-sm btn-primary btn-lock" id="botonregresar">
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                        </a>
                    </div>
                    <div class="col-lg-9 mb-4">
                        <h1 class="text-center text-primary"> Especialidades Deshabilitadas</h1>
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
        function enable(id_especialidad) {
            Swal.fire({
                icon: "question",
                title: "Está seguro de habilitar está especialidad?",
                showDenyButton: true,
                confirmButtonText: "Si",
                confirmButtonColor: "#28A745",
                denyButtonText: "No",
                reverseButtons: "true",
            }).then((respuesta) => {
                if (respuesta.isConfirmed) {
                    window.location.href = "/SIMA/especialidades-enable/" + id_especialidad;
                }
            });
        };

        function eliminar(id_especialidad) {
            Swal.fire({
                icon: "question",
                title: "Está seguro de eliminar está especialidad?",
                showDenyButton: true,
                confirmButtonText: "Si",
                confirmButtonColor: "#28A745",
                denyButtonText: "No",
                reverseButtons: "true",
            }).then((respuesta) => {
                if (respuesta.isConfirmed) {
                    window.location.href = "/SIMA/especialidades-delete/" + id_especialidad;
                }
            });
        };

        document.addEventListener('DOMContentLoaded', () => {

            var dataD = JSON.parse(<?php echo $dataJ; ?>);
            var id_usuario = <?php echo $_SESSION['id_usuario']; ?>;
            var nivel_acceso = <?php echo $_SESSION['nivel_acceso']; ?>;

            var data = [];
            var i = 0;
            dataD.forEach((elemento, index) => {
                acciones = `
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-success btn-sm" onclick="enable(${ elemento["id_especialidad"] });">
                    <i class="fa-solid fa-check"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminar(${ elemento["id_especialidad"] });">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>`;

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