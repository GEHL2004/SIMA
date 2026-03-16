<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class Especialidades
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function index()
    {
        $sql = "SELECT E.id_especialidad, E.nombre, E.codigo, E.activa, (SELECT COUNT(*) FROM medicos M INNER JOIN medicos_especialidades AS ME ON ME.id_medico = M.id_medico WHERE ME.id_especialidad = E.id_especialidad) AS conteo_de_medicos
                FROM especialidades E
                WHERE activa = TRUE;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function index_disable()
    {
        $sql = "SELECT E.id_especialidad, E.nombre, E.codigo, E.activa, E.id_categoria_especialidad
                FROM especialidades E
                WHERE activa = FALSE;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function getAllEspecialidades()
    {
        $sql = "SELECT E.id_especialidad, E.nombre, 'especialidad' as tipo
                FROM especialidades E
                WHERE activa = TRUE;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function store(array $data)
    {
        try {
            $sql = "SELECT * FROM especialidades WHERE nombre = :nombre;";
            $parametros = [':nombre' => $data['nombre']];
            $return = $this->conn->consultar($sql, $parametros);
            if (!empty($return)) {
                return ['error' => 1, 'id_especialidad' => null];
            }
            $sql = "SELECT * FROM especialidades WHERE codigo = :codigo;";
            $parametros = [':codigo' => $data['codigo']];
            $return = $this->conn->consultar($sql, $parametros);
            if (!empty($return)) {
                return ['error' => 2, 'id_especialidad' => null];
            }
            $sql = "INSERT INTO especialidades(nombre, codigo, id_categoria_especialidad, id_tipo_practica, id_sistema_corporal, descripcion, id_creador)
                    VALUES (:nombre, :codigo, :id_categoria_especialidad, :id_tipo_practica, :id_sistema_corporal, :descripcion, :id_creador);";
            $parametros = [':nombre' => $data['nombre'], ':codigo' => $data['codigo'], ':id_categoria_especialidad' => $data['categoria'], ':id_tipo_practica' => $data['tipo_practica'], ':id_sistema_corporal' => $data['sistema_corporal'], ':descripcion' => $data['descripcion'], ':id_creador' => $_SESSION['id_usuario']];
            $return = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_especialidad' => $return];
        } catch (Exception $e) {
            die('(Modelo). Error al registrar la especialidad: ' . $e->getMessage());
        }
    }

    public function update(array $data)
    {
        try {
            $sql = "SELECT * FROM especialidades WHERE nombre = :nombre AND id_especialidad != :id_especialidad;";
            $parametros = [':nombre' => $data['nombre'], ':id_especialidad' => $data['id_especialidad']];
            $return = $this->conn->consultar($sql, $parametros);
            if (!empty($return)) {
                return ['error' => 1, 'id_especialidad' => null];
            }
            $sql = "SELECT * FROM especialidades WHERE codigo = :codigo AND id_especialidad != :id_especialidad;";
            $parametros = [':codigo' => $data['codigo'], ':id_especialidad' => $data['id_especialidad']];
            $return = $this->conn->consultar($sql, $parametros);
            if (!empty($return)) {
                return ['error' => 2, 'id_especialidad' => null];
            }
            $sql = "UPDATE especialidades SET nombre = :nombre, codigo = :codigo, id_categoria_especialidad = :id_categoria_especialidad, id_tipo_practica = :id_tipo_practica, id_sistema_corporal = :id_sistema_corporal, descripcion = :descripcion, id_creador = :id_creador
                    WHERE id_especialidad = :id_especialidad;";
            $parametros = [':nombre' => $data['nombre'], ':codigo' => $data['codigo'], ':id_categoria_especialidad' => $data['categoria'], ':id_tipo_practica' => $data['tipo_practica'], ':id_sistema_corporal' => $data['sistema_corporal'], ':descripcion' => $data['descripcion'], ':id_creador' => $_SESSION['id_usuario'], ':id_especialidad' => $data['id_especialidad']];
            $return = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_especialidad' => $return];
        } catch (Exception $e) {
            die('(Modelo). Error al actualizar la especialidad: ' . $e->getMessage());
        }
    }

    public function show(int $id_especialidad)
    {
        try {
            $sql = "SELECT E.id_especialidad, E.nombre AS nombre_E, E.codigo, CE.id_categoria_especialidad, CE.nombre AS nombre_CE, TP.id_tipo_practica, TP.nombre AS nombre_TP, SC.id_sistema_corporal, SC.nombre AS nombre_SC, E.descripcion, E.activa, E.id_creador, E.creado_el
                    FROM especialidades E
                    INNER JOIN categorias_especialidades AS CE ON CE.id_categoria_especialidad = E.id_categoria_especialidad
                    INNER JOIN tipos_practica AS TP ON TP.id_tipo_practica = E.id_tipo_practica
                    INNER JOIN sistemas_corporales AS SC ON SC.id_sistema_corporal = E.id_sistema_corporal
                    WHERE E.id_especialidad = :id_especialidad;";
            $parametros = [':id_especialidad' => $id_especialidad];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        } catch (Exception $e) {
            die('(Modelo). Error al pedir la información de la especialidad: ' . $e->getMessage());
        }
    }

    public function delete(int $id_especialidad)
    {
        try {
            $sql = "DELETE FROM especialidades WHERE id_especialidad = :id_especialidad";
            $parametros = [':id_especialidad' => $id_especialidad];
            $result = $this->conn->consultar($sql, $parametros);
            return ['error' => 0, 'id_especialidad' => $result];
        } catch (Exception $e) {
            die('(Modelo). Error al eliminar la especialidad: ' . $e->getMessage());
        }
    }

    public function disable(int $id_especialidad)
    {
        try {
            $sql = "UPDATE especialidades SET activa = :activa
                    WHERE id_especialidad = :id_especialidad;";
            $parametros = [':activa' => false, ':id_especialidad' => $id_especialidad];
            $return = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_especialidad' => $return];
        } catch (Exception $e) {
            die('(Modelo). Error al deshabilitar la especialidad: ' . $e->getMessage());
        }
    }

    public function enable(int $id_especialidad)
    {
        try {
            $sql = "UPDATE especialidades SET activa = :activa
                    WHERE id_especialidad = :id_especialidad;";
            $parametros = [':activa' => true, ':id_especialidad' => $id_especialidad];
            $return = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_especialidad' => $return];
        } catch (Exception $e) {
            die('(Modelo). Error al habilitar la especialidad: ' . $e->getMessage());
        }
    }
}
