<?php

namespace App\Controllers;

use App\Models\Especialidades;

class EspecialidadesController
{

    private $especialidades;

    public function __construct()
    {
        $this->especialidades = new Especialidades();
    }

    public function index()
    {
        require_once "public/views/especialidades/index.php";
    }

    public function create()
    {
        require_once "public/views/especialidades/create.php";
    }

    public function store(array $request)
    {
    }

    public function edit(int $id_especialidad)
    {
        require_once "public/views/especialidades/edit.php";
    }

    public function update(array $request)
    {
    }

    public function show(int $id_especialidad)
    {
        require_once "public/views/especialidades/show.php";
    }

    public function delete(int $id_especialidad)
    {
    }
}
