<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class CambiosEstados
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function listarTodos()
    {
        try {
            $sql = "SELECT * FROM cambios_estados_medicos 
                    ORDER BY creado_el DESC;";
            $result = $this->conn->consultar($sql, []);
            return $result;
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function obtenerPorMedico($id_medico)
    {
        try {
            $sql = "SELECT * 
                    FROM cambios_estados_medicos 
                    WHERE id_medico = :id_medico 
                    ORDER BY creado_el DESC;";
            $parametros = [':id_medico' => $id_medico];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function obtenerUltimoEstadoMedico(int $medico)
    {
        try {
            $sql = "SELECT * FROM cambios_estados_medicos 
                    WHERE id_medico = :id_medico 
                    ORDER BY creado_el DESC 
                    LIMIT 1;";
            $parametros = [':id_medico' => $medico];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function obtenerPorId(int $id_cambio_estado_medico)
    {
        try {
            $sql = "SELECT * 
                    FROM cambios_estados_medicos 
                    WHERE id_cambio_estado_medico = :id_cambio_estado_medico;";
            $parametros = [':id_cambio_estado_medico' => $id_cambio_estado_medico];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function store(array $data)
    {
        try {
            $sql = "INSERT INTO cambios_estados_medicos(id_medico, estado, fecha_colocacion, fecha_final) 
                    VALUES (:id_medico, :estado, :fecha_colocacion, :fecha_final);";
            $parametros = [
                ':id_medico' => $data['id_medico'],
                ':estado' => $data['estado'],
                ':fecha_colocacion' => $data['fecha_colocacion'] ?? null,
                ':fecha_final' => null
            ];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {
            $sql = "UPDATE cambios_estados_medicos SET fecha_final = :fecha_final 
                    WHERE id_cambio_estado_medico = :id_cambio_estado_medico;";
            $parametros = [
                ':fecha_final' => $data['fecha_final'],
                ':id_cambio_estado_medico' => $data['id_cambio_estado_medico']
            ];
            $result = $this->conn->ejecutar($sql, $parametros);
            $this->store($data);
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function delete(int $id_cambio_estado_medico)
    {
        try {
            $sql = "DELETE FROM cambios_estados_medicos 
                    WHERE id_cambio_estado_medico = :id;";
            $parametros = [':id' => $id_cambio_estado_medico];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }
}
