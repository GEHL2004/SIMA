<?php

namespace App\Controllers;

use App\Models\Especialidades;

class MedicosReportesEspecialidadesController{

    private $especialidades;

    public function __construct()
    {
        $this->especialidades = new Especialidades();
    }

    public function index(){
        $especialidades = $this->especialidades->getAllEspecialidades();
        // print_r($especialidades);
        // die();
        require_once('public/views/medicos-report-especialidades/index.php');
    }

}

?>