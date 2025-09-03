-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 03 sep. 2025 à 10:12
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `centre_aere_les_loupiots`
--

-- --------------------------------------------------------

--
-- Structure de la table `activites`
--

DROP TABLE IF EXISTS `activites`;
CREATE TABLE IF NOT EXISTS `activites` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `capacity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `categorie` enum('centre','sortie') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'centre',
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `activites`
--

INSERT INTO `activites` (`id`, `titre`, `nom`, `description`, `capacity`, `created_at`, `categorie`, `date_debut`, `date_fin`) VALUES
(1, 'Activité #1', 'Atelier sport', 'Jeux collectifs en extérieur', 75, '2025-08-18 12:47:22', 'centre', '2025-09-09 09:00:00', '2025-09-09 17:30:00'),
(2, 'Activité #2', 'Arts créatifs', 'Peinture & bricolage', 75, '2025-08-18 12:47:22', 'centre', '2025-09-09 09:00:00', '2025-09-09 17:30:00'),
(3, 'Piscine municipale', '', 'Séance piscine', 54, '2025-08-18 13:13:51', 'sortie', '2025-09-01 14:00:00', '2025-09-09 17:30:00'),
(4, 'Sortie vélo + pique-nique', '', 'Balade + repas', 54, '2025-08-18 13:13:51', 'sortie', '2025-09-02 10:00:00', '2025-09-09 17:30:00'),
(5, 'Atelier peinture', '', 'Activité manuelle au centre', 75, '2025-08-18 14:19:25', 'centre', '2025-09-09 09:00:00', '2025-09-09 17:30:00'),
(6, 'Journée plage + pique-nique', '', 'Bus + baignade', 54, '2025-08-18 14:19:25', 'sortie', '2025-09-07 09:00:00', '2025-09-09 17:30:00'),
(7, 'Cinéma', '', 'Projection jeunesse', 54, '2025-08-18 14:19:26', 'sortie', '2025-09-03 14:00:00', '2025-09-09 17:30:00');

-- --------------------------------------------------------

--
-- Structure de la table `activite_certificats`
--

DROP TABLE IF EXISTS `activite_certificats`;
CREATE TABLE IF NOT EXISTS `activite_certificats` (
  `activite_id` int NOT NULL,
  `certificat_type_id` int NOT NULL,
  PRIMARY KEY (`activite_id`,`certificat_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `activite_certificats`
--

INSERT INTO `activite_certificats` (`activite_id`, `certificat_type_id`) VALUES
(3, 1),
(4, 2),
(6, 1);

-- --------------------------------------------------------

--
-- Structure de la table `certificat_types`
--

DROP TABLE IF EXISTS `certificat_types`;
CREATE TABLE IF NOT EXISTS `certificat_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `certificat_types`
--

INSERT INTO `certificat_types` (`id`, `code`, `libelle`) VALUES
(1, 'natation', 'Certificat de natation'),
(2, 'velo', 'Certificat de pratique du vélo');

-- --------------------------------------------------------

--
-- Structure de la table `enfants`
--

DROP TABLE IF EXISTS `enfants`;
CREATE TABLE IF NOT EXISTS `enfants` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `enfants`
--

INSERT INTO `enfants` (`id`, `nom`, `prenom`, `date_naissance`) VALUES
(1, 'Martin', 'Léa', '2015-04-12'),
(2, 'Durand', 'Noah', '2013-09-03'),
(3, 'Martin', 'Zoé', '2015-03-14'),
(6, 'Petit', 'Léo', '2015-06-12'),
(7, 'Roux', 'Emma', '2014-03-22');

-- --------------------------------------------------------

--
-- Structure de la table `enfant_certificats`
--

DROP TABLE IF EXISTS `enfant_certificats`;
CREATE TABLE IF NOT EXISTS `enfant_certificats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `enfant_id` int NOT NULL,
  `certificat_type_id` int NOT NULL,
  `fichier_path` varchar(255) NOT NULL,
  `statut` enum('en_attente','valide','refuse') NOT NULL DEFAULT 'en_attente',
  `verified_at` datetime DEFAULT NULL,
  `verified_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_enfant_certif_type` (`enfant_id`,`certificat_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `enfant_certificats`
--

INSERT INTO `enfant_certificats` (`id`, `enfant_id`, `certificat_type_id`, `fichier_path`, `statut`, `verified_at`, `verified_by`, `created_at`) VALUES
(1, 1, 1, '/uploads/certifs/1755532185_Certificatdenatation.docx', 'valide', '2025-08-18 17:52:41', 1, '2025-08-18 15:49:45'),
(2, 6, 1, '/uploads/certifs/1756382919_Certificatdenatation.docx', 'valide', '2025-08-28 14:24:18', 1, '2025-08-28 12:08:39');

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

DROP TABLE IF EXISTS `inscriptions`;
CREATE TABLE IF NOT EXISTS `inscriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `enfant_id` int NOT NULL,
  `activite_id` int NOT NULL,
  `statut` enum('en_attente','valide','annulee') NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_enfant_activite` (`enfant_id`,`activite_id`),
  KEY `idx_act` (`activite_id`),
  KEY `idx_enf` (`enfant_id`),
  KEY `idx_statut` (`statut`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `inscriptions`
--

INSERT INTO `inscriptions` (`id`, `enfant_id`, `activite_id`, `statut`, `created_at`) VALUES
(1, 2, 4, 'en_attente', '2025-08-18 13:14:22'),
(4, 1, 4, 'valide', '2025-08-18 13:35:43'),
(5, 7, 4, 'valide', '2025-08-18 13:36:28'),
(6, 7, 2, 'valide', '2025-08-18 13:38:03'),
(9, 6, 4, 'valide', '2025-08-18 13:45:04'),
(10, 3, 4, 'valide', '2025-08-18 13:45:25'),
(11, 2, 3, 'valide', '2025-08-18 13:49:57'),
(12, 6, 2, 'valide', '2025-08-18 13:55:00'),
(13, 7, 1, 'valide', '2025-08-18 13:55:39'),
(14, 2, 2, 'valide', '2025-08-18 14:10:43'),
(15, 3, 7, 'valide', '2025-08-18 14:20:43'),
(16, 2, 5, 'valide', '2025-08-18 15:48:06'),
(17, 1, 3, 'valide', '2025-08-18 15:49:45'),
(18, 1, 6, 'valide', '2025-08-21 12:58:52'),
(19, 3, 5, 'valide', '2025-08-28 11:15:06'),
(20, 1, 7, 'valide', '2025-08-28 11:25:13'),
(21, 6, 6, 'valide', '2025-08-28 12:08:39');

-- --------------------------------------------------------

--
-- Structure de la table `presences`
--

DROP TABLE IF EXISTS `presences`;
CREATE TABLE IF NOT EXISTS `presences` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `enfant_id` int UNSIGNED NOT NULL,
  `seance_id` int UNSIGNED NOT NULL,
  `statut` enum('reserve','present','absent','excuse') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'reserve',
  `heure_arrivee` time DEFAULT NULL,
  `heure_depart` time DEFAULT NULL,
  `commentaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_presence_unique` (`enfant_id`,`seance_id`),
  KEY `idx_presences_enfant` (`enfant_id`),
  KEY `idx_presences_seance` (`seance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `presences`
--

INSERT INTO `presences` (`id`, `enfant_id`, `seance_id`, `statut`, `heure_arrivee`, `heure_depart`, `commentaire`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'reserve', NULL, NULL, NULL, '2025-08-17 09:41:22', '2025-08-17 09:41:22'),
(2, 2, 3, 'reserve', NULL, NULL, NULL, '2025-08-17 09:41:22', '2025-08-17 09:41:22');

-- --------------------------------------------------------

--
-- Structure de la table `regles_quotas`
--

DROP TABLE IF EXISTS `regles_quotas`;
CREATE TABLE IF NOT EXISTS `regles_quotas` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `libelle` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `demi_journees_max_par_sem` tinyint UNSIGNED NOT NULL,
  `applicable_age_min` tinyint UNSIGNED DEFAULT NULL,
  `applicable_age_max` tinyint UNSIGNED DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `repas_reservations`
--

DROP TABLE IF EXISTS `repas_reservations`;
CREATE TABLE IF NOT EXISTS `repas_reservations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `enfant_id` int UNSIGNED NOT NULL,
  `date_jour` date NOT NULL,
  `type_repas` enum('repas','gouter') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'repas',
  `statut` enum('reserve','consomme','annule') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'reserve',
  `regime` enum('standard','vegetarien','sans_porc','allergenes') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standard',
  `commentaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_repas_unique` (`enfant_id`,`date_jour`,`type_repas`),
  KEY `idx_repas_date` (`date_jour`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `repas_reservations`
--

INSERT INTO `repas_reservations` (`id`, `enfant_id`, `date_jour`, `type_repas`, `statut`, `regime`, `commentaire`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-08-18', 'repas', 'reserve', 'vegetarien', NULL, '2025-08-17 09:41:22', '2025-08-17 09:41:22'),
(2, 2, '2025-08-18', 'gouter', 'reserve', 'standard', NULL, '2025-08-17 09:41:22', '2025-08-17 09:41:22');

-- --------------------------------------------------------

--
-- Structure de la table `seances`
--

DROP TABLE IF EXISTS `seances`;
CREATE TABLE IF NOT EXISTS `seances` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `activite_id` int UNSIGNED NOT NULL,
  `date_jour` date NOT NULL,
  `type_duree` enum('matin','apresmidi','journee') COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacite_total` smallint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_seances_activite` (`activite_id`),
  KEY `idx_seances_date` (`date_jour`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `seances`
--

INSERT INTO `seances` (`id`, `activite_id`, `date_jour`, `type_duree`, `capacite_total`) VALUES
(1, 1, '2025-08-18', 'matin', 20),
(2, 1, '2025-08-18', 'apresmidi', 20),
(3, 2, '2025-08-18', 'journee', 12);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_seance_capacite`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `v_seance_capacite`;
CREATE TABLE IF NOT EXISTS `v_seance_capacite` (
`seance_id` int unsigned
,`date_jour` date
,`type_duree` enum('matin','apresmidi','journee')
,`capacite_total` smallint unsigned
,`activite_id` int unsigned
,`capacite_restante` decimal(21,0)
);

-- --------------------------------------------------------

--
-- Structure de la vue `v_seance_capacite`
--
DROP TABLE IF EXISTS `v_seance_capacite`;

DROP VIEW IF EXISTS `v_seance_capacite`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_seance_capacite`  AS SELECT `s`.`id` AS `seance_id`, `s`.`date_jour` AS `date_jour`, `s`.`type_duree` AS `type_duree`, `s`.`capacite_total` AS `capacite_total`, `s`.`activite_id` AS `activite_id`, greatest((`s`.`capacite_total` - (select count(0) from `presences` `p` where ((`p`.`seance_id` = `s`.`id`) and (`p`.`statut` in ('reserve','present'))))),0) AS `capacite_restante` FROM `seances` AS `s` ;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `presences`
--
ALTER TABLE `presences`
  ADD CONSTRAINT `fk_pres_enfant` FOREIGN KEY (`enfant_id`) REFERENCES `enfants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pres_seance` FOREIGN KEY (`seance_id`) REFERENCES `seances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `repas_reservations`
--
ALTER TABLE `repas_reservations`
  ADD CONSTRAINT `fk_repas_enfant` FOREIGN KEY (`enfant_id`) REFERENCES `enfants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `seances`
--
ALTER TABLE `seances`
  ADD CONSTRAINT `fk_seances_activite` FOREIGN KEY (`activite_id`) REFERENCES `activites` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
