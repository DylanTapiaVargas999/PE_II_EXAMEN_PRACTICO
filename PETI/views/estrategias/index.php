<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Identificación de Estrategias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f5f5f5;
        }

        .titulo-estrategias {
            background-color: #007BFF;
            color: #fff;
            padding: 15px;
            font-size: 1.8em;
            font-weight: bold;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .subtitulo {
            margin-top: 30px;
            font-size: 1.4em;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 5px;
        }

        p {
            font-size: 1em;
            color: #444;
            margin-bottom: 20px;
        }

        .matriz-grafica {
            text-align: center;
            margin-bottom: 30px;
        }

        .matriz-grafica img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background-color: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            border-radius: 5px;
            resize: vertical;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            text-align: center;
        }

        button[type="submit"] {
            display: block;
            margin: 40px auto;
            padding: 12px 25px;
            font-size: 1.1em;
            color: white;
            background-color: #28a745;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }

        .alert {
            padding: 12px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div>
    <div class="titulo-estrategias">10. IDENTIFICACIÓN DE ESTRATEGIAS</div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert"><?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
    <?php endif; ?>

    <p>
        Tras el análisis realizado, se identificaron fortalezas, oportunidades, debilidades y amenazas. A continuación, se presenta la matriz cruzada para evaluar estrategias FO, AF, OD y AD.
    </p>

    <div class="matriz-grafica">
        <img src="<?= base_url ?>assets/img/matriz_cruzada.png" alt="Matriz Cruzada">
    </div>

    <form action="<?= base_url ?>estrategias/guardar" method="post">

        <!-- Text Areas -->
        <table>
            <tr><td><strong>DEBILIDADES</strong></td><td><textarea name="debilidades" required></textarea></td></tr>
            <tr><td><strong>AMENAZAS</strong></td><td><textarea name="amenazas" required></textarea></td></tr>
            <tr><td><strong>FORTALEZAS</strong></td><td><textarea name="fortalezas" required></textarea></td></tr>
            <tr><td><strong>OPORTUNIDADES</strong></td><td><textarea name="oportunidades" required></textarea></td></tr>
        </table>

        <!-- MATRICES FO / AF / OD / AD -->
        <?php
        $matrices = [
            "FO" => ["Fila" => "F", "Col" => "O"],
            "AF" => ["Fila" => "F", "Col" => "A"],
            "OD" => ["Fila" => "D", "Col" => "O"],
            "AD" => ["Fila" => "D", "Col" => "A"]
        ];

        foreach ($matrices as $titulo => $config):
            echo "<div class='subtitulo'>{$titulo}: {$config['Fila']} vs {$config['Col']}</div>";
            echo "<table class='tabla-matriz'>";
            echo "<tr><th rowspan='2'>{$config['Fila']}ORTALEZAS/DEBILIDADES</th><th colspan='4'>{$config['Col']}PORTUNIDADES/AMENAZAS</th><th rowspan='2'>Total</th></tr>";
            echo "<tr><th>{$config['Col']}1</th><th>{$config['Col']}2</th><th>{$config['Col']}3</th><th>{$config['Col']}4</th></tr>";

            for ($i = 1; $i <= 4; $i++) {
                echo "<tr><td>{$config['Fila']}{$i}</td>";
                for ($j = 1; $j <= 4; $j++) {
                    echo "<td><input type='number' name='{$titulo}_{$config['Fila']}{$i}_{$config['Col']}{$j}' min='0' max='4'></td>";
                }
                echo "<td><input type='number' readonly></td></tr>";
            }

            echo "</table>";
        endforeach;
        ?>

        <button type="submit">Guardar Estrategias</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.tabla-matriz').forEach(tabla => {
        tabla.querySelectorAll('tr').forEach((fila, index) => {
            if (index < 2) return;
            const inputs = fila.querySelectorAll('input[type="number"]:not([readonly])');
            const total = fila.querySelector('input[readonly]');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    let suma = 0;
                    inputs.forEach(i => suma += parseInt(i.value) || 0);
                    total.value = suma;
                });
            });
        });
    });
});
</script>

</body>
</html>
