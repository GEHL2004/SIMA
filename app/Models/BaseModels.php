<?php

namespace App\Models;

use App\config\Conexion;

class BaseModels
{

    private $conn;

    public function __construct()
    {
        $this->conn = new Conexion();
    }

    /**
     * Guarda una única imagen con validaciones de seguridad
     * @param string $tabla Tabla en la que se hara la validación
     * @param string $campo Campo con que se validará el valor
     * @param string|int|float|double $valor Valor a valida
     * @param string|null $tipo Tipo de validación si es Store o Update (ejemplo: 'update'), si es Store no pasar este parametro.
     * @param string|null $id ID del registro a actualizar (solo necesario para validaciones de tipo update).
     * @param string|null $nombre_ID Nombre del campo ID del registro a actualizar (solo necesario para validaciones de tipo update).
     */

    public function validateData($tabla, $campo, $valor, $tipo = "", $id = null, $nombre_ID = ""): array
    {
        if ($tipo == "update") {
            $sql = "SELECT $campo FROM $tabla WHERE $campo = :$campo AND $nombre_ID != :id;";
        } else {
            $sql = "SELECT $campo FROM $tabla WHERE $campo = :$campo;";
        }
        $parametros = [':' . $campo => $valor];
        if ($tipo == "update" && $id !== null) {
            $parametros[':id'] = $id;
        }
        return $this->conn->consultar($sql, $parametros);
    }
}
