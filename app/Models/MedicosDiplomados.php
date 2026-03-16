<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class MedicosDiplomados
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function store(array $data)
    {
        try {
            $sql = "INSERT INTO medicos_diplomados(id_medico, nombre_diplomado, universidad_obtenido, fecha_obtencion) 
                    VALUES (:id_medico, :nombre_diplomado, :universidad_obtenido, :fecha_obtencion);";
            $parametros = [':id_medico' => $data['id_medico'], ':nombre_diplomado' => $data['nombre'], ':universidad_obtenido' => $data['universidad_obtenido'], ':fecha_obtencion' => $data['fecha_obtencion']];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function update(int $id_medico, array $data) {
        self::delete($id_medico);
        foreach ($data as $diplomado) {
            $this->store(['id_medico' => $id_medico, 'nombre' => $diplomado['nombre'], 'universidad_obtenido' => $diplomado['universidad_obtenido'], 'fecha_obtencion' => $diplomado['fecha_obtencion']]);
        }
        return ['error' => 0, 'result' => 'Diplomados actualizados correctamente'];
    }

    public function show(int $id_medico)
    {
        $sql = "SELECT MD.id_medico_diplomado AS id_diplomado, MD.id_medico, MD.nombre_diplomado AS nombre, MD.universidad_obtenido, MD.fecha_obtencion
                FROM medicos_diplomados MD
                WHERE MD.id_medico = :id_medico;";
        $parametros = [':id_medico' => $id_medico];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete(int $id_medico) {
        try {
            $sql = "DELETE FROM medicos_diplomados WHERE id_medico = :id_medico;";
            $parametros = [':id_medico' => $id_medico];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }
}
