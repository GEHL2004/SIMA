<?php

namespace App\Controllers;

use App\Models\Pagos;

class PagosController
{

    private $pagos;

    public function __construct()
    {
        $this->pagos = new Pagos();
    }

    public function index()
    {
        require_once "public/views/pagos/index.php";
    }

    public function create()
    {
        require_once "public/views/pagos/create.php";
    }

    public function store(array $request)
    {
    }

    public function edit(int $id_pago)
    {
        require_once "public/views/pagos/edit.php";
    }

    public function update(array $request)
    {
    }

    public function show(int $id_pago)
    {
        require_once "public/views/pagos/show.php";
    }
}
