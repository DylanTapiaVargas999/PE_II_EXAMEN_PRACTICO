<?php
require_once 'models/Producto.php';
require_once 'helpers/utils.php';

class BCGControlador {
    private $producto;

    private function toDecimal($valor) {
        // Si contiene coma, asume formato europeo (1.234,56)
        if (strpos($valor, ',') !== false) {
            $valor = str_replace('.', '', $valor); // elimina separador de miles
            $valor = str_replace(',', '.', $valor); // cambia coma decimal a punto
        }
        // Si no contiene coma, asume formato estándar (1234.56)
        return floatval($valor);
    }

    public function __construct() {
        $this->producto = new Producto();
    }
    
    // Vista principal que combina gestión de productos y formulario BCG
    public function index() {
        Utilidades::verificarSesion();
        Utilidades::verificarPlan();

        // Forzar recarga de datos desde la base de datos
        $this->producto = new Producto();
        $productos = $this->producto->obtenerPorPlan(
            $_SESSION['plan_codigo'], 
            $_SESSION['identity']->id
        );
        // Verificar si estamos editando un producto específico
        $editarProducto = isset($_GET['editar']) ? (int)$_GET['editar'] : null;
        
        // Obtener productos del plan actual
        $productos = $this->producto->obtenerPorPlan(
            $_SESSION['plan_codigo'], 
            $_SESSION['identity']->id
        );
        
        // Si estamos editando, cargar los datos de ese producto
        $productoEditar = null;
        if ($editarProducto) {
            $productoEditar = $this->producto->obtenerPorId($editarProducto, $_SESSION['identity']->id);
        }
        
        require_once 'views/bcg/index.php';
    }
    
    // Guardar o actualizar producto (datos básicos o completos)
    public function guardar() {
        Utilidades::verificarSesion();
        Utilidades::verificarPlan();
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_bcg'] = 'Acceso no autorizado';
            header("Location:" . base_url . "bcg/index");
            exit();
        }

        try {
            $id_usuario = $_SESSION['identity']->id;
            $codigo_plan = $_SESSION['plan_codigo'];
            
            // Determinar si es un nuevo producto o una actualización
            $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
            
            if (empty($_POST['nombre'])) {
                throw new Exception("El nombre del producto es obligatorio");
            }
            
            // Preparar datos básicos
            $datos = [
                'nombre' => $_POST['nombre'],
                'codigo' => $codigo_plan,
                'id_usuario' => $id_usuario
            ];
            
            // Si es un producto existente, agregar el ID
            if ($id_producto > 0) {
                $datos['id_producto'] = $id_producto;
            }
            
            // Si se están enviando datos completos de BCG
            if (isset($_POST['ventas'])) {
                $datos['ventas'] = $this->toDecimal($_POST['ventas']);
                $datos['tcm1'] = $this->toDecimal($_POST['tcm1'] ?? 0);
                // ... (agregar todos los demás campos de BCG de la misma forma)
                $datos['edgs'] = $this->toDecimal($_POST['edgs'] ?? 0);
                $datos['completado'] = true;
            }
            
            // Guardar o actualizar el producto
            if ($id_producto > 0) {
                $result = $this->producto->actualizarProducto($datos);
            } else {
                $result = $this->producto->guardarProducto($datos);
            }
            
            if (!$result) {
                throw new Exception("Error al guardar el producto");
            }
            
            $_SESSION['exito_bcg'] = $id_producto > 0 ? 'Producto actualizado' : 'Producto agregado';
            header("Location:" . base_url . "bcg/index");
            
        } catch (Exception $e) {
            $_SESSION['error_bcg'] = $e->getMessage();
            header("Location:" . base_url . "bcg/index");
        }
        exit();
    }
    
    public function guardarTodo() {
        Utilidades::verificarSesion();
        Utilidades::verificarPlan();

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['error_bcg'] = 'Acceso no autorizado';
            header("Location:" . base_url . "bcg/index");
            exit();
        }

        try {
            $productos_data = $_POST['productos'] ?? [];
            $id_usuario = $_SESSION['identity']->id;
            $codigo_plan = $_SESSION['plan_codigo'];
            $todos_guardados = true;

            foreach ($productos_data as $producto_id => $datos) {
                if (!empty($datos['id'])) {
                    $datos_actualizar = [
                        'id_producto' => (int)$datos['id'],
                        'id_usuario' => $id_usuario,
                        'codigo' => $codigo_plan,
                        'nombre' => $datos['nombre'],
                        'ventas' => $this->toDecimal($datos['ventas'] ?? 0),
                        'edgs' => $this->toDecimal($datos['edgs'] ?? 0),
                        'completado' => true
                    ];

                    // Tasas de crecimiento
                    for ($i = 1; $i <= 5; $i++) {
                        $datos_actualizar['tcm'.$i] = $this->toDecimal($datos['tcm'.$i] ?? 0);
                    }
                    // Competidores
                    for ($i = 1; $i <= 9; $i++) {
                        $datos_actualizar['cp'.$i] = $this->toDecimal($datos['cp'.$i] ?? 0);
                    }
                    if (!$this->producto->actualizarProducto($datos_actualizar)) {
                        $todos_guardados = false;
                        throw new Exception("Error al actualizar producto ID: ".$datos['id']);
                    }
                }
            }

            if ($todos_guardados) {
                $_SESSION['exito_bcg'] = 'Todos los datos se guardaron correctamente';
            } else {
                $_SESSION['error_bcg'] = 'Algunos datos no se pudieron guardar';
            }
            
            header("Location:" . base_url . "bcg/index");
            exit();
            
        } catch (Exception $e) {
            $_SESSION['error_bcg'] = $e->getMessage();
            header("Location:" . base_url . "bcg/index");
            exit();
        }
    }
    // Eliminar producto
    public function eliminar($id) {
        Utilidades::verificarSesion();
        
        $id = (int)$id;
        if ($id > 0) {
            $this->producto->setIdProducto($id)
                ->setIdUsuario($_SESSION['identity']->id);
            
            if ($this->producto->eliminar()) {
                $_SESSION['exito_bcg'] = 'Producto eliminado correctamente';
            } else {
                $_SESSION['error_bcg'] = 'Error al eliminar el producto';
            }
        }
        
        header("Location:" . base_url . "bcg/index");
    }
}