<?php
require_once 'models/vision.php';

class VisionControlador {

    public function index() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error_vision'] = 'Debes iniciar sesión para acceder a esta sección';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            // Verificar que hay un plan seleccionado
            if (!isset($_SESSION['plan_codigo'])) {
                $_SESSION['error_vision'] = 'Debes seleccionar un plan estratégico primero';
                header("Location:" . base_url . "planEstrategico/seleccionar");
                exit();
            }

            $id_usuario = $_SESSION['identity']->id;
            $codigo_plan = $_SESSION['plan_codigo'];

            $vision = new Vision();
            $visiones = $vision->obtenerPorCodigoPlan($codigo_plan, $id_usuario);

            if ($visiones === false) {
                $_SESSION['error_vision'] = 'Error al cargar las visiones';
            }

            // 🔧 MODO EDICIÓN
            $edicion = isset($_GET['editar']) && is_numeric($_GET['editar']);
            $visionActual = null;

            if ($edicion) {
                $visionActual = $vision->obtenerPorIdYUsuario($_GET['editar'], $id_usuario);
                if (!$visionActual) {
                    $_SESSION['error_vision'] = 'No tienes permiso para editar esta visión';
                    $edicion = false;
                }
            }

            require_once 'views/vision/index.php';

        } catch (Exception $e) {
            $_SESSION['error_vision'] = 'Error en el sistema: ' . $e->getMessage();
            header("Location:" . base_url . "vision/index");
            exit();
        }
    }

    public function guardar() {
        // Verificar autenticación y método POST
        if (!isset($_SESSION['identity']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_vision'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            // Verificar código de plan
            if (!isset($_SESSION['plan_codigo'])) {
                throw new Exception('No has seleccionado un plan activo');
            }

            // Validar y limpiar datos
            $textoVision = trim($_POST['vision'] ?? '');
            $codigoPlan = $_SESSION['plan_codigo'];
            $id_vision = $_POST['id_vision'] ?? null;

            if (empty($textoVision)) {
                throw new Exception('La visión no puede estar vacía');
            }

            // Crear y guardar/actualizar visión
            $vision = new Vision();
            $vision->setVision($textoVision);
            $vision->setCodigo($codigoPlan);
            $vision->setIdUsuario($_SESSION['identity']->id);

            if ($id_vision) {
                // Modo edición
                $vision->setIdVision($id_vision);
                $resultado = $vision->actualizar();
                $_SESSION['vision_actualizada'] = $resultado ? 'completado' : 'fallido';
            } else {
                // Modo creación
                $resultado = $vision->guardar();
                $_SESSION['vision_guardada'] = $resultado ? 'completado' : 'fallido';
            }

            if (!$resultado) {
                throw new Exception('Error al procesar la visión en la base de datos');
            }

        } catch (Exception $e) {
            $_SESSION['error_vision'] = $e->getMessage();
            if ($id_vision) {
                $_SESSION['vision_actualizada'] = 'fallido';
            } else {
                $_SESSION['vision_guardada'] = 'fallido';
            }
        }

        header("Location:" . base_url . "vision/index");
        exit();
    }

    public function eliminar() {
        if (!isset($_SESSION['identity']) || !isset($_GET['id'])) {
            $_SESSION['error_vision'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            $id_vision = (int)$_GET['id'];
            $id_usuario = $_SESSION['identity']->id;

            $vision = new Vision();
            $vision->setIdVision($id_vision)
                ->setIdUsuario($id_usuario);

            if ($vision->eliminar()) {
                $_SESSION['vision_eliminada'] = 'completado';
            } else {
                throw new Exception('Error al eliminar la visión');
            }
        } catch (Exception $e) {
            $_SESSION['vision_eliminada'] = 'fallido';
            $_SESSION['error_vision'] = $e->getMessage();
        }

        header("Location:" . base_url . "vision/index");
        exit();
    }
}