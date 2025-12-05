<?php

namespace App\Controllers;

use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\SubEspecialidades;

class SubEspecialidadesController
{

    private $subespecialidades;
    private $especialidades;
    private $audi;
    private $categorias;
    private $tipos_practicas;
    private $sistemas_corporales;
    private const CLAVES = ['nombre', 'codigo', 'RequiereEspecialidad', 'especialidad', 'categoria', 'tipo_practica', 'sistema_corporal', 'descripcion'];

    public function __construct()
    {
        $this->subespecialidades = new SubEspecialidades();
        $this->especialidades = new EspecialidadesController();
        $this->audi = new AuditoriaController();
        $this->categorias = new CategoriasController();
        $this->tipos_practicas = new TiposPracticaController();
        $this->sistemas_corporales = new SistemasCorporalesController();
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
        $categorias = $this->categorias->getAllCategorias();
        $tipos_practicas = $this->tipos_practicas->getAllTiposPracticas();
        $sistemas_corporales = $this->sistemas_corporales->getAllSistemasCorporales();
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
        foreach (self::CLAVES as $valor) {
            if (!array_key_exists($valor, $request)) {
                $request[$valor] = null;
            }
        }
        // echo "<pre>";
        // print_r($request);
        // echo "<pre>";
        // die();
        $bool = $this->subespecialidades->store($request);
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

    public function edit(string $cadena)
    {
        $id_subespecialidad = explode('_', $cadena)[0];
        $tipo = explode('_', $cadena)[1];
        $especialidades = $this->especialidades->getAllEspecialidades();
        $categorias = $this->categorias->getAllCategorias();
        $tipos_practicas = $this->tipos_practicas->getAllTiposPracticas();
        $sistemas_corporales = $this->sistemas_corporales->getAllSistemasCorporales();
        $data = $this->subespecialidades->show($id_subespecialidad, $tipo);
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
        foreach (self::CLAVES as $valor) {
            if (!array_key_exists($valor, $request)) {
                $request[$valor] = null;
            }
        }
        $data_antigua = $this->subespecialidades->show($request['id_subespecialidad'], 1);
        $request['nombre'] = trim($request['nombre']);
        $request['descripcion'] = trim($request['descripcion']);
        $request['codigo'] = trim($request['codigo']);
        // echo "<pre>";
        // print_r($request);
        // echo "<pre>";
        // die();
        $bool = $this->subespecialidades->update($request);
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

    public function show(string $cadena)
    {
        $id_subespecialidad = explode('_', $cadena)[0];
        $tipo = explode('_', $cadena)[1];
        $especialidades = $this->especialidades->getAllEspecialidades();
        $categorias = $this->categorias->getAllCategorias();
        $tipos_practicas = $this->tipos_practicas->getAllTiposPracticas();
        $sistemas_corporales = $this->sistemas_corporales->getAllSistemasCorporales();
        $data = $this->subespecialidades->show($id_subespecialidad, $tipo);
        // echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        // die();
        require_once "public/views/subespecialidades/show.php";
    }

    public function delete(int $id_subespecialidad)
    {
        $data = $this->subespecialidades->show($id_subespecialidad, 1);
        // echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        // die();
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
        $data = $this->subespecialidades->show($id_subespecialidad, 1);
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
        $data = $this->subespecialidades->show($id_subespecialidad, 1);
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
