-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Jul-2025 às 16:56
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pokemon.sql`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastro`
--

CREATE TABLE `cadastro` (
  `id_pokemon` int(120) NOT NULL,
  `nome_pokemon` varchar(120) NOT NULL,
  `tipo_pokemon` varchar(120) NOT NULL,
  `localizacao_pokemon` varchar(120) NOT NULL,
  `dataregistro_pokemon` date NOT NULL,
  `hp_ataque_defesa_pokemon` varchar(120) NOT NULL,
  `obs_pokemon` varchar(255) NOT NULL,
  `imagem_pokemon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cadastro`
--

INSERT INTO `cadastro` (`id_pokemon`, `nome_pokemon`, `tipo_pokemon`, `localizacao_pokemon`, `dataregistro_pokemon`, `hp_ataque_defesa_pokemon`, `obs_pokemon`, `imagem_pokemon`) VALUES
(1, 'Dratini', 'Dragão', 'Lago Secreto', '2025-07-11', '41/64/45', 'Muito raro e elegante', 'dowloads/Dratini.jpg'),
(2, 'Pikachu', 'Elétrico', 'Floresta de Viridian', '2025-07-01', '35/55/40', 'Adora ketchup, super fofo', '6888ccd9acc51.jpg'),
(3, 'Bulbasaur', 'Planta/Veneno', 'Pallet Town', '2025-07-02', '45/49/49', 'Sempre com uma planta nas costas', '6888cd2929d19.jpg'),
(4, 'Charmander', 'Fogo', 'Rota 3', '2025-07-03', '39/52/43', 'Muito corajoso e impulsivo', '6888cd679cf40.jpg'),
(5, 'Squirtle', 'Água', 'Lago Azul', '2025-07-04', '44/48/65', 'Ama nadar e se esconder na concha', '6888cdc54c936.jpg'),
(6, 'Eevee', 'Normal', 'Cidade Celadon', '2025-07-05', '55/55/50', 'Pode evoluir de várias formas!', '6888ce0f3c672.jpg'),
(7, 'Jigglypuff', 'Normal/Fada', 'Floresta Encantada', '2025-07-06', '115/45/20', 'Canta e faz todo mundo dormir', '6888ce84629ce.jpg'),
(8, 'Meowth', 'Normal', 'Beco de Celadon', '2025-07-07', '	40/45/35', 'Fala como humano e é da Equipe Rocket', '6888cf123ab76.jpg'),
(9, 'Psyduck', 'Água', 'Riacho Nebuloso', '0000-00-00', '50/52/48', 'Tem dores de cabeça psíquicas', '6888cf71eaaaa.jpg'),
(10, 'Gengar', 'Fantasma/Veneno', 'Torre Pokémon', '0000-00-00', '60/65/60', 'Aparece no escuro, meio sinistro ', '6888cfaf7bb16.jpg'),
(11, 'Snorlax', 'Normal', 'Montanha Dorminhoca', '0000-00-00', '160/110/65', 'Vive dormindo no meio da estrada ', '6888cfe953c08.jpg'),
(12, 'Machop', 'Lutador', 'Caverna Rochosa', '2025-07-15', '70/80/50', 'Treina todos os dias com pesos', 'dowloads/machop.png'),
(13, 'Vulpix', 'Fogo', 'Vulcão Nevado', '2025-07-16', '38/41/40', 'Fofinho com múltiplas caudas', 'dowloads/vulpix.png'),
(14, 'Abra', 'Psíquico', 'Torre Misteriosa', '2025-07-17', '25/20/15', 'Some antes que você possa atacar', 'dowloads/abra.png'),
(15, 'Oddish', 'Planta/Veneno', 'Campo de Girassóis', '2025-07-18', '45/50/55', 'Adora a noite, foge da luz', 'dowloads/oddish.png'),
(16, 'Poliwag', 'Água', 'Lago Espiral', '2025-07-13', '40/50/40', 'Seu redemoinho hipnotiza', 'dowloads/poliwag.png'),
(17, 'Growlithe', 'Fogo', 'Floresta Vulcânica', '2025-07-14', '55/70/45', 'Leal como um cãozinho de guarda', 'dowloads/growlithe.png'),
(18, 'Magnemite', 'Elétrico/Aço', 'Usina Abandonada', '2025-07-12', '25/35/70', 'Flutua com eletromagnetismo', 'dowloads/magnemite.png'),
(19, 'Cubone', 'Terra', 'Vale dos Ossos', '2025-07-10', '50/50/95', 'Chora pela mãe... triste mas forte', 'dowloads/cubone.png'),
(20, 'Togepi', 'Fada', 'Jardim das Estrelas', '2025-07-09', '35/20/65', 'Transmite alegria com sua presença', 'dowloads/togepi.png');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cadastro`
--
ALTER TABLE `cadastro`
  ADD PRIMARY KEY (`id_pokemon`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cadastro`
--
ALTER TABLE `cadastro`
  MODIFY `id_pokemon` int(120) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
