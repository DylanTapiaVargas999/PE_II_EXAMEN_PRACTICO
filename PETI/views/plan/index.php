<head>
    <link rel="stylesheet" href="<?=base_url?>assets/css/plan/index.css">
</head>

<?php if (isset($_SESSION['exito_plan'])): ?>
    <div class="alert alert-success"><?= $_SESSION['exito_plan'] ?></div>
    <?php unset($_SESSION['exito_plan']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_plan'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error_plan'] ?></div>
    <?php unset($_SESSION['error_plan']); ?>
<?php endif; ?>

<h2>Plan Estratégico: <?= $_SESSION['plan_codigo'] ?></h2>

<!-- Sección UEN -->
<div class="card mb-4">
    <div class="card-header">
        <h3>Unidades Estratégicas de Negocio (UEN)</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url ?>plan/guardarUen" method="POST">
            <?php if ($edicion['uen']): ?>
                <input type="hidden" name="id_uen" value="<?= $edicion['uen']->id_uen ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Nombre UEN:</label>
                <input type="text" name="uen" class="form-control" 
                       value="<?= $edicion['uen'] ? $edicion['uen']->uen : '' ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <?= $edicion['uen'] ? 'Actualizar' : 'Guardar' ?>
            </button>
            
            <?php if ($edicion['uen']): ?>
                <a href="<?= base_url ?>plan/index" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>
        </form>
        
        <hr>
        
        <h4>Listado de UENs</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($uen = $uenes->fetch_object()): ?>
                <tr>
                    <td><?= $uen->uen ?></td>
                    <td>
                        <a href="<?= base_url ?>plan/index&editar_uen=<?= $uen->id_uen ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="<?= base_url ?>plan/eliminarUen&id=<?= $uen->id_uen ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Sección Objetivos Generales -->
<div class="card mb-4">
    <div class="card-header">
        <h3>Objetivos Generales</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url ?>plan/guardarObjetivoGeneral" method="POST">
            <?php if ($edicion['general']): ?>
                <input type="hidden" name="id_general" value="<?= $edicion['general']->id_general ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Objetivo General:</label>
                <textarea name="objetivo_general" class="form-control" required><?= $edicion['general'] ? $edicion['general']->objetivo : '' ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <?= $edicion['general'] ? 'Actualizar' : 'Guardar' ?>
            </button>
            
            <?php if ($edicion['general']): ?>
                <a href="<?= base_url ?>plan/index" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>
        </form>
        
        <hr>
        
        <h4>Listado de Objetivos Generales</h4>
        <?php foreach ($objetivos as $general): ?>
            <div class="mb-3">
                <h5><?= $general['objetivo'] ?></h5>
                
                <div class="ml-4">
                    <form action="<?= base_url ?>plan/guardarObjetivoEspecifico" method="POST" class="mb-3">
                        <input type="hidden" name="id_general" value="<?= $general['id_general'] ?>">
                        
                        <?php if (isset($edicion['especifico']) && $edicion['especifico']->id_general == $general['id_general']): ?>
                            <input type="hidden" name="id_especifico" value="<?= $edicion['especifico']->id_especifico ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label>Objetivo Específico:</label>
                            <textarea name="objetivo_especifico" class="form-control" required>
                                <?= isset($edicion['especifico']) && $edicion['especifico']->id_general == $general['id_general'] ? $edicion['especifico']->objetivo : '' ?>
                            </textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-sm btn-primary">
                            <?= isset($edicion['especifico']) && $edicion['especifico']->id_general == $general['id_general'] ? 'Actualizar' : 'Agregar' ?>
                        </button>
                        
                        <?php if (isset($edicion['especifico']) && $edicion['especifico']->id_general == $general['id_general']): ?>
                            <a href="<?= base_url ?>plan/index" class="btn btn-sm btn-secondary">Cancelar</a>
                        <?php endif; ?>
                    </form>
                    
                    <ul>
                        <?php foreach ($general['especificos'] as $especifico): ?>
                            <li>
                                <?= $especifico['objetivo'] ?>
                                <a href="<?= base_url ?>plan/index&editar_especifico=<?= $especifico['id_especifico'] ?>&id_general=<?= $general['id_general'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="<?= base_url ?>plan/eliminarObjetivoEspecifico&id=<?= $especifico['id_especifico'] ?>&id_general=<?= $general['id_general'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="actions">
                    <a href="<?= base_url ?>plan/index&editar_general=<?= $general['id_general'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="<?= base_url ?>plan/eliminarObjetivoGeneral&id=<?= $general['id_general'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>