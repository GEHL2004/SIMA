<?php

namespace App\Controllers\Mantenimientos;

use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\Reader\{Xlsx, Csv};
use PhpOffice\PhpSpreadsheet\Reader\Exception as PhpSpreadsheetExceptionReader;

class LeerExcelController
{

    /*
        LECTURA DE LA CARGA MASIVA DE LOS MEDICOS
    */

    public static function leerArchivo($file)
    {
        $datos = [];
        try {
            $extensionArchivo = explode('.', $file['name'])[1];
            $xlsx = new Xlsx();
            $csv = new Csv();
            $archivo = '';
            $columas = [
                'A' => 'cedula',
                'B' => 'nombres',
                'C' => 'apellidos',
                'D' => 'rif',
                'E' => 'correo',
                'F' => 'telefono',
                'G' => 'fecha_nacimiento',
                'H' => 'lugar_nacimiento',
                'I' => 'nombre_municipio',
                'J' => 'nombre_parroquia',
                'K' => 'direccion',
                'L' => 'nacionalidad',
                'M' => 'tipo_sangre',
                'N' => 'numero_colegio',
                'O' => 'fecha_incripcion',
                'P' => 'impre',
                'Q' => 'universidad_graduado',
                'R' => 'fecha_egreso_universidad',
                'S' => 'matricula_ministerio',
                'T' => 'nombre_grado_academico',
                'U' => 'lugar_de_trabajo',
                'V' => 'estado'
            ];
            $CoincidenciasEncontradas = self::validacionDeArchivoDeRequerimientoDeNomina($file, $extensionArchivo);
            if (hash_equals($extensionArchivo, 'xlsx')) {
                if ($CoincidenciasEncontradas == 22) {
                    $excel = $xlsx->load($file['tmp_name']);
                    $archivo = $excel->getActiveSheet();
                    $maxDataRow = $archivo->getHighestRow();
                    foreach ($archivo->getRowIterator(11, $maxDataRow) as $i => $fila) {
                        $celdaIterator = $fila->getCellIterator();
                        $celdaIterator->setIterateOnlyExistingCells(false);
                        $datosTemporales = [];
                        foreach ($celdaIterator as $j => $celda) {
                            if (!array_key_exists($j, $columas)) {
                                continue;
                            }
                            $valor = trim($celda->getValue());
                            if ($j == 'G' || $j == 'O' || $j == 'R') {
                                $fecha_objeto = DateTime::createFromFormat('d/m/Y', $valor);
                                $datosTemporales[$columas[$j]] = $fecha_objeto->format('Y-m-d');
                            } else{
                                $datosTemporales[$columas[$j]] = empty($valor) ? null : $valor;
                            }
                        }
                        if (!empty($datosTemporales)) {
                            $datos[] = $datosTemporales;
                        }
                    }
                } else {
                    return ['bool' => false, 'coincidencias' => $CoincidenciasEncontradas];
                }
            } else {
                return ['bool' => false];
            }
            return $datos;
        } catch (PhpSpreadsheetExceptionReader $e) {
            echo "Error al leer el archivo: " . $e->getMessage();
            return ['bool' => false, 'mensaje' => "Error al leer el archivo: " . $e->getMessage()];
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return ['bool' => false, "Error: " . 'mensaje' => $e->getMessage()];
        }
    }


    private static function validacionDeArchivoDeRequerimientoDeNomina($file, $extensionArchivo)
    {
        try {
            $xlsx = new Xlsx();
            $csv = new Csv();
            $archivo = '';
            $Columnas_A_Verificar = ['Cédula', 'Nombres', 'Apellidos', 'RIF', 'Correo', 'Teléfono', 'Fecha de nacimiento', 'Lugar de nacimiento', 'Municipio', 'Parroquia', 'Dirección', 'Nacionalidad', 'Tipo de Sangre', 'Núm. Colegio', 'Fecha de inscripción', 'Núm. IMPRES', 'Universidad donde se graduó', 'Fecha de egreso', 'Matricula del ministerio', 'Grado académico', 'Lugar de trabajo', 'Estado'];
            $cantidadCoincidencias = 0;
            if (hash_equals($extensionArchivo, 'xlsx')) {
                $excel = $xlsx->load($file['tmp_name']);
                $archivo = $excel->getActiveSheet();
                $maxDataRow = $archivo->getHighestRow();
                foreach ($archivo->getRowIterator(1, $maxDataRow) as $i => $fila) {
                    $celdaIterator = $fila->getCellIterator();
                    $celdaIterator->setIterateOnlyExistingCells(false);
                    foreach ($celdaIterator as $j => $celda) {
                        $valor = trim($celda->getValue());
                        if (!empty($valor)) {
                            if (in_array($valor, $Columnas_A_Verificar)) {
                                $cantidadCoincidencias++;
                            }
                        }
                    }
                }
            } else {
                return ['bool' => false];
            }
            return $cantidadCoincidencias;
        } catch (PhpSpreadsheetExceptionReader $e) {
            echo "Error al leer el archivo: " . $e->getMessage();
            return ['bool' => false, 'mensaje' => "Error al leer el archivo: " . $e->getMessage()];
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return ['bool' => false, "Error: " . 'mensaje' => $e->getMessage()];
        }
    }
}
