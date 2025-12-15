<?php require_once "./public/views/layouts/header.php"; ?>

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
                                    <a href="/SIMA/subespecialidades-create">
                                        <button type="button" class="btn btn-success btn-sm align-middle">
                                            <i class="fa-solid fa-plus"></i> Nuevo
                                        </button>
                                    </a>
                                </div>
                                <div></div>
                                <div>
                                    <a href="/SIMA/subespecialidades-disable">
                                        <button type="button" class="btn btn-light btn-sm align-middle text-white" style="background-color: #052c65;">
                                            <i class="fa-solid fa-check"></i> Habilitar
                                        </button>
                                    </a>
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

        var data = [];
        var i = 0;
        dataD.forEach((elemento, index) => {
            console.log();
            acciones = `
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="/SIMA/subespecialidades-edit/${
                    elemento["id_subespecialidad"] 
                }"}>
                    <button type="button" class="btn btn-warning btn-sm">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </button>
                </a>
                <a href="/SIMA/subespecialidades-show/${
                    elemento["id_subespecialidad"] 
                }">
                    <button type="button" class="btn btn-info btn-sm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </a>
                <button type="button" class="btn btn-danger btn-sm" onclick="disable(${ elemento["id_subespecialidad"] });">
                    <i class="fa-solid fa-x"></i>
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
    });
</script>

<?php if (!empty($_SESSION['mensaje'])) {
    echo $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} ?>