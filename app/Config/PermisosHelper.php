<?php

namespace App\Config;

use App\Controllers\Mantenimientos\AuditoriaController;

/**
 * PermisosHelper - Sistema de Control de Acceso y Permisos
 * 
 * Proporciona métodos para verificar permisos de usuario según nivel de acceso
 * Implementa 4 niveles de control: Menú, Botones UI, Controladores y Auditoría
 */
class PermisosHelper
{
    // Constantes de Niveles de Usuario
    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const COORDINADOR = 3;
    const SECRETARIO = 4;

    // Constantes de Acciones CRUD
    const VER = 'ver';
    const REGISTRAR = 'registrar';
    const ACTUALIZAR = 'actualizar';
    const ELIMINAR = 'eliminar';
    const HABILITAR = 'habilitar';
    const DESHABILITAR = 'deshabilitar';
    const CARGA_MASIVA = 'carga_masiva';

    // Constantes de Módulos
    const MODULO_CATALOGOS = 'catalogos';
    const MODULO_CATEGORIAS = 'categorias';
    const MODULO_TIPOS_PRACTICA = 'tipos_practica';
    const MODULO_SISTEMAS_CORPORALES = 'sistemas_corporales';
    const MODULO_ESPECIALIDADES = 'especialidades';
    const MODULO_SUBESPECIALIDADES = 'subespecialidades';
    const MODULO_MEDICOS = 'medicos';
    const MODULO_CARGA_MASIVA = 'carga_masiva';
    const MODULO_RECONOCIMIENTOS = 'reconocimientos';
    const MODULO_REPORTES = 'reportes';
    const MODULO_MANTENIMIENTOS = 'mantenimientos';
    const MODULO_AUDITORIAS = 'auditorias';
    const MODULO_DEPORTES = 'deportes';
    const MODULO_USUARIOS = 'usuarios';
    const MODULO_SERVICIOS_BD = 'servicios_bd';

    /**
     * Matriz de Permisos por Nivel
     * Estructura: [nivel][modulo][accion] = true/false
     */
    private static $matrizPermisos = [
        // NIVEL 1: SUPER ADMINISTRADOR - Acceso completo
        self::SUPER_ADMIN => [
            self::MODULO_CATEGORIAS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => true,
            ],
            self::MODULO_TIPOS_PRACTICA => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => true,
            ],
            self::MODULO_SISTEMAS_CORPORALES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => true,
            ],
            self::MODULO_ESPECIALIDADES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => true,
                self::HABILITAR => true,
                self::DESHABILITAR => true,
            ],
            self::MODULO_SUBESPECIALIDADES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => true,
                self::HABILITAR => true,
                self::DESHABILITAR => true,
            ],
            self::MODULO_MEDICOS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => true,
            ],
            self::MODULO_CARGA_MASIVA => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => true,
            ],
            self::MODULO_RECONOCIMIENTOS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => true,
            ],
            self::MODULO_REPORTES => [
                self::VER => true,
            ],
            self::MODULO_AUDITORIAS => [
                self::VER => true,
            ],
            self::MODULO_DEPORTES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => true,
            ],
            self::MODULO_USUARIOS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => true,
            ],
            self::MODULO_SERVICIOS_BD => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => true,
            ],
        ],

        // NIVEL 2: ADMINISTRADOR - Sin eliminación, sin servicios BD
        self::ADMIN => [
            self::MODULO_CATEGORIAS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_TIPOS_PRACTICA => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_SISTEMAS_CORPORALES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_ESPECIALIDADES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
                self::HABILITAR => true,
                self::DESHABILITAR => true,
            ],
            self::MODULO_SUBESPECIALIDADES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
                self::HABILITAR => true,
                self::DESHABILITAR => true,
            ],
            self::MODULO_MEDICOS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_CARGA_MASIVA => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_RECONOCIMIENTOS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_REPORTES => [
                self::VER => true,
            ],
            self::MODULO_AUDITORIAS => [
                self::VER => true,
            ],
            self::MODULO_DEPORTES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_USUARIOS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_SERVICIOS_BD => [
                self::VER => false,
                self::REGISTRAR => false,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
            ],
        ],

        // NIVEL 3: COORDINADOR - Sin eliminación, sin habilitar/deshabilitar
        self::COORDINADOR => [
            self::MODULO_CATEGORIAS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_TIPOS_PRACTICA => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_SISTEMAS_CORPORALES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_ESPECIALIDADES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
                self::HABILITAR => false,
                self::DESHABILITAR => false,
            ],
            self::MODULO_SUBESPECIALIDADES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
                self::HABILITAR => false,
                self::DESHABILITAR => false,
            ],
            self::MODULO_MEDICOS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_CARGA_MASIVA => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_RECONOCIMIENTOS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_REPORTES => [
                self::VER => true,
            ],
            self::MODULO_AUDITORIAS => [
                self::VER => false,
            ],
            self::MODULO_DEPORTES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_USUARIOS => [
                self::VER => false,
                self::REGISTRAR => false,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
            ],
            self::MODULO_SERVICIOS_BD => [
                self::VER => false,
                self::REGISTRAR => false,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
            ],
        ],

        // NIVEL 4: SECRETARIO - Solo registrar y ver, sin eliminar ni actualizar
        self::SECRETARIO => [
            self::MODULO_CATEGORIAS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
            ],
            self::MODULO_TIPOS_PRACTICA => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
            ],
            self::MODULO_SISTEMAS_CORPORALES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
            ],
            self::MODULO_ESPECIALIDADES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
                self::HABILITAR => false,
                self::DESHABILITAR => false,
            ],
            self::MODULO_SUBESPECIALIDADES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
                self::HABILITAR => false,
                self::DESHABILITAR => false,
            ],
            self::MODULO_MEDICOS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => true,
                self::ELIMINAR => false,
            ],
            self::MODULO_CARGA_MASIVA => [
                self::VER => false,
                self::REGISTRAR => false,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
            ],
            self::MODULO_RECONOCIMIENTOS => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
            ],
            self::MODULO_REPORTES => [
                self::VER => true,
            ],
            self::MODULO_AUDITORIAS => [
                self::VER => false,
            ],
            self::MODULO_DEPORTES => [
                self::VER => true,
                self::REGISTRAR => true,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
            ],
            self::MODULO_USUARIOS => [
                self::VER => false,
                self::REGISTRAR => false,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
            ],
            self::MODULO_SERVICIOS_BD => [
                self::VER => false,
                self::REGISTRAR => false,
                self::ACTUALIZAR => false,
                self::ELIMINAR => false,
            ],
        ],
    ];

    /**
     * Obtiene el nivel de acceso del usuario actual desde la sesión
     */
    public static function getNivelActual(): int
    {
        return isset($_SESSION['nivel_acceso']) ? (int)$_SESSION['nivel_acceso'] : 0;
    }

    /**
     * Obtiene el ID del usuario actual desde la sesión
     */
    public static function getUsuarioActual(): ?int
    {
        return isset($_SESSION['id_usuario']) ? (int)$_SESSION['id_usuario'] : null;
    }

    /**
     * Obtiene el nombre del usuario actual desde la sesión
     */
    public static function getNombreUsuarioActual(): string
    {
        return isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : 'unknown';
    }

    /**
     * Verifica si el usuario actual tiene permiso para una acción específica
     * 
     * @param string $modulo El módulo a verificar
     * @param string $accion La acción a verificar
     * @return bool True si tiene permiso, false si no
     */
    public static function tienePermiso(string $modulo, string $accion): bool
    {
        $nivel = self::getNivelActual();
        
        // Si no hay sesión activa, no tiene permisos
        if ($nivel === 0) {
            return false;
        }

        // Verificar si el nivel existe en la matriz
        if (!isset(self::$matrizPermisos[$nivel])) {
            return false;
        }

        // Verificar si el módulo existe en la matriz del nivel
        if (!isset(self::$matrizPermisos[$nivel][$modulo])) {
            return false;
        }

        // Verificar si la acción existe en la matriz del módulo
        if (!isset(self::$matrizPermisos[$nivel][$modulo][$accion])) {
            return false;
        }

        return self::$matrizPermisos[$nivel][$modulo][$accion] === true;
    }

    /**
     * Verifica si el usuario puede eliminar registros
     * 
     * @param string $modulo El módulo a verificar
     * @return bool True si puede eliminar
     */
    public static function puedeEliminar(string $modulo): bool
    {
        return self::tienePermiso($modulo, self::ELIMINAR);
    }

    /**
     * Verifica si el usuario puede registrar
     * 
     * @param string $modulo El módulo a verificar
     * @return bool True si puede registrar
     */
    public static function puedeRegistrar(string $modulo): bool
    {
        return self::tienePermiso($modulo, self::REGISTRAR);
    }

    /**
     * Verifica si el usuario puede actualizar
     * 
     * @param string $modulo El módulo a verificar
     * @return bool True si puede actualizar
     */
    public static function puedeActualizar(string $modulo): bool
    {
        return self::tienePermiso($modulo, self::ACTUALIZAR);
    }

    /**
     * Verifica si el usuario puede ver
     * 
     * @param string $modulo El módulo a verificar
     * @return bool True si puede ver
     */
    public static function puedeVer(string $modulo): bool
    {
        return self::tienePermiso($modulo, self::VER);
    }

    /**
     * Verifica si el usuario puede habilitar registros
     * 
     * @param string $modulo El módulo a verificar
     * @return bool True si puede habilitar
     */
    public static function puedeHabilitar(string $modulo): bool
    {
        return self::tienePermiso($modulo, self::HABILITAR);
    }

    /**
     * Verifica si el usuario puede deshabilitar registros
     * 
     * @param string $modulo El módulo a verificar
     * @return bool True si puede deshabilitar
     */
    public static function puedeDeshabilitar(string $modulo): bool
    {
        return self::tienePermiso($modulo, self::DESHABILITAR);
    }

    /**
     * Verifica si el usuario tiene acceso a un módulo completo
     * 
     * @param string $modulo El módulo a verificar
     * @return bool True si tiene al menos acceso de ver
     */
    public static function tieneAccesoAModulo(string $modulo): bool
    {
        return self::puedeVer($modulo);
    }

    /**
     * Obtiene todos los módulos accesibles para el nivel actual
     * 
     * @return array Lista de módulos accesibles
     */
    public static function getModulosAccesibles(): array
    {
        $nivel = self::getNivelActual();
        $modulos = [];

        if (isset(self::$matrizPermisos[$nivel])) {
            foreach (self::$matrizPermisos[$nivel] as $modulo => $acciones) {
                if (isset($acciones[self::VER]) && $acciones[self::VER] === true) {
                    $modulos[] = $modulo;
                }
            }
        }

        return $modulos;
    }

    /**
     * Obtiene las acciones permitidas para un módulo específico
     * 
     * @param string $modulo El módulo a verificar
     * @return array Lista de acciones permitidas
     */
    public static function getAccionesPermitidas(string $modulo): array
    {
        $nivel = self::getNivelActual();
        $acciones = [];

        if (isset(self::$matrizPermisos[$nivel][$modulo])) {
            foreach (self::$matrizPermisos[$nivel][$modulo] as $accion => $permitido) {
                if ($permitido === true) {
                    $acciones[] = $accion;
                }
            }
        }

        return $acciones;
    }

    /**
     * Verifica el acceso y registra intento no autorizado en auditoría
     * 
     * @param string $modulo El módulo al que se intentó acceder
     * @param string $accion La acción que se intentó realizar
     * @param bool $mostrarError Si true, muestra error y redirecciona
     * @return bool True si tiene permiso, false si no
     */
    public static function verificarAcceso(string $modulo, string $accion, bool $mostrarError = true): bool
    {
        if (self::tienePermiso($modulo, $accion)) {
            return true;
        }

        // Registrar intento no autorizado en auditoría
        self::registrarIntentoNoAutorizado($modulo, $accion);

        if ($mostrarError) {
            self::mostrarErrorAcceso();
        }

        return false;
    }

    /**
     * Registra un intento de acceso no autorizado en la auditoría
     * 
     * @param string $modulo El módulo al que se intentó acceder
     * @param string $accion La acción que se intentó realizar
     */
    public static function registrarIntentoNoAutorizado(string $modulo, string $accion): void
    {
        try {
            $descripcion = sprintf(
                "Intento de acceso no autorizado: Usuario '%s' (Nivel %d, ID: %d) intentó '%s' en módulo '%s' sin permisos",
                self::getNombreUsuarioActual(),
                self::getNivelActual(),
                self::getUsuarioActual() ?? 0,
                $accion,
                $modulo
            );

            $auditoria = new AuditoriaController();
            $auditoria->store([
                'ID' => self::getUsuarioActual() ?? 0,
                'accion' => $descripcion
            ]);
        } catch (\Exception $e) {
            // Si falla el registro de auditoría, continuar silenciosamente
            error_log("Error al registrar auditoría de acceso: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la IP del cliente
     * 
     * @return string La IP del cliente
     */
    private static function obtenerIPCliente(): string
    {
        $ip = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }

        return $ip;
    }

    /**
     * Muestra error de acceso y redirecciona
     */
    public static function mostrarErrorAcceso(): void
    {
        // Usar el controlador de alertas si está disponible
        if (class_exists('App\Controllers\AlertasController')) {
            \App\Controllers\AlertasController::error(
                "Acceso Denegado",
                "No tienes permisos para realizar esta acción. El intento ha sido registrado."
            );
        }
        
        // Redireccionar al home
        header("location: /SIMA/home");
        exit();
    }

    /**
     * Redirecciona según el resultado de la verificación
     * 
     * @param string $modulo El módulo a verificar
     * @param string $accion La acción a verificar
     * @param string $rutaRedireccion Ruta a redireccionar si no tiene acceso
     * @return bool True si tiene acceso
     */
    public static function verificarYRedireccionar(string $modulo, string $accion, string $rutaRedireccion = '/SIMA/home'): bool
    {
        if (!self::tienePermiso($modulo, $accion)) {
            self::registrarIntentoNoAutorizado($modulo, $accion);
            header("location: " . $rutaRedireccion);
            exit();
        }
        return true;
    }

    /**
     * Obtiene el nombre del rol según el nivel
     * 
     * @param int|null $nivel El nivel de acceso (opcional, usa el actual si no se especifica)
     * @return string Nombre del rol
     */
    public static function getNombreRol(int $nivel = null): string
    {
        $nivel = $nivel ?? self::getNivelActual();
        
        switch ($nivel) {
            case self::SUPER_ADMIN:
                return "Super Administrador (a)";
            case self::ADMIN:
                return "Administrador (a)";
            case self::COORDINADOR:
                return "Coordinador (a)";
            case self::SECRETARIO:
                return "Secretario (a)";
            default:
                return "Usuario";
        }
    }

    /**
     * Verifica si el usuario es Super Administrador
     * 
     * @return bool True si es Super Administrador
     */
    public static function esSuperAdmin(): bool
    {
        return self::getNivelActual() === self::SUPER_ADMIN;
    }

    /**
     * Verifica si el usuario es Administrador
     * 
     * @return bool True si es Administrador
     */
    public static function esAdmin(): bool
    {
        return self::getNivelActual() === self::ADMIN;
    }

    /**
     * Verifica si el usuario es Coordinador
     * 
     * @return bool True si es Coordinador
     */
    public static function esCoordinador(): bool
    {
        return self::getNivelActual() === self::COORDINADOR;
    }

    /**
     * Verifica si el usuario es Secretario
     * 
     * @return bool True si es Secretario
     */
    public static function esSecretario(): bool
    {
        return self::getNivelActual() === self::SECRETARIO;
    }
}
