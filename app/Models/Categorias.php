<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class Categorias
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function index()
    {
        $sql = "SELECT id_categoria_especialidad, nombre, descripcion FROM categorias_especialidades;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function getAllCategorias()
    {
        $sql = "SELECT id_categoria_especialidad, nombre FROM categorias_especialidades;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function store(array $data)
    {
        try {
            $sql = "SELECT * FROM categorias_especialidades WHERE nombre = :nombre;";
            $parametros = [':nombre' => $data['nombre']];
            $result = $this->conn->consultar($sql, $parametros);
            if (!empty($result)) {
                return ['error' => 1, 'id_categoria' => null];
            }
            $sql = "INSERT INTO categorias_especialidades(nombre, descripcion, id_creador) 
                    VALUES(:nombre, :descripcion, :id_creador)";
            $parametros = [':nombre' => $data['nombre'], ':descripcion' => $data['descripcion'], ':id_creador' => $_SESSION['id_usuario']];
            $id_incertado = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_categoria' => $id_incertado];
        } catch (Exception $e) {
            die('(Modelo). Error al registrar la categoria: ' . $e->getMessage());
        }
    }

    public function update(array $data)
    {
        try {
            $sql = "SELECT * FROM categorias_especialidades 
                    WHERE nombre = :nombre AND id_categoria_especialidad != :id_categoria_especialidad;";
            $parametros = [':nombre' => $data['nombre'], ':id_categoria_especialidad' => $data['id_categoria']];
            $result = $this->conn->consultar($sql, $parametros);
            if (!empty($result)) {
                return ['error' => 1, 'id_categoria' => null];
            }
            $sql = "UPDATE categorias_especialidades SET nombre = :nombre, descripcion = :descripcion, id_creador = :id_creador 
                    WHERE id_categoria_especialidad = :id_categoria_especialidad;";
            $parametros = [':nombre' => $data['nombre'], ':descripcion' => $data['descripcion'], ':id_creador' => $_SESSION['id_usuario'], ':id_categoria_especialidad' => $data['id_categoria']];
            $id_actualizado = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_categoria' => $id_actualizado];
        } catch (Exception $e) {
            die('(Modelo). Error al actualizar la categoria: ' . $e->getMessage());
        }
    }

    public function show(int $id_categoria_especialidad)
    {
        try {
            $sql = "SELECT id_categoria_especialidad, nombre, descripcion FROM categorias_especialidades
                WHERE id_categoria_especialidad = :id_categoria_especialidad;";
            $parametros = [':id_categoria_especialidad' => $id_categoria_especialidad];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        } catch (Exception $e) {
            die('(Modelo). Error al obtener la categoria: ' . $e->getMessage());
        }
    }

    public function delete(int $id_categoria_especialidad)
    {
        try {
            $sql = "DELETE FROM categorias_especialidades
                WHERE id_categoria_especialidad = :id_categoria_especialidad;";
            $parametros = [':id_categoria_especialidad' => $id_categoria_especialidad];
            $result = $this->conn->consultar($sql, $parametros);
            return ['error' => 0, 'resultado' => $result];
        } catch (Exception $e) {
            die('(Modelo). Error al eliminar la categoria: ' . $e->getMessage());
        }
    }
}
