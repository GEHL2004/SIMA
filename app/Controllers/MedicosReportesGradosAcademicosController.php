<?php

namespace App\Controllers;

use App\Models\Mantenimientos\Grados;

class MedicosReportesGradosAcademicosController{

    private $grados;

    public function __construct()
    {
        $this->grados = new Grados();
    }

    public function index(){
        $grados = $this->grados->getAllGrados();
        // print_r($grados);
        // die();
        require_once('public/views/medicos-report-grados-academicos/index.php');
    }

}

?>