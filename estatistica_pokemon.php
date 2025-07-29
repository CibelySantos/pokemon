<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "pokemon.sql";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Erro: " . $conn->connect_error);

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
    
    font-family: 'Segoe UI', sans-serif;
    background-image: url('./img/fundo_lista.jpg'); /* imagem de fundo */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    min-height: 100vh;
    margin: 0;
    padding: 30px;
    backdrop-filter: brightness(0.95);
}

    .navbar {
    background-color: #ffcb05;
    padding: 16px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    width: 95%;
    z-index: 1000;
    
}

.navbar a {
    color: #2a75bb;
    text-decoration: none;
    font-size: 24px;
    font-weight: bold;
    transition: color 0.3s ease;
}

.navbar a:hover {
    color: #0d3c91;
}

.navbar-buttons {
    display: flex;
    gap: 20px;
}

.navbar-buttons a {
    padding: 10px 18px;
    border-radius: 8px;
    background-color: transparent;
    
    color: #2a75bb;
    font-size: 16px;
    transition: 0.3s ease;
}


/* Adiciona margem ao conteúdo para não ficar embaixo da navbar */
body {
    margin: 0;
    padding-top: 80px; /* Ajuste conforme altura da navbar */
}

    .container {
        padding: 40px 20px;
        max-width: 900px;
        margin: 0 auto;
    }

    h1 {
        text-align: center;
        color: #2a75bb;
        font-size: 36px;
        margin-bottom: 30px;
    }

    form {
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    label {
        font-weight: bold;
        color: #2a75bb;
        font-size: 18px;
    }

    select, button {
        width: 100%;
        padding: 12px;
        margin-top: 12px;
        font-size: 16px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    button {
        background-color: #2a75bb;
        color: white;
        border: none;
        margin-top: 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #1b4f87;
    }

    canvas {
        margin: 40px auto;
        display: block;
        background-color: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .voltar {
        text-align: center;
        margin: 40px 0;
    }

    .voltar a {
        text-decoration: none;
        color: white;
        background-color: #2a75bb;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .voltar a:hover {
        background-color: #1b4f87;
    }

    p {
        text-align: center;
        font-size: 18px;
        color: #555;
        margin-top: 20px;
    }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="index.php">MundoPokémon</a>
        <div class="navbar-buttons">
            <a href="estatistica_pokemon.php">Estatísticas</a>
            <a href="pesquisa.php">Listar</a>
            <a href="lista_pokemon.php">Cadastrar</a>
        </div>
    </div>

    <div class="container">
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
            <p>Nenhum dado encontrado para esse filtro.</p>
        <?php endif; ?>

        <div class="voltar">
            <a href="index.php">Voltar</a>
        </div>
    </div>

</body>
</html>
