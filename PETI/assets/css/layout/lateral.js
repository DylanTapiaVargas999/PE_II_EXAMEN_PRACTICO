    // FUNCIÓN PARA MOSTRAR/OCULTAR MENÚ DE PERFIL (VERSIÓN ANTERIOR)
    function toggleProfileMenu() {
        const dropdown = document.querySelector('.profile-dropdown');
        const arrow = document.querySelector('.dropdown-arrow');
        
        dropdown.classList.toggle('show');
        arrow.classList.toggle('rotate');
    }

    // CERRAR MENÚ AL HACER CLIC FUERA (VERSIÓN MEJORADA)
    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.profile-dropdown');
        const userBtn = document.querySelector('.user-info');
        
        if (!userBtn.contains(event.target) && dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
            document.querySelector('.dropdown-arrow').classList.remove('rotate');
        }
    });

    // FUNCIÓN PARA ABRIR MODAL DE LOGIN (MANTENIENDO TU VERSIÓN)
    function abrirModalLogin() {
        // Aquí iría tu lógica para abrir el modal de login
        console.log("Modal de login abierto");
        // Ejemplo: document.getElementById('modal-login').style.display = 'block';
    }

    // ACTIVAR ELEMENTOS DEL MENÚ PRINCIPAL
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.menu-item').forEach(i => {
                i.classList.remove('active');
            });
            this.classList.add('active');
        });
    });



    // Función para marcar el ítem de menú activo
    function setActiveMenuItem() {
        const currentPage = window.location.pathname.split('/').pop() || 'index';
        const menuItems = document.querySelectorAll('.menu-item a');
        
        menuItems.forEach(item => {
            const href = item.getAttribute('href');
            const page = href.split('/').pop().split('?')[0];
            
            if (currentPage.includes(page) && page !== '') {
                item.parentElement.classList.add('active');
            }
        });
    }


    // Inicializar cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', function() {
        setActiveMenuItem();
        
        // Alternar sidebar en móviles (necesita botón de toggle en el header)
        const sidebarToggle = document.createElement('div');
        sidebarToggle.className = 'sidebar-toggle';
        sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
        sidebarToggle.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
        
        // Agregar botón de toggle si es móvil
        if (window.innerWidth < 992) {
            document.body.appendChild(sidebarToggle);
        }
    });