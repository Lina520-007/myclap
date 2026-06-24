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
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `name` varchar(100) NOT NULL COMMENT 'Nom de la catégorie',
  `description` text COMMENT 'Description détaillée de la catégorie',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
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
  `return_date` date DEFAULT NULL COMMENT 'Date de retour effective',
  `status` varchar(20) NOT NULL DEFAULT 'CART' COMMENT 'Statut : CART, PENDING, VALIDATED, RETRIEVED, RETURNED',
  PRIMARY KEY (`id`),
  KEY `fk_emprunt_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `emprunt`
--

INSERT INTO `emprunt` (`id`, `user_id`, `start_date`, `end_date`, `return_date`, `status`) VALUES
(1, 1, '2026-06-21', '2026-06-23', NULL, 'PENDING');

-- --------------------------------------------------------

--
-- Structure de la table `emprunt_item`
--

DROP TABLE IF EXISTS `emprunt_item`;
CREATE TABLE IF NOT EXISTS `emprunt_item` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `emprunt_id` int NOT NULL COMMENT 'Clé étrangère vers la table emprunt',
  `product_id` int NOT NULL COMMENT 'Clé étrangère vers la table product',
  `quantity` int NOT NULL DEFAULT '1' COMMENT 'Quantité souhaitée',
  `start_date` date NOT NULL COMMENT 'Date de début de l''emprunt',
  `end_date` date NOT NULL COMMENT 'Date de fin prévue de l''emprunt',
  PRIMARY KEY (`id`),
  KEY `fk_emprunt_item_emprunt` (`emprunt_id`),
  KEY `fk_emprunt_item_product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `emprunt_item`
--

INSERT INTO `emprunt_item` (`id`, `emprunt_id`, `product_id`, `quantity`, `start_date`, `end_date`) VALUES
(1, 1, 1, 1, '2026-06-21', '2026-06-23');

-- --------------------------------------------------------

--
-- Structure de la table `product_photo`
--

DROP TABLE IF EXISTS `product_photo`;
CREATE TABLE IF NOT EXISTS `product_photo` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `product_id` int NOT NULL COMMENT 'Clé étrangère vers la table product',
  `url` varchar(255) NOT NULL COMMENT 'Chemin ou URL de la photo',
  `index` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_product_photo_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `name` varchar(150) NOT NULL COMMENT 'Nom du produit',
  `description` text COMMENT 'Description détaillée du produit',
  `bail` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Montant de la caution en euros',
  `category_id` int NOT NULL COMMENT 'Clé étrangère vers la table category',
  `stock` int NOT NULL DEFAULT '0' COMMENT 'Quantité disponible en stock',
  PRIMARY KEY (`id`),
  KEY `fk_product_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `bail`, `category_id`, `stock`) VALUES
(1, 'Boitier Lumix S5', '', 700.00, 1, 1),
(2, 'Micro Cravate Rode', '', 50.00, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `fav_product`
--

DROP TABLE IF EXISTS `fav_product`;
CREATE TABLE IF NOT EXISTS `fav_product` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `user_id` int NOT NULL COMMENT 'Clé étrangère vers la table user',
  `product_id` int NOT NULL COMMENT 'Clé étrangère vers la table product',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_fav_product` (`user_id`,`product_id`),
  KEY `fk_fav_product_product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `fav_product`
--

INSERT INTO `fav_product` (`id`, `user_id`, `product_id`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Clé primaire auto-incrémentée',
  `name` varchar(100) NOT NULL COMMENT 'Nom complet de l''utilisateur',
  `role` int NOT NULL DEFAULT '0' COMMENT 'Rôle : 0 = membre, 1 = admin',
  `password` varchar(255) NOT NULL COMMENT 'Mot de passe hashé',
  `flat_num` varchar(100) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL COMMENT 'Email,tél ou facebook',
  `score` int NOT NULL DEFAULT '0' COMMENT 'Points d experience de l''utilisateur',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `name`, `role`, `password`, `flat_num`, `contact`, `score`) VALUES
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
  ADD CONSTRAINT `fk_emprunt_item_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `product_photo`
--
ALTER TABLE `product_photo`
  ADD CONSTRAINT `fk_product_photo_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `product_photo` (`id`, `product_id`, `index`, `url`) VALUES (1, 1, 1, 'ressources/Panasonic_Lumix_S5.jpg'), (2, 2, 1, 'ressources/micro_rode.jpg');
--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `fav_product`
--
ALTER TABLE `fav_product`
  ADD CONSTRAINT `fk_fav_product_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fav_product_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
