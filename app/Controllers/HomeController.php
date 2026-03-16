<?php

namespace App\Controllers;

use App\Models\Especialidades;
use App\Models\Mantenimientos\Auditoria;
use App\Models\Medicos;
use App\Models\MedicosDetalles;
use App\Models\SubEspecialidades;

class HomeController
{



    public function __construct() {}

    public function index()
    {
        $dashboardData = self::obtenerDatos();
        // self::imprimirDatosRecibidos(['dashboardData' => $dashboardData], true);
        $dashboardDataJSON = json_encode(json_encode($dashboardData));
        require_once "public/views/home.php";
    }

    public function dataActualizada(){
        $data = self::obtenerDatos();
        header('Content-Type: application/json');
        $dataJ = json_encode($data);
        echo $dataJ;
    }

    public static function obtenerDatos(): array
    {
        // objetos a instanciar

        $medicos = null;
        $medicosDetalles = null;
        $especialidades = null;
        $subespecialidades = null;
        $auditoria = null;

        //----------------------------//

        $medicos = new Medicos();

        $totalMedicos = count($medicos->index());
        $medicosPorMunicipiosGrafico = $medicos->medicosPorMunicipios()[0];
        $medicosPorEspecialidad = $medicos->especialidadesMasUsadas();

        unset($medicos);

        $medicosDetalles = new MedicosDetalles();

        $medicosPorEstado = $medicosDetalles->cantMedicosEstados()[0];

        unset($medicosDetalles);

        $especialidades = new Especialidades();

        $totalEspecialidades = count($especialidades->getAllEspecialidades());

        unset($especialidades);

        $subespecialidades = new SubEspecialidades();

        $totalSubespecialidades = count($subespecialidades->getAllSubespecialidades());

        unset($subespecialidades);

        $auditoria = new Auditoria();

        $actividadesRecientes = $auditoria->obtenerActividadesRecientes();

        foreach ($actividadesRecientes as $i => $row) {
            $tiempo = '';
            $minutos = (int)$row['minutos_antes'];

            if ($minutos < 60) {
                $tiempo = "Hace $minutos minutos";
            } elseif ($minutos < 1440) {
                $horas = floor($minutos / 60);
                $tiempo = "Hace $horas hora" . ($horas > 1 ? 's' : '');
            } else {
                $dias = floor($minutos / 1440);
                $tiempo = "Hace $dias día" . ($dias > 1 ? 's' : '');
            }

            $actividadesRecientes[$i]['tiempo'] = $tiempo;
        }

        unset($auditoria);

        $data = [
            'totalMedicos' => $totalMedicos,
            'totalEspecialidades' => $totalEspecialidades,
            'totalSubespecialidades' => $totalSubespecialidades,
            'medicosPorEstado' => $medicosPorEstado,
            'medicosPorEstadoGrafico' => [
                ['estado' => 'Atanasio Girardot', 'cantidad' => $medicosPorMunicipiosGrafico['Atanasio_Girardot']],
                ['estado' => 'Bolivar', 'cantidad' => $medicosPorMunicipiosGrafico['Bolivar']],
                ['estado' => 'Camatagua', 'cantidad' => $medicosPorMunicipiosGrafico['Camatagua']],
                ['estado' => 'Francisco Linares Alcentara', 'cantidad' => $medicosPorMunicipiosGrafico['Francisco_Linares_Alcentara']],
                ['estado' => 'Jose Angel Lamas', 'cantidad' => $medicosPorMunicipiosGrafico['Jose_Angel_Lamas']],
                ['estado' => 'Jose Felix Ribas', 'cantidad' => $medicosPorMunicipiosGrafico['Jose_Felix_Ribas']],
                ['estado' => 'Jose Rafael Revenga', 'cantidad' => $medicosPorMunicipiosGrafico['Jose_Rafael_Revenga']],
                ['estado' => 'Libertador', 'cantidad' => $medicosPorMunicipiosGrafico['Libertador']],
                ['estado' => 'Mario Briceno Iragorry', 'cantidad' => $medicosPorMunicipiosGrafico['Mario_Briceno_Iragorry']],
                ['estado' => 'Ocumare de la Costa de Oro', 'cantidad' => $medicosPorMunicipiosGrafico['Ocumare_de_la_Costa_de_Oro']],
                ['estado' => 'San Casimiro', 'cantidad' => $medicosPorMunicipiosGrafico['San_Casimiro']],
                ['estado' => 'San Sebastien', 'cantidad' => $medicosPorMunicipiosGrafico['San_Sebastien']],
                ['estado' => 'Santiago Mariño', 'cantidad' => $medicosPorMunicipiosGrafico['Santiago_Mariño']],
                ['estado' => 'Santos Michelena', 'cantidad' => $medicosPorMunicipiosGrafico['Santos_Michelena']],
                ['estado' => 'Sucre', 'cantidad' => $medicosPorMunicipiosGrafico['Sucre']],
                ['estado' => 'Tovar', 'cantidad' => $medicosPorMunicipiosGrafico['Tovar']],
                ['estado' => 'Urdaneta', 'cantidad' => $medicosPorMunicipiosGrafico['Urdaneta']],
                ['estado' => 'Zamora', 'cantidad' => $medicosPorMunicipiosGrafico['Zamora']]
            ],
            'medicosPorEspecialidad' => $medicosPorEspecialidad,
            'actividadesRecientes' => $actividadesRecientes
        ];

        return $data;
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
}
