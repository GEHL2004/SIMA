<?php

$request = $datos['request'];
$data = $datos['data'];

?>

<?php include_once "header.php"; ?>

<!-- Estilos personalizados base para los reportes verticales -->
<link href="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/css/reporte-base-vertical.css" rel="stylesheet">

<style>
    .fecha-reporte {
        font-size: 0.9em;
        color: #6c757d;
    }
</style>


<div class="text-center mb-3">
    <h1 style="font-size: 1.8rem; font-weight: 600; color: black; margin: 0;">Listado de medicos con reconocimiento <?php echo $request['estado']; ?></h1>
    <p style="font-size: 1.1rem; font-weight: 300; color: black; margin: 5px 0 0 0;">- Listado de médicos de <?php echo $request['intervalo']; ?> años -</strong></p>
    <div class="fecha-reporte mt-2 mb-3">
        Generado el: <?php echo date('d/m/Y h:i:s A'); ?> / Generado por: <?php echo htmlspecialchars($_SESSION['nombres_apellidos']); ?>
    </div>
</div>

<table id="tabla" class="table table-bordered mb-0">
    <thead class="align-middle">
        <tr>
            <th width="1%" class="text-center align-middle">#</th>
            <th class="text-center align-middle">Nombre del médico</th>
            <th width="14%" class="text-center align-middle">Teléfono</th>
            <th width="20%" class="text-center align-middle">Correo</th>
            <th width="10%" class="text-center align-middle">Años de graduado</th>
            <th width="10%"class="text-center align-middle">¿Asistirá? (marcar SI o NO)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $i => $valor): ?>
            <tr>
                <td class="text-center align-middle" style="padding: 12px 15px;"><?php echo $valor['contador']; ?></td>
                <td class="text-center align-middle" style="padding: 12px 15px;"><?php echo $valor['nombres_apellidos']; ?></td>
                <td class="text-center align-middle" style="padding: 12px 15px;"><?php echo $valor['telefono']; ?></td>
                <td class="text-center align-middle" style="padding: 12px 15px;"><?php echo $valor['correo']; ?></td>
                <td class="text-center align-middle" style="padding: 12px 15px;"><?php echo $valor['años_transcurridos_graduado']; ?></td>
                <td class="text-center align-middle" style="padding: 12px 15px;"></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?php include_once "footer.php"; ?>