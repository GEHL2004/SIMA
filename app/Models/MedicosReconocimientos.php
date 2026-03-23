<?php

namespace App\Models;

use App\config\Conexion;

class MedicosReconocimientos
{
    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function index()
    {
        $sql = "SELECT M.id_medico, CONCAT_WS(' ', M.nombres, M.apellidos) AS nombres_apellidos, MD.fecha_egreso_universidad, COUNT(CASE WHEN MR.fecha_de_entrega IS NOT NULL AND MR.fecha_de_entrega != '0000-00-00' THEN 1 END) AS cant_recibidos, COUNT(CASE WHEN MR.fecha_de_entrega IS NULL OR MR.fecha_de_entrega = '0000-00-00' THEN 1 END) AS cant_faltantes, TIMESTAMPDIFF(YEAR, MD.fecha_egreso_universidad, CURDATE()) AS años_transcurridos_graduado
				FROM medicos_reconocimientos MR
                INNER JOIN medicos AS M ON M.id_medico = MR.id_medico
                INNER JOIN medicos_detalles AS MD ON MD.id_medico = M.id_medico
                WHERE MD.estado = TRUE
                GROUP BY M.id_medico;";
        $result = $this->conn->consultar($sql);
        return $result;
    }

    public function getDataMedico($id_medico)
    {
        $sql = "SELECT M.id_medico, MD.id_medico_detalles, MR.id_medico_reconocimiento, MR.fecha_de_entrega, CONCAT_WS(' ', M.nombres, M.apellidos) AS nombres_apellidos, MR.tipo_reconocimiento, TIMESTAMPDIFF(YEAR, MR.fecha_de_entrega, CURDATE()) AS años_desde_entrega, MD.fecha_egreso_universidad, TIMESTAMPDIFF(YEAR, MD.fecha_egreso_universidad, CURDATE()) AS años_transcurridos_graduado  
				FROM medicos_reconocimientos MR
                INNER JOIN medicos AS M ON M.id_medico = MR.id_medico
                INNER JOIN medicos_detalles AS MD ON MD.id_medico = M.id_medico
                WHERE M.id_medico = :id_medico;";
        $result = $this->conn->consultar($sql, [':id_medico' => $id_medico]);
        return $result;
    }

    public function store($data)
    {
        $sql = "INSERT INTO medicos_reconocimientos (id_medico, tipo_reconocimiento, fecha_de_entrega) 
                VALUES (:id_medico, :tipo_reconocimiento, :fecha_de_entrega)";
        $parametros = [':id_medico' => $data['id_medico'], ':tipo_reconocimiento' => $data['tipo_reconocimiento'], ':fecha_de_entrega' => $data['fecha_de_entrega']];
        $result = $this->conn->ejecutar($sql, $parametros);
        return $result;
    }

    public function update(array $data)
    {
        $sql = "UPDATE medicos_reconocimientos 
                SET fecha_de_entrega = :fecha_de_entrega
                WHERE id_medico_reconocimiento = :id_medico_reconocimiento;";
        $parametros = [':fecha_de_entrega' => $data['fecha_entrega'], ':id_medico_reconocimiento' => $data['id_medico_reconocimiento']];
        $result = $this->conn->ejecutar($sql, $parametros);
        return $result;
    }

    public function delete($data)
    {
        $sql = "DELETE FROM medicos_reconocimientos 
                WHERE id_medico_reconocimiento = :id_medico_reconocimiento";
        $parametros = [':id_medico_reconocimiento' => $data['id_medico_reconocimiento']];
        $result = $this->conn->ejecutar($sql, $parametros);
        return $result;
    }

    public function listadoReconocimientos($tipo, $intervalo, $limite, $estado = 'faltantes')
    {
        if ($estado === 'faltantes') {
            $condicion_entrega = "(MR.fecha_de_entrega IS NULL OR MR.fecha_de_entrega = '0000-00-00')";
        } elseif ($estado === 'entregados') {
            $condicion_entrega = "(MR.fecha_de_entrega IS NOT NULL AND MR.fecha_de_entrega != '0000-00-00')";
        } else {
            $condicion_entrega = "1=1";
        }

        $sql = "SELECT M.id_medico, REPLACE(CONCAT_WS(' ', M.nombres, M.apellidos), '_', ' ') AS nombres_apellidos, CONCAT_WS('-', M.telefono_inicio, M.telefono_restante) AS telefono, M.correo, MD.fecha_egreso_universidad, TIMESTAMPDIFF(YEAR, MD.fecha_egreso_universidad, CURDATE()) AS años_transcurridos_graduado
                FROM medicos_reconocimientos MR
                INNER JOIN medicos AS M ON M.id_medico = MR.id_medico
                INNER JOIN medicos_detalles AS MD ON MD.id_medico = M.id_medico
                WHERE {$condicion_entrega} AND MR.tipo_reconocimiento = :tipo AND MD.fecha_egreso_universidad <= DATE_SUB(CURDATE(), INTERVAL :intervalo YEAR)
                ORDER BY MD.fecha_egreso_universidad DESC
                LIMIT :limite";

        $parametros = [
            ':tipo' => $tipo,
            ':intervalo' => $intervalo,
            ':limite' => $limite
        ];

        return $this->conn->consultar($sql, $parametros);
    }
}
