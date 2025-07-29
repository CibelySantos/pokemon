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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome_pokemon"];
    $tipo = $_POST["tipo_pokemon"];
    $localizacao = $_POST["localizacao_pokemon"];
    $dataregistro = $_POST["dataregistro_pokemon"];
    $hp_ataque_defesa = $_POST["hp_ataque_defesa_pokemon"];
    $obs = $_POST["obs_pokemon"];

    $imagem_nome = null;
    if (isset($_FILES["imagem_pokemon"]) && $_FILES["imagem_pokemon"]["error"] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES["imagem_pokemon"]["name"], PATHINFO_EXTENSION);
        $imagem_nome = uniqid() . "." . $extensao;
        $caminho_destino = "uploads/" . $imagem_nome;

        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        move_uploaded_file($_FILES["imagem_pokemon"]["tmp_name"], $caminho_destino);
    }

    $stmt = $conn->prepare("INSERT INTO cadastro (nome_pokemon, tipo_pokemon, localizacao_pokemon, dataregistro_pokemon, hp_ataque_defesa_pokemon, obs_pokemon, imagem_pokemon) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nome, $tipo, $localizacao, $dataregistro, $hp_ataque_defesa, $obs, $imagem_nome);

    if ($stmt->execute()) {
        echo "<p style='color:#f0c419; text-align:center; font-weight:bold; text-shadow: 0 0 5px #24478f;'>✅ Pokémon cadastrado com sucesso!</p>";
    } else {
        echo "<p style='color:#e55353; text-align:center; font-weight:bold;'>❌ Erro ao cadastrar Pokémon: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Cadastro Pokémon Harmonizado</title>
<style>

body {
  font-family: 'Segoe UI', sans-serif;
  background: 
    url('./img/cadastro.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  min-height: 100vh;
  margin: 0;
  padding: 30px;
  backdrop-filter: brightness(0.9) blur(3px);
  display: flex;
  justify-content: center;
  align-items: center;
}

  .form-container {
    background: #1e3a8a; /* azul marinho suave */
    padding: 40px 50px;
    padding-right: 80px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    width: 400px;
    color: #f0c419; /* amarelo mais macio */
    position: relative;
  }

  .form-container::before {
    content: '';
    position: absolute;
    top: -30px;
    right: -30px;
    width: 80px;
    height: 80px;
    background: url('https://cdn-icons-png.flaticon.com/512/1150/1150653.png') no-repeat center/contain;
    opacity: 0.1;
    filter: brightness(0) saturate(100%) invert(46%) sepia(52%) saturate(2320%) hue-rotate(194deg) brightness(90%) contrast(92%);
    transform: rotate(20deg);
    border-radius: 50%;
  }

  h2 {
    font-family: 'Pacifico', cursive;
    font-size: 2.5rem;
    margin-bottom: 25px;
    text-align: center;
    color: #facc15;
    text-shadow: 1px 1px 4px #1e3a8a;
  }

  label {
    display: block;
    margin: 15px 0 15px;
    font-weight: 600;
    letter-spacing: 0.9px;
    font-size: 1.1rem;
    color: #facc15;
  }

  input[type="text"],
  input[type="date"],
  input[type="file"],
  textarea {
    margin-right: 20px;
    width: 100%;
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    background: #ffffffff; /* azul mais claro */
    color: #000000ff;
    font-size: 1rem;
    box-shadow: inset 1px 1px 5px #ffffffff;
    transition: background 0.3s ease;
    resize: vertical;
  }

  input[type="text"]:focus,
  input[type="date"]:focus,
  input[type="file"]:focus,
  textarea:focus {
    background: #1e3a8a;
    outline: none;
  }

button {
  display: block;
  margin: 25px auto 0 auto; /* margem em cima e auto nos lados pra centralizar */
  padding: 12px 30px;
  background: #facc15; /* amarelo macio */
  border: none;
  border-radius: 10px;
  color: #1e3a8a; /* azul marinho */
  font-weight: 700;
  font-size: 1.2rem;
  cursor: pointer;
  box-shadow: 0 5px 15px #facc15;
  transition: background 0.3s ease, color 0.3s ease;
}


  button:hover {
    background: #ddb81a;
    color: #1e3a8a;
  }
</style>
</head>
<body>
  <div class="form-container">
    <h2>Cadastrar Pokémon</h2>
    <form method="POST" enctype="multipart/form-data" action="">
      <label for="nome_pokemon">Nome do Pokémon:</label>
      <input type="text" id="nome_pokemon" name="nome_pokemon" placeholder="Ex: Squirtle" required>

      <label for="tipo_pokemon">Tipo:</label>
      <input type="text" id="tipo_pokemon" name="tipo_pokemon" placeholder="Ex: Água" required>

      <label for="localizacao_pokemon">Localização Encontrada:</label>
      <input type="text" id="localizacao_pokemon" name="localizacao_pokemon" placeholder="Ex: Lago Azul ">

      <label for="dataregistro_pokemon">Data do Registro:</label>
      <input type="date" id="dataregistro_pokemon" name="dataregistro_pokemon" >

      <label for="hp_ataque_defesa_pokemon">HP / Ataque / Defesa:</label>
      <input type="text" id="hp_ataque_defesa_pokemon" name="hp_ataque_defesa_pokemon" placeholder="Ex: 60/45/70">

      <label for="obs_pokemon">Observações:</label>
      <textarea id="obs_pokemon" name="obs_pokemon" rows="4" cols="40" placeholder="Ex: Ama chocolate"></textarea>

      <label for="imagem_pokemon">Imagem do Pokémon:</label>
      <input type="file" id="imagem_pokemon" name="imagem_pokemon" accept="image/*">

      <button type="submit">Cadastrar Pokémon</button>
    </form>
  </div>
</body>
</html>
