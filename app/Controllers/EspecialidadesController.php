<?php

namespace App\Controllers;

use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\Especialidades;
use App\Config\PermisosHelper;

class EspecialidadesController
{

    private $especialidades;
    private $audi;
    private $categorias;
    private $tipos_practicas;
    private $sistemas_corporales;

    public function __construct()
    {
        $this->audi = new AuditoriaController();
        $this->especialidades = new Especialidades();
        $this->categorias = new CategoriasController();
        $this->tipos_practicas = new TiposPracticaController();
        $this->sistemas_corporales = new SistemasCorporalesController();
    }

    public function index()
    {
        // Verificar permiso de ver
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::VER)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::VER);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $data = $this->especialidades->index();
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        require_once "public/views/especialidades/index.php";
    }

    public function getAllEspecialidades()
    {
        $data = $this->especialidades->getAllEspecialidades();
        return $data;
    }

    public function create()
    {
        // Verificar permiso de registrar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::REGISTRAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::REGISTRAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $categorias = $this->categorias->getAllCategorias();
        $tipos_practicas = $this->tipos_practicas->getAllTiposPracticas();
        $sistemas_corporales = $this->sistemas_corporales->getAllSistemasCorporales();
        require_once "public/views/especialidades/create.php";
    }

    public function store(array $request)
    {
        // Verificar permiso de registrar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::REGISTRAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::REGISTRAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $request['nombre'] = trim($request['nombre']);
        $request['descripcion'] = trim($request['descripcion']);
        $request['codigo'] = trim($request['codigo']);
        $bool = $this->especialidades->store($request);
        if ($bool['error'] == 1) {
            AlertasController::error('Nombre Duplicado', 'EL nombre de la especialidad ingresada ya se encuentra registrada, verifiquelo y vuelva a intentar.');
        } else if ($bool['error'] == 2) {
            AlertasController::error('Código Duplicado', 'EL código de la especialidad ingresada ya se encuentra registrada, verifiquelo y vuelva a intentar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' El usuario registro una nueva especialidad llamada: ' . $request['nombre']]);
            AlertasController::success('Registro exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/especialidades');
        die();
    }

    public function edit(int $id_especialidad)
    {
        // Verificar permiso de actualizar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::ACTUALIZAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::ACTUALIZAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $categorias = $this->categorias->getAllCategorias();
        $tipos_practicas = $this->tipos_practicas->getAllTiposPracticas();
        $sistemas_corporales = $this->sistemas_corporales->getAllSistemasCorporales();
        $data = $this->especialidades->show($id_especialidad);
        require_once "public/views/especialidades/edit.php";
    }

    public function update(array $request)
    {
        // Verificar permiso de actualizar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::ACTUALIZAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::ACTUALIZAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $data_antigua = $this->especialidades->show($request['id_especialidad']);
        $request['nombre'] = trim($request['nombre']);
        $request['descripcion'] = trim($request['descripcion']);
        $request['codigo'] = trim($request['codigo']);
        $bool = $this->especialidades->update($request);
        if ($bool['error'] == 1) {
            AlertasController::error('Nombre Duplicado', 'EL nombre ingresado para actualizar la especialidad ya se encuentra registrado, verifiquelo y vuelva a intentar.');
        } else if ($bool['error'] == 2) {
            AlertasController::error('Código Duplicado', 'EL código ingresado para actualizar la especialidad ya se encuentra registrado, verifiquelo y vuelva a intentar.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' El usuario actualizo la especialidad llamada: ' . $request['nombre']]);
            AlertasController::success('Actualización exitoso.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/especialidades');
        die();
    }

    public function show(int $id_especialidad)
    {
        $categorias = $this->categorias->getAllCategorias();
        $tipos_practicas = $this->tipos_practicas->getAllTiposPracticas();
        $sistemas_corporales = $this->sistemas_corporales->getAllSistemasCorporales();
        $data = $this->especialidades->show($id_especialidad);
        require_once "public/views/especialidades/show.php";
    }

    public function delete(int $id_especialidad)
    {
        // Verificar permiso de eliminar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::ELIMINAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::ELIMINAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $data = $this->especialidades->show($id_especialidad);
        $bool = $this->especialidades->delete($id_especialidad);
        if ($bool['error'] == 1) {
            AlertasController::error('ERROR', 'No se pudo eliminar esta especialidad.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' El usuario eliminó la especialidad llamada: ' . $data[0]['nombre']]);
            AlertasController::success('Eliminación exitosa.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/especialidades-disable');
        die();
    }

    public function disable_specialties()
    {
        // Verificar permiso de ver (para especialidades deshabilitadas)
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::VER)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::VER);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $data = $this->especialidades->index_disable();
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        require_once "public/views/especialidades/disable_views.php";
    }

    public function disable(int $id_especialidad)
    {
        // Verificar permiso de deshabilitar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::DESHABILITAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::DESHABILITAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $data = $this->especialidades->show($id_especialidad);
        $bool = $this->especialidades->disable($id_especialidad);
        if ($bool['error'] == 1) {
            AlertasController::error('ERROR', 'No se pudo deshabilitar esta especialidad.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' El usuario deshabilito la especialidad llamada: ' . $data[0]['nombre_E']]);
            AlertasController::success('Deshabilitación exitosa.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/especialidades');
        die();
    }

    public function enable(int $id_especialidad)
    {
        // Verificar permiso de habilitar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::HABILITAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_ESPECIALIDADES, PermisosHelper::HABILITAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $data = $this->especialidades->show($id_especialidad);
        $bool = $this->especialidades->enable($id_especialidad);
        if ($bool['error'] == 1) {
            AlertasController::error('ERROR', 'No se pudo habilitar esta especialidad.');
        } else {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' El usuario habilito la especialidad llamada: ' . $data[0]['nombre_E']]);
            AlertasController::success('Habilitación exitosa.');
        }
        header('Location: ' . $_ENV['BASE_PATH'] . '/especialidades-disable');
        die();
    }
}
