-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 07-Fev-2022 às 14:09
-- Versão do servidor: 8.0.23-0ubuntu0.20.04.1
-- versão do PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `brapci_cited`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cited_article`
--

CREATE TABLE `cited_article` (
  `id_ca` bigint UNSIGNED NOT NULL,
  `ca_id` char(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `ca_rdf` int NOT NULL DEFAULT '0',
  `ca_journal` int NOT NULL DEFAULT '0',
  `ca_journal_origem` int NOT NULL DEFAULT '0',
  `ca_year` int NOT NULL DEFAULT '0',
  `ca_year_origem` int NOT NULL DEFAULT '0',
  `ca_vol` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ca_nr` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ca_pag` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ca_tipo` int NOT NULL,
  `ca_text` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ca_status` int NOT NULL DEFAULT '0',
  `ca_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ca_update_at` date DEFAULT NULL,
  `ca_ordem` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cited_article`
--
ALTER TABLE `cited_article`
  ADD UNIQUE KEY `id_ca` (`id_ca`),
  ADD KEY `cited_article` (`ca_rdf`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cited_article`
--
ALTER TABLE `cited_article`
  MODIFY `id_ca` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
