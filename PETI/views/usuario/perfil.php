<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="<?= base_url ?>assets/stylee.css">
</head>
<body>
    <div class="contenedor-perfil">
        <h1>Perfil de <?= htmlspecialchars($usuario->empresa) ?></h1> <!-- Cambié 'nombre' a 'empresa' -->
        <div class="detalles-perfil">
            <!-- Mostrar la imagen de perfil si existe -->
            <?php if (!empty($usuario->imagen)): ?>
                <img src="<?= base_url ?>assets/img/perfiles/<?= htmlspecialchars($usuario->imagen) ?>" alt="Imagen de perfil de <?= htmlspecialchars($usuario->empresa) ?>" class="imagen-perfil" width="5%" height="5%">
            <?php else: ?>
                <img src="<?= base_url ?>assets/img/perfiles/default.png" alt="Imagen de perfil predeterminada" class="imagen-perfil" width="5%" height="5%">
            <?php endif; ?>

            <p><strong>Empresa:</strong> <?= htmlspecialchars($usuario->empresa) ?></p> <!-- Cambié 'nombre' a 'empresa' -->
            <p><strong>Email:</strong> <?= htmlspecialchars($usuario->correo) ?></p> <!-- Se mantiene correo -->
            <!-- Añade otros campos según tu tabla 'usuario', si es necesario -->
        </div>
        <div class="acciones-perfil">
            <a href="<?= base_url ?>usuario/editarPerfil">Editar Perfil</a>
            <a href="<?= base_url ?>usuario/cerrarSesion">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>
