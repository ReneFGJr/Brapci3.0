-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 03-Set-2022 às 12:59
-- Versão do servidor: 5.7.36
-- versão do PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `brapci_chatbot`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE IF NOT EXISTS `answers` (
  `id_aw` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `aw_question` char(150) COLLATE utf8_bin NOT NULL,
  `aw_answer` text COLLATE utf8_bin NOT NULL,
  `aw_method` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id_aw` (`id_aw`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id_m` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `m_message` text COLLATE utf8mb4_bin NOT NULL,
  `m_ip` char(16) COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `id_m` (`id_m`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Extraindo dados da tabela `messages`
--

INSERT INTO `messages` (`id_m`, `m_message`, `m_ip`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Bom dia', '143.54.112.77', '2022-08-31 20:46:20', NULL, NULL),
(2, 'Ola', '189.6.254.199', '2022-08-31 22:09:48', NULL, NULL),
(3, 'Qual seu nome?', '189.6.254.199', '2022-08-31 22:10:09', NULL, NULL),
(4, 'Hello', '200.173.208.184', '2022-09-01 17:09:31', NULL, NULL),
(5, 'Qual e seu nome', '200.173.208.184', '2022-09-01 17:09:48', NULL, NULL),
(6, 'Hello', '200.173.208.184', '2022-09-01 17:09:56', NULL, NULL),
(7, 'Ciência da informacao', '200.173.208.184', '2022-09-01 17:10:27', NULL, NULL),
(8, 'Rene', '200.173.208.184', '2022-09-01 17:10:37', NULL, NULL),
(9, 'Terminologia', '143.54.112.77', '2022-09-01 19:28:04', NULL, NULL),
(10, 'Gostaria de saber sobre o tempo hoje', '143.54.112.77', '2022-09-01 19:30:30', NULL, NULL),
(11, 'Quero todos os textos da profa. Rita Laipelt', '143.54.112.77', '2022-09-01 19:31:20', NULL, NULL),
(12, 'Ola', '189.6.254.199', '2022-09-02 01:08:27', NULL, NULL),
(13, 'Bom dia', '187.71.142.252', '2022-09-02 11:46:54', NULL, NULL),
(14, 'Repositório de dados', '143.54.112.65', '2022-09-02 13:50:56', NULL, NULL),
(15, 'Ansiedade informacional', '143.54.112.65', '2022-09-02 13:51:22', NULL, NULL),
(16, 'Brapci', '143.54.112.65', '2022-09-02 13:51:32', NULL, NULL),
(17, 'Twitter', '143.54.112.65', '2022-09-02 13:51:43', NULL, NULL),
(18, 'Chatbot', '143.54.112.65', '2022-09-02 13:51:51', NULL, NULL),
(19, 'Pós-graduação', '143.54.112.65', '2022-09-02 14:48:31', NULL, NULL),
(20, 'Inteligência', '143.54.112.65', '2022-09-02 14:48:42', NULL, NULL),
(21, 'saúde', '143.54.112.65', '2022-09-02 14:48:46', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `method`
--

DROP TABLE IF EXISTS `method`;
CREATE TABLE IF NOT EXISTS `method` (
  `id_m` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `m_method` text COLLATE utf8_bin NOT NULL,
  `m_code` text COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `id_m` (`id_m`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `method`
--

INSERT INTO `method` (`id_m`, `m_method`, `m_code`) VALUES
(1, 'Assuntos', ''),
(2, 'AboutMe', ''),
(3, 'DateTime', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `skos`
--

DROP TABLE IF EXISTS `skos`;
CREATE TABLE IF NOT EXISTS `skos` (
  `id_sk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sk_uri` char(150) COLLATE utf8_bin NOT NULL,
  `sk_name` char(100) COLLATE utf8_bin NOT NULL,
  `sk_description` text COLLATE utf8_bin NOT NULL,
  `sk_concepts` int(11) NOT NULL DEFAULT '0',
  `sk_terms` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `id_sk` (`id_sk`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `skos`
--

INSERT INTO `skos` (`id_sk`, `sk_uri`, `sk_name`, `sk_description`, `sk_concepts`, `sk_terms`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'https://www.ufrgs.br/tesauros/index.php/thesa/terms/106', 'Brapci - Tabela de países', '', 0, 0, '2022-09-03 11:24:20', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'https://www.ufrgs.br/tesauros/index.php/thesa/terms/373', 'AskMe', '', 0, 0, '2022-09-03 12:47:44', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'https://www.ufrgs.br/tesauros/index.php/thesa/terms/545', 'ChatBot - Apresentação', '', 0, 0, '2022-09-03 12:54:21', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'https://www.ufrgs.br/tesauros/index.php/thesa/terms/64', 'Ciência da Informação', '', 0, 0, '2022-09-03 12:56:47', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vc_link`
--

DROP TABLE IF EXISTS `vc_link`;
CREATE TABLE IF NOT EXISTS `vc_link` (
  `id_lk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lk_word_0` int(11) DEFAULT NULL,
  `lk_word_1` int(11) DEFAULT NULL,
  `lk_word_2` int(11) DEFAULT NULL,
  `lk_word_3` int(11) DEFAULT NULL,
  `lk_word_4` int(11) DEFAULT NULL,
  `lk_word_5` int(11) DEFAULT NULL,
  `lk_word_6` int(11) DEFAULT NULL,
  `lk_word_7` int(11) DEFAULT NULL,
  `lk_word_8` int(11) DEFAULT NULL,
  `lk_word_9` int(11) DEFAULT NULL,
  UNIQUE KEY `id_lk` (`id_lk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `vc_word`
--

DROP TABLE IF EXISTS `vc_word`;
CREATE TABLE IF NOT EXISTS `vc_word` (
  `id_vc` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vc_prefLabel` char(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `id_vc` (`id_vc`),
  UNIQUE KEY `vc_prefLabel` (`vc_prefLabel`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
