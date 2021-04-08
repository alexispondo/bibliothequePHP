-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 16 fév. 2021 à 22:02
-- Version du serveur :  8.0.21
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tp1`
--

-- --------------------------------------------------------

--
-- Structure de la table `appartenir`
--

DROP TABLE IF EXISTS `appartenir`;
CREATE TABLE IF NOT EXISTS `appartenir` (
  `mat_etu` varchar(11) COLLATE utf8_bin NOT NULL,
  `id_classe` varchar(11) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `appartenir`
--

INSERT INTO `appartenir` (`mat_etu`, `id_classe`) VALUES
('e1', 'c1'),
('e2', 'c2'),
('e3', 'c3'),
('e4', 'c1');

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE IF NOT EXISTS `classe` (
  `Code_cl` varchar(11) COLLATE utf8_bin NOT NULL,
  `Intitule` varchar(255) COLLATE utf8_bin NOT NULL,
  `Effectif` int NOT NULL,
  PRIMARY KEY (`Code_cl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `classe`
--

INSERT INTO `classe` (`Code_cl`, `Intitule`, `Effectif`) VALUES
('c1', 'SRIT', 2),
('c2', 'SIGL', 1),
('c3', 'TWIN', 1);

-- --------------------------------------------------------

--
-- Structure de la table `emprunt`
--

DROP TABLE IF EXISTS `emprunt`;
CREATE TABLE IF NOT EXISTS `emprunt` (
  `mat_etu` varchar(11) COLLATE utf8_bin NOT NULL,
  `code_livre` varchar(11) COLLATE utf8_bin NOT NULL,
  `jour_emprunt` varchar(20) COLLATE utf8_bin NOT NULL,
  `retour` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `emprunt`
--

INSERT INTO `emprunt` (`mat_etu`, `code_livre`, `jour_emprunt`, `retour`) VALUES
('e1', 'l2', '2021-02-01', '2021-02-15'),
('e2', 'l1', '2021-01-31', '2021-02-10'),
('e2', 'l2', '2021-02-17', '2021-02-19');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `Matricule` varchar(11) COLLATE utf8_bin NOT NULL,
  `Nom` varchar(255) COLLATE utf8_bin NOT NULL,
  `Prenom` varchar(255) COLLATE utf8_bin NOT NULL,
  `Sexe` varchar(255) COLLATE utf8_bin NOT NULL,
  `date_inscription` varchar(255) COLLATE utf8_bin NOT NULL,
  `heure` varchar(11) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`Matricule`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`Matricule`, `Nom`, `Prenom`, `Sexe`, `date_inscription`, `heure`) VALUES
('e1', 'pondo', 'kouakou', 'M', '15/02/2021', '17:01:53'),
('e2', 'Brou', 'Mardoche', 'M', '15/02/2021', '17:02:09'),
('e3', 'Anne', 'Marie', 'F', '15/02/2021', '17:02:27'),
('e4', 'Aude', 'Back', 'F', '15/02/2021', '17:02:44');

-- --------------------------------------------------------

--
-- Structure de la table `livre`
--

DROP TABLE IF EXISTS `livre`;
CREATE TABLE IF NOT EXISTS `livre` (
  `Code_Liv` varchar(11) COLLATE utf8_bin NOT NULL,
  `Titre` varchar(255) COLLATE utf8_bin NOT NULL,
  `Auteur` varchar(255) COLLATE utf8_bin NOT NULL,
  `Genre` varchar(255) COLLATE utf8_bin NOT NULL,
  `Prix` double NOT NULL,
  PRIMARY KEY (`Code_Liv`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `livre`
--

INSERT INTO `livre` (`Code_Liv`, `Titre`, `Auteur`, `Genre`, `Prix`) VALUES
('l1', 'file d\'attente', 'pandry', 'algo', 5000),
('l2', 'markov', 'Nzi modeste', 'Math', 9000);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
