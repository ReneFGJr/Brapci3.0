-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 12-Mar-2022 às 16:36
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
-- Estrutura da tabela `users_log`
--

DROP TABLE IF EXISTS `users_log`;
CREATE TABLE IF NOT EXISTS `users_log` (
  `id_ul` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ul_user` int(11) NOT NULL,
  `ul_access` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ul_ip` char(20) NOT NULL,
  UNIQUE KEY `id_ul` (`id_ul`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
