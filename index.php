<?php
// Conexão
$host = "localhost";
$user = "root";
$pass = "";
$db = "pokemon.sql";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Erro: " . $conn->connect_error);

$pesquisa = "";
$resultado = null;

if (isset($_GET["busca"]) && !empty(trim($_GET["busca"]))) {
    $pesquisa = $_GET["busca"];
    $stmt = $conn->prepare("SELECT * FROM cadastro WHERE nome_pokemon LIKE ?");
    $param = "%" . $pesquisa . "%";
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $resultado = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Buscar Pokémon</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: #f2f2f2;
        }

        .navbar {
            background-color: #ffcb05;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }

        .navbar h1 {
            margin: 0;
            color: #2a75bb;
            font-size: 24px;
        }

        .navbar-buttons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-buttons a {
            text-decoration: none;
            color: #2a75bb;
            font-weight: bold;
            font-size: 16px;
            padding-top: 5px;
        }


        .navbar-buttons .botao-cadastrar {
            background-color: #2a75bb;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }



        .search-bar {
            padding: 20px;
            text-align: center;
        }

        .search-bar input {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .search-bar button {
            padding: 10px 15px;
            margin-left: 10px;
            font-size: 16px;
            background-color: #2a75bb;
            color: white;
            border: none;
            border-radius: 8px;
        }

        .sugestoes {
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            width: 300px;
            margin-left: auto;
            margin-right: auto;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .sugestoes div {
            padding: 10px;
            cursor: pointer;
        }

        .sugestoes div:hover {
            background-color: #eee;
        }

        .galeria {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 20px;
            padding: 30px;
        }

        .card {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: white;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .descricao {
            position: absolute;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            width: 100%;
            transform: translateY(100%);
            transition: transform 0.3s ease-in-out;
            padding: 10px;
            font-size: 14px;
        }

        .card:hover .descricao {
            transform: translateY(0%);
        }

        .nome-pokemon {
            padding: 10px;
            font-weight: bold;
            text-align: center;
            color: #2a75bb;
        }

        .botoes {
            display: flex;
            justify-content: center;
            padding-bottom: 10px;
            gap: 10px;
        }

        .botoes a {
            background-color: #2a75bb;
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .nenhum-resultado {
            text-align: center;
            color: #999;
            font-size: 18px;
            margin-top: 30px;
        }

        .botoes button {
            background-color: #d33;
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
    </style>

    <script>
        function excluirPokemon(id, nome, cardElement) {
            Swal.fire({
                title: `Deseja excluir ${nome}?`,
                text: "Essa ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('excluir_pokemon.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id=${id}`
                        })
                        .then(response => response.text())
                        .then(data => {
                            if (data.trim() === "ok") {
                                Swal.fire(
                                    'Excluído!',
                                    `${nome} foi removido com sucesso.`,
                                    'success'
                                );
                                cardElement.remove(); // Remove o card da tela
                            } else {
                                Swal.fire('Erro!', 'Não foi possível excluir.', 'error');
                            }
                        });
                }
            });
        }
    </script>



</head>

<body>

    <div class="navbar">
        <h1>Pokemon</h1>
        <div class="navbar-buttons">
            <a href="estatistica_pokemon.php">Estatísticas</a>
            <a href="pesquisa.php">Listar</a>
            <a href="lista_pokemon.php">Cadastrar</a>
        </div>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="busca" id="busca" placeholder="Pesquisar Pokémon..." autocomplete="off" value="<?= htmlspecialchars($pesquisa) ?>">
            <button type="submit">Buscar</button>
        </form>
        <div class="sugestoes" id="sugestoes"></div>
    </div>

    <?php if ($resultado !== null): ?>
        <?php if ($resultado->num_rows > 0): ?>
            <div class="galeria">
                <?php while ($row = $resultado->fetch_assoc()):
                    $nome = htmlspecialchars($row["nome_pokemon"]);
                    $id = $row["id_pokemon"];
                    $imagem = "imagens/" . strtolower($nome) . ".jpg";
                ?>
                    <div class="card">
                        <img src="<?= $imagem ?>" alt="<?= $nome ?>" onerror="this.src='imagens/default.jpg'">
                        <div class="nome-pokemon"><?= $nome ?></div>
                        <div class="botoes">
                            <a href="editar_pokemon.php?id=<?= $id ?>">Editar</a>
                            <button onclick="excluirPokemon(<?= $id ?>, '<?= addslashes($nome) ?>', this.closest('.card'))">Excluir</button>

                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="nenhum-resultado">Nenhum Pokémon encontrado para "<?= htmlspecialchars($pesquisa) ?>"</p>
        <?php endif; ?>
    <?php endif; ?>

    <script>
        const inputBusca = document.getElementById("busca");
        const sugestoes = document.getElementById("sugestoes");

        inputBusca.addEventListener("input", function() {
            const termo = inputBusca.value.trim();
            if (termo.length === 0) {
                sugestoes.innerHTML = "";
                return;
            }

            fetch(`buscar_nomes.php?termo=${encodeURIComponent(termo)}`)
                .then(res => res.json())
                .then(data => {
                    sugestoes.innerHTML = "";
                    data.forEach(nome => {
                        const div = document.createElement("div");
                        div.textContent = nome;
                        div.onclick = () => {
                            inputBusca.value = nome;
                            sugestoes.innerHTML = "";
                        };
                        sugestoes.appendChild(div);
                    });
                });
        });

        document.addEventListener("click", (e) => {
            if (!sugestoes.contains(e.target) && e.target !== inputBusca) {
                sugestoes.innerHTML = "";
            }
        });
    </script>

</body>

</html>