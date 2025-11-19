<?php

namespace App\Controllers\Mantenimientos;

use App\Models\Mantenimientos\Auditoria;
use DateTime;

class AuditoriaController
{

    private $audi;

    public function __construct()
    {
        $this->audi = new Auditoria();
    }

    public function index()
    {
        require_once "public/views/mantenimientos/auditoria/index.php";
    }

    public function filtrado_general(){
        $data = $this->audi->filtrado_general();
        foreach($data AS $i => $valor){
            $data[$i]['fecha'] = DateTime::createFromFormat('Y-m-d', $valor['fecha'])->format('d-m-Y');
            $data[$i]['hora'] = DateTime::createFromFormat('H:i:s', $valor['hora'])->format('h:i:s A');
            $data[$i]['contador'] = $i + 1;
        }
        header('Content-Type: application/json');
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        echo $dataJ;
    }

    public function filtrar_usuario(int $id_usuario)
    {
        $data = $this->audi->filtrado_usuario($id_usuario);
        foreach($data AS $i => $valor){
            $data[$i]['fecha'] = DateTime::createFromFormat('Y-m-d', $valor['fecha'])->format('d-m-Y');
            $data[$i]['hora'] = DateTime::createFromFormat('H:i:s', $valor['hora'])->format('h:i:s A');
            $data[$i]['contador'] = $i + 1;
        }
        header('Content-Type: application/json');
        $dataJSON = json_encode($data);
        echo json_encode($dataJSON);
    }

    public function filtrado_rango_fechas(string $fechas)
    {
        $fechas = explode('_', $fechas);
        $data = $this->audi->filtrado_rango_fechas($fechas[0], $fechas[1]);
        foreach($data AS $i => $valor){
            $data[$i]['fecha'] = DateTime::createFromFormat('Y-m-d', $valor['fecha'])->format('d-m-Y');
            $data[$i]['hora'] = DateTime::createFromFormat('H:i:s', $valor['hora'])->format('h:i:s A');
            $data[$i]['contador'] = $i + 1;
        }
        header('Content-Type: application/json');
        $dataJSON = json_encode($data);
        echo json_encode($dataJSON);
    }

    public function filtrado_accion(string $cadena)
    {
        $data = $this->audi->filtrado_accion(preg_replace("/[^a-zA-Z\s]+/", " ", $cadena));
        foreach($data AS $i => $valor){
            $data[$i]['fecha'] = DateTime::createFromFormat('Y-m-d', $valor['fecha'])->format('d-m-Y');
            $data[$i]['hora'] = DateTime::createFromFormat('H:i:s', $valor['hora'])->format('h:i:s A');
            $data[$i]['contador'] = $i + 1;
        }
        header('Content-Type: application/json');
        $dataJSON = json_encode($data);
        echo json_encode($dataJSON);
    }

    public function store(array $request)
    {
        date_default_timezone_set('America/Caracas');
        $request['IP'] = self::capturarIP();
        $request['fecha'] = date('Y-m-d');
        $request['hora'] = date('H:i:s');
        $bool = $this->audi->store($request);
        return true;
    }

    public static function capturarIP()
    {
        $ip = '';
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '127.0.0.1';
        }
        $filter = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        if ($filter === false) {
            $ip = '127.0.0.1';
        }
        return $ip;
    }
}
