<?php

namespace App\Models;

use App\config\Conexion;

class EspecialidadesRequeridasParaSubespecialidades{

    private $conn;

    public function __construct(){
        $this->conn = new Conexion();
    }

    public function getEspecialidadesRequeridasParaSubespecialidades(int $id_subespecialidad){
        $sql = "SELECT id_especialidad_requerida_para_subespecialidad, id_especialidad, id_subespecialidad
                FROM especialidades_requeridas_para_subespecialidades
                WHERE id_subespecialidad = :id_subespecialidad;";
        $parametros = [':id_subespecialidad' => $id_subespecialidad];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function store(int $id_especialidad, int $id_subespecialidad){
        $sql = "INSERT INTO especialidades_requeridas_para_subespecialidades(id_especialidad, id_subespecialidad)
                VALUES (:id_especialidad, :id_subespecialidad);";
        $parametros = [':id_especialidad' => $id_especialidad, ':id_subespecialidad' => $id_subespecialidad];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete(int $id_especialidad_requerida_para_subespecialidad){
        $sql = "DELETE FROM especialidades_requeridas_para_subespecialidades
                WHERE id_especialidad_requerida_para_subespecialidad = :id_especialidad_requerida_para_subespecialidad;";
        $parametros = [':id_especialidad_requerida_para_subespecialidad' => $id_especialidad_requerida_para_subespecialidad];
        // $this->conn->deshabilitar_revision_foreign_key();
        $result = $this->conn->consultar($sql, $parametros);
        // $this->conn->habilitar_revision_foreign_key();
        return $result;
    }

}

?>