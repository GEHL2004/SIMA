<?php

namespace App\Controllers;

use App\Models\Mantenimientos\Municipios;

class MedicosReportesMunicpiosController{

    private $municipios;

    public function __construct()
    {
        $this->municipios = new Municipios();
    }

    public function index(){
        $municipios = $this->municipios->getMunicipios();
        // print_r($municipios);
        // die();
        require_once('public/views/medicos-report-municipios/index.php');
    }

}

?>