<?php include_once "./public/views/layouts/header.php"; ?>

<div class="conatiner-fluid content-inner py-0">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            
            <div class="text-center mb-2 mt-3">
                <h1 class="text-primary">Listado de Usuarios</h1>
            </div>

            <div class="row justify-content-center mb-2 mt-3">
                <div class="col-lg-10">
                    <div class="card mb-2 mt-3">
                        <div class="card-body bg-light-subtle">
                            <div class="d-flex justify-content-lg-start justify-content-center align-items-center">
                                <a href="/SIMA/usuarios-create">
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fa-solid fa-user-plus"></i> Nuevo
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="usuarios">
                <div class="row justify-content-center">
                    <div class="col-11 table-responsive">
                        <table id="tabla" class="table table-sm table-bordered mb-0" style="width: auto !important;">
                            <thead>
                                <tr>
                                    <th width="3%" scope="col" class="text-center bg-body-tertiary">#</th>
                                    <th scope="col" class="text-center bg-body-tertiary">Nombres y Apellidos</th>
                                    <th scope="col" class="text-center bg-body-tertiary">Usuario</th>
                                    <th width="15%" scope="col" class="text-center bg-body-tertiary">Nivel de Acceso</th>
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

<?php include_once "./public/views/layouts/footer.php"; ?>

<script>
    var dataD = JSON.parse(<?php echo $dataJ; ?>);
    var id_usuario = <?php echo $_SESSION['id_usuario']; ?>;
    var nivel_acceso = <?php echo $_SESSION['nivel_acceso']; ?>;
</script>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/indexUsuarios.js"></script>

<?php if (!empty($_SESSION['mensaje'])) {
    echo $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} ?>