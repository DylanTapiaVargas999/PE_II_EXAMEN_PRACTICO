<?php
require_once 'models/usuario.php';

class usuarioControlador {

    public $estaLogueado;

    public function index() {
        echo "Controlador de Usuario, Acción index";
        require_once 'index.php';
    }

    public function setEstaLogueado($estaLogueado) {
        $this->estaLogueado = $estaLogueado;
    }

    public function getEstaLogueado() {
        return $this->estaLogueado;
    }

    public function guardar() {
        if (isset($_POST)) {
            $empresa = $_POST['empresa-registro'] ?? false;
            $correo = $_POST['correo-registro'] ?? false;
            $clave = $_POST['clave-registro'] ?? false;

            if ($empresa && $correo && $clave) {
                $usuario = new Usuario();
                $usuario->setEmpresa($empresa);
                $usuario->setCorreo($correo);
                $usuario->setClave($clave);

                // Procesar imagen si existe
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
                    $imagen = $_FILES['imagen'];
                    $nombreImagen = time() . "-" . $imagen['name'];
                    $rutaDestino = 'assets/img/perfiles/' . $nombreImagen;

                    $tipoImagen = pathinfo($imagen['name'], PATHINFO_EXTENSION);
                    $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];
                    $maxSize = 2 * 1024 * 1024;

                    if (in_array(strtolower($tipoImagen), $tiposPermitidos) && $imagen['size'] <= $maxSize) {
                        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                            $usuario->setImagen($nombreImagen);
                        }
                    }
                }

                $guardar = $usuario->guardar();
                $_SESSION['registro'] = $guardar ? 'completado' : 'fallido';
                require_once 'views/usuario/registroCompletado.php';
            } else {
                $_SESSION['registro'] = 'fallido';
                require_once 'views/usuario/registroCompletado.php';
            }
        } else {
            $_SESSION['registro'] = 'fallido';
            require_once 'views/usuario/registroCompletado.php';
        }

        header("Location: " . base_url);
    }

    public function iniciarSesion() {
        if (isset($_POST['correo-login']) && isset($_POST['clave'])) {
            $usuario = new Usuario();
            $usuario->setCorreo($_POST['correo-login']);
            $usuario->setClave($_POST['clave']);

            $identidad = $usuario->iniciarSesion();

            if ($identidad && is_object($identidad)) {
                // Guardar identidad del usuario en sesión
                $_SESSION['identity'] = $identidad;

                // Limpiar cualquier plan estratégico previamente seleccionado
                unset($_SESSION['plan_codigo']);

                // Redirigir al selector de planes estratégicos
                header("Location: " . base_url . "planEstrategico/index");
                exit();
            } else {
                $_SESSION['error_login'] = 'Correo o contraseña incorrectos';
            }
        }

        // Mostrar vista de login
        require_once 'views/usuario/iniciarSesion.php';
    }


    public function perfil() {
        if (!isset($_SESSION['identity'])) {
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        $usuario = $_SESSION['identity'];
        require_once 'views/usuario/perfil.php';
    }

    public function editarPerfil() {
        if (!isset($_SESSION['identity'])) {
            header("Location:" . base_url . "usuario/iniciarSesion");
            exit();
        }

        // Pasar el usuario a la vista
        $usuario = $_SESSION['identity'];
        require_once 'views/usuario/editarPerfil.php';

    }

    public function actualizarPerfil() {
        if (isset($_POST)) {
            $empresa = filter_input(INPUT_POST, 'empresa', FILTER_SANITIZE_STRING);
            $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);

            if ($empresa && $correo) {
                $usuario = new Usuario();
                $usuario->setId($_SESSION['identity']->id);
                $usuario->setEmpresa($empresa);
                $usuario->setCorreo($correo);

                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
                    $imagen = $_FILES['imagen'];
                    $nombreImagen = time() . "-" . $imagen['name'];
                    $rutaDestino = 'assets/img/perfiles/' . $nombreImagen;

                    $tipoImagen = pathinfo($imagen['name'], PATHINFO_EXTENSION);
                    $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];
                    $maxSize = 2 * 1024 * 1024;

                    if (in_array(strtolower($tipoImagen), $tiposPermitidos) && $imagen['size'] <= $maxSize) {
                        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                            $usuario->setImagen($nombreImagen);

                            if (!empty($_SESSION['identity']->imagen)) {
                                unlink('assets/img/perfiles/' . $_SESSION['identity']->imagen);
                            }

                            $_SESSION['identity']->imagen = $nombreImagen;
                        } else {
                            $_SESSION['error_imagen'] = 'Error al mover la imagen.';
                        }
                    } else {
                        $_SESSION['error_imagen'] = 'Formato no permitido o tamaño excesivo.';
                    }
                }

                $actualizado = $usuario->actualizar();

                if ($actualizado) {
                    $_SESSION['identity']->empresa = $empresa;
                    $_SESSION['identity']->correo = $correo;
                    $_SESSION['perfil_actualizado'] = 'completado';
                } else {
                    $_SESSION['perfil_actualizado'] = 'fallido';
                }
            } else {
                $_SESSION['perfil_actualizado'] = 'fallido';
            }

            header("Location: " . base_url . "usuario/perfil");
            exit;
        }
    }

    public function cerrarSesion() {
        if (isset($_SESSION['identity'])) {
            unset($_SESSION['identity']);
        }

        header("Location: " . base_url);
    }
}
