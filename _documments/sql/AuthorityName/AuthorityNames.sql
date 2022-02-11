-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 11/02/2022 às 13:28
-- Versão do servidor: 8.0.28-0ubuntu0.20.04.3
-- Versão do PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `brapci_authority`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `AuthorityNames`
--

CREATE TABLE `AuthorityNames` (
  `id_a` int NOT NULL,
  `a_class` varchar(1) NOT NULL,
  `a_uri` varchar(120) NOT NULL,
  `a_use` int NOT NULL,
  `a_prefTerm` varchar(120) NOT NULL,
  `a_lattes` varchar(24) NOT NULL,
  `a_brapci` int NOT NULL DEFAULT '0',
  `a_orcid` varchar(30) NOT NULL,
  `a_master` int NOT NULL DEFAULT '0',
  `a_UF` int NOT NULL DEFAULT '0',
  `a_country` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `AuthorityNames`
--
ALTER TABLE `AuthorityNames`
  ADD PRIMARY KEY (`id_a`,`a_prefTerm`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `AuthorityNames`
--
ALTER TABLE `AuthorityNames`
  MODIFY `id_a` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
