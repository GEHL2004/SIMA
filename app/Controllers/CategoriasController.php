<?php

namespace App\Controllers;

use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\Categorias;

class CategoriasController
{

    private $categorias;
    private $audi;

    public function __construct()
    {
        $this->categorias = new Categorias();
        $this->audi = new AuditoriaController();
    }

    public function index()
    {
        $data = $this->categorias->index();
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        // echo "<pre>";
        // print_r($data_solicitudes);
        // echo "</pre>";
        // die();
        require_once "public/views/categorias/index.php";
    }

    public function getAllCategorias(){
        $data = $this->categorias->getAllCategorias();
        return $data;
    }

    public function store(array $request)
    {
        $bool = $this->categorias->store($request);
        if ($bool['error'] == 1) {
            AlertasController::warning('Información duplicada.', 'El nombre de está categoría ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' El usuario registro una nueva categoria llamada: ' . $request['nombre']]);
            AlertasController::success('Registro exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/categorias');
        die();
    }

    public function update(array $request)
    {
        $data_antigua = $this->categorias->show($request['id_categoria']);
        $bool = $this->categorias->update($request);
        if ($bool['error'] == 1) {
            AlertasController::warning('Información duplicada.', 'El nombre de la categoría ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' actualizo una categoria.']);
            AlertasController::success('Registro exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/categorias');
        die();
    }

    public function delete(int $id_categoria)
    {
        $data_a_eliminar = $this->categorias->show($id_categoria);
        $bool = $this->categorias->delete($id_categoria);
        if ($bool['error'] == 1) {
            AlertasController::error('ERROR', 'Error al eliminar la categoría.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' eliminó la categoría "' . $data_a_eliminar[0]['nombre'] . '".']);
            AlertasController::success('Eliminación exitosa.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/categorias');
        die();
    }
}
