<?php
require_once 'models/estrategia.php';

class EstrategiasControlador {

    public function index() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error_estrategia'] = 'Debes iniciar sesión para acceder a esta sección';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            // Verificar que hay un plan seleccionado
            if (!isset($_SESSION['plan_codigo'])) {
                $_SESSION['error_estrategia'] = 'Debes seleccionar un plan estratégico primero';
                header("Location:" . base_url . "planEstrategico/seleccionar");
                exit();
            }

            $id_usuario = $_SESSION['identity']->id;
            $codigo_plan = $_SESSION['plan_codigo'];

            $estrategia = new Estrategia();
            $estrategias = $estrategia->obtenerPorCodigoPlan($codigo_plan, $id_usuario);

            if ($estrategias === false) {
                $_SESSION['error_estrategia'] = 'Error al cargar las estrategias';
            }

            // MODO EDICIÓN
            $edicion = isset($_GET['editar']) && is_numeric($_GET['editar']);
            $estrategiaActual = null;

            if ($edicion) {
                $estrategiaActual = $estrategia->obtenerPorIdYUsuario($_GET['editar'], $id_usuario);
                if (!$estrategiaActual) {
                    $_SESSION['error_estrategia'] = 'No tienes permiso para editar esta estrategia';
                    $edicion = false;
                }
            }

            require_once 'views/estrategias/index.php';

        } catch (Exception $e) {
            $_SESSION['error_estrategia'] = 'Error en el sistema: ' . $e->getMessage();
            header("Location:" . base_url . "estrategias/index");
            exit();
        }
    }

    public function guardar() {
        if (!isset($_SESSION['identity']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_estrategia'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            if (!isset($_SESSION['plan_codigo'])) {
                throw new Exception('No has seleccionado un plan activo');
            }

            // Guardar matriz cruzada
            $fo = [];
            $af = [];
            $od = [];
            $ad = [];
            // FO
            for ($f = 1; $f <= 4; $f++) {
                for ($o = 1; $o <= 4; $o++) {
                    $fo["F{$f}"]["O{$o}"] = $_POST["fo_f{$f}_o{$o}"] ?? 0;
                }
            }
            // AF
            for ($f = 1; $f <= 4; $f++) {
                for ($a = 1; $a <= 4; $a++) {
                    $af["F{$f}"]["A{$a}"] = $_POST["af_f{$f}_a{$a}"] ?? 0;
                }
            }
            // OD
            for ($d = 1; $d <= 4; $d++) {
                for ($o = 1; $o <= 4; $o++) {
                    $od["D{$d}"]["O{$o}"] = $_POST["od_d{$d}_o{$o}"] ?? 0;
                }
            }
            // AD
            for ($d = 1; $d <= 4; $d++) {
                for ($a = 1; $a <= 4; $a++) {
                    $ad["D{$d}"]["A{$a}"] = $_POST["ad_d{$d}_a{$a}"] ?? 0;
                }
            }

            $estrategia = new Estrategia();
            $estrategia->setCodigo($_SESSION['plan_codigo']);
            $estrategia->setIdUsuario($_SESSION['identity']->id);

            // Guardar la matriz cruzada
            $resultado = $estrategia->guardarMatriz($fo, $af, $od, $ad);

            $_SESSION['estrategia_guardada'] = $resultado ? 'completado' : 'fallido';

            if (!$resultado) {
                throw new Exception('Error al guardar la matriz de estrategias');
            }

        } catch (Exception $e) {
            $_SESSION['error_estrategia'] = $e->getMessage();
            $_SESSION['estrategia_guardada'] = 'fallido';
        }

        header("Location:" . base_url . "estrategias/index");
        exit();
    }

    public function eliminar() {
        if (!isset($_SESSION['identity']) || !isset($_GET['id'])) {
            $_SESSION['error_estrategia'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            $id_estrategia = (int)$_GET['id'];
            $id_usuario = $_SESSION['identity']->id;

            $estrategia = new Estrategia();
            $estrategia->setIdEstrategia($id_estrategia)
                ->setIdUsuario($id_usuario);

            if ($estrategia->eliminar()) {
                $_SESSION['estrategia_eliminada'] = 'completado';
            } else {
                throw new Exception('Error al eliminar la estrategia');
            }
        } catch (Exception $e) {
            $_SESSION['estrategia_eliminada'] = 'fallido';
            $_SESSION['error_estrategia'] = $e->getMessage();
        }

        header("Location:" . base_url . "estrategias/index");
        exit();
    }
}