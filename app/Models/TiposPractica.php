<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class TiposPractica
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function index()
    {
        $sql = "SELECT * FROM tipos_practica;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function store(array $data)
    {
        try {
            $sql = "SELECT * FROM tipos_practica WHERE nombre = :nombre;";
            $parametros = [':nombre' => $data['nombre']];
            $result = $this->conn->consultar($sql, $parametros);
            if (!empty($result)) {
                return 1;
            }
            $sql = "INSERT INTO tipos_practica(nombre, codigo, id_creador) 
                    VALUES(:nombre, :codigo, :id_creador)";
            $parametros = [':nombre' => $data['nombre'], ':codigo' => $data['codigo'], ':id_creador' => $_SESSION['id_usuario']];
            $this->conn->ejecutar($sql, $parametros);
        } catch (Exception $e) {
            die('(Modelo). Error al registrar el sistema corporal: ' . $e->getMessage());
        }
    }

    public function update(array $data)
    {
        try {
            $sql = "SELECT * FROM tipos_practica 
                    WHERE nombre = :nombre AND id_tipo_practica != :id_tipo_practica;";
            $parametros = [':nombre' => $data['nombre'], ':id_tipo_practica' => $data['id_tipo_practica']];
            $result = $this->conn->consultar($sql, $parametros);
            if (!empty($result)) {
                return 1;
            }
            $sql = "UPDATE tipos_practica SET nombre = :nombre, codigo = :codigo, id_creador = :id_creador 
                    WHERE id_tipo_practica = :id_tipo_practica;";
            $parametros = [':nombre' => $data['nombre'], ':codigo' => $data['codigo'], ':id_creador' => $_SESSION['id_usuario'], ':id_tipo_practica' => $data['id_tipo_practica']];
            $this->conn->ejecutar($sql, $parametros);
        } catch (Exception $e) {
            die('(Modelo). Error al actualizar el sistema corporal: ' . $e->getMessage());
        }
    }

    public function show(int $id_tipo_practica)
    {
        try {
            $sql = "SELECT id_tipo_practica, nombre, codigo FROM tipos_practica
                WHERE id_tipo_practica = :id_tipo_practica;";
            $parametros = [':id_tipo_practica' => $id_tipo_practica];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        } catch (Exception $e) {
            die('(Modelo). Error al obtener el sistema corporal: ' . $e->getMessage());
        }
    }

    public function delete(int $id_tipo_practica)
    {
        try {
            $sql = "DELETE FROM tipos_practica
                WHERE id_tipo_practica = :id_tipo_practica;";
            $parametros = [':id_tipo_practica' => $id_tipo_practica];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        } catch (Exception $e) {
            die('(Modelo). Error al eliminar el sistema corporal: ' . $e->getMessage());
        }
    }
}
