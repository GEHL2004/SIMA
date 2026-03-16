<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class Medicos
{

    private $conn;
    private $baseModel;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function index()
    {
        $sql = "SELECT M.id_medico, CONCAT_WS('_', M.nombres, M.apellidos) AS nombres_apellidos, M.numero_colegio, MD.estado 
                FROM medicos M
                INNER JOIN medicos_detalles AS MD ON MD.id_medico = M.id_medico
                WHERE MD.estado != 0;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function store(array $data)
    {
        try {
            $sql = "INSERT INTO medicos(cedula, impre, rif, correo, nacionalidad, nombres, apellidos, telefono_inicio, telefono_restante, id_parroquia, direccion, numero_colegio, nombre_foto, id_grado_academico, id_creador)
                    VALUES (:cedula, :impre, :rif, :correo, :nacionalidad, :nombres, :apellidos, :telefono_inicio, :telefono_restante, :id_parroquia, :direccion, :numero_colegio, :nombre_foto, :id_grado_academico, :id_creador)";
            $parametros = [':cedula' => $data['cedula'], ':impre' => $data['impre'], ':rif' => $data['rif'], ':correo' => $data['correo'], ':nacionalidad' => $data['nacionalidad'], ':nombres' => $data['nombres'], ':apellidos' => $data['apellidos'], ':telefono_inicio' => $data['telefono_inicio'], ':telefono_restante' => $data['telefono_restante'], ':id_parroquia' => $data['id_parroquia'], ':direccion' => $data['direccion'], ':numero_colegio' => $data['numero_colegio'], ':nombre_foto' => $data['nombre_foto'], ':id_grado_academico' => $data['id_grado_academico'], ':id_creador' => $_SESSION['id_usuario']];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_medico' => $result];
        } catch (Exception $e) {
            die('(Modelo). Error al registrar el medico: ' . $e->getMessage());
        }
    }

    public function update(array $data)
    {
        try {
            $sql = "UPDATE medicos SET cedula = :cedula, impre = :impre, rif = :rif, correo = :correo, nacionalidad = :nacionalidad, nombres = :nombres, apellidos = :apellidos, telefono_inicio = :telefono_inicio, telefono_restante = :telefono_restante, id_parroquia = :id_parroquia, direccion = :direccion, numero_colegio = :numero_colegio, nombre_foto = :nombre_foto, id_grado_academico = :id_grado_academico, id_creador = :id_creador
                    WHERE id_medico = :id_medico;";
            $parametros = [':cedula' => $data['cedula'], ':impre' => $data['impre'], ':rif' => $data['rif'], ':correo' => $data['correo'], ':nacionalidad' => $data['nacionalidad'], ':nombres' => $data['nombres'], ':apellidos' => $data['apellidos'], ':telefono_inicio' => $data['telefono_inicio'], ':telefono_restante' => $data['telefono_restante'], ':id_parroquia' => $data['id_parroquia'], ':direccion' => $data['direccion'], ':numero_colegio' => $data['numero_colegio'], ':nombre_foto' => $data['nombre_foto'], ':id_grado_academico' => $data['id_grado_academico'], ':id_creador' => $_SESSION['id_usuario'], ':id_medico' => $data['id_medico']];
            $result = $this->conn->ejecutar($sql, $parametros);
            return ['error' => 0, 'id_medico' => $result];
        } catch (Exception $e) {
            die('(Modelo). Error al actualizar el medico: ' . $e->getMessage());
        }
    }

    public function show(int $id_medico)
    {
        $sql = "SELECT  m.*, CONCAT(m.telefono_inicio, m.telefono_restante) AS 'telefono', CONCAT_WS('_', m.nombres, m.apellidos, m.cedula) AS directorio, mn.id_municipio, mn.id_municipio, m.id_parroquia, md.*
                FROM medicos m
                INNER JOIN medicos_detalles AS md ON md.id_medico = m.id_medico
                INNER JOIN parroquias AS p ON p.id_parroquia = m.id_parroquia
                INNER JOIN municipios AS mn ON mn.id_municipio = p.id_municipio
                WHERE m.id_medico = :id_medico";
        $parametros = [':id_medico' => $id_medico];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function delete(int $id_medico)
    {
        $sql = "";
        $parametros = [':id_medico' => $id_medico];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function medicosPorMunicipios()
    {
        $sql = "SELECT 
                    SUM(CASE WHEN P.id_municipio = 1 THEN 1 ELSE 0 END) as 'Atanasio_Girardot',
                    SUM(CASE WHEN P.id_municipio = 2 THEN 1 ELSE 0 END) as 'Bolivar',
                    SUM(CASE WHEN P.id_municipio = 3 THEN 1 ELSE 0 END) as 'Camatagua',
                    SUM(CASE WHEN P.id_municipio = 4 THEN 1 ELSE 0 END) as 'Francisco_Linares_Alcentara',
                    SUM(CASE WHEN P.id_municipio = 5 THEN 1 ELSE 0 END) as 'Jose_Angel_Lamas',
                    SUM(CASE WHEN P.id_municipio = 6 THEN 1 ELSE 0 END) as 'Jose_Felix_Ribas',
                    SUM(CASE WHEN P.id_municipio = 7 THEN 1 ELSE 0 END) as 'Jose_Rafael_Revenga',
                    SUM(CASE WHEN P.id_municipio = 8 THEN 1 ELSE 0 END) as 'Libertador',
                    SUM(CASE WHEN P.id_municipio = 9 THEN 1 ELSE 0 END) as 'Mario_Briceno_Iragorry',
                    SUM(CASE WHEN P.id_municipio = 10 THEN 1 ELSE 0 END) as 'Ocumare_de_la_Costa_de_Oro',
                    SUM(CASE WHEN P.id_municipio = 11 THEN 1 ELSE 0 END) as 'San_Casimiro',
                    SUM(CASE WHEN P.id_municipio = 12 THEN 1 ELSE 0 END) as 'San_Sebastien',
                    SUM(CASE WHEN P.id_municipio = 13 THEN 1 ELSE 0 END) as 'Santiago_Mariño',
                    SUM(CASE WHEN P.id_municipio = 14 THEN 1 ELSE 0 END) as 'Santos_Michelena',
                    SUM(CASE WHEN P.id_municipio = 15 THEN 1 ELSE 0 END) as 'Sucre',
                    SUM(CASE WHEN P.id_municipio = 16 THEN 1 ELSE 0 END) as 'Tovar',
                    SUM(CASE WHEN P.id_municipio = 17 THEN 1 ELSE 0 END) as 'Urdaneta',
                    SUM(CASE WHEN P.id_municipio = 18 THEN 1 ELSE 0 END) as 'Zamora'
                FROM medicos AS M
                INNER JOIN parroquias AS P ON P.id_parroquia = M.id_parroquia";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function especialidadesMasUsadas(){
        $sql = "SELECT 
                    especialidad,
                    SUM(cantidad) as cantidad
                FROM (
                    -- Especialidades
                    SELECT 
                        es.nombre as especialidad,
                        COUNT(me.id_medico) as cantidad
                    FROM medicos_especialidades me
                    JOIN especialidades es ON me.id_especialidad = es.id_especialidad
                    WHERE es.activa = 1
                    GROUP BY es.nombre
                    
                    UNION ALL
                    
                    -- Subespecialidades
                    SELECT 
                        sub.nombre as especialidad,
                        COUNT(ms.id_medico) as cantidad
                    FROM medicos_subespecialidades ms
                    JOIN subespecialidades sub ON ms.id_subespecialidad = sub.id_subespecialidad
                    WHERE sub.activa = 1
                    GROUP BY sub.nombre
                ) AS todas_especialidades
                GROUP BY especialidad
                ORDER BY cantidad DESC
                LIMIT 5;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

}
