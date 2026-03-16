<?php

namespace App\Models;

use App\config\Conexion;

class MedicosReportes
{
    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function obtenerDatosMedicoPorMunicipios(int $id_municipio)
    {
        $sql = "SELECT M.nombres, M.apellidos, M.cedula, M.correo, CONCAT_WS('-', M.telefono_inicio, M.telefono_restante) AS telefono, MU.nombre_municipio, M.direccion, M.numero_colegio
                FROM medicos M
                INNER JOIN parroquias AS P ON P.id_parroquia = M.id_parroquia
                INNER JOIN municipios AS MU ON MU.id_municipio = P.id_municipio
                WHERE MU.id_municipio = :id_municipio;";
        $parametros = [':id_municipio' => $id_municipio];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function obtenerDatosMedicoPorParroquias(int $id_parroquia)
    {
        $sql = "SELECT M.nombres, M.apellidos, M.cedula, M.correo, CONCAT_WS('-', M.telefono_inicio, M.telefono_restante) AS telefono, MU.nombre_municipio, P.nombre_parroquia, M.direccion, M.numero_colegio
                FROM medicos M
                INNER JOIN parroquias AS P ON P.id_parroquia = M.id_parroquia
                INNER JOIN municipios AS MU ON MU.id_municipio = P.id_municipio
                WHERE P.id_parroquia = :id_parroquia;";
        $parametros = [':id_parroquia' => $id_parroquia];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function obtenerDatosMedicoPorEspecialidad(int $id_especialidad)
    {
        $sql = "SELECT M.nombres, M.apellidos, M.cedula, M.correo, CONCAT_WS('-', M.telefono_inicio, M.telefono_restante) AS telefono, E.nombre AS nombre_especialidad, M.direccion, M.numero_colegio
                FROM medicos M
                INNER JOIN medicos_especialidades AS ME ON ME.id_medico = M.id_medico
                INNER JOIN especialidades AS E ON E.id_especialidad = ME.id_especialidad
                WHERE E.id_especialidad = :id_especialidad;";
        $parametros = [':id_especialidad' => $id_especialidad];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function obtenerDatosMedicoPorSubespecialidad(int $id_subespecialidad)
    {
        $sql = "SELECT M.nombres, M.apellidos, M.cedula, M.correo, CONCAT_WS('-', M.telefono_inicio, M.telefono_restante) AS telefono, SB.nombre AS nombre_subespecialidad, M.direccion, M.numero_colegio
                FROM medicos M
                INNER JOIN medicos_subespecialidades AS MSB ON MSB.id_medico = M.id_medico
                INNER JOIN subespecialidades AS SB ON SB.id_subespecialidad = MSB.id_subespecialidad
                WHERE SB.id_subespecialidad = :id_subespecialidad;";
        $parametros = [':id_subespecialidad' => $id_subespecialidad];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function obtenerDatosMedicoPorGradoAcademico(int $id_grado_academico)
    {
        $sql = "SELECT M.nombres, M.apellidos, M.cedula, M.correo, CONCAT_WS('-', M.telefono_inicio, M.telefono_restante) AS telefono, GA.nombre_grado, M.direccion, M.numero_colegio
                FROM medicos M
                INNER JOIN grados_academicos AS GA ON GA.id_grado_academico = M.id_grado_academico
                WHERE M.id_grado_academico = :id_grado_academico;";
        $parametros = [':id_grado_academico' => $id_grado_academico];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function obtenerDatosMedicoPorEstado(int $estado)
    {
        $sql = "SELECT M.nombres, M.apellidos, M.cedula, M.correo, CONCAT_WS('-', M.telefono_inicio, M.telefono_restante) AS telefono, MD.estado, M.direccion, M.numero_colegio
                FROM medicos M
                INNER JOIN medicos_detalles AS MD ON MD.id_medico = M.id_medico
                WHERE MD.estado = :estado;";
        $parametros = [':estado' => $estado];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function obtenerDatosMedicoPorDeporte(int $id_deporte)
    {
        $sql = "SELECT M.nombres, M.apellidos, M.cedula, M.correo, CONCAT_WS('-', M.telefono_inicio, M.telefono_restante) AS telefono, D.nombre AS nombre_deporte, M.direccion, M.numero_colegio
                FROM medicos M
                INNER JOIN medicos_deportes AS MD ON MD.id_medico = M.id_medico
                INNER JOIN deportes AS D ON D.id_deporte = MD.id_deporte
                WHERE MD.id_deporte = :id_deporte;";
        $parametros = [':id_deporte' => $id_deporte];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }
}
