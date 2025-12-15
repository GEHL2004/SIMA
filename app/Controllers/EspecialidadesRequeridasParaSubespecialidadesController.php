<?php

namespace App\Controllers;

use App\Models\EspecialidadesRequeridasParaSubespecialidades AS ERPSE;

class EspecialidadesRequeridasParaSubespecialidadesController{

    private $conn;
    private $ERPSE;

    public function __construct(){
        $this->ERPSE = new ERPSE();
    }

    public function getEspecialidadesRequeridasParaSubespecialidades(int $id_subespecialidad){
        $data = $this->ERPSE->getEspecialidadesRequeridasParaSubespecialidades($id_subespecialidad);
        return $data;
    }

    public function store(array $data, int $id_subespecialidad){
        $contador = 0;
        $return = false;
        foreach($data AS $i => $valor){
            $result = $this->ERPSE->store($valor, $id_subespecialidad);   
        }
        return true;
    }

    public function update(array $data, $id_subespecialidad){
        $this->delete($id_subespecialidad);
        $this->store($data, $id_subespecialidad);
        return true;
    }

    public function delete(int $id_subespecialidad){
        $data = $this->ERPSE->getEspecialidadesRequeridasParaSubespecialidades($id_subespecialidad);
        $contador = 0;
        $return = false;
        foreach($data AS $i => $valor){
            $result = $this->ERPSE->delete($valor['id_especialidad_requerida_para_subespecialidad']);
            if($result['error'] == 0){
                $contador++;
            }
        }
        if($contador == count($data)){
            $return = true;
        }
        return $return;
    }

}

?>