<?php include_once "./public/views/layouts/header.php"; ?>

<div class="conatiner-fluid content-inner py-0">
  <div class="row">
    <div class="col-md-12 col-lg-12">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h1 class="mb-4 text-primary">Servicios de Base de datos</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10 p-7 rounded h-25 text-center">
          <div class="d-lg-flex align-items-lg-start align-items-center">
            <div class="nav flex-lg-column nav-pills me-3 p-3 rounded h-25 mb-5 justify-content-center" style="background-color: #f1f1f1;" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                Respaldar
              </button>
              <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                <i class="fa-solid fa-file-import"></i>
                Restaurar
              </button>
              <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                <i class="fa-solid fa-arrows-rotate"></i>
                Restablecer
              </button>
            </div>

            <div class="col w-lg-0 w-100"></div>

            <div class="tab-content p-4 rounded h-25 " id="v-pills-tabContent" style="background-color: #f1f1f1;">

              <div class="tab-pane fade show active " id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                <div class="input-group justify-content-center mb-3">
                  <img class="me-2" width="52" height="52" src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/respaldar.png" class="img-fluid rounded-top" alt="">
                  <h2> Respaldar</h2>
                </div>
                <label class="text-break text-start mb-5 mt-4">Es ejecutar un archivo, en el será una copia de seguridad digital donde contenga los datos almacenados del sistema SISPRE.</label>
                <div class="row">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-5 p-3 rounded h-25 mb-3" style="background-color: #ffcaca;">
                    <div class="input-group">
                      <img class="me-2" width="28" height="28" src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/esclamacion.png" class="img-fluid rounded-top" alt="">
                      <h5><strong> Recuerde!</strong></h5>
                    </div>
                    <label class="text-break text-start">Cada mes debe emplear este proceso</label>
                  </div>
                  <div class="col-lg-1 "></div>
                  <div class="col-lg-4 ">
                    <div class="mb-5"></div>
                    <div>
                      <button type="button" class="btn btn-success btn-lock" onclick="respaldar();">
                        Respaldar Datos
                      </button>
                    </div>
                  </div>
                  <div class="col-lg-1 "></div>
                </div>
              </div>

              <div class="tab-pane fade " id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                <div class="input-group justify-content-center mb-3">
                  <img class="me-2" width="52" height="52" src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/restaurar.png" class="img-fluid rounded-top" alt="">
                  <h2> Restaurar</h2>
                </div>
                <label class="text-break text-start mb-5 mt-4">Permite la recuperación de la información para el sistema, proporcionando técnicas de ingreso de la información a través de una copia de los datos.</label>
                <div class="row">
                  <div class="col-lg-1 "></div>
                  <div class="col-lg-5 ">
                    <label class=" mb-3"><strong> Selecciona un punto de restauración:</strong> </label>
                    <form action="/SIMA/serviciosBD-restore" method="post">
                      <select class="form-select mb-4" name="file_name" id="file_name" required>
                        <option value="" selected disabled>- seleccione -</option>
                        <?php for ($i = 2; $i < count($data); $i++) { ?>
                          <option value="<?php echo $data[$i]; ?>"><?php echo $data[$i]; ?></option>
                        <?php } ?>
                      </select>
                      <button type="submit" class="btn btn-success btn-lock mb-3">
                        Restaurar Sistema
                      </button>
                    </form>
                  </div>
                  <div class="col-lg-1 "></div>
                  <div class="col-lg-4 p-3 rounded h-25 mb-3" style="background-color: #ffcaca;">
                    <div class="input-group">
                      <img class="me-2" width="28" height="28" src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/esclamacion.png" class="img-fluid rounded-top" alt="">
                      <h5><strong> Recuerde!</strong></h5>
                    </div>
                    <label class="text-break text-start"> Debe emplear un respaldo de los datos con anterioridad</label>
                  </div>
                  <div class="col-lg-1 "></div>
                </div>
              </div>

              <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                <div class="input-group justify-content-center mb-3">
                  <img class="me-2" width="52" height="52" src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/restablecer.png" class="img-fluid rounded-top" alt="">
                  <h2>Restablecer</h2>
                </div>

                <h5 class="text-danger justify-content-center mt-4"><strong>ADVERTENCIA:</strong></h5>
                <label class="text-break text-start mb-5 fw-bold">Al utilizar este proceso eliminará permanentemente todos los datos que obtiene dentro del programa, dejándolo como si fuera su primer uso.</label>

                <div class="row">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-5 p-3 rounded h-25 mb-3" style="background-color: #ffcaca;">
                    <div class="input-group">
                      <img class="me-2" width="28" height="28" src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/esclamacion.png" class="img-fluid rounded-top" alt="">
                      <h5><strong> Recuerde!</strong></h5>
                    </div>
                    <label class="text-break text-start"> Solo usar esta opción si es necesario, en el caso de un error fatal presentado en el programa</label>
                  </div>
                  <div class="col-lg-1"></div>
                  <div class="col-lg-4 ">
                    <div class="mb-5"></div>
                    <div>
                      <form action="/SIMA/serviciosBD-restore_factory" method="post" id="form" name="form">
                        <input type="text" value="factory_db.sql" name="file_name" hidden>
                        <button type="button" class="btn btn-success btn-lock" onclick="pregunta(this.form);">
                          Restablecer el Sistema
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once "./public/views/layouts/footer.php"; ?>

<script src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/js/AyudaVentanas/mantenimientos/servicios_base_de_datos/index.js"></script>

<script>
  function respaldar() {
    fetch('/SIMA/serviciosBD-backup', {
        method: 'POST',
        body: JSON.stringify({
          funcion: 'backup'
        }),
        headers: {
          'Content-Type': 'application/json'
        }
      })
      .then(response => response.text())
      .then(data => {
        // setTimeout(function() {
        //   location.reload();
        // }, 2100);
        location.reload();
      })
      .catch(error => {
        location.reload();
      });
  }

  function pregunta(form) {
    Swal.fire({
      title: "Pregunta",
      text: "¿Está seguro de restablecer el sistema de fabrica?",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si",
      cancelButtonText: "No"
    }).then((result) => {
      if (result.isConfirmed) {
        document.form.submit();
      }
    });
  }
</script>

<?php
if (!empty($_SESSION['mensaje'])) {
  echo $_SESSION['mensaje'];
  unset($_SESSION['mensaje']);
}
?>