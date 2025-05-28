<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Mijo Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url?>assets/css/usuario/iniciarSesion.css">
</head>
<body>
<?php
// Verifica si el usuario está logueado
if (!isset($_SESSION['identity'])): ?>
<div class="auth-container" id="contenedor-auth">
    <!-- Panel Izquierdo (Login) -->
    <div class="panel-formulario panel-izquierdo" id="panel-login">
        <div class="formulario-box">
            <h2 class="text-center mb-4">Iniciar Sesión</h2>
            
            <?php if(isset($_SESSION['error_login'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error_login'] ?>
                </div>
                <?php unset($_SESSION['error_login']); ?>
            <?php endif; ?>
            
            <form action="<?=base_url?>usuario/iniciarSesion" method="POST">
                <div class="grupo-formulario">
                    <label for="correo-login">Correo Electrónico:</label>
                    <input type="email" name="correo-login" id="correo-login" required>
                </div>
                <div class="grupo-formulario">
                    <label for="clave">Contraseña:</label>
                    <div class="password-container">
                        <input type="password" name="clave" id="clave" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('clave')"></i>
                    </div>
                </div>
                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Recordarme
                    </label>
                    <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                </div>
                <input type="submit" class="boton" value="Iniciar Sesión">
            </form>
        </div>
    </div>

    <!-- Panel Derecho (Registro) -->
    <div class="panel-formulario panel-derecho" id="panel-registro">
        <div class="formulario-box">
            <h2 class="text-center mb-4">Registro</h2>
            
            <?php if(isset($_SESSION['error_registro'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error_registro'] ?>
                </div>
                <?php unset($_SESSION['error_registro']); ?>
            <?php endif; ?>
            
            <form action="<?=base_url?>usuario/guardar" method="POST" enctype="multipart/form-data" id="form-registro">
                <div class="grupo-formulario">
                    <label for="empresa-registro">Empresa:</label>
                    <input type="text" name="empresa-registro" id="empresa-registro" required>
                </div>
                <div class="grupo-formulario">
                    <label for="correo-registro">Correo Electrónico:</label>
                    <input type="email" name="correo-registro" id="correo-registro" required>
                </div>
                <div class="grupo-formulario">
                    <label for="clave-registro">Contraseña:</label>
                    <div class="password-container">
                        <input type="password" name="clave-registro" id="clave-registro" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('clave-registro')"></i>
                    </div>
                    <div class="password-strength" id="strength-indicator"></div>
                </div>
                <div class="grupo-formulario">
                    <label for="confirmar-clave-registro">Confirmar Contraseña:</label>
                    <div class="password-container">
                        <input type="password" name="confirmar-clave-registro" id="confirmar-clave-registro" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('confirmar-clave-registro')"></i>
                    </div>
                </div>
                <div class="terms-conditions">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">Acepto los <a href="#">Términos y Condiciones</a></label>
                </div>
                <input type="submit" class="boton" value="Registrarse">
            </form>
        </div>
    </div>

    <!-- Controles para alternar -->
    <div class="controles-alternar">
        <button id="btn-mostrar-registro" class="boton-alternar">
            ¿No tienes cuenta? <span>Regístrate</span>
        </button>
        <button id="btn-mostrar-login" class="boton-alternar oculto">
            ¿Ya tienes cuenta? <span>Inicia Sesión</span>
        </button>
    </div>
</div>

<script src="<?=base_url?>assets/css/usuario/iniciarSesion.js"></script>
<?php endif; ?>
</body>
</html>
