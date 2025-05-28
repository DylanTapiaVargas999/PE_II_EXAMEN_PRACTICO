<?php
require_once 'models/valor.php';

class ValorControlador {

    public function index() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['error_valor'] = 'Debes iniciar sesión para acceder a esta sección';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            if (!isset($_SESSION['plan_codigo'])) {
                $_SESSION['error_valor'] = 'Debes seleccionar un plan estratégico primero';
                header("Location:" . base_url . "planEstrategico/seleccionar");
                exit();
            }

            $id_usuario = $_SESSION['identity']->id;
            $codigo_plan = $_SESSION['plan_codigo'];

            $valor = new Valor();
            $valores = $valor->obtenerPorCodigoPlan($codigo_plan, $id_usuario);

            if ($valores === false) {
                $_SESSION['error_valor'] = 'Error al cargar los valores';
            }

            $edicion = isset($_GET['editar']) && is_numeric($_GET['editar']);
            $valorActual = null;

            if ($edicion) {
                $valorActual = $valor->obtenerPorIdYUsuario($_GET['editar'], $id_usuario);
                if (!$valorActual) {
                    $_SESSION['error_valor'] = 'No tienes permiso para editar este valor';
                    $edicion = false;
                }
            }

            require_once 'views/valor/index.php';

        } catch (Exception $e) {
            $_SESSION['error_valor'] = 'Error en el sistema: ' . $e->getMessage();
            header("Location:" . base_url . "valor/index");
            exit();
        }
    }

    public function guardar() {
        if (!isset($_SESSION['identity']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_valor'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            if (!isset($_SESSION['plan_codigo'])) {
                throw new Exception('No has seleccionado un plan activo');
            }

            $textoValor = trim($_POST['valor'] ?? '');
            $codigoPlan = $_SESSION['plan_codigo'];
            $id_valor = $_POST['id_valor'] ?? null;

            if (empty($textoValor)) {
                throw new Exception('El valor no puede estar vacío');
            }

            $valor = new Valor();
            $valor->setValor($textoValor);
            $valor->setCodigo($codigoPlan);
            $valor->setIdUsuario($_SESSION['identity']->id);

            if ($id_valor) {
                $valor->setIdValor($id_valor);
                $resultado = $valor->actualizar();
                $_SESSION['valor_actualizado'] = $resultado ? 'completado' : 'fallido';
            } else {
                $resultado = $valor->guardar();
                $_SESSION['valor_guardado'] = $resultado ? 'completado' : 'fallido';
            }

            if (!$resultado) {
                throw new Exception('Error al procesar el valor en la base de datos');
            }

        } catch (Exception $e) {
            $_SESSION['error_valor'] = $e->getMessage();
            if ($id_valor) {
                $_SESSION['valor_actualizado'] = 'fallido';
            } else {
                $_SESSION['valor_guardado'] = 'fallido';
            }
        }

        header("Location:" . base_url . "valor/index");
        exit();
    }

    public function eliminar() {
        if (!isset($_SESSION['identity']) || !isset($_GET['id'])) {
            $_SESSION['error_valor'] = 'Acceso no autorizado';
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        try {
            $id_valor = (int)$_GET['id'];
            $id_usuario = $_SESSION['identity']->id;

            $valor = new Valor();
            $valor->setIdValor($id_valor)
                ->setIdUsuario($id_usuario);

            if ($valor->eliminar()) {
                $_SESSION['valor_eliminado'] = 'completado';
            } else {
                throw new Exception('Error al eliminar el valor');
            }
        } catch (Exception $e) {
            $_SESSION['valor_eliminado'] = 'fallido';
            $_SESSION['error_valor'] = $e->getMessage();
        }

        header("Location:" . base_url . "valor/index");
        exit();
    }
}