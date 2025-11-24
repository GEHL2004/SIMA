<?php

namespace App\Controllers;

use App\Models\Medicos;

class MedicosController
{

    private $medicos;

    public function __construct()
    {
        $this->medicos = new Medicos();
    }

    public function index()
    {
        require_once "public/views/medicos/index.php";
    }

    public function create()
    {
        require_once "public/views/medicos/create.php";
    }

    public function store(array $request)
    {
    }

    public function edit(int $id_medico)
    {
        require_once "public/views/medicos/edit.php";
    }

    public function update(array $request)
    {
    }

    public function show(int $id_medico)
    {
        require_once "public/views/medicos/show.php";
    }

    public function delete(int $id_medico)
    {
    }
}
