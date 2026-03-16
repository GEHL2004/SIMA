<?php

namespace App\Models\Mantenimientos;

use App\config\Conexion;

class Auditoria
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    public function filtrado_general()
    {
        $sql = "SELECT audi.id_auditoria, users.nombre_user, audi.fecha, audi.hora, audi.accion 
                FROM auditorias audi
                INNER JOIN usuarios AS users ON users.id_usuario = audi.id_usuario
                ORDER BY audi.id_auditoria DESC;";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function filtrado_usuario(int $id_usuario)
    {
        if ($id_usuario > 0) {
            $sql = "SELECT audi.id_auditoria, users.nombre_user, audi.fecha, audi.hora, audi.accion
                FROM auditorias audi
                INNER JOIN usuarios AS users ON audi.id_usuario = users.id_usuario
                WHERE audi.id_usuario = :id_usuario
                ORDER BY audi.id_auditoria DESC;";
            $parametros = [':id_usuario' => $id_usuario];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        } else {
            $sql = "SELECT audi.id_auditoria, users.nombre_user, audi.fecha, audi.hora, audi.accion
                FROM auditorias audi
                INNER JOIN usuarios AS users ON audi.id_usuario = users.id_usuario
                ORDER BY audi.id_auditoria DESC;";
            $parametros = [];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        }
    }

    public function filtrado_rango_fechas(string $fecha_inicio, string $fecha_fin)
    {
        $sql = "SELECT audi.id_auditoria, users.nombre_user, audi.fecha, audi.hora, audi.accion
                FROM auditorias audi
                INNER JOIN usuarios AS users ON audi.id_usuario = users.id_usuario
                WHERE fecha BETWEEN :fecha_inicio AND :fecha_fin
                ORDER BY audi.id_auditoria DESC;";
        $parametros = [':fecha_inicio' => $fecha_inicio, ':fecha_fin' => $fecha_fin];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }

    public function filtrado_accion(string $cadena)
    {
        if (!empty($cadena)) {
            $sql = "SELECT audi.id_auditoria, users.nombre_user, audi.fecha, audi.hora, audi.accion
                    FROM auditorias audi
                    INNER JOIN usuarios AS users ON audi.id_usuario = users.id_usuario
                    WHERE audi.accion LIKE :cadena
                    ORDER BY audi.id_auditoria DESC;";
            $parametros = [':cadena' => $cadena];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        } else {
            $sql = "SELECT audi.id_auditoria, users.nombre_user, audi.fecha, audi.hora, audi.accion
                    FROM auditorias audi
                    INNER JOIN usuarios AS users ON audi.id_usuario = users.id_usuario
                    ORDER BY audi.id_auditoria DESC;";
            $parametros = [];
            $result = $this->conn->consultar($sql, $parametros);
            return $result;
        }
    }

    public function store(array $data)
    {
        $sql = "INSERT INTO auditorias(id_usuario, fecha, hora, accion)
                VALUES(:id_usuario, :fecha, :hora, :accion);";
        $parametros = [':id_usuario' => $data['ID'],  ':fecha' => $data['fecha'], ':hora' => $data['hora'], ':accion' => $data['accion']];
        $this->conn->ejecutar($sql, $parametros);
        return true;
    }

    public function obtenerActividadesRecientes()
    {
        $sql = "SELECT 
                    a.accion,
                    DATE_FORMAT(a.fecha, '%d/%m/%Y') as fecha,
                    a.hora,
                    TIMESTAMPDIFF(MINUTE, CONCAT(a.fecha, ' ', a.hora), NOW()) as minutos_antes
                FROM auditorias a
                ORDER BY a.fecha DESC, a.hora DESC
                LIMIT 10";
        $parametros = [];
        $result = $this->conn->consultar($sql, $parametros);
        return $result;
    }
}
