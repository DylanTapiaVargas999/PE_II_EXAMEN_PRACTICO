<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-4">
    <!-- Mensajes de alerta -->
    <?php if (isset($_SESSION['error_bcg'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error_bcg'] ?></div>
        <?php unset($_SESSION['error_bcg']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['exito_bcg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['exito_bcg'] ?></div>
        <?php unset($_SESSION['exito_bcg']); ?>
    <?php endif; ?>
    
    <h2 class="mb-4">Gestión de Productos y Matriz BCG</h2>
    
    <!-- Formulario para agregar/editar producto -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <?= isset($productoEditar) ? 'Editar Producto' : 'Agregar Nuevo Producto' ?>
        </div>
        <div class="card-body">
            <form action="<?=base_url?>bcg/guardar" method="POST">
                <input type="hidden" name="id_producto" value="<?= $productoEditar ? $productoEditar->id_producto : '' ?>">
                
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label">Nombre del Producto</label>
                        <input type="text" name="nombre" class="form-control" 
                               value="<?= isset($productoEditar) ? htmlspecialchars($productoEditar->nombre) : '' ?>" 
                               required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <?= isset($productoEditar) ? 'Actualizar' : 'Guardar' ?>
                        </button>
                        <?php if (isset($productoEditar)): ?>
                            <a href="<?=base_url?>bcg/index" class="btn btn-outline-secondary">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Sección BCG (visible solo cuando se edita un producto) -->
                <?php if (isset($productoEditar)): ?>
                <div class="border-top pt-3 mt-3">
                    <h5>Datos para Matriz BCG</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Ventas (S/)</label>
                            <input type="text" name="ventas" class="form-control" 
                                   value="<?= isset($productoEditar->ventas) ? number_format($productoEditar->ventas, 2, ',', '.') : '' ?>">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Tasa Crecimiento Año 1 (%)</label>
                            <input type="number" step="0.01" name="tcm1" class="form-control" 
                                   value="<?= isset($productoEditar->tcm1) ? $productoEditar->tcm1 : '' ?>">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Demanda Global Sector (S/)</label>
                            <input type="text" name="edgs" class="form-control" 
                                   value="<?= isset($productoEditar->EDGS) ? number_format($productoEditar->EDGS, 2, ',', '.') : '' ?>">
                        </div>
                        
                        <!-- Competidores -->
                        <div class="col-12 mt-3">
                            <h6>Competidores</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Competidor</th>
                                            <th>Ventas (S/)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 1; $i <= 9; $i++): ?>
                                        <tr>
                                            <td>Competidor <?= $i ?></td>
                                            <td>
                                                <input type="text" name="cp<?= $i ?>" class="form-control" 
                                                       value="<?= isset($productoEditar->{'CP_'.$i}) ? number_format($productoEditar->{'CP_'.$i}, 2, ',', '.') : '' ?>">
                                            </td>
                                        </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <!-- Lista de productos -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Lista de Productos
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th class="text-center">Estado BCG</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Reiniciar el puntero para poder recorrer los resultados nuevamente
                        $productos->data_seek(0); 
                        while($prod = $productos->fetch_object()): 
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($prod->nombre) ?></td>
                            <td class="text-center">
                                <?php if($prod->completado): ?>
                                    <span class="badge bg-success">Completo</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="<?=base_url?>bcg/index?editar=<?=$prod->id_producto?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="<?=base_url?>bcg/eliminar/<?=$prod->id_producto?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Matriz BCG Completa -->
    <?php 
    // Verificar si hay productos para mostrar la matriz
    $productos->data_seek(0);
    if ($productos->num_rows > 0): 
    ?>
    <div class="card">
        <div class="card-header bg-primary text-white">
            Matriz BCG Completa
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-4" id="bcgTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="resumen-tab" data-bs-toggle="tab" data-bs-target="#resumen" type="button">
                        Resumen
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="detalle-tab" data-bs-toggle="tab" data-bs-target="#detalle" type="button">
                        Detalle Completo
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="bcgTabContent">
                <!-- Resumen BCG -->
                <div class="tab-pane fade show active" id="resumen" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th>Ventas</th>
                                    <th>% Participación</th>
                                    <th>Tasa Crecimiento</th>
                                    <th>Posición BCG</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $productos->data_seek(0);
                                $totalVentas = 0;
                                $productosArray = [];
                                
                                // Calcular total de ventas primero
                                while($p = $productos->fetch_object()) {
                                    if ($p->completado) {
                                        $totalVentas += $p->ventas;
                                        $productosArray[] = $p;
                                    }
                                }
                                
                                // Mostrar productos completos
                                foreach ($productosArray as $prod): 
                                    $porcentaje = $totalVentas > 0 ? ($prod->ventas / $totalVentas * 100) : 0;
                                    $tasaCrecimiento = $prod->tcm1; // Puedes calcular un promedio de las tasas
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($prod->nombre) ?></td>
                                    <td class="text-end">S/ <?= number_format($prod->ventas, 2) ?></td>
                                    <td class="text-end"><?= number_format($porcentaje, 2) ?>%</td>
                                    <td class="text-end"><?= number_format($tasaCrecimiento, 2) ?>%</td>
                                    <td class="text-center">
                                        <?php
                                        // Determinar posición en matriz BCG (ejemplo básico)
                                        if ($tasaCrecimiento > 10 && $porcentaje > 15) {
                                            echo '<span class="badge bg-success">Estrella</span>';
                                        } elseif ($tasaCrecimiento > 10) {
                                            echo '<span class="badge bg-info">Interrogante</span>';
                                        } elseif ($porcentaje > 15) {
                                            echo '<span class="badge bg-warning">Vaca</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">Perro</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Detalle Completo -->
                <div class="tab-pane fade" id="detalle" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2">Producto</th>
                                    <th colspan="5" class="text-center">Tasas de Crecimiento (%)</th>
                                    <th colspan="9" class="text-center">Competidores (Ventas S/)</th>
                                </tr>
                                <tr>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <th>Año <?= $i ?></th>
                                    <?php endfor; ?>
                                    
                                    <?php for ($i = 1; $i <= 9; $i++): ?>
                                    <th>CP<?= $i ?></th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productosArray as $prod): ?>
                                <tr>
                                    <td><?= htmlspecialchars($prod->nombre) ?></td>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <td class="text-end">
                                        <?= isset($prod->{'tcm'.$i}) ? number_format($prod->{'tcm'.$i}, 2) : '0.00' ?>
                                    </td>
                                    <?php endfor; ?>
                                    
                                    <?php for ($i = 1; $i <= 9; $i++): ?>
                                    <td class="text-end">
                                        <?= isset($prod->{'CP_'.$i}) ? number_format($prod->{'CP_'.$i}, 2) : '0.00' ?>
                                    </td>
                                    <?php endfor; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Font Awesome para iconos -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar pestañas
    var bcgTabs = new bootstrap.Tab(document.querySelector('#bcgTabs .nav-link.active'));
    bcgTabs.show();
    
    // Formatear inputs numéricos
    document.querySelectorAll('input[type="text"][name*="ventas"], input[name*="cp"]').forEach(input => {
        input.addEventListener('blur', function() {
            let value = this.value.replace(/[^0-9,]/g, '');
            if (value) {
                value = parseFloat(value.replace(',', '.')).toFixed(2);
                this.value = value.replace('.', ',');
            }
        });
    });
    
    // Validación antes de enviar
    document.querySelector('form').addEventListener('submit', function(e) {
        let nombre = document.querySelector('input[name="nombre"]').value.trim();
        if (!nombre) {
            e.preventDefault();
            alert('El nombre del producto es obligatorio');
        }
    });
});
</script>