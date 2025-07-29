<?php
$servername = "localhost";
$username = "root";
$password = ""; // coloca tua senha se tiver
$database = "pokemon.sql"; // Corrigido: sem ".sql"

// Conexão com o banco
$conn = new mysqli($servername, $username, $password, $database);

// Checar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Puxar dados da tabela
$sql = "SELECT * FROM cadastro";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pokémon</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-image: url('./img/fundo_lista.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            margin: 0;
            padding: 30px;
            padding-top: 100px; /* espaço para navbar */
            backdrop-filter: brightness(0.95);
        }

        /* NAVBAR FIXA */
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

       

        /* TABELA */
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
            font-size: 16px;
            color: #333;
        }

        th {
            background-color: #facc15;
            color: #1f2937;
            font-weight: bold;
        }

        tr:hover {
            background-color: #fef3c7;
        }

        h1 {
            color: #1e3a8a;
            text-shadow: 1px 1px 2px #fff;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <a href="index.php">MundoPokémon</a>
    <div class="navbar-buttons">
        <a href="estatistica_pokemon.php">Estatistica</a>
        <a href="pesquisa.php">Listar</a>
        <a href="cadastro.php">Cadastrar</a>

    </div>
</div>

<h1>Lista de Pokémon</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Tipo</th>
        <th>Localização</th>
        <th>Data Registro</th>
        <th>HP / Ataque / Defesa</th>
        <th>Observações</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>".$row["id_pokemon"]."</td>
                    <td>".$row["nome_pokemon"]."</td>
                    <td>".$row["tipo_pokemon"]."</td>
                    <td>".$row["localizacao_pokemon"]."</td>
                    <td>".$row["dataregistro_pokemon"]."</td>
                    <td>".$row["hp_ataque_defesa_pokemon"]."</td>
                    <td>".$row["obs_pokemon"]."</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Nenhum Pokémon encontrado</td></tr>";
    }

    $conn->close();
    ?>
</table>

</body>
</html>
