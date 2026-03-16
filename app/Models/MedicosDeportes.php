<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class MedicosDeportes
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function store(int $id_deporte, int $id_medico)
    {
        try {
            $sql = "INSERT INTO medicos_deportes(id_medico, id_deporte) 
                    VALUES (:id_medico, :id_deporte);";
            $parametros = [':id_medico' => $id_medico, ':id_deporte' => $id_deporte];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function update(int $id_medico, array $data) {
        self::delete($id_medico);
        foreach ($data as $deporte) {
            $this->store($deporte, $id_medico);
        }
        return ['error' => 0, 'result' => 'Deportes actualizados correctamente'];
    }

    public function show(int $id_medico)
    {
        $sql = "SELECT MD.id_medico_deporte, MD.id_deporte, MD.id_medico, D.nombre
                FROM medicos_deportes MD
                INNER JOIN deportes AS D ON D.id_deporte = MD.id_deporte
                WHERE MD.id_medico = :id_medico;";
        $parametros = [':id_medico' => $id_medico];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete(int $id_medico) {
        try {
            $sql = "DELETE FROM medicos_deportes WHERE id_medico = :id_medico;";
            $parametros = [':id_medico' => $id_medico];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }
}
