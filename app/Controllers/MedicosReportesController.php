<?php

namespace App\Controllers;

use App\Models\MedicosReportes;
use App\Controllers\ReportesPDFController;

class MedicosReportesController
{

    private $medicos;
    private $medicosReportes;
    private $reportesPDFController;
    private $RUTA_BASE;

    public function __construct()
    {
        $this->medicos = new MedicosController();
        $this->medicosReportes = new MedicosReportes();
        $this->reportesPDFController = new ReportesPDFController();
        $this->RUTA_BASE = "public/views/reportes/";
    }


    public function generarReporteMedicoIndividual(int $id_medico): void
    {
        $data = $this->medicos->getdatashow($id_medico);
        // self::imprimirDatosRecibidos(['$data' => $data], true);
        $nombre_reporte = "REPORTE_MEDICO_INDIVIDUAL_" . $data['medico']['nombres'] . '_' . $data['medico']['apellidos'] . '_' . $data['medico']['cedula'];
        $ruta_reporte = $this->RUTA_BASE . "REPORTE_MEDICO_INDIVIDUAL.php";
        $this->reportesPDFController->generarPDF($ruta_reporte, 'A4', 'portrait', $nombre_reporte, $data);
    }

    public function vistaReporteMedicoPorMunicipios(int $id_municipio)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorMunicipios($id_municipio);
        $data = self::procesarNombresApellidos($data);
        header('Content-Type: application/json');
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        echo $dataJ;
    }
    public function generarReporteMedicoPorMunicipios(int $id_municipio)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorMunicipios($id_municipio);
        $data = self::procesarNombresApellidos($data);
        // self::imprimirDatosRecibidos(['$data' => $data], true);
        $nombre_reporte = "REPORTE_MEDICOS_MUNICPIOS";
        $ruta_reporte = $this->RUTA_BASE . "REPORTE_MEDICOS_MUNICPIOS.php";
        $this->reportesPDFController->generarPDF($ruta_reporte, 'A4', 'landscape', $nombre_reporte, $data);
    }

    public function vistaReporteMedicoPorParroquias(int $id_parroquia)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorParroquias($id_parroquia);
        $data = self::procesarNombresApellidos($data);
        header('Content-Type: application/json');
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        echo $dataJ;
        // self::imprimirDatosRecibidos(['$data' => $data], true);
    }
    public function generarReporteMedicoPorParroquias(int $id_parroquia)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorParroquias($id_parroquia);
        $data = self::procesarNombresApellidos($data);
        // self::imprimirDatosRecibidos(['$data' => $data], true);
        $nombre_reporte = "REPORTE_MEDICOS_PARROQUIAS";
        $ruta_reporte = $this->RUTA_BASE . "REPORTE_MEDICOS_PARROQUIAS.php";
        $this->reportesPDFController->generarPDF($ruta_reporte, 'A4', 'portrait', $nombre_reporte, $data);
    }

    public function vistaReporteMedicoPorEspecialidad(int $id_especialidad)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorEspecialidad($id_especialidad);
        $data = self::procesarNombresApellidos($data);
        header('Content-Type: application/json');
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        echo $dataJ;
        // self::imprimirDatosRecibidos(['$data' => $data], true);
    }
    public function generarReporteMedicoPorEspecialidad(int $id_especialidad)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorEspecialidad($id_especialidad);
        $data = self::procesarNombresApellidos($data);
        // self::imprimirDatosRecibidos(['$data' => $data], true);
        $nombre_reporte = "REPORTE_MEDICOS_ESPECIALIDAD";
        $ruta_reporte = $this->RUTA_BASE . "REPORTE_MEDICOS_ESPECIALIDAD.php";
        $this->reportesPDFController->generarPDF($ruta_reporte, 'A4', 'portrait', $nombre_reporte, $data);
    }

    public function vistaReporteMedicoPorSubespecialidad(int $id_subespecialidad)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorSubespecialidad($id_subespecialidad);
        $data = self::procesarNombresApellidos($data);
        header('Content-Type: application/json');
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        echo $dataJ;
        // self::imprimirDatosRecibidos(['$data' => $data], true);
    }

    public function generarReporteMedicoPorSubespecialidad(int $id_subespecialidad)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorSubespecialidad($id_subespecialidad);
        $data = self::procesarNombresApellidos($data);
        // self::imprimirDatosRecibidos(['$data' => $data], true);
        $nombre_reporte = "REPORTE_MEDICOS_SUBESPECIALIDAD";
        $ruta_reporte = $this->RUTA_BASE . "REPORTE_MEDICOS_SUBESPECIALIDAD.php";
        $this->reportesPDFController->generarPDF($ruta_reporte, 'A4', 'portrait', $nombre_reporte, $data);
    }

    public function vistaReporteMedicoPorGradoAcademico(int $id_grado_academico)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorGradoAcademico($id_grado_academico);
        $data = self::procesarNombresApellidos($data);
        header('Content-Type: application/json');
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        echo $dataJ;
        // self::imprimirDatosRecibidos(['$data' => $data], true);
    }

    public function generarReporteMedicoPorGradoAcademico(int $id_grado_academico)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorGradoAcademico($id_grado_academico);
        $data = self::procesarNombresApellidos($data);
        // self::imprimirDatosRecibidos(['$data' => $data], true);
        $nombre_reporte = "REPORTE_MEDICOS_GRADO_ACADEMICO";
        $ruta_reporte = $this->RUTA_BASE . "REPORTE_MEDICOS_GRADO_ACADEMICO.php";
        $this->reportesPDFController->generarPDF($ruta_reporte, 'A4', 'portrait', $nombre_reporte, $data);
    }

    public function vistaReporteMedicoPorEstado(int $estado)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorEstado($estado);
        $data = self::procesarNombresApellidos($data);
        header('Content-Type: application/json');
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        echo $dataJ;
        // self::imprimirDatosRecibidos(['$data' => $data], true);
    }

    public function generarReporteMedicoPorEstado(int $estado)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorEstado($estado);
        $data = self::procesarNombresApellidos($data);
        // self::imprimirDatosRecibidos(['$data' => $data], true);
        $nombre_reporte = "REPORTE_MEDICOS_ESTADO";
        $ruta_reporte = $this->RUTA_BASE . "REPORTE_MEDICOS_ESTADO.php";
        $this->reportesPDFController->generarPDF($ruta_reporte, 'A4', 'portrait', $nombre_reporte, $data);
    }

    public function vistaReporteMedicoPorDeporte(int $id_deporte)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorDeporte($id_deporte);
        $data = self::procesarNombresApellidos($data);
        header('Content-Type: application/json');
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        echo $dataJ;
        // self::imprimirDatosRecibidos(['$data' => $data], true);
    }

    public function generarReporteMedicoPorDeporte(int $id_deporte)
    {
        $data = $this->medicosReportes->obtenerDatosMedicoPorDeporte($id_deporte);
        $data = self::procesarNombresApellidos($data);
        // self::imprimirDatosRecibidos(['$data' => $data], true);
        $nombre_reporte = "REPORTE_MEDICOS_DEPORTE";
        $ruta_reporte = $this->RUTA_BASE . "REPORTE_MEDICOS_DEPORTE.php";
        $this->reportesPDFController->generarPDF($ruta_reporte, 'A4', 'portrait', $nombre_reporte, $data);
    }

    public static function imprimirDatosRecibidos(array $datos, bool $imprimirIndice = false): void
    {
        foreach ($datos as $indice => $valor) {
            if ($imprimirIndice) {
                echo "----- " . $indice . " -----<br>";
            }
            echo "<pre>";
            print_r($valor);
            echo "</pre>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
        }
        die();
    }

    private static function procesarNombresApellidos(array $data = []): array
    {
        $i = 1;
        foreach ($data as $j => $valor) {
            $data[$j]['nombres'] = str_replace('_', ' ', $valor['nombres']);
            $data[$j]['apellidos'] = str_replace('_', ' ', $valor['apellidos']);
            $data[$j]['nombres_allidos'] = str_replace('_', ' ', $valor['nombres'] . ' ' . $valor['apellidos']);
            $data[$j]['contador'] = $i;
            $i++;
        }
        return $data;
    }
}
