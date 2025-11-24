<?php

namespace App\Models;

use App\config\Conexion;

class Pagos
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

    public function show(int $id_pago)
    {
        $sql = "";
        $parametros = [':id_pago' => $id_pago];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete(int $id_pago){
        $sql = "";
        $parametros = [':id_pago' => $id_pago];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }
}
