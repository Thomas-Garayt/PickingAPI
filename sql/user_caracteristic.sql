-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mar 06 Mars 2018 à 15:36
-- Version du serveur :  5.5.59-0+deb8u1-log
-- Version de PHP :  7.1.13-1+0~20180105151310.14+jessie~1.gbp1086fa

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `pickingapi`
--

-- --------------------------------------------------------

--
-- Structure de la table `user_caracteristic`
--

CREATE TABLE IF NOT EXISTS `user_caracteristic` (
`id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `stamina` int(11) NOT NULL,
  `strength` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `edited_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `stamina_coefficient` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `user_caracteristic`
--

INSERT INTO `user_caracteristic` (`id`, `user_id`, `stamina`, `strength`, `created_at`, `edited_at`, `deleted_at`, `stamina_coefficient`) VALUES
(1, 1, 50, 50, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 50),
(2, 2, 90, 70, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 70),
(3, 3, 90, 60, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 75),
(4, 4, 45, 50, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 60),
(5, 5, 90, 90, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 90),
(6, 6, 50, 40, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 40),
(7, 7, 75, 55, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 45),
(8, 8, 40, 50, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 50),
(9, 9, 100, 100, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 100),
(10, 10, 85, 100, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 80),
(11, 11, 25, 25, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 25),
(12, 12, 35, 35, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 55),
(13, 13, 40, 60, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 70),
(14, 14, 15, 10, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 10),
(15, 15, 5, 5, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 10);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `user_caracteristic`
--
ALTER TABLE `user_caracteristic`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `UNIQ_751B2E14A76ED395` (`user_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `user_caracteristic`
--
ALTER TABLE `user_caracteristic`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `user_caracteristic`
--
ALTER TABLE `user_caracteristic`
ADD CONSTRAINT `FK_751B2E14A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
