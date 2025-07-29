<?php
// Conexão com o banco de dados
$host = "localhost";
$user = "root";
$pass = ""; // sua senha do MySQL, se houver
$db = "pokemon.sql";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome_pokemon"];
    $tipo = $_POST["tipo_pokemon"];
    $localizacao = $_POST["localizacao_pokemon"];
    $dataregistro = $_POST["dataregistro_pokemon"];
    $hp_ataque_defesa = $_POST["hp_ataque_defesa_pokemon"];
    $obs = $_POST["obs_pokemon"];

    $stmt = $conn->prepare("INSERT INTO cadastro (nome_pokemon, tipo_pokemon, localizacao_pokemon, dataregistro_pokemon, hp_ataque_defesa_pokemon, obs_pokemon) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $tipo, $localizacao, $dataregistro, $hp_ataque_defesa, $obs);

    if ($stmt->execute()) {
        echo "<p>✅ Pokémon cadastrado com sucesso!</p>";
    } else {
        echo "<p>❌ Erro ao cadastrar Pokémon: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<h2>Cadastrar Pokémon</h2>
<form method="POST" action="">
    <label>Nome do Pokémon:<br>
        <input type="text" name="nome_pokemon" required>
    </label><br><br>

    <label>Tipo:<br>
        <input type="text" name="tipo_pokemon" required>
    </label><br><br>

    <label>Localização Encontrada:<br>
        <input type="text" name="localizacao_pokemon">
    </label><br><br>

    <label>Data do Registro:<br>
        <input type="date" name="dataregistro_pokemon">
    </label><br><br>

    <label>HP / Ataque / Defesa:<br>
        <input type="text" name="hp_ataque_defesa_pokemon" placeholder="Ex: 60/45/70">
    </label><br><br>

    <label>Observações:<br>
        <textarea name="obs_pokemon" rows="4" cols="40"></textarea>
    </label><br><br>

    <button type="submit">Cadastrar Pokémon</button>
</form>
