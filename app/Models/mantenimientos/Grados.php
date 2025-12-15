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

}

?>