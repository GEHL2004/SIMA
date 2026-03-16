<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class MedicosDocumentos
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function store(array $data)
    {
        try {
            $sql = "INSERT INTO documentos_medicos(id_medico, nombre_documento_original, nombre_documento_directorio) 
                    VALUES (:id_medico, :nombre_documento_original, :nombre_documento_directorio);";
            $parametros = [':id_medico' => $data['id_medico'], ':nombre_documento_original' => $data['nombre_original'], ':nombre_documento_directorio' => $data['nombre_en_directorio']];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'result' => $result];
        } catch (Exception $e) {
            return ['error' => 1, 'result' => $e->getMessage()];
        }
    }

    public function update(int $id_medico, array $data) {}

    public function show(int $id_medico)
    {
        $sql = "SELECT DM.id_documento, DM.id_medico, DM.nombre_documento_original AS 'nombre_documento', DM.nombre_documento_directorio AS 'ruta_archivo' 
                FROM documentos_medicos DM
                INNER JOIN medicos AS M ON M.id_medico = DM.id_medico 
                WHERE M.id_medico = :id_medico;";
        $parametros = [':id_medico' => $id_medico];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function buscar_nombre_en_directorio(int $id_documento)
    {
        $sql = "SELECT nombre_documento_directorio FROM documentos_medicos WHERE id_documento = :id_documento;";
        $parametros = [':id_documento' => $id_documento];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete_documento_individual(int $id_documento) {
        $sql = "DELETE FROM documentos_medicos WHERE id_documento = :id_documento;";
        $parametros = [':id_documento' => $id_documento];
        $this->conn->ejecutar($sql, $parametros);
        return true;
    }

    public function delete_documentos(int $id_medico) {
        $sql = "DELETE FROM documentos_medicos WHERE id_medico = :id_medico;";
        $parametros = [':id_medico' => $id_medico];
        $this->conn->ejecutar($sql, $parametros);
        return true;
    }
}
