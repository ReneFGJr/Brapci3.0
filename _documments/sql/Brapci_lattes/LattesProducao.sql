-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 25-Fev-2022 às 16:47
-- Versão do servidor: 10.4.13-MariaDB
-- versão do PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `brapci_lattes`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `LattesProducao`
--

DROP TABLE IF EXISTS `LattesProducao`;
CREATE TABLE IF NOT EXISTS `LattesProducao` (
  `id_lp` int(11) NOT NULL AUTO_INCREMENT,
  `lp_author` bigint(20) NOT NULL,
  `lp_author_total` int(11) NOT NULL,
  `lp_brapci_rdf` int(11) NOT NULL,
  `lp_authors` text NOT NULL,
  `lp_title` text NOT NULL,
  `lp_ano` varchar(4) NOT NULL,
  `lp_url` varchar(120) NOT NULL,
  `lp_doi` varchar(120) NOT NULL,
  `lp_issn` varchar(9) NOT NULL,
  `lp_journal` varchar(80) NOT NULL,
  `lp_vol` varchar(10) NOT NULL,
  `lp_nr` varchar(10) NOT NULL,
  `lp_place` varchar(40) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_lp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
