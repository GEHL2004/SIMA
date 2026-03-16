<?php include_once "header.php"; ?>

<!-- Estilos personalizados base para los reportes horizontales -->
<link href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/reporte-base-horizontal.css" rel="stylesheet">

<style>
    .fecha-reporte {
        font-size: 0.9em;
        color: #6c757d;
    }
</style>


<div class="text-center mb-3">
    <h1 style="font-size: 1.8rem; font-weight: 600; color: black; margin: 0;">Médicos por Grado Academico</h1>
    <p style="font-size: 1.1rem; font-weight: 300; color: black; margin: 5px 0 0 0;">Grado Academico: <strong><?php echo $datos[0]['nombre_grado']; ?></strong></p>
    <div class="fecha-reporte mt-2 mb-3">
        Generado el: <?php echo date('d/m/Y h:i:s A'); ?> / Generado por: <?php echo htmlspecialchars($_SESSION['nombres_apellidos']); ?>
    </div>
</div>

<table id="tabla" class="table table-bordered mb-0">
    <thead class="align-middle">
        <tr>
            <th width="1%" class="text-center align-middle">#</th>
            <th width="5%" class="text-center align-middle">Cédula</th>
            <th width="5%" class="text-center align-middle">N° Colégio</th>
            <th class="text-center align-middle">Nombres y Apellidos</th>
            <th width="14%" class="text-center align-middle">Teléfono</th>
            <th class="text-center align-middle">Correo</th>
            <th class="text-center align-middle">Dirección</th>
        </tr>
    </thead>
    <tbody>
        <?php $contador = 0; ?>
        <?php if (!empty($datos)): ?>
            <?php foreach ($datos as $i => $valor): ?>
                <tr>
                    <td class="text-center align-middle" style="padding: 12px 15px;"><?= ++$contador; ?></td>
                    <td class="text-center align-middle" style="padding: 12px 15px;"><?= $valor['cedula']; ?></td>
                    <td class="text-center align-middle" style="padding: 12px 15px;"><?= $valor['numero_colegio']; ?></td>
                    <td class="text-center align-middle" style="padding: 12px 15px;"><?= $valor['nombres_allidos']; ?></td>
                    <td class="text-center align-middle" style="padding: 12px 15px;"><?= $valor['telefono']; ?></td>
                    <td class="text-center align-middle" style="padding: 12px 15px;"><?= $valor['correo']; ?></td>
                    <td class="text-center align-middle" style="padding: 12px 15px;"><?= $valor['direccion']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td class="text-center align-middle" style="padding: 12px 15px;" colspan="7">No se poseen medicos de perteneciente a este municipio</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


<?php include_once "footer.php"; ?>