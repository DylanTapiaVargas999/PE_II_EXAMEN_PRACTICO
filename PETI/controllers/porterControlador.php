<?php
require_once 'models/amenaza.php';
require_once 'models/oportunidad.php';
require_once 'models/encuestaPorter.php';

class PorterControlador {
    public function info() {
        require_once 'views/porter/info.php';
    }

    public function index() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error_porter'] = 'Debes iniciar sesión para acceder a esta sección';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            // Verificar que hay un plan seleccionado
            if (!isset($_SESSION['plan_codigo'])) {
                $_SESSION['error_porter'] = 'Debes seleccionar un plan estratégico primero';
                header("Location:" . base_url . "planEstrategico/seleccionar");
                exit();
            }

            $id_usuario = $_SESSION['identity']->id;
            $codigo_plan = $_SESSION['plan_codigo'];

            // Obtener datos de los modelos
            $amenaza = new Amenaza();
            $oportunidad = new Oportunidad();
            $encuesta = new EncuestaPorter();

            $amenazas = $amenaza->obtenerPorCodigo($codigo_plan);
            $oportunidades = $oportunidad->obtenerPorCodigo($codigo_plan);
            $encuestaActual = $encuesta->obtenerPorCodigo($codigo_plan);

            // 🔧 MODO EDICIÓN
            $edicionAmenaza = isset($_GET['editarAmenaza']) && is_numeric($_GET['editarAmenaza']);
            $edicionOportunidad = isset($_GET['editarOportunidad']) && is_numeric($_GET['editarOportunidad']);
            
            $amenazaActual = null;
            $oportunidadActual = null;

            if ($edicionAmenaza) {
                $amenazaActual = $amenaza->obtenerPorIdYUsuario($_GET['editarAmenaza'], $id_usuario);
                if (!$amenazaActual) {
                    $_SESSION['error_porter'] = 'No tienes permiso para editar esta amenaza';
                    $edicionAmenaza = false;
                }
            }

            if ($edicionOportunidad) {
                $oportunidadActual = $oportunidad->obtenerPorIdYUsuario($_GET['editarOportunidad'], $id_usuario);
                if (!$oportunidadActual) {
                    $_SESSION['error_porter'] = 'No tienes permiso para editar esta oportunidad';
                    $edicionOportunidad = false;
                }
            }

            require_once 'views/porter/index.php';

        } catch (Exception $e) {
            $_SESSION['error_porter'] = 'Error en el sistema: ' . $e->getMessage();
            header("Location:" . base_url . "porter/index");
            exit();
        }
    }

    public function guardarAmenaza() {
        if (!isset($_SESSION['identity']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_porter'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            if (!isset($_SESSION['plan_codigo'])) {
                throw new Exception('No has seleccionado un plan activo');
            }

            $textoAmenaza = trim($_POST['amenaza'] ?? '');
            $codigoPlan = $_SESSION['plan_codigo'];
            $id_amenaza = $_POST['id_amenaza'] ?? null;

            if (empty($textoAmenaza)) {
                throw new Exception('La amenaza no puede estar vacía');
            }

            $amenaza = new Amenaza();
            $amenaza->setAmenaza($textoAmenaza)
                     ->setCodigo($codigoPlan)
                     ->setIdUsuario($_SESSION['identity']->id);

            if ($id_amenaza) {
                // Modo edición
                $amenaza->setIdAmenaza($id_amenaza);
                $resultado = $amenaza->actualizar();
                $_SESSION['amenaza_actualizada'] = $resultado ? 'completado' : 'fallido';
            } else {
                // Modo creación
                $resultado = $amenaza->guardar();
                $_SESSION['amenaza_guardada'] = $resultado ? 'completado' : 'fallido';
            }

            if (!$resultado) {
                throw new Exception('Error al procesar la amenaza en la base de datos');
            }

        } catch (Exception $e) {
            $_SESSION['error_porter'] = $e->getMessage();
            if ($id_amenaza) {
                $_SESSION['amenaza_actualizada'] = 'fallido';
            } else {
                $_SESSION['amenaza_guardada'] = 'fallido';
            }
        }

        header("Location:" . base_url . "porter/index");
        exit();
    }

    public function guardarOportunidad() {
        if (!isset($_SESSION['identity']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_porter'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            if (!isset($_SESSION['plan_codigo'])) {
                throw new Exception('No has seleccionado un plan activo');
            }

            $textoOportunidad = trim($_POST['oportunidad'] ?? '');
            $codigoPlan = $_SESSION['plan_codigo'];
            $id_oportunidad = $_POST['id_oportunidad'] ?? null;

            if (empty($textoOportunidad)) {
                throw new Exception('La oportunidad no puede estar vacía');
            }

            $oportunidad = new Oportunidad();
            $oportunidad->setOportunidad($textoOportunidad)
                       ->setCodigo($codigoPlan)
                       ->setIdUsuario($_SESSION['identity']->id);

            if ($id_oportunidad) {
                // Modo edición
                $oportunidad->setIdOportunidad($id_oportunidad);
                $resultado = $oportunidad->actualizar();
                $_SESSION['oportunidad_actualizada'] = $resultado ? 'completado' : 'fallido';
            } else {
                // Modo creación
                $resultado = $oportunidad->guardar();
                $_SESSION['oportunidad_guardada'] = $resultado ? 'completado' : 'fallido';
            }

            if (!$resultado) {
                throw new Exception('Error al procesar la oportunidad en la base de datos');
            }

        } catch (Exception $e) {
            $_SESSION['error_porter'] = $e->getMessage();
            if ($id_oportunidad) {
                $_SESSION['oportunidad_actualizada'] = 'fallido';
            } else {
                $_SESSION['oportunidad_guardada'] = 'fallido';
            }
        }

        header("Location:" . base_url . "porter/index");
        exit();
    }

    public function guardarEncuesta() {
        if (!isset($_SESSION['identity']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_porter'] = 'Acceso no autorizado';
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

            $encuesta = new EncuestaPorter();
            $encuesta->setCodigo($codigoPlan)
                     ->setIdUsuario($id_usuario);

            // Establecer todas las respuestas de la encuesta (p1 a p17)
            for ($i = 1; $i <= 17; $i++) {
                $pregunta = 'p' . $i;
                $valor = isset($_POST[$pregunta]) ? (int)$_POST[$pregunta] : 0;
                $setter = 'setP' . $i;
                $encuesta->$setter($valor);
            }

            if ($id_encuesta) {
                // Modo edición
                $encuesta->setIdEncuestaPorter($id_encuesta);
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
            $_SESSION['error_porter'] = $e->getMessage();
            if ($id_encuesta) {
                $_SESSION['encuesta_actualizada'] = 'fallido';
            } else {
                $_SESSION['encuesta_guardada'] = 'fallido';
            }
        }

        header("Location:" . base_url . "porter/index");
        exit();
    }

    public function eliminarAmenaza() {
        if (!isset($_SESSION['identity']) || !isset($_GET['id'])) {
            $_SESSION['error_porter'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            $id_amenaza = (int)$_GET['id'];
            $id_usuario = $_SESSION['identity']->id;

            $amenaza = new Amenaza();
            $amenaza->setIdAmenaza($id_amenaza)
                    ->setIdUsuario($id_usuario);

            if ($amenaza->eliminar()) {
                $_SESSION['amenaza_eliminada'] = 'completado';
            } else {
                throw new Exception('Error al eliminar la amenaza');
            }
        } catch (Exception $e) {
            $_SESSION['amenaza_eliminada'] = 'fallido';
            $_SESSION['error_porter'] = $e->getMessage();
        }

        header("Location:" . base_url . "porter/index");
        exit();
    }

    public function eliminarOportunidad() {
        if (!isset($_SESSION['identity']) || !isset($_GET['id'])) {
            $_SESSION['error_porter'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            $id_oportunidad = (int)$_GET['id'];
            $id_usuario = $_SESSION['identity']->id;

            $oportunidad = new Oportunidad();
            $oportunidad->setIdOportunidad($id_oportunidad)
                       ->setIdUsuario($id_usuario);

            if ($oportunidad->eliminar()) {
                $_SESSION['oportunidad_eliminada'] = 'completado';
            } else {
                throw new Exception('Error al eliminar la oportunidad');
            }
        } catch (Exception $e) {
            $_SESSION['oportunidad_eliminada'] = 'fallido';
            $_SESSION['error_porter'] = $e->getMessage();
        }

        header("Location:" . base_url . "porter/index");
        exit();
    }
}