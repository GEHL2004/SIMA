<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE</title>

    <!-- Bootstrap 4 CSS -->
    <link href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/bootstrap-4.6.2.min.css" rel="stylesheet">

</head>


<body>

    <!-- Marca de agua centrada -->
    <div id="watermark">
        <img src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/Escudo_Colegio_Medicos.png" alt="Logo Colegio Médico" class="img-fluid">
    </div>

    <!-- ENCABEZADO -->
    <header>
        <table class="table table-borderless border-bottom border-dark">
            <tbody class="align-middle">
                <tr class="text-center">
                    <td width="20%" style="line-height: 1.1;">
                        <div class="mb-2 text-center mr-5" style="left: 6;">
                            <span class="font-weight-bold mb-1" style="font-size: 12pt;">COLEGIO DE MÉDICOS</span>
                            <br>
                            <span class="font-weight-bold mb-1" style="font-size: 12pt;">DEL ESTADO ARAGUA</span>
                            <br>
                            <span class="mb-1" style="font-weight: normal; font-size: 10pt;">Avenida Universidad, Sector La Trinidad</span>
                            <br>
                            <span class="mb-1" style="font-weight: normal; font-size: 10pt;">El Limón (Al Lado de Tránsito Terrestre)</span>
                            <br>
                            <span class="mb-1 font-weight-bold" style="font-size: 10pt;">TELÉFONOS FAX: 0243-383.10.45 - 283.45.56</span>
                            <br>
                            <span class="mb-1" style="font-weight: normal; font-size: 10pt;">www.colegiodemedicosaragua.com.ve</span>
                            <br>
                            <span class="mb-1" style="font-weight: normal; font-size: 10pt;">E-mail: medicosaragua@hotmail.com</span>
                            <br>
                            <span class="mb-0" style="font-weight: normal; font-size: 10pt;">MARACAY - ESTADO ARAGUA</span>
                        </div>
                    </td>
                    <td width="25%"></td>
                    <td width="8%">
                        <img src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/Escudo_Colegio_Medicos.png" alt="Logo Colegio Médico" class="logo-header img-fluid ml-5 mt-1">
                    </td>
                </tr>
            </tbody>
        </table>
    </header>

    <!-- PIE DE PÁGINA -->
    <footer class="border-top border-dark pt-3" style="border-width: 2px !important;">
        <div class="row mb-1">
            <div class="col-lg-6">
                <p class="mb-1"><strong>Documento Oficial</strong></p>
                <p class="text-muted small mb-0">Este documento es para uso exclusivo del Colegio Médico del Estado Aragua.</p>
            </div>
            <div class="col-lg-6 text-md-right">
                <p class="text-muted small mb-0">Generado por: Sistema Integral de Medicos de Aragua | Versión 1.0</p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 text-center">
                <p class="text-muted small mb-0">© <?php echo date('Y'); ?> SIMA. Colegio Médico del Estado Aragua (CMA). Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- CUERPO DEL REPORTE -->
    <main class="mb-5">
        <!-- Contenedor principal del reporte -->
        <div class="container-fluid">