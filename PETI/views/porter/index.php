<head>
    <link rel="stylesheet" href="<?=base_url?>assets/css/porter/index.css">
    
</head>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function calcularResultado() {
            let total = 0;
            let totalPreguntas = 17;

            for (let i = 1; i <= totalPreguntas; i++) {
                const seleccion = document.querySelector(`input[name="p${i}"]:checked`);
                if (seleccion) {
                    total += parseInt(seleccion.value);
                }
            }

            const resultado = document.getElementById("resultado");
            const interpretacion = document.getElementById("interpretacion");

            if (resultado) resultado.value = total;

            if (interpretacion) {
                if (total < 30) {
                    interpretacion.innerText = "Estamos en un mercado altamente competitivo, en el que es muy difícil hacerse un hueco en el mercado.";
                } else if (total < 45) {
                    interpretacion.innerText = "Estamos en un mercado de competitividad relativamente alta, pero con ciertas modificaciones en el producto y la política comercial de la empresa, podría encontrarse un nicho de mercado.";
                } else if (total < 60) {
                    interpretacion.innerText = "La situación actual del mercado es favorable a la empresa.";
                } else {
                    interpretacion.innerText = "Estamos en una situación excelente para la empresa.";
                }
            }
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
    <?php if (isset($_SESSION['error_porter'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error_porter'] ?>
        </div>
        <?php unset($_SESSION['error_porter']); ?>
    <?php endif; ?>

    <h2 class="text-center mb-4">Análisis de las 5 Fuerzas de Porter</h2>
    <p class="lead text-center mb-4">Evalúe cada factor en una escala del 1 (Hostil) al 5 (Favorable)</p>

    <form action="<?= base_url ?>porter/guardarEncuesta" method="POST">
        <?php if ($encuestaActual): ?>
            <input type="hidden" name="id_encuesta" value="<?= $encuestaActual->id_encuesta_porter ?>">
        <?php endif; ?>

        <!-- SECCIÓN 1: RIVALIDAD ENTRE COMPETIDORES -->
        <h3 class="section-title">1. Rivalidad entre empresas del sector</h3>
        

        <table class="pregunta-table">
            <thead>
                <tr>
                    <th class="pregunta-text">Factor Competitivo</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pregunta-text">Crecimiento del sector</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p1" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p1 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Naturaleza de los competidores</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p2" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p2 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Exceso de capacidad productiva</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p3" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p3 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Rentabilidad media del sector</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p4" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p4 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Diferenciación del producto</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p5" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p5 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Barreras de salida</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p6" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p6 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>

        <!-- SECCIÓN 2: BARRERAS DE ENTRADA -->
        <h3 class="section-title">2. Barreras de Entrada</h3>
        
        <table class="pregunta-table">
            <thead>
                <tr>
                    <th class="pregunta-text">Factor Competitivo</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pregunta-text">Economías de escala</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p7" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p7 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Necesidad de capital</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p8" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p8 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Acceso a la tecnología</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p9" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p9 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Reglamentos o leyes limitativos</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p10" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p10 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Trámites burocráticos</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p11" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p11 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Reacción esperada de competidores actuales</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p12" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p12 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>

        <!-- SECCIÓN 3: PODER DE LOS CLIENTES -->
        <h3 class="section-title">3. Poder de los Clientes</h3>
        
        <table class="pregunta-table">
            <thead>
                <tr>
                    <th class="pregunta-text">Factor Competitivo</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pregunta-text">Número de clientes</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p13" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p13 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Posibilidad de integración ascendente</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p14" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p14 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Rentabilidad de los clientes</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p15" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p15 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
                <tr>
                    <td class="pregunta-text">Coste de cambio de proveedor para cliente</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p16" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p16 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>

        <!-- SECCIÓN 4: PRODUCTOS SUSTITUTIVOS -->
        <h3 class="section-title">4. Productos Sustitutivos</h3>
        <table class="pregunta-table">
            <thead>
                <tr>
                    <th class="pregunta-text">Factor Competitivo</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pregunta-text">Disponibilidad de Productos Sustitutivos</td>
                    <?php for ($val = 1; $val <= 5; $val++): ?>
                        <td><input type="radio" name="p17" value="<?= $val ?>" <?= ($encuestaActual && $encuestaActual->p17 == $val) ? 'checked' : '' ?> required></td>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>

        <!-- RESULTADOS -->
        <div class="resultado-container">
            <h4 class="text-center mb-3">Resultado del Análisis Porter</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="resultado" class="font-weight-bold">Puntaje Total (17-85):</label>
                        <input type="text" id="resultado" class="form-control form-control-lg text-center" readonly style="font-size:1.5rem; font-weight:bold; background:#f8f9fa;">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Interpretación:</label>
                        <div id="interpretacion" class="form-control" style="height:auto; min-height:60px; background:#f8f9fa; font-size:1.1rem;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-5">
            <button type="submit" class="btn btn-primary btn-lg">Guardar Análisis</button>
        </div>
    </form>

    <!-- Resto del código para Amenazas y Oportunidades... -->

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
                <form action="<?= base_url ?>porter/guardarAmenaza" method="POST" class="mb-4">
                    <input type="hidden" name="id_amenaza" value="<?= $amenazaActual->id_amenaza ?>">
                    <div class="form-group">
                        <label>Editar amenaza:</label>
                        <textarea name="amenaza" class="form-control" rows="3" required><?= htmlspecialchars($amenazaActual->amenaza) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <a href="<?= base_url ?>porter/index" class="btn btn-secondary">Cancelar</a>
                </form>
            <?php else: ?>
                <form action="<?= base_url ?>porter/guardarAmenaza" method="POST" class="mb-4">
                    <div class="form-group">
                        <label>Agregar nueva amenaza:</label>
                        <textarea name="amenaza" class="form-control" rows="3" placeholder="Ej: Nuevos competidores internacionales ingresando al mercado..." required></textarea>
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
                                <a href="<?= base_url ?>porter/index&editarAmenaza=<?= $a->id_amenaza ?>" class="btn btn-sm btn-warning mr-2">Editar</a>
                                <a href="<?= base_url ?>porter/eliminarAmenaza&id=<?= $a->id_amenaza ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta amenaza?')">Eliminar</a>
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
                <form action="<?= base_url ?>porter/guardarOportunidad" method="POST" class="mb-4">
                    <input type="hidden" name="id_oportunidad" value="<?= $oportunidadActual->id_oportunidad ?>">
                    <div class="form-group">
                        <label>Editar oportunidad:</label>
                        <textarea name="oportunidad" class="form-control" rows="3" required><?= htmlspecialchars($oportunidadActual->oportunidad) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <a href="<?= base_url ?>porter/index" class="btn btn-secondary">Cancelar</a>
                </form>
            <?php else: ?>
                <form action="<?= base_url ?>porter/guardarOportunidad" method="POST" class="mb-4">
                    <div class="form-group">
                        <label>Agregar nueva oportunidad:</label>
                        <textarea name="oportunidad" class="form-control" rows="3" placeholder="Ej: Expansión a mercados emergentes con crecimiento..." required></textarea>
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
                                <a href="<?= base_url ?>porter/index&editarOportunidad=<?= $o->id_oportunidad ?>" class="btn btn-sm btn-warning mr-2">Editar</a>
                                <a href="<?= base_url ?>porter/eliminarOportunidad&id=<?= $o->id_oportunidad ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta oportunidad?')">Eliminar</a>
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