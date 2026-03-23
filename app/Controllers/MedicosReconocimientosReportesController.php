<?php

namespace App\Controllers;

use App\Models\MedicosReconocimientos;

class MedicosReconocimientosReportesController
{

    private $medicos_reconocimientos_model;
    private $pdf;
    private $RUTA_BASE;

    public function __construct()
    {
        $this->medicos_reconocimientos_model = new MedicosReconocimientos();
        $this->pdf = new ReportesPDFController();
        $this->RUTA_BASE = "public/views/reportes/";
    }

    public function vista_reporte(array $request)
    {
        switch ($request['tipo_reconocimiento']) {
            case 1:
                $request['intervalo'] = 30;
                break;

            case 2:
                $request['intervalo'] = 40;
                break;

            case 3:
                $request['intervalo'] = 50;
                break;

            case 4:
                $request['intervalo'] = 60;
                break;
        }
        $data = $this->medicos_reconocimientos_model->listadoReconocimientos($request['tipo_reconocimiento'], $request['intervalo'], $request['cantidad'], $request['estado']);
        // echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        // die();
        if (!empty($data)) {
            $j = 1;
            foreach ($data as $i => $valor) {
                $data[$i]['contador'] = $j++;
            }
            require_once 'public/views/medicos_reconocimientos/reporte.php';
        } else {
            AlertasController::warning("Sin datos", "No existen resultados de médicos bajo ese filtrado, rectifique y vuelva a intentarlo.");
            header("Location: " . $_ENV['BASE_PATH'] . "/medicos-reconocimientos");
        }

        die();
    }

    public function generar_reporte_pdf(string $cadena)
    {
        $data_separada = explode('_', $cadena);
        $tipo_reconocimiento = $data_separada[0];
        $intervalo = $data_separada[1];
        $cantidad = $data_separada[2];
        $estado = $data_separada[3];
        $data = $this->medicos_reconocimientos_model->listadoReconocimientos($tipo_reconocimiento, $intervalo, $cantidad, $estado);
        $j = 1;
        foreach ($data as $i => $valor) {
            $data[$i]['contador'] = $j++;
        }
        $datos = [
            'data' => $data,
            'request' => [
                'tipo_reconocimiento' => $tipo_reconocimiento,
                'intervalo' => $intervalo,
                'cantidad' => $cantidad,
                'estado' => $estado,
            ]
        ];
        $nombre_reporte = "LISTADO_DE_MEDICOS_DE_" . $intervalo . " AÑOS";
        $ruta_reporte = $this->RUTA_BASE . "LISTADO DE RECONOCIMIENTOS MEDICOS.php";
        $this->pdf->generarPDF($ruta_reporte, 'A4', 'portrait', $nombre_reporte, $datos);
    }
}
