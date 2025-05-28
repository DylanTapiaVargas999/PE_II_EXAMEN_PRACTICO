<head>
    <!-- Otros enlaces y metadatos -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<?php if (isset($_SESSION['identity'])): ?>
    <div class="container mt-4">
        <h2 class="mb-4">Planes Estratégicos</h2>

        <!-- Mensajes de sesión -->
        <?php if (isset($_SESSION['plan_guardado'])): ?>
            <div class="alert alert-<?= $_SESSION['plan_guardado'] == 'completado' ? 'success' : 'danger' ?>">
                <?= $_SESSION['plan_guardado'] == 'completado' ? 'Plan guardado correctamente.' : 'Error al guardar el plan.' ?>
            </div>
            <?php unset($_SESSION['plan_guardado']); ?>
        <?php endif; ?>

        <!-- Botón para cerrar sesión -->
        <div class="mb-4">
            <a href="<?= base_url ?>usuario/cerrarSesion" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <!-- Formulario para crear o editar un plan -->
        <form action="<?= base_url ?>planEstrategico/guardar" method="POST" class="mb-4">
            <div class="form-group mb-2">
                <label for="titulo">Título del Plan</label>
                <input type="text" name="titulo" class="form-control" value="<?= isset($planData) ? $planData->titulo : '' ?>" required>
            </div>

            <?php if (isset($planData)): ?>
                <!-- Campo oculto para editar un plan -->
                <input type="hidden" name="id" value="<?= $planData->id ?>">
                <!-- Campo oculto para el código, ya que no debe cambiar -->
                <input type="hidden" name="codigo" value="<?= $planData->codigo ?>">
            <?php endif; ?>

            <button type="submit" class="btn btn-primary"><?= isset($planData) ? 'Actualizar Plan' : 'Guardar Plan' ?></button>
        </form>

        <!-- Listado de planes -->
        <h4>Listado de Planes</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $plan = new PlanEstrategico();
                $planes = $plan->obtenerTodosPorUsuario($_SESSION['identity']->id);
                while ($p = $planes->fetch_object()):
                ?>
                    <tr>
                        <td><?= $p->id ?></td>
                        <td><?= $p->codigo ?></td>
                        <td><?= $p->titulo ?></td>
                        <td>
                            <a href="<?= base_url ?>planEstrategico/editar&id=<?= $p->id ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= base_url ?>planEstrategico/eliminar&id=<?= $p->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este plan?')">Eliminar</a>
                            <a href="<?= base_url ?>planEstrategico/seleccionar&id=<?= $p->id ?>" class="btn btn-success btn-sm">Seleccionar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="container mt-5">
        <div class="alert alert-warning">Debes iniciar sesión para ver esta sección.</div>
    </div>
<?php endif; ?>
