<?php

namespace App\Controllers;

use App\Models\SubEspecialidades;

class MedicosReportesSubespecialidadesController{

    private $subespecialidades;

    public function __construct()
    {
        $this->subespecialidades = new SubEspecialidades();
    }

    public function index(){
        $subespecialidades = $this->subespecialidades->getAllSubespecialidades();
        // print_r($subespecialidades);
        // die();
        require_once('public/views/medicos-report-subespecialidades/index.php');
    }

}

?>