<?php

namespace App\Controllers\mantenimientos;

use App\config\Conexion;
use App\Controllers\AlertasController;
use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\Deportes;

class DeportesController
{

    private $conn;
    private $deportes;
    private $audi;

    public function __construct()
    {
        $this->conn = new Conexion();
        $this->deportes = new Deportes();
        $this->audi = new AuditoriaController();
    }

    public function index()
    {
        $data = $this->deportes->index();
        foreach ($data as $i => $valor) {
            $data[$i]['data'] = json_encode($valor);
        }
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        require_once('public/views/mantenimientos/deportes/index.php');
    }

    public function store(array $request)
    {
        $bool = $this->deportes->store($request);
        if ($bool['error'] == 0) {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombre_apellido"] . ' registró un deporte con el nombre ' . $request['nombre']]);
            AlertasController::success("Deporte registrado exitosamente");
        } else if ($bool['error'] == 1) {
            AlertasController::error("Error", $bool['message']);
        }
        header("Location: " . $_ENV['BASE_PATH'] . "/deportes");
        die();
    }

    public function update(array $request)
    {
        $bool = $this->deportes->update($request);
        if ($bool['error'] == 0) {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombre_apellido"] . ' actualizó un deporte con el nombre ' . $request['nombre']]);
            AlertasController::success("Deporte actualizado exitosamente");
        } else if ($bool['error'] == 1) {
            AlertasController::error("Error", $bool['message']);
        }
        header("Location: " . $_ENV['BASE_PATH'] . "/deportes");
        die();
    }

    public function delete(int $id_deporte) {
        $bool = $this->deportes->delete($id_deporte);
        if ($bool['error'] == 0) {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombre_apellido"] . ' eliminó un deporte con el id ' . $id_deporte]);
            AlertasController::success("Deporte eliminado exitosamente");
        } else if ($bool['error'] == 1) {
            AlertasController::error("Error", $bool['message']);
        }
        header("Location: " . $_ENV['BASE_PATH'] . "/deportes");
        die();
    }
}
