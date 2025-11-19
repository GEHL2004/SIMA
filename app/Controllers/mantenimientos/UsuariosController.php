<?php

namespace App\Controllers\Mantenimientos;

use App\Controllers\AlertasController;
use App\Controllers\AuthController;
use App\Controllers\CorreosController;
use App\Controllers\CriptografiaController;
use App\Controllers\ImagesController;
use App\Models\Auth;
use App\Models\Mantenimientos\Usuarios;

class UsuariosController
{

    private $user;
    private $soli;
    private $correo;
    private $audi;
    private $cripto;
    private $img;

    public function __construct()
    {
        $this->user = new Usuarios();
        // $this->correo = new CorreosController('smtp.gmail.com', true, $_ENV['EMAIL_USER'], str_replace('_', ' ', $_ENV['EMAIL_PASSWORD']), 587);
        $this->audi = new AuditoriaController();
        $this->cripto = new CriptografiaController();
        $this->img = new ImagesController();
    }

    public function index()
    {
        $data = $this->user->index();
        foreach ($data as $i => $valor) {
            $data[$i]['nombres'] = str_replace('_', ' ', $data[$i]['nombres']);
            $data[$i]['apellidos'] = str_replace('_', ' ', $data[$i]['apellidos']);
            $data[$i]['nombres_apellidos'] = $data[$i]['nombres'] . ' ' . $data[$i]['apellidos'];
        }
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        // echo "<pre>";
        // print_r($data_solicitudes);
        // echo "</pre>";
        // die();
        require_once "public/views/mantenimientos/usuarios/index.php";
    }

    public function getAllData()
    {
        $data = $this->user->getAllData();
        header('Content-Type: application/json');
        $dataJSON = json_encode($data);
        echo json_encode($dataJSON);
    }

    public function create()
    {
        require_once "public/views/mantenimientos/usuarios/create.php";
    }

    public function store(array $request)
    {
        $request['nombres'] = str_replace(' ', '_', trim($request['nombres']));
        $request['apellidos'] = str_replace(' ', '_', trim($request['apellidos']));
        $request['password_user'] = $this->cripto->encriptacion($request['password_user']);
        $bool = $this->user->store($request);
        if ($bool['error'] == 2) {
            AlertasController::warning('Información duplicada.', 'La cédula del usuario a actualizar ya se encuentra vinculada con otro usuario en el sistema.');
        } else if ($bool['error'] == 3) {
            AlertasController::warning('Información duplicada.', 'El nombre de usuario a actualizar ya se encuentra vinculada con otro usuario en el sistema.');
        } else if ($bool['error'] == 4) {
            AlertasController::error('Información manipulada.', 'El código de la pagina fue manipulado, salga del formulario y vulva a abrirlo para continuar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' registro un nuevo usuario para SIMA el cual posee el nombre de usuario de ' . $request['nombre_user']]);
            AlertasController::success('Registro exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/usuarios');
        die();
    }

    public function edit(int $id_usuario)
    {
        $data = $this->user->show($id_usuario);
        $data[0]['password_user'] = $this->cripto->desencriptacion($data[0]['password_user']);
        $data[0]['nombres'] = str_replace('_', ' ', $data[0]['nombres']);
        $data[0]['apellidos'] = str_replace('_', ' ', $data[0]['apellidos']);
        require_once "public/views/mantenimientos/usuarios/edit.php";
    }

    public function update(array $request)
    {
        $request['nombres'] = str_replace(' ', '_', trim($request['nombres']));
        $request['apellidos'] = str_replace(' ', '_', trim($request['apellidos']));
        $request['password_user'] = $this->cripto->encriptacion($request['password_user']);
        $bool = $this->user->update($request);
        if ($bool['error'] == 2) {
            AlertasController::warning('Información duplicada.', 'La cédula del usuario a registrar ya se encuentra vinculada con otro usuario en el sistema.');
        } else if ($bool['error'] == 3) {
            AlertasController::warning('Información duplicada.', 'El nombre de usuario a registrar ya se encuentra vinculada con otro usuario en el sistema.');
        } else if ($bool['error'] == 4) {
            AlertasController::error('Información manipulada.', 'El código de la pagina fue manipulado, salga del formulario y vulva a abrirlo para continuar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' actualizo la información del usuario "' . $request['nombre_user'] . '" del sistema SIMA']);
            AlertasController::success('Actualización exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/usuarios');
        die();
    }

    public function updateNivel($cadena)
    {
        $valores = explode('_', $cadena);
        $bool = $this->user->updateNivel($valores[0], $valores[1]);
        if ($bool) {
            AlertasController::success('Nivel del usuario actualizado exitosamente.');
        } else {
            AlertasController::error('Error', 'No se pudo realizar la modificación del nivel.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/usuarios');
        die();
    }

    public function updateEstado($cadena)
    {
        $valores = explode('_', $cadena);
        $bool = $this->user->updateEstado($valores[0], $valores[1]);
        if ($valores[0] == 1 && $bool) {
            AlertasController::success('Usuario habilitado exitosamente.');
        } else if ($valores[0] == 0 && $bool) {
            AlertasController::success('Usuario deshabilitado exitosamente.');
        } else if ($valores[0] == -1 && $bool) {
            AlertasController::success('Usuario bloqueado exitosamente.');
        } else {
            AlertasController::error('Error', 'No se pudo realizar la modificación de estado.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/usuarios');
        die();
    }

    public function show(int $id_usuario)
    {
        $data = $this->user->show($id_usuario);
        if ($_SESSION['nivel_acceso'] != 1) {
            unset($data[0]['password_user']);
            unset($data[0]['pregunta_secreta']);
            unset($data[0]['respuesta_secreta']);
        } else {
            $data[0]['password_user'] = $this->cripto->desencriptacion($data[0]['password_user']);
        }
        $data[0]['nombres'] = str_replace('_', ' ', $data[0]['nombres']);
        $data[0]['apellidos'] = str_replace('_', ' ', $data[0]['apellidos']);
        require_once("public/views/mantenimientos/usuarios/show.php");
    }

    public function delete(int $id_usuario)
    {
        $bool = $this->user->delete($id_usuario);
        if ($bool == 1) {
            AlertasController::success('Usuario eliminado exitosamente.');
        } else {
            AlertasController::error('Error', 'El usuario no pudo ser eliminado.');
        }
        $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombre_apellido"] . ' elimino a un usuario del sistema']);
        header('Location: ' . $_ENV['BASE_PATH'] . '/usuarios');
    }

    public function validar_usuario(array $request)
    {
        $bool = [];
        $usuario = Auth::login($request['usuario']);
        if (!empty($usuario)) {
            $contraseña_desencriptada = (new CriptografiaController())->desencriptacion($usuario[0]['password_user']);
            if (strcmp($usuario[0]['user'], $request['usuario']) == 0 && strcmp($contraseña_desencriptada, $request['contrasena']) == 0) {
                $data = $this->user->validar_usuario($usuario[0]['cedu']);
                if (strcmp($data[0]['depart'], 'PRESUPUESTO') == 0 && strcmp($data[0]['dcargo'], 'GERENTE') == 0) {
                    $bool = ['mensaje' => 4];
                } else {
                    $nombre_apellidos = explode(',', ucwords(mb_strtolower($data[0]['nape'])));
                    $bool = ['mensaje' => 3, 'nombre_persona' => $nombre_apellidos[1] . ' ' . $nombre_apellidos[0], 'cargo' => mb_strtolower($data[0]['dcargo']), 'gerencia' => mb_strtolower($data[0]['depart'])];
                }
            } else {
                $bool = ['mensaje' => 2];
            }
        } else {
            $bool = ['mensaje' => 1];
        }
        header('Content-Type: application/json');
        $dataJ = json_encode($bool);
        $dataJ = json_encode($dataJ);
        echo $dataJ;
        die();
    }

    public function rucuperarPassword1($correo)
    {
        // $data = $this->user->buscar_usuario($correo);
        die();
        $bool = ['bool' => false];
        if (!empty($data)) {
            $bool['bool'] = true;
            $bool['data'] = $data[0];
        }
        header('Content-Type: application/json');
        $dataJ = json_encode($bool);
        $dataJ = json_encode($dataJ);
        echo $dataJ;
    }

    public function rucuperarPassword2(array $request)
    {
        $request['password'] = $this->cripto->encriptacion($request['password']);
        $bool = $this->user->updatePassword($request);
        $bool = true;
        $data = ['bool' => $bool];
        header('Content-Type: application/json');
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        echo $dataJ;
        die();
    }
}
