<head>
    <link rel="stylesheet" href="<?=base_url?>assets/css/vision/index.css">
</head>


<div class="vision-container">
    <h1>VISIÓN</h1>

    <?php if (isset($_SESSION['vision_guardada'])): ?>
        <div class="alert <?= $_SESSION['vision_guardada'] == 'completado' ? 'alert-success' : 'alert-error' ?>">
            <?= $_SESSION['vision_guardada'] == 'completado' ? '✅ Visión guardada correctamente.' : '❌ Hubo un error al guardar la visión.' ?>
        </div>
        <?php unset($_SESSION['vision_guardada']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['vision_actualizada'])): ?>
        <div class="alert <?= $_SESSION['vision_actualizada'] == 'completado' ? 'alert-success' : 'alert-error' ?>">
            <?= $_SESSION['vision_actualizada'] == 'completado' ? '✅ Visión actualizada correctamente.' : '❌ Hubo un error al actualizar la visión.' ?>
        </div>
        <?php unset($_SESSION['vision_actualizada']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['vision_eliminada'])): ?>
        <div class="alert <?= $_SESSION['vision_eliminada'] == 'completado' ? 'alert-success' : 'alert-error' ?>">
            <?= $_SESSION['vision_eliminada'] == 'completado' ? '✅ Visión eliminada correctamente.' : '❌ Hubo un error al eliminar la visión.' ?>
        </div>
        <?php unset($_SESSION['vision_eliminada']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_vision'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['error_vision'] ?>
        </div>
        <?php unset($_SESSION['error_vision']); ?>
    <?php endif; ?>

    <p><strong>La VISIÓN</strong> es la imagen futura que se desea tener de la empresa u organización. Representa el estado ideal al que se quiere llegar.</p>
    <ul>
        <li>Debe ser inspiradora, motivadora y alcanzable.</li>
        <li>Orienta las decisiones estratégicas de la organización.</li>
        <li>Proyecta lo que la empresa quiere ser en el futuro.</li>
    </ul>
    <p>La visión es una declaración que indica hacia dónde se dirige la empresa a largo plazo y qué espera conseguir. Sirve como guía para el camino a seguir.</p>
    <p>Se expresa en términos claros y debe ser compartida por todos los miembros de la organización.</p>

    <h2>Ejemplos de visión</h2>
    <ul>
        <li><strong>Empresa tecnológica:</strong> Ser reconocidos como la empresa líder en soluciones innovadoras que transforman la manera en que las personas interactúan con la tecnología, mejorando su calidad de vida.</li>
        <li><strong>Cadena de restaurantes:</strong> Convertirnos en la opción preferida de alimentación en toda la región, siendo sinónimo de calidad, servicio excepcional y compromiso con nuestros clientes y comunidades.</li>
        <li><strong>Institución educativa:</strong> Ser reconocidos como el referente nacional en educación de calidad, formando profesionales íntegros y competitivos que contribuyan al desarrollo del país.</li>
    </ul>

    <?php if (isset($edicion) && $edicion && isset($visionActual)): ?>
        <div class="editar-vision-container">
            <h2>Editar Visión</h2>
            
            <form action="<?= base_url ?>vision/guardar" method="POST">
                <input type="hidden" name="id_vision" value="<?= $visionActual->id_vision ?>">
                
                <textarea name="vision" placeholder="Edita tu visión aquí..." required><?= htmlspecialchars($visionActual->vision) ?></textarea>
                
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <button type="submit">Guardar Cambios</button>
                    <a href="<?= base_url ?>vision/index" style="background: #e74c3c; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">Cancelar</a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <form action="<?= base_url ?>vision/guardar" method="POST">
            <label for="vision"><strong>Describe la visión de tu empresa:</strong></label>
            <textarea name="vision" placeholder="Escribe aquí la visión de tu empresa..." required></textarea>
            <button type="submit">Agregar Visión</button>
        </form>
    <?php endif; ?>

    <div class="vision-list">
        <h2>Mis Visiones Registradas</h2>
        <?php if ($visiones && $visiones->num_rows > 0): ?>
            <ul>
            <?php while($v = $visiones->fetch_object()): ?>
                <li>
                    <?= htmlspecialchars($v->vision) ?>
                    <a href="<?= base_url ?>vision/index&editar=<?= $v->id_vision ?>">Editar</a>
                    <a href="<?= base_url ?>vision/eliminar&id=<?= $v->id_vision ?>" onclick="return confirm('¿Estás seguro de eliminar esta visión?')">Eliminar</a>
                </li>
            <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No tienes visiones registradas aún.</p>
        <?php endif; ?>
    </div>
</div>