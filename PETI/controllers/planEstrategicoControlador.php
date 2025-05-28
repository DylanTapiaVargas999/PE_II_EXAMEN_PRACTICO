<?php
require_once 'models/planEstrategico.php';

class planEstrategicoControlador {

    public function index() {
        if (!isset($_SESSION['identity'])) {
            header("Location: " . base_url . "usuario/iniciarSesion");
            return;
        }

        $plan = new PlanEstrategico();
        $planes = $plan->obtenerTodosPorUsuario($_SESSION['identity']->id);

        require_once 'views/planEstrategico/index.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['identity'])) {
            $titulo = $_POST['titulo'] ?? false;
            $id = $_POST['id'] ?? null;
            $codigo = $_POST['codigo'] ?? null; // Captura el código

            if ($titulo) {
                $plan = new PlanEstrategico();
                $plan->setTitulo($titulo);
                $plan->setIdUsuario($_SESSION['identity']->id);

                if ($id) {
                    // Es edición
                    $plan->setId($id);
                    $plan->setCodigo($codigo); // Establece el código también
                    $actualizado = $plan->actualizar();
                    $_SESSION['plan_guardado'] = $actualizado ? 'completado' : 'fallido';
                } else {
                    // Es creación
                    $codigo = $this->generarCodigoUnico();
                    $plan->setCodigo($codigo);
                    $guardado = $plan->guardar();
                    $_SESSION['plan_guardado'] = $guardado ? 'completado' : 'fallido';
                }
            } else {
                $_SESSION['plan_guardado'] = 'fallido';
            }
        } else {
            $_SESSION['plan_guardado'] = 'fallido';
        }

        header("Location: " . base_url . "planEstrategico/index");
    }

    public function editar() {
        if (isset($_GET['id']) && isset($_SESSION['identity'])) {
            $id = $_GET['id'];

            $plan = new PlanEstrategico();
            $planData = $plan->obtenerUno($id); // Ahora pasa el id correctamente

            $planListado = new PlanEstrategico();
            $planes = $planListado->obtenerTodosPorUsuario($_SESSION['identity']->id);

            require_once 'views/planEstrategico/index.php';
        } else {
            header("Location: " . base_url . "planEstrategico/index");
        }
    }

    public function eliminar() {
        if (isset($_GET['id']) && isset($_SESSION['identity'])) {
            $plan = new PlanEstrategico();
            $plan->setId($_GET['id']);
            $eliminado = $plan->eliminar();

            $_SESSION['plan_eliminado'] = $eliminado ? 'completado' : 'fallido';
        }

        header("Location: " . base_url . "planEstrategico/index");
    }

    private function generarCodigoUnico($longitud = 10) {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $codigo = '';
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }
        return $codigo;
    }

    public function seleccionar() {
        if (isset($_GET['id']) && isset($_SESSION['identity'])) {
            $plan = new PlanEstrategico();
            $planSeleccionado = $plan->obtenerUno($_GET['id']);

            if ($planSeleccionado) {
                $_SESSION['plan_codigo'] = $planSeleccionado->codigo;
                header("Location: " . base_url ); // Redirige a dashboard o página principal
                exit();
            }
        }

        header("Location: " . base_url . "planEstrategico/index");
    }

    public function cambiar() {
        // Limpiar cualquier dato previo sobre el plan si es necesario
        unset($_SESSION['plan_codigo']); // Limpiamos el plan anterior si es necesario

        // Redirigir a la vista de selección de plan
        header("Location: " . base_url . "planEstrategico/seleccionar"); // Aquí se redirige al formulario de selección de plan
        exit();
    }

}
?>
