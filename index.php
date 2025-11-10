<?php

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// use App\Controllers\Mantenimientos\{};

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$route = str_replace("/SIMA", '', $request_uri);

session_start();
date_default_timezone_set('America/Caracas');
setlocale(LC_TIME, 'Spanish');

// validacion de logueo o registro

//rutas tipo GET

