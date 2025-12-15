<?php

namespace App\Controllers;

use App\Controllers\Mantenimientos\MunicipiosController;
use App\Models\Mantenimientos\Grados;
use App\Models\Medicos;
use App\Models\MedicosEspecialidades;
use App\Models\MedicosSubespecialidades;

class MedicosController
{

    private $medicos;
    private $medicos_detalles;
    private $grados;
    private $especialidades;
    private $subespecialidades;
    private $medicos_especialidades;
    private $medicos_subespecialidades;
    private $municipios;

    public function __construct()
    {
        $this->medicos = new Medicos();
        $this->medicos_detalles = new MedicosDetallesController();
        $this->grados = new Grados();
        $this->especialidades = new EspecialidadesController();
        $this->subespecialidades = new SubespecialidadesController();
        $this->medicos_especialidades = new MedicosEspecialidades();
        $this->medicos_subespecialidades = new MedicosSubespecialidades();
        $this->municipios = new MunicipiosController();
    }

    public function index()
    {
        $data = $this->medicos->index();
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();
        require_once "public/views/medicos/index.php";
    }

    public function create()
    {
        $grados = $this->grados->getAllGrados();
        $especialidades = $this->especialidades->getAllEspecialidades();
        $municipios = $this->municipios->getMunicipios();
        $especialidadesJ = json_encode($especialidades);
        $especialidadesJ = json_encode($especialidadesJ);
        require_once "public/views/medicos/create.php";
    }

    public function store(array $request, array $files)
    {
        echo "<pre>";
        print_r($request);
        echo "</pre>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<pre>";
        print_r($files);
        echo "</pre>";
        die();
    }

    public function edit(int $id_medico)
    {
        require_once "public/views/medicos/edit.php";
    }

    public function update(array $request, array $files)
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
