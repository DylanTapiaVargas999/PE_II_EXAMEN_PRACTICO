<head>
    <link rel="stylesheet" href="<?=base_url?>assets/css/analisis/index.css">
</head>

<script>
function calcularPorcentaje() {
    const totalPreguntas = 25;

    // Contar cuántas preguntas tienen al menos un radio seleccionado
    let respondidas = 0;
    for(let i = 1; i <= totalPreguntas; i++) {
        if (document.querySelector(`input[name="p${i}"]:checked`)) {
            respondidas++;
        }
    }

    if (respondidas < totalPreguntas) {
        document.getElementById('resultado').value = "Completa todas las preguntas";
        return;
    }

    // Sumar valores seleccionados
    let sumaTotal = 0;
    for(let i = 1; i <= totalPreguntas; i++) {
        const val = document.querySelector(`input[name="p${i}"]:checked`).value;
        sumaTotal += parseInt(val);
    }

    let porcentaje = ((100 - sumaTotal) / 100) * 100;
    if (porcentaje < 0) porcentaje = 0;

    document.getElementById('resultado').value = porcentaje.toFixed(2) + '%';
}

document.addEventListener('DOMContentLoaded', () => {
    calcularPorcentaje();

    // Añadir evento a todos los radios
    document.querySelectorAll('.pregunta').forEach(input => {
        input.addEventListener('change', calcularPorcentaje);
    });
});


async function generarReflexion() {
    const porcentaje = document.getElementById('resultado').value.replace('%', '');
    if (!porcentaje || isNaN(porcentaje)) {
        alert('Calcula el porcentaje antes de generar la reflexión.');
        return;
    }

    const prompt = `Redacta una muy muy breve y directa reflexión profesional sobre el potencial de mejora de la cadena de valor interna de una empresa, considerando que el porcentaje de oportunidad de mejora es de ${porcentaje}%. para la toma de deicion para definir mi fortaleza y debilidad.`;

    const response = await fetch('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyDXoYn43bis7un9n3JGHngX7BhpAzYslOE', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            contents: [{
                parts: [{ text: prompt }]
            }]
        })
    });

    const data = await response.json();
    const textoGenerado = data.candidates?.[0]?.content?.parts?.[0]?.text;

    if (textoGenerado) {
        document.getElementById('reflexion').value = textoGenerado;
    } else {
        alert('No se pudo generar la reflexión. Verifica la clave o el uso de la API.');
        console.error(data);
    }
}
</script>






<?php
$preguntas = [
    "La empresa tiene una política sistematizada de cero defectos en la producción de productos/servicios.",
    "La empresa emplea los medios productivos tecnológicamente más avanzados de su sector.",
    "La empresa dispone de un sistema de información y control de gestión eficiente y eficaz.",
    "Los medios técnicos y tecnológicos de la empresa están preparados para competir en un futuro a corto, medio y largo plazo.",
    "La empresa es un referente en su sector en I+D+i.",
    "La excelencia de los procedimientos de la empresa (en ISO, etc.) son una principal fuente de ventaja competitiva.",
    "La empresa dispone de página web, y esta se emplea no sólo como escaparate virtual de productos/servicios, sino también para establecer relaciones con clientes y proveedores.",
    "Los productos/servicios que desarrolla nuestra empresa llevan incorporada una tecnología difícil de imitar.",
    "La empresa es referente en su sector en la optimización, en términos de coste, de su cadena de producción, siendo ésta una de sus principales ventajas competitivas.",
    "La informatización de la empresa es una fuente de ventaja competitiva clara respecto a sus competidores.",
    "Los canales de distribución de la empresa son una importante fuente de ventajas competitivas.",
    "Los productos/servicios de la empresa son altamente, y diferencialmente, valorados por el cliente respecto a nuestros competidores.",
    "La empresa dispone y ejecuta un sistemático plan de marketing y ventas.",
    "La empresa tiene optimizada su gestión financiera.",
    "La empresa busca continuamente el mejorar la relación con sus clientes cortando los plazos de ejecución, personalizando la oferta o mejorando las condiciones de entrega. Pero siempre partiendo de un plan previo.",
    "La empresa es referente en su sector en el lanzamiento de innovadores productos y servicio de éxito demostrado en el mercado.",
    "Los Recursos Humanos son especialmente responsables del éxito de la empresa, considerándolos incluso como el principal activo estratégico.",
    "Se tiene una plantilla altamente motivada, que conoce con claridad las metas, objetivos y estrategias de la organización.",
    "La empresa siempre trabaja conforme a una estrategia y objetivos claros.",
    "La gestión del circulante está optimizada.",
    "Se tiene definido claramente el posicionamiento estratégico de todos los productos de la empresa.",
    "Se dispone de una política de marca basada en la reputación que la empresa genera, en la gestión de relación con el cliente y en el posicionamiento estratégico previamente definido.",
    "La cartera de clientes de nuestra empresa está altamente fidelizada, ya que tenemos como principal propósito el deleitarlos día a día.",
    "Nuestra política y equipo de ventas y marketing es una importante ventaja competitiva de nuestra empresa respecto al sector.",
    "El servicio al cliente que prestamos es uno de nuestras principales ventajas competitivas respecto a nuestros competidores."
];
?>

<div class="container mt-4">
    <?php if (isset($_SESSION['error_analisis'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error_analisis'] ?>
        </div>
        <?php unset($_SESSION['error_analisis']); ?>
    <?php endif; ?>


   <!-- Encuesta --> 
<h2>Encuesta de Cadena de Valor</h2>
<form action="<?= base_url ?>analisis/guardarEncuesta" method="POST">
    <?php if ($encuestaActual): ?>
        <input type="hidden" name="id_encuesta" value="<?= $encuestaActual->id_encuesta_cadena ?>">
    <?php endif; ?>

    <div class="row">
        <?php foreach ($preguntas as $index => $texto): 
            $i = $index + 1; ?>
            <div class="col-md-12 mb-4">
                <label class="form-label d-block"><strong>Pregunta <?= $i ?>:</strong> <?= $texto ?></label>
                <div class="btn-group" role="group" aria-label="Calificación pregunta <?= $i ?>">
                    <?php for ($val = 0; $val <= 4; $val++): ?>
                        <input type="radio" class="btn-check pregunta" name="p<?= $i ?>" id="p<?= $i ?>_<?= $val ?>" autocomplete="off" value="<?= $val ?>"
                            <?= ($encuestaActual && $encuestaActual->{'p' . $i} == $val) ? 'checked' : '' ?> required>
                        <label class="btn btn-outline-primary" for="p<?= $i ?>_<?= $val ?>"><?= $val ?></label>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mb-3">
        <label for="resultado" class="form-label">Porcentaje calculado:</label>
        <input type="text" id="resultado" class="form-control" readonly style="background:#f0f0f0; font-weight:bold;">
    </div>

    <div class="mb-3">
        <label for="reflexion" class="form-label">Reflexión final:</label>
        <textarea name="reflexion" id="reflexion" class="form-control" rows="5"><?= $encuestaActual ? htmlspecialchars($encuestaActual->reflexion) : '' ?></textarea>
        <button type="button" class="btn btn-secondary mt-2" onclick="generarReflexion()">Generar reflexión con IA</button>
    </div>

    <button type="submit" class="btn btn-primary">Guardar Encuesta</button>
</form>


    <!-- Mensajes de Debilidad -->
    <?php foreach (['debilidad_guardada', 'debilidad_actualizada', 'debilidad_eliminada'] as $key): ?>
        <?php if (isset($_SESSION[$key])): ?>
            <div class="alert <?= $_SESSION[$key] == 'completado' ? 'alert-success' : 'alert-danger' ?>">
                <?= $_SESSION[$key] == 'completado' ? '✅ Debilidad procesada correctamente.' : '❌ Hubo un error con la debilidad.' ?>
            </div>
            <?php unset($_SESSION[$key]); ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Mensajes de Fortaleza -->
    <?php foreach (['fortaleza_guardada', 'fortaleza_actualizada', 'fortaleza_eliminada'] as $key): ?>
        <?php if (isset($_SESSION[$key])): ?>
            <div class="alert <?= $_SESSION[$key] == 'completado' ? 'alert-success' : 'alert-danger' ?>">
                <?= $_SESSION[$key] == 'completado' ? '✅ Fortaleza procesada correctamente.' : '❌ Hubo un error con la fortaleza.' ?>
            </div>
            <?php unset($_SESSION[$key]); ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Mensajes de Encuesta -->
    <?php foreach (['encuesta_guardada', 'encuesta_actualizada'] as $key): ?>
        <?php if (isset($_SESSION[$key])): ?>
            <div class="alert <?= $_SESSION[$key] == 'completado' ? 'alert-success' : 'alert-danger' ?>">
                <?= $_SESSION[$key] == 'completado' ? '✅ Encuesta guardada correctamente.' : '❌ Error al guardar la encuesta.' ?>
            </div>
            <?php unset($_SESSION[$key]); ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Debilidades -->
    <h2 class="mt-4">Debilidades</h2>
    <div class="mb-3">
        <?php if ($edicionDebilidad && $debilidadActual): ?>
            <form action="<?= base_url ?>analisis/guardarDebilidad" method="POST">
                <input type="hidden" name="id_debilidad" value="<?= $debilidadActual->id_debilidad ?>">
                <textarea name="debilidad" class="form-control mb-2" required><?= htmlspecialchars($debilidadActual->debilidad) ?></textarea>
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                <a href="<?= base_url ?>analisis/index" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php else: ?>
            <form action="<?= base_url ?>analisis/guardarDebilidad" method="POST">
                <textarea name="debilidad" class="form-control mb-2" placeholder="Describe una debilidad..." required></textarea>
                <button type="submit" class="btn btn-primary">Agregar Debilidad</button>
            </form>
        <?php endif; ?>
    </div>

    <ul class="list-group mb-4">
        <?php foreach ($debilidades as $d): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($d['debilidad']) ?>
<a href="<?= base_url ?>analisis/index&editarDebilidad=<?= $d['id_debilidad'] ?>">Editar</a>
<a href="<?= base_url ?>analisis/eliminarDebilidad&id=<?= $d['id_debilidad'] ?>" ...>Eliminar</a>

            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Fortalezas -->
    <h2>Fortalezas</h2>
    <div class="mb-3">
        <?php if ($edicionFortaleza && $fortalezaActual): ?>
            <form action="<?= base_url ?>analisis/guardarFortaleza" method="POST">
                <input type="hidden" name="id_fortaleza" value="<?= $fortalezaActual->id_fortaleza ?>">
                <textarea name="fortaleza" class="form-control mb-2" required><?= htmlspecialchars($fortalezaActual->fortaleza) ?></textarea>
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                <a href="<?= base_url ?>analisis/index" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php else: ?>
            <form action="<?= base_url ?>analisis/guardarFortaleza" method="POST">
                <textarea name="fortaleza" class="form-control mb-2" placeholder="Describe una fortaleza..." required></textarea>
                <button type="submit" class="btn btn-primary">Agregar Fortaleza</button>
            </form>
        <?php endif; ?>
    </div>

    <ul class="list-group mb-4">
        <?php foreach ($fortalezas as $f): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($f['fortaleza']) ?>
<a href="<?= base_url ?>analisis/index&editarFortaleza=<?= $f['id_fortaleza'] ?>">Editar</a>
<a href="<?= base_url ?>analisis/eliminarFortaleza&id=<?= $f['id_fortaleza'] ?>" ...>Eliminar</a>

            </li>
        <?php endforeach; ?>
    </ul>

    
</div>
