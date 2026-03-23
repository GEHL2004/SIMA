<?php

namespace App\Controllers;

use App\Controllers\Mantenimientos\{AuditoriaController, LeerExcelController, MunicipiosController, UsuariosController};
use App\Models\Mantenimientos\Grados;
use App\Models\{BaseModels, Deportes, Medicos, MedicosCursos, MedicosDeportes, MedicosDetalles, MedicosDiplomados, MedicosDocumentos, MedicosEspecialidades, MedicosSubespecialidades};
use App\Models\Mantenimientos\Usuarios;
use App\Config\PermisosHelper;

class MedicosController
{

    private $medicos;
    private $medicos_detalles;
    private $grados;
    private $especialidades;
    private $subespecialidades;
    private $deportes;
    private $medicos_especialidades;
    private $medicos_reconocimientos;
    private $medicos_subespecialidades;
    private $medicos_documentos;
    private $medicos_cursos;
    private $medicos_diplomados;
    private $medicos_deportes;
    private $cambios_estados;
    private $municipios;
    private $imagenes;
    private $documentos;
    private $audi;
    private $baseModel;
    private $usuarios;
    const camposEsperados = [
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'tipo_sangre',
        'telefono_inicio',
        'telefono_restante',
        'id_municipio',
        'id_parroquia',
        'direccion',
        'numero_colegio',
        'matricula_ministerio',
        'universidad_graduado',
        'fecha_egreso_universidad',
        'fecha_incripcion',
        'id_grado_academico',
        'lugar_de_trabajo',
        'estado'
    ];

    public function __construct()
    {
        $this->medicos = new Medicos();
        $this->medicos_detalles = new MedicosDetalles();
        $this->grados = new Grados();
        $this->especialidades = new EspecialidadesController();
        $this->subespecialidades = new SubEspecialidadesController();
        $this->medicos_especialidades = new MedicosEspecialidades();
        $this->medicos_reconocimientos = new MedicosReconocimientosController();
        $this->medicos_subespecialidades = new MedicosSubespecialidades();
        $this->medicos_documentos = new MedicosDocumentos();
        $this->medicos_cursos = new MedicosCursos();
        $this->medicos_diplomados = new MedicosDiplomados();
        $this->medicos_deportes = new MedicosDeportes();
        $this->cambios_estados = new CambiosEstadosController();
        $this->municipios = new MunicipiosController();
        $this->imagenes = new ImagesController();
        $this->documentos = new DocumentosController();
        $this->audi = new AuditoriaController();
        $this->baseModel = new BaseModels();
        $this->deportes = new Deportes();
        $this->usuarios = new Usuarios();
    }

    public function index()
    {
        // Verificar permiso de ver
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_MEDICOS, PermisosHelper::VER)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_MEDICOS, PermisosHelper::VER);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $data = $this->medicos->index();
        foreach ($data as $i => $valor) {
            $data[$i]['nombres_apellidos'] = str_replace('_', ' ', $valor['nombres_apellidos']);
        }
        $dataJ = json_encode($data);
        $dataJ = json_encode($dataJ);
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        require_once "public/views/medicos/index.php";
    }

    public function create()
    {
        // Verificar permiso de registrar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_MEDICOS, PermisosHelper::REGISTRAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_MEDICOS, PermisosHelper::REGISTRAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $grados = $this->grados->getAllGrados();
        $especialidades = $this->especialidades->getAllEspecialidades();
        $subespecialidades = $this->subespecialidades->getAllSubespecialidades();
        $especialiades_subespecialdiades = [];
        // Procesar especialidades
        foreach ($especialidades as $especialidad) {
            $especialiades_subespecialdiades[] = [
                'id' => 'esp_' . $especialidad['id_especialidad'],
                'nombre' => $especialidad['nombre'],
                'tipo' => 'especialidad',
                'id_original' => $especialidad['id_especialidad']
            ];
        }

        // Procesar subespecialidades
        foreach ($subespecialidades as $subespecialidad) {
            $especialiades_subespecialdiades[] = [
                'id' => 'sub_' . $subespecialidad['id_subespecialidad'],
                'nombre' => $subespecialidad['nombre'],
                'tipo' => 'subespecialidad',
                'id_original' => $subespecialidad['id_subespecialidad']
            ];
        }
        // echo "<pre>";
        // print_r($especialiades_subespecialdiades);
        // echo "</pre>";
        $municipios = $this->municipios->getMunicipios();
        $deportes = $this->deportes->getAllDeportes();
        $especialiades_subespecialdiadesJ = json_encode($especialiades_subespecialdiades);
        $especialiades_subespecialdiadesJ = json_encode($especialiades_subespecialdiadesJ);
        require_once "public/views/medicos/create.php";
    }

    public function store(array $request, array $files)
    {
        // Verificar permiso de registrar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_MEDICOS, PermisosHelper::REGISTRAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_MEDICOS, PermisosHelper::REGISTRAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $request = self::validarNullosPermitidos($request);
        if (!self::validarExistenciaCampos($request)) {
            AlertasController::error('Campos incompletos', 'Por favor complete todos los campos requeridos del formulario');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        // Procesamiento de datos basicos
        foreach (self::camposEsperados as $i => $valor) {
            $request[$valor] = trim($request[$valor]);
        }
        $request['nombres'] = strtoupper(str_replace(' ', '_', $request['nombres']));
        $request['apellidos'] = strtoupper(str_replace(' ', '_', $request['apellidos']));
        $validateDateDuplicate = $this->baseModel->validateData('medicos', 'cedula', $request['cedula']);
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'La cédula ingresada ya se encuentra registrada en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        $validateDateDuplicate = $this->baseModel->validateData('medicos', 'rif', $request['rif']);
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'El RIF ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        $validateDateDuplicate = $this->baseModel->validateData('medicos', 'impre', $request['impre']);
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'El número de IMPRE ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        $validateDateDuplicate = $this->baseModel->validateData('medicos', 'correo', $request['correo']);
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'El correo electrónico ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        $validateDateDuplicate = $this->baseModel->validateData('medicos', 'numero_colegio', $request['numero_colegio']);
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'El numero de colegio de medicos del Estado Aragua ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        $validateDateDuplicate = $this->baseModel->validateData('medicos_detalles', 'matricula_ministerio', $request['matricula_ministerio']);
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'La matricula del ministerio ingresada ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        if (self::validarExistenciaCampo($request, 'cursos')) {
            if (!self::validarArrays($request['cursos'], false)) {
                AlertasController::error('Data Incompleta', 'Los cursos no fueron recibidos');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
        }
        if (self::validarExistenciaCampo($request, 'diplomados')) {
            if (!self::validarArrays($request['diplomados'], false)) {
                AlertasController::error('Data Incompleta', 'Los diplomados no fueron recibidos');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
        }
        if (self::validarExistenciaCampo($request, 'deportes')) {
            if (!self::validarArrays($request['deportes'], false)) {
                AlertasController::error('Data Incompleta', 'Los deportes no fueron recibidos');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
        }
        if (self::validarExistenciaCampo($request, 'especialidades')) {
            if (!self::validarArrays($request['especialidades'], false)) {
                AlertasController::error('Data Incompleta', 'Las especialidades no fueron recibidas');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            // Procesar especialidades
            $request['especialidades'] = self::procesarEspecialidades($request['especialidades']);
        }
        if (self::validarExistenciaCampo($files, 'nombre_foto')) {
            if (!self::validarArrays($files['nombre_foto'], false)) {
                AlertasController::error('Data Incompleta', 'La foto del medico no fue recibida');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            $validacionFoto = self::validarFoto($files['nombre_foto'] ?? []);
            if (!$validacionFoto['valido']) {
                AlertasController::error('Error en la foto', implode($validacionFoto['errores']));
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            // Procesar foto del medico
            $nombreDeLaFoto = $request['nombres'] . '_' . $request['apellidos'] . '_' . $request['cedula'];
            $request['nombre_foto'] = $this->imagenes->guardarImagen($files['nombre_foto'], 'medicos', $nombreDeLaFoto)['archivo'];
        } else {
            $request['nombre_foto'] = null;
        }
        if (self::validarExistenciaCampo($files, 'documentos')) {
            if (!self::validarArrays($files['documentos'], false)) {
                AlertasController::error('Data Incompleta', 'Los documentos individuales no fueron recibidos');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            $validacionDocs = self::validarDocumentosMultiples(
                $files['documentos'] ?? [],
                [
                    'campo_nombre' => 'documentos',
                    'requerido' => false,
                    'max_archivos' => 10,
                    'tamano_maximo' => 5 * 1024 * 1024 // 5MB
                ]
            );
            if (!$validacionDocs['valido']) {
                AlertasController::error('Error en documentos', implode('<br>', $validacionDocs['errores']));
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            $nombreSubDirectorio = $request['nombres'] . '_' . $request['apellidos'] . '_' . $request['cedula'];
            $resultadoSubidaDocs = $this->documentos->guardarMultiplesDocumentos($files['documentos'], 'documentos_medicos', $nombreSubDirectorio);
            foreach ($resultadoSubidaDocs['resultados'] as $i => $valor) {
                $request['nombresDocumentos'][] = ['nombre_en_directorio' => $valor['archivo'], 'nombre_original' => $valor['nombre_original']];
            }
        }
        if (self::validarExistenciaCampo($files, 'documentos_multiples')) {
            if (!self::validarArrays($files['documentos_multiples'], false)) {
                AlertasController::error('Data Incompleta', 'Los documentos individuales no fueron recibidos');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            $validacionDocsMultiples = self::validarDocumentosMultiples(
                $files['documentos_multiples'] ?? [],
                [
                    'campo_nombre' => 'documentos_multiples',
                    'requerido' => false,
                    'max_archivos' => 20,
                    'tamano_maximo' => 5 * 1024 * 1024 // 5MB
                ]
            );
            if (!$validacionDocsMultiples['valido']) {
                AlertasController::error('Error en documentos múltiples', implode('<br>', $validacionDocsMultiples['errores']));
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            // Procesar documentos multples
            $nombreSubDirectorio = $request['nombres'] . '_' . $request['apellidos'] . '_' . $request['cedula'];
            $resultadoSubidaDocs = $this->documentos->guardarMultiplesDocumentos($files['documentos_multiples'], 'documentos_medicos', $nombreSubDirectorio);
            foreach ($resultadoSubidaDocs['resultados'] as $i => $valor) {
                $request['nombresDocumentos'][] = ['nombre_en_directorio' => $valor['archivo'], 'nombre_original' =>
                $valor['nombre_original']];
            }
        }
        $resultMedicos = $this->medicos->store($request);
        $request['id_medico'] = $resultMedicos['id_medico'];
        $resultMedicosDetalles = $this->medicos_detalles->store($request);

        if (self::validarExistenciaCampo($request, 'cursos')) {
            foreach ($request['cursos'] as $i => $valor) {
                $valor['id_medico'] = $request['id_medico'];
                $this->medicos_cursos->store($valor);
            }
        }
        if (self::validarExistenciaCampo($request, 'diplomados')) {
            foreach ($request['diplomados'] as $i => $valor) {
                $valor['id_medico'] = $request['id_medico'];
                $this->medicos_diplomados->store($valor);
            }
        }
        if (self::validarExistenciaCampo($request, 'deportes')) {
            foreach ($request['deportes'] as $i => $valor) {
                $this->medicos_deportes->store($valor, $request['id_medico']);
            }
        }
        if (self::validarExistenciaCampo($request, 'especialidades')) {
            foreach ($request['especialidades'] as $i => $valor) {
                $valor['id_medico'] = $request['id_medico'];
                if (!is_null($valor['id_especialidad'])) {
                    $this->medicos_especialidades->store($valor);
                } else {
                    $this->medicos_subespecialidades->store($valor);
                }
            }
        }
        if (self::validarExistenciaCampo($files, 'documentos') || self::validarExistenciaCampo($files, 'documentos_multiples')) {
            foreach ($request['nombresDocumentos'] as $i => $valor) {
                $valor['id_medico'] = $request['id_medico'];
                $resultado = $this->medicos_documentos->store($valor);
            }
        }
        $this->cambios_estados->store($request['id_medico'], $request['estado']);
        $this->medicos_reconocimientos->store($request['id_medico']); 
        if ($resultMedicos['error'] == 0 && $resultMedicosDetalles['error'] == 0) {
            $nombreMedico = str_replace('_', ' ', $request['nombres'] . $request['apellidos']);
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' registro al médico ' . $nombreMedico . '.']);
            AlertasController::success('Registro exitoso.');
        }
        if ($resultMedicos['error'] == 1) {
            AlertasController::error('Dato duplicado', 'La cédula ingresada ya se encuentra registrada en el sistema, verifique y vuelvalo a intentar.');
        }
        if ($resultMedicos['error'] == 2) {
            AlertasController::error('Dato duplicado', 'El correo electrónico ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        }
        if ($resultMedicos['error'] == 3) {
            AlertasController::error('Dato duplicado', 'El numero de colegio de medicos del Estado Aragua ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        }
        if ($resultMedicos['error'] == 4) {
            AlertasController::error('Dato duplicado', 'La matricula del ministerio ingresada ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        }
        header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
        die();
    }































    public function vista_carga_masiva()
    {
        require_once "public/views/medicos/carga_masiva.php";
    }

    public function carga_masiva(array $file)
    {
        if (!isset($file['file'])) {
            AlertasController::error('Archivo no recibido', 'No se recibió ningún archivo, por favor seleccione un archivo y vuelva a intentarlo.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos-carga-masiva');
            die();
        }
        $datos = LeerExcelController::leerArchivo($file['file']);
        // echo "<pre>";
        // print_r($datos);
        // echo "</pre>";
        // die();
        if (($datos['bool'] ?? true) === false) {
            if (count($datos) == 1) {
                AlertasController::error('Formato inválido', 'El archivo excel no corresponde al formato requerido, por favor verifique el formato del archivo y vuelva a intentarlo.');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos-carga-masiva');
                die();
            } else if (count($datos) >= 2 && array_key_exists('coincidencias', $datos)) {
                AlertasController::error('Archivo incorrecto', 'El archivo excel esta incompleto o no falta información requerida, por favor verifique el archivo y vuelva a intentarlo.');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos-carga-masiva');
                die();
            } else {
                AlertasController::error('Error desconocido', 'Ocurrió un error desconocido al procesar el archivo, por favor verifique el archivo y vuelva a intentarlo.');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos-carga-masiva');
                die();
            }
        }
        $contador_registros_incompletos = 0;
        //Completar y procesar los campos de los medicos
        foreach ($datos as $i => $valor) {
            $datos[$i]['id_municipio'] = $this->municipios->searchMunicipio($valor['nombre_municipio'])[0]['id_municipio'];
            $datos[$i]['id_parroquia'] = $this->municipios->searchParroquias($valor['nombre_parroquia'])[0]['id_parroquia'];
            $datos[$i]['id_grado_academico'] = $this->grados->searchGrados($valor['nombre_grado_academico'])[0]['id_grado_academico'];
            $datos[$i]['telefono_inicio'] = explode('-', $valor['telefono'])[0];
            $datos[$i]['telefono_restante'] = explode('-', $valor['telefono'])[1];
            $datos[$i]['nombre_foto'] = '';
            $datos[$i]['nombres'] = strtoupper(str_replace(' ', '_', $valor['nombres']));
            $datos[$i]['apellidos'] = strtoupper(str_replace(' ', '_', $valor['apellidos']));
            switch ($valor['estado']) {
                case 'Activo':
                    $datos[$i]['estado'] = 1;
                    break;
                case 'Desincorporado':
                    $datos[$i]['estado'] = 2;
                    break;
                case 'Jubilado':
                    $datos[$i]['estado'] = 3;
                    break;
                case 'Fallecido':
                    $datos[$i]['estado'] = 4;
                    break;
                case 'Traslado':
                    $datos[$i]['estado'] = 5;
                    break;
                default:
                    $datos[$i]['estado'] = 1;
            }
            $datos[$i] = self::validarNullosPermitidos($datos[0]);
            $contador_registros_incompletos += !self::validarExistenciaCampos($datos[$i]) ? 1 : 0;
            if ($contador_registros_incompletos > 0) {
                AlertasController::error('Datos incompletos', 'No se recibieron todos los campos requeridos para uno o varios médicos, por favor verifique el archivo y vuelva a intentarlo.');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos-carga-masiva');
                die();
            }
        }
        // echo "<pre>";
        // print_r($datos);
        // echo "</pre>";
        // die();
        $medicos_registrados = 0;
        $medicos_no_registrados = 0;
        $resultados = [];
        //Registro y validaciones para cada medico
        $resultados['registrados'] = [];
        $resultados['no_registrados'] = [];
        foreach ($datos as $i => $valor) {
            $validateDateDuplicate = $this->baseModel->validateData('medicos', 'cedula', $valor['cedula']);
            if (!empty($validateDateDuplicate)) {
                $resultados['no_registrados'][] = ['resultado' => 'duplicado', 'codigo_validacion' => 1, 'mensaje' => 'La cédula ingresada ya se encuentra registrada en el sistema, verifique y vuelvalo a intentar.', 'data' => $valor];
                $medicos_no_registrados += 1;
                continue;
            }
            $validateDateDuplicate = $this->baseModel->validateData('medicos', 'rif', $valor['rif']);
            if (!empty($validateDateDuplicate)) {
                $resultados['no_registrados'][] = ['resultado' => 'duplicado', 'codigo_validacion' => 2, 'mensaje' => 'El RIF ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.', 'data' => $valor];
                $medicos_no_registrados += 1;
                continue;
            }
            $validateDateDuplicate = $this->baseModel->validateData('medicos', 'impre', $valor['impre']);
            if (!empty($validateDateDuplicate)) {
                $resultados['no_registrados'][] = ['resultado' => 'duplicado', 'codigo_validacion' => 3, 'mensaje' => 'El número de IMPRE ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.', 'data' => $valor];
                $medicos_no_registrados += 1;
                continue;
            }
            $validateDateDuplicate = $this->baseModel->validateData('medicos', 'correo', $valor['correo']);
            if (!empty($validateDateDuplicate)) {
                $resultados['no_registrados'][] = ['resultado' => 'duplicado', 'codigo_validacion' => 4, 'mensaje' => 'El correo electrónico ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.', 'data' => $valor];
                $medicos_no_registrados += 1;
                continue;
            }
            $validateDateDuplicate = $this->baseModel->validateData('medicos', 'numero_colegio', $valor['numero_colegio']);
            if (!empty($validateDateDuplicate)) {
                $resultados['no_registrados'][] = ['resultado' => 'duplicado', 'codigo_validacion' => 5, 'mensaje' => 'El numero de colegio de medicos del Estado Aragua ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.', 'data' => $valor];
                $medicos_no_registrados += 1;
                continue;
            }
            $validateDateDuplicate = $this->baseModel->validateData('medicos_detalles', 'matricula_ministerio', $valor['matricula_ministerio']);
            if (!empty($validateDateDuplicate)) {
                $resultados['no_registrados'][] = ['resultado' => 'duplicado', 'codigo_validacion' => 6, 'mensaje' => 'La matricula del ministerio ingresada ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.', 'data' => $valor];
                $medicos_no_registrados += 1;
                continue;
            }
            $resultMedicos = $this->medicos->store($valor);
            $data = $valor;
            $data['id_medico'] = $resultMedicos['id_medico'];
            $resultMedicosDetalles = $this->medicos_detalles->store($data);
            $this->cambios_estados->store($data['id_medico'], $valor['estado']);
            $this->medicos_reconocimientos->store($data['id_medico']); 
            $resultados['registrados'][] = ['resultado' => 'registrado', 'id_medico' => $data['id_medico'], 'data' => $valor];
            $medicos_registrados += 1;
            $mensaje = "El usuario " . $_SESSION["nombres_apellidos"] . " registró al médico " . $valor['nombres'] . " " . $valor['apellidos'] . " (V-" . $valor['cedula'] . ")";
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => $mensaje]);
        }
        $resultados['resumen'] = ['total_registros' => count($datos), 'medicos_registrados' => $medicos_registrados, 'medicos_no_registrados' => $medicos_no_registrados];
        // echo "<pre>";
        // print_r($resultados);
        // echo "</pre>";
        // die();
        require_once "public/views/medicos/carga_masiva_resultados.php";
        die();
    }


















    public function edit(int $id_medico)
    {
        // Verificar permiso de actualizar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_MEDICOS, PermisosHelper::ACTUALIZAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_MEDICOS, PermisosHelper::ACTUALIZAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $grados = $this->grados->getAllGrados();
        $especialidades = $this->especialidades->getAllEspecialidades();
        $subespecialidades = $this->subespecialidades->getAllSubespecialidades();
        $especialiades_subespecialdiades = [];
        // Procesar especialidades
        foreach ($especialidades as $especialidad) {
            $especialiades_subespecialdiades[] = [
                'id' => 'esp_' . $especialidad['id_especialidad'],
                'nombre' => $especialidad['nombre'],
                'tipo' => 'especialidad',
                'id_original' => $especialidad['id_especialidad']
            ];
        }

        // Procesar subespecialidades
        foreach ($subespecialidades as $subespecialidad) {
            $especialiades_subespecialdiades[] = [
                'id' => 'sub_' . $subespecialidad['id_subespecialidad'],
                'nombre' => $subespecialidad['nombre'],
                'tipo' => 'subespecialidad',
                'id_original' => $subespecialidad['id_subespecialidad']
            ];
        }
        $medico = $this->medicos->show($id_medico)[0];
        $especialidades_medico  = array_merge($this->medicos_especialidades->show($id_medico), $this->medicos_subespecialidades->show($id_medico));
        $documentos_medico = $this->medicos_documentos->show($id_medico);
        $DIRECTORIO_BASE = $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] . '/assets/documents/documentos_medicos/' . $medico['directorio'] . '/';
        foreach ($documentos_medico as $i => $valor) {
            $documentos_medico[$i]['ruta_archivo'] = $DIRECTORIO_BASE . $valor['ruta_archivo'];
        }
        $cursos_medico = $this->medicos_cursos->show($id_medico);
        $diplomados_medico = $this->medicos_diplomados->show($id_medico);
        $deportes = $this->medicos_deportes->show($id_medico);
        $deportes_medico = [];
        foreach ($deportes as $i => $valor) {
            $deportes_medico[] = $valor['id_deporte'];
        }
        $deportes = $this->deportes->getAllDeportes();
        $parroquias = $this->municipios->getParroquiasSinJSON($medico['id_municipio']);
        $datos = ['$medicos' => $medico, '$especialidades_medico' => $especialidades_medico, '$cursos_medico' => $cursos_medico, '$diplomados_medico' => $diplomados_medico, '$documentos_medico' => $documentos_medico, '$deportes_medico' => $deportes_medico, '$parroquias' => $parroquias];
        // self::imprimirDatosRecibidos($datos, true);
        $medico['nombres'] = str_replace('_', ' ', $medico['nombres']);
        $medico['apellidos'] = str_replace('_', ' ', $medico['apellidos']);
        $municipios = $this->municipios->getMunicipios();
        $especialiades_subespecialdiadesJ = json_encode($especialiades_subespecialdiades);
        $especialiades_subespecialdiadesJ = json_encode($especialiades_subespecialdiadesJ);
        require_once "public/views/medicos/edit.php";
    }

    public function update(array $request, array $files)
    {
        // Verificar permiso de actualizar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_MEDICOS, PermisosHelper::ACTUALIZAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_MEDICOS, PermisosHelper::ACTUALIZAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $request = self::validarNullosPermitidos($request);
        // $datos = ['$request' => $request, '$files' => $files];
        // self::imprimirDatosRecibidos($datos, true);
        $request = self::validarNullosPermitidos($request);
        if (!self::validarExistenciaCampos($request)) {
            AlertasController::error('Campos incompletos', 'Por favor complete todos los campos requeridos del formulario');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        // Procesamiento de datos basicos
        foreach (self::camposEsperados as $i => $valor) {
            $request[$valor] = trim($request[$valor]);
        }
        $request['nombres'] = strtoupper(str_replace(' ', '_', $request['nombres']));
        $request['apellidos'] = strtoupper(str_replace(' ', '_', $request['apellidos']));
        $validateDateDuplicate = $this->baseModel->validateData('medicos', 'cedula', $request['cedula'], 'update', $request['id_medico'], 'id_medico');
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'La cédula ingresada ya se encuentra registrada en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        $validateDateDuplicate = $this->baseModel->validateData('medicos', 'rif', $request['rif'], 'update', $request['id_medico'], 'id_medico');
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'El RIF ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        $validateDateDuplicate = $this->baseModel->validateData('medicos', 'impre', $request['impre'], 'update', $request['id_medico'], 'id_medico');
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'El número de IMPRE ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        $validateDateDuplicate = $this->baseModel->validateData('medicos', 'correo', $request['correo'], 'update', $request['id_medico'], 'id_medico');
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'El correo electrónico ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        $validateDateDuplicate = $this->baseModel->validateData('medicos', 'numero_colegio', $request['numero_colegio'], 'update', $request['id_medico'], 'id_medico');
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'El numero de colegio de medicos del Estado Aragua ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        $validateDateDuplicate = $this->baseModel->validateData('medicos_detalles', 'matricula_ministerio', $request['matricula_ministerio'], 'update', $request['id_medico'], 'id_medico');
        if (!empty($validateDateDuplicate)) {
            AlertasController::warning('Dato duplicado', 'La matricula del ministerio ingresada ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
            header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
            die();
        }
        if (self::validarExistenciaCampo($request, 'cursos')) {
            if (!self::validarArrays($request['cursos'], false)) {
                AlertasController::error('Data Incompleta', 'Los cursos no fueron recibidos');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
        }
        if (self::validarExistenciaCampo($request, 'diplomados')) {
            if (!self::validarArrays($request['diplomados'], false)) {
                AlertasController::error('Data Incompleta', 'Los diplomados no fueron recibidos');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
        }
        if (self::validarExistenciaCampo($request, 'deportes')) {
            if (!self::validarArrays($request['deportes'], false)) {
                AlertasController::error('Data Incompleta', 'Los deportes no fueron recibidos');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
        }
        if (self::validarExistenciaCampo($request, 'especialidades')) {
            if (!self::validarArrays($request['especialidades'], false)) {
                AlertasController::error('Data Incompleta', 'Las especialidades no fueron recibidas');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            // Procesar especialidades
            $request['especialidades'] = self::procesarEspecialidades($request['especialidades']);
        }
        if (self::validarExistenciaCampo($files, 'nombre_foto')) {
            if (!self::validarArrays($files['nombre_foto'], false)) {
                AlertasController::error('Data Incompleta', 'La foto del medico no fue recibida');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            $validacionFoto = self::validarFoto($files['nombre_foto'] ?? []);
            if (!$validacionFoto['valido']) {
                AlertasController::error('Error en la foto', implode($validacionFoto['errores']));
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            if ($request['accion_foto'] == 'cambiar') {
                $this->imagenes->eliminarImagen($request['foto_actual'], 'medicos');
                $nombreDeLaFoto = $request['nombres'] . '_' . $request['apellidos'] . '_' . $request['cedula'];
                $request['nombre_foto'] = $this->imagenes->guardarImagen($files['nombre_foto'], 'medicos', $nombreDeLaFoto)['archivo'];
            } else if ($request['accion_foto'] == 'eliminar') {
                $this->imagenes->eliminarImagen('medicos', $request['foto_actual']);
                $request['nombre_foto'] = null;
            } else {
                $request['nombre_foto'] = $request['foto_actual'];
            }
        } else {
            $request['nombre_foto'] = null;
        }
        if (self::validarExistenciaCampo($files, 'nuevos_documentos') && $files['nuevos_documentos']['error'][0] != 4) {
            if (!self::validarArrays($files['nuevos_documentos'], false)) {
                AlertasController::error('Data Incompleta', 'Los documentos nuevos no fueron recibidos');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            $validacionDocsMultiples = self::validarDocumentosMultiples(
                $files['nuevos_documentos'] ?? [],
                [
                    'campo_nombre' => 'nuevos_documentos',
                    'requerido' => false,
                    'max_archivos' => 20,
                    'tamano_maximo' => 5 * 1024 * 1024 // 5MB
                ]
            );
            if (!$validacionDocsMultiples['valido']) {
                AlertasController::error('Error en documentos múltiples', implode('<br>', $validacionDocsMultiples['errores']));
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            // Procesar documentos multples
            $nombreSubDirectorio = $request['nombres'] . '_' . $request['apellidos'] . '_' . $request['cedula'];
            $resultadoSubidaDocs = $this->documentos->guardarMultiplesDocumentos($files['nuevos_documentos'], 'documentos_medicos', $nombreSubDirectorio);
            $request['nombresDocumentos'] = [];
            foreach ($resultadoSubidaDocs['resultados'] as $i => $valor) {
                $request['nombresDocumentos'][] = ['nombre_en_directorio' => $valor['archivo'], 'nombre_original' => $valor['nombre_original']];
            }
        }
        if (self::validarExistenciaCampo($request, 'documentos_existentes')) {
            if (!self::validarArrays($request['documentos_existentes'], false)) {
                AlertasController::error('Data Incompleta', 'Los documentos existentes no fueron recibidos');
                header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
                die();
            }
            $subdirectorio = $request['nombres'] . '_' . $request['apellidos'] . '_' . $request['cedula'];
            foreach ($request['documentos_existentes'] as $i => $valor) {
                if (!array_key_exists('conservar', $valor)) {
                    $nombre_en_directorio = $this->medicos_documentos->buscar_nombre_en_directorio($valor['id']);
                    $this->documentos->eliminarDocumento($nombre_en_directorio[0]['nombre_documento_directorio'], 'documentos_medicos', $subdirectorio);
                    $this->medicos_documentos->delete_documento_individual($valor['id']);
                }
            }
        }
        // $datos = ['$request' => $request, '$files' => $files];
        // self::imprimirDatosRecibidos($datos, true);
        $resultMedicos = $this->medicos->update($request);
        $resultMedicosDetalles = $this->medicos_detalles->update($request);
        if (self::validarExistenciaCampo($request, 'cursos')) {
            $this->medicos_cursos->update($request['id_medico'], $request['cursos']);
        }
        if (self::validarExistenciaCampo($request, 'diplomados')) {
            $this->medicos_diplomados->update($request['id_medico'], $request['diplomados']);
        }
        if (self::validarExistenciaCampo($request, 'deportes')) {
            $this->medicos_deportes->update($request['id_medico'], $request['deportes']);
        }
        if (self::validarExistenciaCampo($request, 'especialidades')) {
            $this->medicos_especialidades->delete($request['id_medico']);
            $this->medicos_subespecialidades->delete($request['id_medico']);
            foreach ($request['especialidades'] as $i => $valor) {
                $valor['id_medico'] = $request['id_medico'];
                if (!is_null($valor['id_especialidad'])) {
                    $this->medicos_especialidades->update($request['id_medico'], $valor);
                } else {
                    $this->medicos_subespecialidades->update($request['id_medico'], $valor);
                }
            }
        }
        if (self::validarExistenciaCampo($files, 'nuevos_documentos') && $files['nuevos_documentos']['error'][0] != 4) {
            foreach ($request['nombresDocumentos'] as $i => $valor) {
                $valor['id_medico'] = $request['id_medico'];
                $resultado = $this->medicos_documentos->store($valor);
            }
        }
        $this->cambios_estados->update($request['id_medico'], $request['estado']);
        // $datos = ['$request' => $request, '$files' => $files];
        // self::imprimirDatosRecibidos($datos, true);
        if ($resultMedicos['error'] == 0 && $resultMedicosDetalles['error'] == 0) {
            $nombreMedico = str_replace('_', ' ', $request['nombres'] . $request['apellidos']);
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' modifico los datos del médico perteneciente de la cedula de indentidad ' . $request['cedula']]);
            AlertasController::success('Actualización Exitosa.');
        }
        if ($resultMedicos['error'] == 1) {
            AlertasController::error('Dato duplicado', 'La cédula ingresada ya se encuentra registrada en el sistema, verifique y vuelvalo a intentar.');
        }
        if ($resultMedicos['error'] == 2) {
            AlertasController::error('Dato duplicado', 'El correo electrónico ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        }
        if ($resultMedicos['error'] == 3) {
            AlertasController::error('Dato duplicado', 'El numero de colegio de medicos del Estado Aragua ingresado ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        }
        if ($resultMedicos['error'] == 4) {
            AlertasController::error('Dato duplicado', 'La matricula del ministerio ingresada ya se encuentra registrado en el sistema, verifique y vuelvalo a intentar.');
        }
        header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
        die();
    }

    public function getdatashow(int $id_medico)
    {
        $grados = $this->grados->getAllGrados();
        $especialidades_medicos = $this->medicos_especialidades->show($id_medico);
        $subespecialidades_medicos = $this->medicos_subespecialidades->show($id_medico);
        $medico = $this->medicos->show($id_medico)[0];
        $medico['nombres'] = str_replace('_', ' ', $medico['nombres']);
        $medico['apellidos'] = str_replace('_', ' ', $medico['apellidos']);
        $documentos_medico = $this->medicos_documentos->show($id_medico);
        $DIRECTORIO_BASE = $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] . '/assets/documents/documentos_medicos/' . $medico['directorio'] . '/';
        foreach ($documentos_medico as $i => $valor) {
            $documentos_medico[$i]['ruta_archivo'] = $DIRECTORIO_BASE . $valor['ruta_archivo'];
        }
        $cursos_medico = $this->medicos_cursos->show($id_medico);
        $diplomados_medico = $this->medicos_diplomados->show($id_medico);
        $deportes_medico = $this->medicos_deportes->show($id_medico);
        $municipios = $this->municipios->getMunicipios();
        $parroquias = $this->municipios->getParroquiasSinJSON($medico['id_municipio']);
        $usuario_creador = $this->usuarios->show($_SESSION['id_usuario'])[0];
        $datos = [
            'medico' => $medico,
            'especialidades_medicos' => $especialidades_medicos,
            'subespecialidades_medicos' => $subespecialidades_medicos,
            'documentos_medico' => $documentos_medico,
            'cursos_medico' => $cursos_medico,
            'diplomados_medico' => $diplomados_medico,
            'deportes_medico' => $deportes_medico,
            'grados' => $grados,
            'municipios' => $municipios,
            'parroquias' => $parroquias,
            'usuario_creador' => $usuario_creador
        ];
        return $datos;
    }

    public function show(int $id_medico)
    {
        // Verificar permiso de ver
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_MEDICOS, PermisosHelper::VER)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_MEDICOS, PermisosHelper::VER);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        $grados = $this->grados->getAllGrados();
        $especialidades_medicos = $this->medicos_especialidades->show($id_medico);
        $subespecialidades_medicos = $this->medicos_subespecialidades->show($id_medico);
        $medico = $this->medicos->show($id_medico)[0];
        $medico['nombres'] = str_replace('_', ' ', $medico['nombres']);
        $medico['apellidos'] = str_replace('_', ' ', $medico['apellidos']);
        $documentos_medico = $this->medicos_documentos->show($id_medico);
        $DIRECTORIO_BASE = $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] . '/assets/documents/documentos_medicos/' . $medico['directorio'] . '/';
        foreach ($documentos_medico as $i => $valor) {
            $documentos_medico[$i]['ruta_archivo'] = $DIRECTORIO_BASE . $valor['ruta_archivo'];
        }
        $cursos_medico = $this->medicos_cursos->show($id_medico);
        $diplomados_medico = $this->medicos_diplomados->show($id_medico);
        $deportes_medico = $this->medicos_deportes->show($id_medico);
        $municipios = $this->municipios->getMunicipios();
        $parroquias = $this->municipios->getParroquiasSinJSON($medico['id_municipio']);
        $usuario_creador = $this->usuarios->show($_SESSION['id_usuario'])[0];
        $datos = ['$medico' => $medico, '$especialidades_medicos' => $especialidades_medicos, '$subespecialidades_medicos' => $subespecialidades_medicos, '$documentos_medico' => $documentos_medico, '$cursos_medico' => $cursos_medico, '$diplomados_medico' => $diplomados_medico, '$deportes_medico' => $deportes_medico, 'usuario_creador' => $usuario_creador, 'grados' => $grados, 'municipios' => $municipios, 'parroquias' => $parroquias];
        // self::imprimirDatosRecibidos($datos, true);
        require_once "public/views/medicos/show.php";
    }

    public function changeStatus(string $cadena)
    {
        $id_medico = intval(explode('__', $cadena)[0]);
        $estado = intval(explode('__', $cadena)[1]);
        $cedulaMedico = $this->medicos->show($id_medico)[0]['cedula'];
        $result = $this->medicos_detalles->changeStatus($id_medico, $estado);
        if ($result['error'] == 0) {
            $this->audi->store(['ID' => $_SESSION["id_usuario"], 'accion' => 'El usuario ' . $_SESSION["nombres_apellidos"] . ' elimino al médico perteneciente de la cedula de indentidad ' . $cedulaMedico]);
            AlertasController::success('Medico eliminado exitosamente.');
        } else {
            AlertasController::error('Error al eliminar el medico.', 'Por favor intente nuevamente.');
        }
        header('Location:' . $_ENV['BASE_PATH'] . '/medicos');
        die();
    }

    public function delete(int $id_medico)
    {
        // Verificar permiso de eliminar
        if (!PermisosHelper::tienePermiso(PermisosHelper::MODULO_MEDICOS, PermisosHelper::ELIMINAR)) {
            PermisosHelper::registrarIntentoNoAutorizado(PermisosHelper::MODULO_MEDICOS, PermisosHelper::ELIMINAR);
            PermisosHelper::mostrarErrorAcceso();
            return;
        }
        
        // Aquí continúa la lógica de eliminación si existe
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

    private static function validarExistenciaCampos(array $arrayData): bool
    {
        foreach (self::camposEsperados as $i => $valor) {
            if (!array_key_exists($valor, $arrayData)) {
                // echo $valor;
                return false;
            }
            if ($valor === 'estado') {
                if (!isset($arrayData[$valor])) {
                    return false;
                    // echo $valor;
                }
            } elseif (empty($arrayData[$valor])) {
                return false;
                // echo $valor;
            }
        }
        return true;
    }

    private static function validarNullosPermitidos(array $arrayData): array
    {
        $CamposNulosPermitidos = [
            'rif',
            'correo',
            'cedula',
            'impre'
        ];
        foreach ($CamposNulosPermitidos as $i => $valor) {
            if (!array_key_exists($valor, $arrayData)) {
                $arrayData[$valor] = null;
                continue;
            }
            continue;
        }
        return $arrayData;
    }

    private static function validarExistenciaCampo(array $arrayData, string $clave_a_buscar): bool
    {
        if (!array_key_exists($clave_a_buscar, $arrayData)) {
            return false;
        }
        if ($clave_a_buscar === 'estado') {
            if (!isset($arrayData[$clave_a_buscar])) {
                return false;
            }
        } elseif (empty($arrayData[$clave_a_buscar])) {
            return false;
        }
        return true;
    }

    private static function validarArrays($arrayData, bool $permitirVacio = false): bool
    {
        if (!is_array($arrayData)) {
            return false;
        }
        if (!$permitirVacio && empty($arrayData)) {
            return false;
        }
        return true;
    }

    private static function validarDocumentosMultiples(array $archivos, array $configuracion = []): array
    {
        $configuracionPredeterminada = [
            'tipos_permitidos' => [
                'application/pdf' => 'pdf',
                'application/msword' => 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            ],
            'tamano_maximo' => 5 * 1024 * 1024, // 5MB
            'max_archivos' => 10,
            'requerido' => false,
            'campo_nombre' => 'documentos'
        ];

        $config = array_merge($configuracionPredeterminada, $configuracion);
        $errores = [];
        $archivosValidos = [];

        // Si no hay archivos y no son requeridos, retornar éxito
        if (empty($archivos) && !$config['requerido']) {
            return ['valido' => true, 'errores' => [], 'archivos' => []];
        }

        // Validar estructura básica
        if (!isset($archivos['error']) || !is_array($archivos['error'])) {
            return [
                'valido' => false,
                'errores' => ["Estructura de {$config['campo_nombre']} inválida"],
                'archivos' => []
            ];
        }

        $contador = 0;

        foreach ($archivos['error'] as $indice => $errorCode) {
            $contador++;

            // Validar límite de archivos
            if ($contador > $config['max_archivos']) {
                $errores[] = "Se excedió el límite máximo de {$config['max_archivos']} archivos en {$config['campo_nombre']}";
                break;
            }

            // Si no hay archivo, continuar (son opcionales)
            if ($errorCode === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            // Validar código de error
            if ($errorCode !== UPLOAD_ERR_OK) {
                $errores[] = self::obtenerMensajeErrorUpload($errorCode, $indice, $config['campo_nombre']);
                continue;
            }

            // Validar campos requeridos
            $camposRequeridos = ['name', 'type', 'tmp_name', 'size'];
            foreach ($camposRequeridos as $campo) {
                if (!isset($archivos[$campo][$indice])) {
                    $errores[] = "{$config['campo_nombre']}[{$indice}]: Falta el campo '{$campo}'";
                    continue 2;
                }
            }

            $nombre = $archivos['name'][$indice];
            $tipo = $archivos['type'][$indice];
            $tmpName = $archivos['tmp_name'][$indice];
            $tamano = $archivos['size'][$indice];

            // Validar archivo individual
            $validacionArchivo = self::validarArchivoIndividual(
                $nombre,
                $tipo,
                $tmpName,
                $tamano,
                $config['tipos_permitidos'],
                $config['tamano_maximo']
            );

            if (!$validacionArchivo['valido']) {
                $errores[] = "{$config['campo_nombre']} '{$nombre}': " . $validacionArchivo['error'];
                continue;
            }

            $archivosValidos[] = [
                'name' => $nombre,
                'type' => $tipo,
                'tmp_name' => $tmpName,
                'size' => $tamano,
                'error' => $errorCode
            ];
        }

        // Validar si son requeridos y no hay archivos
        if ($config['requerido'] && empty($archivosValidos)) {
            $errores[] = "Se requiere al menos un archivo en {$config['campo_nombre']}";
        }

        return [
            'valido' => empty($errores),
            'errores' => $errores,
            'archivos' => $archivosValidos
        ];
    }

    private static function validarArchivoIndividual(
        string $nombre,
        string $tipo,
        string $tmpName,
        int $tamano,
        array $tiposPermitidos = null,
        int $tamanoMaximo = null
    ): array {

        // Tipos permitidos por defecto
        $tiposPermitidos = $tiposPermitidos ?? [
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => 'png'
        ];

        $tamanoMaximo = $tamanoMaximo ?? (5 * 1024 * 1024); // 5MB por defecto

        // Obtener extensión del archivo
        $extension = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

        // Validar tamaño
        if ($tamano > $tamanoMaximo) {
            $tamanoMB = number_format($tamanoMaximo / (1024 * 1024), 2);
            return [
                'valido' => false,
                'error' => "El archivo excede el tamaño máximo de {$tamanoMB}MB"
            ];
        }

        // Validar tipo MIME
        if (!array_key_exists($tipo, $tiposPermitidos)) {
            // También validar por extensión como respaldo
            $extensionValida = false;
            foreach ($tiposPermitidos as $extensions) {
                if (is_array($extensions)) {
                    if (in_array($extension, $extensions)) {
                        $extensionValida = true;
                        break;
                    }
                } elseif ($extensions === $extension) {
                    $extensionValida = true;
                    break;
                }
            }

            if (!$extensionValida) {
                $extensionesPermitidas = [];
                foreach ($tiposPermitidos as $ext) {
                    if (is_array($ext)) {
                        $extensionesPermitidas = array_merge($extensionesPermitidas, $ext);
                    } else {
                        $extensionesPermitidas[] = $ext;
                    }
                }
                $extensionesStr = '.' . implode(', .', array_unique($extensionesPermitidas));

                return [
                    'valido' => false,
                    'error' => "Tipo de archivo no permitido. Extensiones válidas: {$extensionesStr}"
                ];
            }
        }

        // Validar que la extensión coincida con el tipo MIME
        $extensionEsperada = $tiposPermitidos[$tipo] ?? null;
        if ($extensionEsperada) {
            if (is_array($extensionEsperada)) {
                if (!in_array($extension, $extensionEsperada)) {
                    return [
                        'valido' => false,
                        'error' => "La extensión del archivo no coincide con su tipo MIME"
                    ];
                }
            } elseif ($extension !== $extensionEsperada) {
                return [
                    'valido' => false,
                    'error' => "La extensión del archivo no coincide con su tipo MIME"
                ];
            }
        }

        // Validar nombre
        if (!self::validarNombreArchivo($nombre)) {
            return [
                'valido' => false,
                'error' => 'Nombre de archivo no válido'
            ];
        }

        // Validar que sea archivo subido
        if (!is_uploaded_file($tmpName)) {
            return [
                'valido' => false,
                'error' => 'No es un archivo subido válido'
            ];
        }

        return ['valido' => true, 'error' => ''];
    }

    private static function validarNombreArchivo(string $nombre): bool
    {
        // Validar longitud
        if (strlen($nombre) > 255) {
            return false;
        }

        // Validar que tenga extensión
        $extension = pathinfo($nombre, PATHINFO_EXTENSION);
        if (empty($extension)) {
            return false;
        }

        // Validar caracteres permitidos
        if (preg_match('/[\/\\\?\*:"<>|]/', $nombre)) {
            return false;
        }

        // Validar que no sea un archivo peligroso
        $extensionesPeligrosas = [
            'php',
            'phtml',
            'php3',
            'php4',
            'php5',
            'php7',
            'phps',
            'exe',
            'bat',
            'cmd',
            'sh',
            'js',
            'vbs',
            'wsf',
            'scr',
            'jar',
            'war',
            'ear',
            'rar',
            'zip' // También podrías bloquear comprimidos si quieres
        ];

        if (in_array(strtolower($extension), $extensionesPeligrosas, true)) {
            return false;
        }

        return true;
    }

    private static function validarFoto(array $foto): array
    {
        $errores = [];

        // Si no hay foto, está bien (es opcional)
        if (!isset($foto['error']) || $foto['error'] === UPLOAD_ERR_NO_FILE) {
            return ['valido' => true, 'errores' => [], 'archivo' => null];
        }

        // Validar error de subida
        if ($foto['error'] !== UPLOAD_ERR_OK) {
            $errores[] = self::obtenerMensajeErrorUpload($foto['error'], null, 'foto');
            return ['valido' => false, 'errores' => $errores, 'archivo' => null];
        }

        // Validar campos requeridos
        $camposRequeridos = ['name', 'type', 'tmp_name', 'size'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($foto[$campo])) {
                $errores[] = "foto: Falta el campo '{$campo}'";
                return ['valido' => false, 'errores' => $errores, 'archivo' => null];
            }
        }

        // Tipos permitidos para foto (solo imágenes)
        $tiposFotoPermitidos = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => 'png'
            // Nota: según tu lista, GIF no está permitido para documentos generales
            // Si quieres permitir GIF para fotos, agrégalo aquí
        ];

        // Validar archivo individual (solo imágenes, máximo 2MB)
        $validacionArchivo = self::validarArchivoIndividual(
            $foto['name'],
            $foto['type'],
            $foto['tmp_name'],
            $foto['size'],
            $tiposFotoPermitidos,
            2 * 1024 * 1024 // 2MB máximo para fotos
        );

        if (!$validacionArchivo['valido']) {
            $errores[] = "foto: " . $validacionArchivo['error'];
            return ['valido' => false, 'errores' => $errores, 'archivo' => null];
        }

        return [
            'valido' => true,
            'errores' => [],
            'archivo' => $foto
        ];
    }

    private static function obtenerMensajeErrorUpload(int $errorCode, $indice = null, $campo = 'archivo'): string
    {
        $prefijo = $campo . ($indice !== null ? "[{$indice}]" : "") . ": ";

        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return $prefijo . "El archivo excede el tamaño máximo permitido (5MB)";
            case UPLOAD_ERR_PARTIAL:
                return $prefijo . "El archivo fue subido parcialmente";
            case UPLOAD_ERR_NO_TMP_DIR:
                return $prefijo . "No hay carpeta temporal configurada";
            case UPLOAD_ERR_CANT_WRITE:
                return $prefijo . "No se pudo escribir el archivo en disco";
            case UPLOAD_ERR_EXTENSION:
                return $prefijo . "Extensión de archivo no permitida";
            default:
                return $prefijo . "Error desconocido al subir el archivo (código: {$errorCode})";
        }
    }

    private function procesarEspecialidades($especialidades): array
    {
        $especialiades_subespecialdiades = [];

        foreach ($especialidades as $especialidad) {
            // Extraer información
            $tipoReal = $especialidad['tipo_real'] ?? $especialidad['tipo'] ?? '';
            $idOriginal = $especialidad['id_original'] ?? $especialidad['id_especialidad'] ?? '';
            if ($tipoReal && $idOriginal) {
                if ($tipoReal === 'esp' || $tipoReal === 'especialidad') {
                    // Es una especialidad
                    $especialiades_subespecialdiades[] = [
                        'tipo' => 'especialidad',
                        'id_especialidad' => $idOriginal,
                        'id_subespecialidad' => null,
                        'universidad_obtenido' => $especialidad['universidad_obtenido'],
                        'fecha_obtencion' => $especialidad['fecha_obtencion']
                    ];
                } elseif ($tipoReal === 'sub' || $tipoReal === 'subespecialidad') {
                    // Es una subespecialidad
                    $especialiades_subespecialdiades[] = [
                        'tipo' => 'subespecialidad',
                        'id_especialidad' => null,
                        'id_subespecialidad' => $idOriginal,
                        'universidad_obtenido' => $especialidad['universidad_obtenido'],
                        'fecha_obtencion' => $especialidad['fecha_obtencion']
                    ];
                }
            }
        }

        return $especialiades_subespecialdiades;
    }

    public static function codificarDocumentoBase64($file)
    {
        $contenidoFILE = file_get_contents($file['tmp_name']);
        $FILE_base_64 = base64_encode($contenidoFILE);
        return $FILE_base_64;
    }
}
