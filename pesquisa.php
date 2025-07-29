<?php
$servername = "localhost";
$username = "root";
$password = ""; // coloca tua senha se tiver
$database = "pokemon.sql"; // NÃO coloca ".sql" aqui

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
    background-image: url('./img/fundo_lista.jpg'); /* imagem de fundo */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    min-height: 100vh;
    margin: 0;
    padding: 30px;
    backdrop-filter: brightness(0.95);
}

table {
    border-collapse: collapse;
    width: 100%;
    background-color: rgba(255, 255, 255, 0.9); /* fundo branco com transparência */
    box-shadow: 0 4px 15px rgba(0,0,0,0.3); /* sombra estilosa */
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
    background-color: #facc15; /* amarelo Pokémon */
    color: #1f2937; /* cinza escuro */
    font-weight: bold;
}

tr:hover {
    background-color: #fef3c7; /* amarelo clarinho no hover */
}

h1 {
    color: #1e3a8a; /* azul forte */
    text-shadow: 1px 1px 2px #fff;
    text-align: center;
    margin-bottom: 20px;
}

    </style>
</head>
<body>

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
