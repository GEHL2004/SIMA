<?php require_once "./public/views/layouts/header.php"; ?>

<div class="container-fluid content-inner m-0 mt-5">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-center" style="padding: 15px;">
                    <h5 class="card-title mb-0 text-white"><i class="fa-solid fa-user-doctor mx-2"></i>Carga de Masiva de Medico<i class="fa-solid fa-user-doctor mx-2"></i></h5>
                </div>
                <div class="card-body text-dark">
                    <p class="small">Por favor, selecciona un archivo en formato Excel (.xlsx) para procesar los datos.</p>
                    <form action="/SIMA/medicos-carga-masiva" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="formFile" class="form-label fw-bold">Archivo Excel</label>
                            <input class="form-control text-dark rounded-pill" type="file" id="formFile" name="file" accept=".xlsx" required>
                            <div class="invalid-feedback">
                                Por favor, selecciona un archivo válido.
                            </div>
                            <div class="form-text">Solo se permiten archivos con extensión .xlsx</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success rounded-pill">
                                <i class="bi bi-upload me-2"></i>Subir Archivo
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <span class="badge rounded-pill text-bg-light border">Formato requerido: Excel 2007+</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "./public/views/layouts/footer.php"; ?>

<script>
    // Script básico para habilitar la validación visual de Bootstrap
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

<?php if (!empty($_SESSION['mensaje'])) {
    echo $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
} ?>