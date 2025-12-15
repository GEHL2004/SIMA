<?php

namespace App\Models;

use App\config\Conexion;

class MedicosDetalles
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function store(array $data)
    {
    }

    public function update(int $id_medico, array $data)
    {
    }

    public function delete(int $id_medico)
    {
    }
}
