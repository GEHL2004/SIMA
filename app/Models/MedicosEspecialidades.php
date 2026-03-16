<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class MedicosEspecialidades
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function store(array $data)
    {
        try {
            $sql = "INSERT INTO medicos_especialidades(id_medico, id_especialidad, universidad_obtenido, fecha_obtencion) 
                    VALUES (:id_medico, :id_especialidad, :universidad_obtenido, :fecha_obtencion);";
            $parametros = [':id_medico' => $data['id_medico'], ':id_especialidad' => $data['id_especialidad'], ':universidad_obtenido' => $data['universidad_obtenido'], ':fecha_obtencion' => $data['fecha_obtencion']];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            echo $e->getMessage();
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function update(int $id_medico, array $data)
    {
        $datos = [
            'id_medico' => $id_medico,
            'id_especialidad' => intval(str_replace('esp_', '', $data['id_especialidad'])),
            'universidad_obtenido' => $data['universidad_obtenido'],
            'fecha_obtencion' => $data['fecha_obtencion']
        ];
        $this->store($datos);
        return ['error' => 0, 'result' => 'Especialidades actualizadas correctamente'];
    }

    public function show(int $id_medico)
    {
        $sql = "SELECT ME.id_medico_especialidad AS id_medico_especialidad, ME.id_medico, CONCAT_WS('_', 'esp', E.id_especialidad) AS id_especialidad, E.nombre, 'especialidad' AS tipo, ME.fecha_obtencion, ME.universidad_obtenido
                FROM medicos_especialidades ME
                INNER JOIN especialidades AS E ON E.id_especialidad = ME.id_especialidad
                WHERE ME.id_medico = :id_medico;";
        $parametros = [':id_medico' => $id_medico];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete(int $id_medico)
    {
        try {
            $sql = "DELETE FROM medicos_especialidades WHERE id_medico = :id_medico;";
            $parametros = [':id_medico' => $id_medico];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }
}
