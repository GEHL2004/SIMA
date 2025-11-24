<?php

namespace App\Controllers;

use App\Models\SubEspecialidades;

class SubEspecialidadesController
{

    private $sub_especialidades;

    public function __construct()
    {
        $this->sub_especialidades = new SubEspecialidades();
    }

    public function index()
    {
        require_once "public/views/sub_especialidades/index.php";
    }

    public function create()
    {
        require_once "public/views/sub_especialidades/create.php";
    }

    public function store(array $request)
    {
    }

    public function edit(int $id_sub_especialidades)
    {
        require_once "public/views/sub_especialidades/edit.php";
    }

    public function update(array $request)
    {
    }

    public function show(int $id_sub_especialidades)
    {
        require_once "public/views/sub_especialidades/show.php";
    }

    public function delete(int $id_sub_especialidades)
    {
    }
}
