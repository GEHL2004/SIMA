<?php

namespace App\Controllers\Mantenimientos;

use App\Controllers\AlertasController;
use App\Models\Mantenimientos\Municipios;

class MunicipiosController
{

    private $municipios;

    public function __construct()
    {
        $this->municipios = new Municipios();
    }

    public function index()
    {
        $data = $this->municipios->getMunicipios();
        $dataJSON = json_encode($data);
        $dataJSON = json_encode($dataJSON);
        // require_once("public/views/Mantenimientos/municipios/index.php");
    }

    public function update(array $request)
    {
        $bool = $this->municipios->update($request);
        if ($bool == true) {
            AlertasController::success("Municipio actualizado exitosamente");
        } else if ($bool == false) {
            AlertasController::error("Error", "El nombre del Municipio ingresado ya existe");
        }
        header("Location: " . $_ENV['BASE_PATH'] . "/municipios");
    }

    public static function getMunicipios()
    {
        $municipios = new Municipios();
        $data = $municipios->getMunicipios();
        return $data;
    }

    public function getParroquias(int $id)
    {
        $municipios = new Municipios();
        $data = $municipios->getParroquias($id);
        $dataJSON = json_encode($data);
        $dataJSON = json_encode($dataJSON);
        header('Content-Type: application/json');
        echo $dataJSON;
    }
}
