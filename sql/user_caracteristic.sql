-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 08 Mars 2018 à 12:54
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
  `stamina_coefficient` int(11) NOT NULL,
  `current_stamina` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `user_caracteristic`
--

INSERT INTO `user_caracteristic` (`id`, `user_id`, `stamina`, `strength`, `created_at`, `edited_at`, `deleted_at`, `stamina_coefficient`, `current_stamina`) VALUES
(1, 1, 50, 50, '2018-03-06 15:12:39', '2018-03-07 15:25:23', NULL, 50, 100),
(2, 2, 10, 70, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 30, 100),
(3, 3, 10, 60, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 25, 100),
(4, 4, 65, 50, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 40, 100),
(5, 5, 10, 90, '2018-03-06 15:12:39', '2018-03-07 15:26:01', NULL, 10, 100),
(6, 6, 50, 40, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 60, 100),
(7, 7, 25, 55, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 55, 100),
(8, 8, 60, 50, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 50, 100),
(9, 9, 0, 100, '2018-03-06 15:12:39', '2018-03-08 12:23:58', NULL, 0, 100),
(10, 10, 15, 100, '2018-03-06 15:12:39', '2018-03-08 12:21:38', NULL, 10, 100),
(11, 11, 75, 25, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 75, 100),
(12, 12, 65, 35, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 65, 100),
(13, 13, 60, 60, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 30, 100),
(14, 14, 85, 10, '2018-03-06 15:12:39', '2018-03-08 12:27:04', NULL, 90, 86),
(15, 15, 95, 5, '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL, 90, 100);

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
