<?php session_start(); ?>

<?php

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\Controllers\{AuthController, HomeController};
use App\Controllers\Mantenimientos\AuditoriaController;
use App\Controllers\Mantenimientos\ServiciosBaseDeDatosController;
use App\Controllers\Mantenimientos\UsuariosController;

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$route = str_replace("/SIMA", '', $request_uri);

date_default_timezone_set('America/Caracas');
setlocale(LC_TIME, 'Spanish');

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

if ($request_method === 'GET' && strpos($route, '/auditoria-filtrar-usuario/') === 0) {
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
}

// rutas tipo POST

else if ($request_method === 'POST' && $route === '/serviciosBD-backup') {
    (new ServiciosBaseDeDatosController())->backup();
} else if ($request_method === 'POST' && $route === '/serviciosBD-restore') {
    (new ServiciosBaseDeDatosController())->restore($_REQUEST);
} else if ($request_method === 'POST' && $route === '/serviciosBD-restore_factory') {
    (new ServiciosBaseDeDatosController())->restore_factory($_REQUEST);
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


    default:
        http_response_code(404);
        require_once 'public/views/404/index.php';
        // echo "Ruta no encontrada";
        break;
}
