-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 23-Fev-2022 às 10:07
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
-- Banco de dados: `brapci3`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `checked`
--

DROP TABLE IF EXISTS `checked`;
CREATE TABLE IF NOT EXISTS `checked` (
  `id_at` int(11) NOT NULL AUTO_INCREMENT,
  `at_rdf` int(11) NOT NULL DEFAULT '0',
  `at_type` int(11) NOT NULL DEFAULT '0',
  `at_title` int(11) NOT NULL DEFAULT '0',
  `at_abstract` int(11) NOT NULL DEFAULT '0',
  `at_keyword` int(11) NOT NULL DEFAULT '0',
  `at_portuguese` int(11) NOT NULL DEFAULT '0',
  `at_english` int(11) NOT NULL DEFAULT '0',
  `at_spanish` int(11) NOT NULL DEFAULT '0',
  `at_pdf` int(11) NOT NULL DEFAULT '0',
  `at_txt` int(11) NOT NULL DEFAULT '0',
  `at_authors` int(11) NOT NULL DEFAULT '0',
  `at_chapter` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
