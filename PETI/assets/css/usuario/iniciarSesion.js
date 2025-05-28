document.addEventListener('DOMContentLoaded', function() {
    // Alternar entre paneles
    const contenedor = document.getElementById('contenedor-auth');
    const btnRegistro = document.getElementById('btn-mostrar-registro');
    const btnLogin = document.getElementById('btn-mostrar-login');
    
    if(btnRegistro && btnLogin) {
        btnRegistro.addEventListener('click', function(e) {
            e.preventDefault();
            contenedor.classList.add('panel-registro-activo');
            btnRegistro.classList.add('oculto');
            btnLogin.classList.remove('oculto');
        });
        
        btnLogin.addEventListener('click', function(e) {
            e.preventDefault();
            contenedor.classList.remove('panel-registro-activo');
            btnLogin.classList.add('oculto');
            btnRegistro.classList.remove('oculto');
        });
    }
    
    // Función para mostrar/ocultar contraseñas
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling;
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
    
    // Agregar la funcionalidad para la validación de la contraseña
    const passwordInput = document.getElementById('clave-registro');
    const confirmPasswordInput = document.getElementById('confirmar-clave-registro');
    const strengthIndicator = document.getElementById('strength-indicator');
    
    if (passwordInput && confirmPasswordInput) {
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(passwordInput.value);
            strengthIndicator.textContent = strength;
        });

        confirmPasswordInput.addEventListener('input', function() {
            if (confirmPasswordInput.value !== passwordInput.value) {
                confirmPasswordInput.setCustomValidity("Las contraseñas no coinciden");
            } else {
                confirmPasswordInput.setCustomValidity("");
            }
        });
    }

    function checkPasswordStrength(password) {
        let strength = 'Débil';
        if (password.length >= 8 && /[A-Za-z]/.test(password) && /\d/.test(password)) {
            strength = 'Fuerte';
        } else if (password.length >= 6) {
            strength = 'Media';
        }
        return strength;
    }
});