<head>
    <link rel="stylesheet" href="<?=base_url?>assets/css/mision/index.css">
</head>

<div class="mision-container">
    <h1>MISIÓN</h1>

    <?php if (isset($_SESSION['mision_guardada'])): ?>
        <div class="alert <?= $_SESSION['mision_guardada'] == 'completado' ? 'alert-success' : 'alert-error' ?>">
            <?= $_SESSION['mision_guardada'] == 'completado' ? '✅ Misión guardada correctamente.' : '❌ Hubo un error al guardar la misión.' ?>
        </div>
        <?php unset($_SESSION['mision_guardada']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['mision_actualizada'])): ?>
        <div class="alert <?= $_SESSION['mision_actualizada'] == 'completado' ? 'alert-success' : 'alert-error' ?>">
            <?= $_SESSION['mision_actualizada'] == 'completado' ? '✅ Misión actualizada correctamente.' : '❌ Hubo un error al actualizar la misión.' ?>
        </div>
        <?php unset($_SESSION['mision_actualizada']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['mision_eliminada'])): ?>
        <div class="alert <?= $_SESSION['mision_eliminada'] == 'completado' ? 'alert-success' : 'alert-error' ?>">
            <?= $_SESSION['mision_eliminada'] == 'completado' ? '✅ Misión eliminada correctamente.' : '❌ Hubo un error al eliminar la misión.' ?>
        </div>
        <?php unset($_SESSION['mision_eliminada']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_mision'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['error_mision'] ?>
        </div>
        <?php unset($_SESSION['error_mision']); ?>
    <?php endif; ?>

    <p><strong>La MISIÓN</strong> es la razón de ser de la empresa u organización. Describe la actividad y propósito fundamental en el mercado.</p>
    <ul>
        <li>Debe ser clara, concisa y compartida.</li>
        <li>Siempre orientada hacia el cliente, no solo al producto o servicio.</li>
        <li>Refleja el propósito fundamental de la empresa en el mercado.</li>
    </ul>
    <p>En términos generales, describe la actividad y razón de ser de la organización y contribuye como una referencia permanente en el proceso de planificación estratégica.</p>
    <p>Se expresa en una oración que define qué hace la empresa, por qué y para quién lo hace.</p>

    <h2>Ejemplos de misión</h2>
    <ul>
        <li><strong>Empresa de servicios:</strong> La gestión de servicios que contribuyen a la calidad de vida de las personas y generan valor para los grupos de interés.</li>
        <li><strong>Productora de café:</strong> Gracias a nuestro entusiamo, trabajo en equipo y valores, queremos deleitar a todos aquellos que, en el mundo aman la calidad de vida, a través del mejor café que la naturaleza pueda ofrecer, ensalzado por las mejores tecnologías, por la emoción y la imlicación intelectual que nacen de la búsqueda de lo bello en todo lo que hacemos.</li>
        <li><strong>Agencia de certificación:</strong> Dar a nuestros clientes avlro económico a través de la gestión de la Calidad, la Salud y la Seguridad, el Medio Ambiente y la Responsabildad Social de sus activos, proyectos, productos y sistemas, obteniendo como resultado la capacidad para lograr la reducción de riesgos y la mejora de los resultados.</li>
    </ul>

    <?php if (isset($edicion) && $edicion && isset($misionActual)): ?>
        <div class="editar-mision-container">
            <h2>Editar Misión</h2>
            
            <form action="<?= base_url ?>mision/guardar" method="POST">
                <input type="hidden" name="id_mision" value="<?= $misionActual->id_mision ?>">
                
                <textarea name="mision" placeholder="Edita tu misión aquí..." required><?= htmlspecialchars($misionActual->mision) ?></textarea>
                
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <button type="submit">Guardar Cambios</button>
                    <a href="<?= base_url ?>mision/index" style="background: #e74c3c; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">Cancelar</a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <form action="<?= base_url ?>mision/guardar" method="POST">
            <label for="mision"><strong>Describe la misión de tu empresa:</strong></label>
            <textarea name="mision" placeholder="Escribe aquí la misión de tu empresa..." required></textarea>
            <button type="submit">Agregar Misión</button>
        </form>
    <?php endif; ?>

    <div class="mision-list">
        <h2>Mis Misiones Registradas</h2>
        <?php if ($misiones && $misiones->num_rows > 0): ?>
            <ul>
            <?php while($m = $misiones->fetch_object()): ?>
                <li>
                    <?= htmlspecialchars($m->mision) ?>
                    <a href="<?= base_url ?>mision/index&editar=<?= $m->id_mision ?>">Editar</a>
                    <a href="<?= base_url ?>mision/eliminar&id=<?= $m->id_mision ?>" onclick="return confirm('¿Estás seguro de eliminar esta misión?')">Eliminar</a>
                </li>
            <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No tienes misiones registradas aún.</p>
        <?php endif; ?>
    </div>
</div>