-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 11-Out-2021 às 23:12
-- Versão do servidor: 5.7.31
-- versão do PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `brapci`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `source_issue`
--

DROP TABLE IF EXISTS `source_issue`;
CREATE TABLE IF NOT EXISTS `source_issue` (
  `id_is` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `is_source` int(11) NOT NULL,
  `is_source_rdf` int(11) NOT NULL,
  `is_source_issue` int(11) NOT NULL,
  `is_year` int(11) NOT NULL,
  `is_issue` char(100) COLLATE utf8_bin NOT NULL,
  `is_vol` char(15) COLLATE utf8_bin NOT NULL,
  `is_nr` char(15) COLLATE utf8_bin NOT NULL,
  `is_place` char(30) COLLATE utf8_bin NOT NULL,
  `is_edition` char(10) COLLATE utf8_bin NOT NULL,
  `is_thema` char(100) COLLATE utf8_bin NOT NULL,
  `is_cover` char(150) COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `id_is` (`id_is`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
