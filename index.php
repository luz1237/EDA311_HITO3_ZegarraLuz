<?php
declare(strict_types=1);
require_once 'config/db.php';
require_once 'config/funciones.php';
require_once 'models/Nodo.php';
require_once 'models/ListaDoble.php';
require_once 'models/Registro.php';
require_once 'controllers/RegistroController.php';

$db = new database();  
$pdo = $db->getpdo();   

$tablas = require 'config/tablas.php';

$t = $_GET['t'] ?? '';
$accion = $_GET['accion'] ?? 'listar';

if ($t === '') {
    require 'views/inicio.php';
    exit;
}

if (!isset($tablas[$t])) {
    echo 'Tabla no válida';
    exit;
}

$controlador = new RegistroController($pdo, $t, $tablas[$t]);

if ($accion === 'guardar') {
    $controlador->guardar();
} elseif ($accion === 'eliminar') {
    $controlador->eliminar();
} else {
    $controlador->listar();
}