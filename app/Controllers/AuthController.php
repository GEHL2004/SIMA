<?php

namespace App\Controllers;

use App\Models\Auth;
use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\Mantenimientos\Usuarios;

class AuthController
{

    private $auditoria;
    private $usuarios;
    private $notificaciones;

    public function __construct()
    {
        $this->auditoria = new AuditoriaController();
        $this->usuarios = new Usuarios();
        // $this->notificaciones = new NotificacionesController();
    }

    public static function validarLogueo()
    {
        if (empty($_SESSION['nombre_usuario'])) {
            header("location: /SIMA/login");
        }
    }

    public function login($user, $password)
    {
        $auth = [];
        $auth = Auth::login($user);
        // print_r($_SESSION);
        // die();
        if (!empty($auth)) {
            $contraseña_desencriptada = (new CriptografiaController())->desencriptacion($auth[0]['password_user']);
            if (strcmp($auth[0]['nombre_user'], $user) === 0 && strcmp($contraseña_desencriptada, $password) === 0) {
                if ($auth[0]['estado'] > 0) {
                    // set_time_limit(180);
                    foreach ($auth as $valor) {
                        $_SESSION["nombre_usuario"] = $valor['nombre_user'];
                        $_SESSION["nombres"] = trim(str_replace('_', ' ', $valor['nombres']));
                        $_SESSION["apellidos"] = trim(str_replace('_', ' ', $valor['apellidos']));
                        $_SESSION['nombres_apellidos'] = $_SESSION["nombres"] . ' ' . $_SESSION["apellidos"];
                        $_SESSION["nivel_acceso"] = $valor['nivel'];
                        $_SESSION["id_usuario"] = $valor['id_usuario'];
                        $_SESSION["estado"] = $valor['estado'];
                    }
                    $this->auditoria->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'Inicio Sesión']);
                    // set_time_limit(30);
                    header("location: /SIMA/home");
                } else {
                    AlertasController::error("USUARIO BLOQUEADO!!!", "Su usuario en el sistema SIMA se encuentra bloqueado por fallar los tres intentos de inicio de sesión anteriores, solicite al Super Administrador que lo desbloquee...");
                    return false;
                }
            } else if (!empty($this->usuarios->buscar_usuario($user))) {
                $estado = $this->usuarios->buscar_usuario($user)[0]['estado'];
                $id_usuario = $this->usuarios->buscar_usuario($user)[0]['id_usuario'];
                if ($estado < 0) {
                    AlertasController::error("USUARIO BLOQUEADO!!!", "Su usuario en el sistema SIMA se encuentra bloqueado por fallar los tres intentos de inicio de sesión anteriores, solicite al Super Administrador que lo desbloquee...");
                    return false;
                } else if (empty($_SESSION["usuario_no_login"])) {
                    $_SESSION["usuario_no_login"] = $user;
                    $_SESSION["intento"] = 1;
                    AlertasController::error("Error", "Usuario o contraseña incorrectos");
                    return false;
                } else if ($_SESSION["usuario_no_login"] == $user) {
                    $_SESSION["intento"] += 1;
                    if ($_SESSION["intento"] >= 3) {
                        $id_usuario = $this->usuarios->buscar_usuario($user)[0]['id_usuario'];
                        $this->usuarios->updateEstado(-1, $id_usuario);
                        unset($_SESSION['usuario_no_login']);
                        unset($_SESSION['intento']);
                        // $this->notificaciones->store(['titulo' => 'Usuarios bloqueados', 'texto' => 'Tiene uno o más usuarios bloqueados pendientes de desbloqueo.', 'tipo' => 1, 'gerencia' => 'INFORMÁTICA']);
                        AlertasController::error("Error", "Su usuario ha sido bloqueado por fallar los tres intentos, solicite al administrador que lo desbloquee...");
                        return false;
                    }
                    AlertasController::error("Error", "Usuario o contraseña incorrectos");
                    return false;
                } else if ($_SESSION["usuario_no_login"] != $user) {
                    $_SESSION["usuario_no_login"] = $user;
                    $_SESSION["intento"] = 1;
                    AlertasController::error("Error", "Usuario o contraseña incorrectos");
                    return false;
                }
            } else {
                unset($_SESSION["usuario_no_login"]);
                unset($_SESSION["intento"]);
                AlertasController::error("Error", "Usuario o contraseña incorrectos");
                return false;
            }
        } else {
            unset($_SESSION["usuario_no_login"]);
            unset($_SESSION["intento"]);
            AlertasController::error("Error", "Usuario o contraseña incorrectos");
            return false;
        }
    }

    public function logout()
    {
        set_time_limit(120);
        $this->auditoria->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'Cerro Sesión']);
        session_destroy();
        set_time_limit(30);
        header("location: /SIMA/login");
    }
}
