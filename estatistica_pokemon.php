<?php
// Conexão
$host = "localhost";
$user = "root";
$pass = "";
$db = "pokemon.sql";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Erro: " . $conn->connect_error);

// Inicialização
$dados = [];
$rotulo = "";
$filtro = isset($_POST['filtro']) ? $_POST['filtro'] : "";

if ($filtro) {
    switch ($filtro) {
        case "tipo_pokemon":
            $rotulo = "Tipo de Pokémon";
            $sql = "SELECT tipo_pokemon as categoria, COUNT(*) as total FROM cadastro GROUP BY tipo_pokemon";
            break;
        case "dataregistro_pokemon":
            $rotulo = "Data de Registro";
            $sql = "SELECT dataregistro_pokemon as categoria, COUNT(*) as total FROM cadastro GROUP BY dataregistro_pokemon";
            break;
        case "localizacao_pokemon":
            $rotulo = "Localização Encontrada";
            $sql = "SELECT localizacao_pokemon as categoria, COUNT(*) as total FROM cadastro GROUP BY localizacao_pokemon";
            break;
        default:
            $sql = "";
    }

    if ($sql) {
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $dados[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Estatísticas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 40px;
        }
        h1 {
            text-align: center;
            color: #2a75bb;
        }
        form {
            text-align: center;
            margin-bottom: 30px;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
            margin: 5px;
        }
        canvas {
            display: block;
            max-width: 800px;
            margin: auto;
        }
        .voltar {
            text-align: center;
            margin-top: 20px;
        }
        .voltar a {
            text-decoration: none;
            color: white;
            background-color: #2a75bb;
            padding: 10px 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<h1>Estatísticas dos Pokémon</h1>

<form method="POST">
    <label for="filtro">Escolha um filtro:</label>
    <select name="filtro" id="filtro">
        <option value="">-- Selecione --</option>
        <option value="tipo_pokemon" <?= $filtro == "tipo_pokemon" ? "selected" : "" ?>>Tipo</option>
        <option value="dataregistro_pokemon" <?= $filtro == "dataregistro_pokemon" ? "selected" : "" ?>>Data</option>
        <option value="localizacao_pokemon" <?= $filtro == "localizacao_pokemon" ? "selected" : "" ?>>Localização</option>
    </select>
    <button type="submit">Gerar Gráfico</button>
</form>

<?php if (!empty($dados)): ?>
    <canvas id="grafico"></canvas>

    <script>
        const ctx = document.getElementById('grafico').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($dados, 'categoria')) ?>,
                datasets: [{
                    label: 'Quantidade por <?= $rotulo ?>',
                    data: <?= json_encode(array_column($dados, 'total')) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
<?php elseif ($filtro): ?>
    <p style="text-align:center;">Nenhum dado encontrado para esse filtro.</p>
<?php endif; ?>

<div class="voltar">
    <a href="index.php">Voltar</a>
</div>

</body>
</html>

