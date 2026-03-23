<?php

namespace App\Controllers;

use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\Medicos;
use App\Models\MedicosReconocimientos;

class MedicosReconocimientosController
{
    private $medicos_reconocimientos_modelo;
    private $medicos;
    private $audi;

    public function __construct()
    {
        $this->medicos_reconocimientos_modelo = new MedicosReconocimientos();
        $this->medicos = new Medicos();
        $this->audi = new AuditoriaController();
    }

    public function index()
    {
        $data = $this->medicos_reconocimientos_modelo->index();
        foreach ($data as $i => $valor) {
            $data[$i]['nombres_apellidos'] = str_replace('_', ' ', $valor['nombres_apellidos']);
        }
        foreach ($data as $i => $valor) {
            $data[$i]['data'] = json_encode($valor);
        }
        $dataJ = json_encode(json_encode($data));
        require_once 'public/views/medicos_reconocimientos/index.php';
    }

    public function getDataMedico($id_medico)
    {
        $data = $this->medicos_reconocimientos_modelo->getDataMedico($id_medico);
        foreach ($data as $i => $valor) {
            $data[$i]['nombres_apellidos'] = str_replace('_', ' ', $valor['nombres_apellidos']);
        }
        header('Content-Type: application/json');
        $dataJSON = json_encode($data);
        echo json_encode($data);
    }

    public function store($id_medico): void
    {
        $data = [];
        for ($i = 1; $i < 5; $i++) {
            $data[] = [
                'id_medico' => $id_medico,
                'tipo_reconocimiento' => $i,
                'fecha_de_entrega' => null
            ];
        }
        foreach ($data as $reconocimiento) {
            $this->medicos_reconocimientos_modelo->store($reconocimiento);
        }
    }

    public function update(array $request){
        $dataMedico = $this->medicos->show($request['id_medico']);
        $nombre_apellidos = str_replace('_', ' ', $dataMedico['nombres'] . " " . $dataMedico['apellidos']);
        $mensaje = "El usuario " . $_SESSION["nombre_apellido"] . " actualizó los reconocimientos del médico " . $nombre_apellidos . " con ID " . $request['id_medico'] . ". Perteneciente de la cédula " . $dataMedico['cedula'] . ".";
        foreach ($request['reconocimientos'] AS $i => $valor) {
            $this->medicos_reconocimientos_modelo->update($valor);
        }
        $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => $mensaje]);
        AlertasController::success("Reconocimientos actualizados exitosamente");
        header("Location: " . $_ENV['BASE_PATH'] . "/medicos-reconocimientos");
        die();
    }

    public function delete($id_medico_reconocimiento)
    {
        $result = $this->medicos_reconocimientos_modelo->delete($id_medico_reconocimiento);
        return $result;
    }
}
