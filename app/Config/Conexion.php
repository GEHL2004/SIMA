<?php

namespace App\config;

use Exception;
use PDO;
use PDOException;

class Conexion
{

    private $dbname;
    private $servidor;
    private $usuario;
    private $password;
    private $conexion;
    private $puerto;
    private $conexionExitosa = false;

    public function __construct(bool $lanzarError = false)
    {
        $this->dbname = $_ENV['DB_DATABASE'] ?? '';
        $this->servidor = $_ENV['DB_HOST'] ?? 'localhost';
        $this->usuario = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASSWORD'] ?? '';
        $this->puerto = $_ENV['DB_PORT'] ?? '3306';
        if (empty($this->dbname) || empty($this->servidor) || empty($this->usuario)) {
            $this->conexionExitosa = false;
            if ($lanzarError) {
                throw new Exception("Configuración de base de datos incompleta");
            }
            error_log("Configuración de base de datos incompleta");
            return;
        }
        try {
            $ConfiguracionPDO = [
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 5
            ];
            $this->conexion = new PDO("mysql:host=$this->servidor;port=$this->puerto;dbname=$this->dbname;charset=utf8mb4", $this->usuario, $this->password, $ConfiguracionPDO);
            $this->conexionExitosa = true;
        } catch (PDOException $e) {
            error_log("Error de base de datos [{$e->getCode()}]: " . $e->getMessage());
            error_log("Intento de conexión a: {$this->servidor}/{$this->dbname}");
            if ($lanzarError) {
                throw new Exception("Error de conexión con la base de datos");
            }
        }
    }

    public function ejecutar(string $sql, array $parametros = [])
    {
        try {
            $this->conexion->beginTransaction();
            $sentencia = $this->conexion->prepare($sql);
            foreach ($parametros as $indice => $valor) {
                echo "$indice => $valor";
                $sentencia->bindValue($indice, $valor);
            }
            $sentencia->execute();
            $ultimo_id_insertado = $this->conexion->lastInsertId();
            $this->conexion->commit();
            return $ultimo_id_insertado;
        } catch (PDOException $e) {
            die('Error al ejecutar el codigo MySQL: ' . $e->getMessage());
        }
    }

    public function consultar(string $sql, array $parametros = [])
    {
        try {
            $sentencia = $this->conexion->prepare($sql);
            foreach ($parametros as $indice => $valor) {
                $sentencia->bindValue($indice, $valor);
            }
            $sentencia->execute();
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error al ejcutar la consulta MySQL: ' . $e->getMessage());
        }
    }

    public function backup()
    {
        try {
            $consulta = $this->conexion->query("SHOW TABLES");
            $tablas = $consulta->fetchAll(PDO::FETCH_COLUMN);
            $respaldo_data = 'SET FOREIGN_KEY_CHECKS = 0;';
            foreach ($tablas as $tabla) {
                $crear = $this->conexion->query("SHOW CREATE TABLE $tabla")->fetch(PDO::FETCH_ASSOC);
                $respaldo_data .= "\n\n" . $crear['Create Table'] . ";\n\n";
                $filas = $this->conexion->query("SELECT * FROM $tabla")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($filas as $fila) {
                    $respaldo_data .= "INSERT INTO $tabla VALUES (";
                    $respaldo_data .= "'" . implode("','", array_map(function ($valor) {
                        return addslashes($valor);
                    }, $fila)) . "'";
                    $respaldo_data .= ");\n";
                }
            }
            $respaldo_data .= "\n\n" . 'SET FOREIGN_KEY_CHECKS = 1;';
            $path_bck = $_ENV['BCK_BACKUP_PATH'];
            if (!file_exists("storage/")) {
                mkdir("storage/", 0755);
                if (!file_exists("storage/backup/")) {
                    mkdir("storage/backup/", 0755);
                }
            } else {
                if (!file_exists("storage/backup/")) {
                    mkdir("storage/backup/", 0755);
                }
            }
            $respaldo_archivo = 'backup_' . date("Ymd_His") . '.sql';
            if (file_put_contents($path_bck . $respaldo_archivo, $respaldo_data)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die("Error al realizar el respaldo de base de datos: " . $e->getMessage());
        }
    }

    public function restore($respaldo_archivo)
    {
        $ruta = $_ENV['BCK_BACKUP_PATH'];
        try {
            $result = $this->conexion->query("SHOW TABLES");
            $tablas = $result->fetchAll(PDO::FETCH_COLUMN);
            $this->conexion->exec("SET FOREIGN_KEY_CHECKS = 0");
            foreach ($tablas as $tabla) {
                $this->conexion->beginTransaction();
                $this->conexion->exec("DROP TABLE IF EXISTS " . $tabla);
                $this->conexion->commit();
            }
            $sql = file_get_contents($ruta . $respaldo_archivo);
            $this->conexion->beginTransaction();
            $this->conexion->exec($sql);
            $this->conexion->commit();
            $this->conexion->exec("SET FOREIGN_KEY_CHECKS = 1");
            return true;
        } catch (PDOException $e) {
            die("Error al restaurar la base de datos: " . $e->getMessage());
        }
    }

    public function restore_factory($respaldo_archivo)
    {
        $ruta = $_ENV['BCK_BACKUP_FACTORY_PATH'];
        try {
            $sql = file_get_contents($ruta . $respaldo_archivo);
            $this->conexion->exec($sql);
            return true;
        } catch (PDOException $e) {
            die("Error al restablecer de fabrica la base de datos: " . $e->getMessage());
        }
    }

    public function listarBck()
    {
        $ruta = $_ENV['BCK_BACKUP_PATH'];
        $elementos = [];
        if (!file_exists("storage/")) {
            mkdir("storage/", 0755);
            if (!file_exists("storage/backup/")) {
                mkdir("storage/backup/", 0755);
                $elementos = scandir($ruta);
            }
        } else {
            $elementos = scandir($ruta);
        }
        return $elementos;
    }

    public function habilitar_revision_foreign_key()
    {
        try {
            $this->conexion->exec("SET FOREIGN_KEY_CHECKS = 1;");
            return true;
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
            return "Error de conexión a la base de datos: " . $e->getMessage();
        }
    }

    public function deshabilitar_revision_foreign_key()
    {
        try {
            $this->conexion->exec("SET FOREIGN_KEY_CHECKS = 0;");
            return true;
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
            return "Error de conexión a la base de datos: " . $e->getMessage();
        }
    }

    public function statusConnection()
    {
        try {
            if (!$this->conexionExitosa || !($this->conexion instanceof PDO)) {
                return false;
            }
            $this->consultar("SELECT 1");
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerInformacionCompletaJSON()
    {
        header('Content-Type: application/json');
        echo json_encode(json_encode($this->obtenerInformacionCompleta()));
        die();
    }

    public function obtenerInformacionCompleta()
    {
        try {
            $informacion = [];

            // Verificar si hay conexión activa y funcional
            $conexionActiva = $this->verificarConexionActiva();

            // 1. Información básica de conexión
            $informacion['conexion'] = [
                'servidor' => $this->servidor ?? 'No configurado',
                'base_datos' => $this->dbname ?? 'No configurado',
                'usuario' => $this->usuario ?? 'No configurado',
                'password' => $this->password ? '***' . substr($this->password, -3) : 'No configurado',
                'charset' => 'utf8mb4',
                'driver' => 'MySQL',
                'estado' => $conexionActiva ? 'Conectado' : 'Desconectado',
                'conexion_inicial' => $this->conexionExitosa ? 'Exitosa' : 'Fallida'
            ];

            // Si no hay conexión activa, retornar información básica con valores por defecto
            if (!$conexionActiva) {
                return $this->obtenerInformacionSinConexion($informacion);
            }

            // 2. Información del servidor MySQL
            try {
                $serverEstatus = $this->statusConnection();
                $serverInfo = $this->conexion->getAttribute(PDO::ATTR_SERVER_INFO);
                $serverVersion = $this->conexion->getAttribute(PDO::ATTR_SERVER_VERSION);
                $clientVersion = $this->conexion->getAttribute(PDO::ATTR_CLIENT_VERSION);

                $informacion['servidor'] = [
                    'version_servidor' => $serverVersion,
                    'version_cliente' => $clientVersion,
                    'info_servidor' => $serverInfo,
                    'estado_servidor' => $serverEstatus,
                    'conexion_id' => $this->conexion->query('SELECT CONNECTION_ID()')->fetchColumn(),
                    'timestamp_servidor' => $this->conexion->query('SELECT NOW()')->fetchColumn()
                ];
            } catch (PDOException $e) {
                $informacion['servidor'] = [
                    'version_servidor' => 'Error al obtener',
                    'version_cliente' => 'Error al obtener',
                    'info_servidor' => 'Error al obtener',
                    'estado_servidor' => 'Error',
                    'conexion_id' => 'Error al obtener',
                    'timestamp_servidor' => 'Error al obtener',
                    'error' => $e->getMessage()
                ];
            }

            // 3. Estado de la base de datos
            try {
                $estadoDB = $this->conexion->query("
            SELECT 
                TABLE_SCHEMA as base_datos,
                SUM(data_length + index_length) as tamaño_total_bytes,
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as tamaño_total_mb,
                COUNT(*) as total_tablas
            FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = DATABASE()
        ")->fetch(PDO::FETCH_ASSOC);

                $informacion['estado_base_datos'] = $estadoDB;
            } catch (PDOException $e) {
                $informacion['estado_base_datos'] = [
                    'base_datos' => 'Error al obtener',
                    'tamaño_total_bytes' => 0,
                    'tamaño_total_mb' => 0,
                    'total_tablas' => 0,
                    'error' => $e->getMessage()
                ];
            }

            // 4. Lista de tablas con información detallada
            $informacion['tablas'] = [];
            $totalTablas = 0;
            $totalRegistros = 0;

            try {
                $tablasInfo = $this->conexion->query("
            SELECT 
                TABLE_NAME as nombre_tabla,
                TABLE_ROWS as cantidad_filas,
                DATA_LENGTH as tamaño_datos,
                INDEX_LENGTH as tamaño_indices,
                DATA_FREE as espacio_libre,
                ENGINE as motor,
                TABLE_COLLATION as collation,
                CREATE_TIME as fecha_creacion,
                UPDATE_TIME as fecha_actualizacion
            FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = DATABASE()
            ORDER BY TABLE_NAME
        ")->fetchAll(PDO::FETCH_ASSOC);

                // 5. Información detallada de cada tabla y sus registros
                foreach ($tablasInfo as $tablaInfo) {
                    $nombreTabla = $tablaInfo['nombre_tabla'];

                    try {
                        // Estructura de la tabla
                        $columnas = $this->conexion->query("DESCRIBE `$nombreTabla`")->fetchAll(PDO::FETCH_ASSOC);

                        // Claves primarias y foráneas
                        $clavesPrimarias = $this->conexion->query("
                    SELECT COLUMN_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = '$nombreTabla' 
                    AND CONSTRAINT_NAME = 'PRIMARY'
                ")->fetchAll(PDO::FETCH_COLUMN);

                        $clavesForaneas = $this->conexion->query("
                    SELECT 
                        COLUMN_NAME,
                        CONSTRAINT_NAME,
                        REFERENCED_TABLE_NAME,
                        REFERENCED_COLUMN_NAME
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = '$nombreTabla' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ")->fetchAll(PDO::FETCH_ASSOC);

                        // Registros de la tabla (con límite por seguridad/rendimiento)
                        $registros = $this->conexion->query("SELECT * FROM `$nombreTabla` LIMIT 100")->fetchAll(PDO::FETCH_ASSOC);
                        $totalRegistrosTabla = $this->conexion->query("SELECT COUNT(*) FROM `$nombreTabla`")->fetchColumn();

                        $informacion['tablas'][$nombreTabla] = [
                            'informacion' => $tablaInfo,
                            'estructura' => $columnas,
                            'claves_primarias' => $clavesPrimarias,
                            'claves_foraneas' => $clavesForaneas,
                            'total_registros' => (int)$totalRegistrosTabla,
                            'registros_muestra' => $registros,
                            'limite_registros' => count($registros)
                        ];

                        $totalTablas++;
                        $totalRegistros += (int)$totalRegistrosTabla;
                    } catch (PDOException $e) {
                        $informacion['tablas'][$nombreTabla] = [
                            'informacion' => $tablaInfo,
                            'error' => 'No se pudo obtener información detallada: ' . $e->getMessage(),
                            'estructura' => [],
                            'claves_primarias' => [],
                            'claves_foraneas' => [],
                            'total_registros' => 0,
                            'registros_muestra' => [],
                            'limite_registros' => 0
                        ];
                        $totalTablas++;
                    }
                }
            } catch (PDOException $e) {
                return $this->obtenerInformacionConError($e->getMessage());
            }

            // 6. Estadísticas generales
            $informacion['estadisticas'] = [
                'total_tablas' => $totalTablas,
                'total_registros' => $totalRegistros,
                'tamaño_total_mb' => $informacion['estado_base_datos']['tamaño_total_mb'] ?? 0,
                'timestamp_consulta' => date('Y-m-d H:i:s')
            ];

            // 7. Variables importantes del servidor
            try {
                $variablesServidor = $this->conexion->query("SHOW VARIABLES LIKE '%version%'")->fetchAll(PDO::FETCH_ASSOC);
                $informacion['variables_servidor'] = $variablesServidor;
            } catch (PDOException $e) {
                $informacion['variables_servidor'] = [
                    'error' => 'No se pudieron obtener las variables: ' . $e->getMessage()
                ];
            }

            // 8. Procesos activos
            try {
                $procesosActivos = $this->conexion->query("SHOW PROCESSLIST")->fetchAll(PDO::FETCH_ASSOC);
                $informacion['procesos_activos'] = $procesosActivos;
            } catch (PDOException $e) {
                $informacion['procesos_activos'] = [
                    'error' => 'No se pudieron obtener los procesos: ' . $e->getMessage()
                ];
            }

            // 9. Información de caracteres y collation
            try {
                $charsetInfo = $this->conexion->query("
            SELECT 
                @@character_set_server as charset_servidor,
                @@collation_server as collation_servidor,
                @@character_set_database as charset_base_datos,
                @@collation_database as collation_base_datos,
                @@character_set_client as charset_cliente,
                @@character_set_connection as charset_conexion,
                @@character_set_results as charset_resultados
        ")->fetch(PDO::FETCH_ASSOC);

                $informacion['configuracion_charset'] = $charsetInfo;
            } catch (PDOException $e) {
                $informacion['configuracion_charset'] = [
                    'charset_servidor' => 'Error al obtener',
                    'collation_servidor' => 'Error al obtener',
                    'charset_base_datos' => 'Error al obtener',
                    'collation_base_datos' => 'Error al obtener',
                    'charset_cliente' => 'Error al obtener',
                    'charset_conexion' => 'Error al obtener',
                    'charset_resultados' => 'Error al obtener',
                    'error' => $e->getMessage()
                ];
            }

            return $informacion;
        } catch (Exception $e) {
            return $this->obtenerInformacionConError($e->getMessage());
        }
    }

    // Método adicional para obtener solo estadísticas básicas (más rápido)
    public function obtenerEstadisticasBasicas()
    {
        try {
            $estadisticas = [];

            // Verificar si hay conexión activa y funcional
            $conexionActiva = $this->verificarConexionActiva();

            // Información básica
            $estadisticas['base_datos'] = $this->dbname ?? 'No configurado';
            $estadisticas['servidor'] = $this->servidor ?? 'No configurado';
            $estadisticas['estado_conexion'] = $conexionActiva ? 'Conectado' : 'Desconectado';

            // Si no hay conexión activa, retornar valores por defecto
            if (!$conexionActiva) {
                $estadisticas['total_tablas'] = 0;
                $estadisticas['tamaño_total_mb'] = 0;
                $estadisticas['timestamp'] = date('Y-m-d H:i:s');
                $estadisticas['mensaje'] = 'No hay conexión activa a la base de datos';
                return $estadisticas;
            }

            // Conteo de tablas
            try {
                $tablas = $this->conexion->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                $estadisticas['total_tablas'] = count($tablas);
            } catch (PDOException $e) {
                $estadisticas['total_tablas'] = 0;
                $estadisticas['error_tablas'] = $e->getMessage();
            }

            // Tamaño total
            try {
                $tamaño = $this->conexion->query("
            SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as tamaño_mb 
            FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = DATABASE()
        ")->fetchColumn();
                $estadisticas['tamaño_total_mb'] = $tamaño;
            } catch (PDOException $e) {
                $estadisticas['tamaño_total_mb'] = 0;
                $estadisticas['error_tamaño'] = $e->getMessage();
            }

            $estadisticas['timestamp'] = date('Y-m-d H:i:s');

            return $estadisticas;
        } catch (Exception $e) {
            // Retornar estadísticas básicas con información del error
            return [
                'base_datos' => $this->dbname ?? 'No configurado',
                'servidor' => $this->servidor ?? 'No configurado',
                'total_tablas' => 0,
                'tamaño_total_mb' => 0,
                'timestamp' => date('Y-m-d H:i:s'),
                'estado_conexion' => 'Error',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Método para verificar si la conexión está activa y funcional
     */
    private function verificarConexionActiva()
    {
        try {
            // Verificar si la conexión inicial fue exitosa y es una instancia de PDO
            if (!$this->conexionExitosa || !($this->conexion instanceof PDO)) {
                return false;
            }

            // Intentar ejecutar una consulta simple para verificar que la conexión funciona
            $this->conexion->query("SELECT 1")->fetchColumn();
            return true;
        } catch (PDOException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Método auxiliar para generar información cuando no hay conexión
     */
    private function obtenerInformacionSinConexion($informacionBase)
    {
        $informacionBase['servidor'] = [
            'version_servidor' => 'No disponible',
            'version_cliente' => 'No disponible',
            'info_servidor' => 'No disponible',
            'estado_servidor' => false,
            'conexion_id' => 'No disponible',
            'timestamp_servidor' => 'No disponible',
            'mensaje' => 'Servidor MySQL no disponible'
        ];

        $informacionBase['estado_base_datos'] = [
            'base_datos' => 'No disponible',
            'tamaño_total_bytes' => 0,
            'tamaño_total_mb' => 0,
            'total_tablas' => 0,
            'mensaje' => 'Servidor MySQL no disponible'
        ];

        $informacionBase['tablas'] = [];

        $informacionBase['estadisticas'] = [
            'total_tablas' => 0,
            'total_registros' => 0,
            'tamaño_total_mb' => 0,
            'timestamp_consulta' => date('Y-m-d H:i:s'),
            'mensaje' => 'Servidor MySQL no disponible'
        ];

        $informacionBase['variables_servidor'] = [];

        $informacionBase['procesos_activos'] = [];

        $informacionBase['configuracion_charset'] = [
            'charset_servidor' => 'No disponible',
            'collation_servidor' => 'No disponible',
            'charset_base_datos' => 'No disponible',
            'collation_base_datos' => 'No disponible',
            'charset_cliente' => 'No disponible',
            'charset_conexion' => 'No disponible',
            'charset_resultados' => 'No disponible',
            'mensaje' => 'Servidor MySQL no disponible'
        ];

        return $informacionBase;
    }

    /**
     * Método auxiliar para generar información cuando hay error en la conexión
     */
    private function obtenerInformacionConError($mensajeError)
    {
        $informacion = [];

        $informacion['conexion'] = [
            'servidor' => $this->servidor ?? 'No configurado',
            'base_datos' => $this->dbname ?? 'No configurado',
            'usuario' => $this->usuario ?? 'No configurado',
            'password' => $this->password ? '***' . substr($this->password, -3) : 'No configurado',
            'charset' => 'utf8mb4',
            'driver' => 'MySQL',
            'estado' => 'Error',
            'error' => $mensajeError
        ];

        $informacion['servidor'] = [
            'version_servidor' => 'No disponible',
            'version_cliente' => 'No disponible',
            'info_servidor' => 'No disponible',
            'estado_servidor' => 'Error',
            'conexion_id' => 'No disponible',
            'timestamp_servidor' => 'No disponible',
            'error' => $mensajeError
        ];

        $informacion['estado_base_datos'] = [
            'base_datos' => 'No disponible',
            'tamaño_total_bytes' => 0,
            'tamaño_total_mb' => 0,
            'total_tablas' => 0,
            'error' => $mensajeError
        ];

        $informacion['tablas'] = [];

        $informacion['estadisticas'] = [
            'total_tablas' => 0,
            'total_registros' => 0,
            'tamaño_total_mb' => 0,
            'timestamp_consulta' => date('Y-m-d H:i:s'),
            'error' => $mensajeError
        ];

        $informacion['variables_servidor'] = [
            'error' => $mensajeError
        ];

        $informacion['procesos_activos'] = [
            'error' => $mensajeError
        ];

        $informacion['configuracion_charset'] = [
            'charset_servidor' => 'No disponible',
            'collation_servidor' => 'No disponible',
            'charset_base_datos' => 'No disponible',
            'collation_base_datos' => 'No disponible',
            'charset_cliente' => 'No disponible',
            'charset_conexion' => 'No disponible',
            'charset_resultados' => 'No disponible',
            'error' => $mensajeError
        ];

        return $informacion;
    }
}
