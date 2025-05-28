<head>
    <link rel="stylesheet" href="<?=base_url?>assets/css/pestel/index.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('resultadosChart').getContext('2d');
            let chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Porcentaje de impacto',
                        data: [],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Porcentaje (%)'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Impacto de Factores en la Empresa',
                            font: {
                                size: 18
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });

            function calcularResultado() {
                // Factores y sus rangos de preguntas
                const factores = {
                    'Sociales': { inicio: 1, fin: 5 },
                    'Medioambientales': { inicio: 6, fin: 10 },
                    'Políticos': { inicio: 11, fin: 15 },
                    'Económicos': { inicio: 16, fin: 20 },
                    'Tecnológicos': { inicio: 21, fin: 25 }
                };

                let resultadosHTML = '';
                let totalGeneral = 0;
                let preguntasContestadas = 0;
                
                // Arrays para el gráfico
                const labels = [];
                const data = [];
                const backgroundColors = [];

                // Calcular para cada factor
                for (const [nombreFactor, rango] of Object.entries(factores)) {
                    let totalFactor = 0;
                    let preguntasFactor = 0;

                    for (let i = rango.inicio; i <= rango.fin; i++) {
                        const seleccion = document.querySelector(`input[name="p${i}"]:checked`);
                        if (seleccion) {
                            totalFactor += parseInt(seleccion.value);
                            preguntasFactor++;
                            preguntasContestadas++;
                        }
                    }

                    // Calcular porcentaje 
                    const porcentaje = preguntasFactor > 0 ? Math.round((totalFactor / 20) * 100) : 0;
                    totalGeneral += porcentaje;
                    
                    // Agregar datos para el gráfico
                    labels.push(nombreFactor);
                    data.push(porcentaje);
                    backgroundColors.push(porcentaje < 70 ? 'rgba(255, 99, 132, 0.6)' : 'rgba(75, 192, 192, 0.6)');

                    // Generar interpretación
                    const interpretacion = porcentaje < 70 ? 
                        `❌ NO hay un notable impacto de factores ${nombreFactor.toLowerCase()} en el funcionamiento de la empresa.` : 
                        `✅ HAY un notable impacto de factores ${nombreFactor.toLowerCase()} en el funcionamiento de la empresa.`;

                    resultadosHTML += `
                        <div class="factor-result ${porcentaje < 70 ? 'negative' : 'positive'}">
                            <strong>${nombreFactor}:</strong> ${porcentaje}%<br>
                            ${interpretacion}
                        </div>
                    `;
                }

                // Actualizar el gráfico
                chart.data.labels = labels;
                chart.data.datasets[0].data = data;
                chart.data.datasets[0].backgroundColor = backgroundColors;
                chart.update();

                // Actualizar resultados
                const resultadosContainer = document.getElementById("resultados-factores");
                if (resultadosContainer) resultadosContainer.innerHTML = resultadosHTML;
            }

            // Agregar listeners a todos los radios
            const radios = document.querySelectorAll('input[type=radio]');
            radios.forEach(input => {
                input.addEventListener('change', calcularResultado);
            });

            // Ejecutar al cargar por si hay valores precargados
            calcularResultado();
        });
    </script>







<div class="container mt-4">
    <?php if (isset($_SESSION['error_pestel'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error_pestel'] ?>
        </div>
        <?php unset($_SESSION['error_pestel']); ?>
    <?php endif; ?>

    <h2 class="text-center mb-4">Análisis PESTEL</h2>
    <p class="lead text-center mb-4">Evalúe cada afirmación según su grado de acuerdo (0 = En total desacuerdo, 4 = En total acuerdo)</p>

    <form action="<?= base_url ?>pestel/guardarEncuesta" method="POST">
        <?php if ($encuestaActual): ?>
            <input type="hidden" name="id_encuesta" value="<?= $encuestaActual->id_encuesta_pest ?>">
        <?php endif; ?>

        <!-- SECCIÓN SOCIALES -->
        <h3 class="section-title">Factores Sociales y Demográficos</h3>
        
        <div class="scale-labels">
            <span>En total desacuerdo</span>
            <span>No está de acuerdo</span>
            <span>Está de acuerdo</span>
            <span>Está bastante de acuerdo</span>
            <span>En total acuerdo</span>
        </div>
        
        <table class="pregunta-table">
            <thead>
                <tr>
                    <th class="pregunta-text">Afirmación</th>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pregunta-text">Los cambios demográficos afectan positivamente nuestro mercado objetivo</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p1" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p1 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Los niveles educativos de la población son adecuados para nuestros productos/servicios</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p2" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p2 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Los cambios en los estilos de vida favorecen nuestro negocio</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p3" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p3 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Las tendencias culturales son favorables para nuestra empresa</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p4" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p4 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">La distribución por edades de la población beneficia nuestro sector</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p5" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p5 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>

        <!-- SECCIÓN MEDIOAMBIENTALES -->
        <h3 class="section-title">Factores Medioambientales</h3>
        
        <div class="scale-labels">
            <span>En total desacuerdo</span>
            <span>No está de acuerdo</span>
            <span>Está de acuerdo</span>
            <span>Está bastante de acuerdo</span>
            <span>En total acuerdo</span>
        </div>
        
        <table class="pregunta-table">
            <thead>
                <tr>
                    <th class="pregunta-text">Afirmación</th>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pregunta-text">Las regulaciones ambientales afectan positivamente nuestra operación</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p6" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p6 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">La disponibilidad de recursos naturales es adecuada para nuestro negocio</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p7" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p7 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">El cambio climático tiene un impacto positivo en nuestro sector</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p8" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p8 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">La conciencia ecológica de los consumidores beneficia nuestros productos</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p9" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p9 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Las políticas de sostenibilidad son favorables para nuestra empresa</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p10" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p10 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>

        <!-- SECCIÓN POLÍTICOS -->
        <h3 class="section-title">Factores Políticos</h3>
        
        <div class="scale-labels">
            <span>En total desacuerdo</span>
            <span>No está de acuerdo</span>
            <span>Está de acuerdo</span>
            <span>Está bastante de acuerdo</span>
            <span>En total acuerdo</span>
        </div>
        
        <table class="pregunta-table">
            <thead>
                <tr>
                    <th class="pregunta-text">Afirmación</th>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pregunta-text">La estabilidad política del país favorece nuestro negocio</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p11" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p11 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Las políticas fiscales y tributarias son favorables para nuestro sector</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p12" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p12 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Las regulaciones comerciales internacionales benefician nuestra operación</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p13" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p13 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Las relaciones internacionales del país son positivas para nuestro negocio</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p14" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p14 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Las políticas gubernamentales específicas de nuestro sector son adecuadas</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p15" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p15 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>

        <!-- SECCIÓN ECONÓMICOS -->
        <h3 class="section-title">Factores Económicos</h3>
        
        <div class="scale-labels">
            <span>En total desacuerdo</span>
            <span>No está de acuerdo</span>
            <span>Está de acuerdo</span>
            <span>Está bastante de acuerdo</span>
            <span>En total acuerdo</span>
        </div>
        
        <table class="pregunta-table">
            <thead>
                <tr>
                    <th class="pregunta-text">Afirmación</th>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pregunta-text">El crecimiento económico del país beneficia nuestro sector</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p16" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p16 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Las tasas de interés actuales son favorables para nuestra empresa</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p17" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p17 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">La tasa de inflación no afecta negativamente nuestro negocio</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p18" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p18 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">El nivel de desempleo no perjudica nuestra capacidad de contratación</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p19" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p19 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">El poder adquisitivo de los consumidores es adecuado para nuestros productos</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p20" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p20 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>

        <!-- SECCIÓN TECNOLÓGICOS -->
        <h3 class="section-title">Factores Tecnológicos</h3>
        
        <div class="scale-labels">
            <span>En total desacuerdo</span>
            <span>No está de acuerdo</span>
            <span>Está de acuerdo</span>
            <span>Está bastante de acuerdo</span>
            <span>En total acuerdo</span>
        </div>
        
        <table class="pregunta-table">
            <thead>
                <tr>
                    <th class="pregunta-text">Afirmación</th>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pregunta-text">El nivel de innovación en nuestro sector es adecuado</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p21" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p21 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">La infraestructura tecnológica disponible es suficiente para nuestras necesidades</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p22" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p22 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">El acceso a nuevas tecnologías beneficia nuestra competitividad</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p23" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p23 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">El gasto en I+D del sector es adecuado</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p24" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p24 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">La velocidad de obsolescencia tecnológica no perjudica nuestro negocio</td>
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <td><input type="radio" name="p25" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p25 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>

        <!-- RESULTADOS -->
        <div id="resultados-factores"></div>
        <div id="chart-container">
            <canvas id="resultadosChart"></canvas>
        </div>
    
    

        <div class="text-center mb-5">
            <button type="submit" class="btn btn-primary btn-lg">Guardar Análisis</button>
        </div>
    </form>

    <!-- Mensajes de Amenaza -->
    <?php foreach (['amenaza_guardada', 'amenaza_actualizada', 'amenaza_eliminada'] as $key): ?>
        <?php if (isset($_SESSION[$key])): ?>
            <div class="alert <?= $_SESSION[$key] == 'completado' ? 'alert-success' : 'alert-danger' ?>">
                <?= $_SESSION[$key] == 'completado' ? '✅ Amenaza procesada correctamente' : '❌ Error al procesar la amenaza' ?>
            </div>
            <?php unset($_SESSION[$key]); ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Mensajes de Oportunidad -->
    <?php foreach (['oportunidad_guardada', 'oportunidad_actualizada', 'oportunidad_eliminada'] as $key): ?>
        <?php if (isset($_SESSION[$key])): ?>
            <div class="alert <?= $_SESSION[$key] == 'completado' ? 'alert-success' : 'alert-danger' ?>">
                <?= $_SESSION[$key] == 'completado' ? '✅ Oportunidad procesada correctamente' : '❌ Error al procesar la oportunidad' ?>
            </div>
            <?php unset($_SESSION[$key]); ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Amenazas -->
    <div class="card mt-4">
        <div class="card-header bg-danger text-white">
            <h3 class="mb-0">Amenazas Externas</h3>
        </div>
        <div class="card-body">
            <?php if ($edicionAmenaza && $amenazaActual): ?>
                <form action="<?= base_url ?>pestel/guardarAmenaza" method="POST" class="mb-4">
                    <input type="hidden" name="id_amenaza" value="<?= $amenazaActual->id_amenaza ?>">
                    <div class="form-group">
                        <label>Editar amenaza:</label>
                        <textarea name="amenaza" class="form-control" rows="3" required><?= htmlspecialchars($amenazaActual->amenaza) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <a href="<?= base_url ?>pestel/index" class="btn btn-secondary">Cancelar</a>
                </form>
            <?php else: ?>
                <form action="<?= base_url ?>pestel/guardarAmenaza" method="POST" class="mb-4">
                    <div class="form-group">
                        <label>Agregar nueva amenaza:</label>
                        <textarea name="amenaza" class="form-control" rows="3" placeholder="Ej: Cambios regulatorios que afectan negativamente al sector..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar Amenaza</button>
                </form>
            <?php endif; ?>

            <div class="list-group">
                <?php if ($amenazas && $amenazas->num_rows > 0): ?>
                    <?php while ($a = $amenazas->fetch_object()): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div><?= htmlspecialchars($a->amenaza) ?></div>
                            <div>
                                <a href="<?= base_url ?>pestel/index&editarAmenaza=<?= $a->id_amenaza ?>" class="btn btn-sm btn-warning mr-2">Editar</a>
                                <a href="<?= base_url ?>pestel/eliminarAmenaza&id=<?= $a->id_amenaza ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta amenaza?')">Eliminar</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info">No se han registrado amenazas aún</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Oportunidades -->
    <div class="card mt-4">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">Oportunidades Externas</h3>
        </div>
        <div class="card-body">
            <?php if ($edicionOportunidad && $oportunidadActual): ?>
                <form action="<?= base_url ?>pestel/guardarOportunidad" method="POST" class="mb-4">
                    <input type="hidden" name="id_oportunidad" value="<?= $oportunidadActual->id_oportunidad ?>">
                    <div class="form-group">
                        <label>Editar oportunidad:</label>
                        <textarea name="oportunidad" class="form-control" rows="3" required><?= htmlspecialchars($oportunidadActual->oportunidad) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <a href="<?= base_url ?>pestel/index" class="btn btn-secondary">Cancelar</a>
                </form>
            <?php else: ?>
                <form action="<?= base_url ?>pestel/guardarOportunidad" method="POST" class="mb-4">
                    <div class="form-group">
                        <label>Agregar nueva oportunidad:</label>
                        <textarea name="oportunidad" class="form-control" rows="3" placeholder="Ej: Nuevas tecnologías que podrían mejorar nuestros procesos..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar Oportunidad</button>
                </form>
            <?php endif; ?>

            <div class="list-group">
                <?php if ($oportunidades && $oportunidades->num_rows > 0): ?>
                    <?php while ($o = $oportunidades->fetch_object()): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div><?= htmlspecialchars($o->oportunidad) ?></div>
                            <div>
                                <a href="<?= base_url ?>pestel/index&editarOportunidad=<?= $o->id_oportunidad ?>" class="btn btn-sm btn-warning mr-2">Editar</a>
                                <a href="<?= base_url ?>pestel/eliminarOportunidad&id=<?= $o->id_oportunidad ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta oportunidad?')">Eliminar</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info">No se han registrado oportunidades aún</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>