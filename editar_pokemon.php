<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "pokemon.sql";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

if (!isset($_GET["id"])) {
    echo "ID do Pokémon não fornecido.";
    exit;
}

$id = $_GET["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome_pokemon"];
    $tipo = $_POST["tipo_pokemon"];
    $localizacao = $_POST["localizacao_pokemon"];
    $data = $_POST["dataregistro_pokemon"];
    $hp_atk_def = $_POST["hp_ataque_defesa_pokemon"];
    $obs = $_POST["obs_pokemon"];

    $stmt = $conn->prepare("UPDATE cadastro SET nome_pokemon=?, tipo_pokemon=?, localizacao_pokemon=?, dataregistro_pokemon=?, hp_ataque_defesa_pokemon=?, obs_pokemon=? WHERE id_pokemon=?");
    $stmt->bind_param("ssssssi", $nome, $tipo, $localizacao, $data, $hp_atk_def, $obs, $id);

    if ($stmt->execute()) {
        echo "<h2> Pokémon atualizado com sucesso!</h2>";
        echo "<p><a href='index.php'>Voltar</a></p>";
        exit;
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }
}

// Busca dados atuais
$stmt = $conn->prepare("SELECT * FROM cadastro WHERE id_pokemon = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Pokémon não encontrado.";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Pokémon</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background:  #ffcb05;
            padding: 40px;
        }
        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2a75bb;
        }
        label {
            display: block;
            margin-top: 15px;
            color: #333;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid  #2a75bb;
            margin-top: 5px;
        }
        button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #2a75bb;
            color: white;
            border: none;
            border-radius: 6px;
            width: 100%;
            font-size: 16px;
        }
        a {
            display: block;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            color: #2a75bb;
        }
    </style>
</head>
<body>

<form method="POST">
    <h2>Editar Pokémon</h2>

    <label>Nome:</label>
    <input type="text" name="nome_pokemon" value="<?= htmlspecialchars($row['nome_pokemon']) ?>" required>

    <label>Tipo:</label>
    <input type="text" name="tipo_pokemon" value="<?= htmlspecialchars($row['tipo_pokemon']) ?>" required>

    <label>Localização Encontrada:</label>
    <input type="text" name="localizacao_pokemon" value="<?= htmlspecialchars($row['localizacao_pokemon']) ?>">

    <label>Data de Registro:</label>
    <input type="date" name="dataregistro_pokemon" value="<?= htmlspecialchars($row['dataregistro_pokemon']) ?>">

    <label>HP / Ataque / Defesa:</label>
    <input type="text" name="hp_ataque_defesa_pokemon" value="<?= htmlspecialchars($row['hp_ataque_defesa_pokemon']) ?>">

    <label>Observações:</label>
    <textarea name="obs_pokemon" rows="4"><?= htmlspecialchars($row['obs_pokemon']) ?></textarea>

    <button type="submit">Salvar Alterações</button>
    <a href="index.php">Cancelar</a>
</form>

</body>
</html>
