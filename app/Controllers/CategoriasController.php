<?php

namespace App\Controllers;

use App\Controllers\Mantenimientos\AuditoriaController;
use App\Models\Categorias;
use App\Config\PermisosHelper;

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
        // Verificar permiso de ver
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_CATEGORIAS, PermisosHelper::VER)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_CATEGORIAS, PermisosHelper::VER);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $data = $this->categorias->index();
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        require_once "public/views/categorias/index.php";
    }

    public function getAllCategorias(){
        $data = $this->categorias->getAllCategorias();
        return $data;
    }

    public function store(array $request)
    {
        // Verificar permiso de registrar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_CATEGORIAS, PermisosHelper::REGISTRAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_CATEGORIAS, PermisosHelper::REGISTRAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
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
        // Verificar permiso de actualizar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_CATEGORIAS, PermisosHelper::ACTUALIZAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_CATEGORIAS, PermisosHelper::ACTUALIZAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
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
        // Verificar permiso de eliminar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_CATEGORIAS, PermisosHelper::ELIMINAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_CATEGORIAS, PermisosHelper::ELIMINAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
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
