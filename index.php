<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "pokemon.sql"; // Retira o ".sql" aqui, só o nome do banco mesmo
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
        /* teu CSS aqui, mantive o original pra não encher */
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
                                cardElement.remove();
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
        <h1>MundoPokémon</h1>
        <div class="navbar-buttons">
            <a href="estatistica_pokemon.php">Estatísticas</a>
            <a href="pesquisa.php">Listar</a>
            <a href="cadastro.php">Cadastrar</a>
        </div>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="busca" id="busca" placeholder="Pesquisar Pokémon..." autocomplete="off" value="<?= htmlspecialchars($pesquisa) ?>">
            <button type="submit">Buscar</button>
        </form>
        <div class="sugestoes" id="sugestoes"></div>
    </div>

    <?php if ($resultado === null): ?>
    <!-- Banner e tipos quando não há pesquisa -->
    <div class="intro-banner">
        <img src="./img/pokemoninicial.jpg" alt="Banner Pokémon">
        <div class="intro-overlay">
            <div class="intro-text-card">
                <h2>Bem-vindo ao Mundo Pokémon!</h2>
                <p>Pesquise pelo nome de um Pokémon para ver detalhes, editar ou excluir. Utilize a barra acima para começar sua jornada!</p>
            </div>
        </div>
    </div>

    <div class="tipos-container">
        <h2>Tipos de Pokémon</h2>
        <div class="tipos-grid">
            <div class="tipo-card">
                <img src="./img/pokemonfogo.jpg" alt="Tipo Fogo">
                <div class="tipo-overlay">Fogo</div>
            </div>
            <div class="tipo-card">
                <img src="./img/pokemonagua.png" alt="Tipo Água">
                <div class="tipo-overlay">Água</div>
            </div>
            <div class="tipo-card">
                <img src="./img/pokemonplanta.png" alt="Tipo Planta">
                <div class="tipo-overlay">Planta</div>
            </div>
            <div class="tipo-card">
                <img src="./img/pokemoneletrico.jpg" alt="Tipo Elétrico">
                <div class="tipo-overlay">Elétrico</div>
            </div>
            <div class="tipo-card">
                <img src="./img/pokemonpedra.jpg" alt="Tipo Pedra">
                <div class="tipo-overlay">Pedra</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($resultado !== null): ?>
        <?php if ($resultado->num_rows > 0): ?>
            <div class="galeria">
                <?php while ($row = $resultado->fetch_assoc()): ?>
                    <?php
                        $nome = htmlspecialchars($row["nome_pokemon"]);
                        $id = $row["id_pokemon"];

                        // Corrige o caminho da imagem (se tem erro de "dowloads" e se não tem caminho)
                        $imagem = $row["imagem_pokemon"];
                        $imagem = str_replace("dowloads/", "downloads/", $imagem);

                        // Se não tem barra no caminho, adiciona "uploads/"
                        if (!str_contains($imagem, "/")) {
                            $imagem = "uploads/" . $imagem;
                        }

                        // Se o arquivo não existe, usa imagem default
                        if (!file_exists($imagem)) {
                            $imagem = "img/default.jpg";
                        }
                    ?>
                    <div class="card">
                        <img src="<?= htmlspecialchars($imagem) ?>" alt="<?= $nome ?>" onerror="this.onerror=null;this.src='img/default.jpg';">
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

    <script>
        window.onload = () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
    </script>

</body>

</html>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background:rgb(239, 239, 234);
        }

        .navbar {
            background-color: #ffcb05;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 38px;
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
            font-weight:bolder ;
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
.intro-banner {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.intro-banner img {
    width: 100%;
    height: 350px;
    object-fit: cover;
    display: block;
}

.intro-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    box-sizing: border-box;
}

.intro-text-card {
    background: rgba(255, 255, 255, 0.95);
    padding: 30px 40px;
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    max-width: 600px;
    text-align: center;
    color: #333;
}

.intro-text-card h2 {
    color: #2a75bb;
    margin-top: 0;
    font-size: 28px;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .intro-banner img {
        height: 250px;
    }

    .intro-text-card {
        padding: 20px;
        font-size: 15px;
    }

    .intro-text-card h2 {
        font-size: 22px;
    }
}

.tipos-container {
    padding: 50px 30px;
    background-color: #fff;
    text-align: center;
}

.tipos-container h2 {
    color: #2a75bb;
    font-size: 26px;
    margin-bottom: 30px;
}

.tipos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 20px;
    justify-items: center;
}

.tipo-card {
    position: relative;
    width: 160px;
    height: 160px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    cursor: pointer;
}

.tipo-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.tipo-card:hover img {
    transform: scale(1.1);
}

.tipo-overlay {
    position: absolute;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    color: #fff;
    width: 100%;
    text-align: center;
    padding: 10px 0;
    font-weight: bold;
    font-size: 16px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.tipo-card:hover .tipo-overlay {
    opacity: 1;
}

    </style>