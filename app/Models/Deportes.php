<?php

namespace App\Models;

use App\config\Conexion;

class Deportes
{

    private $conn;
    private $baseModel;

    public function __construct()
    {
        $this->conn = new Conexion();
        $this->baseModel = new BaseModels();
    }

    public function index(){
        $sql = "SELECT D.id_deporte, D.nombre, D.categoria, D.es_olimpico, D.popularidad, D.deporte_nacional
                FROM deportes D;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function getAllDeportes()
    {
        $sql = "SELECT D.id_deporte, D.nombre
                FROM deportes D;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function store(array $data){
        $result = $this->baseModel->validateData('deportes', 'nombre', $data['nombre']);
        if(!empty($result)){
            return ['error' => 1, 'message' => 'El nombre del deporte ya existe.'];
        }
        $sql = "INSERT INTO deportes(nombre, categoria, es_olimpico, popularidad, deporte_nacional)
                VALUES(:nombre, :categoria, :es_olimpico, :popularidad, :deporte_nacional);";
        $parametros = [':nombre' => $data['nombre'], ':categoria' => $data['categoria'], ':es_olimpico' => $data['es_olimpico'], ':popularidad' => $data['popularidad'], ':deporte_nacional' => $data['deporte_nacional']];
        $result = $this->conn->ejecutar($sql, $parametros);
        return ['error' => 0, 'result' => $result];
    }

    public function update(array $data){
        $result = $this->baseModel->validateData('deportes', 'nombre', $data['nombre'], 'update', $data['id-deporte'], 'id_deporte');
        if(!empty($result)){
            return ['error' => 1, 'message' => 'El nombre del deporte ya existe.'];
        }
        $sql = "UPDATE deportes SET nombre = :nombre, categoria = :categoria, es_olimpico = :es_olimpico, popularidad = :popularidad, deporte_nacional = :deporte_nacional
                WHERE id_deporte = :id_deporte;";
        $parametros = [':nombre' => $data['nombre'], ':categoria' => $data['categoria'], ':es_olimpico' => $data['es_olimpico'], ':popularidad' => $data['popularidad'], ':deporte_nacional' => $data['deporte_nacional'], ':id_deporte' => $data['id-deporte']];
        $result = $this->conn->ejecutar($sql, $parametros);
        return ['error' => 0, 'result' => $result];
    }

    public function delete(int $id_deportes){
        $sql = "DELETE FROM deportes
                WHERE id_deporte = :id_deporte;";
        $parametros = [':id_deporte' => $id_deportes];
        $result = $this->conn->ejecutar($sql, $parametros);
        return ['error' => 0, 'result' => $result];
    }

}
