<?php

namespace App\Controllers;

use App\Controllers\EspecialidadesRequeridasParaSubespecialidadesController AS ERPSEC;
use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\SubEspecialidades;

class SubEspecialidadesController
{

    private $subespecialidades;
    private $especialidades;
    private $audi;
    private $ERPSEC;


    public function __construct()
    {
        $this->subespecialidades = new SubEspecialidades();
        $this->especialidades = new EspecialidadesController();
        $this->audi = new AuditoriaController();
        $this->ERPSEC = new ERPSEC();
    }

    public function index()
    {
        $data = $this->subespecialidades->index();
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();
        require_once "public/views/subespecialidades/index.php";
    }

    public function create()
    {
        $especialidades = $this->especialidades->getAllEspecialidades();
        $especialidadesJ = json_encode($especialidades);
        $especialidadesJ = json_encode($especialidadesJ);
        // echo "<pre>";
        // print_r($especialidades);
        // echo "<pre>";
        // die();
        require_once "public/views/subespecialidades/create.php";
    }

    public function store(array $request)
    {
        $request['nombre'] = trim($request['nombre']);
        $request['descripcion'] = trim($request['descripcion']);
        $request['codigo'] = trim($request['codigo']);
        $bool = $this->subespecialidades->store($request);
        $this->ERPSEC->store($request['especialidades'], $bool['id_subespecialidad']);
        // echo "<pre>";
        // print_r($bool);
        // echo "<pre>";
        // die();
        if ($bool['error'] == 1) {
            AlertasController::error('Nombre Duplicado', 'EL nombre de la subespecialidad ingresada ya se encuentra registrada, verifiquelo y vuelva a intentar.');
        } else if ($bool['error'] == 2) {
            AlertasController::error('Código Duplicado', 'EL código de la subespecialidad ingresado ya se encuentra registrado, verifiquelo y vuelva a intentar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' registro una nueva subespecialidad llamada: ' . $request['nombre']]);
            AlertasController::success('Registro exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/subespecialidades');
        die();
    }

    public function edit(int $id_subespecialidad)
    {
        $especialidades = $this->especialidades->getAllEspecialidades();
        $especialidadesJ = json_encode($especialidades);
        $especialidadesJ = json_encode($especialidadesJ);
        $dataERPSEC = $this->ERPSEC->getEspecialidadesRequeridasParaSubespecialidades($id_subespecialidad);
        // $dataERPSECJ = json_encode($dataERPSEC);
        // $dataERPSECJ = json_encode($dataERPSECJ);
        $data = $this->subespecialidades->show($id_subespecialidad);
        $contador = 0;
        // echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        // die();
        require_once "public/views/subespecialidades/edit.php";
    }

    public function update(array $request)
    {
        $request['nombre'] = trim($request['nombre']);
        $request['descripcion'] = trim($request['descripcion']);
        $request['codigo'] = trim($request['codigo']);
        $data_antigua = $this->subespecialidades->show($request['id_subespecialidad']);
        // echo "<pre>";
        // print_r($request);
        // echo "<pre>";
        // die();
        $bool = $this->subespecialidades->update($request);
        $this->ERPSEC->update($request['especialidades'], $request['id_subespecialidad']);
        if ($bool['error'] == 1) {
            AlertasController::error('Nombre Duplicado', 'EL nombre ingresado para actualizar la subespecialidad ya se encuentra registrado, verifiquelo y vuelva a intentar.');
        } else if ($bool['error'] == 2) {
            AlertasController::error('Código Duplicado', 'EL código ingresado para actualizar la subespecialidad ya se encuentra registrado, verifiquelo y vuelva a intentar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' actualizo la subespecialidad llamada: ' . $request['nombre']]);
            AlertasController::success('Actualización exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/subespecialidades');
        die();
    }

    public function show(int $id_subespecialidad)
    {
        $data = $this->subespecialidades->show($id_subespecialidad);
        // echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        // die();
        require_once "public/views/subespecialidades/show.php";
    }

    public function delete(int $id_subespecialidad)
    {
        $data = $this->subespecialidades->show($id_subespecialidad);
        // echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        // die();
        $this->ERPSEC->delete($id_subespecialidad);
        $bool = $this->subespecialidades->delete($id_subespecialidad);
        if ($bool['error'] == 1) {
            AlertasController::error('ERROR', 'NO se pudo eliminar esta subespecialidad.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' eliminó la subespecialidad llamada: ' . $data[0]['nombre']]);
            AlertasController::success('Eliminación exitosa.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/subespecialidades-disable');
        die();
    }

    public function disable_sub_specialties()
    {
        $data = $this->subespecialidades->index_disable();
        // echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        // die();
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        require_once "public/views/subespecialidades/disable_views.php";
    }

    public function disable(int $id_subespecialidad)
    {
        $data = $this->subespecialidades->show($id_subespecialidad);
        // echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        // die();
        $bool = $this->subespecialidades->disable($id_subespecialidad);
        if ($bool['error'] == 1) {
            AlertasController::error('ERROR', 'No se pudo deshabilitar esta subespecialidad.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' deshabilito la subespecialidad llamada: ' . $data[0]['nombre_SE']]);
            AlertasController::success('Deshabilitación exitosa.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/subespecialidades');
        die();
    }

    public function enable(int $id_subespecialidad)
    {
        $data = $this->subespecialidades->show($id_subespecialidad);
        // echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        // die();
        $bool = $this->subespecialidades->enable($id_subespecialidad);
        if ($bool['error'] == 1) {
            AlertasController::error('ERROR', 'No se pudo habilitar esta subespecialidad.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' habilito la subespecialidad llamada: ' . $data[0]['nombre_SE']]);
            AlertasController::success('Habilitación exitosa.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/subespecialidades-disable');
        die();
    }
}
