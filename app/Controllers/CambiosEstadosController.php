<?php

namespace App\Controllers;

use App\Models\CambiosEstados;
use Exception;

class CambiosEstadosController
{
    private $cambios_estados;

    public function __construct()
    {
        $this->cambios_estados = new CambiosEstados();
    }

    public function listarTodos()
    {
        return $this->cambios_estados->listarTodos();
    }

    public function obtenerPorMedico(int $id_medico)
    {
        return $this->cambios_estados->obtenerPorMedico($id_medico);
    }

    public function obtenerUltimoEstadoMedico(int $medico)
    {
        return $this->cambios_estados->obtenerUltimoEstadoMedico($medico);
    }

    public function obtenerPorId(int $id_medico)
    {
        $ultimo_estado = $this->cambios_estados->obtenerUltimoEstadoMedico($id_medico);
        $id_cambio_estado_medico = $ultimo_estado[0]['id_cambio_estado_medico'];
        return $this->cambios_estados->obtenerPorId($ultimo_estado[0]['id_cambio_estado_medico']);
    }

    public function store(int $id_medico, int $estado)
    {
        $data = [
            'id_medico' => $id_medico,
            'estado' => $estado,
            'fecha_colocacion' => date('Y-m-d'),
            'fecha_final' => null
        ];
        $result = $this->cambios_estados->store($data);
        return $result;
    }

    public function update(int $id_medico, int $estado)
    {
        $ultimo_estado = $this->cambios_estados->obtenerUltimoEstadoMedico($id_medico);
        if ($ultimo_estado[0]['estado'] == $estado) {
            return ['error' => 0, 'result' => 'El estado es el mismo al actual, no se realizaron cambios'];
        } else {
            $data = [
                'id_cambio_estado_medico' => $ultimo_estado[0]['id_cambio_estado_medico'],
                'id_medico' => $id_medico,
                'estado' => $estado,
                'fecha_colocacion' => date('Y-m-d'),
                'fecha_final' => date('Y-m-d')
            ];
            return $this->cambios_estados->update($data);
        }
    }

    public function delete(int $id_medico)
    {
        $ultimo_estado = $this->cambios_estados->obtenerUltimoEstadoMedico($id_medico);
        $id_cambio_estado_medico = $ultimo_estado[0]['id_cambio_estado_medico'];
        return $this->cambios_estados->delete($ultimo_estado[0]['id_cambio_estado_medico']);
    }
}
