<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class SistemasCorporales
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function index()
    {
        $sql = "SELECT * FROM sistemas_corporales;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function store(array $data)
    {
        try {
            $sql = "SELECT * FROM sistemas_corporales WHERE nombre = :nombre;";
            $parametros = [':nombre' => $data['nombre']];
            $result = $this->conn->consultar($sql, $parametros);
            if (!empty($result)) {
                return 1;
            }
            $sql = "INSERT INTO sistemas_corporales(nombre, descripcion, id_creador) 
                    VALUES(:nombre, :descripcion, :id_creador)";
            $parametros = [':nombre' => $data['nombre'], ':descripcion' => $data['descripcion'], ':id_creador' => $_SESSION['id_usuario']];
            $this->conn->ejecutar($sql, $parametros);
        } catch (Exception $e) {
            die('(Modelo). Error al registrar el sistema corporal: ' . $e->getMessage());
        }
    }

    public function update(array $data)
    {
        try {
            $sql = "SELECT * FROM sistemas_corporales 
                    WHERE nombre = :nombre AND id_sistema_corporal != :id_sistema_corporal;";
            $parametros = [':nombre' => $data['nombre'], ':id_sistema_corporal' => $data['id_sistema_corporal']];
            $result = $this->conn->consultar($sql, $parametros);
            if (!empty($result)) {
                return 1;
            }
            $sql = "UPDATE sistemas_corporales SET nombre = :nombre, descripcion = :descripcion, id_creador = :id_creador 
                    WHERE id_sistema_corporal = :id_sistema_corporal;";
            $parametros = [':nombre' => $data['nombre'], ':descripcion' => $data['descripcion'], ':id_creador' => $_SESSION['id_usuario'], ':id_sistema_corporal' => $data['id_sistema_corporal']];
            $this->conn->ejecutar($sql, $parametros);
        } catch (Exception $e) {
            die('(Modelo). Error al actualizar el sistema corporal: ' . $e->getMessage());
        }
    }

    public function show(int $id_sistema_corporal)
    {
        try {
            $sql = "SELECT id_sistema_corporal, nombre, descripcion FROM sistemas_corporales
                WHERE id_sistema_corporal = :id_sistema_corporal;";
            $parametros = [':id_sistema_corporal' => $id_sistema_corporal];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        } catch (Exception $e) {
            die('(Modelo). Error al obtener el sistema corporal: ' . $e->getMessage());
        }
    }

    public function delete(int $id_sistema_corporal)
    {
        try {
            $sql = "DELETE FROM sistemas_corporales
                WHERE id_sistema_corporal = :id_sistema_corporal;";
            $parametros = [':id_sistema_corporal' => $id_sistema_corporal];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        } catch (Exception $e) {
            die('(Modelo). Error al eliminar el sistema corporal: ' . $e->getMessage());
        }
    }
}
