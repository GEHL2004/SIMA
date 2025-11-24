<?php

namespace App\Models;

use App\config\Conexion;

class SubEspecialidades
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function index()
    {
        $sql = "";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function store(array $data)
    {
    }

    public function update(array $data)
    {
    }

    public function show(int $id_sub_especialidades)
    {
        $sql = "";
        $parametros = [':id_sub_especialidades' => $id_sub_especialidades];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete(int $id_sub_especialidades){
        $sql = "";
        $parametros = [':id_sub_especialidades' => $id_sub_especialidades];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }
}
