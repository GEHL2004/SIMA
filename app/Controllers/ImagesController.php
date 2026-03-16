<?php

namespace App\Controllers;

use Error;

class ImagesController
{
    const DIRECTORIOS = [
        'graficos' => '/images_graficos',
        'personal' => '/personal',
        'medicos' => '/medicos',
    ];

    const PERMISOS_DIRECTORIO = 0775;

    // Lista blanca de tipos MIME permitidos
    const MIME_TYPES_PERMITIDOS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/svg+xml' => 'svg'
    ];

    /**
     * Guarda una única imagen con validaciones de seguridad
     * @param array $file Array del archivo subido ($_FILES['nombre'])
     * @param string $directorio Clave del directorio (ej: 'graficos')
     * @param string|null $nombrePersonalizado Nombre base personalizado (sin extensión)
     */
    public function guardarImagen(array $file, string $directorio, ?string $nombrePersonalizado = null): array
    {
        try {
            // Validaciones iniciales
            $this->validarArchivo($file);
            $this->validarDirectorioClave($directorio);

            $rutaDirectorio = $this->validarYCrearDirectorio($directorio);
            $nombreArchivoSeguro = $this->generarNombreSeguro($file, $nombrePersonalizado);
            $rutaCompleta = $rutaDirectorio . '/' . $nombreArchivoSeguro;

            // Validar tipo MIME
            $tipoMime = $this->obtenerTipoMimeReal($file['tmp_name']);
            $this->validarTipoMime($tipoMime);

            // Mover archivo subido
            if (!move_uploaded_file($file['tmp_name'], $rutaCompleta)) {
                throw new \Exception('Error al mover el archivo subido');
            }

            return [
                'success' => true,
                'message' => 'Imagen guardada exitosamente',
                'archivo' => $nombreArchivoSeguro,
                'ruta' => $rutaCompleta,
                'nombre_original' => $file['name']
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'archivo' => $file['name'] ?? 'Desconocido'
            ];
        }
    }

    /**
     * Guarda múltiples imágenes
     * @param array $files Array de archivos múltiples
     * @param string $directorio Clave del directorio
     * @param array|null $nombresPersonalizados Array de nombres personalizados (opcional, debe coincidir con el índice)
     */
    public function guardarMultiplesImagenes(array $files, string $directorio, ?array $nombresPersonalizados = null): array
    {
        $resultados = [];

        // Validar estructura de archivos múltiples
        if (!isset($files['name']) || !is_array($files['name'])) {
            return [
                'success' => false,
                'error' => 'Estructura de archivos múltiples inválida'
            ];
        }

        foreach ($files['name'] as $index => $nombre) {
            // Reconstruir estructura de archivo individual
            $archivoIndividual = [
                'name' => $files['name'][$index],
                'type' => $files['type'][$index],
                'tmp_name' => $files['tmp_name'][$index],
                'error' => $files['error'][$index],
                'size' => $files['size'][$index]
            ];

            // Obtener nombre personalizado si existe
            $nombrePersonalizado = isset($nombresPersonalizados[$index]) ? $nombresPersonalizados[$index] : null;

            // Solo procesar si no hay error de subida
            if ($archivoIndividual['error'] === UPLOAD_ERR_OK) {
                $resultados[] = $this->guardarImagen($archivoIndividual, $directorio, $nombrePersonalizado);
            } else {
                $resultados[] = [
                    'success' => false,
                    'error' => $this->obtenerMensajeErrorSubida($archivoIndividual['error']),
                    'archivo' => $nombre
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Procesamiento de múltiples imágenes completado',
            'resultados' => $resultados
        ];
    }

    /**
     * Maneja la respuesta final (JSON o array)
     */
    public function retornarRespuesta(array $data, bool $esJson = true)
    {
        if ($esJson) {
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }

        return $data;
    }

    /**
     * Valida y crea el directorio si no existe
     */
    private function validarYCrearDirectorio(string $directorioClave): string
    {
        if (!isset(self::DIRECTORIOS[$directorioClave])) {
            throw new \Exception("Directorio clave '{$directorioClave}' no válido");
        }

        $RUTA_BASE = "../" . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] . "/assets/images";
        $rutaDirectorio = $RUTA_BASE . self::DIRECTORIOS[$directorioClave];

        if (!is_dir($rutaDirectorio)) {
            if (!mkdir($rutaDirectorio, self::PERMISOS_DIRECTORIO, true)) {
                throw new \Exception("No se pudo crear el directorio: {$rutaDirectorio}");
            }
        }

        if (!is_writable($rutaDirectorio)) {
            throw new \Exception("El directorio no tiene permisos de escritura: {$rutaDirectorio}");
        }

        return $rutaDirectorio;
    }

    /**
     * Valida el archivo subido
     */
    private function validarArchivo(array $file): void
    {
        $MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception($this->obtenerMensajeErrorSubida($file['error'] ?? UPLOAD_ERR_NO_FILE));
        }

        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new \Exception('Archivo no subido mediante HTTP POST');
        }

        if ($file['size'] > $MAX_FILE_SIZE) {
            throw new \Exception('El archivo excede el tamaño máximo permitido de 10MB');
        }

        if ($file['size'] === 0) {
            throw new \Exception('El archivo está vacío');
        }
    }

    /**
     * Valida la clave del directorio
     */
    private function validarDirectorioClave(string $directorio): void
    {
        if (!array_key_exists($directorio, self::DIRECTORIOS)) {
            throw new \Exception("Tipo de directorio no válido: {$directorio}");
        }
    }

    /**
     * Valida el tipo MIME del archivo
     */
    private function validarTipoMime(string $tipoMime): void
    {
        if (!array_key_exists($tipoMime, self::MIME_TYPES_PERMITIDOS)) {
            throw new \Exception("Tipo de archivo no permitido: {$tipoMime}");
        }
    }

    /**
     * Obtiene el tipo MIME real del archivo
     */
    private function obtenerTipoMimeReal(string $rutaTemporal): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipoMime = finfo_file($finfo, $rutaTemporal);
        finfo_close($finfo);

        if (!$tipoMime) {
            throw new \Exception('No se pudo determinar el tipo de archivo');
        }

        return $tipoMime;
    }

    /**
     * Genera un nombre de archivo seguro y único
     * @param array $file Array del archivo subido
     * @param string|null $nombrePersonalizado Nombre base personalizado (opcional)
     * @return string Nombre del archivo con extensión
     */
    private function generarNombreSeguro(array $file, ?string $nombrePersonalizado = null): string
    {
        $tipoMime = $this->obtenerTipoMimeReal($file['tmp_name']);

        if (!isset(self::MIME_TYPES_PERMITIDOS[$tipoMime])) {
            throw new \Exception("Tipo MIME no permitido: {$tipoMime}");
        }

        $extension = self::MIME_TYPES_PERMITIDOS[$tipoMime];

        // Generar parte única criptográficamente segura
        $parteUnica = bin2hex(random_bytes(8)); // 16 caracteres hex

        // Construir el nombre final
        if ($nombrePersonalizado !== null && $nombrePersonalizado !== '') {
            // Limpiar el nombre personalizado
            $nombreLimpio = $this->limpiarNombreArchivo($nombrePersonalizado);
            $nombreFinal = $nombreLimpio . '_' . $parteUnica . '.' . $extension;
        } else {
            // Usar nombre original como base (limpio)
            $nombreOriginal = pathinfo($file['name'], PATHINFO_FILENAME);
            $nombreLimpio = $this->limpiarNombreArchivo($nombreOriginal);
            $nombreFinal = $nombreLimpio . '_' . $parteUnica . '.' . $extension;
        }

        return $nombreFinal;
    }

    /**
     * Limpia un nombre de archivo para hacerlo seguro
     * @param string $nombre Nombre a limpiar
     * @return string Nombre limpio
     */
    private function limpiarNombreArchivo(string $nombre): string
    {
        // Convertir a minúsculas y reemplazar espacios/acentos
        $nombre = mb_strtoupper($nombre, 'UTF-8');
        $nombre = str_replace(' ', '_', $nombre);

        // Reemplazar caracteres especiales
        $nombre = preg_replace('/[^a-z0-9_-]/', '', $nombre);

        // Limitar longitud
        if (strlen($nombre) > 50) {
            $nombre = substr($nombre, 0, 50);
        }

        return $nombre;
    }

    /**
     * Obtiene mensaje descriptivo del error de subida
     */
    private function obtenerMensajeErrorSubida(int $errorCode): string
    {
        $mensajes = [
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño máximo permitido por el servidor',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo permitido por el formulario',
            UPLOAD_ERR_PARTIAL => 'El archivo fue solo parcialmente subido',
            UPLOAD_ERR_NO_FILE => 'No se seleccionó ningún archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'No existe directorio temporal',
            UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo en disco',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la subida del archivo',
        ];

        return $mensajes[$errorCode] ?? 'Error desconocido en la subida del archivo';
    }

    /**
     * Elimina una imagen de forma segura
     */
    public function eliminarImagen(string $nombreArchivo, string $directorio): array
    {
        try {
            // Validar directorio
            $this->validarDirectorioClave($directorio);

            // Obtener ruta del directorio
            $rutaDirectorio = $this->validarYCrearDirectorio($directorio);
            $rutaCompleta = $rutaDirectorio . '/' . $nombreArchivo;

            // Validaciones de seguridad
            if (empty($nombreArchivo) || preg_match('/\.\./', $nombreArchivo)) {
                throw new \Exception('Nombre de archivo no válido');
            }

            if (!file_exists($rutaCompleta)) {
                throw new \Exception('La imagen no existe');
            }

            if (!is_file($rutaCompleta)) {
                throw new \Exception('La ruta no corresponde a un archivo válido');
            }

            // Verificar que es una imagen (opcional pero recomendado)
            $tipoMime = $this->obtenerTipoMimeReal($rutaCompleta);
            if (!array_key_exists($tipoMime, self::MIME_TYPES_PERMITIDOS)) {
                throw new \Exception('El archivo no es una imagen válida');
            }

            // Eliminar el archivo
            if (!unlink($rutaCompleta)) {
                throw new \Exception('No se pudo eliminar la imagen');
            }

            return [
                'success' => true,
                'message' => 'Imagen eliminada exitosamente',
                'archivo' => $nombreArchivo
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'archivo' => $nombreArchivo
            ];
        }
    }

    /**
     * Método alternativo para guardar imágenes desde JSON
     * @param array $file Datos del archivo
     * @param string|null $nombrePersonalizado Nombre personalizado (opcional)
     */
    public function guardarImagenProvenienteJSON($file, ?string $nombrePersonalizado = null)
    {
        try {
            // Validar que existe la estructura esperada
            if (!isset($file['image'])) {
                throw new \Exception('Estructura de datos inválida');
            }

            $directorio = 'graficos'; // Directorio por defecto para este método
            $resultado = $this->guardarImagen($file['image'], $directorio, $nombrePersonalizado);

            if ($resultado['success']) {
                $data = array(
                    'success' => true,
                    'message' => 'Imagen guardada exitosamente',
                    'archivo' => $resultado['archivo'],
                    'ruta' => $resultado['ruta']
                );
            } else {
                $data = array(
                    'success' => false,
                    'error' => $resultado['error'],
                    'archivo' => $resultado['archivo']
                );
            }

            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        } catch (\Exception $e) {
            $data = array(
                'success' => false,
                'error' => $e->getMessage(),
                'file' => isset($file['image']['name']) ? $file['image']['name'] : 'Desconocido'
            );

            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
    }
}
