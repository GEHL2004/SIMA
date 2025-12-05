<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class SubEspecialidades
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function index()
    {
        $sql = "SELECT SE.id_subespecialidad, SE.nombre, SE.codigo, SE.activa, (SELECT COUNT(*) FROM medicos M WHERE M.id_subespecialidad = SE.id_subespecialidad) AS conteo_de_medicos, SE.requiere_especialidad_base
                FROM subespecialidades SE
                WHERE activa = TRUE;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function index_disable()
    {
        $sql = "SELECT SE.id_subespecialidad, SE.nombre, SE.codigo, SE.activa
                FROM subespecialidades SE
                WHERE activa = FALSE;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function store(array $data)
    {
        $sql = "SELECT * FROM subespecialidades WHERE nombre = :nombre;";
        $parametros = [':nombre' => $data['nombre']];
        $return = $this->conn->consultar($sql, $parametros);
        if (!empty($return)) {
            return ['error' => 1, 'id_subespecialidad' => null];
        }
        $sql = "SELECT * FROM subespecialidades WHERE codigo = :codigo;";
        $parametros = [':codigo' => $data['codigo']];
        $return = $this->conn->consultar($sql, $parametros);
        if (!empty($return)) {
            return ['error' => 2, 'id_subespecialidad' => null];
        }
        $sql = "INSERT INTO subespecialidades(nombre, codigo, id_especialidad, id_categoria_especialidad, id_tipo_practica, id_sistema_corporal, descripcion, requiere_especialidad_base, id_creador)
                    VALUES (:nombre, :codigo, :id_especialidad, :id_categoria_especialidad, :id_tipo_practica, :id_sistema_corporal, :descripcion, :requiere_especialidad_base, :id_creador);";
        $parametros = [':nombre' => $data['nombre'], ':codigo' => $data['codigo'], ':id_especialidad' => $data['especialidad'], ':id_categoria_especialidad' => $data['categoria'], ':id_tipo_practica' => $data['tipo_practica'], ':id_sistema_corporal' => $data['sistema_corporal'], ':descripcion' => $data['descripcion'], ':requiere_especialidad_base' => $data['RequiereEspecialidad'], ':id_creador' => $_SESSION['id_usuario']];
        $return = $this->conn->ejecutar($sql, $parametros);
        return ['error' => 0, 'id_subespecialidad' => $return];
    }

    public function update(array $data)
    {
        $sql = "SELECT * FROM subespecialidades WHERE nombre = :nombre AND id_subespecialidad != :id_subespecialidad;";
        $parametros = [':nombre' => $data['nombre'], ':id_subespecialidad' => $data['id_subespecialidad']];
        $return = $this->conn->consultar($sql, $parametros);
        if (!empty($return)) {
            return ['error' => 1, 'id_subespecialidad' => null];
        }
        $sql = "SELECT * FROM subespecialidades WHERE codigo = :codigo AND id_subespecialidad != :id_subespecialidad;";
        $parametros = [':codigo' => $data['codigo'], ':id_subespecialidad' => $data['id_subespecialidad']];
        $return = $this->conn->consultar($sql, $parametros);
        if (!empty($return)) {
            return ['error' => 2, 'id_subespecialidad' => null];
        }
        // echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        // die();
        $sql = "UPDATE subespecialidades SET nombre = :nombre, codigo = :codigo, id_especialidad = :id_especialidad, id_categoria_especialidad = :id_categoria_especialidad, id_tipo_practica = :id_tipo_practica, id_sistema_corporal = :id_sistema_corporal, descripcion = :descripcion, requiere_especialidad_base = :requiere_especialidad_base, activa = :activa, id_creador = :id_creador
                WHERE id_subespecialidad = :id_subespecialidad;";
        $parametros = [':nombre' => $data['nombre'], ':codigo' => $data['codigo'], ':id_especialidad' => $data['especialidad'], ':id_categoria_especialidad' => $data['categoria'], ':id_tipo_practica' => $data['tipo_practica'], ':id_sistema_corporal' => $data['sistema_corporal'], ':descripcion' => $data['descripcion'], ':requiere_especialidad_base' => $data['RequiereEspecialidad'], ':activa' => true, ':id_creador' => $_SESSION['id_usuario'], ':id_subespecialidad' => $data['id_subespecialidad']];
        $return = $this->conn->ejecutar($sql, $parametros);
        return ['error' => 0, 'id_subespecialidad' => $return];
    }

    public function show(int $id_subespecialidad, int $tipo)
    {
        $sql = '';
        if ($tipo == 1) {
            $sql .= 'SELECT * FROM subespecialidades;';
        } else if ($tipo == 2) {
            $sql .= 'SELECT SE.id_subespecialidad, SE.nombre AS nombre_SE, SE.id_especialidad, CE.id_categoria_especialidad, CE.nombre AS nombre_CE, TP.id_tipo_practica, TP.nombre AS nombre_TP, SC.id_sistema_corporal, SC.nombre AS nombre_SC, SE.codigo, SE.descripcion, SE.requiere_especialidad_base, SE.activa, SE.id_creador, SE.creado_el
                    FROM subespecialidades SE
                    LEFT JOIN categorias_especialidades AS CE ON CE.id_categoria_especialidad = SE.id_categoria_especialidad
                    LEFT JOIN tipos_practica AS TP ON TP.id_tipo_practica = SE.id_tipo_practica
                    LEFT JOIN sistemas_corporales AS SC ON SC.id_sistema_corporal = SE.id_sistema_corporal
                    WHERE SE.id_subespecialidad = :id_subespecialidad;';
        } else if ($tipo == 3) {
            $sql .= 'SELECT SE.id_subespecialidad, SE.nombre AS nombre_SE, E.id_especialidad, E.nombre AS nombre_E, CE.id_categoria_especialidad, CE.nombre AS nombre_CE, TP.id_tipo_practica, TP.nombre AS nombre_TP, SC.id_sistema_corporal, SC.nombre AS nombre_SC, SE.codigo, SE.descripcion, SE.requiere_especialidad_base, SE.activa, SE.id_creador, SE.creado_el
                    FROM subespecialidades SE
                    LEFT JOIN especialidades AS E ON E.id_especialidad = SE.id_especialidad
                    LEFT JOIN categorias_especialidades AS CE ON CE.id_categoria_especialidad = E.id_categoria_especialidad
                    LEFT JOIN tipos_practica AS TP ON TP.id_tipo_practica = E.id_tipo_practica
                    LEFT JOIN sistemas_corporales AS SC ON SC.id_sistema_corporal = E.id_sistema_corporal
                    WHERE SE.id_subespecialidad = :id_subespecialidad;';
        }
        $parametros = [':id_subespecialidad' => $id_subespecialidad];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete(int $id_subespecialidad)
    {
        try {
            $sql = "DELETE FROM subespecialidades WHERE id_subespecialidad = :id_subespecialidad";
            $parametros = [':id_subespecialidad' => $id_subespecialidad];
            $result = $this->conn->consultar($sql, $parametros);
            return ['error' => 0, 'id_subespecialidad' => $result];
        } catch (Exception $e) {
            die('(Modelo). Error al eliminar la sub-especialidad: ' . $e->getMessage());
        }
    }

    public function disable(int $id_subespecialidad)
    {
        try {
            $sql = "UPDATE subespecialidades SET activa = :activa
                    WHERE id_subespecialidad = :id_subespecialidad;";
            $parametros = [':activa' => false, ':id_subespecialidad' => $id_subespecialidad];
            $return = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_subespecialidad' => $return];
        } catch (Exception $e) {
            die('(Modelo). Error al deshabilitar la sub-especialidad: ' . $e->getMessage());
        }
    }

    public function enable(int $id_subespecialidad)
    {
        try {
            $sql = "UPDATE subespecialidades SET activa = :activa
                    WHERE id_subespecialidad = :id_subespecialidad;";
            $parametros = [':activa' => true, ':id_subespecialidad' => $id_subespecialidad];
            $return = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_subespecialidad' => $return];
        } catch (Exception $e) {
            die('(Modelo). Error al habilitar la sub-especialidad: ' . $e->getMessage());
        }
    }
}
