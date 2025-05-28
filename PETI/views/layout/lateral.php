<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Profesional</title>
    <link rel="stylesheet" href="<?=base_url?>assets/css/layout/lateral.css">
    <link rel="stylesheet" href="<?=base_url?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .selected-plan {
            font-size: 0.9rem;
            color: #f0f0f0;
            margin-top: 8px;
            padding: 5px 10px;
            background-color: #333;
            border-radius: 5px;
        }
        .text-warning {
            color: #ffc107;
        }
        .btn-change-plan {
            margin-top: 10px;
            padding: 5px 10px;
            font-size: 0.9rem;
            background-color: #ff5722;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-change-plan:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>
    <!-- BARRA LATERAL -->
    <div class="sidebar modern-sidebar">
        <!-- ENCABEZADO -->
        <div class="sidebar-header">
            <h2>Estrategia Corporativa</h2>
            <span class="plan-badge">PETI</span>
            
            <!-- Mostrar el plan seleccionado -->
            <?php if (isset($_SESSION['plan_codigo'])): ?>
                <div class="selected-plan">
                    <i class="fas fa-file-alt"></i>
                    Plan activo: <strong><?= $_SESSION['plan_codigo'] ?></strong>
                </div>
                <!-- Botón para cambiar el plan -->
                <form action="<?=base_url?>planEstrategico/cambiar" method="POST" id="form-cambiar-plan">
                    <button type="submit" class="btn-change-plan">Cambiar Plan</button>
                </form>

            <?php else: ?>
                <div class="selected-plan text-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    No hay plan seleccionado
                </div>
            <?php endif; ?>
        </div>
        
        <!-- CONTENIDO DEL MENÚ -->
        <div class="menu-container">
            <!-- Sección Fundamentos -->
            <div class="menu-section">
                <h3 class="menu-title">Fundamentos</h3>
                <ul class="menu-list">
                    <li class="menu-item">
                        <i class="fas fa-bullseye"></i>
                        <a href="<?=base_url?>mision/index">1. Misión</a>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-eye"></i>
                        <a href="<?=base_url?>vision/index">2. Visión</a>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-gem"></i>
                        <a href="<?=base_url?>valor/index">3. Valores</a>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-flag-checkered"></i>
                        <a href="<?=base_url?>plan/index">4. Objetivos</a>
                    </li>
                </ul>
            </div>
            
            <!-- Sección Análisis -->
            <div class="menu-section">
                <h3 class="menu-title">Análisis Estratégico</h3>
                <ul class="menu-list">
                    <li class="menu-item">
                        <i class="fas fa-search"></i>
                        <a href="<?=base_url?>analisis/info">5. Análisis FODA</a>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-link"></i>
                        <a href="<?=base_url?>analisis/index">6. Cadena de Valor</a>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-th"></i>
                        <a href="<?=base_url?>BCG/index">7. Matriz BCG</a>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-chess-board"></i>
                        <a href="<?=base_url?>porter/index">8. 5 Fuerzas Porter</a>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-globe"></i>
                        <a href="<?=base_url?>pestel/index">9. Análisis PESTEL</a>
                    </li>
                </ul>
            </div>
            
            <!-- Sección Estrategia -->
            <div class="menu-section">
                <h3 class="menu-title">Formulación</h3>
                <ul class="menu-list">
                    <li class="menu-item">
                        <i class="fas fa-chess-queen"></i>
                        <a href="#">10. Estrategias</a>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-table"></i>
                        <a href="#">11. Matriz CAME</a>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-file-alt"></i>
                        <a href="#">12. Resumen</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- PIE DE PÁGINA CON USUARIO - VERSIÓN ADAPTADA -->
        <div class="sidebar-footer">
            <div class="menu-section">
                <h3 class="menu-title">Settings</h3>
                <ul class="menu-list">
                    <?php if (!isset($_SESSION['identity'])): ?>
                        <li class="menu-item">
                            <i class="fas fa-sign-in-alt"></i>
                            <button class="btn-login" onclick="abrirModalLogin()">Iniciar Sesión</button>
                        </li>
                    <?php else: ?>
                        <li class="menu-item user-profile">
                            <div class="user-info" onclick="toggleProfileMenu()">
                                <i class="fas fa-user-circle"></i>
                                <div class="user-welcome">
                                    <span class="welcome-text">Bienvenido,</span>
                                    <span class="user-name"><?= $_SESSION['identity']->empresa ?></span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </div>
                            <div class="profile-dropdown">
                                <a href="<?= base_url ?>usuario/perfil" class="dropdown-item">
                                    <i class="fas fa-user"></i> Mi Perfil
                                </a>
                                <a href="<?= base_url ?>usuario/cerrarSesion" class="dropdown-item logout">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </a>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <main style="margin-left: 280px; padding: 20px;">
        <!-- Tu contenido principal aquí -->
    </main>

    <script src="<?=base_url?>assets/css/layout/lateral.js"></script>

</body>
</html>
