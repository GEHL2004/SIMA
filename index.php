<?php session_start(); ?>

<?php

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\Controllers\{AuthController, HomeController, CategoriasController, TiposPracticaController, SistemasCorporalesController, EspecialidadesController, SubEspecialidadesController, MedicosController, MedicosReportesController, MedicosReportesDeportesController, MedicosReportesEspecialidadesController, MedicosReportesEstadosController, MedicosReportesGradosAcademicosController, MedicosReportesMunicpiosController, MedicosReportesParroquiasController, MedicosReportesSubespecialidadesController};
use App\Controllers\Mantenimientos\{AuditoriaController, DeportesController, MunicipiosController, ServiciosBaseDeDatosController, UsuariosController};

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$route = str_replace("/SIMA", '', $request_uri);

date_default_timezone_set('America/Caracas');
setlocale(LC_TIME, 'Spanish');
set_time_limit(30);

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
// die();

if ($request_method === 'POST' && $route === '/login') {
    $authController = (new AuthController())->login($_REQUEST['user'], $_REQUEST['password']);
    if (!$authController) {
        $error = true;
        require_once 'public/views/auth/login.php';
    }
}

if ($request_method === 'GET' && strpos($route, '/rucuperarPassword1/') === 0) {
    $user = substr($route, strlen('/rucuperarPassword1/'));
    (new UsuariosController())->rucuperarPassword1($user);
    exit;
} else if ($request_method === 'POST' && $route === '/rucuperarPassword2') {
    (new UsuariosController())->rucuperarPassword2($_REQUEST);
}

// validacion de logueo
if (empty($_SESSION)) {
    require_once 'public/views/auth/login.php';
    exit;
} else if ((isset($_SESSION['nombre_usuario']) && $route === '/login') || (isset($_SESSION['nombre_usuario']) && $route === '/')) {
    (new HomeController())->index();
    exit;
}

//rutas tipo GET

if ($request_method === 'GET' && strpos($route, '/BuscadorDeSitios/') === 0) {
    $id = intval(substr($route, strlen('/BuscadorDeSitios/')));
    (new MunicipiosController())->getParroquias($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/categorias-delete/') === 0) {
    $id = intval(substr($route, strlen('/categorias-delete/')));
    (new CategoriasController())->delete($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/tipos-practicas-delete/') === 0) {
    $id = intval(substr($route, strlen('/tipos-practicas-delete/')));
    (new TiposPracticaController())->delete($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/sistemas-corporales-delete/') === 0) {
    $id = intval(substr($route, strlen('/sistemas-corporales-delete/')));
    (new SistemasCorporalesController())->delete($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/especialidades-edit/') === 0) {
    $id = intval(substr($route, strlen('/especialidades-edit/')));
    (new EspecialidadesController())->edit($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/especialidades-show/') === 0) {
    $id = intval(substr($route, strlen('/especialidades-show/')));
    (new EspecialidadesController())->show($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/especialidades-delete/') === 0) {
    $id = intval(substr($route, strlen('/especialidades-delete/')));
    (new EspecialidadesController())->delete($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/especialidades-disable/') === 0) {
    $id = intval(substr($route, strlen('/especialidades-disable/')));
    (new EspecialidadesController())->disable($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/especialidades-enable/') === 0) {
    $id = intval(substr($route, strlen('/especialidades-enable/')));
    (new EspecialidadesController())->enable($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/subespecialidades-edit/') === 0) {
    $id = intval(substr($route, strlen('/subespecialidades-edit/')));
    (new SubEspecialidadesController())->edit($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/subespecialidades-show/') === 0) {
    $id = intval(substr($route, strlen('/subespecialidades-show/')));
    (new SubEspecialidadesController())->show($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/subespecialidades-delete/') === 0) {
    $id = intval(substr($route, strlen('/subespecialidades-delete/')));
    (new SubEspecialidadesController())->delete($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/subespecialidades-disable/') === 0) {
    $id = intval(substr($route, strlen('/subespecialidades-disable/')));
    (new SubEspecialidadesController())->disable($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/subespecialidades-enable/') === 0) {
    $id = intval(substr($route, strlen('/subespecialidades-enable/')));
    (new SubEspecialidadesController())->enable($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-edit/') === 0) {
    $id = intval(substr($route, strlen('/medicos-edit/')));
    (new MedicosController())->edit($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-show/') === 0) {
    $id = intval(substr($route, strlen('/medicos-show/')));
    (new MedicosController())->show($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-delete/') === 0) {
    $cadena = substr($route, strlen('/medicos-delete/'));
    (new MedicosController())->changeStatus($cadena);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-generar-reporte-individual/') === 0) {
    $id = intval(substr($route, strlen('/medicos-generar-reporte-individual/')));
    (new MedicosReportesController())->generarReporteMedicoIndividual($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-ver-reporte-municipios/') === 0) {
    $id = intval(substr($route, strlen('/medicos-ver-reporte-municipios/')));
    (new MedicosReportesController())->vistaReporteMedicoPorMunicipios($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-generar-reporte-municipios/') === 0) {
    $id = intval(substr($route, strlen('/medicos-generar-reporte-municipios/')));
    (new MedicosReportesController())->generarReporteMedicoPorMunicipios($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-ver-reporte-parroquias/') === 0) {
    $id = intval(substr($route, strlen('/medicos-ver-reporte-parroquias/')));
    (new MedicosReportesController())->vistaReporteMedicoPorParroquias($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-generar-reporte-parroquias/') === 0) {
    $id = intval(substr($route, strlen('/medicos-generar-reporte-parroquias/')));
    (new MedicosReportesController())->generarReporteMedicoPorParroquias($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-ver-reporte-especialidades/') === 0) {
    $id = intval(substr($route, strlen('/medicos-ver-reporte-especialidades/')));
    (new MedicosReportesController())->vistaReporteMedicoPorEspecialidad($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-generar-reporte-especialidades/') === 0) {
    $id = intval(substr($route, strlen('/medicos-generar-reporte-especialidades/')));
    (new MedicosReportesController())->generarReporteMedicoPorEspecialidad($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-ver-reporte-subespecialidades/') === 0) {
    $id = intval(substr($route, strlen('/medicos-ver-reporte-subespecialidades/')));
    (new MedicosReportesController())->vistaReporteMedicoPorSubespecialidad($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-generar-reporte-subespecialidades/') === 0) {
    $id = intval(substr($route, strlen('/medicos-generar-reporte-subespecialidades/')));
    (new MedicosReportesController())->generarReporteMedicoPorSubespecialidad($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-ver-reporte-grados-academicos/') === 0) {
    $id = intval(substr($route, strlen('/medicos-ver-reporte-grados-academicos/')));
    (new MedicosReportesController())->vistaReporteMedicoPorGradoAcademico($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-generar-reporte-grados-academicos/') === 0) {
    $id = intval(substr($route, strlen('/medicos-generar-reporte-grados-academicos/')));
    (new MedicosReportesController())->generarReporteMedicoPorGradoAcademico($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-ver-reporte-estados/') === 0) {
    $id = intval(substr($route, strlen('/medicos-ver-reporte-estados/')));
    (new MedicosReportesController())->vistaReporteMedicoPorEstado($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-generar-reporte-estados/') === 0) {
    $id = intval(substr($route, strlen('/medicos-generar-reporte-estados/')));
    (new MedicosReportesController())->generarReporteMedicoPorEstado($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-ver-reporte-deportes/') === 0) {
    $id = intval(substr($route, strlen('/medicos-ver-reporte-deportes/')));
    (new MedicosReportesController())->vistaReporteMedicoPorDeporte($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/medicos-generar-reporte-deportes/') === 0) {
    $id = intval(substr($route, strlen('/medicos-generar-reporte-deportes/')));
    (new MedicosReportesController())->generarReporteMedicoPorDeporte($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/auditoria-filtrar-usuario/') === 0) {
    $id = intval(substr($route, strlen('/auditoria-filtrar-usuario/')));
    (new AuditoriaController())->filtrar_usuario($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/auditoria-filtrar-fechas/') === 0) {
    $fehcas = substr($route, strlen('/auditoria-filtrar-fechas/'));
    (new AuditoriaController())->filtrado_rango_fechas($fehcas);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/auditoria-filtrar-accion/') === 0) {
    $cadena = substr($route, strlen('/auditoria-filtrar-accion/'));
    (new AuditoriaController())->filtrado_accion($cadena);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/usuarios-edit/') === 0) {
    $id = intval(substr($route, strlen('/usuarios-edit/')));
    (new UsuariosController())->edit($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/usuarios-show/') === 0) {
    $id = intval(substr($route, strlen('/usuarios-show/')));
    (new UsuariosController())->show($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/usuarios-update-nivel/') === 0) {
    $cadena = substr($route, strlen('/usuarios-update-nivel/'));
    (new UsuariosController())->updateNivel($cadena);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/update-estado/') === 0) {
    $cadena = substr($route, strlen('/update-estado/'));
    (new UsuariosController())->updateEstado($cadena);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/usuarios-delete/') === 0) {
    $id = intval(substr($route, strlen('/usuarios-delete/')));
    (new UsuariosController())->delete($id);
    exit;
} else if ($request_method === 'GET' && strpos($route, '/deportes-delete/') === 0) {
    $id = intval(substr($route, strlen('/deportes-delete/')));
    (new DeportesController())->delete($id);
    exit;
}

// rutas tipo POST

else if ($request_method === 'POST' && $route === '/categorias-store') {
    (new CategoriasController())->store($_REQUEST);
} else if ($request_method === 'POST' && $route === '/categorias-update') {
    (new CategoriasController())->update($_REQUEST);
} else if ($request_method === 'POST' && $route === '/tipos-practicas-store') {
    (new TiposPracticaController())->store($_REQUEST);
} else if ($request_method === 'POST' && $route === '/tipos-practicas-update') {
    (new TiposPracticaController())->update($_REQUEST);
} else if ($request_method === 'POST' && $route === '/sistemas-corporales-store') {
    (new SistemasCorporalesController())->store($_REQUEST);
} else if ($request_method === 'POST' && $route === '/sistemas-corporales-update') {
    (new SistemasCorporalesController())->update($_REQUEST);
} else if ($request_method === 'POST' && $route === '/especialidades-store') {
    (new EspecialidadesController())->store($_REQUEST);
} else if ($request_method === 'POST' && $route === '/especialidades-update') {
    (new EspecialidadesController())->update($_REQUEST);
} else if ($request_method === 'POST' && $route === '/subespecialidades-store') {
    (new SubEspecialidadesController())->store($_REQUEST);
} else if ($request_method === 'POST' && $route === '/subespecialidades-update') {
    (new SubEspecialidadesController())->update($_REQUEST);
} else if ($request_method === 'POST' && $route === '/medicos-store') {
    (new MedicosController())->store($_REQUEST, $_FILES);
} else if ($request_method === 'POST' && $route === '/medicos-update') {
    (new MedicosController())->update($_REQUEST, $_FILES);
} else if ($request_method === 'POST' && $route === '/medicos-carga-masiva') {
    (new MedicosController())->carga_masiva($_FILES);
} else if ($request_method === 'POST' && $route === '/serviciosBD-backup') {
    (new ServiciosBaseDeDatosController())->backup();
} else if ($request_method === 'POST' && $route === '/serviciosBD-restore') {
    (new ServiciosBaseDeDatosController())->restore($_REQUEST);
} else if ($request_method === 'POST' && $route === '/serviciosBD-restore_factory') {
    (new ServiciosBaseDeDatosController())->restore_factory($_REQUEST);
} else if ($request_method === 'POST' && $route === '/deportes-store') {
    (new DeportesController())->store($_REQUEST);
} else if ($request_method === 'POST' && $route === '/deportes-update') {
    (new DeportesController())->update($_REQUEST);
}

// rutas normales

switch ($route) {

    case '/':
        require_once 'public/views/auth/login.php';
        break;

    case '/login':
        require_once 'public/views/auth/login.php';
        break;

    case '/logout':
        (new AuthController())->logout();
        break;

    case '/home':
        if (isset($_SESSION['nombre_usuario'])) {
            (new HomeController())->index();
        } else {
            header("Location: /SIMA/login");
            exit;
        }
        break;

    case '/dataHomeActualziada':
        (new HomeController())->dataActualizada();
        break;

    case '/categorias':
        (new CategoriasController())->index();
        break;

    case '/tipos-practicas':
        (new TiposPracticaController())->index();
        break;

    case '/sistemas-corporales':
        (new SistemasCorporalesController())->index();
        break;

    case '/especialidades':
        (new EspecialidadesController())->index();
        break;

    case '/especialidades-create':
        (new EspecialidadesController())->create();
        break;

    case '/especialidades-disable':
        (new EspecialidadesController())->disable_specialties();
        break;

    case '/subespecialidades':
        (new SubEspecialidadesController())->index();
        break;

    case '/subespecialidades-create':
        (new SubEspecialidadesController())->create();
        break;

    case '/subespecialidades-disable':
        (new SubEspecialidadesController())->disable_sub_specialties();
        break;

    case '/medicos':
        (new MedicosController())->index();
        break;

    case '/medicos-create':
        (new MedicosController())->create();
        break;

    case '/medicos-municipios':
        (new MedicosReportesMunicpiosController())->index();
        break;

    case '/medicos-parroquias':
        (new MedicosReportesParroquiasController())->index();
        break;

    case '/medicos-especialidades':
        (new MedicosReportesEspecialidadesController())->index();
        break;

    case '/medicos-subespecialidades':
        (new MedicosReportesSubespecialidadesController())->index();
        break;

    case '/medicos-grados-academicos':
        (new MedicosReportesGradosAcademicosController())->index();
        break;

    case '/medicos-estados':
        (new MedicosReportesEstadosController())->index();
        break;

    case '/medicos-deportes':
        (new MedicosReportesDeportesController())->index();
        break;

    case '/medicos-carga-masiva':
        (new MedicosController())->vista_carga_masiva();
        break;

    case '/usuarios':
        (new UsuariosController())->index();
        break;

    case '/usuarios-create':
        (new UsuariosController())->create();
        break;

    case '/usuarios-getData':
        (new UsuariosController())->getAllData();
        break;

    case '/auditoria':
        (new AuditoriaController())->index();
        break;

    case '/auditoria-filtrar-general':
        (new AuditoriaController())->filtrado_general();
        break;

    case '/servicios-bd':
        (new ServiciosBaseDeDatosController())->index();
        break;

    case '/monitor-bd':
        (new ServiciosBaseDeDatosController())->monitorBD();
        break;

    case '/deportes':
        (new DeportesController())->index();
        break;

    default:
        http_response_code(404);
        require_once 'public/views/404/index.php';
        // echo "Ruta no encontrada";
        break;
}
