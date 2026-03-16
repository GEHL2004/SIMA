<?php

namespace App\Models\Mantenimientos;

use App\config\Conexion;
use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\Auth;

class Usuarios
{

    private $conn;
    private $audi;

    public function __construct()
    {
        $this->conn = new Conexion();
        $this->audi = new AuditoriaController();
    }

    public function index()
    {
        $sql = "SELECT id_usuario, cedula, nombres, apellidos, nombre_user, nivel, estado 
                FROM usuarios";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function getAllData()
    {
        $sql = "SELECT id_usuario, nombre_user 
                FROM usuarios;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function store(array $data)
    {
        $sql = "SELECT * FROM usuarios WHERE cedula = :cedula";
        $parametros = [':cedula' => $data['cedula']];
        $consulta = $this->conn->consultar($sql, $parametros);
        if (!empty($consulta)) {
            return ['error' => 2, 'id_usuario' => null];
        }
        $sql = "SELECT * FROM usuarios WHERE nombre_user = :nombre_user";
        $parametros = [':nombre_user' => $data['nombre_user']];
        $consulta = $this->conn->consultar($sql, $parametros);
        if (!empty($consulta)) {
            return ['error' => 3, 'id_usuario' => null];
        }
        $sql = "INSERT INTO usuarios(cedula, nombres, apellidos, nombre_user, password_user, pregunta_secreta, respuesta_secreta, nivel, estado)
                VALUES(:cedula, :nombres, :apellidos, :nombre_user, :password_user, :pregunta_secreta, :respuesta_secreta, :nivel, 1);";
        $parametros = [':cedula' => $data['cedula'], ':nombres' => $data['nombres'], ':apellidos' => $data['apellidos'], ':nombre_user' => $data['nombre_user'], ':password_user' => $data['password_user'], ':nivel' => $data['nivel'], ':pregunta_secreta' => $data['pregunta_secreta'], ':respuesta_secreta' => $data['respuesta_secreta']];
        $id_incertado = $this->conn->ejecutar($sql, $parametros);
        return ['error' => 0, 'id_usuario' => $id_incertado];
    }

    public function update(array $data)
    {
        $sql = "SELECT * FROM usuarios WHERE cedula = :cedula AND id_usuario != :id_usuario";
        $parametros = [':cedula' => $data['cedula'], ':id_usuario' => $data['id_usuario']];
        $consulta = $this->conn->consultar($sql, $parametros);
        if (!empty($consulta)) {
            return ['error' => 2, 'id_usuario' => null];
        }
        $sql = "SELECT * FROM usuarios WHERE nombre_user = :nombre_user AND id_usuario != :id_usuario";
        $parametros = [':nombre_user' => $data['nombre_user'], ':id_usuario' => $data['id_usuario']];
        $consulta = $this->conn->consultar($sql, $parametros);
        if (!empty($consulta)) {
            return ['error' => 3, 'id_usuario' => null];
        }
        $sql = "UPDATE usuarios SET cedula = :cedula, nombres = :nombres, apellidos = :apellidos, nombre_user = :nombre_user, password_user = :password_user, pregunta_secreta = :pregunta_secreta, respuesta_secreta = :respuesta_secreta, nivel = :nivel
                WHERE id_usuario = :id_usuario;";
        $parametros = [':cedula' => $data['cedula'], ':nombres' => $data['nombres'], ':apellidos' => $data['apellidos'], ':nombre_user' => $data['nombre_user'], ':password_user' => $data['password_user'], ':nivel' => $data['nivel'], ':pregunta_secreta' => $data['pregunta_secreta'], ':respuesta_secreta' => $data['respuesta_secreta'], ':id_usuario' => $data['id_usuario']];
        $id_incertado = $this->conn->ejecutar($sql, $parametros);
        return ['error' => 0, 'id_usuario' => $id_incertado];
    }

    public function updatePassword(array $data)
    {
        $sql = "UPDATE usuarios SET password_user = :password_user WHERE id_usuario = :id_usuario;";
        $parametros = [':password_user' => $data['password'], ':id_usuario' => $data['id_usuario']];
        $this->conn->ejecutar($sql, $parametros);
        $this->audi->store(['ID' => $data['id_usuario'], 'accion' => 'El usuario perteneciente a este registro modifico su contraseña por haberla olvidado.']);
        return true;
    }

    public function updateNivel(int $nivel, int $id_usuario)
    {
        $sql = "UPDATE usuarios SET nivel = :nivel WHERE id_usuario = :id_usuario;";
        $parametros = [':nivel' => $nivel, ':id_usuario' => $id_usuario];
        $this->conn->ejecutar($sql, $parametros);
        $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombre_apellido"] . ' actualizo el nivel de permisologia de un usuario en el sistema SISPRE.']);
        return true;
    }

    public function updateEstado(int $estado, int $id_usuario)
    {
        if ($estado == 1) {
            $sql = "UPDATE usuarios SET estado = 1 WHERE id_usuario = :id_usuario;";
            $parametros = [':id_usuario' => $id_usuario];
            $this->conn->ejecutar($sql, $parametros);
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombre_apellido"] . ' habilito un usuario en el sistema SISPRE.']);
            return true;
        } else if ($estado == 0) {
            $sql = "UPDATE usuarios SET estado = 0 WHERE id_usuario = :id_usuario;";
            $parametros = [':id_usuario' => $id_usuario];
            $this->conn->ejecutar($sql, $parametros);
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombre_apellido"] . ' deshabilito un usuario en el sistema SISPRE.']);
            return true;
        } else if ($estado == -1) {
            $sql = "UPDATE usuarios SET estado = -1 WHERE id_usuario = :id_usuario;";
            $parametros = [':id_usuario' => $id_usuario];
            $this->conn->ejecutar($sql, $parametros);
            $this->audi->store(['ID' => $id_usuario, 'accion' => 'El usuario perteneciente a este registro ah sido bloqueado por fallar los tres intentos de inicio de sesión en el sistema SISPRE.']);
            return true;
        } else {
            return false;
        }
    }

    public function show(int $id_usuario)
    {
        $sql = "SELECT id_usuario, cedula, nombres, apellidos, nombre_user, password_user, pregunta_secreta, respuesta_secreta, nivel, estado
                FROM usuarios
                WHERE id_usuario = :id";
        $parametros = [':id' => $id_usuario];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete(int $id_usuario)
    {
        $sql = "DELETE FROM usuarios WHERE id_usuario = :id";
        $parametros = [':id' => $id_usuario];
        $this->conn->ejecutar($sql, $parametros);
        return true;
    }

    public function validar_usuario($cedula)
    {
        $sql = "SELECT id_usuario, nombre_user, cedula, nombres, apellidos, nivel FROM usuaros 
                WHERE cedula = :cedula;";
        $parametros = [':cedula' => $cedula];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function buscar_usuario($cedula)
    {
        $sql = "SELECT * FROM usuarios
                WHERE cedula = :cedula;";
        $parametros = [':cedula' => $cedula];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }
}
