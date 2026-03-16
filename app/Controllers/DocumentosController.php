<?php

namespace App\Controllers;

class DocumentosController
{
    const DIRECTORIOS = [
        'documentos_medicos' => '/documentos_medicos',
        'documentos_varios' => '/documentos_varios',
    ];

    const PERMISOS_DIRECTORIO = 0775;

    // Lista blanca de tipos MIME permitidos para documentos
    const MIME_TYPES_PERMITIDOS = [
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.ms-powerpoint' => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'text/plain' => 'txt',
        'text/csv' => 'csv',
    ];

    /**
     * Guarda un único documento con validaciones de seguridad
     */
    public function guardarDocumento(array $file, string $directorio, string $subdirectorio = ''): array
    {
        try {
            // Validaciones iniciales
            $this->validarArchivo($file);
            $this->validarDirectorioClave($directorio);

            // Validar y sanitizar subdirectorio si se proporciona
            if (!empty($subdirectorio)) {
                $subdirectorio = $this->sanitizarSubdirectorio($subdirectorio);
            }

            $rutaDirectorio = $this->validarYCrearDirectorio($directorio, $subdirectorio);
            $nombreArchivoSeguro = $this->generarNombreSeguro($file);
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
                'message' => 'Documento guardado exitosamente',
                'archivo' => $nombreArchivoSeguro,
                'nombre_original' => $file['name'],
                'tipo_mime' => $tipoMime,
                'tamaño' => $file['size'],
                'directorio' => $directorio,
                'subdirectorio' => $subdirectorio,
                'ruta_relativa' => $this->obtenerRutaRelativa($directorio, $subdirectorio, $nombreArchivoSeguro),
                'ruta_completa' => $rutaCompleta
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
     * Guarda múltiples documentos con soporte para subdirectorios
     */
    public function guardarMultiplesDocumentos(array $files, string $directorio, string $subdirectorio = ''): array
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

            // Solo procesar si no hay error de subida
            if ($archivoIndividual['error'] === UPLOAD_ERR_OK) {
                $resultados[] = $this->guardarDocumento($archivoIndividual, $directorio, $subdirectorio);
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
            'message' => 'Procesamiento de múltiples documentos completado',
            'total' => count($resultados),
            'directorio' => $directorio,
            'subdirectorio' => $subdirectorio,
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
     * Valida y crea el directorio (y subdirectorios) si no existen
     */
    private function validarYCrearDirectorio(string $directorioClave, string $subdirectorio = ''): string
    {
        if (!isset(self::DIRECTORIOS[$directorioClave])) {
            throw new \Exception("Directorio clave '{$directorioClave}' no válido");
        }

        $RUTA_BASE = "../" . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] . "/assets/documents";
        $rutaDirectorioBase = $RUTA_BASE . self::DIRECTORIOS[$directorioClave];

        // Construir ruta completa incluyendo subdirectorio si existe
        $rutaDirectorio = $rutaDirectorioBase;
        if (!empty($subdirectorio)) {
            $rutaDirectorio .= '/' . $subdirectorio;
        }

        // Crear directorio base si no existe
        if (!is_dir($rutaDirectorioBase)) {
            if (!mkdir($rutaDirectorioBase, self::PERMISOS_DIRECTORIO, true)) {
                throw new \Exception("No se pudo crear el directorio base: {$rutaDirectorioBase}");
            }
        }

        // Crear subdirectorio si se especificó y no existe
        if (!empty($subdirectorio) && !is_dir($rutaDirectorio)) {
            if (!mkdir($rutaDirectorio, self::PERMISOS_DIRECTORIO, true)) {
                throw new \Exception("No se pudo crear el subdirectorio: {$rutaDirectorio}");
            }
        }

        if (!is_writable($rutaDirectorio)) {
            throw new \Exception("El directorio no tiene permisos de escritura: {$rutaDirectorio}");
        }

        return $rutaDirectorio;
    }

    /**
     * Sanitiza el nombre del subdirectorio
     */
    private function sanitizarSubdirectorio(string $subdirectorio): string
    {
        // Eliminar espacios en blanco al inicio y final
        $subdirectorio = trim($subdirectorio);

        // Reemplazar múltiples slashes por uno solo
        $subdirectorio = preg_replace('/\/+/', '/', $subdirectorio);

        // Eliminar puntos que puedan indicar path traversal
        $subdirectorio = str_replace(['..', './', '/.'], '', $subdirectorio);

        // Dividir en partes y sanitizar cada una
        $partes = explode('/', $subdirectorio);
        $partesSanitizadas = [];

        foreach ($partes as $parte) {
            if (empty($parte)) continue;

            // Sanitizar caracteres no permitidos en nombres de directorio
            $parteSanitizada = preg_replace('/[^\w\-\.]/', '_', $parte);

            // Evitar nombres vacíos después de sanitizar
            if (!empty($parteSanitizada)) {
                $partesSanitizadas[] = $parteSanitizada;
            }
        }

        // Limitar la profundidad máxima de subdirectorios
        if (count($partesSanitizadas) > 3) {
            $partesSanitizadas = array_slice($partesSanitizadas, 0, 3);
        }

        return implode('/', $partesSanitizadas);
    }

    /**
     * Obtiene la ruta relativa del archivo
     */
    private function obtenerRutaRelativa(string $directorio, string $subdirectorio, string $nombreArchivo): string
    {
        $ruta = self::DIRECTORIOS[$directorio];

        if (!empty($subdirectorio)) {
            $ruta .= '/' . $subdirectorio;
        }

        $ruta .= '/' . $nombreArchivo;

        return ltrim($ruta, '/');
    }



    /**
     * Valida el archivo subido
     */
    private function validarArchivo(array $file): void
    {
        $MAX_FILE_SIZE = 50 * 1024 * 1024; // 50MB para documentos
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception($this->obtenerMensajeErrorSubida($file['error'] ?? UPLOAD_ERR_NO_FILE));
        }

        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new \Exception('Archivo no subido mediante HTTP POST');
        }

        if ($file['size'] > $MAX_FILE_SIZE) {
            throw new \Exception('El archivo excede el tamaño máximo permitido de 50MB');
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
            throw new \Exception("Tipo de documento no permitido: {$tipoMime}");
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
     */
    private function generarNombreSeguro(array $file): string
    {
        $tipoMime = $this->obtenerTipoMimeReal($file['tmp_name']);

        if (!isset(self::MIME_TYPES_PERMITIDOS[$tipoMime])) {
            throw new \Exception("Tipo MIME no permitido: {$tipoMime}");
        }

        $extension = self::MIME_TYPES_PERMITIDOS[$tipoMime];
        $nombreUnico = bin2hex(random_bytes(16)); // Nombre único criptográficamente seguro
        $nombreBase = pathinfo($file['name'], PATHINFO_FILENAME);
        $nombreBaseSeguro = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombreBase);

        return $nombreBaseSeguro . '_' . $nombreUnico . '.' . $extension;
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
     * Elimina un documento de forma segura con soporte para subdirectorios
     */
    public function eliminarDocumento(string $nombreArchivo, string $directorio, string $subdirectorio = ''): array
    {
        try {
            // Validar directorio
            $this->validarDirectorioClave($directorio);

            // Sanitizar subdirectorio si se proporciona
            if (!empty($subdirectorio)) {
                $subdirectorio = $this->sanitizarSubdirectorio($subdirectorio);
            }

            // Obtener ruta del directorio
            $rutaDirectorio = $this->validarYCrearDirectorio($directorio, $subdirectorio);
            $rutaCompleta = $rutaDirectorio . '/' . $nombreArchivo;

            // Validaciones de seguridad
            if (empty($nombreArchivo) || preg_match('/\.\./', $nombreArchivo)) {
                throw new \Exception('Nombre de archivo no válido');
            }

            if (!file_exists($rutaCompleta)) {
                throw new \Exception('El documento no existe');
            }

            if (!is_file($rutaCompleta)) {
                throw new \Exception('La ruta no corresponde a un archivo válido');
            }

            // Eliminar el archivo
            if (!unlink($rutaCompleta)) {
                throw new \Exception('No se pudo eliminar el documento');
            }

            return [
                'success' => true,
                'message' => 'Documento eliminado exitosamente',
                'archivo' => $nombreArchivo,
                'directorio' => $directorio,
                'subdirectorio' => $subdirectorio
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
     * Obtiene información de un documento con soporte para subdirectorios
     */
    public function obtenerInfoDocumento(string $nombreArchivo, string $directorio, string $subdirectorio = ''): array
    {
        try {
            $this->validarDirectorioClave($directorio);
            
            // Sanitizar subdirectorio si se proporciona
            if (!empty($subdirectorio)) {
                $subdirectorio = $this->sanitizarSubdirectorio($subdirectorio);
            }
            
            $rutaDirectorio = $this->validarYCrearDirectorio($directorio, $subdirectorio);
            $rutaCompleta = $rutaDirectorio . '/' . $nombreArchivo;

            if (!file_exists($rutaCompleta)) {
                throw new \Exception('Documento no encontrado');
            }

            $tipoMime = $this->obtenerTipoMimeReal($rutaCompleta);
            $tamaño = filesize($rutaCompleta);
            $fechaModificacion = date('Y-m-d H:i:s', filemtime($rutaCompleta));

            return [
                'success' => true,
                'archivo' => $nombreArchivo,
                'tipo_mime' => $tipoMime,
                'tamaño_bytes' => $tamaño,
                'tamaño_formateado' => $this->formatearTamaño($tamaño),
                'fecha_modificacion' => $fechaModificacion,
                'directorio' => $directorio,
                'subdirectorio' => $subdirectorio,
                'ruta_relativa' => $this->obtenerRutaRelativa($directorio, $subdirectorio, $nombreArchivo),
                'ruta_completa' => $rutaCompleta
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
     * Formatea el tamaño de bytes a formato legible
     */
    private function formatearTamaño(int $bytes): string
    {
        $unidades = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($unidades) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $unidades[$i];
    }

    /**
     * Lista documentos con soporte para subdirectorios
     */
    public function listarDocumentos(string $directorio, string $subdirectorio = '', bool $recursivo = false): array
    {
        try {
            $this->validarDirectorioClave($directorio);
            
            // Sanitizar subdirectorio si se proporciona
            if (!empty($subdirectorio)) {
                $subdirectorio = $this->sanitizarSubdirectorio($subdirectorio);
            }
            
            $rutaDirectorio = $this->validarYCrearDirectorio($directorio, $subdirectorio);
            
            if (!is_dir($rutaDirectorio)) {
                return [
                    'success' => true,
                    'total' => 0,
                    'directorio' => $directorio,
                    'subdirectorio' => $subdirectorio,
                    'documentos' => []
                ];
            }
            
            $archivos = scandir($rutaDirectorio);
            $documentos = [];
            
            foreach ($archivos as $archivo) {
                if ($archivo !== '.' && $archivo !== '..') {
                    $rutaCompleta = $rutaDirectorio . '/' . $archivo;
                    
                    if (is_file($rutaCompleta)) {
                        $tipoMime = $this->obtenerTipoMimeReal($rutaCompleta);
                        if (array_key_exists($tipoMime, self::MIME_TYPES_PERMITIDOS)) {
                            $documentos[] = [
                                'nombre' => $archivo,
                                'tipo_mime' => $tipoMime,
                                'tamaño' => filesize($rutaCompleta),
                                'tamaño_formateado' => $this->formatearTamaño(filesize($rutaCompleta)),
                                'fecha_modificacion' => date('Y-m-d H:i:s', filemtime($rutaCompleta))
                            ];
                        }
                    } elseif ($recursivo && is_dir($rutaCompleta)) {
                        // Opcional: listar recursivamente si se solicita
                        $subDocumentos = $this->listarDocumentosRecursivo($rutaCompleta, $archivo);
                        $documentos = array_merge($documentos, $subDocumentos);
                    }
                }
            }
            
            return [
                'success' => true,
                'total' => count($documentos),
                'directorio' => $directorio,
                'subdirectorio' => $subdirectorio,
                'documentos' => $documentos
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Lista documentos de forma recursiva
     */
    private function listarDocumentosRecursivo(string $rutaDirectorio, string $nombreSubdirectorio): array
    {
        $documentos = [];
        $archivos = scandir($rutaDirectorio);
        
        foreach ($archivos as $archivo) {
            if ($archivo !== '.' && $archivo !== '..') {
                $rutaCompleta = $rutaDirectorio . '/' . $archivo;
                
                if (is_file($rutaCompleta)) {
                    $tipoMime = $this->obtenerTipoMimeReal($rutaCompleta);
                    if (array_key_exists($tipoMime, self::MIME_TYPES_PERMITIDOS)) {
                        $documentos[] = [
                            'nombre' => $nombreSubdirectorio . '/' . $archivo,
                            'tipo_mime' => $tipoMime,
                            'tamaño' => filesize($rutaCompleta),
                            'tamaño_formateado' => $this->formatearTamaño(filesize($rutaCompleta)),
                            'fecha_modificacion' => date('Y-m-d H:i:s', filemtime($rutaCompleta))
                        ];
                    }
                } elseif (is_dir($rutaCompleta)) {
                    $subDocumentos = $this->listarDocumentosRecursivo($rutaCompleta, $nombreSubdirectorio . '/' . $archivo);
                    $documentos = array_merge($documentos, $subDocumentos);
                }
            }
        }
        
        return $documentos;
    }

    /**
     * Crea un subdirectorio dentro de un directorio permitido
     */
    public function crearSubdirectorio(string $directorioClave, string $nombreSubdirectorio): array
    {
        try {
            $this->validarDirectorioClave($directorioClave);
            
            $nombreSubdirectorio = $this->sanitizarSubdirectorio($nombreSubdirectorio);
            
            if (empty($nombreSubdirectorio)) {
                throw new \Exception('Nombre de subdirectorio no válido');
            }
            
            $rutaDirectorio = $this->validarYCrearDirectorio($directorioClave, $nombreSubdirectorio);
            
            return [
                'success' => true,
                'message' => 'Subdirectorio creado exitosamente',
                'directorio' => $directorioClave,
                'subdirectorio' => $nombreSubdirectorio,
                'ruta_completa' => $rutaDirectorio
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'directorio' => $directorioClave,
                'subdirectorio' => $nombreSubdirectorio
            ];
        }
    }

}
