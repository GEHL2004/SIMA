<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class MedicosDetalles
{

    private $conn;
    private $baseModel;

    public function __construct()
    {
        $this->conn = new Conexion();
        $this->baseModel = new BaseModels;
    }

    public function store(array $data)
    {
        try {
            $sql = "INSERT INTO medicos_detalles(id_medico, fecha_nacimiento, lugar_nacimiento, tipo_sangre, universidad_graduado, fecha_egreso_universidad, fecha_incripcion, matricula_ministerio, lugar_de_trabajo, estado)
                    VALUES(:id_medico, :fecha_nacimiento, :lugar_nacimiento, :tipo_sangre, :universidad_graduado, :fecha_egreso_universidad, :fecha_incripcion, :matricula_ministerio, :lugar_de_trabajo, :estado);";
            $parametros = [':id_medico' => $data['id_medico'], ':fecha_nacimiento' => $data['fecha_nacimiento'], ':lugar_nacimiento' => $data['lugar_nacimiento'], ':tipo_sangre' => $data['tipo_sangre'], ':universidad_graduado' => $data['universidad_graduado'], ':fecha_egreso_universidad' => $data['fecha_egreso_universidad'], ':fecha_incripcion' => $data['fecha_incripcion'], ':matricula_ministerio' => $data['matricula_ministerio'], ':lugar_de_trabajo' => $data['lugar_de_trabajo'], ':estado' => $data['estado']];
            $id_medico_detalles = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_medico_detalles' => $id_medico_detalles];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function update(array $data)
    {
        try {
            $sql = "UPDATE medicos_detalles SET  fecha_nacimiento = :fecha_nacimiento, lugar_nacimiento = :lugar_nacimiento, tipo_sangre = :tipo_sangre, universidad_graduado = :universidad_graduado, fecha_egreso_universidad = :fecha_egreso_universidad, fecha_incripcion = :fecha_incripcion, matricula_ministerio = :matricula_ministerio, lugar_de_trabajo = :lugar_de_trabajo, estado = :estado
                    WHERE id_medico = :id_medico;";
            $parametros = [':id_medico' => $data['id_medico'], ':fecha_nacimiento' => $data['fecha_nacimiento'], ':lugar_nacimiento' => $data['lugar_nacimiento'], ':tipo_sangre' => $data['tipo_sangre'], ':universidad_graduado' => $data['universidad_graduado'], ':fecha_egreso_universidad' => $data['fecha_egreso_universidad'], ':fecha_incripcion' => $data['fecha_incripcion'], ':matricula_ministerio' => $data['matricula_ministerio'], ':lugar_de_trabajo' => $data['lugar_de_trabajo'], ':estado' => $data['estado']];
            $id_medico_detalles = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_medico_detalles' => $id_medico_detalles];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function changeStatus(int $id_medico, int $estado)
    {
        try {
            $sql = "UPDATE medicos_detalles SET estado = :estado
                    WHERE id_medico = :id_medico;";
            $parametros = [':estado' => $estado, ':id_medico' => $id_medico];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            die('Error al cambiar el estado del medico (Modelo): ' . $e->getMessage());
        }
    }

    public function delete(int $id_medico)
    {
        try {
            $sql = "DELETE FROM medicos_detalles WHERE id_medico = :id_medico;";
            $parametros = [':id_medico' => $id_medico];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function cantMedicosEstados(){
        $sql = "SELECT 
                    SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as activo,
                    SUM(CASE WHEN estado = 2 THEN 1 ELSE 0 END) as desincorporado,
                    SUM(CASE WHEN estado = 3 THEN 1 ELSE 0 END) as jubilado,
                    SUM(CASE WHEN estado = 4 THEN 1 ELSE 0 END) as fallecido,
                    SUM(CASE WHEN estado = 5 THEN 1 ELSE 0 END) as traslado
                FROM medicos_detalles";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }
}
