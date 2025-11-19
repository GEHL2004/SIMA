<?php

namespace App\config;

use HTMLPurifier;
use HTMLPurifier_Config;
use InvalidArgumentException;

class SecurityHelper
{
    private static $purifier = null;

    // Configuración de seguridad mejorada
    private const HTML_CONFIG = [
        'Core.Encoding' => 'UTF-8',
        'HTML.Doctype' => 'XHTML 1.0 Strict',
        'HTML.Allowed' => 'p[class],br,b,strong,i,em,a[href|title|target|rel],ul,ol,li',
        'AutoFormat.RemoveEmpty' => true,
        'AutoFormat.Linkify' => true,
        'URI.AllowedSchemes' => ['http', 'https'],
        'URI.DisableExternal' => true,
        'URI.DisableExternalResources' => true,
        'Attr.AllowedFrameTargets' => ['_blank'],
        'Attr.AllowedRel' => ['nofollow', 'noopener', 'noreferrer'],
        'HTML.SafeIframe' => false,
        'HTML.SafeObject' => false,
        'Output.FlashCompat' => false,
        'Filter.YouTube' => false,
        'CSS.AllowedProperties' => [],
        'HTML.MaxImgLength' => 1500,
        'HTML.TidyLevel' => 'heavy',

    ];

    // Regex optimizadas
    private const SECURITY_REGEX = [
        'control_chars' => '/[\x00-\x1F\x7F]/u',
        'invisible_chars' => '/[\x{200B}-\x{200F}\x{FEFF}]/u',
        'directional_chars' => '/[\x{202A}-\x{202E}]/u',
        'spaces' => '/\s{2,}/u',
        'object_props' => '/^\x00.+\x00|[^\w\-\.]|\.{2,}/u',
        'malicious_urls' => '/(javascript:|data:|vbscript:)/i',
    ];

    // Límites de longitud por tipo de campo
    private const MAX_LENGTHS = [
        'default' => 2000,
        'html' => 5000,
        'email' => 300,
        'url' => 2000,
        'comment' => 1000,
        'name' => 100
    ];

    /**
     * Obtiene instancia de HTMLPurifier optimizada
     */
    private static function getPurifier(array $customConfig = []): HTMLPurifier
    {
        if (empty($customConfig)) {
            if (self::$purifier === null) {
                $config = HTMLPurifier_Config::createDefault();
                foreach (self::HTML_CONFIG as $key => $value) {
                    $config->set($key, $value);
                }
                self::$purifier = new HTMLPurifier($config);
            }
            return self::$purifier;
        }

        $config = HTMLPurifier_Config::createDefault();
        $finalConfig = array_merge(self::HTML_CONFIG, [
            'HTML.Allowed' => $customConfig['HTML.Allowed'] ?? self::HTML_CONFIG['HTML.Allowed'],
            'CSS.AllowedProperties' => $customConfig['CSS.AllowedProperties'] ?? []
        ]);

        foreach ($finalConfig as $key => $value) {
            $config->set($key, $value);
        }

        return new HTMLPurifier($config);
    }

    /**
     * Sanitiza HTML con validación mejorada
     */
    public static function sanitizarHTML(string $html, array $config = [], string $fieldName = ''): string
    {
        if (!is_string($html) || mb_strlen(trim($html)) === 0) {
            throw new InvalidArgumentException('El contenido HTML no puede estar vacío');
        }

        self::validateLength($html, 'html', $fieldName);

        $purified = self::getPurifier($config)->purify($html);

        if ($purified !== $html && $fieldName) {
            self::logSecurityEvent('xss_attempt', $fieldName, $html);
        }

        return $purified;
    }

    /**
     * Validación de longitud mejorada
     */
    private static function validateLength(string $input, string $fieldType, string $fieldName = ''): void
    {
        $maxLength = self::MAX_LENGTHS[$fieldType] ?? self::MAX_LENGTHS['default'];

        if (mb_strlen($input) > $maxLength) {
            $error = "Campo '$fieldName' excede el máximo de $maxLength caracteres";
            self::logSecurityEvent('length_exceeded', $fieldName, $input);
            throw new InvalidArgumentException($error);
        }
    }

    /**
     * Limpieza profunda con protección mejorada
     */
    private static function cleanString(string $input, string $fieldType = 'default', string $fieldName = ''): string
    {
        self::validateLength($input, $fieldType, $fieldName);

        $trimmed = trim($input);
        if ($trimmed === '') {
            return '';
        }

        $cleaned = preg_replace(
            array_values(self::SECURITY_REGEX),
            [' ', '', '', ' ', '', ''],
            $trimmed
        );

        if (str_contains($cleaned, '://')) {
            $cleaned = preg_replace(self::SECURITY_REGEX['malicious_urls'], '', $cleaned);
            if ($cleaned !== $trimmed) {
                self::logSecurityEvent('malicious_url', $fieldName, $input);
            }
        }

        return $cleaned;
    }

    /**
     * Sanitización de strings con contexto
     */
    private static function sanitizarString(
        string $data,
        bool $allowHtml,
        array $htmlConfig,
        string $fieldType = 'default',
        string $fieldName = ''
    ): string {
        $cleaned = self::cleanString($data, $allowHtml ? 'html' : $fieldType, $fieldName);
        return $allowHtml
            ? self::sanitizarHTML($cleaned, $htmlConfig, $fieldName)
            : htmlspecialchars($cleaned, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8', false);
    }

    /**
     * Sanitización avanzada con contexto
     */
    public static function sanitizarDatos(
        $data,
        bool $allowHtml = false,
        array $htmlConfig = [],
        string $fieldType = 'text',
        string $fieldName = ''
    ) {
        switch ($fieldType) {
            case 'email':
                $data = filter_var($data, FILTER_SANITIZE_EMAIL);
                self::validateLength($data, 'email', $fieldName);
                return $data;
            case 'url':
                $data = filter_var($data, FILTER_SANITIZE_URL);
                self::validateLength($data, 'url', $fieldName);
                return $data;
            case 'int':
                return (int)$data;
            case 'float':
                return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT);
        }

        if ($data === null || is_bool($data)) {
            return $data;
        }

        if (is_array($data)) {
            return array_map(
                function ($item) use ($allowHtml, $htmlConfig, $fieldType, $fieldName) {
                    return self::sanitizarDatos($item, $allowHtml, $htmlConfig, $fieldType, $fieldName);
                },
                $data
            );
        }

        if (is_object($data)) {
            return method_exists($data, '__toString')
                ? self::sanitizarString((string)$data, $allowHtml, $htmlConfig, $fieldType, $fieldName)
                : self::sanitizarObjeto($data, $allowHtml, $fieldName);
        }

        return self::sanitizarString((string)$data, $allowHtml, $htmlConfig, $fieldType, $fieldName);
    }

    /**
     * Conversión segura de objetos
     */
    public static function objetoToArray($object, string $context = ''): array
    {
        if (is_array($object)) return $object;
        if (!is_object($object)) return [];

        $result = [];
        foreach ((array)$object as $key => $value) {
            $cleanKey = preg_replace(self::SECURITY_REGEX['object_props'], '', $key);
            $cleanKey = self::cleanString($cleanKey, 'default', $context);

            if ($cleanKey !== '') {
                $result[$cleanKey] = self::objetoToArray($value, $context);
            }
        }

        return $result;
    }

    /**
     * Sanitización de objetos con contexto
     */
    public static function sanitizarObjeto($object, bool $allowHtml = false, string $context = ''): array
    {
        if (!is_object($object) && !is_array($object)) {
            throw new InvalidArgumentException(sprintf(
                'Se esperaba objeto o array, se recibió: %s',
                gettype($object)
            ));
        }
        return self::sanitizarDatos(self::objetoToArray($object, $context), $allowHtml, [], 'text', $context);
    }

    /**
     * Sistema de logging unificado
     */
    private static function logSecurityEvent(string $eventType, string $field, string $input): void
    {
        $logDir = __DIR__ . '/../Logs';

        // Crear directorio si no existe
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        switch ($eventType) {
            case 'xss_attempt':
                $eventMessage = 'Intento XSS';
                break;
            case 'malicious_url':
                $eventMessage = 'URL maliciosa detectada';
                break;
            case 'length_exceeded':
                $eventMessage = 'Longitud excedida';
                break;
            default:
                $eventMessage = 'Evento de seguridad';
        }


        switch ($eventType) {
            case 'xss_attempt':
                $logFile = 'xss_attempts.log';
                break;
            case 'malicious_url':
                $logFile = 'malicious_urls.log';
                break;
            default:
                $logFile = 'security_events.log';
        }

        $logEntry = sprintf(
            "[%s] %s en campo '%s': %s",
            date('Y-m-d H:i:s'),
            $eventMessage,
            $field,
            substr(json_encode($input), 0, 1000)
        );

        file_put_contents(
            __DIR__ . '/../Logs/' . $logFile,
            $logEntry . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }

    /**
     * Genera headers CSP estrictos
     */
    public static function getCSPHeaders(): string
    {
        return implode('; ', [
            "default-src 'self'", // Bloquea todo por defecto, solo permite mismo origen
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com", // Controla scripts JavaScript
            "style-src 'self' 'unsafe-inline'", // Controla hojas de estilo CSS
            "img-src 'self' data: https://picsum.photos https://fastly.picsum.photos", // Controla imágenes
            "media-src 'self'", // Controla imágenes
            "font-src 'self'", // Controla fuentes web
            "connect-src 'self'", // Para llamadas AJAX/fetch
            "form-action 'self'", // A donde pueden enviar los formularios
            "frame-src https://www.google.com", // Permite incrustar Google
            "frame-ancestors 'none'", // Evita que te iframeen
            "base-uri 'self'", // Controla las URLs base
            "block-all-mixed-content", // Bloquea contenido HTTP en HTTPS
            "upgrade-insecure-requests" // Intenta convertir HTTP a HTTPS
        ]);
    }
}
