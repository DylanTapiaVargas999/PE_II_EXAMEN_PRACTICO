<?php
require_once 'models/debilidad.php';
require_once 'models/fortaleza.php';
require_once 'models/encuestaCadena.php';

class AnalisisControlador {
    public function info() {
    require_once 'views/analisis/info.php';
}


    public function index() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error_analisis'] = 'Debes iniciar sesión para acceder a esta sección';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            // Verificar que hay un plan seleccionado
            if (!isset($_SESSION['plan_codigo'])) {
                $_SESSION['error_analisis'] = 'Debes seleccionar un plan estratégico primero';
                header("Location:" . base_url . "planEstrategico/seleccionar");
                exit();
            }

            $id_usuario = $_SESSION['identity']->id;
            $codigo_plan = $_SESSION['plan_codigo'];

            // Obtener datos de los modelos
            $debilidad = new Debilidad();
            $fortaleza = new Fortaleza();
            $encuesta = new EncuestaCadena();

            $debilidades = $debilidad->obtenerPorCodigo($codigo_plan);
            $fortalezas = $fortaleza->obtenerPorCodigo($codigo_plan);
            $encuestaActual = $encuesta->obtenerPorCodigo($codigo_plan);

            // 🔧 MODO EDICIÓN
            $edicionDebilidad = isset($_GET['editarDebilidad']) && is_numeric($_GET['editarDebilidad']);
            $edicionFortaleza = isset($_GET['editarFortaleza']) && is_numeric($_GET['editarFortaleza']);
            
            $debilidadActual = null;
            $fortalezaActual = null;

            if ($edicionDebilidad) {
                $debilidadActual = $debilidad->obtenerPorIdYUsuario($_GET['editarDebilidad'], $id_usuario);
                if (!$debilidadActual) {
                    $_SESSION['error_analisis'] = 'No tienes permiso para editar esta debilidad';
                    $edicionDebilidad = false;
                }
            }

            if ($edicionFortaleza) {
                $fortalezaActual = $fortaleza->obtenerPorIdYUsuario($_GET['editarFortaleza'], $id_usuario);
                if (!$fortalezaActual) {
                    $_SESSION['error_analisis'] = 'No tienes permiso para editar esta fortaleza';
                    $edicionFortaleza = false;
                }
            }

            require_once 'views/analisis/index.php';

        } catch (Exception $e) {
            $_SESSION['error_analisis'] = 'Error en el sistema: ' . $e->getMessage();
            header("Location:" . base_url . "analisis/index");
            exit();
        }
    }

    public function guardarDebilidad() {
        if (!isset($_SESSION['identity']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_analisis'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            if (!isset($_SESSION['plan_codigo'])) {
                throw new Exception('No has seleccionado un plan activo');
            }

            $textoDebilidad = trim($_POST['debilidad'] ?? '');
            $codigoPlan = $_SESSION['plan_codigo'];
            $id_debilidad = $_POST['id_debilidad'] ?? null;

            if (empty($textoDebilidad)) {
                throw new Exception('La debilidad no puede estar vacía');
            }

            $debilidad = new Debilidad();
            $debilidad->setDebilidad($textoDebilidad)
                     ->setCodigo($codigoPlan)
                     ->setIdUsuario($_SESSION['identity']->id);

            if ($id_debilidad) {
                // Modo edición
                $debilidad->setIdDebilidad($id_debilidad);
                $resultado = $debilidad->actualizar();
                $_SESSION['debilidad_actualizada'] = $resultado ? 'completado' : 'fallido';
            } else {
                // Modo creación
                $resultado = $debilidad->guardar();
                $_SESSION['debilidad_guardada'] = $resultado ? 'completado' : 'fallido';
            }

            if (!$resultado) {
                throw new Exception('Error al procesar la debilidad en la base de datos');
            }

        } catch (Exception $e) {
            $_SESSION['error_analisis'] = $e->getMessage();
            if ($id_debilidad) {
                $_SESSION['debilidad_actualizada'] = 'fallido';
            } else {
                $_SESSION['debilidad_guardada'] = 'fallido';
            }
        }

        header("Location:" . base_url . "analisis/index");
        exit();
    }

    public function guardarFortaleza() {
        if (!isset($_SESSION['identity']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_analisis'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            if (!isset($_SESSION['plan_codigo'])) {
                throw new Exception('No has seleccionado un plan activo');
            }

            $textoFortaleza = trim($_POST['fortaleza'] ?? '');
            $codigoPlan = $_SESSION['plan_codigo'];
            $id_fortaleza = $_POST['id_fortaleza'] ?? null;

            if (empty($textoFortaleza)) {
                throw new Exception('La fortaleza no puede estar vacía');
            }

            $fortaleza = new Fortaleza();
            $fortaleza->setFortaleza($textoFortaleza)
                     ->setCodigo($codigoPlan)
                     ->setIdUsuario($_SESSION['identity']->id);

            if ($id_fortaleza) {
                // Modo edición
                $fortaleza->setIdFortaleza($id_fortaleza);
                $resultado = $fortaleza->actualizar();
                $_SESSION['fortaleza_actualizada'] = $resultado ? 'completado' : 'fallido';
            } else {
                // Modo creación
                $resultado = $fortaleza->guardar();
                $_SESSION['fortaleza_guardada'] = $resultado ? 'completado' : 'fallido';
            }

            if (!$resultado) {
                throw new Exception('Error al procesar la fortaleza en la base de datos');
            }

        } catch (Exception $e) {
            $_SESSION['error_analisis'] = $e->getMessage();
            if ($id_fortaleza) {
                $_SESSION['fortaleza_actualizada'] = 'fallido';
            } else {
                $_SESSION['fortaleza_guardada'] = 'fallido';
            }
        }

        header("Location:" . base_url . "analisis/index");
        exit();
    }

    public function guardarEncuesta() {
        if (!isset($_SESSION['identity']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_analisis'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            if (!isset($_SESSION['plan_codigo'])) {
                throw new Exception('No has seleccionado un plan activo');
            }

            $codigoPlan = $_SESSION['plan_codigo'];
            $id_usuario = $_SESSION['identity']->id;
            $id_encuesta = $_POST['id_encuesta'] ?? null;
            $reflexion = trim($_POST['reflexion'] ?? '');

            $encuesta = new EncuestaCadena();
            $encuesta->setCodigo($codigoPlan)
                    ->setIdUsuario($id_usuario)
                    ->setReflexion($reflexion);

            // Establecer todas las respuestas de la encuesta
            for ($i = 1; $i <= 25; $i++) {
                $pregunta = 'p' . $i;
                $valor = isset($_POST[$pregunta]) ? (int)$_POST[$pregunta] : 0;
                $setter = 'setP' . $i;
                $encuesta->$setter($valor);
            }

            if ($id_encuesta) {
                // Modo edición
                $encuesta->setIdEncuestaCadena($id_encuesta);
                $resultado = $encuesta->actualizar();
                $_SESSION['encuesta_actualizada'] = $resultado ? 'completado' : 'fallido';
            } else {
                // Modo creación
                $resultado = $encuesta->guardar();
                $_SESSION['encuesta_guardada'] = $resultado ? 'completado' : 'fallido';
            }

            if (!$resultado) {
                throw new Exception('Error al procesar la encuesta en la base de datos');
            }

        } catch (Exception $e) {
            $_SESSION['error_analisis'] = $e->getMessage();
            if ($id_encuesta) {
                $_SESSION['encuesta_actualizada'] = 'fallido';
            } else {
                $_SESSION['encuesta_guardada'] = 'fallido';
            }
        }

        header("Location:" . base_url . "analisis/index");
        exit();
    }

    public function eliminarDebilidad() {
        if (!isset($_SESSION['identity']) || !isset($_GET['id'])) {
            $_SESSION['error_analisis'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            $id_debilidad = (int)$_GET['id'];
            $id_usuario = $_SESSION['identity']->id;

            $debilidad = new Debilidad();
            $debilidad->setIdDebilidad($id_debilidad)
                     ->setIdUsuario($id_usuario);

            if ($debilidad->eliminar()) {
                $_SESSION['debilidad_eliminada'] = 'completado';
            } else {
                throw new Exception('Error al eliminar la debilidad');
            }
        } catch (Exception $e) {
            $_SESSION['debilidad_eliminada'] = 'fallido';
            $_SESSION['error_analisis'] = $e->getMessage();
        }

        header("Location:" . base_url . "analisis/index");
        exit();
    }

    public function eliminarFortaleza() {
        if (!isset($_SESSION['identity']) || !isset($_GET['id'])) {
            $_SESSION['error_analisis'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            $id_fortaleza = (int)$_GET['id'];
            $id_usuario = $_SESSION['identity']->id;

            $fortaleza = new Fortaleza();
            $fortaleza->setIdFortaleza($id_fortaleza)
                     ->setIdUsuario($id_usuario);

            if ($fortaleza->eliminar()) {
                $_SESSION['fortaleza_eliminada'] = 'completado';
            } else {
                throw new Exception('Error al eliminar la fortaleza');
            }
        } catch (Exception $e) {
            $_SESSION['fortaleza_eliminada'] = 'fallido';
            $_SESSION['error_analisis'] = $e->getMessage();
        }

        header("Location:" . base_url . "analisis/index");
        exit();
    }
}