-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 19 juin 2026 à 23:15
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `siteemprunt2`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `name` varchar(100) NOT NULL COMMENT 'Nom de la catégorie',
  `description` text COMMENT 'Description détaillée de la catégorie',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `name`, `description`) VALUES
(1, 'Boitier de caméra', ''),
(2, 'Objectifs', 'Les boitiers de caméra ont besoin d\un objectif adapté à vos besoins pour fonctionner'),
(3, 'Micros', '');

-- --------------------------------------------------------

--
-- Structure de la table `emprunt`
--

DROP TABLE IF EXISTS `emprunt`;
CREATE TABLE IF NOT EXISTS `emprunt` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `user_id` int NOT NULL COMMENT 'Clé étrangère vers la table user',
  `start_date` date NOT NULL COMMENT 'Date de début de l''emprunt',
  `end_date` date NOT NULL COMMENT 'Date de fin prévue de l''emprunt',
  `actual_return_date` date DEFAULT NULL COMMENT 'Date de retour effective',
  `status` varchar(20) NOT NULL DEFAULT 'en attente' COMMENT 'Statut : en attente, à valider, validé, annulé, en cours, fini',
  PRIMARY KEY (`id`),
  KEY `fk_emprunt_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `emprunt`
--

INSERT INTO `emprunt` (`id`, `user_id`, `start_date`, `end_date`, `actual_return_date`, `status`) VALUES
(1, 1, '2026-06-21', '2026-06-23', '0000-00-00', 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `emprunt_item`
--

DROP TABLE IF EXISTS `emprunt_item`;
CREATE TABLE IF NOT EXISTS `emprunt_item` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `emprunt_id` int NOT NULL COMMENT 'Clé étrangère vers la table emprunt',
  `product_id` int NOT NULL COMMENT 'Clé étrangère vers la table produit',
  `status` varchar(20) NOT NULL DEFAULT 'en attente',
  PRIMARY KEY (`id`),
  KEY `fk_emprunt_item_emprunt` (`emprunt_id`),
  KEY `fk_emprunt_item_produit` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `emprunt_item`
--

INSERT INTO `emprunt_item` (`id`, `emprunt_id`, `product_id`, `status`) VALUES
(1, 1, 1, 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `customerId` int NOT NULL COMMENT 'Clé étrangère vers la table user',
  PRIMARY KEY (`id`),
  KEY `fk_panier_user` (`customerId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`id`, `customerId`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `panier_item`
--

DROP TABLE IF EXISTS `panier_item`;
CREATE TABLE IF NOT EXISTS `panier_item` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `panierId` int NOT NULL COMMENT 'Clé étrangère vers la table panier',
  `productId` int NOT NULL COMMENT 'Clé étrangère vers la table produit',
  `itemQte` int NOT NULL DEFAULT '1' COMMENT 'Quantité souhaitée',
  `dateDebutEmprunt` date NOT NULL COMMENT 'Date de début souhaitée pour l''emprunt',
  `dateFinEmprunt` date NOT NULL COMMENT 'Date de fin souhaitée pour l''emprunt',
  PRIMARY KEY (`id`),
  KEY `fk_panier_item_panier` (`panierId`),
  KEY `fk_panier_item_produit` (`productId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `panier_item`
--

INSERT INTO `panier_item` (`id`, `panierId`, `productId`, `itemQte`, `dateDebutEmprunt`, `dateFinEmprunt`) VALUES
(1, 1, 1, 1, '2026-08-06', '2026-09-01');

-- --------------------------------------------------------

--
-- Structure de la table `photos_produit`
--

DROP TABLE IF EXISTS `photos_produit`;
CREATE TABLE IF NOT EXISTS `photos_produit` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `produitId` int NOT NULL COMMENT 'Clé étrangère vers la table produit',
  `url` varchar(255) NOT NULL COMMENT 'Chemin ou URL de la photo',
  `ordre` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_photos_produit` (`produitId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `nom` varchar(150) NOT NULL COMMENT 'Nom du produit',
  `description` text COMMENT 'Description détaillée du produit',
  `caution` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Montant de la caution en euros',
  `categorieId` int NOT NULL COMMENT 'Clé étrangère vers la table categorie',
  PRIMARY KEY (`id`),
  KEY `fk_produit_categorie` (`categorieId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `description`, `caution`, `categorieId`) VALUES
(1, 'Boitier Lumix S5', '', 700.00, 1),
(2, 'Micro Cravate Rode', '', 50.00, 3);

-- --------------------------------------------------------

--
-- Structure de la table `produit_favori`
--

DROP TABLE IF EXISTS `produit_favori`;
CREATE TABLE IF NOT EXISTS `produit_favori` (
  `userId` int NOT NULL COMMENT 'Clé étrangère vers la table user',
  `produitId` int NOT NULL COMMENT 'Clé étrangère vers la table produit',
  PRIMARY KEY (`userId`,`produitId`),
  KEY `fk_favori_produit` (`produitId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `produit_favori`
--

INSERT INTO `produit_favori` (`userId`, `produitId`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `nom` varchar(100) NOT NULL COMMENT 'Nom complet de l''utilisateur',
  `role` int NOT NULL DEFAULT '0' COMMENT 'Rôle : 0 = membre, 1 = admin',
  `mdp` varchar(255) NOT NULL COMMENT 'Mot de passe hashé',
  `numAppart` varchar(100) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL COMMENT 'Email,tél ou facebook',
  `points` int NOT NULL DEFAULT '0' COMMENT 'Points d experience de l''utilisateur',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `role`, `mdp`, `numAppart`, `contact`, `points`) VALUES
(1, 'User1', 1, 'mdp', 'B302', 'User@gmail.com', 0),
(2, 'User2', 1, 'mdp', 'A202', 'user2 sur messenger', 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `emprunt`
--
ALTER TABLE `emprunt`
  ADD CONSTRAINT `fk_emprunt_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `emprunt_item`
--
ALTER TABLE `emprunt_item`
  ADD CONSTRAINT `fk_emprunt_item_emprunt` FOREIGN KEY (`emprunt_id`) REFERENCES `emprunt` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_emprunt_item_produit` FOREIGN KEY (`product_id`) REFERENCES `produit` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `fk_panier_user` FOREIGN KEY (`customerId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `panier_item`
--
ALTER TABLE `panier_item`
  ADD CONSTRAINT `fk_panier_item_panier` FOREIGN KEY (`panierId`) REFERENCES `panier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_panier_item_produit` FOREIGN KEY (`productId`) REFERENCES `produit` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `photos_produit`
--
ALTER TABLE `photos_produit`
  ADD CONSTRAINT `fk_photos_produit` FOREIGN KEY (`produitId`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk_produit_categorie` FOREIGN KEY (`categorieId`) REFERENCES `categorie` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit_favori`
--
ALTER TABLE `produit_favori`
  ADD CONSTRAINT `fk_favori_produit` FOREIGN KEY (`produitId`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favori_user` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
