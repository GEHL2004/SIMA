<?php

namespace App\Controllers;

use Dompdf\Dompdf;

class ReportesPDFController{
    
    private $html;
    private $pdf;
    private $fecha;
    private $hora;

    public function __construct(){
        $this->pdf = new Dompdf();
        date_default_timezone_set('America/Caracas');
        $this->fecha = date("d-m-Y");
        $this->hora = date("h:i:s A");
    }

    public function cargarHTML(string $ruta, array $datos){
        ob_start();
        require_once($ruta);
        $this->html = ob_get_clean();
    }

    public function generarPDF(string $ruta, string $tamañoHoja, string $orientacion, string $nombre, array $datos){
        self::cargarHTML($ruta, $datos);
        // print_r($datos);
        // echo $this->html;
        // die();
        $op = $this->pdf->getOptions();
        $op->set(array('isRemoteEnabled' => true));
        $this->pdf->setOptions($op);
        $this->pdf->loadHtml($this->html);
        $this->pdf->setPaper($tamañoHoja, $orientacion);
        $this->pdf->render();
        $this->pdf->stream($nombre."_".$this->fecha."_(".$this->hora.").pdf", array("Attachment" => true));
    }
}

?>