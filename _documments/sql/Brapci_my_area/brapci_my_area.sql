-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 14/05/2022 às 00:44
-- Versão do servidor: 8.0.29-0ubuntu0.20.04.3
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
-- Banco de dados: `brapci_my_area`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `my_files`
--

CREATE TABLE `my_files` (
  `id_file` bigint UNSIGNED NOT NULL,
  `file_full` char(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `file_path_logical` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `file_name` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `file_type` int DEFAULT NULL,
  `file_size` float DEFAULT '0',
  `file_cotenttype` int NOT NULL,
  `file_ext` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `file_data` date DEFAULT NULL,
  `file_save` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `file_own` int NOT NULL,
  `file_own_gropup` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Despejando dados para a tabela `my_files`
--

INSERT INTO `my_files` (`id_file`, `file_full`, `file_path_logical`, `file_name`, `file_type`, `file_size`, `file_cotenttype`, `file_ext`, `file_data`, `file_save`, `file_own`, `file_own_gropup`) VALUES
(1, '../.tmp/.user/1/4c53255d06f2c3ca58d825943cf362ab', '', '0000.pdf', -1, 2509750, 0, 'pdf', '2022-05-13', '1', 1, 0),
(2, '../.tmp/.user/1/338bbd7523583baa505a2dd8c31981a0', '', '0001.pdf', -1, 2753320, 0, 'pdf', '2022-05-13', '1', 1, 0),
(3, '../.tmp/.user/1/b1f7cfbeb27f2981a052de7596274e24', '', '0002.pdf', -1, 1527350, 0, 'pdf', '2022-05-13', '1', 1, 0),
(4, '../.tmp/.user/1/5117e93014fbe271755fc0180734f3b6', '', '0003.pdf', -1, 2091540, 0, 'pdf', '2022-05-13', '1', 1, 0),
(5, '../.tmp/.user/1/425c1afd127a0ab9343da5056d50a5d7', '', '0004.pdf', -1, 2189930, 0, 'pdf', '2022-05-13', '1', 1, 0),
(6, '../.tmp/.user/1/10582da243289a11716ec2a687fa73c9', '', '0005.pdf', -1, 885633, 0, 'pdf', '2022-05-13', '1', 1, 0),
(7, '../.tmp/.user/1/7f02eab7542cdade8c889a47f0025608', '', '0006.pdf', -1, 6735850, 0, 'pdf', '2022-05-13', '1', 1, 0),
(8, '../.tmp/.user/1/64b228ac42d9e613aa724a72b71b5061', '', '0007.pdf', -1, 2993780, 0, 'pdf', '2022-05-13', '1', 1, 0),
(9, '../.tmp/.user/1/47218c8f69a4e4d2745c685a3c0ef3e5', '', '0008.pdf', -1, 764775, 0, 'pdf', '2022-05-13', '1', 1, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `my_group`
--

CREATE TABLE `my_group` (
  `id_gr` int NOT NULL,
  `gr_name` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `gr_own` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Estrutura para tabela `my_group_member`
--

CREATE TABLE `my_group_member` (
  `id_grm` bigint UNSIGNED NOT NULL,
  `grm_user` int NOT NULL,
  `gmr_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `my_files`
--
ALTER TABLE `my_files`
  ADD UNIQUE KEY `id_file` (`id_file`);

--
-- Índices de tabela `my_group_member`
--
ALTER TABLE `my_group_member`
  ADD UNIQUE KEY `id_grm` (`id_grm`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `my_files`
--
ALTER TABLE `my_files`
  MODIFY `id_file` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `my_group_member`
--
ALTER TABLE `my_group_member`
  MODIFY `id_grm` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
