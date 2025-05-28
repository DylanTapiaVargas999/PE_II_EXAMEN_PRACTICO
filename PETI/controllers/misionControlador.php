<?php
require_once 'models/mision.php';

class MisionControlador {

    public function index() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error_mision'] = 'Debes iniciar sesión para acceder a esta sección';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            // Verificar que hay un plan seleccionado
            if (!isset($_SESSION['plan_codigo'])) {
                $_SESSION['error_mision'] = 'Debes seleccionar un plan estratégico primero';
                header("Location:" . base_url . "planEstrategico/seleccionar");
                exit();
            }

            $id_usuario = $_SESSION['identity']->id;
            $codigo_plan = $_SESSION['plan_codigo'];

            $mision = new Mision();
            $misiones = $mision->obtenerPorCodigoPlan($codigo_plan, $id_usuario);

            if ($misiones === false) {
                $_SESSION['error_mision'] = 'Error al cargar las misiones';
            }

            // 🔧 MODO EDICIÓN
            $edicion = isset($_GET['editar']) && is_numeric($_GET['editar']);
            $misionActual = null;

            if ($edicion) {
                $misionActual = $mision->obtenerPorIdYUsuario($_GET['editar'], $id_usuario);
                if (!$misionActual) {
                    $_SESSION['error_mision'] = 'No tienes permiso para editar esta misión';
                    $edicion = false;
                }
            }

            require_once 'views/mision/index.php';

        } catch (Exception $e) {
            $_SESSION['error_mision'] = 'Error en el sistema: ' . $e->getMessage();
            header("Location:" . base_url . "mision/index");
            exit();
        }
    }


    public function guardar() {
        // Verificar autenticación y método POST
        if (!isset($_SESSION['identity']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_mision'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            // Verificar código de plan
            if (!isset($_SESSION['plan_codigo'])) {
                throw new Exception('No has seleccionado un plan activo');
            }

            // Validar y limpiar datos
            $textoMision = trim($_POST['mision'] ?? '');
            $codigoPlan = $_SESSION['plan_codigo'];
            $id_mision = $_POST['id_mision'] ?? null;

            if (empty($textoMision)) {
                throw new Exception('La misión no puede estar vacía');
            }

            // Crear y guardar/actualizar misión
            $mision = new Mision();
            $mision->setMision($textoMision);
            $mision->setCodigo($codigoPlan);
            $mision->setIdUsuario($_SESSION['identity']->id);

            if ($id_mision) {
                // Modo edición
                $mision->setIdMision($id_mision);
                $resultado = $mision->actualizar();
                $_SESSION['mision_actualizada'] = $resultado ? 'completado' : 'fallido';
            } else {
                // Modo creación
                $resultado = $mision->guardar();
                $_SESSION['mision_guardada'] = $resultado ? 'completado' : 'fallido';
            }

            if (!$resultado) {
                throw new Exception('Error al procesar la misión en la base de datos');
            }

        } catch (Exception $e) {
            $_SESSION['error_mision'] = $e->getMessage();
            if ($id_mision) {
                $_SESSION['mision_actualizada'] = 'fallido';
            } else {
                $_SESSION['mision_guardada'] = 'fallido';
            }
        }

        header("Location:" . base_url . "mision/index");
        exit();
    }

    public function eliminar() {
        if (!isset($_SESSION['identity']) || !isset($_GET['id'])) {
            $_SESSION['error_mision'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            $id_mision = (int)$_GET['id'];
            $id_usuario = $_SESSION['identity']->id;

            $mision = new Mision();
            $mision->setIdMision($id_mision)
                ->setIdUsuario($id_usuario);

            if ($mision->eliminar()) {
                $_SESSION['mision_eliminada'] = 'completado';
            } else {
                throw new Exception('Error al eliminar la misión');
            }
        } catch (Exception $e) {
            $_SESSION['mision_eliminada'] = 'fallido';
            $_SESSION['error_mision'] = $e->getMessage();
        }

        header("Location:" . base_url . "mision/index");
        exit();
    }

    // Métodos eliminados porque ahora se maneja todo en index() y guardar()
    // public function editar() {}
    // public function actualizar() {}
}