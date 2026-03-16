<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class MedicosSubespecialidades
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function store(array $data)
    {
        try {
            $sql = "INSERT INTO medicos_subespecialidades(id_medico, id_subespecialidad, universidad_obtenido, fecha_obtencion) 
                    VALUES (:id_medico, :id_subespecialidad, :universidad_obtenido, :fecha_obtencion);";
            $parametros = [':id_medico' => $data['id_medico'], ':id_subespecialidad' => $data['id_subespecialidad'], ':universidad_obtenido' => $data['universidad_obtenido'], ':fecha_obtencion' => $data['fecha_obtencion']];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function update(int $id_medico, array $data)
    {
        $datos = [
            'id_medico' => $id_medico,
            'id_subespecialidad' => intval(str_replace('sub_', '', $data['id_subespecialidad'])),
            'universidad_obtenido' => $data['universidad_obtenido'],
            'fecha_obtencion' => $data['fecha_obtencion']
        ];
        $this->store($datos);
        return ['error' => 0, 'result' => 'Subespecialidades actualizadas correctamente'];
    }

    public function show(int $id_medico)
    {
        $sql = "SELECT MSE.id_medico_subespecialidad AS id_medico_especialidad, MSE.id_medico, CONCAT_WS('_', 'sub', SE.id_subespecialidad) AS id_especialidad, SE.nombre, 'subespecialidad' AS tipo, MSE.fecha_obtencion, MSE.universidad_obtenido
                FROM medicos_subespecialidades MSE
                INNER JOIN subespecialidades AS SE ON SE.id_subespecialidad = MSE.id_subespecialidad
                WHERE MSE.id_medico = :id_medico;";
        $parametros = [':id_medico' => $id_medico];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete(int $id_medico)
    {
        try {
            $sql = "DELETE FROM medicos_subespecialidades WHERE id_medico = :id_medico;";
            $parametros = [':id_medico' => $id_medico];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }
}
