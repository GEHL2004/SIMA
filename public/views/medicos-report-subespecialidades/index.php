<?php require_once "./public/views/layouts/header.php"; ?>

<div class="conatiner-fluid content-inner py-0 romper-text">
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <div class="row">
                <div class="text-center mb-3 mt-2">
                    <h1 class="text-primary">Consulta de Medicos por Subespecialidad</h1>
                </div>
            </div>

            <!-- Header del Reporte -->
            <div class="d-flex justify-content-between align-items-center p-4 mb-4 bg-light-subtle">
                <div>
                    <p style="font-size: 0.95rem; color: black; margin: 5px 0 0 0;">
                        Especialidad:
                        <select class="form-select" id="select-subespecialidades" onchange="filtrado_subespecialidades();">
                            <option value="" selected disabled>- Seleccione -</option>
                            <?php foreach ($subespecialidades as $i => $subespecialidad) { ?>
                                <option value="<?= $subespecialidad['id_subespecialidad']; ?>"><?= $subespecialidad['nombre']; ?></option>
                            <?php } ?>
                        </select>
                    </p>
                </div>
                <button class="btn btn-danger d-flex align-items-center" style="background-color: #dc3545; border: none; padding: 10px 20px; font-weight: 500;" onclick="descargarPDF()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16" style="margin-right: 8px;">
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.218.385.244.684.024.278.015.616-.105.92-.136.332-.41.616-.824.803-.414.188-.879.238-1.348.118-.386-.077-.744-.216-1.048-.496a5.712 5.712 0 0 1-.67-.639 11.744 11.744 0 0 0-1.152 1.213 2.11 2.11 0 0 1-.49.469c-.173.12-.36.21-.56.253zm2.177-.472a12.696 12.696 0 0 1-.71-.257 5.714 5.714 0 0 0-.42-.127 2.101 2.101 0 0 0-.673-.067 2.1 2.1 0 0 0-.496.069 1.38 1.38 0 0 0-.44.176.8.8 0 0 0-.3.355c-.049.111-.07.227-.07.295 0 .115.036.22.106.293.07.073.165.11.28.11.143 0 .223-.048.351-.159.127-.11.289-.256.484-.445.181-.17.36-.347.535-.531.13-.139.264-.283.397-.432.044 1 .154 1.948.429 2.83zm2.245-2.235a5.697 5.697 0 0 0-.394.337c-.142.123-.265.24-.37.357a5.613 5.613 0 0 0-.744 1.06 5.152 5.152 0 0 0-.086.345c-.028.14-.04.28-.04.41 0 .21.044.392.146.534.102.142.248.242.44.3.188.057.39.068.6.033.207-.035.396-.116.558-.242.161-.126.29-.299.387-.514.095-.215.14-.452.14-.71 0-.331-.07-.604-.207-.82-.138-.214-.33-.38-.575-.498.21-.186.407-.381.592-.584l.034-.03c.157-.16.312-.324.465-.492a6.264 6.264 0 0 0 1.06-.97l.04-.045z" />
                    </svg>
                    Descargar PDF
                </button>
            </div>

            <div class="row justify-content-center m-1">
                <div class="col-12 bd-example table-responsive" id="contenedorTabla">
                    <table id="tabla" class="table table-bordered table-sm mb-0" style="width:100%">
                        <thead class="align-middle">
                            <tr>
                                <th width="3%" class="text-center bg-body-tertiary">#</th>
                                <th width="13%" class="text-center bg-body-tertiary">Cédula</th>
                                <th width="13%" class="text-center bg-body-tertiary">N° Colégio</th>
                                <th class="text-center bg-body-tertiary">Nombres y Apellidos</th>
                                <th class="text-center bg-body-tertiary">Teléfono</th>
                                <th class="text-center bg-body-tertiary">Correo</th>
                                <th class="text-center bg-body-tertiary">Dirección</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once "./public/views/layouts/footer.php"; ?>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/medicos-subespecialidades.js"></script>