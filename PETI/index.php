<?php
session_start();
ob_start();
require_once 'autoload.php';
require_once 'config/db.php';
require_once 'helpers/utils.php';
require_once 'config/parametros.php';

// Conexión a la base de datos
$db = Database::conexion();

function mostrar_error() {
    $error = new errorControlador();
    $error->index();
}

// Verificar si el usuario está logueado (excepto para páginas públicas)
$paginas_publicas = ['usuario-iniciarSesion', 'usuario-guardar', 'error-index'];
$controlador_actual = isset($_GET['controlador']) ? $_GET['controlador'] : 'usuario';
$accion_actual = isset($_GET['accion']) ? $_GET['accion'] : 'iniciarSesion';
$pagina_actual = "$controlador_actual-$accion_actual";

// Redirigir si no está logueado y no es página pública
if (!isset($_SESSION['identity']) && !in_array($pagina_actual, $paginas_publicas)) {
    header("Location: ".base_url."usuario/iniciarSesion");
    exit();
}

// Lógica para determinar el controlador y acción
if (isset($_GET['controlador'])) {
    $nombre_controlador = $_GET['controlador'] . 'Controlador';
} elseif (!isset($_GET['controlador']) && !isset($_GET['accion'])) {
    $nombre_controlador = 'usuarioControlador'; // Controlador por defecto
} else {
    mostrar_error();
    exit();
}

if (class_exists($nombre_controlador)) {
    $controlador = new $nombre_controlador;

    if (isset($_GET['accion']) && method_exists($controlador, $_GET['accion'])) {
        $accion = $_GET['accion'];
        ob_start();
        $controlador->$accion();
        $contenido = ob_get_clean();
    } elseif (!isset($_GET['controlador']) && !isset($_GET['accion'])) {
        $accion_predeterminada = 'iniciarSesion'; // Acción por defecto
        ob_start();
        $controlador->$accion_predeterminada();
        $contenido = ob_get_clean();
    } else {
        mostrar_error();
        exit();
    }
} else {
    mostrar_error();
    exit();
}

if (isset($_SESSION['identity'])) {
    // Si aún no ha seleccionado un plan, mostrar contenido sin layout
    if (!isset($_SESSION['plan_codigo'])) {
        echo $contenido;
    } else {
        // Si ya seleccionó un plan, se muestra el layout completo
        require_once 'views/layout/layout.php';
    }
} else {
    // Para usuarios no logueados (login/registro)
    echo $contenido;
}
