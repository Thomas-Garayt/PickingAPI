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
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `caracteristic_id` int(11) DEFAULT NULL,
  `identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `edited_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `caracteristic_id`, `identifier`, `firstname`, `lastname`, `email`, `password`, `username`, `created_at`, `edited_at`, `deleted_at`) VALUES
(1, 1, 'admin', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'admin', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(2, 2, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'marc', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(3, 3, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'paul', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(4, 4, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'julie', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(5, 5, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'alexa', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(6, 6, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'diego', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(7, 7, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'linda', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(8, 8, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'sofia', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(9, 9, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'clarkkent', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(10, 10, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'hercule', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(11, 11, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'ben', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(12, 12, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'albert', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(13, 13, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'jules', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(14, 14, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'jacob', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL),
(15, 15, '', '', '', '', '$2y$12$6Wo0il65EF5DuvOGScrFSORz1KMvEmohiodLSPnCoAmWNW78ksnsK', 'mimi', '2018-03-06 15:12:39', '2018-03-06 15:12:39', NULL);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `UNIQ_1483A5E9F85E0677` (`username`), ADD UNIQUE KEY `UNIQ_1483A5E981194CF4` (`caracteristic_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `FK_1483A5E981194CF4` FOREIGN KEY (`caracteristic_id`) REFERENCES `user_caracteristic` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
