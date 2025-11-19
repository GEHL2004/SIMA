<?php

namespace App\Controllers\Mantenimientos;

use App\config\Conexion;
use App\Controllers\AlertasController;
use DateTime;

class ServiciosBaseDeDatosController
{

    private $conn;
    private $serviciosBD;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function index()
    {
        $data = $this->conn->listarBck();
        require_once "public/views/mantenimientos/servicios_base_de_datos/index.php";
    }

    public function monitorBD(){
        $data = $this->conn->obtenerInformacionCompleta();
        require_once "public/views/mantenimientos/servicios_base_de_datos/monitorDB.php";
    }

    public function backup()
    {
        var_dump($bck = $this->conn->backup());
        if ($bck == true) {
            AlertasController::success("Respaldo realizado de forma exitosa.");
            echo true;
        } else {
            AlertasController::error("Error", "No se pudo realizar el respaldo.");
            echo false;
        }
    }

    public function restore(array $data)
    {
        set_time_limit(1200);
        $retore = $this->conn->restore($data['file_name']);
        set_time_limit(30);
        if ($retore == true) {
            AlertasController::success("Base de datos restaurada con éxito.");
            header("Location: " . $_ENV['BASE_PATH'] . "/servicios-bd");
            die();
        } else {
            AlertasController::error("Error", $retore);
            header("Location: " . $_ENV['BASE_PATH'] . "/servicios-bd");
            die();
        }
    }

    public function restore_factory(array $data)
    {
        set_time_limit(1200);
        $retore_factory = $this->conn->restore_factory($data['file_name']);
        set_time_limit(30);
        if ($retore_factory == true) {
            AlertasController::success("Base de datos restablecida de fábrica con éxito.");
            header("Location: " . $_ENV['BASE_PATH'] . "/servicios-bd");
            die();
        } else {
            AlertasController::error("Error", "La base de datos no pudo ser restablecida de fábrica");
            header("Location: " . $_ENV['BASE_PATH'] . "/servicios-bd");
            die();
        }
    }
}
