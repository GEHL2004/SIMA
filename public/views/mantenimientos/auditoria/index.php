<?php require_once "./public/views/layouts/header.php"; ?>

<div class="conatiner-fluid content-inner py-0 romper-text">
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <div class="row">
                <div class="text-center mb-3 mt-2">
                    <h1 class="text-primary">Auditoría de CMA</h1>
                </div>
            </div>
            <div class="row justify-content-center mb-3">
                <div class="col-lg-12">
                    <div class="card mb-3">
                        <div class="card-body bg-light-subtle">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                </div>
                                <div class="col-5">
                                    <div class="input-group" id="tipofiltrado">
                                        <span class="input-group-text">Tipo de Filtrado:</span>
                                        <select class="form-select bg-body-secondary" id="tipo_filtrado" onchange="crearCampos();">
                                            <option value="0" selected>General</option>
                                            <option value="1">Por usuario</option>
                                            <option value="2">Por rango de fechas</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center" id="contenedor">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center m-1">
                <div class="col-12 bd-example table-responsive" id="contenedorTabla">
                    <table id="tabla" class="table table-bordered table-sm mb-0" style="width:100%">
                        <thead class="align-middle">
                            <tr>
                                <th width="3%" class="text-center bg-body-tertiary">#</th>
                                <th width="10%" class="text-center bg-body-tertiary">Fecha</th>
                                <th width="10%" class="text-center bg-body-tertiary">Hora</th>
                                <th width="13%" class="text-center bg-body-tertiary">Usuario</th>
                                <th class="text-center bg-body-tertiary">Acción</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>

<?php require_once "./public/views/layouts/footer.php"; ?>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/filtrosAuditoria.js"></script>

<script>
    fetch(`/SIMA/auditoria-filtrar-general`)
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            let dataD = JSON.parse(data);
            // console.log(dataD);
            let i = 0;
            dataD.forEach(element => {
                dataD[i]['contador'] = (i + 1);
                i++;
            });
            const datos = {
                'id_tabla': '#tabla',
                'data': dataD,
                'columns': [{
                        'data': 'contador',
                        'title': '#',
                        'className': 'text-center'
                    },
                    {
                        'data': 'fecha',
                        'title': 'Fecha',
                        'className': 'text-center'
                    },
                    {
                        'data': 'hora',
                        'title': 'Hora',
                        'className': 'text-center'
                    },
                    {
                        'data': 'nombre_user',
                        'title': 'Usuario',
                        'className': 'text-center'
                    },
                    {
                        'data': 'accion',
                        'title': 'Acción',
                        'className': 'justify-description td-datatable'
                    }
                ]
            };

            // console.log(datos);

            cargar_tabla(datos);
        })
        .catch(error => {
            console.error(error);
        });
</script>