<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "pokemon.sql";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

$termo = isset($_GET['termo']) ? trim($_GET['termo']) : '';

if ($termo !== '') {
    $stmt = $conn->prepare("SELECT nome_pokemon FROM cadastro WHERE nome_pokemon LIKE ? LIMIT 10");
    $param = "%" . $termo . "%";
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $res = $stmt->get_result();

    $nomes = [];
    while ($row = $res->fetch_assoc()) {
        $nomes[] = $row['nome_pokemon'];
    }

    echo json_encode($nomes);
}
?>
