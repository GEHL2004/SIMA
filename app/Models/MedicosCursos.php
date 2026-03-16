<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class MedicosCursos
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function store(array $data)
    {
        try {
            $sql = "INSERT INTO medicos_cursos(id_medico, nombre_curso, universidad_obtenido, fecha_obtencion) 
                    VALUES (:id_medico, :nombre_curso, :universidad_obtenido, :fecha_obtencion);";
            $parametros = [':id_medico' => $data['id_medico'], ':nombre_curso' => $data['nombre'], ':universidad_obtenido' => $data['universidad_obtenido'], ':fecha_obtencion' => $data['fecha_obtencion']];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function update(int $id_medico, array $data) {
        self::delete($id_medico);
        foreach ($data as $curso) {
            $this->store(['id_medico' => $id_medico, 'nombre' => $curso['nombre'], 'universidad_obtenido' => $curso['universidad_obtenido'], 'fecha_obtencion' => $curso['fecha_obtencion']]);
        }
        return ['error' => 0, 'result' => 'Cursos actualizados correctamente'];
    }

    public function show(int $id_medico)
    {
        $sql = "SELECT MC.id_medico_curso AS id_curso, MC.id_medico, MC.nombre_curso AS nombre, MC.universidad_obtenido, MC.fecha_obtencion
                FROM medicos_cursos MC
                WHERE MC.id_medico = :id_medico;";
        $parametros = [':id_medico' => $id_medico];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete(int $id_medico) {
        try {
            $sql = "DELETE FROM medicos_cursos WHERE id_medico = :id_medico;";
            $parametros = [':id_medico' => $id_medico];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }
}
