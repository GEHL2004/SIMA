<?php

namespace App\Models\Mantenimientos;

use App\config\Conexion;
use App\Controllers\Mantenimientos\AuditoriaController;

class Municipios{

    private $conn;
    private $auditoria;

    public function __construct(){
        $this->conn = new Conexion();
        $this->auditoria = new AuditoriaController();
    }

    public function getMunicipios(){
        $sql = "SELECT id_municipio, nombre_municipio 
                FROM municipios;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function getParroquias(int $id){
        $sql = "SELECT nombre_parroquia, id_parroquia, id_municipio
                FROM parroquias WHERE id_municipio = :id_municipio;";
        $parametros = [':id_municipio' => $id];
        return $this->conn->consultar($sql, $parametros);
    }

    public function update(array $data){
        $sql1 = "SELECT nombre_municipio 
                    FROM municipios WHERE nombre_municipio = :nombre_municipio AND id_municipio != :id_municipio";
        $parametros1 = [':nombre_municipio' => $data['municipio'], ':id_municipio' => $data['ID']];
        $conculta = $this->conn->consultar($sql1, $parametros1);
        if(empty($conculta)){
            $sql2 = "UPDATE municipios SET nombre_municipio = :nombre_municipio WHERE id_municipio = :id_municipio";
            $parametros2 = [':nombre_municipio' => $data['municipio'], ':id_municipio' => $data['ID']];
            $this->conn->ejecutar($sql2, $parametros2);
            $this->auditoria->store([$_SESSION["id_usuario"], "Actualizó el nombre del municipio número ".$data['ID']]);
            return true;
        }else{
            return false;
        }
    }

    public function searchMunicipio(string $nombre_municipio){
        $sql = "SELECT id_municipio, nombre_municipio
                FROM municipios 
                WHERE nombre_municipio = :nombre_municipio;";
        $parametros = [':nombre_municipio' => $nombre_municipio];
        return $this->conn->consultar($sql, $parametros);
    }

    public function searchParroquias(string $nombre_parroquia){
        $sql = "SELECT nombre_parroquia, id_parroquia, id_municipio
                FROM parroquias 
                WHERE nombre_parroquia = :nombre_parroquia;";
        $parametros = [':nombre_parroquia' => $nombre_parroquia];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }
        
}


?>