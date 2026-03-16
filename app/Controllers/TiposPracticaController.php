<?php

namespace App\Controllers;

use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\TiposPractica;
use App\Config\PermisosHelper;

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
        // Verificar permiso de ver
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_TIPOS_PRACTICA, PermisosHelper::VER)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_TIPOS_PRACTICA, PermisosHelper::VER);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $data = $this->tipos_practicas->index();
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        require_once "public/views/tipos_practicas/index.php";
    }

    public function getAllTiposPracticas()
    {
        $data = $this->tipos_practicas->getAllTiposPracticas();
        return $data;
    }
    public function store(array $request)
    {
        // Verificar permiso de registrar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_TIPOS_PRACTICA, PermisosHelper::REGISTRAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_TIPOS_PRACTICA, PermisosHelper::REGISTRAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
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
        // Verificar permiso de actualizar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_TIPOS_PRACTICA, PermisosHelper::ACTUALIZAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_TIPOS_PRACTICA, PermisosHelper::ACTUALIZAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
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
        // Verificar permiso de eliminar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_TIPOS_PRACTICA, PermisosHelper::ELIMINAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_TIPOS_PRACTICA, PermisosHelper::ELIMINAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
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
