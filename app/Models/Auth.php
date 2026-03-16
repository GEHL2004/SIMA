<?php

namespace App\Models;

use App\config\Conexion;


class Auth{

    public static function login(string $user){
        $conn = new Conexion();
        $sql = "SELECT id_usuario, nombres, apellidos, nombre_user, password_user, nivel, estado FROM usuarios 
                WHERE nombre_user = :nombre_user";
        $parametros = [':nombre_user' => $user];
        $result = $conn->consultar($sql, $parametros);
        return $result;
    }

}

?>