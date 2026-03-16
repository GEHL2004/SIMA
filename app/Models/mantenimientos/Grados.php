<?php

namespace App\Models\Mantenimientos;

use App\config\Conexion;

class Grados{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function getAllGrados(){
        $sql = "SELECT id_grado_academico, nombre_grado FROM grados_academicos;";
        $result = $this->conn->consultar($sql);
        return $result;
    }

    public function searchGrados(string $nombre_grado){
        $sql = "SELECT id_grado_academico, nombre_grado FROM grados_academicos 
                WHERE nombre_grado LIKE :nombre_grado;";
        $parametros = [':nombre_grado' => "%$nombre_grado%"];
        return $this->conn->consultar($sql, $parametros);
    }

}

?>