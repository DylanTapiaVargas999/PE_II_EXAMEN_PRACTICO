<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>

<div class="container mt-4">

    <!-- Botón para abrir el modal de agregar producto -->
    <div class="mb-3 text-end">
        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">
            <i class="fas fa-plus"></i> Agregar Producto
        </button>
    </div>

    <!-- Modal Agregar Producto SOLO NOMBRE -->
    <div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-labelledby="modalAgregarProductoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="<?=base_url?>bcg/guardar" method="POST">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="modalAgregarProductoLabel">Agregar Nuevo Producto</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Nombre del Producto</label>
                <input type="text" name="nombre" class="form-control" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <?php if (isset($_SESSION['error_bcg'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error_bcg'] ?></div>
        <?php unset($_SESSION['error_bcg']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['exito_bcg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['exito_bcg'] ?></div>
        <?php unset($_SESSION['exito_bcg']); ?>
    <?php endif; ?>

    <h2 class="mb-4">Matriz BCG - Análisis Completo</h2>

    <!-- Formulario principal -->
    <form action="<?=base_url?>bcg/guardarTodo" method="POST" id="formBCG">
        <input type="hidden" name="codigo_plan" value="<?= $_SESSION['plan_codigo'] ?>">

        <!-- Pestañas -->
        <ul class="nav nav-tabs mb-4" id="bcgTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#prevision">Previsión Ventas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tcm">Tasas Crecimiento</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#demanda">Demanda Global</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#competidores">Competidores</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- PREVISIÓN DE VENTAS -->
            <div class="tab-pane fade show active" id="prevision">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>PRODUCTOS</th>
                                <th>VENTAS</th>
                                <th>% S/ TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalVentas = 0;
                            $productos->data_seek(0);
                            $i = 1;
                            while($prod = $productos->fetch_object()): 
                                $totalVentas += $prod->ventas ?? 0;
                            ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="productos[<?=$i?>][id]" value="<?=$prod->id_producto?>">
                                    <input type="text" name="productos[<?=$i?>][nombre]" class="form-control" 
                                           value="<?=htmlspecialchars($prod->nombre)?>" required>
                                </td>
                                <td>
                                    <input type="text" name="productos[<?=$i?>][ventas]" class="form-control ventas-input" 
                                           value="<?=number_format($prod->ventas ?? 0, 2, ',', '.')?>" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control porcentaje-ventas" readonly>
                                </td>
                            </tr>
                            <?php $i++; endwhile; ?>
                            <tr class="table-secondary">
                                <td><strong>TOTAL</strong></td>
                                <td><span id="total-ventas"><?=number_format($totalVentas, 2, ',', '.')?></span></td>
                                <td><span id="total-porcentaje">100%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TASAS DE CRECIMIENTO DEL MERCADO -->
            <div class="tab-pane fade" id="tcm">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>PERIODOS</th>
                                <?php for ($p = 1; $p <= $productos->num_rows; $p++): ?>
                                <th>Producto <?=$p?></th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($year = 1; $year <= 5; $year++): ?>
                            <tr>
                                <td><?=(2024+$year)?>-<?=(2025+$year)?></td>
                                <?php 
                                $productos->data_seek(0);
                                $i = 1;
                                while($prod = $productos->fetch_object()): 
                                ?>
                                <td>
                                    <input type="number" step="0.01" name="productos[<?=$i?>][tcm<?=$year?>]" 
                                           class="form-control tcm-input" 
                                           value="<?=$prod->{'tcm'.$year} ?? 0?>">
                                </td>
                                <?php $i++; endwhile; ?>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- DEMANDA GLOBAL SECTOR -->
            <div class="tab-pane fade" id="demanda">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th rowspan="2">AÑOS</th>
                                <th colspan="<?=$productos->num_rows?>" class="text-center">MERCADOS</th>
                            </tr>
                            <tr>
                                <?php for ($p = 1; $p <= $productos->num_rows; $p++): ?>
                                <th>Producto <?=$p?></th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($year = 2025; $year <= 2030; $year++): ?>
                            <tr>
                                <td><?=$year?></td>
                                <?php 
                                $productos->data_seek(0);
                                $i = 1;
                                while($prod = $productos->fetch_object()): 
                                ?>
                                <td>
                                    <?php if ($year == 2025): ?>
                                        <input type="text" name="productos[<?=$i?>][edgs]" 
                                               class="form-control demanda-input" 
                                               value="<?=number_format($prod->EDGS ?? 0, 2, ',', '.')?>"> 
                                    <?php else: ?>
                                        <input type="text" class="form-control demanda-calculada" 
                                               data-producto="<?=$i?>" data-year="<?=$year?>" readonly>
                                    <?php endif; ?>
                                </td>
                                <?php $i++; endwhile; ?>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- COMPETIDORES -->
            <div class="tab-pane fade" id="competidores">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <?php for ($p = 1; $p <= $productos->num_rows; $p++): ?>
                                <th colspan="2">Producto <?=$p?></th>
                                <?php endfor; ?>
                            </tr>
                            <tr>
                                <?php for ($p = 1; $p <= $productos->num_rows; $p++): ?>
                                <th>Competidor</th>
                                <th>Ventas</th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($comp = 1; $comp <= 9; $comp++): ?>
                            <tr>
                                <?php 
                                $productos->data_seek(0);
                                $i = 1;
                                while($prod = $productos->fetch_object()): 
                                ?>
                                <td>CP<?=$i?>-<?=$comp?></td>
                                <td>
                                    <input type="text" name="productos[<?=$i?>][cp<?=$comp?>]" 
                                           class="form-control competidor-input" 
                                           value="<?=number_format($prod->{'CP_'.$comp} ?? 0, 2, ',', '.')?>"> 
                                </td>
                                <?php $i++; endwhile; ?>
                            </tr>
                            <?php endfor; ?>
                            <tr class="table-secondary">
                                <?php for ($p = 1; $p <= $productos->num_rows; $p++): ?>
                                <td><strong>Mayor</strong></td>
                                <td><span class="mayor-competidor" data-producto="<?=$p?>">0</span></td>
                                <?php endfor; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Guardar Todos los Datos
            </button>
        </div>
    </form>
</div>

<!-- Tabla de Resultados BCG -->
<div class="container mt-5">
    <h3 class="mb-3">Resumen BCG</h3>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>BCG</th>
                    <?php 
                    $productos->data_seek(0);
                    while($prod = $productos->fetch_object()): 
                    ?>
                        <th><?= htmlspecialchars($prod->nombre) ?></th>
                    <?php endwhile; ?>
                </tr>
            </thead>
            <tbody>
                <!-- Fila TCM (Tasa de Crecimiento del Mercado) -->
                <tr>
                    <td><strong>TCM</strong></td>
                    <?php
                    $productos->data_seek(0);
                    $tcm_values = [];
                    while($prod = $productos->fetch_object()): 
                        // Calcular promedio de tasas de crecimiento (en porcentaje)
                        $sumaTasas = ($prod->tcm1 + $prod->tcm2 + $prod->tcm3 + $prod->tcm4 + $prod->tcm5);
                        $tcm = ($sumaTasas / 5);
                        $tcm_values[] = $tcm;
                    ?>
                        <td class="tcm-value"><?= number_format($tcm, 2) ?>%</td>
                    <?php endwhile; ?>
                </tr>
                
                <!-- Fila PRM (Participación Relativa de Mercado) -->
                <tr>
                    <td><strong>PRM</strong></td>
                    <?php
                    // Primero encontrar el líder absoluto del mercado (el competidor más fuerte de todos)
                    $productos->data_seek(0);
                    $max_ventas_lider = 0;
                    $prm_values = [];
                    
                    while($prod = $productos->fetch_object()) {
                        $max_competidor = max([
                            $prod->CP_1, $prod->CP_2, $prod->CP_3, 
                            $prod->CP_4, $prod->CP_5, $prod->CP_6,
                            $prod->CP_7, $prod->CP_8, $prod->CP_9
                        ]);
                        if ($max_competidor > $max_ventas_lider) {
                            $max_ventas_lider = $max_competidor;
                        }
                    }
                    
                    // Calcular PRM para cada producto
                    $productos->data_seek(0);
                    while($prod = $productos->fetch_object()): 
                        $prm = ($max_ventas_lider > 0) ? ($prod->ventas / $max_ventas_lider) : 0;
                        $prm_values[] = $prm;
                    ?>
                        <td class="prm-value"><?= number_format($prm, 2) ?></td>
                    <?php endwhile; ?>
                </tr>
                
                <!-- Fila % S/VTAS (Porcentaje sobre Ventas Totales) -->
                <tr>
                    <td><strong>% S/VTAS</strong></td>
                    <?php
                    $productos->data_seek(0);
                    $totalVentas = 0;
                    while($prod = $productos->fetch_object()) {
                        $totalVentas += $prod->ventas;
                    }
                    
                    $productos->data_seek(0);
                    while($prod = $productos->fetch_object()): 
                        $porcentaje = ($totalVentas > 0) ? ($prod->ventas / $totalVentas * 100) : 0;
                    ?>
                        <td><?= number_format($porcentaje, 0) ?>%</td>
                    <?php endwhile; ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Gráfico de Posicionamiento BCG Mejorado -->
<div class="container mt-4 mb-5">
    <h4 class="mb-3">Posicionamiento en Matriz BCG</h4>
    <div style="width: 100%; height: 500px;">
        <canvas id="bcgChart"></canvas>
    </div>
    
    <div class="mt-3">
        <button class="btn btn-sm btn-outline-secondary" id="exportChartBtn">
            <i class="fas fa-download"></i> Exportar Gráfico
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar pestañas
    var bcgTabs = new bootstrap.Tab(document.querySelector('#bcgTabs .nav-link.active'));
    bcgTabs.show();

    // Función para formatear números
    function formatNumber(value) {
        return parseFloat(value.toString().replace(/\./g, '').replace(',', '.')) || 0;
    }

    // Función para mostrar números formateados
    function displayNumber(value, decimals = 2) {
        return value.toLocaleString('es-ES', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }

    // Validación de inputs numéricos
    function validarNumeros(input) {
        let value = input.value.replace(/[^0-9,.]/g, '');
        value = value.replace(/\./g, '').replace(',', '.');
        input.value = value.includes('.') ? 
            value.substring(0, value.indexOf('.') + 3) : 
            value;
    }

    // Calcular porcentajes de ventas
    function calcularPorcentajesVentas() {
        let total = 0;
        const ventasInputs = document.querySelectorAll('.ventas-input');
        
        // Calcular total
        ventasInputs.forEach(input => {
            const valor = formatNumber(input.value);
            total += valor;
        });
        
        // Actualizar total
        document.getElementById('total-ventas').textContent = displayNumber(total);
        
        // Calcular y actualizar porcentajes individuales
        ventasInputs.forEach(input => {
            const valor = formatNumber(input.value);
            const porcentaje = total > 0 ? (valor / total * 100) : 0;
            const porcentajeInput = input.closest('tr').querySelector('.porcentaje-ventas');
            porcentajeInput.value = porcentaje.toFixed(2) + '%';
        });
    }

    // Calcular demanda global para años futuros
    function calcularDemandaGlobal() {
        const demandaBaseInputs = document.querySelectorAll('.demanda-input');
        
        demandaBaseInputs.forEach(input => {
            const productoIndex = input.name.match(/\[(\d+)\]/)[1];
            const valorBase = formatNumber(input.value);
            
            // Calcular para cada año futuro (2026-2030)
            for (let year = 2026; year <= 2030; year++) {
                const tasaSelector = `input[name="productos[${productoIndex}][tcm${year-2025}]"]`;
                const tasaInput = document.querySelector(tasaSelector);
                const tasa = parseFloat(tasaInput?.value) || 0;
                
                const crecimiento = 1 + (tasa / 100);
                const años = year - 2025;
                const valorCalculado = valorBase * Math.pow(crecimiento, años);
                
                const celda = document.querySelector(`.demanda-calculada[data-producto="${productoIndex}"][data-year="${year}"]`);
                if (celda) {
                    celda.value = displayNumber(valorCalculado);
                }
            }
        });
    }

    // Calcular mayor competidor por producto
    function calcularMayorCompetidor() {
        for (let p = 1; p <= <?=$productos->num_rows?>; p++) {
            let mayor = 0;
            document.querySelectorAll(`input[name^="productos[${p}][cp"]`).forEach(input => {
                const valor = formatNumber(input.value);
                if (valor > mayor) mayor = valor;
            });
            
            const span = document.querySelector(`.mayor-competidor[data-producto="${p}"]`);
            if (span) {
                span.textContent = displayNumber(mayor);
            }
        }
    }

    // Actualizar matriz BCG
    function actualizarMatrizBCG() {
        // Recolectar datos de productos
        const productos = [];
        const ventasInputs = document.querySelectorAll('.ventas-input');
        
        // Calcular total ventas
        let totalVentas = 0;
        ventasInputs.forEach(input => {
            totalVentas += formatNumber(input.value);
        });
        
        // Calcular PRM (necesitamos el mayor competidor)
        let maxCompetidor = 0;
        for (let p = 1; p <= <?=$productos->num_rows?>; p++) {
            let mayor = 0;
            document.querySelectorAll(`input[name^="productos[${p}][cp"]`).forEach(input => {
                const valor = formatNumber(input.value);
                if (valor > mayor) mayor = valor;
            });
            if (mayor > maxCompetidor) maxCompetidor = mayor;
        }
        
        // Procesar cada producto
        ventasInputs.forEach((input, index) => {
            const productoIndex = input.name.match(/\[(\d+)\]/)[1];
            const ventas = formatNumber(input.value);
            const nombre = document.querySelector(`input[name="productos[${productoIndex}][nombre]"]`).value;
            
            // Calcular TCM (promedio de los 5 años)
            let sumaTasas = 0;
            for (let year = 1; year <= 5; year++) {
                const tasa = parseFloat(document.querySelector(`input[name="productos[${productoIndex}][tcm${year}]"]`).value) || 0;
                sumaTasas += tasa;
            }
            const tcm = sumaTasas / 5;
            
            // Calcular PRM
            const prm = maxCompetidor > 0 ? (ventas / maxCompetidor) : 0;
            
            // Calcular % sobre ventas totales
            const porcentajeVentas = totalVentas > 0 ? (ventas / totalVentas * 100) : 0;
            
            productos.push({
                index: index + 1,
                nombre: nombre,
                tcm: tcm,
                prm: prm,
                porcentajeVentas: porcentajeVentas
            });
            
            // Actualizar valores en la tabla
            const tcmCell = document.querySelectorAll('.tcm-value')[index];
            const prmCell = document.querySelectorAll('.prm-value')[index];
            
            if (tcmCell) tcmCell.textContent = tcm.toFixed(2) + '%';
            if (prmCell) prmCell.textContent = prm.toFixed(2);
        });
        
        // Actualizar gráfico
        actualizarGraficoBCG(productos);
    }
    
    // Gráfico BCG mejorado con zoom
    let bcgChart;
    function actualizarGraficoBCG(productos) {
        const ctx = document.getElementById('bcgChart').getContext('2d');
        
        // Iconos para cada categoría (usando Font Awesome)
        const icons = {
            star: '⭐',
            cow: '🐄',
            question: '❓',
            dog: '🐕'
        };
        
        // Datasets para cada cuadrante con mejoras visuales
        const datasets = [
            { 
                label: 'Estrellas '+icons.star, 
                backgroundColor: 'rgba(0, 123, 255, 0.7)', 
                borderColor: 'rgba(0, 123, 255, 1)', 
                borderWidth: 2,
                data: [] 
            },
            { 
                label: 'Interrogantes '+icons.question, 
                backgroundColor: 'rgba(23, 162, 184, 0.7)', 
                borderColor: 'rgba(23, 162, 184, 1)',
                borderWidth: 2,
                data: [] 
            },
            { 
                label: 'Vacas '+icons.cow, 
                backgroundColor: 'rgba(255, 193, 7, 0.7)', 
                borderColor: 'rgba(255, 193, 7, 1)',
                borderWidth: 2,
                data: [] 
            },
            { 
                label: 'Perros '+icons.dog, 
                backgroundColor: 'rgba(220, 53, 69, 0.7)', 
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 2,
                data: [] 
            }
        ];
        
        // Encontrar máximos para escalado con márgenes
        const maxTCM = Math.max(...productos.map(p => p.tcm), 10) * 1.2;
        const maxPRM = Math.max(...productos.map(p => p.prm), 1) * 1.2;
        
        // Procesar cada producto
        productos.forEach(producto => {
            // Determinar cuadrante
            let cuadrante;
            if (producto.tcm >= 10 && producto.prm >= 1) {
                cuadrante = 0; // Estrella
            } else if (producto.tcm >= 10) {
                cuadrante = 1; // Interrogante
            } else if (producto.prm >= 1) {
                cuadrante = 2; // Vaca
            } else {
                cuadrante = 3; // Perro
            }
            
            // Tamaño de la burbuja basado en % de ventas (mínimo 15, máximo 40)
            const radius = Math.max(15, Math.min(40, producto.porcentajeVentas / 4));
            
            datasets[cuadrante].data.push({
                x: producto.prm,
                y: producto.tcm,
                r: radius,
                label: `${producto.index}. ${producto.nombre}`,
                tcm: producto.tcm,
                prm: producto.prm,
                porcentaje: producto.porcentajeVentas.toFixed(1)
            });
        });
        
        // Destruir gráfico anterior si existe
        if (bcgChart) {
            bcgChart.destroy();
        }
        
        // Crear nuevo gráfico con zoom
        bcgChart = new Chart(ctx, {
            type: 'bubble',
            data: { datasets: datasets.filter(ds => ds.data.length > 0) },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    zoom: {
                        zoom: {
                            wheel: {
                                enabled: true,
                            },
                            pinch: {
                                enabled: true
                            },
                            mode: 'xy',
                        },
                        pan: {
                            enabled: true,
                            mode: 'xy'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const data = context.raw;
                                return [
                                    data.label,
                                    `PRM: ${data.prm.toFixed(2)} (${data.prm >= 1 ? 'Alta' : 'Baja'})`,
                                    `TCM: ${data.tcm.toFixed(2)}% (${data.tcm >= 10 ? 'Alto' : 'Bajo'})`,
                                    `Participación en ventas: ${data.porcentaje}%`
                                ];
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 12
                            },
                            padding: 20
                        }
                    },
                    annotation: {
                        annotations: {
                            lineaCentralX: {
                                type: 'line',
                                xMin: 1,
                                xMax: 1,
                                borderColor: 'rgba(0,0,0,0.8)',
                                borderWidth: 3,
                                borderDash: [8, 6],
                                label: {
                                    content: 'PRM = 1',
                                    enabled: true,
                                    position: 'start',
                                    backgroundColor: 'rgba(0,0,0,0.7)',
                                    color: '#fff',
                                    font: { weight: 'bold' }
                                }
                            },
                            lineaCentralY: {
                                type: 'line',
                                yMin: 10,
                                yMax: 10,
                                borderColor: 'rgba(0,0,0,0.8)',
                                borderWidth: 3,
                                borderDash: [8, 6],
                                label: {
                                    content: 'TCM = 10%',
                                    enabled: true,
                                    position: 'end',
                                    backgroundColor: 'rgba(0,0,0,0.7)',
                                    color: '#fff',
                                    font: { weight: 'bold' }
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom',
                        min: 0,
                        max: maxPRM,
                        title: {
                            display: true,
                            text: 'Participación Relativa de Mercado (PRM) - Escala Logarítmica',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(1);
                            }
                        },
                        grid: {
                            color: function(context) {
                                if (context.tick.value === 1) {
                                    return 'rgba(0, 0, 0, 0.7)';
                                }
                                return 'rgba(0, 0, 0, 0.1)';
                            },
                            lineWidth: function(context) {
                                if (context.tick.value === 1) {
                                    return 2;
                                }
                                return 1;
                            }
                        }
                    },
                    y: {
                        min: 0,
                        max: maxTCM,
                        title: {
                            display: true,
                            text: 'Tasa de Crecimiento del Mercado (TCM %)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: function(context) {
                                if (context.tick.value === 10) {
                                    return 'rgba(0, 0, 0, 0.7)';
                                }
                                return 'rgba(0, 0, 0, 0.1)';
                            },
                            lineWidth: function(context) {
                                if (context.tick.value === 10) {
                                    return 2;
                                }
                                return 1;
                            }
                        }
                    }
                },
                onClick: (e, activeEls) => {
                    if (activeEls.length > 0) {
                        const datasetIndex = activeEls[0].datasetIndex;
                        const dataIndex = activeEls[0].index;
                        const value = bcgChart.data.datasets[datasetIndex].data[dataIndex];
                        mostrarAnalisisProducto(value);
                    }
                }
            }
        });
        
        // Agregar controles de zoom
        const zoomControls = `
            <div class="btn-group mt-3">
                <button class="btn btn-sm btn-outline-primary" id="zoomInBtn">
                    <i class="fas fa-search-plus"></i> Zoom In
                </button>
                <button class="btn btn-sm btn-outline-primary" id="zoomOutBtn">
                    <i class="fas fa-search-minus"></i> Zoom Out
                </button>
                <button class="btn btn-sm btn-outline-secondary" id="resetZoomBtn">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        `;
        document.querySelector('#bcgChart').parentNode.insertAdjacentHTML('beforeend', zoomControls);
        
        // Event listeners para controles de zoom
        document.getElementById('zoomInBtn').addEventListener('click', () => {
            bcgChart.zoom(1.2);
        });
        
        document.getElementById('zoomOutBtn').addEventListener('click', () => {
            bcgChart.zoom(0.8);
        });
        
        document.getElementById('resetZoomBtn').addEventListener('click', () => {
            bcgChart.resetZoom();
        });
        
        // Agregar leyenda BCG
        agregarLeyendaBCG();
    }
    
    // Función para mostrar análisis del producto
    function mostrarAnalisisProducto(producto) {
        let estrategia = '';
        let icono = '';
        
        if (producto.tcm >= 10 && producto.prm >= 1) {
            estrategia = 'ESTRELLA: Producto con alto crecimiento y alta participación. Requiere inversión para mantener su posición.';
            icono = '⭐';
        } else if (producto.tcm >= 10) {
            estrategia = 'INTERROGANTE: Alto crecimiento pero baja participación. Evaluar si vale la pena invertir para convertirlo en estrella.';
            icono = '❓';
        } else if (producto.prm >= 1) {
            estrategia = 'VACA: Bajo crecimiento pero alta participación. Genera efectivo que puede usarse para otros productos.';
            icono = '🐄';
        } else {
            estrategia = 'PERRO: Bajo crecimiento y baja participación. Considerar desinversión o eliminación.';
            icono = '🐕';
        }
        
        const html = `
            <div class="modal fade" id="productoModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${icono} ${producto.label}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Estrategia recomendada:</strong> ${estrategia}</p>
                            <ul>
                                <li>Participación relativa: ${producto.prm.toFixed(2)}</li>
                                <li>Tasa crecimiento: ${producto.tcm.toFixed(2)}%</li>
                                <li>Participación en ventas: ${producto.porcentaje}%</li>
                            </ul>
                            <div class="alert alert-info">
                                <strong>Acciones sugeridas:</strong> ${generarAccionesRecomendadas(producto)}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Agregar modal al DOM si no existe
        if (!document.getElementById('productoModal')) {
            document.body.insertAdjacentHTML('beforeend', html);
        } else {
            document.getElementById('productoModal').outerHTML = html;
        }
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('productoModal'));
        modal.show();
    }

    // Generar acciones recomendadas basadas en la posición BCG
    function generarAccionesRecomendadas(producto) {
        let acciones = [];
        
        if (producto.tcm >= 10 && producto.prm >= 1) {
            // Estrella
            acciones.push('Invertir en desarrollo y mejora');
            acciones.push('Mantener o aumentar participación de mercado');
            acciones.push('Proteger contra competidores');
        } else if (producto.tcm >= 10) {
            // Interrogante
            acciones.push('Evaluar potencial de crecimiento');
            acciones.push('Decidir si invertir para ganar participación o retirarse');
            acciones.push('Considerar alianzas estratégicas');
        } else if (producto.prm >= 1) {
            // Vaca
            acciones.push('Optimizar costos para maximizar flujo de caja');
            acciones.push('Invertir lo mínimo necesario para mantener posición');
            acciones.push('Usar excedentes para financiar otros productos');
        } else {
            // Perro
            acciones.push('Considerar desinversión o eliminación');
            acciones.push('Reducir costos al mínimo');
            acciones.push('Evaluar nichos de mercado especializados');
        }
        
        return acciones.map(a => `• ${a}`).join('<br>');
    }

    // Agregar leyenda explicativa
    function agregarLeyendaBCG() {
        // Verificar si ya existe la leyenda para no duplicarla
        if (document.getElementById('leyendaBCG')) {
            return;
        }
        
        const leyendaHTML = `
            <div class="card mt-4" id="leyendaBCG">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Leyenda y Guía de la Matriz BCG</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><span class="badge bg-primary">⭐ Estrellas</span></h6>
                            <p>Alto crecimiento, alta participación. Son líderes en mercados en crecimiento. Requieren inversión para mantener su posición.</p>
                            
                            <h6><span class="badge bg-info">❓ Interrogantes</span></h6>
                            <p>Alto crecimiento, baja participación. Requieren análisis para decidir si invertir o retirarse.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><span class="badge bg-warning text-dark">🐄 Vacas</span></h6>
                            <p>Bajo crecimiento, alta participación. Generan más efectivo del que consumen. Son la "base" del negocio.</p>
                            
                            <h6><span class="badge bg-danger">🐕 Perros</span></h6>
                            <p>Bajo crecimiento, baja participación. Generalmente generan poco o ningún beneficio neto.</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6>Tamaño de las burbujas</h6>
                            <p>El tamaño de cada burbuja representa la participación porcentual del producto en las ventas totales.</p>
                            
                            <h6>Líneas divisorias</h6>
                            <ul>
                                <li><strong>Eje X (PRM):</strong> 1.0 - Líder relativo de mercado</li>
                                <li><strong>Eje Y (TCM):</strong> 10% - Tasa de crecimiento promedio</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.querySelector('#bcgChart').parentNode.insertAdjacentHTML('afterend', leyendaHTML);
    }

    // Exportar gráfico
    document.getElementById('exportChartBtn').addEventListener('click', function() {
        if (bcgChart) {
            const link = document.createElement('a');
            link.download = 'matriz-bcg.png';
            link.href = document.getElementById('bcgChart').toDataURL('image/png');
            link.click();
        }
    });

    // Event listeners para inputs
    document.querySelectorAll('.ventas-input').forEach(input => {
        input.addEventListener('input', function() {
            validarNumeros(this);
            calcularPorcentajesVentas();
            actualizarMatrizBCG();
        });
    });

    document.querySelectorAll('.demanda-input').forEach(input => {
        input.addEventListener('input', function() {
            validarNumeros(this);
            calcularDemandaGlobal();
        });
    });

    document.querySelectorAll('.tcm-input').forEach(input => {
        input.addEventListener('input', function() {
            calcularDemandaGlobal();
            actualizarMatrizBCG();
        });
    });

    document.querySelectorAll('.competidor-input').forEach(input => {
        input.addEventListener('input', function() {
            validarNumeros(this);
            calcularMayorCompetidor();
            actualizarMatrizBCG();
        });
    });

    // Validación del formulario
    document.getElementById('formBCG').addEventListener('submit', function(e) {
        // Convertir todos los valores numéricos al formato correcto antes de enviar
        document.querySelectorAll('.ventas-input, .demanda-input, .competidor-input').forEach(input => {
            const valor = input.value.replace(/\./g, '').replace(',', '.');
            input.value = valor;
        });
        
        // Validar que al menos un producto tenga datos
        let tieneDatos = false;
        document.querySelectorAll('.ventas-input').forEach(input => {
            if (parseFloat(input.value) > 0) tieneDatos = true;
        });
        
        if (!tieneDatos) {
            e.preventDefault();
            alert('Debe ingresar datos para al menos un producto');
            return false;
        }
        
        return true;
    });

    // Inicializar cálculos
    calcularPorcentajesVentas();
    calcularDemandaGlobal();
    calcularMayorCompetidor();
    actualizarMatrizBCG();
});
</script>