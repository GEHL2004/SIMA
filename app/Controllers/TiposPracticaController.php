<?php

namespace App\Controllers;

use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\TiposPractica;

class TiposPracticaController
{

    private $tipos_practicas;
    private $audi;

    public function __construct()
    {
        $this->tipos_practicas = new TiposPractica();
        $this->audi = new AuditoriaController();
    }

    public function index()
    {
        $data = $this->tipos_practicas->index();
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        // echo "<pre>";
        // print_r($data_solicitudes);
        // echo "</pre>";
        // die();
        require_once "public/views/tipos_practicas/index.php";

    }

    public function store(array $request)
    {
        $bool = $this->tipos_practicas->store($request);
        if ($bool['error'] == 1) {
            AlertasController::warning('Información duplicada.', 'El nombre de este tipo de practica ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' El usuario registro una nueva tipo de practica llamada: ' . $request['nombre']]);
            AlertasController::success('Registro exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/tipos-practicas');
        die();
    }

    public function update(array $request)
    {
        $data_antigua = $this->tipos_practicas->show($request['id_tipo_practica']);
        $bool = $this->tipos_practicas->update($request);
        if ($bool['error'] == 1) {
            AlertasController::warning('Información duplicada.', 'El nombre de este tipo de practica ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' actualizo una tipo de practica.']);
            AlertasController::success('Registro exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/tipos-practicas');
        die();
    }

    public function delete(int $id_tipo_practica)
    {
        $data_a_eliminar = $this->tipos_practicas->show($id_tipo_practica);
        $bool = $this->tipos_practicas->delete($id_tipo_practica);
        if ($bool['error'] == 1) {
            AlertasController::error('ERROR', 'Error al eliminar esta tipo de practica.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' eliminó la tipo de practica "' . $data_a_eliminar[0]['nombre'] . '".']);
            AlertasController::success('Eliminación exitosa.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/tipos-practicas');
        die();
    }
}
