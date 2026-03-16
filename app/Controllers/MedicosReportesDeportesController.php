<?php

namespace App\Controllers;

use App\Models\Deportes;

class MedicosReportesDeportesController
{

    private $deportes;

    public function __construct()
    {
        $this->deportes = new Deportes();
    }

    public function index()
    {
        $deportes = $this->deportes->getAllDeportes();
        // print_r($deportes);
        // die();
        require_once('public/views/medicos-report-deportes/index.php');
    }
}
