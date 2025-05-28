<?php
require_once 'models/uen.php';
require_once 'models/objetivoGeneral.php';
require_once 'models/objetivoEspecifico.php';

class PlanControlador {
    private $uen;
    private $objetivoGeneral;
    private $objetivoEspecifico;

    public function __construct() {
        $this->uen = new Uen();
        $this->objetivoGeneral = new ObjetivoGeneral();
        $this->objetivoEspecifico = new ObjetivoEspecifico();
    }

    // Método para verificar autenticación y plan seleccionado
    private function verificarAcceso() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error_plan'] = 'Debes iniciar sesión';
            header("Location: ".base_url."usuario/iniciarSesion");
            exit();
        }

        if (!isset($_SESSION['plan_codigo'])) {
            $_SESSION['error_plan'] = 'Debes seleccionar un plan estratégico';
            header("Location: ".base_url."planEstrategico/seleccionar");
            exit();
        }
    }

    // Método principal que muestra todos los componentes del plan
    public function index() {
        $this->verificarAcceso();
        
        $id_usuario = $_SESSION['identity']->id;
        $codigo_plan = $_SESSION['plan_codigo'];

        // Obtener todos los datos del plan
        $uenes = $this->uen->obtenerPorUsuarioYPlan($id_usuario, $codigo_plan);
        $objetivos = $this->objetivoGeneral->obtenerConEspecificos($id_usuario, $codigo_plan);

        // Verificar si estamos en modo edición para alguna entidad
        $edicion = $this->procesarModoEdicion($id_usuario);

        require_once 'views/plan/index.php';
    }

    // Método para procesar el modo edición
    private function procesarModoEdicion($id_usuario) {
        $edicion = [
            'uen' => null,
            'general' => null,
            'especifico' => null
        ];

        if (isset($_GET['editar_uen']) && is_numeric($_GET['editar_uen'])) {
            $edicion['uen'] = $this->uen->obtenerUno($_GET['editar_uen'], $id_usuario);
        }

        if (isset($_GET['editar_general']) && is_numeric($_GET['editar_general'])) {
            $edicion['general'] = $this->objetivoGeneral->obtenerUno($_GET['editar_general'], $id_usuario);
        }

        if (isset($_GET['editar_especifico']) && is_numeric($_GET['editar_especifico'])) {
            $id_especifico = $_GET['editar_especifico'];
            $especifico = $this->objetivoEspecifico->obtenerUno($id_especifico, $_GET['id_general'] ?? 0);
            
            // Verificar que el objetivo general pertenece al usuario
            if ($especifico) {
                $general = $this->objetivoGeneral->obtenerUno($especifico->id_general, $id_usuario);
                if ($general) {
                    $edicion['especifico'] = $especifico;
                }
            }
        }

        return $edicion;
    }

    // Métodos para guardar/actualizar cada entidad
    public function guardarUen() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_usuario = $_SESSION['identity']->id;
            $codigo_plan = $_SESSION['plan_codigo'];
            $nombre = trim($_POST['uen'] ?? '');

            if (empty($nombre)) {
                $_SESSION['error_plan'] = 'El nombre de la UEN no puede estar vacío';
                header("Location: ".base_url."plan/index");
                exit();
            }

            $this->uen->setUen($nombre)
                     ->setCodigo($codigo_plan)
                     ->setIdUsuario($id_usuario);

            // Modo edición
            if (!empty($_POST['id_uen'])) {
                $this->uen->setIdUen($_POST['id_uen']);
                $resultado = $this->uen->actualizar();
                $mensaje = $resultado ? 'UEN actualizada correctamente' : 'Error al actualizar la UEN';
            } 
            // Modo creación
            else {
                $resultado = $this->uen->guardar();
                $mensaje = $resultado ? 'UEN creada correctamente' : 'Error al crear la UEN';
            }

            $_SESSION[$resultado ? 'exito_plan' : 'error_plan'] = $mensaje;
        }

        header("Location: ".base_url."plan/index");
    }

    public function guardarObjetivoGeneral() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_usuario = $_SESSION['identity']->id;
            $codigo_plan = $_SESSION['plan_codigo'];
            $objetivo = trim($_POST['objetivo_general'] ?? '');

            if (empty($objetivo)) {
                $_SESSION['error_plan'] = 'El objetivo general no puede estar vacío';
                header("Location: ".base_url."plan/index");
                exit();
            }

            $this->objetivoGeneral->setObjetivo($objetivo)
                                 ->setCodigo($codigo_plan)
                                 ->setIdUsuario($id_usuario);

            // Modo edición
            if (!empty($_POST['id_general'])) {
                $this->objetivoGeneral->setIdGeneral($_POST['id_general']);
                $resultado = $this->objetivoGeneral->actualizar();
                $mensaje = $resultado ? 'Objetivo general actualizado correctamente' : 'Error al actualizar el objetivo general';
            } 
            // Modo creación
            else {
                $resultado = $this->objetivoGeneral->guardar();
                $mensaje = $resultado ? 'Objetivo general creado correctamente' : 'Error al crear el objetivo general';
            }

            $_SESSION[$resultado ? 'exito_plan' : 'error_plan'] = $mensaje;
        }

        header("Location: ".base_url."plan/index");
    }

    public function guardarObjetivoEspecifico() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_general = $_POST['id_general'] ?? 0;
            $objetivo = trim($_POST['objetivo_especifico'] ?? '');

            if (empty($objetivo) || empty($id_general)) {
                $_SESSION['error_plan'] = 'Datos incompletos para el objetivo específico';
                header("Location: ".base_url."plan/index");
                exit();
            }

            $this->objetivoEspecifico->setObjetivo($objetivo)
                                    ->setCodigo($_SESSION['plan_codigo'])
                                    ->setIdGeneral($id_general);

            // Modo edición
            if (!empty($_POST['id_especifico'])) {
                $this->objetivoEspecifico->setIdEspecifico($_POST['id_especifico']);
                $resultado = $this->objetivoEspecifico->actualizar();
                $mensaje = $resultado ? 'Objetivo específico actualizado correctamente' : 'Error al actualizar el objetivo específico';
            } 
            // Modo creación
            else {
                $resultado = $this->objetivoEspecifico->guardar();
                $mensaje = $resultado ? 'Objetivo específico creado correctamente' : 'Error al crear el objetivo específico';
            }

            $_SESSION[$resultado ? 'exito_plan' : 'error_plan'] = $mensaje;
        }

        header("Location: ".base_url."plan/index");
    }

    // Métodos para eliminar cada entidad
    public function eliminarUen() {
        $this->verificarAcceso();
        
        if (isset($_GET['id'])) {
            $this->uen->setIdUen($_GET['id'])
                     ->setIdUsuario($_SESSION['identity']->id);
            
            $resultado = $this->uen->eliminar();
            $_SESSION[$resultado ? 'exito_plan' : 'error_plan'] = $resultado ? 'UEN eliminada correctamente' : 'Error al eliminar la UEN';
        }

        header("Location: ".base_url."plan/index");
    }

    public function eliminarObjetivoGeneral() {
        $this->verificarAcceso();
        
        if (isset($_GET['id'])) {
            $this->objetivoGeneral->setIdGeneral($_GET['id'])
                                ->setIdUsuario($_SESSION['identity']->id);
            
            $resultado = $this->objetivoGeneral->eliminar();
            $_SESSION[$resultado ? 'exito_plan' : 'error_plan'] = $resultado ? 'Objetivo general eliminado correctamente' : 'Error al eliminar el objetivo general';
        }

        header("Location: ".base_url."plan/index");
    }

    public function eliminarObjetivoEspecifico() {
        $this->verificarAcceso();
        
        if (isset($_GET['id']) && isset($_GET['id_general'])) {
            $this->objetivoEspecifico->setIdEspecifico($_GET['id'])
                                    ->setIdGeneral($_GET['id_general']);
            
            $resultado = $this->objetivoEspecifico->eliminar();
            $_SESSION[$resultado ? 'exito_plan' : 'error_plan'] = $resultado ? 'Objetivo específico eliminado correctamente' : 'Error al eliminar el objetivo específico';
        }

        header("Location: ".base_url."plan/index");
    }
}
?>