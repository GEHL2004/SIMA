<?php

namespace App\Controllers;

use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\SistemasCorporales;

class SistemasCorporalesController
{

    private $sistemas_corporales;
    private $audi;

    public function __construct()
    {
        $this->sistemas_corporales = new SistemasCorporales();
        $this->audi = new AuditoriaController();
    }

    public function index()
    {
        $data = $this->sistemas_corporales->index();
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        // echo "<pre>";
        // print_r($data_solicitudes);
        // echo "</pre>";
        // die();
        require_once "public/views/sistemas_corporales/index.php";
    }

    public function getAllSistemasCorporales(){
        $data = $this->sistemas_corporales->getAllSistemasCorporales();
        return $data;
    }

    public function store(array $request)
    {
        $bool = $this->sistemas_corporales->store($request);
        if ($bool['error'] == 1) {
            AlertasController::warning('Información duplicada.', 'El nombre de este sistema corporal ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' El usuario registro un nuevo sistema corporal llamado: ' . $request['nombre']]);
            AlertasController::success('Registro exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/sistemas-corporales');
        die();
    }

    public function update(array $request)
    {
        $data_antigua = $this->sistemas_corporales->show($request['id_sistema_corporal']);
        $bool = $this->sistemas_corporales->update($request);
        if ($bool['error'] == 1) {
            AlertasController::warning('Información duplicada.', 'El nombre del sistema corporal ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' actualizo un sistema corporal.']);
            AlertasController::success('Registro exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/sistemas-corporales');
        die();
    }

    public function delete(int $id_sistema_corporal)
    {
        $data_a_eliminar = $this->sistemas_corporales->show($id_sistema_corporal);
        $bool = $this->sistemas_corporales->delete($id_sistema_corporal);
        if ($bool['error'] == 1) {
            AlertasController::error('ERROR', 'Error al eliminar el sistema corporal.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' eliminó el sistema corporal "' . $data_a_eliminar[0]['nombre'] . '".']);
            AlertasController::success('Eliminación exitosa.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/sistemas-corporales');
        die();
    }
}
