<?php

namespace App\Controllers;

use App\Models\MedicosDetalles;

class MedicosDetallesController
{

    private $medicos_detalles;

    public function __construct()
    {
        $this->medicos_detalles = new MedicosDetalles();
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
