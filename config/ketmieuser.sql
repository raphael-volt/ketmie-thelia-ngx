-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Hôte : ketmie_t_db
-- Généré le :  jeu. 25 jan. 2018 à 16:26
-- Version du serveur :  5.5.56
-- Version de PHP :  7.0.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ketmieuser`
--

-- --------------------------------------------------------

--
-- Structure de la table `accessoire`
--

CREATE TABLE `accessoire` (
  `id` int(11) NOT NULL,
  `produit` int(11) NOT NULL DEFAULT '0',
  `accessoire` int(11) NOT NULL DEFAULT '0',
  `classement` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `id` int(11) NOT NULL,
  `identifiant` text NOT NULL,
  `motdepasse` text NOT NULL,
  `prenom` text NOT NULL,
  `nom` text NOT NULL,
  `profil` int(11) NOT NULL,
  `lang` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`id`, `identifiant`, `motdepasse`, `prenom`, `nom`, `profil`, `lang`) VALUES
(2, 'admin', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'Admin', 'Dev', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `id` int(11) NOT NULL,
  `libelle` varchar(120) NOT NULL DEFAULT '',
  `client` int(11) NOT NULL DEFAULT '0',
  `raison` smallint(6) NOT NULL DEFAULT '0',
  `entreprise` text NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `adresse1` varchar(40) NOT NULL DEFAULT '',
  `adresse2` varchar(40) NOT NULL DEFAULT '',
  `adresse3` varchar(40) NOT NULL DEFAULT '',
  `cpostal` varchar(10) NOT NULL DEFAULT '',
  `ville` varchar(30) NOT NULL DEFAULT '',
  `tel` text NOT NULL,
  `pays` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `adresse`
--

INSERT INTO `adresse` (`id`, `libelle`, `client`, `raison`, `entreprise`, `nom`, `prenom`, `adresse1`, `adresse2`, `adresse3`, `cpostal`, `ville`, `tel`, `pays`) VALUES
(15, 'Adresse 2', 6, 1, '', 'Mousquey', 'Stéphanie', 'Le champs aux moines', '', '', '22630', 'St-André-des-eaux', '0265847884', 64),
(17, 'uygjyg', 29, 1, '', 'jhbjbjb', '1010', '101', '', '', '000', 'MMM', '1010', 64),
(18, 'Chez oim', 30, 3, '', 'Volt', 'Raphaël', '23 place Jules Ferry', 'Résidence Rohan', 'Bâtiement Nord , étage 1', '56100', 'LORIENT', '0674654493', 64),
(19, 'Chez MFP', 30, 1, '', 'Piel', 'Marie-France', 'Le champs aux moines', '', '', '22630', 'St-André-des-eaux', '0296274818', 64);

-- --------------------------------------------------------

--
-- Structure de la table `autorisation`
--

CREATE TABLE `autorisation` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `autorisation`
--

INSERT INTO `autorisation` (`id`, `nom`, `type`) VALUES
(1, 'acces_clients', 1),
(2, 'acces_commandes', 1),
(3, 'acces_catalogue', 1),
(4, 'acces_contenu', 1),
(5, 'acces_codespromos', 1),
(6, 'acces_configuration', 1),
(7, 'acces_modules', 1),
(8, 'acces_rechercher', 1),
(9, 'acces_stats', 1);

-- --------------------------------------------------------

--
-- Structure de la table `autorisationdesc`
--

CREATE TABLE `autorisationdesc` (
  `id` int(11) NOT NULL,
  `autorisation` int(11) NOT NULL,
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `postscriptum` text NOT NULL,
  `lang` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `autorisationdesc`
--

INSERT INTO `autorisationdesc` (`id`, `autorisation`, `titre`, `chapo`, `description`, `postscriptum`, `lang`) VALUES
(1, 1, 'Accès aux clients', '', '', '', 1),
(2, 2, 'Accès aux commandes', '', '', '', 1),
(3, 3, 'Accès au catalogue', '', '', '', 1),
(4, 4, 'Accès aux contenus', '', '', '', 1),
(5, 5, 'Accès aux codes promos', '', '', '', 1),
(6, 6, 'Accès à la configuration', '', '', '', 1),
(7, 7, 'Accès aux modules', '', '', '', 1),
(8, 8, 'Accès aux recherches', '', '', '', 1),
(9, 9, 'Accès aux statistiques de ventes', '', '', '', 1),
(10, 1, 'Customer access', '', '', '', 2),
(11, 2, 'Orders access', '', '', '', 2),
(12, 3, 'Catalog access', '', '', '', 2),
(13, 4, 'Content access', '', '', '', 2),
(14, 5, 'Coupon codes access', '', '', '', 2),
(15, 6, 'Configuration access', '', '', '', 2),
(16, 7, 'Plugins access', '', '', '', 2),
(17, 8, 'Search access', '', '', '', 2),
(18, 9, 'Stats access', '', '', '', 2),
(19, 1, 'Acceso a los clientes', '', '', '', 3),
(20, 2, 'Acceso a los pedidos', '', '', '', 3),
(21, 3, 'Acceso al catalogo', '', '', '', 3),
(22, 4, 'Acceso a los contenidos', '', '', '', 3),
(23, 5, 'Acceso a los codigos promocionales', '', '', '', 3),
(24, 6, 'Acceso a la configuración', '', '', '', 3),
(25, 7, 'Acceso a los modulos', '', '', '', 3),
(26, 8, 'Acceso a las búsquedas', '', '', '', 3),
(27, 9, 'Acceso a las estadísticas de ventas', '', '', '', 3);

-- --------------------------------------------------------

--
-- Structure de la table `autorisation_administrateur`
--

CREATE TABLE `autorisation_administrateur` (
  `id` int(11) NOT NULL,
  `administrateur` int(11) NOT NULL,
  `autorisation` int(11) NOT NULL,
  `lecture` smallint(6) NOT NULL,
  `ecriture` smallint(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `autorisation_modules`
--

CREATE TABLE `autorisation_modules` (
  `id` int(11) NOT NULL,
  `administrateur` int(11) NOT NULL,
  `module` int(11) NOT NULL,
  `autorise` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `autorisation_profil`
--

CREATE TABLE `autorisation_profil` (
  `id` int(11) NOT NULL,
  `profil` int(11) NOT NULL,
  `autorisation` int(11) NOT NULL,
  `lecture` int(11) NOT NULL,
  `ecriture` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `autorisation_profil`
--

INSERT INTO `autorisation_profil` (`id`, `profil`, `autorisation`, `lecture`, `ecriture`) VALUES
(1, 2, 1, 1, 1),
(2, 2, 2, 1, 1),
(3, 2, 7, 1, 1),
(4, 2, 8, 1, 1),
(5, 3, 3, 1, 1),
(6, 3, 4, 1, 1),
(7, 3, 5, 1, 1),
(8, 3, 7, 1, 1),
(9, 3, 8, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `bo_carac`
--

CREATE TABLE `bo_carac` (
  `id` int(11) NOT NULL,
  `caracdisp` int(11) NOT NULL,
  `size` decimal(4,2) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `metal` varchar(11) NOT NULL DEFAULT 'zinc'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `bo_carac`
--

INSERT INTO `bo_carac` (`id`, `caracdisp`, `size`, `price`, `metal`) VALUES
(18, 18, 4.00, 28.00, 'zinc'),
(19, 18, 5.00, 32.00, 'zinc'),
(20, 18, 6.00, 36.00, 'zinc'),
(21, 19, 4.00, 46.00, 'zinc'),
(22, 19, 5.00, 50.00, 'zinc'),
(23, 19, 6.00, 54.00, 'zinc'),
(24, 20, 4.00, 52.00, 'zinc'),
(25, 20, 5.00, 56.00, 'zinc'),
(26, 20, 6.00, 60.00, 'zinc'),
(27, 21, 4.00, 58.00, 'zinc'),
(28, 21, 5.00, 62.00, 'zinc'),
(29, 21, 6.00, 66.00, 'zinc'),
(33, 18, 7.00, 40.00, 'zinc'),
(34, 19, 7.00, 58.00, 'zinc'),
(35, 20, 7.00, 64.00, 'zinc'),
(36, 21, 7.00, 70.00, 'zinc');

-- --------------------------------------------------------

--
-- Structure de la table `caracdisp`
--

CREATE TABLE `caracdisp` (
  `id` int(11) NOT NULL,
  `caracteristique` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caracdisp`
--

INSERT INTO `caracdisp` (`id`, `caracteristique`) VALUES
(18, 6),
(23, 7),
(22, 7),
(21, 6),
(20, 6),
(19, 6);

-- --------------------------------------------------------

--
-- Structure de la table `caracdispdesc`
--

CREATE TABLE `caracdispdesc` (
  `id` int(11) NOT NULL,
  `caracdisp` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `classement` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caracdispdesc`
--

INSERT INTO `caracdispdesc` (`id`, `caracdisp`, `lang`, `titre`, `classement`) VALUES
(20, 20, 1, 'tarif C', 3),
(21, 21, 1, 'tarif D', 4),
(18, 18, 1, 'tarif A', 1),
(19, 19, 1, 'tarif B', 2),
(22, 22, 1, 'portrait', 1),
(23, 23, 1, 'paysage', 2);

-- --------------------------------------------------------

--
-- Structure de la table `caracteristique`
--

CREATE TABLE `caracteristique` (
  `id` int(11) NOT NULL,
  `affiche` int(11) NOT NULL DEFAULT '0',
  `classement` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caracteristique`
--

INSERT INTO `caracteristique` (`id`, `affiche`, `classement`) VALUES
(7, 1, 2),
(6, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `caracteristiquedesc`
--

CREATE TABLE `caracteristiquedesc` (
  `id` int(11) NOT NULL,
  `caracteristique` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caracteristiquedesc`
--

INSERT INTO `caracteristiquedesc` (`id`, `caracteristique`, `lang`, `titre`, `chapo`, `description`) VALUES
(6, 6, 1, 'boucles', '', ''),
(7, 7, 1, 'collages', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `caracval`
--

CREATE TABLE `caracval` (
  `id` int(11) NOT NULL,
  `produit` int(11) NOT NULL DEFAULT '0',
  `caracteristique` int(11) NOT NULL DEFAULT '0',
  `caracdisp` int(11) NOT NULL DEFAULT '0',
  `valeur` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `caracval`
--

INSERT INTO `caracval` (`id`, `produit`, `caracteristique`, `caracdisp`, `valeur`) VALUES
(151, 175, 6, 21, ''),
(152, 176, 6, 19, ''),
(153, 178, 6, 19, ''),
(154, 179, 6, 19, ''),
(155, 180, 6, 20, ''),
(156, 181, 6, 18, ''),
(157, 182, 6, 19, ''),
(158, 183, 6, 18, ''),
(159, 184, 6, 19, ''),
(160, 185, 6, 18, ''),
(161, 188, 6, 19, ''),
(162, 191, 6, 19, ''),
(163, 192, 6, 18, ''),
(164, 193, 6, 19, ''),
(165, 194, 6, 19, ''),
(166, 195, 6, 19, ''),
(167, 196, 6, 18, ''),
(168, 18, 6, 20, ''),
(169, 19, 6, 20, ''),
(170, 20, 6, 19, ''),
(171, 21, 6, 19, ''),
(172, 23, 6, 18, ''),
(173, 28, 6, 20, ''),
(174, 29, 6, 18, ''),
(175, 30, 6, 20, ''),
(176, 31, 6, 19, ''),
(177, 32, 6, 19, ''),
(178, 33, 6, 19, ''),
(179, 35, 6, 19, ''),
(180, 36, 6, 20, ''),
(181, 39, 6, 19, ''),
(182, 53, 6, 19, ''),
(183, 41, 6, 20, ''),
(184, 42, 6, 19, ''),
(185, 49, 6, 19, ''),
(186, 54, 6, 19, ''),
(187, 51, 6, 20, ''),
(188, 52, 6, 20, ''),
(189, 55, 6, 19, ''),
(190, 157, 6, 18, ''),
(191, 158, 6, 19, ''),
(192, 159, 6, 19, ''),
(193, 163, 6, 20, ''),
(194, 50, 6, 18, ''),
(195, 56, 6, 18, ''),
(196, 58, 6, 19, ''),
(197, 59, 6, 19, ''),
(198, 60, 6, 18, ''),
(199, 61, 6, 18, ''),
(200, 63, 6, 19, ''),
(201, 64, 6, 19, ''),
(202, 65, 6, 20, ''),
(203, 66, 6, 19, ''),
(204, 69, 6, 18, ''),
(205, 70, 6, 19, ''),
(206, 71, 6, 19, ''),
(207, 72, 6, 18, ''),
(208, 73, 6, 18, ''),
(209, 164, 6, 19, ''),
(210, 74, 6, 19, ''),
(211, 75, 6, 19, ''),
(212, 77, 6, 20, ''),
(213, 197, 6, 18, ''),
(214, 79, 6, 20, ''),
(215, 80, 6, 19, ''),
(216, 81, 6, 19, ''),
(217, 82, 6, 19, ''),
(218, 84, 6, 19, ''),
(219, 78, 6, 20, ''),
(220, 85, 6, 20, ''),
(221, 86, 6, 20, ''),
(222, 87, 6, 20, ''),
(223, 88, 6, 19, ''),
(224, 89, 6, 19, ''),
(225, 93, 6, 19, ''),
(226, 94, 6, 19, ''),
(227, 95, 6, 19, ''),
(228, 96, 6, 20, ''),
(229, 97, 6, 20, ''),
(230, 98, 6, 19, ''),
(231, 147, 6, 19, ''),
(232, 165, 6, 19, ''),
(233, 100, 6, 20, ''),
(257, 102, 6, 19, ''),
(235, 101, 6, 19, ''),
(236, 107, 6, 19, ''),
(237, 103, 6, 19, ''),
(238, 104, 6, 20, ''),
(239, 105, 6, 20, ''),
(308, 106, 6, 21, ''),
(241, 108, 6, 20, ''),
(242, 109, 6, 20, ''),
(243, 110, 6, 21, ''),
(244, 112, 6, 20, ''),
(245, 113, 6, 21, ''),
(246, 118, 6, 20, ''),
(247, 145, 6, 20, ''),
(248, 149, 6, 21, ''),
(249, 117, 6, 19, ''),
(250, 151, 6, 21, ''),
(306, 152, 6, 21, ''),
(252, 116, 6, 19, ''),
(253, 153, 6, 21, ''),
(254, 154, 6, 21, ''),
(255, 155, 6, 20, ''),
(256, 171, 6, 21, '');

-- --------------------------------------------------------

--
-- Structure de la table `carac_price`
--

CREATE TABLE `carac_price` (
  `id` int(11) NOT NULL,
  `caracteristique` int(11) NOT NULL,
  `price` decimal(6,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `carac_price`
--

INSERT INTO `carac_price` (`id`, `caracteristique`, `price`) VALUES
(1, 7, 130.00);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `ref` text NOT NULL,
  `datecrea` datetime NOT NULL,
  `raison` smallint(6) NOT NULL DEFAULT '0',
  `entreprise` text NOT NULL,
  `siret` text NOT NULL,
  `intracom` text NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `adresse1` varchar(40) NOT NULL DEFAULT '',
  `adresse2` varchar(40) NOT NULL DEFAULT '',
  `adresse3` varchar(40) NOT NULL DEFAULT '',
  `cpostal` varchar(10) NOT NULL DEFAULT '',
  `ville` varchar(30) NOT NULL DEFAULT '',
  `pays` mediumint(9) NOT NULL DEFAULT '0',
  `telfixe` text NOT NULL,
  `telport` text NOT NULL,
  `email` text NOT NULL,
  `motdepasse` text NOT NULL,
  `parrain` int(11) NOT NULL DEFAULT '0',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `pourcentage` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `ref`, `datecrea`, `raison`, `entreprise`, `siret`, `intracom`, `nom`, `prenom`, `adresse1`, `adresse2`, `adresse3`, `cpostal`, `ville`, `pays`, `telfixe`, `telport`, `email`, `motdepasse`, `parrain`, `type`, `pourcentage`, `lang`) VALUES
(32, '1512091216000032', '2015-12-09 12:16:17', 1, '', '', '', 'DE THEZY', 'Cécile', '14 rue Louis Rossel', '', '14 rue Louis Rossel', '35000', 'RENNES', 64, '0642278619', '', 'ceciledethezy@gmail.com', '*AD36C11F9BB7E12236E7ED7A81AC303AD142A5D4', 0, 0, 0, 1),
(33, '1702021827000033', '2017-02-02 18:27:38', 1, '', '', '', 'kjh', 'kjh', 'kjh', 'kjh', 'kjh', '200', 'kjh', 64, '00000', '00000', 'fionnvolt@gmail.com', '*F2E84D3EB14990103E27F92513BB854ECAA8C727', 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `client` int(11) NOT NULL DEFAULT '0',
  `adrfact` int(11) NOT NULL,
  `adrlivr` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `datefact` date NOT NULL DEFAULT '0000-00-00',
  `ref` text NOT NULL,
  `transaction` text NOT NULL,
  `livraison` text NOT NULL,
  `facture` text NOT NULL,
  `transport` int(11) NOT NULL DEFAULT '0',
  `port` float NOT NULL DEFAULT '0',
  `datelivraison` date NOT NULL DEFAULT '0000-00-00',
  `remise` float NOT NULL DEFAULT '0',
  `devise` int(11) NOT NULL,
  `taux` float NOT NULL,
  `colis` text NOT NULL,
  `paiement` int(11) NOT NULL DEFAULT '0',
  `statut` smallint(6) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `client`, `adrfact`, `adrlivr`, `date`, `datefact`, `ref`, `transaction`, `livraison`, `facture`, `transport`, `port`, `datelivraison`, `remise`, `devise`, `taux`, `colis`, `paiement`, `statut`, `lang`) VALUES
(49, 30, 97, 98, '2015-05-21 23:23:03', '0000-00-00', 'C1505212323000049', '000049', 'L1505212323000049', '0', 19, 6, '0000-00-00', 0, 1, 1, '', 21, 5, 1),
(50, 30, 99, 100, '2015-06-24 16:22:07', '0000-00-00', 'C1506241622000050', '000050', 'L1506241622000050', '0', 19, 6, '0000-00-00', 0, 1, 1, '', 20, 5, 1),
(51, 30, 101, 102, '2015-07-02 15:44:59', '0000-00-00', 'C1507021544000051', '000051', 'L1507021544000051', '0', 19, 6, '0000-00-00', 0, 1, 1, '', 21, 5, 1),
(52, 30, 103, 104, '2015-07-24 12:17:21', '0000-00-00', 'C1507241217000052', '000052', 'L1507241217000052', '0', 19, 6, '0000-00-00', 0, 1, 1, '', 20, 5, 1),
(53, 31, 105, 106, '2015-09-18 21:42:15', '0000-00-00', 'C1509182142000053', '000053', 'L1509182142000053', '0', 19, 6, '0000-00-00', 0, 1, 1, '', 20, 5, 1),
(54, 33, 107, 108, '2017-02-02 18:28:16', '0000-00-00', 'C1702021828000054', '000054', 'L1702021828000054', '0', 19, 6, '0000-00-00', 0, 1, 1, '', 21, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `contenu`
--

CREATE TABLE `contenu` (
  `id` int(11) NOT NULL,
  `datemodif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dossier` int(11) NOT NULL DEFAULT '0',
  `ligne` smallint(6) NOT NULL DEFAULT '0',
  `classement` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `contenu`
--

INSERT INTO `contenu` (`id`, `datemodif`, `dossier`, `ligne`, `classement`) VALUES
(5, '2018-01-25 17:23:49', 0, 1, 3),
(4, '2018-01-25 17:22:45', 0, 1, 2),
(6, '2018-01-25 17:24:48', 0, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `contenuassoc`
--

CREATE TABLE `contenuassoc` (
  `id` int(11) NOT NULL,
  `objet` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `contenu` int(11) NOT NULL DEFAULT '0',
  `classement` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `contenudesc`
--

CREATE TABLE `contenudesc` (
  `id` int(11) NOT NULL,
  `contenu` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `postscriptum` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `contenudesc`
--

INSERT INTO `contenudesc` (`id`, `contenu`, `lang`, `titre`, `chapo`, `description`, `postscriptum`) VALUES
(4, 4, 1, 'Conditions générales de vente', '', '<dl><dt>Conditions g&eacute;n&eacute;rales de vente</dt><dd><dl><dt>Article 1 Objet</dt><dd>\r\n<p>Les conditions g&eacute;n&eacute;rales de ventes d&eacute;crites ci-apr&egrave;s d&eacute;taillent les droits et obligations de la cr&eacute;atrice St&eacute;phanie Mousquey et de ses clients dans le cadre de la vente des marchandises suivantes :</p>\r\n<ul>\r\n<li>Bijoux</li>\r\n<li>Collages</li>\r\n</ul>\r\n<p>Toute prestation accomplie par St&eacute;phanie Mousquey implique l\'adh&eacute;sion sans r&eacute;serve de l\'acheteur aux pr&eacute;sentes conditions g&eacute;n&eacute;rales de vente.</p>\r\n</dd><dt>Article 2 Pr&eacute;sentation des produits</dt><dd>\r\n<p>Les caract&eacute;ristiques des produits propos&eacute;s &agrave; la vente sont pr&eacute;sent&eacute;es dans la rubrique \" Boutique \" de notre site. Les photographies n\'entrent pas dans le champ contractuel. La responsabilit&eacute; de St&eacute;phanie Mousquey ne peut &ecirc;tre engag&eacute;e si des erreurs s\'y sont introduites. Tous les textes et images pr&eacute;sent&eacute;s sur le site de St&eacute;phanie Mousquey sont r&eacute;serv&eacute;s, pour le monde entier, au titre des droits d\'auteur et de propri&eacute;t&eacute; intellectuelle; leur reproduction, m&ecirc;me partielle, est strictement interdite.</p>\r\n</dd><dt>Article 3 Dur&eacute;e de validit&eacute; des offres de vente</dt><dd>\r\n<p>Les produits sont propos&eacute;s &agrave; la vente jusqu\'&agrave; &eacute;puisement du stock. En cas de commande d\'un produit devenu indisponible, le client sera inform&eacute; de cette indisponibilit&eacute;, dans les meilleurs d&eacute;lais, par courrier &eacute;lectronique ou par courrier postal.</p>\r\n</dd><dt>Article 4 Prix des produits</dt><dd>\r\n<p>La rubrique \" Boutique \" de notre site indique les prix en euros toutes taxes comprises, hors frais de port. Le montant de la TVA est pr&eacute;cis&eacute; lors de la finalisation de la commande, les frais de port sont d&eacute;finis et calcul&eacute;s durant le processus de commande.</p>\r\n<p>St&eacute;phanie Mousquey se r&eacute;serve le droit de modifier ses prix &agrave; tout moment mais les produits command&eacute;s sont factur&eacute;s au prix en vigueur lors de l\'enregistrement de la commande.</p>\r\n<p>Les tarifs propos&eacute;s comprennent les rabais et ristournes que l\'entreprise St&eacute;phanie Mousquey serait amen&eacute;e &agrave; octroyer compte tenu de ses r&eacute;sultats ou de la prise en charge par l\'acheteur de certaines prestations.</p>\r\n<p>Aucun escompte ne sera accord&eacute; en cas de paiement anticip&eacute;.</p>\r\n</dd><dt>Article 5 Commande</dt><dd>\r\n<p>Le client valide sa commande lorsqu\'il active le lien \" Finaliser ma commande \" en bas de la page \" Contenu de mon panier \" apr&egrave;s avoir accept&eacute; les pr&eacute;sentes conditions de vente. Avant cette validation, il est syst&eacute;matiquement propos&eacute; au client de v&eacute;rifier chacun des &eacute;l&eacute;ments de sa commande; il peut ainsi corriger ses erreurs &eacute;ventuelles.</p>\r\n<p>St&eacute;phanie Mousquey confirme la commande par courrier &eacute;lectronique; cette information reprend notamment tous les &eacute;l&eacute;ments de la commande et le droit de r&eacute;tractation du client.</p>\r\n<p>Les donn&eacute;es enregistr&eacute;es par St&eacute;phanie Mousquey constituent la preuve de la nature, du contenu et de la date de la commande. Celle-ci est archiv&eacute;e par St&eacute;phanie Mousquey dans les conditions et les d&eacute;lais l&eacute;gaux; le client peut acc&eacute;der &agrave; cet archivage en contactant le service Relations Clients.</p>\r\n</dd><dt>Article 6 Modalit&eacute;s de paiement</dt><dd>\r\n<p>Le r&egrave;glement des commandes s\'effectue par ch&egrave;que ou par virement bancaire.</p>\r\n<p>La pr&eacute;parations des marchandises intervient apr&egrave;s la validation du paiement.</p>\r\n</dd><dt>Article 7 D&eacute;lai de r&eacute;tractation</dt><dd>\r\n<p>L\'Acheteur dispose d\'un d&eacute;lai de quatorze jours francs, &agrave; compter de la r&eacute;ception des produits, pour exercer son droit de r&eacute;tractation sans avoir &agrave; justifier de motifs ni &agrave; payer de p&eacute;nalit&eacute;s, &agrave; l\'exception, le cas &eacute;ch&eacute;ant, des frais de retour. Si le d&eacute;lai de quatorze jours vient &agrave; expirer un samedi, un dimanche ou un jour f&eacute;ri&eacute; ou ch&ocirc;m&eacute;, il est prorog&eacute; jusqu\'au premier jour ouvrable suivant.</p>\r\n<p>En cas d\'exercice du droit de r&eacute;tractation, la soci&eacute;t&eacute; rembourse l\'Acheteur de la totalit&eacute; des sommes vers&eacute;es, dans les meilleurs d&eacute;lais et au plus tard dans les trente jours suivant la date &agrave; laquelle ce droit a &eacute;t&eacute; exerc&eacute;.</p>\r\n</dd><dt>Article 8 Retard de paiement (clause applicable aux professionnels)</dt><dd>\r\n<p>En cas de d&eacute;faut de paiement total ou partiel des marchandises livr&eacute;es au jour de la r&eacute;ception, l\'acheteur doit verser &agrave; St&eacute;phanie Mousquey une p&eacute;nalit&eacute; de retard &eacute;gale &agrave; une fois et demi le taux de l\'int&eacute;r&ecirc;t l&eacute;gal.</p>\r\n<p>Le taux de l\'int&eacute;r&ecirc;t l&eacute;gal retenu est celui en vigueur au jour de la livraison des marchandises.</p>\r\n<p>Cette p&eacute;nalit&eacute; est calcul&eacute;e sur le montant hors taxes de la somme restant due, et court &agrave; compter de la date d\'&eacute;ch&eacute;ance du prix sans qu\'aucune mise en demeure pr&eacute;alable ne soit n&eacute;cessaire.</p>\r\n<p>Conform&eacute;ment aux articles 441-6 c. com. et D. 441-5 c. com., tout retard de paiement entraine de plein droit, outre les p&eacute;nalit&eacute;s de retard, une obligation pour le d&eacute;biteur de payer une indemnit&eacute; forfaitaire de 40&euro; pour frais de recouvrement.</p>\r\n<p>Une indemnit&eacute; compl&eacute;mentaire pourra &ecirc;tre r&eacute;clam&eacute;e, sur justificatifs, lorsque les frais de recouvrement expos&eacute;s sont sup&eacute;rieurs au montant de l\'indemnit&eacute; forfaitaire.</p>\r\n</dd><dt>Article 9 Livraison</dt><dd>\r\n<p>Tout produit est livr&eacute; sans garantie quant aux d&eacute;lais, exception faite des livraisons aux particuliers. La date limite de livraison varie suivant leur adresse. Elle est fix&eacute;e, pour une adresse en France m&eacute;tropolitaine, au jour du paiement + 8 jours et, pour les autres destinations, au jour du paiement + 1 mois.</p>\r\n</dd><dt>Article 10 Relations clients - Service apr&egrave;s-vente</dt><dd>\r\n<p>Pour toute information, question ou r&eacute;clamation, le client peut s\'adresser du lundi au vendredi, de 9 h &agrave; 18 h &agrave; St&eacute;phanie Mousquey.</p>\r\n<address>St&eacute;phanie Mousquey<br /> 23 place Jules Ferry<br /> R&eacute;sidence Rohan, b&acirc;timent nord<br /> 56100 LORIENT<br /> tel : <br />email :</address></dd></dl></dd></dl>', ''),
(5, 5, 1, 'Informations légales', '', '<dl><dt>Informations l&eacute;gales</dt><dd><dl><dt>1. Pr&eacute;sentation du site.</dt><dd>\r\n<p>En vertu de l\'article 6 de la loi n&deg; 2004-575 du 21 juin 2004 pour la confiance dans l\'&eacute;conomie num&eacute;rique, il est pr&eacute;cis&eacute; aux utilisateurs du site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> l\'identit&eacute; des diff&eacute;rents intervenants dans le cadre de sa r&eacute;alisation et de son suivi :</p>\r\n<p><strong>Propri&eacute;taire</strong> : </p>\r\n<address>St&eacute;phanie Mousquey <strong>&ndash; 48463184100018 &ndash;</strong><br /> 23 place Jules Ferry<br /> R&eacute;sidence Rohan, b&acirc;timent nord<br /> 56100 LORIENT<br />tel :</address>\r\n<p><strong>Cr&eacute;ateur</strong> : <a href=\"/admin_dev/www.dev.ketmie.com\">Rapha&euml;l Volt</a><br /> <strong>Responsable publication</strong> : St&eacute;phanie Mousquey &ndash; email : <br /> Le responsable publication est une personne physique ou une personne morale.<br /> <strong>Webmaster</strong> : Rapha&euml;l Volt &ndash; email : <br /> <strong>H&eacute;bergeur</strong> : OVH &ndash; 2 rue Kellermann - 59100 Roubaix - France<br /> Cr&eacute;dits : les mentions l&eacute;gales ont &eacute;t&eacute; g&eacute;n&eacute;r&eacute;es et offertes par Subdelirium <a href=\"http://www.subdelirium.com/site-mobile/\" target=\"_blank\">cr&eacute;ation de site responsive</a></p>\r\n</dd><dt>2. Conditions g&eacute;n&eacute;rales d&rsquo;utilisation du site et des services propos&eacute;s.</dt><dd>\r\n<p>L&rsquo;utilisation du site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> implique l&rsquo;acceptation pleine et enti&egrave;re des conditions g&eacute;n&eacute;rales d&rsquo;utilisation ci-apr&egrave;s d&eacute;crites. Ces conditions d&rsquo;utilisation sont susceptibles d&rsquo;&ecirc;tre modifi&eacute;es ou compl&eacute;t&eacute;es &agrave; tout moment, les utilisateurs du site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> sont donc invit&eacute;s &agrave; les consulter de mani&egrave;re r&eacute;guli&egrave;re.</p>\r\n<p>Ce site est normalement accessible &agrave; tout moment aux utilisateurs. Une interruption pour raison de maintenance technique peut &ecirc;tre toutefois d&eacute;cid&eacute;e par St&eacute;phanie Mousquey, qui s&rsquo;efforcera alors de communiquer pr&eacute;alablement aux utilisateurs les dates et heures de l&rsquo;intervention.</p>\r\n<p>Le site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> est mis &agrave; jour r&eacute;guli&egrave;rement par St&eacute;phanie Mousquey. De la m&ecirc;me fa&ccedil;on, les mentions l&eacute;gales peuvent &ecirc;tre modifi&eacute;es &agrave; tout moment : elles s&rsquo;imposent n&eacute;anmoins &agrave; l&rsquo;utilisateur qui est invit&eacute; &agrave; s&rsquo;y r&eacute;f&eacute;rer le plus souvent possible afin d&rsquo;en prendre connaissance.</p>\r\n</dd><dt>3. Description des services fournis.</dt><dd>\r\n<p>Le site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> a pour objet de fournir une information concernant l&rsquo;ensemble des activit&eacute;s de la soci&eacute;t&eacute;.</p>\r\n<p>St&eacute;phanie Mousquey s&rsquo;efforce de fournir sur le site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> des informations aussi pr&eacute;cises que possible. Toutefois, il ne pourra &ecirc;tre tenue responsable des omissions, des inexactitudes et des carences dans la mise &agrave; jour, qu&rsquo;elles soient de son fait ou du fait des tiers partenaires qui lui fournissent ces informations.</p>\r\n<p>Tous les informations indiqu&eacute;es sur le site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> sont donn&eacute;es &agrave; titre indicatif, et sont susceptibles d&rsquo;&eacute;voluer. Par ailleurs, les renseignements figurant sur le site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> ne sont pas exhaustifs. Ils sont donn&eacute;s sous r&eacute;serve de modifications ayant &eacute;t&eacute; apport&eacute;es depuis leur mise en ligne.</p>\r\n</dd><dt>4. Limitations contractuelles sur les donn&eacute;es techniques.</dt><dd>\r\n<p>Le site utilise la technologie JavaScript.</p>\r\n<p>Le site Internet ne pourra &ecirc;tre tenu responsable de dommages mat&eacute;riels li&eacute;s &agrave; l&rsquo;utilisation du site. De plus, l&rsquo;utilisateur du site s&rsquo;engage &agrave; acc&eacute;der au site en utilisant un mat&eacute;riel r&eacute;cent, ne contenant pas de virus et avec un navigateur de derni&egrave;re g&eacute;n&eacute;ration mis-&agrave;-jour</p>\r\n</dd><dt>5. Propri&eacute;t&eacute; intellectuelle et contrefa&ccedil;ons.</dt><dd>\r\n<p>St&eacute;phanie Mousquey est propri&eacute;taire des droits de propri&eacute;t&eacute; intellectuelle ou d&eacute;tient les droits d&rsquo;usage sur tous les &eacute;l&eacute;ments accessibles sur le site, notamment les textes, images, graphismes, logo, ic&ocirc;nes, sons, logiciels.</p>\r\n<p>Toute reproduction, repr&eacute;sentation, modification, publication, adaptation de tout ou partie des &eacute;l&eacute;ments du site, quel que soit le moyen ou le proc&eacute;d&eacute; utilis&eacute;, est interdite, sauf autorisation &eacute;crite pr&eacute;alable de : St&eacute;phanie Mousquey.</p>\r\n<p>Toute exploitation non autoris&eacute;e du site ou de l&rsquo;un quelconque des &eacute;l&eacute;ments qu&rsquo;il contient sera consid&eacute;r&eacute;e comme constitutive d&rsquo;une contrefa&ccedil;on et poursuivie conform&eacute;ment aux dispositions des articles L.335-2 et suivants du Code de Propri&eacute;t&eacute; Intellectuelle.</p>\r\n</dd><dt>6. Limitations de responsabilit&eacute;.</dt><dd>\r\n<p>St&eacute;phanie Mousquey ne pourra &ecirc;tre tenue responsable des dommages directs et indirects caus&eacute;s au mat&eacute;riel de l&rsquo;utilisateur, lors de l&rsquo;acc&egrave;s au site www.ketmie.com, et r&eacute;sultant soit de l&rsquo;utilisation d&rsquo;un mat&eacute;riel ne r&eacute;pondant pas aux sp&eacute;cifications indiqu&eacute;es au point 4, soit de l&rsquo;apparition d&rsquo;un bug ou d&rsquo;une incompatibilit&eacute;.</p>\r\n<p>St&eacute;phanie Mousquey ne pourra &eacute;galement &ecirc;tre tenue responsable des dommages indirects (tels par exemple qu&rsquo;une perte de march&eacute; ou perte d&rsquo;une chance) cons&eacute;cutifs &agrave; l&rsquo;utilisation du site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a>.</p>\r\n<p>Des espaces interactifs (possibilit&eacute; de poser des questions dans l&rsquo;espace contact) sont &agrave; la disposition des utilisateurs. St&eacute;phanie Mousquey se r&eacute;serve le droit de supprimer, sans mise en demeure pr&eacute;alable, tout contenu d&eacute;pos&eacute; dans cet espace qui contreviendrait &agrave; la l&eacute;gislation applicable en France, en particulier aux dispositions relatives &agrave; la protection des donn&eacute;es. Le cas &eacute;ch&eacute;ant, St&eacute;phanie Mousquey se r&eacute;serve &eacute;galement la possibilit&eacute; de mettre en cause la responsabilit&eacute; civile et/ou p&eacute;nale de l&rsquo;utilisateur, notamment en cas de message &agrave; caract&egrave;re raciste, injurieux, diffamant, ou pornographique, quel que soit le support utilis&eacute; (texte, photographie&hellip;).</p>\r\n</dd><dt>7. Gestion des donn&eacute;es personnelles.</dt><dd>\r\n<p>En France, les donn&eacute;es personnelles sont notamment prot&eacute;g&eacute;es par la loi n&deg; 78-87 du 6 janvier 1978, la loi n&deg; 2004-801 du 6 ao&ucirc;t 2004, l\'article L. 226-13 du Code p&eacute;nal et la Directive Europ&eacute;enne du 24 octobre 1995.</p>\r\n<p>A l\'occasion de l\'utilisation du site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a>, peuvent &ecirc;tres recueillies : l\'URL des liens par l\'interm&eacute;diaire desquels l\'utilisateur a acc&eacute;d&eacute; au site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a>, le fournisseur d\'acc&egrave;s de l\'utilisateur, l\'adresse de protocole Internet (IP) de l\'utilisateur.</p>\r\n<p>En tout &eacute;tat de cause St&eacute;phanie Mousquey ne collecte des informations personnelles relatives &agrave; l\'utilisateur que pour le besoin de certains services propos&eacute;s par le site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a>. L\'utilisateur fournit ces informations en toute connaissance de cause, notamment lorsqu\'il proc&egrave;de par lui-m&ecirc;me &agrave; leur saisie. Il est alors pr&eacute;cis&eacute; &agrave; l\'utilisateur du site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> l&rsquo;obligation ou non de fournir ces informations.</p>\r\n<p>Conform&eacute;ment aux dispositions des articles 38 et suivants de la loi 78-17 du 6 janvier 1978 relative &agrave; l&rsquo;informatique, aux fichiers et aux libert&eacute;s, tout utilisateur dispose d&rsquo;un droit d&rsquo;acc&egrave;s, de rectification et d&rsquo;opposition aux donn&eacute;es personnelles le concernant, en effectuant sa demande &eacute;crite et sign&eacute;e, accompagn&eacute;e d&rsquo;une copie du titre d&rsquo;identit&eacute; avec signature du titulaire de la pi&egrave;ce, en pr&eacute;cisant l&rsquo;adresse &agrave; laquelle la r&eacute;ponse doit &ecirc;tre envoy&eacute;e.</p>\r\n<p>Aucune information personnelle de l\'utilisateur du site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> n\'est publi&eacute;e &agrave; l\'insu de l\'utilisateur, &eacute;chang&eacute;e, transf&eacute;r&eacute;e, c&eacute;d&eacute;e ou vendue sur un support quelconque &agrave; des tiers. Seule l\'hypoth&egrave;se du rachat de St&eacute;phanie Mousquey et de ses droits permettrait la transmission des dites informations &agrave; l\'&eacute;ventuel acqu&eacute;reur qui serait &agrave; son tour tenu de la m&ecirc;me obligation de conservation et de modification des donn&eacute;es vis &agrave; vis de l\'utilisateur du site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a>.</p>\r\n<p>Le site susnomm&eacute; est d&eacute;clar&eacute; &agrave; la CNIL sous le num&eacute;ro n&deg; CNIL.</p>\r\n<p>Les bases de donn&eacute;es sont prot&eacute;g&eacute;es par les dispositions de la loi du 1er juillet 1998 transposant la directive 96/9 du 11 mars 1996 relative &agrave; la protection juridique des bases de donn&eacute;es.</p>\r\n</dd><dt>8. Liens hypertextes et cookies.</dt><dd>\r\n<p>Le site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> contient un certain nombre de liens hypertextes vers d&rsquo;autres sites, mis en place avec l&rsquo;autorisation de St&eacute;phanie Mousquey. Cependant, St&eacute;phanie Mousquey n&rsquo;a pas la possibilit&eacute; de v&eacute;rifier le contenu des sites ainsi visit&eacute;s, et n&rsquo;assumera en cons&eacute;quence aucune responsabilit&eacute; de ce fait.</p>\r\n<p>La navigation sur le site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> est susceptible de provoquer l&rsquo;installation de cookie(s) sur l&rsquo;ordinateur de l&rsquo;utilisateur. Un cookie est un fichier de petite taille, qui ne permet pas l&rsquo;identification de l&rsquo;utilisateur, mais qui enregistre des informations relatives &agrave; la navigation d&rsquo;un ordinateur sur un site. Les donn&eacute;es ainsi obtenues visent &agrave; faciliter la navigation ult&eacute;rieure sur le site, et ont &eacute;galement vocation &agrave; permettre diverses mesures de fr&eacute;quentation.</p>\r\n<p>Le refus d&rsquo;installation d&rsquo;un cookie peut entra&icirc;ner l&rsquo;impossibilit&eacute; d&rsquo;acc&eacute;der &agrave; certains services. L&rsquo;utilisateur peut toutefois configurer son ordinateur de la mani&egrave;re suivante, pour refuser l&rsquo;installation des cookies :</p>\r\n<p>Sous Internet Explorer : onglet outil (pictogramme en forme de rouage en haut a droite) / options internet. Cliquez sur Confidentialit&eacute; et choisissez Bloquer tous les cookies. Validez sur Ok.</p>\r\n<p>Sous Firefox : en haut de la fen&ecirc;tre du navigateur, cliquez sur le bouton Firefox, puis aller dans l\'onglet Options. Cliquer sur l\'onglet Vie priv&eacute;e. Param&eacute;trez les R&egrave;gles de conservation sur : utiliser les param&egrave;tres personnalis&eacute;s pour l\'historique. Enfin d&eacute;cochez-la pour d&eacute;sactiver les cookies.</p>\r\n<p>Sous Safari : Cliquez en haut &agrave; droite du navigateur sur le pictogramme de menu (symbolis&eacute; par un rouage). S&eacute;lectionnez Param&egrave;tres. Cliquez sur Afficher les param&egrave;tres avanc&eacute;s. Dans la section \"Confidentialit&eacute;\", cliquez sur Param&egrave;tres de contenu. Dans la section \"Cookies\", vous pouvez bloquer les cookies.</p>\r\n<p>Sous Chrome : Cliquez en haut &agrave; droite du navigateur sur le pictogramme de menu (symbolis&eacute; par trois lignes horizontales). S&eacute;lectionnez Param&egrave;tres. Cliquez sur Afficher les param&egrave;tres avanc&eacute;s. Dans la section \"Confidentialit&eacute;\", cliquez sur pr&eacute;f&eacute;rences. Dans l\'onglet \"Confidentialit&eacute;\", vous pouvez bloquer les cookies.</p>\r\n</dd><dt>9. Droit applicable et attribution de juridiction.</dt><dd>\r\n<p>Tout litige en relation avec l&rsquo;utilisation du site <a title=\"St&eacute;phanie Mousquey - www.ketmie.com\" href=\"http://www.ketmie.com/\">www.ketmie.com</a> est soumis au droit fran&ccedil;ais. Il est fait attribution exclusive de juridiction aux tribunaux comp&eacute;tents de Paris.</p>\r\n</dd><dt>10. Les principales lois concern&eacute;es.</dt><dd>\r\n<p>Loi n&deg; 78-87 du 6 janvier 1978, notamment modifi&eacute;e par la loi n&deg; 2004-801 du 6 ao&ucirc;t 2004 relative &agrave; l\'informatique, aux fichiers et aux libert&eacute;s.</p>\r\n<p>Loi n&deg; 2004-575 du 21 juin 2004 pour la confiance dans l\'&eacute;conomie num&eacute;rique.</p>\r\n</dd><dt>11. Lexique.</dt><dd>\r\n<p>Utilisateur : Internaute se connectant, utilisant le site susnomm&eacute;.</p>\r\n<p>Informations personnelles : &laquo; les informations qui permettent, sous quelque forme que ce soit, directement ou non, l\'identification des personnes physiques auxquelles elles s\'appliquent &raquo; (article 4 de la loi n&deg; 78-17 du 6 janvier 1978).</p>\r\n</dd></dl></dd></dl>', ''),
(6, 6, 1, 'Stéphanie Mousquey', '', '<p>Pr&eacute;sentation</p>', '');

-- --------------------------------------------------------

--
-- Structure de la table `dbtest`
--

CREATE TABLE `dbtest` (
  `id` int(11) NOT NULL,
  `label` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `declidisp`
--

CREATE TABLE `declidisp` (
  `id` int(11) NOT NULL,
  `declinaison` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `declidispdesc`
--

CREATE TABLE `declidispdesc` (
  `id` int(11) NOT NULL,
  `declidisp` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `classement` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `declinaison`
--

CREATE TABLE `declinaison` (
  `id` int(11) NOT NULL,
  `classement` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `declinaisondesc`
--

CREATE TABLE `declinaisondesc` (
  `id` int(11) NOT NULL,
  `declinaison` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `devise`
--

CREATE TABLE `devise` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `code` text NOT NULL,
  `symbole` text NOT NULL,
  `taux` float NOT NULL DEFAULT '0',
  `defaut` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `devise`
--

INSERT INTO `devise` (`id`, `nom`, `code`, `symbole`, `taux`, `defaut`) VALUES
(1, 'euro', 'EUR', '€', 1, 1),
(2, 'dollar', 'USD', '$', 1.26, 0),
(3, 'livre', 'GBP', '£', 0.89, 0);

-- --------------------------------------------------------

--
-- Structure de la table `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `produit` int(11) NOT NULL DEFAULT '0',
  `rubrique` int(11) NOT NULL DEFAULT '0',
  `contenu` int(11) NOT NULL DEFAULT '0',
  `dossier` int(11) NOT NULL DEFAULT '0',
  `fichier` text NOT NULL,
  `classement` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `documentdesc`
--

CREATE TABLE `documentdesc` (
  `id` int(11) NOT NULL,
  `document` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `dossier`
--

CREATE TABLE `dossier` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `lien` text NOT NULL,
  `ligne` smallint(6) NOT NULL DEFAULT '0',
  `classement` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `dossierdesc`
--

CREATE TABLE `dossierdesc` (
  `id` int(11) NOT NULL,
  `dossier` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `postscriptum` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `exdecprod`
--

CREATE TABLE `exdecprod` (
  `id` int(11) NOT NULL,
  `produit` int(11) NOT NULL DEFAULT '0',
  `declidisp` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `produit` int(11) NOT NULL DEFAULT '0',
  `rubrique` int(11) NOT NULL DEFAULT '0',
  `contenu` int(11) NOT NULL DEFAULT '0',
  `dossier` int(11) NOT NULL DEFAULT '0',
  `fichier` text NOT NULL,
  `classement` int(6) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `produit`, `rubrique`, `contenu`, `dossier`, `fichier`, `classement`) VALUES
(31, 27, 0, 0, 0, 'img_6352_31.JPG', 1),
(36, 32, 0, 0, 0, 'img_7124_36v1.JPG', 1),
(35, 31, 0, 0, 0, 'img_7111_35v1.JPG', 1),
(34, 30, 0, 0, 0, 'img_7067_34v2.JPG', 1),
(33, 29, 0, 0, 0, '552_33.JPG', 1),
(32, 28, 0, 0, 0, '511_32v1.JPG', 1),
(30, 26, 0, 0, 0, 'img_0357_30.JPG', 1),
(29, 25, 0, 0, 0, 'img_0352_29v1.JPG', 1),
(27, 23, 0, 0, 0, '483_27.JPG', 1),
(28, 24, 0, 0, 0, 'img_8731_28v1.JPG', 1),
(39, 36, 0, 0, 0, '453_39.JPG', 1),
(23, 19, 0, 0, 0, '460_23v1.JPG', 1),
(24, 20, 0, 0, 0, '467_24v1.JPG', 1),
(25, 21, 0, 0, 0, '470_25v1.JPG', 1),
(22, 18, 0, 0, 0, '445_22v1.JPG', 1),
(37, 33, 0, 0, 0, 'img_8723_37v1.JPG', 1),
(38, 35, 0, 0, 0, 'img_8729_38v1.JPG', 1),
(40, 39, 0, 0, 0, '525_40v1.JPG', 1),
(41, 41, 0, 0, 0, '526_41v1.JPG', 1),
(42, 42, 0, 0, 0, '535_42v1.JPG', 1),
(133, 158, 0, 0, 0, 'img_4617_133v1.jpg', 1),
(460, 173, 0, 0, 0, 'img_3192_460.jpg', 1),
(45, 49, 0, 0, 0, 'img_7088_45v1.JPG', 1),
(46, 50, 0, 0, 0, 'img_7098_46v1.JPG', 1),
(47, 51, 0, 0, 0, 'img_7120_47v1.JPG', 1),
(48, 52, 0, 0, 0, 'img_8680_48v1.JPG', 1),
(49, 53, 0, 0, 0, 'img_8681_49v1.jpg', 1),
(50, 54, 0, 0, 0, 'img_8717_50v1.JPG', 1),
(51, 55, 0, 0, 0, 'img_8720_51.JPG', 1),
(52, 56, 0, 0, 0, '524_52v1.JPG', 1),
(53, 58, 0, 0, 0, '530_53v1.JPG', 1),
(54, 59, 0, 0, 0, '538_54v1.JPG', 1),
(55, 60, 0, 0, 0, '540_55v1.JPG', 1),
(56, 61, 0, 0, 0, '546_56v1.JPG', 1),
(57, 63, 0, 0, 0, '549_57v1.JPG', 1),
(58, 64, 0, 0, 0, '558_58v1.JPG', 1),
(59, 65, 0, 0, 0, '564_59v1.JPG', 1),
(60, 66, 0, 0, 0, '570_60v1.JPG', 1),
(61, 69, 0, 0, 0, 'img_7136_61v1.JPG', 1),
(62, 70, 0, 0, 0, 'img_7161_62v1.JPG', 1),
(63, 71, 0, 0, 0, 'img_7164_63v1.JPG', 1),
(64, 72, 0, 0, 0, 'img_7168_64v1.JPG', 1),
(65, 73, 0, 0, 0, 'img_7174_65v1.JPG', 1),
(487, 74, 0, 0, 0, 'img_2040_487.jpg', 1),
(67, 75, 0, 0, 0, '502_67v1.JPG', 1),
(68, 77, 0, 0, 0, '506_68v1.JPG', 1),
(69, 78, 0, 0, 0, '509_69v1.JPG', 1),
(70, 79, 0, 0, 0, '514_70v1.JPG', 1),
(71, 80, 0, 0, 0, '519_71v1.JPG', 1),
(72, 81, 0, 0, 0, '573_72v1.JPG', 1),
(73, 82, 0, 0, 0, 'img_7114_73v1.JPG', 1),
(74, 84, 0, 0, 0, 'img_8715_74v1.JPG', 1),
(486, 85, 0, 0, 0, 'img_2032_486.jpg', 1),
(494, 86, 0, 0, 0, 'img_2024_494.jpg', 1),
(77, 87, 0, 0, 0, '458_77v1.JPG', 1),
(78, 88, 0, 0, 0, '464_78v1.JPG', 1),
(488, 89, 0, 0, 0, 'img_2042_488.jpg', 1),
(80, 93, 0, 0, 0, '477_80v1.JPG', 1),
(489, 94, 0, 0, 0, 'img_2022_489.jpg', 1),
(82, 95, 0, 0, 0, '482_82v2.JPG', 1),
(485, 96, 0, 0, 0, 'img_2028_485.jpg', 1),
(84, 97, 0, 0, 0, '489_84v1.JPG', 1),
(85, 98, 0, 0, 0, '495_85v1.JPG', 1),
(482, 147, 0, 0, 0, 'img_2036_482.jpg', 1),
(87, 100, 0, 0, 0, 'img_8646_87v1.JPG', 1),
(88, 101, 0, 0, 0, 'img_8651_88v1.JPG', 1),
(191, 103, 0, 0, 0, '009_191v2.jpg', 1),
(192, 176, 0, 0, 0, '017_192v1.jpg', 1),
(91, 104, 0, 0, 0, 'img_8659_91v1.JPG', 1),
(92, 105, 0, 0, 0, 'img_8661_92v1.JPG', 1),
(481, 106, 0, 0, 0, 'img_2020_481.jpg', 1),
(94, 107, 0, 0, 0, 'img_8666_94v1.JPG', 1),
(95, 108, 0, 0, 0, 'img_8667_95v1.JPG', 1),
(484, 102, 0, 0, 0, 'img_2044_484.jpg', 1),
(97, 110, 0, 0, 0, 'img_8675_97v1.JPG', 1),
(99, 112, 0, 0, 0, 'img_8677_99v1.JPG', 1),
(100, 113, 0, 0, 0, 'img_0391_100v1.JPG', 1),
(101, 120, 0, 0, 0, 'img_8694_101v1.JPG', 1),
(102, 125, 0, 0, 0, 'img_0377_102.JPG', 1),
(103, 126, 0, 0, 0, 'img_0386_103v1.JPG', 1),
(152, 152, 0, 0, 0, '811_152v1.jpg', 1),
(105, 128, 0, 0, 0, 'img_0387_105.JPG', 1),
(106, 129, 0, 0, 0, '609_106.JPG', 1),
(107, 131, 0, 0, 0, 'img_0363_107.JPG', 1),
(108, 133, 0, 0, 0, '608_108.JPG', 1),
(109, 134, 0, 0, 0, '613_109.JPG', 1),
(110, 135, 0, 0, 0, '615_110.JPG', 1),
(111, 136, 0, 0, 0, '616_111.JPG', 1),
(112, 137, 0, 0, 0, '617_112.JPG', 1),
(113, 138, 0, 0, 0, 'img_8701_113.JPG', 1),
(114, 139, 0, 0, 0, 'img_8699_114.JPG', 1),
(115, 140, 0, 0, 0, '621_115.JPG', 1),
(117, 142, 0, 0, 0, '620_117.JPG', 1),
(118, 143, 0, 0, 0, '619_118.JPG', 1),
(147, 151, 0, 0, 0, '808_147v1.jpg', 1),
(123, 118, 0, 0, 0, 'img_0408_123v1.jpg', 1),
(146, 117, 0, 0, 0, '815_146v1.jpg', 1),
(125, 149, 0, 0, 0, 'img_0396_125v1.JPG', 1),
(148, 153, 0, 0, 0, '805_148v1.jpg', 1),
(459, 173, 0, 0, 0, 'img_3193_459.jpg', 4),
(149, 155, 0, 0, 0, '807_149v1.jpg', 1),
(129, 154, 0, 0, 0, 'img_0420_129v1.JPG', 1),
(483, 116, 0, 0, 0, 'img_2046_483.jpg', 1),
(132, 157, 0, 0, 0, '559_132v1.JPG', 1),
(134, 159, 0, 0, 0, '555_134v1.JPG', 1),
(137, 163, 0, 0, 0, '485_137v1.JPG', 1),
(138, 164, 0, 0, 0, '572_138v1.JPG', 1),
(139, 165, 0, 0, 0, '474_139v1.JPG', 1),
(458, 173, 0, 0, 0, 'img_3197_458.jpg', 3),
(143, 169, 0, 0, 0, 'img_6355_143.JPG', 1),
(145, 145, 0, 0, 0, '802_145v1.jpg', 1),
(493, 171, 0, 0, 0, 'img_2038_493.jpg', 1),
(193, 197, 0, 0, 0, '014_193v1.jpg', 1),
(194, 191, 0, 0, 0, '013_194v1.jpg', 1),
(492, 180, 0, 0, 0, 'img_2026_492.jpg', 1),
(159, 178, 0, 0, 0, '754_159v1.jpg', 1),
(491, 179, 0, 0, 0, 'img_2030_491.jpg', 1),
(196, 181, 0, 0, 0, '011_196v1.jpg', 1),
(197, 195, 0, 0, 0, '015_197v1.jpg', 1),
(186, 188, 0, 0, 0, '003_186v1.jpg', 1),
(490, 183, 0, 0, 0, 'img_2034_490.jpg', 1),
(165, 184, 0, 0, 0, '766_165v1.jpg', 1),
(166, 185, 0, 0, 0, '767_166v1.jpg', 1),
(187, 196, 0, 0, 0, '008_187v1.jpg', 1),
(177, 193, 0, 0, 0, '789_177v1.jpg', 1),
(189, 109, 0, 0, 0, '002_189v2.jpg', 1),
(198, 194, 0, 0, 0, '004_198v1.jpg', 1),
(181, 175, 0, 0, 0, '012_181v1.jpg', 1),
(188, 192, 0, 0, 0, '005_188v1.jpg', 1),
(185, 182, 0, 0, 0, '006_185v1.jpg', 1),
(297, 27, 0, 0, 0, 'img_6382_297.JPG', 2),
(455, 166, 0, 0, 0, 'img_2915_455.JPG', 2),
(294, 26, 0, 0, 0, '1-12_294v1.jpg', 2),
(219, 0, 0, 0, 0, 'gimg_219.jpg', 1),
(283, 0, 0, 0, 0, 'gimg_283v1.jpg', 0),
(223, 0, 0, 0, 0, 'gimg_223.jpg', 1),
(222, 0, 0, 0, 0, 'gimg_222.jpg', 1),
(221, 0, 0, 0, 0, 'gimg_221.jpg', 1),
(286, 0, 0, 0, 0, 'gimg_286.jpg', 0),
(220, 0, 0, 0, 0, 'gimg_220.jpg', 1),
(293, 0, 0, 0, 0, 'gimg_293v1.jpg', 0),
(213, 0, 0, 0, 0, 'gimg_213.jpg', 1),
(204, 0, 0, 0, 0, 'gimg_204.jpg', 1),
(206, 0, 0, 0, 0, 'gimg_206.jpg', 1),
(224, 0, 0, 0, 0, 'gimg_224.jpg', 1),
(215, 0, 0, 0, 0, 'gimg_215.jpg', 1),
(207, 0, 0, 0, 0, 'gimg_207.jpg', 1),
(226, 0, 0, 0, 0, 'gimg_226.jpg', 1),
(210, 0, 0, 0, 0, 'gimg_210.jpg', 1),
(211, 0, 0, 0, 0, 'gimg_211.jpg', 1),
(212, 0, 0, 0, 0, 'gimg_212.jpg', 1),
(240, 0, 0, 0, 0, 'gimg_240.jpg', 1),
(241, 0, 0, 0, 0, 'gimg_241.jpg', 2),
(242, 0, 0, 0, 0, 'gimg_242.jpg', 3),
(243, 0, 0, 0, 0, 'gimg_243.jpg', 4),
(244, 0, 0, 0, 0, 'gimg_244.jpg', 5),
(245, 0, 0, 0, 0, 'gimg_245.jpg', 6),
(246, 0, 0, 0, 0, 'gimg_246.jpg', 7),
(247, 0, 0, 0, 0, 'gimg_247.jpg', 8),
(248, 0, 0, 0, 0, 'gimg_248.jpg', 9),
(249, 0, 0, 0, 0, 'gimg_249.jpg', 10),
(250, 0, 0, 0, 0, 'gimg_250.jpg', 11),
(251, 0, 0, 0, 0, 'gimg_251.jpg', 12),
(252, 0, 0, 0, 0, 'gimg_252.jpg', 13),
(253, 0, 0, 0, 0, 'gimg_253.jpg', 14),
(254, 0, 0, 0, 0, 'gimg_254.jpg', 15),
(255, 0, 0, 0, 0, 'gimg_255.jpg', 16),
(256, 0, 0, 0, 0, 'gimg_256.jpg', 17),
(257, 0, 0, 0, 0, 'gimg_257.jpg', 18),
(258, 0, 0, 0, 0, 'gimg_258.jpg', 19),
(259, 0, 0, 0, 0, 'gimg_259.jpg', 20),
(260, 0, 0, 0, 0, 'gimg_260.jpg', 21),
(261, 0, 0, 0, 0, 'gimg_261.jpg', 22),
(262, 0, 0, 0, 0, 'gimg_262.jpg', 23),
(263, 0, 0, 0, 0, 'gimg_263.jpg', 24),
(264, 0, 0, 0, 0, 'gimg_264.jpg', 25),
(265, 0, 0, 0, 0, 'gimg_265.jpg', 26),
(266, 0, 0, 0, 0, 'gimg_266.jpg', 27),
(267, 0, 0, 0, 0, 'gimg_267.jpg', 28),
(268, 0, 0, 0, 0, 'gimg_268.jpg', 29),
(269, 0, 0, 0, 0, 'gimg_269.jpg', 30),
(270, 0, 0, 0, 0, 'gimg_270.jpg', 31),
(271, 0, 0, 0, 0, 'gimg_271.jpg', 32),
(272, 0, 0, 0, 0, 'gimg_272.jpg', 33),
(273, 0, 0, 0, 0, 'gimg_273.jpg', 34),
(274, 0, 0, 0, 0, 'gimg_274.jpg', 35),
(275, 0, 0, 0, 0, 'gimg_275.jpg', 36),
(298, 120, 0, 0, 0, 'img_8692_298v1.JPG', 2),
(277, 0, 0, 0, 0, 'gimg_277.jpg', 38),
(291, 0, 0, 0, 0, 'gimg_291.jpg', 0),
(279, 0, 0, 0, 0, 'gimg_279v1.jpg', 0),
(282, 0, 0, 0, 0, 'gimg_282v1.jpg', 0),
(281, 0, 0, 0, 0, 'gimg_281v2.jpg', 0),
(284, 0, 0, 0, 0, 'gimg_284v2.jpg', 0),
(287, 0, 0, 0, 0, 'gimg_287v1.jpg', 0),
(288, 0, 0, 0, 0, 'gimg_288v1.jpg', 0),
(225, 0, 0, 0, 0, 'gimg_225.jpg', 1),
(227, 0, 0, 0, 0, 'gimg_227.jpg', 1),
(218, 0, 0, 0, 0, 'gimg_218.jpg', 1),
(228, 0, 0, 0, 0, 'gimg_228.jpg', 1),
(229, 0, 0, 0, 0, 'gimg_229.jpg', 1),
(230, 0, 0, 0, 0, 'gimg_230.jpg', 1),
(231, 0, 0, 0, 0, 'gimg_231.jpg', 1),
(233, 0, 0, 0, 0, 'gimg_233.jpg', 1),
(234, 0, 0, 0, 0, 'gimg_234.jpg', 1),
(235, 0, 0, 0, 0, 'gimg_235.jpg', 1),
(232, 0, 0, 0, 0, 'gimg_232.jpg', 1),
(208, 0, 0, 0, 0, 'gimg_208.jpg', 1),
(236, 0, 0, 0, 0, 'gimg_236.jpg', 1),
(237, 0, 0, 0, 0, 'gimg_237.jpg', 1),
(238, 0, 0, 0, 0, 'gimg_238.jpg', 1),
(239, 0, 0, 0, 0, 'gimg_239.jpg', 1),
(290, 0, 0, 0, 0, 'gimg_290.jpg', 0),
(292, 0, 0, 0, 0, 'gimg_292v1.jpg', 0),
(323, 26, 0, 0, 0, '1-12_323v1.jpg', 3),
(461, 240, 0, 0, 0, 'img_3183_461.jpg', 1),
(303, 173, 0, 0, 0, 'dsc_6776_303.jpg', 2),
(305, 24, 0, 0, 0, 'img_8734_305v1.JPG', 2),
(306, 25, 0, 0, 0, 'img_0375_306v1.JPG', 2),
(307, 125, 0, 0, 0, 'img_0378_307v1.JPG', 2),
(384, 133, 0, 0, 0, '103_384.JPG', 2),
(314, 169, 0, 0, 0, 'img_6356_314.JPG', 2),
(316, 129, 0, 0, 0, 'img_4601_316.jpg', 2),
(317, 131, 0, 0, 0, 'img_0364_317v1.JPG', 2),
(318, 169, 0, 0, 0, 'img_6356_318v1.JPG', 3),
(456, 172, 0, 0, 0, 'img_3203_456.jpg', 1),
(329, 72, 0, 0, 0, 'dsc_6855_329.jpg', 2),
(334, 0, 0, 0, 0, 'gimg_334v1.jpg', 0),
(337, 0, 0, 0, 0, 'gimg_337v1.jpg', 0),
(338, 0, 0, 0, 0, 'gimg_338v1.jpg', 0),
(339, 0, 0, 0, 0, 'gimg_339v1.jpg', 0),
(344, 178, 0, 0, 0, 'dsc_6816_344v1.jpg', 2),
(345, 181, 0, 0, 0, '1-8_345v1.jpg', 2),
(353, 176, 0, 0, 0, '1-30_353v1.jpg', 2),
(385, 223, 0, 0, 0, 'photo-529_385.jpg', 3),
(386, 223, 0, 0, 0, 'photo-489_386.jpg', 2),
(387, 223, 0, 0, 0, 'photo-492_387v1.jpg', 1),
(388, 224, 0, 0, 0, 'photo-541_388.jpg', 2),
(389, 224, 0, 0, 0, 'photo-471_389v1.jpg', 1),
(390, 225, 0, 0, 0, 'photo-539_390.jpg', 2),
(391, 225, 0, 0, 0, 'photo-468_391v1.jpg', 1),
(392, 225, 0, 0, 0, 'photo-469_392.jpg', 3),
(398, 227, 0, 0, 0, 'photo-473_398.jpg', 3),
(397, 227, 0, 0, 0, 'photo-475_397v1.jpg', 1),
(396, 227, 0, 0, 0, 'photo-535_396.jpg', 2),
(399, 228, 0, 0, 0, 'photo-534_399.jpg', 2),
(400, 228, 0, 0, 0, 'photo-476_400v1.jpg', 1),
(401, 228, 0, 0, 0, 'photo-477_401.jpg', 3),
(402, 229, 0, 0, 0, 'photo-533_402.jpg', 2),
(403, 229, 0, 0, 0, 'photo-478_403.jpg', 3),
(404, 229, 0, 0, 0, 'photo-479_404v1.jpg', 1),
(406, 230, 0, 0, 0, 'photo-532_406.jpg', 2),
(472, 230, 0, 0, 0, 'img_2902_472.JPG', 3),
(408, 230, 0, 0, 0, 'photo-480_408v1.jpg', 1),
(409, 231, 0, 0, 0, 'photo-531_409.jpg', 3),
(410, 231, 0, 0, 0, 'photo-483_410.jpg', 2),
(439, 231, 0, 0, 0, 'photo-485_439v1.jpg', 1),
(412, 232, 0, 0, 0, 'photo-530_412.jpg', 2),
(466, 232, 0, 0, 0, 'img_2831_466.JPG', 3),
(414, 232, 0, 0, 0, 'photo-486_414v1.jpg', 1),
(415, 233, 0, 0, 0, 'photo-528_415.jpg', 2),
(476, 233, 0, 0, 0, 'img_2974_476.JPG', 3),
(417, 233, 0, 0, 0, 'photo-493_417v1.jpg', 1),
(418, 234, 0, 0, 0, 'photo-527_418.jpg', 2),
(421, 234, 0, 0, 0, 'photo-499_421v1.jpg', 1),
(422, 235, 0, 0, 0, 'photo-526_422.jpg', 2),
(423, 235, 0, 0, 0, 'photo-504_423.jpg', 3),
(424, 235, 0, 0, 0, 'photo-502_424v1.jpg', 1),
(425, 236, 0, 0, 0, 'photo-525_425.jpg', 2),
(426, 236, 0, 0, 0, 'photo-511_426.jpg', 3),
(427, 236, 0, 0, 0, 'photo-509_427v1.jpg', 1),
(428, 237, 0, 0, 0, 'photo-524_428.jpg', 2),
(429, 237, 0, 0, 0, 'photo-507_429.jpg', 3),
(430, 237, 0, 0, 0, 'photo-505_430v1.jpg', 1),
(431, 238, 0, 0, 0, 'photo-523_431.jpg', 2),
(432, 238, 0, 0, 0, 'photo-515_432.jpg', 3),
(433, 238, 0, 0, 0, 'photo-513_433v1.jpg', 1),
(434, 239, 0, 0, 0, 'photo-522_434.jpg', 2),
(437, 239, 0, 0, 0, 'photo-517_437.jpg', 3),
(436, 239, 0, 0, 0, 'photo-518_436v1.jpg', 1),
(438, 224, 0, 0, 0, 'photo-536_438.jpg', 3),
(480, 240, 0, 0, 0, 'img_3210_480.jpg', 3),
(457, 172, 0, 0, 0, 'img_3175_457.jpg', 2),
(479, 240, 0, 0, 0, 'img_3190_479.jpg', 2),
(477, 235, 0, 0, 0, 'img_2959_477.JPG', 4),
(454, 166, 0, 0, 0, 'img_3166_454.jpg', 1),
(478, 240, 0, 0, 0, 'img_3191_478.jpg', 4),
(495, 106, 0, 0, 0, 'img_1421_495.jpg', 2),
(496, 85, 0, 0, 0, 'img_1524_496.jpg', 2),
(497, 96, 0, 0, 0, 'img_1593_497.jpg', 2),
(498, 116, 0, 0, 0, 'img_3482_498.jpg', 2),
(499, 74, 0, 0, 0, 'img_1605_499.jpg', 2),
(500, 147, 0, 0, 0, 'img_1634_500.jpg', 2),
(501, 89, 0, 0, 0, 'img_1559_501.jpg', 2),
(502, 94, 0, 0, 0, 'img_1544_502.jpg', 2),
(503, 102, 0, 0, 0, 'img_3504_503.jpg', 2),
(504, 171, 0, 0, 0, 'img_3508_504.jpg', 2),
(505, 86, 0, 0, 0, 'img_1652_505.jpg', 2),
(506, 179, 0, 0, 0, 'img_3500_506.jpg', 2),
(507, 183, 0, 0, 0, 'img_1618_507.jpg', 2),
(508, 180, 0, 0, 0, 'img_1570_508.jpg', 2);

-- --------------------------------------------------------

--
-- Structure de la table `imagedesc`
--

CREATE TABLE `imagedesc` (
  `id` int(11) NOT NULL,
  `image` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `lang`
--

CREATE TABLE `lang` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `code` varchar(2) NOT NULL,
  `url` varchar(255) NOT NULL,
  `defaut` smallint(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `lang`
--

INSERT INTO `lang` (`id`, `description`, `code`, `url`, `defaut`) VALUES
(1, 'Français', 'fr', 'http://localhost:8088/boutique-ketmie', 1),
(2, 'English', 'en', '', 0),
(3, 'Espanol', 'es', '', 0),
(4, 'Italiano', 'it', '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `protege` smallint(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `nom`, `protege`) VALUES
(1, 'changepass', 0),
(2, 'mailconfirmcli', 0),
(3, 'mailconfirmadm', 0),
(6, 'colissimo', 0),
(5, 'creation_client', 0),
(7, 'mail-template', 0),
(8, 'virement-info', 0),
(9, 'cheque-info', 0),
(10, 'statut_commande', 0);

-- --------------------------------------------------------

--
-- Structure de la table `messagedesc`
--

CREATE TABLE `messagedesc` (
  `id` int(11) NOT NULL,
  `message` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `intitule` text NOT NULL,
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `descriptiontext` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `messagedesc`
--

INSERT INTO `messagedesc` (`id`, `message`, `lang`, `intitule`, `titre`, `chapo`, `description`, `descriptiontext`) VALUES
(1, 1, 1, 'Mail de changement de mot de passe', 'Votre nouveau mot de passe', '', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\" dir=\"ltr\" lang=\"fr\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"  />\n<title>courriel de confirmation de changement de mot de passe de __URLSITE__ </title>\n<style type=\"text/css\">\nbody {font-family: Arial, Helvetica, sans-serif;font-size:100%;text-align:center;}\n#liencompte {margin:25px 0 ;text-align: middle;font-size:10pt;}\n#wrapper {width:480pt;margin:0 auto;}\n#entete {padding-bottom:20px;margin-bottom:10px;border-bottom:1px dotted #000;}\n#logotexte {float:left;width:180pt;height:75pt;border:1pt solid #000;font-size:18pt;text-align:center;}\n#logoimg{float:left;}\n#h2 {margin:0;padding:0;font-size:140%;text-align:center;}\n#h3 {margin:0;padding:0;font-size:120%;text-align:center;}\n\n</style>\n</head>\n<body>\n<div id=\"wrapper\">\n<div id=\"entete\">\n<h1 id=\"logotexte\">__NOMSITE__</h1>\n<h2 id=\"info\">Changement de mot de passe</h2>\n<h5 id=\"mdp\"> Vous avez perdu votre mot de passe. <br />\n\nVotre nouveau mot de passe est <span style=\"font-size:80%\">__MOTDEPASSE__</span>.</h5>\n</div>\n</div>\n<p id=\"liencompte\">Vous  pouvez &agrave; pr&eacute;sent vous connecter sur <a href=\"__URLSITE__\">__URLSITE__</a>.<br /> N\'oubliez pas de modifier votre mot de passe.</p>\n\n</body>\n\n</html>', 'Votre nouveau mot de passe est : __MOTDEPASSE__'),
(2, 2, 1, 'Mail de confirmation client', 'Commande : __COMMANDE_REF__', '', '<div class=\"mail-body\">\r\n<h1 class=\"mail-header\"><img style=\"border:0px none;\" src=\"__LOGO_SITE__\"/></h1>\r\n<h2 class=\"mail-title\">Confirmation de commande</h2>\r\n<h3 class=\"mail-title\">N° __COMMANDE_REF__ du <span style=\"font-size:80%\">__COMMANDE_DATE__</span></h3>\r\n__PAIEMENT_INFO__\r\n<table class=\"table-detail\">\r\n<caption>Détail de la commande</caption>\r\n<thead class=\"detail-head\">\r\n<tr>\r\n<th colspan=\"2\" class=\"detail-th\">Désignation</th> \r\n<th class=\"detail-th\">Référence</th> \r\n<th class=\"detail-th\">P.U. ::euro::</th> \r\n<th class=\"detail-th\">Qté</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<VENTEPROD>\r\n<tr>\r\n<td class=\"cellprod-img\"><img src=\"__VENTEPROD_URL__\"/></td>\r\n<td class=\"cellprod-title\">__VENTEPROD_TITRE__</td>\r\n<td class=\"cellprod-ref\">__VENTEPROD_REF__</td>\r\n<td class=\"cellprod\">__VENTEPROD_PRIXU__</td>\r\n<td class=\"cellprod\">__VENTEPROD_QUANTITE__</td>\r\n</tr>\r\n</VENTEPROD>\r\n<tr>\r\n<td class=\"cellprod-right\" colspan=\"4\">Montant total avant remise  ::euro::</td>\r\n<td class=\"cellprod-right\">__COMMANDE_TOTAL__</td>\r\n</tr>\r\n<tr>\r\n<td class=\"cellprod-right\" colspan=\"4\">Remise  ::euro::</td>\r\n<td class=\"cellprod-right\">__COMMANDE_REMISE__</td>\r\n</tr>\r\n<tr>\r\n<td class=\"cellprod-right\" colspan=\"4\">Port  ::euro::</td>\r\n<td class=\"cellprod-right\">__COMMANDE_PORT__</td>\r\n</tr>\r\n<tr>\r\n<td class=\"cellprod-right-last\" colspan=\"4\">Montant total de la commande ::euro::</td>\r\n<td class=\"cellprod-right-last\">__COMMANDE_TOTALPORT__</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table class=\"table-base\">\r\n<caption>Livraison</caption>\r\n<tbody>\r\n<tr><td class=\"right\">Transporteur : </td><td>__COMMANDE_TRANSPORT__</td></tr>\r\n<tr><td class=\"right\">Nom : </td><td>__COMMANDE_LIVRRAISON__ __COMMANDE_LIVRPRENOM__ __COMMANDE_LIVRNOM__</td></tr>\r\n<tr><td class=\"right\">Adresse : </td><td>__ADRESSE_LIVRAISON__<br/>__COMMANDE_LIVRCPOSTAL__ __COMMANDE_LIVRVILLE__</td></tr>\r\n<tr><td class=\"right\">Pays : </td><td>__COMMANDE_LIVRPAYS__</td></tr>\r\n</tbody>\r\n</table>\r\n<table class=\"table-base\">\r\n<caption>Facturation</caption>\r\n<tbody>\r\n<tr><td class=\"right\">paiement par : </td><td>__COMMANDE_PAIEMENT__</td></tr>\r\n<tr><td class=\"right\">Nom : </td><td>__CIVILITE__  __PRENOM__ __NOM__</td></tr>\r\n<tr><td class=\"right\">Adresse : </td><td>__ADRESSE__<br/>__CPOSTAL__ __VILLE__</td></tr>\r\n<tr><td class=\"right\">Pays : </td><td>__PAYS__</td></tr>\r\n</tbody>\r\n</table>\r\n<p class=\"default\">Le suivi de votre commande est disponible dans la rubrique mon compte sur <a href=\"__URLSITE__\">__URLSITE__</a></p>\r\n</div>', 'Confirmation de commande\r\nN° __COMMANDE_REF__ du __COMMANDE_DATE__\r\n\r\n__PAIEMENT_INFO__\r\n\r\nDétail de la commande\r\n-------------------------------------------------------\r\n<VENTEPROD>\r\nArticle : __VENTEPROD_TITRE__\r\nRéférence : __VENTEPROD_REF__\r\nQuantité : __VENTEPROD_QUANTITE__\r\nPrix unitaire TTC : __VENTEPROD_PRIXU__ EUR\r\n-------------------------------------------------------\r\n</VENTEPROD>\r\nMontant total avant remise ::euro:: : __COMMANDE_TOTAL__\r\nRemise  ::euro:: : __COMMANDE_REMISE__\r\nPort ::euro:: : __COMMANDE_PORT__\r\nMontant total de la commande ::euro:: : __COMMANDE_TOTALPORT__\r\n=======================================================\r\n\r\nLivraison\r\n-------------------------------------------------------\r\nTransporteur : __COMMANDE_TRANSPORT__\r\nNom : __COMMANDE_LIVRRAISON__ __COMMANDE_LIVRPRENOM__ __COMMANDE_LIVRNOM__\r\nAdresse : __ADRESSE_LIVRAISON__\r\n__COMMANDE_LIVRCPOSTAL__ __COMMANDE_LIVRVILLE__\r\nPays : __COMMANDE_LIVRPAYS__\r\n=======================================================\r\n\r\nFacturation\r\n-------------------------------------------------------\r\npaiement par : __COMMANDE_PAIEMENT__\r\nNom : __CIVILITE__  __PRENOM__ __NOM__\r\n__ADRESSE__\r\n__CPOSTAL__ __VILLE__\r\nPays : __PAYS__\r\n=======================================================\r\n\r\nLe suivi de votre commande est disponible dans la rubrique mon compte sur __URLSITE__'),
(8, 8, 1, 'Information virement', 'Vous avez choisi le mode de paiement par virement', '', '<div><p class=\"default\">Vous avez choisi le mode paiement par virement. Voici les informations requises pour effectuer la transaction :</p>\r\n<table class=\"table-base\">\r\n<tbody>\r\n<tr><td class=\"right\">Destinataire : </td><td>Stéphanie Mousquey</td></tr>\r\n<tr><td class=\"right\">Libellé : </td><td>Commande __COMMANDE_REF__</td></tr>\r\n<tr><td class=\"right\">RIB : </td><td>15589 22802 02989255740 15</td></tr>\r\n<tr><td class=\"right\">IBAN : </td><td>FR76 1558 9228 0202 9892 5574 015</td></tr>\r\n<tr><td class=\"right\">BIC : </td><td>CMBRFR2BARK</td></tr>\r\n</tbody>\r\n</table>\r\n</div>', 'Vous avez choisi le mode paiement par virement. \r\nVoici les informations requises pour effectuer la transaction :\r\n-------------------------------------------------------\r\nDestinataire : Stéphanie Mousquey\r\nLibellé : Commande __COMMANDE_REF__\r\nRIB : 15589 22802 02989255740 15\r\nIBAN : FR76 1558 9228 0202 9892 5574 015\r\n======================================================='),
(9, 9, 1, 'Information chèque', 'Vous avez choisi le mode de paiement par chèque', '', '<div>\r\n<p class=\"default\">Vous avez choisi le mode paiement par chèque.<br/>\r\nEnvoyez votre chèque établi à l\'ordre de <span style=\"font-weight:bold;\">Stéphanie Mousquey</span> accompagné de la référence de la commande (__COMMANDE_REF__) à l\'adresse :</p>\r\n<table class=\"table-base\">\r\n<tbody>\r\n<tr><td>Stéphanie Mousquey</td></tr>\r\n<tr><td>23 place Jules Ferry</td></tr>\r\n<tr><td>Résidence Rohan, bâtiment nord</td></tr>\r\n<tr><td>56100 LORIENT</td></tr>\r\n</tbody>\r\n</table>\r\n</div>', 'Vous avez choisi le mode paiement par chèque.\r\nEnvoyez votre chèque établi à l\'ordre de Stéphanie Mousquey accompagné de la référence de la commande (__COMMANDE_REF__) à l\'adresse :\r\nStéphanie Mousquey\r\n23 place Jules Ferry\r\nRésidence Rohan, bâtiment nord\r\n56100 LORIENT'),
(3, 3, 1, 'Mail de confirmation administrateur', 'Nouvelle commande', '', '<div class=\"mail-body\">\n<h2 class=\"mail-title\">Une nouvelle commande a été passée</h2>\n<h3 class=\"mail-title\">N° __COMMANDE_REF__ du <span style=\"font-size:80%\">__COMMANDE_DATE__</span></h3>\n<table class=\"table-detail\">\n<caption>Détail de la commande</caption>\n<thead class=\"detail-head\">\n<tr>\n<th colspan=\"2\" class=\"detail-th\">Désignation</th> \n<th class=\"detail-th\">Référence</th> \n<th class=\"detail-th\">P.U. ::euro::</th> \n<th class=\"detail-th\">Qté</th>\n</tr>\n</thead>\n<tbody>\n<VENTEPROD>\n<tr>\n<td class=\"cellprod-img\"><img src=\"__VENTEPROD_URL__\"/></td>\n<td class=\"cellprod-title\">__VENTEPROD_TITRE__</td>\n<td class=\"cellprod-ref\">__VENTEPROD_REF__</td>\n<td class=\"cellprod\">__VENTEPROD_PRIXU__</td>\n<td class=\"cellprod\">__VENTEPROD_QUANTITE__</td>\n</tr>\n</VENTEPROD>\n<tr>\n<td class=\"cellprod-right\" colspan=\"4\">Montant total avant remise  ::euro::</td>\n<td class=\"cellprod-right\">__COMMANDE_TOTAL__</td>\n</tr>\n<tr>\n<td class=\"cellprod-right\" colspan=\"4\">Remise  ::euro::</td>\n<td class=\"cellprod-right\">__COMMANDE_REMISE__</td>\n</tr>\n<tr>\n<td class=\"cellprod-right\" colspan=\"4\">Port  ::euro::</td>\n<td class=\"cellprod-right\">__COMMANDE_PORT__</td>\n</tr>\n<tr>\n<td class=\"cellprod-right-last\" colspan=\"4\">Montant total de la commande ::euro::</td>\n<td class=\"cellprod-right-last\">__COMMANDE_TOTALPORT__</td>\n</tr>\n</tbody>\n</table>\n<table class=\"table-base\">\n<caption>Livraison</caption>\n<tbody>\n<tr><td class=\"right\">Transporteur : </td><td>__COMMANDE_TRANSPORT__</td></tr>\n<tr><td class=\"right\">Nom : </td><td>__COMMANDE_LIVRRAISON__ __COMMANDE_LIVRPRENOM__ __COMMANDE_LIVRNOM__</td></tr>\n<tr><td class=\"right\">Adresse : </td><td>__ADRESSE_LIVRAISON__<br/>__COMMANDE_LIVRCPOSTAL__ __COMMANDE_LIVRVILLE__</td></tr>\n<tr><td class=\"right\">Pays : </td><td>__COMMANDE_LIVRPAYS__</td></tr>\n</tbody>\n</table>\n<table class=\"table-base\">\n<caption>Facturation</caption>\n<tbody>\n<tr><td class=\"right\">paiement par : </td><td>__COMMANDE_PAIEMENT__</td></tr>\n<tr><td class=\"right\">N° de client : </td><td>__CLIENT_REF__</td></tr>\n<tr><td class=\"right\">Nom : </td><td>__CIVILITE__  __PRENOM__ __NOM__</td></tr>\n<tr><td class=\"right\">Adresse : </td><td>__ADRESSE__<br/>__CPOSTAL__ __VILLE__</td></tr>\n<tr><td class=\"right\">Pays : </td><td>__PAYS__</td></tr>\n</tbody>\n</table>\n<h2>AU BOULOT !!!</h2>\n</div>', 'Une nouvelle commande a été passée\r\nN° __COMMANDE_REF__ du __COMMANDE_DATE__\r\n\r\nDétail de la commande\r\n-------------------------------------------------------\r\n<VENTEPROD>\r\nArticle : __VENTEPROD_TITRE__\r\nRéférence : __VENTEPROD_REF__\r\nQuantité : __VENTEPROD_QUANTITE__\r\nPrix unitaire TTC : __VENTEPROD_PRIXU__ EUR\r\n-------------------------------------------------------\r\n</VENTEPROD>\r\nMontant total avant remise ::euro:: : __COMMANDE_TOTAL__\r\nRemise  ::euro:: : __COMMANDE_REMISE__\r\nPort ::euro:: : __COMMANDE_PORT__\r\nMontant total de la commande ::euro:: : __COMMANDE_TOTALPORT__\r\n=======================================================\r\n\r\nLivraison\r\n-------------------------------------------------------\r\nTransporteur : __COMMANDE_TRANSPORT__\r\nNom : __COMMANDE_LIVRRAISON__ __COMMANDE_LIVRPRENOM__ __COMMANDE_LIVRNOM__\r\nAdresse : __ADRESSE_LIVRAISON__\r\n__COMMANDE_LIVRCPOSTAL__ __COMMANDE_LIVRVILLE__\r\nPays : __COMMANDE_LIVRPAYS__\r\n=======================================================\r\n\r\nFacturation\r\n-------------------------------------------------------\r\npaiement par : __COMMANDE_PAIEMENT__\r\nN° de client : __CLIENT_REF__\r\nNom : __CIVILITE__  __PRENOM__ __NOM__\r\n__ADRESSE__\r\n__CPOSTAL__ __VILLE__\r\nPays : __PAYS__\r\n=======================================================\r\n\r\nAU BOULOT !!!'),
(6, 6, 1, 'Envoi du numéro de suivi Colissimo', 'Expédition de votre commande __COMMANDE__', '', '__RAISON__ __NOM__ __PRENOM__,<br />\n<br />\nNous vous remercions de votre commande sur notre site __URLSITE__.<br />\n<br />\nUn colis concernant votre commande __COMMANDE__ du __DATE__ __HEURE__ a quitté nos entrepôts pour être pris en charge par La Poste le __DATEDJ__.<br />\n<br />\nSon numéro de suivi est le suivant : __COLIS__<br />\n<br />\nIl vous permet de suivre votre colis en ligne sur le site de La Poste : http://www.coliposte.net.<br />\nIl vous sera, par ailleurs, très utile si vous étiez absent au moment de la livraison de votre colis : en fournissant ce numéro de Colissimo Suivi, vous pourrez retirer votre colis dans le bureau de Poste le plus proche.<br />\n<br />\nATTENTION ! Si vous ne trouvez pas l\'avis de passage normalement déposé dans votre boîte aux lettres au bout de 48 Heures jours ouvrables, n\'hésitez pas à aller le réclamer à votre bureau de Poste, muni de votre numéro de Colissimo Suivi.<br />\n<br />\nNous restons à votre disposition pour toute information complémentaire.<br />\nCordialement,<br />\nL\'équipe __NOMSITE__.<br />\n', '__RAISON__ __NOM__ __PRENOM__,\n\nNous vous remercions de votre commande sur notre site __URLSITE__.\n\nUn colis concernant votre commande __COMMANDE__ du __DATE__ __HEURE__ a quitté nos entrepôts pour être pris en charge par La Poste le __DATEDJ__.\n\nSon numéro de suivi est le suivant : __COLIS__\n\nIl vous permet de suivre votre colis en ligne sur le site de La Poste : http://www.coliposte.net.\nIl vous sera, par ailleurs, très utile si vous étiez absent au moment de la livraison de votre colis : en fournissant ce numéro de Colissimo Suivi, vous pourrez retirer votre colis dans le bureau de Poste le plus proche.\n\nATTENTION ! Si vous ne trouvez pas l\'avis de passage normalement déposé dans votre boîte aux lettres au bout de 48 Heures jours ouvrables, n\'hésitez pas à aller le réclamer à votre bureau de Poste, muni de votre numéro de Colissimo Suivi.\n\nNous restons à votre disposition pour toute information complémentaire.\nCordialement,\nL\'équipe __NOMSITE__.\n'),
(5, 5, 1, 'Confirmation de création de compte sur __NOMSITE__', 'Création compte client', '', '<div class=\"mail-body\">\r\n<h1 class=\"mail-header\"><img style=\"border:0px none;\" src=\"__LOGO_SITE__\"/></h1>\r\n<h2 class=\"mail-title\">Création de compte</h2>\r\n<table class=\"table-base\">\r\n<caption>Vous avez créé un compte sur <a class=\"link\" href=\"__URLSITE__\">__NOMSITE__</a>.</caption>\r\n<tbody>	\r\n<tr><td class=\"right\">votre identifiant : </td><td><span class=\"mail-span\">__EMAIL__</span></td></tr>\r\n<tr><td class=\"right\">votre mot de passe : </td><td>__MOTDEPASSE__</td></tr>\r\n</tbody>\r\n</table>\r\n<table class=\"table-base\">\r\n<caption>Récapitulatif de votre compte :</caption>\r\n<tbody>\r\n<tr><td class=\"right\">Adresse : </td><td>__ADRESSE__</td></tr>\r\n<tr><td class=\"right\">Code postal : </td><td>__CPOSTAL__</td></tr>\r\n<tr><td class=\"right\">Ville : </td><td>__VILLE__</td></tr>\r\n<tr><td class=\"right\">Pays : </td><td>__PAYS__</td></tr>\r\n<tr><td class=\"right\">Téléphone : </td><td>__TELEPHONE__</td></tr>\r\n<tr><td class=\"right\">email : </td><td><span class=\"mail-span\">__EMAIL__</span></td></tr>\r\n</tbody>\r\n</table>\r\n<p class=\"default\">Vous pouvez à présent vous connecter sur <a class=\"link\" href=\"__URLSITE__\">__URLSITE__</a>.</p>\r\n</div>', '----------------------------------------------\r\nCréation de compte\r\n----------------------------------------------\r\n\r\n\r\nVous avez créé un compte sur __NOMSITE__.\r\n\r\n----------------------------------------------\r\nvotre identifiant : __EMAIL__\r\nvotre mot de passe : __MOTDEPASSE__\r\n----------------------------------------------\r\n\r\n\r\nRécapitulatif de votre compte :\r\n\r\n----------------------------------------------\r\nAdresse : __ADRESSE__\r\nCode postal : __CPOSTAL__\r\nVille : __VILLE__\r\nPays : __PAYS__\r\nTéléphone : __TELEPHONE__\r\nemail : __EMAIL__\r\n----------------------------------------------	\r\n\r\n\r\nVous pouvez à présent vous connecter sur __URLSITE__.'),
(7, 7, 1, '', '', '', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" dir=\"ltr\" lang=\"fr\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"  />\r\n<title>__TITLE__</title>\r\n</head>\r\n<body>\r\n__MESSAGE__\r\n</body>\r\n</html>', ''),
(10, 10, 1, 'Suivi de votre commande __COMMANDE__', 'Suivi de votre commande __COMMANDE__', '', '<div class=\"mail-body\">\r\n<h1 class=\"mail-header\"><img style=\"border:0px none;\" src=\"__LOGO_SITE__\"/></h1>\r\n<p class=\"default\">__STATUT__</p>\r\n<p class=\"default\">Le suivi de votre commande est disponible dans la rubrique mon compte sur <a href=\"__URLSITE__\">__URLSITE__</a></p>\r\n</div>', '__STATUT__\r\nLe suivi de votre commande est disponible dans la rubrique mon compte sur __URLSITE__');

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `type` smallint(6) NOT NULL,
  `actif` smallint(6) NOT NULL,
  `classement` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `modules`
--

INSERT INTO `modules` (`id`, `nom`, `type`, `actif`, `classement`) VALUES
(19, 'colissimo', 2, 1, 1),
(20, 'cheque', 1, 1, 1),
(21, 'virement', 1, 1, 2),
(28, 'filtreajaxform', 4, 1, 1),
(24, 'tinymce4', 3, 1, 1),
(25, 'filtrecarac', 4, 1, 0),
(26, 'filtreimglist', 4, 1, 0),
(27, 'filtremenu', 4, 1, 0),
(29, 'filtreamf', 4, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `modulesdesc`
--

CREATE TABLE `modulesdesc` (
  `id` int(11) NOT NULL,
  `plugin` text NOT NULL,
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `devise` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `modulesdesc`
--

INSERT INTO `modulesdesc` (`id`, `plugin`, `lang`, `titre`, `chapo`, `description`, `devise`) VALUES
(5, 'colissimo', 1, 'Colissimo', 'Colissimo', '', 0),
(6, 'cheque', 1, 'Chèque', 'Chèque', '', 0),
(7, 'virement', 1, 'Virement', 'Virement', '', 0),
(9, 'tinymce4', 1, 'TinyMCE 4', 'Utiliser TinyMCE 4 et ResponsiveFilemanager 9 dans votre interface d\'administration', 'Module de TinyMCE4.', 0);

-- --------------------------------------------------------

--
-- Structure de la table `pays`
--

CREATE TABLE `pays` (
  `id` int(11) NOT NULL,
  `lang` int(11) NOT NULL DEFAULT '0',
  `zone` int(11) NOT NULL DEFAULT '0',
  `defaut` int(11) NOT NULL,
  `tva` smallint(6) NOT NULL,
  `isocode` varchar(4) NOT NULL DEFAULT '',
  `isoalpha2` varchar(2) NOT NULL DEFAULT '',
  `isoalpha3` varchar(4) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pays`
--

INSERT INTO `pays` (`id`, `lang`, `zone`, `defaut`, `tva`, `isocode`, `isoalpha2`, `isoalpha3`) VALUES
(1, 2, 9, 0, 0, '004', 'AF', 'AFG'),
(2, 2, 8, 0, 0, '710', 'ZA', 'ZAF'),
(3, 2, 7, 0, 0, '008', 'AL', 'ALB'),
(4, 2, 7, 0, 0, '012', 'DZ', 'DZA'),
(5, 2, 2, 0, 1, '276', 'DE', 'DEU'),
(6, 1, 1, 0, 0, '020', 'AD', 'AND'),
(7, 2, 8, 0, 0, '024', 'AO', 'AGO'),
(8, 2, 9, 0, 0, '028', 'AG', 'ATG'),
(9, 2, 8, 0, 0, '682', 'SA', 'SAU'),
(10, 3, 9, 0, 0, '032', 'AR', 'ARG'),
(11, 2, 7, 0, 0, '051', 'AM', 'ARM'),
(12, 2, 9, 0, 0, '036', 'AU', 'AUS'),
(13, 2, 4, 0, 1, '040', 'AT', 'AUT'),
(14, 2, 7, 0, 0, '031', 'AZ', 'AZE'),
(15, 2, 9, 0, 0, '044', 'BS', 'BHS'),
(16, 2, 8, 0, 0, '048', 'BR', 'BHR'),
(17, 2, 9, 0, 0, '050', 'BD', 'BGD'),
(18, 2, 9, 0, 0, '052', 'BB', 'BRB'),
(19, 2, 9, 0, 0, '585', 'PW', 'PLW'),
(20, 1, 2, 0, 1, '056', 'BE', 'BEL'),
(21, 2, 9, 0, 0, '084', 'BL', 'BLZ'),
(22, 2, 8, 0, 0, '204', 'BJ', 'BEN'),
(23, 2, 9, 0, 0, '064', 'BT', 'BTN'),
(24, 2, 7, 0, 0, '112', 'BY', 'BLR'),
(25, 2, 9, 0, 0, '104', 'MM', 'MMR'),
(26, 2, 9, 0, 0, '068', 'BO', 'BOL'),
(27, 2, 7, 0, 0, '070', 'BA', 'BIH'),
(28, 2, 8, 0, 0, '072', 'BW', 'BWA'),
(29, 2, 9, 0, 0, '076', 'BR', 'BRA'),
(30, 2, 9, 0, 0, '096', 'BN', 'BRN'),
(31, 2, 7, 0, 1, '100', 'BG', 'BGR'),
(32, 2, 8, 0, 0, '854', 'BF', 'BFA'),
(33, 2, 8, 0, 0, '108', 'BI', 'BDI'),
(34, 2, 9, 0, 0, '116', 'KH', 'KHM'),
(35, 2, 8, 0, 0, '120', 'CM', 'CMR'),
(246, 2, 9, 0, 0, '124', 'CA', 'CAN'),
(37, 2, 8, 0, 0, '132', 'CV', 'CPV'),
(38, 2, 9, 0, 0, '152', 'CL', 'CHL'),
(39, 2, 9, 0, 0, '156', 'CN', 'CHN'),
(40, 2, 7, 0, 1, '196', 'CY', 'CYP'),
(41, 3, 9, 0, 0, '170', 'CO', 'COL'),
(42, 2, 8, 0, 0, '174', 'KM', 'COM'),
(43, 2, 8, 0, 0, '178', 'CG', 'COG'),
(44, 2, 9, 0, 0, '184', 'CK', 'COK'),
(45, 2, 9, 0, 0, '408', 'KP', 'PRK'),
(46, 2, 9, 0, 0, '410', 'KR', 'KOR'),
(47, 2, 9, 0, 0, '188', 'CR', 'CRI'),
(48, 2, 8, 0, 0, '384', 'CI', 'CIV'),
(49, 2, 7, 0, 0, '191', 'HR', 'HRV'),
(50, 3, 9, 0, 0, '192', 'CU', 'CUB'),
(51, 2, 4, 0, 1, '208', 'DK', 'DNK'),
(52, 2, 8, 0, 0, '262', 'DJ', 'DJI'),
(53, 2, 9, 0, 0, '212', 'DM', 'DMA'),
(54, 2, 8, 0, 0, '818', 'EG', 'EGY'),
(55, 2, 8, 0, 0, '784', 'AE', 'ARE'),
(56, 2, 9, 0, 0, '218', 'EC', 'ECU'),
(57, 2, 8, 0, 0, '232', 'ER', 'ERI'),
(58, 3, 3, 0, 1, '724', 'ES', 'ESP'),
(59, 2, 7, 0, 1, '233', 'EE', 'EST'),
(61, 2, 8, 0, 0, '231', 'ET', 'ETH'),
(62, 2, 9, 0, 0, '242', 'FJ', 'FJI'),
(63, 2, 5, 0, 1, '246', 'FI', 'FIN'),
(64, 2, 1, 1, 1, '250', 'FR', 'FRA'),
(65, 2, 8, 0, 0, '266', 'GA', 'GAB'),
(66, 2, 8, 0, 0, '270', 'GM', 'GMB'),
(67, 2, 7, 0, 0, '268', 'GE', 'GEO'),
(68, 2, 8, 0, 0, '288', 'GH', 'GHA'),
(69, 2, 6, 0, 1, '300', 'GR', 'GRC'),
(70, 2, 9, 0, 0, '308', 'GD', 'GRD'),
(71, 3, 9, 0, 0, '320', 'GT', 'GTM'),
(72, 2, 8, 0, 0, '324', 'GN', 'GIN'),
(73, 2, 8, 0, 0, '624', 'GW', 'GNB'),
(74, 2, 8, 0, 0, '226', 'GQ', 'GNQ'),
(75, 2, 9, 0, 0, '328', 'GY', 'GUY'),
(76, 2, 9, 0, 0, '332', 'HT', 'HTI'),
(77, 2, 9, 0, 0, '340', 'HN', 'HND'),
(78, 2, 6, 0, 1, '348', 'HU', 'HUN'),
(79, 2, 9, 0, 0, '356', 'IN', 'IND'),
(80, 2, 9, 0, 0, '360', 'ID', 'IDN'),
(81, 2, 8, 0, 0, '364', 'IR', 'IRN'),
(82, 2, 8, 0, 0, '368', 'IQ', 'IRQ'),
(83, 2, 4, 0, 1, '372', 'IE', 'IRL'),
(84, 2, 6, 0, 0, '352', 'IS', 'ISL'),
(85, 2, 8, 0, 0, '376', 'IL', 'ISR'),
(86, 4, 3, 0, 1, '380', 'IT', 'ITA'),
(87, 2, 9, 0, 0, '388', 'JM', 'JAM'),
(88, 2, 9, 0, 0, '392', 'JP', 'JPN'),
(89, 2, 8, 0, 0, '400', 'JO', 'JOR'),
(90, 2, 9, 0, 0, '398', 'KZ', 'KAZ'),
(91, 2, 8, 0, 0, '404', 'KE', 'KEN'),
(92, 2, 9, 0, 0, '417', 'KG', 'KGZ'),
(93, 2, 9, 0, 0, '296', 'KI', 'KIR'),
(94, 2, 8, 0, 0, '414', 'KW', 'KWT'),
(95, 2, 9, 0, 0, '418', 'LA', 'LAO'),
(96, 2, 8, 0, 0, '426', 'LS', 'LSO'),
(97, 2, 7, 0, 1, '428', 'LV', 'LVA'),
(98, 2, 8, 0, 0, '422', 'LB', 'LBN'),
(99, 2, 8, 0, 0, '430', 'LR', 'LBR'),
(100, 2, 8, 0, 0, '343', 'LY', 'LBY'),
(101, 2, 6, 0, 0, '438', 'LI', 'LIE'),
(102, 2, 7, 0, 1, '440', 'LT', 'LTU'),
(103, 2, 2, 0, 1, '442', 'LU', 'LUX'),
(104, 2, 7, 0, 0, '807', 'MK', 'MKD'),
(105, 2, 8, 0, 0, '450', 'MD', 'MDG'),
(106, 2, 9, 0, 0, '458', 'MY', 'MYS'),
(107, 2, 8, 0, 0, '454', 'MW', 'MWI'),
(108, 2, 9, 0, 0, '462', 'MV', 'MDV'),
(109, 2, 8, 0, 0, '466', 'ML', 'MLI'),
(110, 2, 8, 0, 1, '470', 'MT', 'MLT'),
(111, 2, 7, 0, 0, '504', 'MA', 'MAR'),
(112, 2, 9, 0, 0, '584', 'MH', 'MHL'),
(113, 2, 8, 0, 0, '480', 'MU', 'MUS'),
(114, 2, 8, 0, 0, '478', 'MR', 'MRT'),
(115, 2, 9, 0, 0, '484', 'MX', 'MEX'),
(116, 2, 9, 0, 0, '583', 'FM', 'FSM'),
(117, 2, 7, 0, 0, '498', 'MD', 'MDA'),
(118, 2, 1, 0, 1, '492', 'MC', 'MCO'),
(119, 2, 9, 0, 0, '496', 'MN', 'MNG'),
(120, 2, 8, 0, 0, '508', 'MZ', 'MOZ'),
(121, 2, 8, 0, 0, '516', 'NA', 'NAM'),
(122, 2, 9, 0, 0, '520', 'NR', 'NRU'),
(123, 2, 9, 0, 0, '524', 'NP', 'NPL'),
(124, 2, 9, 0, 0, '558', 'NI', 'NIC'),
(125, 2, 8, 0, 0, '562', 'NE', 'NER'),
(126, 2, 8, 0, 0, '566', 'NG', 'NGA'),
(127, 2, 9, 0, 0, '570', 'NU', 'NIU'),
(128, 2, 5, 0, 0, '578', 'NO', 'NOR'),
(129, 2, 9, 0, 0, '554', 'NZ', 'NZL'),
(130, 2, 8, 0, 0, '512', 'OM', 'OMN'),
(131, 2, 8, 0, 0, '800', 'UG', 'UGA'),
(132, 2, 9, 0, 0, '860', 'UZ', 'UZB'),
(133, 2, 9, 0, 0, '586', 'PK', 'PAK'),
(134, 2, 9, 0, 0, '591', 'PA', 'PAN'),
(135, 2, 9, 0, 0, '598', 'PG', 'PNG'),
(136, 2, 9, 0, 0, '600', 'PY', 'PRY'),
(137, 2, 2, 0, 1, '528', 'NL', 'NLD'),
(138, 2, 9, 0, 0, '604', 'PE', 'PER'),
(139, 2, 9, 0, 0, '608', 'PH', 'PHL'),
(140, 2, 6, 0, 1, '616', 'PL', 'POL'),
(141, 2, 4, 0, 1, '620', 'PT', 'PRT'),
(142, 2, 8, 0, 0, '634', 'QA', 'QAT'),
(143, 2, 8, 0, 0, '140', 'CF', 'CAF'),
(144, 2, 9, 0, 0, '214', 'DO', 'DOM'),
(145, 2, 6, 0, 1, '203', 'CZ', 'CZE'),
(146, 2, 7, 0, 1, '642', 'RO', 'ROU'),
(147, 2, 3, 0, 1, '826', 'GB', 'GBR'),
(148, 2, 7, 0, 0, '643', 'RU', 'RUS'),
(149, 2, 8, 0, 0, '646', 'RW', 'RWA'),
(150, 2, 9, 0, 0, '659', 'KN', 'KNA'),
(151, 2, 9, 0, 0, '662', 'LC', 'LCA'),
(152, 2, 9, 0, 0, '674', 'SM', 'SMR'),
(153, 2, 9, 0, 0, '670', 'VC', 'VCT'),
(154, 2, 9, 0, 0, '090', 'SB', 'SLB'),
(155, 2, 9, 0, 0, '222', 'SV', 'SLV'),
(156, 2, 9, 0, 0, '882', 'WS', 'WSM'),
(157, 2, 8, 0, 0, '678', 'ST', 'STP'),
(158, 2, 8, 0, 0, '686', 'SN', 'SEN'),
(159, 2, 9, 0, 0, '690', 'SC', 'SYC'),
(160, 2, 8, 0, 0, '694', 'SL', 'SLE'),
(161, 2, 9, 0, 0, '702', 'SG', 'SGP'),
(162, 2, 6, 0, 1, '703', 'SK', 'SVK'),
(163, 2, 6, 0, 1, '705', 'SI', 'SVN'),
(164, 2, 8, 0, 0, '706', 'SO', 'SOM'),
(165, 2, 8, 0, 0, '729', 'SD', 'SDN'),
(166, 2, 9, 0, 0, '144', 'LK', 'LKA'),
(167, 2, 5, 0, 1, '752', 'SE', 'SWE'),
(168, 1, 5, 0, 0, '756', 'CH', 'CHE'),
(169, 2, 9, 0, 0, '740', 'SR', 'SUR'),
(170, 2, 8, 0, 0, '748', 'SZ', 'SWZ'),
(171, 2, 8, 0, 0, '760', 'SY', 'SYR'),
(172, 2, 9, 0, 0, '762', 'TJ', 'TJK'),
(173, 2, 8, 0, 0, '834', 'TZ', 'TZA'),
(174, 2, 8, 0, 0, '148', 'TD', 'TCD'),
(175, 2, 9, 0, 0, '764', 'TH', 'THA'),
(176, 2, 8, 0, 0, '768', 'TG', 'TGO'),
(177, 2, 9, 0, 0, '776', 'TO', 'TON'),
(178, 2, 9, 0, 0, '780', 'TT', 'TTO'),
(179, 2, 7, 0, 0, '788', 'TN', 'TUN'),
(180, 2, 9, 0, 0, '795', 'TM', 'TKM'),
(181, 2, 7, 0, 0, '792', 'TR', 'TUR'),
(182, 2, 9, 0, 0, '798', 'TV', 'TUV'),
(183, 2, 7, 0, 0, '804', 'UA', 'UKR'),
(184, 2, 9, 0, 0, '858', 'UY', 'URY'),
(185, 2, 3, 0, 0, '336', 'VA', 'VAT'),
(186, 2, 9, 0, 0, '548', 'VU', 'VUT'),
(187, 2, 9, 0, 0, '862', 'VE', 'VEN'),
(188, 2, 9, 0, 0, '704', 'VN', 'VNM'),
(189, 2, 8, 0, 0, '887', 'YE', 'YEM'),
(190, 2, 7, 0, 0, '807', 'MK', 'MKD'),
(191, 2, 8, 0, 0, '180', 'CD', 'COD'),
(192, 2, 8, 0, 0, '894', 'ZM', 'ZMB'),
(193, 2, 8, 0, 0, '716', 'ZW', 'ZWE'),
(247, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(196, 2, 8, 0, 0, '840', 'US', 'USA'),
(197, 2, 8, 0, 0, '840', 'US', 'USA'),
(198, 2, 8, 0, 0, '840', 'US', 'USA'),
(199, 2, 8, 0, 0, '840', 'US', 'USA'),
(200, 2, 8, 0, 0, '840', 'US', 'USA'),
(201, 2, 8, 0, 0, '840', 'US', 'USA'),
(202, 2, 8, 0, 0, '840', 'US', 'USA'),
(203, 2, 8, 0, 0, '840', 'US', 'USA'),
(204, 2, 8, 0, 0, '840', 'US', 'USA'),
(205, 2, 8, 0, 0, '840', 'US', 'USA'),
(206, 2, 8, 0, 0, '840', 'US', 'USA'),
(207, 2, 8, 0, 0, '840', 'US', 'USA'),
(208, 2, 8, 0, 0, '840', 'US', 'USA'),
(209, 2, 8, 0, 0, '840', 'US', 'USA'),
(210, 2, 8, 0, 0, '840', 'US', 'USA'),
(211, 2, 8, 0, 0, '840', 'US', 'USA'),
(212, 2, 8, 0, 0, '840', 'US', 'USA'),
(213, 2, 8, 0, 0, '840', 'US', 'USA'),
(214, 2, 8, 0, 0, '840', 'US', 'USA'),
(215, 2, 8, 0, 0, '840', 'US', 'USA'),
(216, 2, 8, 0, 0, '840', 'US', 'USA'),
(217, 2, 8, 0, 0, '840', 'US', 'USA'),
(218, 2, 8, 0, 0, '840', 'US', 'USA'),
(219, 2, 8, 0, 0, '840', 'US', 'USA'),
(220, 2, 8, 0, 0, '840', 'US', 'USA'),
(221, 2, 8, 0, 0, '840', 'US', 'USA'),
(222, 2, 8, 0, 0, '840', 'US', 'USA'),
(223, 2, 8, 0, 0, '840', 'US', 'USA'),
(224, 2, 8, 0, 0, '840', 'US', 'USA'),
(225, 2, 8, 0, 0, '840', 'US', 'USA'),
(226, 2, 8, 0, 0, '840', 'US', 'USA'),
(227, 2, 8, 0, 0, '840', 'US', 'USA'),
(228, 2, 8, 0, 0, '840', 'US', 'USA'),
(229, 2, 8, 0, 0, '840', 'US', 'USA'),
(230, 2, 8, 0, 0, '840', 'US', 'USA'),
(231, 2, 8, 0, 0, '840', 'US', 'USA'),
(232, 2, 8, 0, 0, '840', 'US', 'USA'),
(233, 2, 8, 0, 0, '840', 'US', 'USA'),
(234, 2, 8, 0, 0, '840', 'US', 'USA'),
(235, 2, 8, 0, 0, '840', 'US', 'USA'),
(236, 2, 8, 0, 0, '840', 'US', 'USA'),
(237, 2, 8, 0, 0, '840', 'US', 'USA'),
(238, 2, 8, 0, 0, '840', 'US', 'USA'),
(239, 2, 8, 0, 0, '840', 'US', 'USA'),
(240, 2, 8, 0, 0, '840', 'US', 'USA'),
(241, 2, 8, 0, 0, '840', 'US', 'USA'),
(242, 2, 8, 0, 0, '840', 'US', 'USA'),
(243, 2, 8, 0, 0, '840', 'US', 'USA'),
(244, 2, 8, 0, 0, '840', 'US', 'USA'),
(245, 2, 8, 0, 0, '840', 'US', 'USA'),
(248, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(249, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(250, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(251, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(252, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(253, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(254, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(255, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(256, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(257, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(258, 2, 8, 0, 0, '124', 'CA', 'CAN'),
(259, 1, 10, 0, 0, '312', 'GP', 'GLP'),
(260, 1, 10, 0, 0, '254', 'GF', 'GUF'),
(261, 1, 10, 0, 0, '474', 'MQ', 'MTQ'),
(262, 1, 10, 0, 0, '175', 'YT', 'MYT'),
(263, 1, 10, 0, 0, '638', 'RE', 'REU'),
(264, 1, 10, 0, 0, '666', 'PM', 'SPM'),
(265, 1, 11, 0, 0, '540', 'NC', 'NCL'),
(266, 1, 11, 0, 0, '258', 'PF', 'PYF'),
(267, 1, 11, 0, 0, '876', 'WF', 'WLF'),
(268, 2, 11, 0, 0, '840', 'US', 'USA');

-- --------------------------------------------------------

--
-- Structure de la table `paysdesc`
--

CREATE TABLE `paysdesc` (
  `id` int(11) NOT NULL,
  `pays` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `paysdesc`
--

INSERT INTO `paysdesc` (`id`, `pays`, `lang`, `titre`, `chapo`, `description`) VALUES
(1, 1, 1, 'Afghanistan', '', ''),
(2, 2, 1, 'Afrique du Sud', '', ''),
(3, 3, 1, 'Albanie', '', ''),
(4, 4, 1, 'Algérie', '', ''),
(5, 5, 1, 'Allemagne', '', ''),
(6, 6, 1, 'Andorre', '', ''),
(7, 7, 1, 'Angola', '', ''),
(8, 8, 1, 'Antigua-et-Barbuda', '', ''),
(9, 9, 1, 'Arabie saoudite', '', ''),
(10, 10, 1, 'Argentine', '', ''),
(11, 11, 1, 'Arménie', '', ''),
(12, 12, 1, 'Australie', '', ''),
(13, 13, 1, 'Autriche', '', ''),
(14, 14, 1, 'Azerbaïdjan', '', ''),
(15, 15, 1, 'Bahamas', '', ''),
(16, 16, 1, 'Bahreïn', '', ''),
(17, 17, 1, 'Bangladesh', '', ''),
(18, 18, 1, 'Barbade', '', ''),
(19, 19, 1, 'Belau', '', ''),
(20, 20, 1, 'Belgique', '', ''),
(21, 21, 1, 'Belize', '', ''),
(22, 22, 1, 'Bénin', '', ''),
(23, 23, 1, 'Bhoutan', '', ''),
(24, 24, 1, 'Biélorussie', '', ''),
(25, 25, 1, 'Birmanie', '', ''),
(26, 26, 1, 'Bolivie', '', ''),
(27, 27, 1, 'Bosnie-Herzégovine', '', ''),
(28, 28, 1, 'Botswana', '', ''),
(29, 29, 1, 'Brésil', '', ''),
(30, 30, 1, 'Brunei', '', ''),
(31, 31, 1, 'Bulgarie', '', ''),
(32, 32, 1, 'Burkina', '', ''),
(33, 33, 1, 'Burundi', '', ''),
(34, 34, 1, 'Cambodge', '', ''),
(35, 35, 1, 'Cameroun', '', ''),
(37, 37, 1, 'Cap-Vert', '', ''),
(38, 38, 1, 'Chili', '', ''),
(39, 39, 1, 'Chine', '', ''),
(40, 40, 1, 'Chypre', '', ''),
(41, 41, 1, 'Colombie', '', ''),
(42, 42, 1, 'Comores', '', ''),
(43, 43, 1, 'Congo', '', ''),
(44, 44, 1, 'Cook', '', ''),
(45, 45, 1, 'Corée du Nord', '', ''),
(46, 46, 1, 'Corée du Sud', '', ''),
(47, 47, 1, 'Costa Rica', '', ''),
(48, 48, 1, 'Côte d\'Ivoire', '', ''),
(49, 49, 1, 'Croatie', '', ''),
(50, 50, 1, 'Cuba', '', ''),
(51, 51, 1, 'Danemark', '', ''),
(52, 52, 1, 'Djibouti', '', ''),
(53, 53, 1, 'Dominique', '', ''),
(54, 54, 1, 'Égypte', '', ''),
(55, 55, 1, 'Émirats arabes unis', '', ''),
(56, 56, 1, 'Équateur', '', ''),
(57, 57, 1, 'Érythrée', '', ''),
(58, 58, 1, 'Espagne', '', ''),
(59, 59, 1, 'Estonie', '', ''),
(582, 198, 1, 'USA - Arkansas', '', ''),
(61, 61, 1, 'Éthiopie', '', ''),
(62, 62, 1, 'Fidji', '', ''),
(63, 63, 1, 'Finlande', '', ''),
(64, 64, 1, 'France métropolitaine', '', ''),
(65, 65, 1, 'Gabon', '', ''),
(66, 66, 1, 'Gambie', '', ''),
(67, 67, 1, 'Géorgie', '', ''),
(68, 68, 1, 'Ghana', '', ''),
(69, 69, 1, 'Grèce', '', ''),
(70, 70, 1, 'Grenade', '', ''),
(71, 71, 1, 'Guatemala', '', ''),
(72, 72, 1, 'Guinée', '', ''),
(73, 73, 1, 'Guinée-Bissao', '', ''),
(74, 74, 1, 'Guinée équatoriale', '', ''),
(75, 75, 1, 'Guyana', '', ''),
(76, 76, 1, 'Haïti', '', ''),
(77, 77, 1, 'Honduras', '', ''),
(78, 78, 1, 'Hongrie', '', ''),
(79, 79, 1, 'Inde', '', ''),
(80, 80, 1, 'Indonésie', '', ''),
(81, 81, 1, 'Iran', '', ''),
(82, 82, 1, 'Iraq', '', ''),
(83, 83, 1, 'Irlande', '', ''),
(84, 84, 1, 'Islande', '', ''),
(85, 85, 1, 'Israël', '', ''),
(86, 86, 1, 'Italie', '', ''),
(87, 87, 1, 'Jamaïque', '', ''),
(88, 88, 1, 'Japon', '', ''),
(89, 89, 1, 'Jordanie', '', ''),
(90, 90, 1, 'Kazakhstan', '', ''),
(91, 91, 1, 'Kenya', '', ''),
(92, 92, 1, 'Kirghizistan', '', ''),
(93, 93, 1, 'Kiribati', '', ''),
(94, 94, 1, 'Koweït', '', ''),
(95, 95, 1, 'Laos', '', ''),
(96, 96, 1, 'Lesotho', '', ''),
(97, 97, 1, 'Lettonie', '', ''),
(98, 98, 1, 'Liban', '', ''),
(99, 99, 1, 'Liberia', '', ''),
(100, 100, 1, 'Libye', '', ''),
(101, 101, 1, 'Liechtenstein', '', ''),
(102, 102, 1, 'Lituanie', '', ''),
(103, 103, 1, 'Luxembourg', '', ''),
(104, 104, 1, 'Macédoine', '', ''),
(105, 105, 1, 'Madagascar', '', ''),
(106, 106, 1, 'Malaisie', '', ''),
(107, 107, 1, 'Malawi', '', ''),
(108, 108, 1, 'Maldives', '', ''),
(109, 109, 1, 'Mali', '', ''),
(110, 110, 1, 'Malte', '', ''),
(111, 111, 1, 'Maroc', '', ''),
(112, 112, 1, 'Marshall', '', ''),
(113, 113, 1, 'Maurice', '', ''),
(114, 114, 1, 'Mauritanie', '', ''),
(115, 115, 1, 'Mexique', '', ''),
(116, 116, 1, 'Micronésie', '', ''),
(117, 117, 1, 'Moldavie', '', ''),
(118, 118, 1, 'Monaco', '', ''),
(119, 119, 1, 'Mongolie', '', ''),
(120, 120, 1, 'Mozambique', '', ''),
(121, 121, 1, 'Namibie', '', ''),
(122, 122, 1, 'Nauru', '', ''),
(123, 123, 1, 'Népal', '', ''),
(124, 124, 1, 'Nicaragua', '', ''),
(125, 125, 1, 'Niger', '', ''),
(126, 126, 1, 'Nigeria', '', ''),
(127, 127, 1, 'Niue', '', ''),
(128, 128, 1, 'Norvège', '', ''),
(129, 129, 1, 'Nouvelle-Zélande', '', ''),
(130, 130, 1, 'Oman', '', ''),
(131, 131, 1, 'Ouganda', '', ''),
(132, 132, 1, 'Ouzbékistan', '', ''),
(133, 133, 1, 'Pakistan', '', ''),
(134, 134, 1, 'Panama', '', ''),
(135, 135, 1, 'Papouasie', '', ''),
(136, 136, 1, 'Paraguay', '', ''),
(137, 137, 1, 'Pays-Bas', '', ''),
(138, 138, 1, 'Pérou', '', ''),
(139, 139, 1, 'Philippines', '', ''),
(140, 140, 1, 'Pologne', '', ''),
(141, 141, 1, 'Portugal', '', ''),
(142, 142, 1, 'Qatar', '', ''),
(143, 143, 1, 'République centrafricaine', '', ''),
(144, 144, 1, 'République dominicaine', '', ''),
(145, 145, 1, 'République tchèque', '', ''),
(146, 146, 1, 'Roumanie', '', ''),
(147, 147, 1, 'Royaume-Uni', '', ''),
(148, 148, 1, 'Russie', '', ''),
(149, 149, 1, 'Rwanda', '', ''),
(150, 150, 1, 'Saint-Christophe-et-Niévès', '', ''),
(151, 151, 1, 'Sainte-Lucie', '', ''),
(152, 152, 1, 'Saint-Marin', '', ''),
(153, 153, 1, 'Saint-Vincent-et-les Grenadines', '', ''),
(154, 154, 1, 'Salomon', '', ''),
(155, 155, 1, 'Salvador', '', ''),
(156, 156, 1, 'Samoa occidentales', '', ''),
(157, 157, 1, 'Sao Tomé-et-Principe', '', ''),
(158, 158, 1, 'Sénégal', '', ''),
(159, 159, 1, 'Seychelles', '', ''),
(160, 160, 1, 'Sierra Leone', '', ''),
(161, 161, 1, 'Singapour', '', ''),
(162, 162, 1, 'Slovaquie', '', ''),
(163, 163, 1, 'Slovénie', '', ''),
(164, 164, 1, 'Somalie', '', ''),
(165, 165, 1, 'Soudan', '', ''),
(166, 166, 1, 'Sri Lanka', '', ''),
(167, 167, 1, 'Suède', '', ''),
(168, 168, 1, 'Suisse', '', ''),
(169, 169, 1, 'Suriname', '', ''),
(170, 170, 1, 'Swaziland', '', ''),
(171, 171, 1, 'Syrie', '', ''),
(172, 172, 1, 'Tadjikistan', '', ''),
(173, 173, 1, 'Tanzanie', '', ''),
(174, 174, 1, 'Tchad', '', ''),
(175, 175, 1, 'Thaïlande', '', ''),
(176, 176, 1, 'Togo', '', ''),
(177, 177, 1, 'Tonga', '', ''),
(178, 178, 1, 'Trinité-et-Tobago', '', ''),
(179, 179, 1, 'Tunisie', '', ''),
(180, 180, 1, 'Turkménistan', '', ''),
(181, 181, 1, 'Turquie', '', ''),
(182, 182, 1, 'Tuvalu', '', ''),
(183, 183, 1, 'Ukraine', '', ''),
(184, 184, 1, 'Uruguay', '', ''),
(185, 185, 1, 'Vatican', '', ''),
(186, 186, 1, 'Vanuatu', '', ''),
(187, 187, 1, 'Venezuela', '', ''),
(188, 188, 1, 'Viêt Nam', '', ''),
(189, 189, 1, 'Yémen', '', ''),
(190, 190, 1, 'Yougoslavie', '', ''),
(191, 191, 1, 'Zaïre', '', ''),
(192, 192, 1, 'Zambie', '', ''),
(193, 193, 1, 'Zimbabwe', '', ''),
(194, 1, 2, 'Afghanistan', '', ''),
(195, 2, 2, 'South Africa', '', ''),
(196, 3, 2, 'Albania', '', ''),
(197, 4, 2, 'Algeria', '', ''),
(198, 5, 2, 'Germany', '', ''),
(199, 6, 2, 'Andorra', '', ''),
(200, 7, 2, 'Angola', '', ''),
(201, 8, 2, 'Antigua and Barbuda', '', ''),
(202, 9, 2, 'Saudi Arabia', '', ''),
(203, 10, 2, 'Argentina', '', ''),
(204, 11, 2, 'Armenia', '', ''),
(205, 12, 2, 'Australia', '', ''),
(206, 13, 2, 'Austria', '', ''),
(207, 14, 2, 'Azerbaijan', '', ''),
(208, 15, 2, 'Bahamas', '', ''),
(209, 16, 2, 'Bahrain', '', ''),
(210, 17, 2, 'Bangladesh', '', ''),
(211, 18, 2, 'Barbados', '', ''),
(212, 19, 2, 'Belarus', '', ''),
(213, 20, 2, 'Belgium', '', ''),
(214, 21, 2, 'Belize', '', ''),
(215, 22, 2, 'Benin', '', ''),
(216, 23, 2, 'Bhutan', '', ''),
(217, 24, 2, 'Bielorussia', '', ''),
(218, 25, 2, 'Burma', '', ''),
(219, 26, 2, 'Bolivia', '', ''),
(220, 27, 2, 'Bosnia and Herzegovina', '', ''),
(221, 28, 2, 'Botswana', '', ''),
(222, 29, 2, 'Brazil', '', ''),
(223, 30, 2, 'Brunei', '', ''),
(224, 31, 2, 'Bulgaria', '', ''),
(225, 32, 2, 'Burkina', '', ''),
(226, 33, 2, 'Burundi', '', ''),
(227, 34, 2, 'Cambodia', '', ''),
(228, 35, 2, 'Cameroon', '', ''),
(230, 37, 2, 'Cape Verde', '', ''),
(231, 38, 2, 'Chile', '', ''),
(232, 39, 2, 'China', '', ''),
(233, 40, 2, 'Cyprus', '', ''),
(234, 41, 2, 'Colombia', '', ''),
(235, 42, 2, 'Comoros', '', ''),
(236, 43, 2, 'Congo', '', ''),
(237, 44, 2, 'Cook Islands', '', ''),
(238, 45, 2, 'North Korea', '', ''),
(239, 46, 2, 'South Korea', '', ''),
(240, 47, 2, 'Costa Rica', '', ''),
(241, 48, 2, 'Ivory Coast', '', ''),
(242, 49, 2, 'Croatia', '', ''),
(243, 50, 2, 'Cuba', '', ''),
(244, 51, 2, 'Denmark', '', ''),
(245, 52, 2, 'Djibouti', '', ''),
(246, 53, 2, 'Dominica', '', ''),
(247, 54, 2, 'Egypt', '', ''),
(248, 55, 2, 'United Arab Emirates', '', ''),
(249, 56, 2, 'Ecuador', '', ''),
(250, 57, 2, 'Eritrea', '', ''),
(251, 58, 2, 'Spain', '', ''),
(252, 59, 2, 'Estonia', '', ''),
(581, 197, 1, 'USA - Arizona', '', ''),
(254, 61, 2, 'Ethiopia', '', ''),
(255, 62, 2, 'Fiji', '', ''),
(256, 63, 2, 'Finland', '', ''),
(257, 64, 2, 'France metropolitan', '', ''),
(258, 65, 2, 'Gabon', '', ''),
(259, 66, 2, 'Gambia', '', ''),
(260, 67, 2, 'Georgia', '', ''),
(261, 68, 2, 'Ghana', '', ''),
(262, 69, 2, 'Greece', '', ''),
(263, 70, 2, 'Grenada', '', ''),
(264, 71, 2, 'Guatemala', '', ''),
(265, 72, 2, 'Guinea', '', ''),
(266, 73, 2, 'Guinea-Bissau', '', ''),
(267, 74, 2, 'Equatorial Guinea', '', ''),
(268, 75, 2, 'Guyana', '', ''),
(269, 76, 2, 'Haiti', '', ''),
(270, 77, 2, 'Honduras', '', ''),
(271, 78, 2, 'Hungary', '', ''),
(272, 79, 2, 'India', '', ''),
(273, 80, 2, 'Indonesia', '', ''),
(274, 81, 2, 'Iran', '', ''),
(275, 82, 2, 'Iraq', '', ''),
(276, 83, 2, 'Ireland', '', ''),
(277, 84, 2, 'Iceland', '', ''),
(278, 85, 2, 'Israel', '', ''),
(279, 86, 2, 'Italy', '', ''),
(280, 87, 2, 'Jamaica', '', ''),
(281, 88, 2, 'Japan', '', ''),
(282, 89, 2, 'Jordan', '', ''),
(283, 90, 2, 'Kazakhstan', '', ''),
(284, 91, 2, 'Kenya', '', ''),
(285, 92, 2, 'Kyrgyzstan', '', ''),
(286, 93, 2, 'Kiribati', '', ''),
(287, 94, 2, 'Kuwait', '', ''),
(288, 95, 2, 'Laos', '', ''),
(289, 96, 2, 'Lesotho', '', ''),
(290, 97, 2, 'Latvia', '', ''),
(291, 98, 2, 'Lebanon', '', ''),
(292, 99, 2, 'Liberia', '', ''),
(293, 100, 2, 'Libya', '', ''),
(294, 101, 2, 'Liechtenstein', '', ''),
(295, 102, 2, 'Lithuania', '', ''),
(296, 103, 2, 'Luxembourg', '', ''),
(297, 104, 2, 'Macedonia', '', ''),
(298, 105, 2, 'Madagascar', '', ''),
(299, 106, 2, 'Malaysia', '', ''),
(300, 107, 2, 'Malawi', '', ''),
(301, 108, 2, 'Maldives', '', ''),
(302, 109, 2, 'Mali', '', ''),
(303, 110, 2, 'Malta', '', ''),
(304, 111, 2, 'Morocco', '', ''),
(305, 112, 2, 'Marshall Islands', '', ''),
(306, 113, 2, 'Mauritius', '', ''),
(307, 114, 2, 'Mauritania', '', ''),
(308, 115, 2, 'Mexico', '', ''),
(309, 116, 2, 'Micronesia', '', ''),
(310, 117, 2, 'Moldova', '', ''),
(311, 118, 2, 'Monaco', '', ''),
(312, 119, 2, 'Mongolia', '', ''),
(313, 120, 2, 'Mozambique', '', ''),
(314, 121, 2, 'Namibia', '', ''),
(315, 122, 2, 'Nauru', '', ''),
(316, 123, 2, 'Nepal', '', ''),
(317, 124, 2, 'Nicaragua', '', ''),
(318, 125, 2, 'Niger', '', ''),
(319, 126, 2, 'Nigeria', '', ''),
(320, 127, 2, 'Niue', '', ''),
(321, 128, 2, 'Norway', '', ''),
(322, 129, 2, 'New Zealand', '', ''),
(323, 130, 2, 'Oman', '', ''),
(324, 131, 2, 'Uganda', '', ''),
(325, 132, 2, 'Uzbekistan', '', ''),
(326, 133, 2, 'Pakistan', '', ''),
(327, 134, 2, 'Panama', '', ''),
(328, 135, 2, 'Papua Nueva Guinea', '', ''),
(329, 136, 2, 'Paraguay', '', ''),
(330, 137, 2, 'Netherlands', '', ''),
(331, 138, 2, 'Peru', '', ''),
(332, 139, 2, 'Philippines', '', ''),
(333, 140, 2, 'Poland', '', ''),
(334, 141, 2, 'Portugal', '', ''),
(335, 142, 2, 'Qatar', '', ''),
(336, 143, 2, 'Central African Republic', '', ''),
(337, 144, 2, 'Dominican Republic', '', ''),
(338, 145, 2, 'Czech Republic', '', ''),
(339, 146, 2, 'Romania', '', ''),
(340, 147, 2, 'United Kingdom', '', ''),
(341, 148, 2, 'Russia', '', ''),
(342, 149, 2, 'Rwanda', '', ''),
(343, 150, 2, 'Saint Kitts and Nevis', '', ''),
(344, 151, 2, 'Saint Lucia', '', ''),
(345, 152, 2, 'San Marino', '', ''),
(346, 153, 2, 'Saint Vincent and the Grenadines', '', ''),
(347, 154, 2, 'Solomon Islands', '', ''),
(348, 155, 2, 'El Salvador', '', ''),
(349, 156, 2, 'Western Samoa', '', ''),
(350, 157, 2, 'Sao Tome and Principe', '', ''),
(351, 158, 2, 'Senegal', '', ''),
(352, 159, 2, 'Seychelles', '', ''),
(353, 160, 2, 'Sierra Leone', '', ''),
(354, 161, 2, 'Singapore', '', ''),
(355, 162, 2, 'Slovakia', '', ''),
(356, 163, 2, 'Slovenia', '', ''),
(357, 164, 2, 'Somalia', '', ''),
(358, 165, 2, 'Sudan', '', ''),
(359, 166, 2, 'Sri Lanka', '', ''),
(360, 167, 2, 'Sweden', '', ''),
(361, 168, 2, 'Switzerland', '', ''),
(362, 169, 2, 'Suriname', '', ''),
(363, 170, 2, 'Swaziland', '', ''),
(364, 171, 2, 'Syria', '', ''),
(365, 172, 2, 'Tajikistan', '', ''),
(366, 173, 2, 'Tanzania', '', ''),
(367, 174, 2, 'Chad', '', ''),
(368, 175, 2, 'Thailand', '', ''),
(369, 176, 2, 'Togo', '', ''),
(370, 177, 2, 'Tonga', '', ''),
(371, 178, 2, 'Trinidad and Tobago', '', ''),
(372, 179, 2, 'Tunisia', '', ''),
(373, 180, 2, 'Turkmenistan', '', ''),
(374, 181, 2, 'Turkey', '', ''),
(375, 182, 2, 'Tuvalu', '', ''),
(376, 183, 2, 'Ukraine', '', ''),
(377, 184, 2, 'Uruguay', '', ''),
(378, 185, 2, 'The Vatican', '', ''),
(379, 186, 2, 'Vanuatu', '', ''),
(380, 187, 2, 'Venezuela', '', ''),
(381, 188, 2, 'Vietnam', '', ''),
(382, 189, 2, 'Yemen', '', ''),
(383, 190, 2, 'Yougoslavia', '', ''),
(384, 191, 2, 'Zaire', '', ''),
(385, 192, 2, 'Zambia', '', ''),
(386, 193, 2, 'Zimbabwe', '', ''),
(387, 1, 3, 'Afganistán', '', ''),
(388, 2, 3, 'Sudáfrica', '', ''),
(389, 3, 3, 'Albania', '', ''),
(390, 4, 3, 'Argelia', '', ''),
(391, 5, 3, 'Alemania', '', ''),
(392, 6, 3, 'Andorra', '', ''),
(393, 7, 3, 'Angola', '', ''),
(394, 8, 3, 'Antigua y Barbuda', '', ''),
(395, 9, 3, 'Arabia Saudita', '', ''),
(396, 10, 3, 'Argentina', '', ''),
(397, 11, 3, 'Armenia', '', ''),
(398, 12, 3, 'Australia', '', ''),
(399, 13, 3, 'Austria', '', ''),
(400, 14, 3, 'Azerbaiyán', '', ''),
(401, 15, 3, 'Bahamas', '', ''),
(402, 16, 3, 'Bahrein', '', ''),
(403, 17, 3, 'Bangladesh', '', ''),
(404, 18, 3, 'Barbados', '', ''),
(405, 19, 3, 'Belarús', '', ''),
(406, 20, 3, 'Bélgica', '', ''),
(407, 21, 3, 'Belice', '', ''),
(408, 22, 3, 'Benin', '', ''),
(409, 23, 3, 'Bhután', '', ''),
(410, 24, 3, 'Bielorusia', '', ''),
(411, 25, 3, 'Birmania', '', ''),
(412, 26, 3, 'Bolivia', '', ''),
(413, 27, 3, 'Bosnia y Herzegovina', '', ''),
(414, 28, 3, 'Botswana', '', ''),
(415, 29, 3, 'Brasil', '', ''),
(416, 30, 3, 'Brunei', '', ''),
(417, 31, 3, 'Bulgaria', '', ''),
(418, 32, 3, 'Burkina', '', ''),
(419, 33, 3, 'Burundi', '', ''),
(420, 34, 3, 'Camboya', '', ''),
(421, 35, 3, 'Camerún', '', ''),
(730, 246, 1, 'Colombie-Britannique', '', ''),
(423, 37, 3, 'Cabo Verde', '', ''),
(424, 38, 3, 'Chile', '', ''),
(425, 39, 3, 'China', '', ''),
(426, 40, 3, 'Chipre', '', ''),
(427, 41, 3, 'Colombia', '', ''),
(428, 42, 3, 'Comoras', '', ''),
(429, 43, 3, 'Congo', '', ''),
(430, 44, 3, 'Cook', '', ''),
(431, 45, 3, 'Corea del Norte', '', ''),
(432, 46, 3, 'Corea del Sur', '', ''),
(433, 47, 3, 'Costa Rica', '', ''),
(434, 48, 3, 'Costa de Marfil', '', ''),
(435, 49, 3, 'Croacia', '', ''),
(436, 50, 3, 'Cuba', '', ''),
(437, 51, 3, 'Dinamarca', '', ''),
(438, 52, 3, 'Djibouti', '', ''),
(439, 53, 3, 'Dominica', '', ''),
(440, 54, 3, 'Egipto', '', ''),
(441, 55, 3, 'Emiratos Árabes Unidos', '', ''),
(442, 56, 3, 'Ecuador', '', ''),
(443, 57, 3, 'Eritrea', '', ''),
(444, 58, 3, 'España', '', ''),
(445, 59, 3, 'Estonia', '', ''),
(580, 196, 1, 'USA - Alaska', '', ''),
(447, 61, 3, 'Etiopía', '', ''),
(448, 62, 3, 'Fiji', '', ''),
(449, 63, 3, 'Finlandia', '', ''),
(450, 64, 3, 'Francia', '', ''),
(451, 65, 3, 'Gabón', '', ''),
(452, 66, 3, 'Gambia', '', ''),
(453, 67, 3, 'Georgia', '', ''),
(454, 68, 3, 'Ghana', '', ''),
(455, 69, 3, 'Grecia', '', ''),
(456, 70, 3, 'Granada', '', ''),
(457, 71, 3, 'Guatemala', '', ''),
(458, 72, 3, 'Guinea', '', ''),
(459, 73, 3, 'Guinea-Bissau', '', ''),
(460, 74, 3, 'Guinea Ecuatorial', '', ''),
(461, 75, 3, 'Guyana', '', ''),
(462, 76, 3, 'Haití', '', ''),
(463, 77, 3, 'Honduras', '', ''),
(464, 78, 3, 'Hungría', '', ''),
(465, 79, 3, 'India', '', ''),
(466, 80, 3, 'Indonesia', '', ''),
(467, 81, 3, 'Irán', '', ''),
(468, 82, 3, 'Iraq', '', ''),
(469, 83, 3, 'Irlanda', '', ''),
(470, 84, 3, 'Islandia', '', ''),
(471, 85, 3, 'Israel', '', ''),
(472, 86, 3, 'Italia', '', ''),
(473, 87, 3, 'Jamaica', '', ''),
(474, 88, 3, 'Japón', '', ''),
(475, 89, 3, 'Jordania', '', ''),
(476, 90, 3, 'Kazajstán', '', ''),
(477, 91, 3, 'Kenia', '', ''),
(478, 92, 3, 'Kirguistán', '', ''),
(479, 93, 3, 'Kiribati', '', ''),
(480, 94, 3, 'Kuwait', '', ''),
(481, 95, 3, 'Laos', '', ''),
(482, 96, 3, 'Lesotho', '', ''),
(483, 97, 3, 'Letonia', '', ''),
(484, 98, 3, 'Líbano', '', ''),
(485, 99, 3, 'Liberia', '', ''),
(486, 100, 3, 'Libia', '', ''),
(487, 101, 3, 'Liechtenstein', '', ''),
(488, 102, 3, 'Lituania', '', ''),
(489, 103, 3, 'Luxemburgo', '', ''),
(490, 104, 3, 'Macedonia', '', ''),
(491, 105, 3, 'Madagascar', '', ''),
(492, 106, 3, 'Malasia', '', ''),
(493, 107, 3, 'Malawi', '', ''),
(494, 108, 3, 'Maldivas', '', ''),
(495, 109, 3, 'Malí', '', ''),
(496, 110, 3, 'Malta', '', ''),
(497, 111, 3, 'Marruecos', '', ''),
(498, 112, 3, 'Marshall', '', ''),
(499, 113, 3, 'Mauricio', '', ''),
(500, 114, 3, 'Mauritania', '', ''),
(501, 115, 3, 'Méjico', '', ''),
(502, 116, 3, 'Micronesia', '', ''),
(503, 117, 3, 'Moldova', '', ''),
(504, 118, 3, 'Mónaco', '', ''),
(505, 119, 3, 'Mongolia', '', ''),
(506, 120, 3, 'Mozambique', '', ''),
(507, 121, 3, 'Namibia', '', ''),
(508, 122, 3, 'Nauru', '', ''),
(509, 123, 3, 'Nepal', '', ''),
(510, 124, 3, 'Nicaragua', '', ''),
(511, 125, 3, 'Níger', '', ''),
(512, 126, 3, 'Nigeria', '', ''),
(513, 127, 3, 'Niue', '', ''),
(514, 128, 3, 'Noruega', '', ''),
(515, 129, 3, 'Nueva Zelandia', '', ''),
(516, 130, 3, 'Omán', '', ''),
(517, 131, 3, 'Uganda', '', ''),
(518, 132, 3, 'Uzbekistán', '', ''),
(519, 133, 3, 'Pakistán', '', ''),
(520, 134, 3, 'Panamá', '', ''),
(521, 135, 3, 'Papua Nueva Guinea', '', ''),
(522, 136, 3, 'Paraguay', '', ''),
(523, 137, 3, 'Países Bajos', '', ''),
(524, 138, 3, 'Perú', '', ''),
(525, 139, 3, 'Filipinas', '', ''),
(526, 140, 3, 'Polonia', '', ''),
(527, 141, 3, 'Portugal', '', ''),
(528, 142, 3, 'Qatar', '', ''),
(529, 143, 3, 'República Centroafricana', '', ''),
(530, 144, 3, 'República Dominicana', '', ''),
(531, 145, 3, 'República Checa', '', ''),
(532, 146, 3, 'Rumania', '', ''),
(533, 147, 3, 'Reino Unido', '', ''),
(534, 148, 3, 'Rusia', '', ''),
(535, 149, 3, 'Ruanda', '', ''),
(536, 150, 3, 'San Cristóbal', '', ''),
(537, 151, 3, 'Santa Lucía', '', ''),
(538, 152, 3, 'San Marino', '', ''),
(539, 153, 3, 'San Vicente y las Granadinas', '', ''),
(540, 154, 3, 'Salomón', '', ''),
(541, 155, 3, 'El Salvador', '', ''),
(542, 156, 3, 'Samoa', '', ''),
(543, 157, 3, 'Santo Tomé y Príncipe', '', ''),
(544, 158, 3, 'Senegal', '', ''),
(545, 159, 3, 'Seychelles', '', ''),
(546, 160, 3, 'Sierra Leona', '', ''),
(547, 161, 3, 'Singapur', '', ''),
(548, 162, 3, 'Eslovaquia', '', ''),
(549, 163, 3, 'Eslovenia', '', ''),
(550, 164, 3, 'Somalia', '', ''),
(551, 165, 3, 'Sudán', '', ''),
(552, 166, 3, 'Sri Lanka', '', ''),
(553, 167, 3, 'Suecia', '', ''),
(554, 168, 3, 'Suiza', '', ''),
(555, 169, 3, 'Suriname', '', ''),
(556, 170, 3, 'Swazilandia', '', ''),
(557, 171, 3, 'Siria', '', ''),
(558, 172, 3, 'Tayikistán', '', ''),
(559, 173, 3, 'Tanzanía', '', ''),
(560, 174, 3, 'Chad', '', ''),
(561, 175, 3, 'Tailandia', '', ''),
(562, 176, 3, 'Togo', '', ''),
(563, 177, 3, 'Tonga', '', ''),
(564, 178, 3, 'Trinidad y Tabago', '', ''),
(565, 179, 3, 'Túnez', '', ''),
(566, 180, 3, 'Turkmenistán', '', ''),
(567, 181, 3, 'Turquía', '', ''),
(568, 182, 3, 'Tuvalu', '', ''),
(569, 183, 3, 'Ucrania', '', ''),
(570, 184, 3, 'Uruguay', '', ''),
(571, 185, 3, 'El Vatican', '', ''),
(572, 186, 3, 'Vanuatu', '', ''),
(573, 187, 3, 'Venezuela', '', ''),
(574, 188, 3, 'Viet Nam', '', ''),
(575, 189, 3, 'Yemen', '', ''),
(576, 190, 3, 'Yugoslavia', '', ''),
(577, 191, 3, 'Zaire', '', ''),
(578, 192, 3, 'Zambia', '', ''),
(579, 193, 3, 'Zimbabwe', '', ''),
(583, 199, 1, 'USA - California', '', ''),
(584, 200, 1, 'USA - Colorado', '', ''),
(585, 201, 1, 'USA - Connecticut', '', ''),
(586, 202, 1, 'USA - Delaware', '', ''),
(587, 203, 1, 'USA - District Of Columbia', '', ''),
(588, 204, 1, 'USA - Florida', '', ''),
(589, 205, 1, 'USA - Georgia', '', ''),
(590, 206, 1, 'USA - Hawaii', '', ''),
(591, 207, 1, 'USA - Idaho', '', ''),
(592, 208, 1, 'USA - Illinois', '', ''),
(593, 209, 1, 'USA - Indiana', '', ''),
(594, 210, 1, 'USA - Iowa', '', ''),
(595, 211, 1, 'USA - Kansas', '', ''),
(596, 212, 1, 'USA - Kentucky', '', ''),
(597, 213, 1, 'USA - Louisiana', '', ''),
(598, 214, 1, 'USA - Maine', '', ''),
(599, 215, 1, 'USA - Maryland', '', ''),
(600, 216, 1, 'USA - Massachusetts', '', ''),
(601, 217, 1, 'USA - Michigan', '', ''),
(602, 218, 1, 'USA - Minnesota', '', ''),
(603, 219, 1, 'USA - Mississippi', '', ''),
(604, 220, 1, 'USA - Missouri', '', ''),
(605, 221, 1, 'USA - Montana', '', ''),
(606, 222, 1, 'USA - Nebraska', '', ''),
(607, 223, 1, 'USA - Nevada', '', ''),
(608, 224, 1, 'USA - New Hampshire', '', ''),
(609, 225, 1, 'USA - New Jersey', '', ''),
(610, 226, 1, 'USA - New Mexico', '', ''),
(611, 227, 1, 'USA - New York', '', ''),
(612, 228, 1, 'USA - North Carolina', '', ''),
(613, 229, 1, 'USA - North Dakota', '', ''),
(614, 230, 1, 'USA - Ohio', '', ''),
(615, 231, 1, 'USA - Oklahoma', '', ''),
(616, 232, 1, 'USA - Oregon', '', ''),
(617, 233, 1, 'USA - Pennsylvania', '', ''),
(618, 234, 1, 'USA - Rhode Island', '', ''),
(619, 235, 1, 'USA - South Carolina', '', ''),
(620, 236, 1, 'USA - South Dakota', '', ''),
(621, 237, 1, 'USA - Tennessee', '', ''),
(622, 238, 1, 'USA - Texas', '', ''),
(623, 239, 1, 'USA - Utah', '', ''),
(624, 240, 1, 'USA - Vermont', '', ''),
(625, 241, 1, 'USA - Virginia', '', ''),
(626, 242, 1, 'USA - Washington', '', ''),
(627, 243, 1, 'USA - West Virginia', '', ''),
(628, 244, 1, 'USA - Wisconsin', '', ''),
(629, 245, 1, 'USA - Wyoming', '', ''),
(630, 196, 2, 'USA - Alaska', '', ''),
(631, 197, 2, 'USA - Arizona', '', ''),
(632, 198, 2, 'USA - Arkansas', '', ''),
(633, 199, 2, 'USA - California', '', ''),
(634, 200, 2, 'USA - Colorado', '', ''),
(635, 201, 2, 'USA - Connecticut', '', ''),
(636, 202, 2, 'USA - Delaware', '', ''),
(637, 203, 2, 'USA - District Of Columbia', '', ''),
(638, 204, 2, 'USA - Florida', '', ''),
(639, 205, 2, 'USA - Georgia', '', ''),
(640, 206, 2, 'USA - Hawaii', '', ''),
(641, 207, 2, 'USA - Idaho', '', ''),
(642, 208, 2, 'USA - Illinois', '', ''),
(643, 209, 2, 'USA - Indiana', '', ''),
(644, 210, 2, 'USA - Iowa', '', ''),
(645, 211, 2, 'USA - Kansas', '', ''),
(646, 212, 2, 'USA - Kentucky', '', ''),
(647, 213, 2, 'USA - Louisiana', '', ''),
(648, 214, 2, 'USA - Maine', '', ''),
(649, 215, 2, 'USA - Maryland', '', ''),
(650, 216, 2, 'USA - Massachusetts', '', ''),
(651, 217, 2, 'USA - Michigan', '', ''),
(652, 218, 2, 'USA - Minnesota', '', ''),
(653, 219, 2, 'USA - Mississippi', '', ''),
(654, 220, 2, 'USA - Missouri', '', ''),
(655, 221, 2, 'USA - Montana', '', ''),
(656, 222, 2, 'USA - Nebraska', '', ''),
(657, 223, 2, 'USA - Nevada', '', ''),
(658, 224, 2, 'USA - New Hampshire', '', ''),
(659, 225, 2, 'USA - New Jersey', '', ''),
(660, 226, 2, 'USA - New Mexico', '', ''),
(661, 227, 2, 'USA - New York', '', ''),
(662, 228, 2, 'USA - North Carolina', '', ''),
(663, 229, 2, 'USA - North Dakota', '', ''),
(664, 230, 2, 'USA - Ohio', '', ''),
(665, 231, 2, 'USA - Oklahoma', '', ''),
(666, 232, 2, 'USA - Oregon', '', ''),
(667, 233, 2, 'USA - Pennsylvania', '', ''),
(668, 234, 2, 'USA - Rhode Island', '', ''),
(669, 235, 2, 'USA - South Carolina', '', ''),
(670, 236, 2, 'USA - South Dakota', '', ''),
(671, 237, 2, 'USA - Tennessee', '', ''),
(672, 238, 2, 'USA - Texas', '', ''),
(673, 239, 2, 'USA - Utah', '', ''),
(674, 240, 2, 'USA - Vermont', '', ''),
(675, 241, 2, 'USA - Virginia', '', ''),
(676, 242, 2, 'USA - Washington', '', ''),
(677, 243, 2, 'USA - West Virginia', '', ''),
(678, 244, 2, 'USA - Wisconsin', '', ''),
(679, 245, 2, 'USA - Wyoming', '', ''),
(680, 196, 3, 'USA - Alaska', '', ''),
(681, 197, 3, 'USA - Arizona', '', ''),
(682, 198, 3, 'USA - Arkansas', '', ''),
(683, 199, 3, 'USA - California', '', ''),
(684, 200, 3, 'USA - Colorado', '', ''),
(685, 201, 3, 'USA - Connecticut', '', ''),
(686, 202, 3, 'USA - Delaware', '', ''),
(687, 203, 3, 'USA - District Of Columbia', '', ''),
(688, 204, 3, 'USA - Florida', '', ''),
(689, 205, 3, 'USA - Georgia', '', ''),
(690, 206, 3, 'USA - Hawaii', '', ''),
(691, 207, 3, 'USA - Idaho', '', ''),
(692, 208, 3, 'USA - Illinois', '', ''),
(693, 209, 3, 'USA - Indiana', '', ''),
(694, 210, 3, 'USA - Iowa', '', ''),
(695, 211, 3, 'USA - Kansas', '', ''),
(696, 212, 3, 'USA - Kentucky', '', ''),
(697, 213, 3, 'USA - Louisiana', '', ''),
(698, 214, 3, 'USA - Maine', '', ''),
(699, 215, 3, 'USA - Maryland', '', ''),
(700, 216, 3, 'USA - Massachusetts', '', ''),
(701, 217, 3, 'USA - Michigan', '', ''),
(702, 218, 3, 'USA - Minnesota', '', ''),
(703, 219, 3, 'USA - Mississippi', '', ''),
(704, 220, 3, 'USA - Missouri', '', ''),
(705, 221, 3, 'USA - Montana', '', ''),
(706, 222, 3, 'USA - Nebraska', '', ''),
(707, 223, 3, 'USA - Nevada', '', ''),
(708, 224, 3, 'USA - New Hampshire', '', ''),
(709, 225, 3, 'USA - New Jersey', '', ''),
(710, 226, 3, 'USA - New Mexico', '', ''),
(711, 227, 3, 'USA - New York', '', ''),
(712, 228, 3, 'USA - North Carolina', '', ''),
(713, 229, 3, 'USA - North Dakota', '', ''),
(714, 230, 3, 'USA - Ohio', '', ''),
(715, 231, 3, 'USA - Oklahoma', '', ''),
(716, 232, 3, 'USA - Oregon', '', ''),
(717, 233, 3, 'USA - Pennsylvania', '', ''),
(718, 234, 3, 'USA - Rhode Island', '', ''),
(719, 235, 3, 'USA - South Carolina', '', ''),
(720, 236, 3, 'USA - South Dakota', '', ''),
(721, 237, 3, 'USA - Tennessee', '', ''),
(722, 238, 3, 'USA - Texas', '', ''),
(723, 239, 3, 'USA - Utah', '', ''),
(724, 240, 3, 'USA - Vermont', '', ''),
(725, 241, 3, 'USA - Virginia', '', ''),
(726, 242, 3, 'USA - Washington', '', ''),
(727, 243, 3, 'USA - West Virginia', '', ''),
(728, 244, 3, 'USA - Wisconsin', '', ''),
(729, 245, 3, 'USA - Wyoming', '', ''),
(783, 247, 2, 'Canada - Alberta', '', ''),
(782, 246, 2, 'Canada - Colombie-Britannique', '', ''),
(781, 258, 1, 'Canada - Nunavut', '', ''),
(780, 257, 1, 'Canada - Territoires-du-Nord-Ouest', '', ''),
(779, 256, 1, 'Canada - Yukon', '', ''),
(778, 255, 1, 'Canada - Terre-Neuve-et-Labrador    ', '', ''),
(777, 254, 1, 'Canada - Île-du-Prince-Édouard    ', '', ''),
(776, 253, 1, 'Canada - Nouvelle-Écosse', '', ''),
(775, 252, 1, 'Canada - Nouveau-Brunswick', '', ''),
(774, 251, 1, 'Canada - Québec', '', ''),
(773, 250, 1, 'Canada - Ontario', '', ''),
(772, 249, 1, 'Canada - Manitoba', '', ''),
(771, 248, 1, 'Canada - Saskatchewan', '', ''),
(770, 247, 1, 'Canada - Alberta', '', ''),
(769, 246, 1, 'Canada - Colombie-Britannique', '', ''),
(790, 254, 2, 'Canada - Île-du-Prince-Édouard    ', '', ''),
(789, 253, 2, 'Canada - Nouvelle-Écosse', '', ''),
(788, 252, 2, 'Canada - Nouveau-Brunswick', '', ''),
(787, 251, 2, 'Canada - Québec', '', ''),
(786, 250, 2, 'Canada - Ontario', '', ''),
(785, 249, 2, 'Canada - Manitoba', '', ''),
(784, 248, 2, 'Canada - Saskatchewan', '', ''),
(791, 255, 2, 'Canada - Terre-Neuve-et-Labrador    ', '', ''),
(792, 256, 2, 'Canada - Yukon', '', ''),
(793, 257, 2, 'Canada - Territoires-du-Nord-Ouest', '', ''),
(794, 258, 2, 'Canada - Nunavut', '', ''),
(795, 246, 3, 'Canada - Colombie-Britannique', '', ''),
(796, 247, 3, 'Canada - Alberta', '', ''),
(797, 248, 3, 'Canada - Saskatchewan', '', ''),
(798, 249, 3, 'Canada - Manitoba', '', ''),
(799, 250, 3, 'Canada - Ontario', '', ''),
(800, 251, 3, 'Canada - Québec', '', ''),
(801, 252, 3, 'Canada - Nouveau-Brunswick', '', ''),
(802, 253, 3, 'Canada - Nouvelle-Écosse', '', ''),
(803, 254, 3, 'Canada - Île-du-Prince-Édouard    ', '', ''),
(804, 255, 3, 'Canada - Terre-Neuve-et-Labrador    ', '', ''),
(805, 256, 3, 'Canada - Yukon', '', ''),
(806, 257, 3, 'Canada - Territoires-du-Nord-Ouest', '', ''),
(807, 258, 3, 'Canada - Nunavut', '', ''),
(808, 259, 1, 'Guadeloupe', '', ''),
(809, 260, 1, 'Guyane Française', '', ''),
(810, 261, 1, 'Martinique', '', ''),
(811, 262, 1, 'Mayotte', '', ''),
(812, 263, 1, 'Réunion(La)', '', ''),
(813, 264, 1, 'St Pierre et Miquelon', '', ''),
(814, 265, 1, 'Nouvelle-Calédonie', '', ''),
(815, 259, 2, 'Guadeloupe', '', ''),
(816, 260, 2, 'Guyane Française', '', ''),
(817, 261, 2, 'Martinique', '', ''),
(818, 262, 2, 'Mayotte', '', ''),
(819, 263, 2, 'Réunion(La)', '', ''),
(820, 264, 2, 'St Pierre et Miquelon', '', ''),
(821, 265, 2, 'Nouvelle-Calédonie', '', ''),
(822, 259, 3, 'Guadeloupe', '', ''),
(823, 260, 3, 'Guyane Française', '', ''),
(824, 261, 3, 'Martinique', '', ''),
(825, 262, 3, 'Mayotte', '', ''),
(826, 263, 3, 'Réunion(La)', '', ''),
(827, 264, 3, 'St Pierre et Miquelon', '', ''),
(828, 265, 3, 'Nouvelle-Calédonie', '', ''),
(829, 266, 1, 'Polynésie française', '', ''),
(830, 266, 2, 'Polynésie française', '', ''),
(831, 266, 3, 'Polynésie française', '', ''),
(832, 267, 1, 'Wallis-et-Futuna', '', ''),
(833, 267, 2, 'Wallis-et-Futuna', '', ''),
(834, 267, 3, 'Wallis-et-Futuna', '', ''),
(835, 268, 1, 'USA - Alabama', '', ''),
(836, 268, 2, 'USA - Alabama', '', ''),
(837, 268, 3, 'USA - Alabama', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `ref` text NOT NULL,
  `datemodif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `prix` float NOT NULL DEFAULT '0',
  `ecotaxe` float NOT NULL,
  `promo` smallint(6) NOT NULL DEFAULT '0',
  `prix2` float NOT NULL DEFAULT '0',
  `rubrique` int(11) NOT NULL DEFAULT '0',
  `nouveaute` smallint(6) NOT NULL DEFAULT '0',
  `perso` int(11) NOT NULL DEFAULT '0',
  `stock` int(11) NOT NULL DEFAULT '0',
  `ligne` smallint(6) NOT NULL DEFAULT '0',
  `garantie` int(11) NOT NULL DEFAULT '0',
  `poids` float NOT NULL DEFAULT '0',
  `tva` float NOT NULL DEFAULT '0',
  `classement` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `ref`, `datemodif`, `prix`, `ecotaxe`, `promo`, `prix2`, `rubrique`, `nouveaute`, `perso`, `stock`, `ligne`, `garantie`, `poids`, `tva`, `classement`) VALUES
(29, 'BJX-BO-0029', '2017-04-30 21:01:15', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 2),
(28, 'BJX-BO-0028', '2017-05-13 19:14:02', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 32),
(27, 'BJX-TOR-0027', '2017-04-30 21:21:21', 80, 0, 0, 0, 19, 0, 0, 0, 0, 0, 0, 19.6, 5),
(26, 'BJX-TOR-0026', '2015-03-27 11:44:29', 90, 0, 0, 0, 19, 0, 0, -2, 1, 0, 0, 19.6, 4),
(25, 'BJX-COL-0025', '2015-03-27 11:54:32', 70, 0, 0, 0, 4, 0, 0, 1, 1, 0, 0, 19.6, 2),
(24, 'BJX-COL-0024', '2015-03-27 11:54:18', 65, 0, 0, 65, 4, 0, 0, 1, 1, 0, 0, 19.6, 1),
(23, 'BJX-BO-0023', '2017-04-30 21:02:22', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 8),
(21, 'BJX-BO-0021', '2018-01-21 10:06:15', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 17),
(36, 'BJX-BO-0036', '2018-01-21 10:06:05', 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 0, 19.6, 14),
(20, 'BJX-BO-0020', '2018-01-21 10:05:47', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 7),
(19, 'BJX-BO-0019', '2018-01-21 10:05:28', 0, 0, 0, 0, 3, 0, 0, -3, 0, 0, 0, 19.6, 4),
(18, 'BJX-BO-0018', '2017-05-13 19:13:17', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 3),
(30, 'BJX-BO-0030', '2017-05-13 19:13:31', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 10),
(31, 'BJX-BO-0031', '2018-01-21 10:05:59', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 11),
(32, 'BJX-BO-0032', '2017-05-13 19:14:14', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 44),
(33, 'BJX-BO-0033', '2017-05-13 19:13:34', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 12),
(35, 'BJX-BO-0035', '2017-04-30 21:02:29', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 13),
(163, 'BJX-BO-0163', '2017-04-30 21:04:38', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 82),
(39, 'BJX-BO-0039', '2017-04-30 21:02:32', 0, 0, 0, 0, 3, 0, 0, -1, 0, 0, 0, 19.6, 15),
(41, 'BJX-BO-0041', '2018-01-21 10:05:53', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 9),
(42, 'BJX-BO-0042', '2017-04-30 21:02:36', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 18),
(159, 'BJX-BO-0159', '2017-04-30 21:04:36', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 81),
(157, 'BJX-BO-0157', '2017-04-30 21:04:33', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 79),
(158, 'BJX-BO-0158', '2018-01-21 10:08:37', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 80),
(49, 'BJX-BO-0049', '2017-05-13 19:13:44', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 19),
(50, 'BJX-BO-0050', '2017-04-30 21:02:41', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 21),
(51, 'BJX-BO-0051', '2018-01-21 10:06:22', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 22),
(52, 'BJX-BO-0052', '2017-05-13 19:14:09', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 39),
(53, 'BJX-BO-0053', '2018-01-21 10:05:32', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 5),
(54, 'BJX-BO-0054', '2018-01-21 10:06:25', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 23),
(55, 'BJX-BO-0055', '2016-12-07 16:37:01', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 45),
(56, 'BJX-BO-0056', '2017-04-30 21:02:46', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 24),
(58, 'BJX-BO-0058', '2017-04-30 21:03:53', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 61),
(59, 'BJX-BO-0059', '2017-04-30 21:03:03', 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 0, 19.6, 31),
(60, 'BJX-BO-0060', '2017-04-30 21:02:51', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 27),
(61, 'BJX-BO-0061', '2017-04-30 21:03:57', 0, 0, 0, 0, 3, 0, 0, -1, 0, 0, 0, 19.6, 64),
(63, 'BJX-BO-0063', '2017-04-30 21:04:19', 0, 0, 0, 0, 3, 0, 0, -4, 0, 0, 0, 19.6, 74),
(64, 'BJX-BO-0064', '2017-04-30 21:02:57', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 29),
(65, 'BJX-BO-0065', '2017-04-30 21:03:06', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 33),
(66, 'BJX-BO-0066', '2017-04-30 21:03:11', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 35),
(164, 'BJX-BO-0164', '2017-04-30 21:04:39', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 83),
(69, 'BJX-BO-0069', '2017-05-13 19:14:05', 0, 0, 0, 0, 3, 0, 0, 0, 1, 0, 0, 19.6, 36),
(70, 'BJX-BO-0070', '2017-04-30 21:03:14', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 37),
(71, 'BJX-BO-0071', '2018-01-21 10:07:49', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 59),
(72, 'BJX-BO-0072', '2017-04-30 21:03:15', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 38),
(73, 'BJX-BO-0073', '2017-04-30 21:03:20', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 40),
(74, 'BJX-BO-0074', '2018-01-21 10:07:17', 0, 0, 0, 0, 3, 0, 0, -17, 0, 0, 0, 19.6, 42),
(75, 'BJX-BO-0075', '2018-01-21 10:07:10', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 43),
(77, 'BJX-BO-0077', '2017-05-13 19:14:16', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 46),
(78, 'BJX-BO-0078', '2017-05-13 19:13:56', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 26),
(79, 'BJX-BO-0079', '2017-05-13 19:14:18', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 47),
(80, 'BJX-BO-0080', '2017-04-30 21:01:44', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 98),
(81, 'BJX-BO-0081', '2017-04-30 21:03:32', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 48),
(82, 'BJX-BO-0082', '2017-05-13 19:14:20', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 49),
(84, 'BJX-BO-0084', '2017-05-13 19:14:21', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 50),
(85, 'BJX-BO-0085', '2018-01-21 10:09:31', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 52),
(86, 'BJX-BO-0086', '2015-03-10 15:10:29', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 87),
(87, 'BJX-BO-0087', '2017-05-13 19:14:25', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 53),
(88, 'BJX-BO-0088', '2018-01-21 10:07:39', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 55),
(89, 'BJX-BO-0089', '2018-01-21 10:07:45', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 58),
(165, 'BJX-BO-0165', '2017-05-13 19:14:59', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 84),
(93, 'BJX-BO-0093', '2017-04-30 21:03:52', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 60),
(94, 'BJX-BO-0094', '2015-03-10 15:16:17', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 62),
(95, 'BJX-BO-0095', '2017-05-13 19:15:26', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 103),
(96, 'BJX-BO-0096', '2015-03-10 15:17:39', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 28),
(97, 'BJX-BO-0097', '2018-01-21 10:06:41', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 30),
(98, 'BJX-BO-0098', '2017-05-13 19:15:28', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 106),
(147, 'BJX-BO-0147', '2015-03-10 21:54:47', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 54),
(100, 'BJX-BO-0100', '2018-01-21 10:07:29', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 51),
(101, 'BJX-BO-0101', '2017-04-30 21:04:00', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 66),
(102, 'BJX-BO-0102', '2015-06-16 18:04:08', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 67),
(103, 'BJX-BO-0103', '2017-05-13 19:14:41', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 68),
(104, 'BJX-BO-0104', '2017-04-30 21:03:10', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 34),
(105, 'BJX-BO-0105', '2017-04-30 21:04:07', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 69),
(106, 'BJX-BO-0106', '2018-01-21 10:09:27', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 1),
(107, 'BJX-BO-0107', '2017-04-30 21:01:39', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 102),
(108, 'BJX-BO-0108', '2017-04-30 21:04:16', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 71),
(109, 'BJX-BO-0109', '2017-05-13 19:14:44', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 72),
(110, 'BJX-BO-0110', '2017-05-13 19:13:25', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 6),
(112, 'BJX-BO-0112', '2017-04-30 21:04:18', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 73),
(113, 'BJX-BO-0113', '2017-05-13 19:13:53', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 25),
(116, 'BJX-BO-0116', '2018-01-21 10:07:01', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 41),
(117, 'BJX-BO-0117', '2017-05-13 19:14:48', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 75),
(118, 'BJX-BO-0118', '2017-05-13 19:14:33', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 63),
(120, 'BJX-TOR-0120', '2015-03-27 11:48:41', 80, 0, 0, 0, 19, 0, 0, -5, 1, 0, 0, 19.6, 6),
(133, 'BJX-PEN-0133', '2017-05-13 19:16:47', 60, 0, 0, 0, 20, 0, 0, 1, 1, 0, 0, 19.6, 1),
(125, 'BJX-COL-0125', '2015-03-27 11:55:10', 70, 0, 0, 0, 4, 0, 0, 1, 1, 0, 0, 19.6, 3),
(126, 'BJX-COL-0126', '2015-03-27 11:55:23', 70, 0, 0, 0, 4, 0, 0, 1, 1, 0, 0, 19.6, 4),
(166, 'BJX-TOR-0166', '2017-04-30 21:21:30', 120, 0, 0, 0, 19, 0, 0, -16, 0, 0, 0, 19.6, 1),
(128, 'BJX-COL-0128', '2015-03-27 11:55:38', 70, 0, 0, 0, 4, 0, 0, 1, 1, 0, 0, 19.6, 5),
(129, 'BJX-COL-0129', '2015-06-24 20:28:31', 65, 0, 0, 0, 4, 0, 0, -3, 1, 0, 0, 19.6, 6),
(131, 'BJX-COL-0131', '2015-03-27 11:55:51', 65, 0, 0, 0, 4, 0, 0, 1, 1, 0, 0, 19.6, 7),
(134, 'BJX-PEN-0134', '2017-04-30 21:21:58', 60, 0, 0, 0, 20, 0, 0, 1, 0, 0, 0, 19.6, 2),
(135, 'BJX-PEN-0135', '2017-05-13 19:16:51', 50, 0, 0, 0, 20, 0, 0, 1, 1, 0, 0, 19.6, 3),
(136, 'BJX-PEN-0136', '2017-04-30 21:22:01', 50, 0, 0, 0, 20, 0, 0, 0, 0, 0, 0, 19.6, 4),
(137, 'BJX-PEN-0137', '2017-04-30 21:22:02', 50, 0, 0, 0, 20, 0, 0, 1, 0, 0, 0, 19.6, 5),
(138, 'BJX-PEN-0138', '2017-04-30 21:22:04', 60, 0, 0, 0, 20, 0, 0, 0, 0, 0, 0, 19.6, 6),
(139, 'BJX-PEN-0139', '2017-05-13 19:16:56', 65, 0, 0, 0, 20, 0, 0, -2, 1, 0, 0, 19.6, 7),
(140, 'BJX-PEN-0140', '2017-05-13 19:16:57', 60, 0, 0, 0, 20, 0, 0, -1, 1, 0, 0, 19.6, 8),
(142, 'BJX-PEN-0142', '2017-04-30 21:22:08', 60, 0, 0, 0, 20, 0, 0, 1, 0, 0, 0, 19.6, 9),
(143, 'BJX-PEN-0143', '2017-04-30 21:22:10', 60, 0, 0, 0, 20, 0, 0, 1, 0, 0, 0, 19.6, 10),
(145, 'BJX-BO-0145', '2017-05-13 19:14:28', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 56),
(149, 'BJX-BO-0149', '2018-01-21 10:08:54', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 57),
(151, 'BJX-BO-0151', '2017-05-13 19:14:49', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 76),
(152, 'BJX-BO-0152', '2017-05-13 19:13:40', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 16),
(153, 'BJX-BO-0153', '2017-04-30 21:02:40', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 20),
(154, 'BJX-BO-0154', '2017-05-13 19:14:51', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 77),
(155, 'BJX-BO-0155', '2017-05-13 19:14:52', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 78),
(169, 'BJX-COL-0169', '2017-04-30 21:20:23', 80, 0, 0, 0, 4, 0, 0, -3, 0, 0, 0, 19.6, 8),
(171, 'BJX-BO-0171', '2017-04-30 21:04:50', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 85),
(172, 'BJX-TOR-0172', '2017-04-30 21:21:27', 120, 0, 0, 0, 19, 0, 0, -4, 0, 0, 0, 19.6, 2),
(173, 'BJX-TOR-0173', '2017-04-30 21:21:25', 120, 0, 0, 0, 19, 0, 0, 1, 0, 0, 0, 19.6, 3),
(175, 'BJX-BO-0175', '2017-04-30 21:04:52', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 86),
(176, 'BJX-BO-0176', '2017-04-30 21:04:58', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 88),
(191, 'BJX-BO-0191', '2018-01-21 10:09:13', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 99),
(178, 'BJX-BO-0178', '2017-04-30 21:05:01', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 89),
(179, 'BJX-BO-0179', '2015-03-12 21:52:45', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 90),
(180, 'BJX-BO-0180', '2017-04-30 21:05:04', 0, 0, 0, 0, 3, 0, 0, -3, 1, 0, 0, 19.6, 91),
(181, 'BJX-BO-0181', '2017-04-30 21:01:59', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 92),
(182, 'BJX-BO-0182', '2017-05-13 19:15:12', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 93),
(183, 'BJX-BO-0183', '2015-03-12 21:58:02', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 94),
(184, 'BJX-BO-0184', '2018-01-21 10:14:33', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 95),
(185, 'BJX-BO-0185', '2018-01-21 10:14:38', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 96),
(188, 'BJX-BO-0188', '2017-05-13 19:15:19', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 97),
(192, 'BJX-BO-0192', '2017-05-13 19:15:23', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 100),
(193, 'BJX-BO-0193', '2018-01-21 10:09:07', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 101),
(194, 'BJX-BO-0194', '2017-05-13 19:14:42', 0, 0, 0, 0, 3, 0, 0, -3, 1, 0, 0, 19.6, 70),
(195, 'BJX-BO-0195', '2017-05-13 19:14:36', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 65),
(196, 'BJX-BO-0196', '2018-01-21 10:08:15', 0, 0, 0, 0, 3, 0, 0, 1, 0, 0, 0, 19.6, 66),
(197, 'BJX-BO-0197', '2017-05-13 19:15:27', 0, 0, 0, 0, 3, 0, 0, 1, 1, 0, 0, 19.6, 105),
(223, 'BJX-PLP-0223', '2017-04-30 21:23:48', 120, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 1),
(224, 'BJX-PLP-0224', '2017-04-30 21:23:49', 120, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 2),
(225, 'BJX-PLP-0225', '2015-06-24 23:11:14', 120, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 3),
(227, 'BJX-PLP-0227', '2017-04-30 21:23:58', 120, 0, 0, 0, 30, 0, 0, 1, 0, 0, 0, 19.6, 4),
(228, 'BJX-PLP-0228', '2017-04-30 21:24:01', 120, 0, 0, 0, 30, 0, 0, 1, 0, 0, 0, 19.6, 5),
(229, 'BJX-PLP-0229', '2015-06-24 23:12:10', 120, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 6),
(230, 'BJX-PLP-0230', '2018-01-21 10:17:07', 120, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 7),
(231, 'BJX-PLP-0231', '2015-06-24 23:14:15', 90, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 8),
(232, 'BJX-PLP-0232', '2015-06-24 23:14:47', 90, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 9),
(233, 'BJX-PLP-0233', '2015-06-24 23:15:12', 120, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 10),
(234, 'BJX-PLP-0234', '2015-06-24 23:16:44', 100, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 11),
(235, 'BJX-PLP-0235', '2018-01-21 10:17:09', 100, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 12),
(236, 'BJX-PLP-0236', '2015-06-24 23:17:06', 120, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 13),
(237, 'BJX-PLP-0237', '2017-04-30 21:24:11', 120, 0, 0, 0, 30, 0, 0, 1, 0, 0, 0, 19.6, 14),
(238, 'BJX-PLP-0238', '2017-04-30 21:24:13', 100, 0, 0, 0, 30, 0, 0, 1, 0, 0, 0, 19.6, 15),
(239, 'BJX-PLP-0239', '2015-06-24 23:18:13', 0, 0, 0, 0, 30, 0, 0, 1, 1, 0, 0, 19.6, 16),
(240, 'BJX-TOR-0240', '2017-04-30 21:21:17', 120, 0, 0, 0, 19, 0, 0, 1, 0, 0, 0, 19.6, 7);

-- --------------------------------------------------------

--
-- Structure de la table `produitdesc`
--

CREATE TABLE `produitdesc` (
  `id` int(11) NOT NULL,
  `produit` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `postscriptum` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produitdesc`
--

INSERT INTO `produitdesc` (`id`, `produit`, `lang`, `titre`, `chapo`, `description`, `postscriptum`) VALUES
(24, 24, 1, 'Colliers 24', '', '<p>Pi&egrave;ces en zinc bross&eacute;, nou&eacute;es sur un cordon.</p>', ''),
(29, 29, 1, 'Boucles d\'oreilles 29', '', '', ''),
(28, 28, 1, 'Boucles d\'oreilles 28', '', '', ''),
(27, 27, 1, 'Torques 27', '', '<p>Les pi&egrave;ces sont en zinc bross&eacute;s, mont&eacute;es sur des anneaux enfil&eacute;es sur une barre cintr&eacute;e en aluminium.</p>', ''),
(26, 26, 1, 'Torques 26', '', '<p>Les pi&egrave;ces sont en zinc bross&eacute;s, mont&eacute;es sur des anneaux, enfil&eacute;es sur une barre cintr&eacute;e en aluminium.</p>', ''),
(25, 25, 1, 'Colliers 25', '', '<p>Pi&egrave;ces en zinc bross&eacute;, nou&eacute;es sur un cordon.</p>', ''),
(23, 23, 1, 'Boucles d\'oreilles 23', '', '', ''),
(36, 36, 1, 'Boucles d\'oreilles 36', '', '', ''),
(21, 21, 1, 'Boucles d\'oreilles 21', '', '', ''),
(20, 20, 1, 'Boucles d\'oreilles 20', '', '', ''),
(18, 18, 1, 'Boucles d\'oreilles 18', '', '', ''),
(19, 19, 1, 'Boucles d\'oreilles 19', '', '', ''),
(30, 30, 1, 'Boucles d\'oreilles 30', '', '', ''),
(31, 31, 1, 'Boucles d\'oreilles 31', '', '', ''),
(32, 32, 1, 'Boucles d\'oreilles 32', '', '', ''),
(33, 33, 1, 'Boucles d\'oreilles 33', '', '', ''),
(35, 35, 1, 'Boucles d\'oreilles 35', '', '', ''),
(163, 163, 1, 'Boucles d\'oreilles 163', '', '', ''),
(39, 39, 1, 'Boucles d\'oreilles 39', '', '', ''),
(41, 41, 1, 'Boucles d\'oreilles 41', '', '', ''),
(42, 42, 1, 'Boucles d\'oreilles 42', '', '', ''),
(159, 159, 1, 'Boucles d\'oreilles 159', '', '', ''),
(157, 157, 1, 'Boucles d\'oreilles 157', '', '', ''),
(158, 158, 1, 'Boucles d\'oreilles 158', '', '', ''),
(49, 49, 1, 'Boucles d\'oreilles 49', '', '', ''),
(50, 50, 1, 'Boucles d\'oreilles 50', '', '', ''),
(51, 51, 1, 'Boucles d\'oreilles 51', '', '', ''),
(52, 52, 1, 'Boucles d\'oreilles 52', '', '', ''),
(53, 53, 1, 'Boucles d\'oreilles 53', '', '', ''),
(54, 54, 1, 'Boucles d\'oreilles 54', '', '', ''),
(55, 55, 1, 'Boucles d\'oreilles 55', '', '', ''),
(56, 56, 1, 'Boucles d\'oreilles 56', '', '', ''),
(58, 58, 1, 'Boucles d\'oreilles 58', '', '', ''),
(59, 59, 1, 'Boucles d\'oreilles 59', '', '', ''),
(60, 60, 1, 'Boucles d\'oreilles 60', '', '', ''),
(61, 61, 1, 'Boucles d\'oreilles 61', '', '', ''),
(63, 63, 1, 'Boucles d\'oreilles 63', '', '', ''),
(64, 64, 1, 'Boucles d\'oreilles 64', '', '', ''),
(65, 65, 1, 'Boucles d\'oreilles 65', '', '', ''),
(66, 66, 1, 'Boucles d\'oreilles 66', '', '', ''),
(164, 164, 1, 'Boucles d\'oreilles 164', '', '', ''),
(69, 69, 1, 'Boucles d\'oreilles 69', '', '', ''),
(70, 70, 1, 'Boucles d\'oreilles 70', '', '', ''),
(71, 71, 1, 'Boucles d\'oreilles 71', '', '', ''),
(72, 72, 1, 'Boucles d\'oreilles 72', '', '', ''),
(73, 73, 1, 'Boucles d\'oreilles 73', '', '', ''),
(74, 74, 1, 'Boucles d\'oreilles 74', '', '', ''),
(75, 75, 1, 'Boucles d\'oreilles 75', '', '', ''),
(77, 77, 1, 'Boucles d\'oreilles 77', '', '', ''),
(78, 78, 1, 'Boucles d\'oreilles 78', '', '', ''),
(79, 79, 1, 'Boucles d\'oreilles 79', '', '', ''),
(80, 80, 1, 'Boucles d\'oreilles 80', '', '', ''),
(81, 81, 1, 'Boucles d\'oreilles 81', '', '', ''),
(82, 82, 1, 'Boucles d\'oreilles 82', '', '', ''),
(84, 84, 1, 'Boucles d\'oreilles 84', '', '', ''),
(85, 85, 1, 'Boucles d\'oreilles 85', '', '', ''),
(86, 86, 1, 'Boucles d\'oreilles 86', '', '', ''),
(87, 87, 1, 'Boucles d\'oreilles 87', '', '', ''),
(88, 88, 1, 'Boucles d\'oreilles 88', '', '', ''),
(89, 89, 1, 'Boucles d\'oreilles 89', '', '', ''),
(165, 165, 1, 'Boucles d\'oreilles 165', '', '', ''),
(93, 93, 1, 'Boucles d\'oreilles 93', '', '', ''),
(94, 94, 1, 'Boucles d\'oreilles 94', '', '', ''),
(95, 95, 1, 'Boucles d\'oreilles 95', '', '', ''),
(96, 96, 1, 'Boucles d\'oreilles 96', '', '', ''),
(97, 97, 1, 'Boucles d\'oreilles 97', '', '', ''),
(98, 98, 1, 'Boucles d\'oreilles 98', '', '', ''),
(147, 147, 1, 'Boucles d\'oreilles 147', '', '', ''),
(100, 100, 1, 'Boucles d\'oreilles 100', '', '', ''),
(101, 101, 1, 'Boucles d\'oreilles 101', '', '', ''),
(102, 102, 1, 'Boucles d\'oreilles 102', '', '', ''),
(103, 103, 1, 'Boucles d\'oreilles 103', '', '', ''),
(104, 104, 1, 'Boucles d\'oreilles 104', '', '', ''),
(105, 105, 1, 'Boucles d\'oreilles 105', '', '', ''),
(106, 106, 1, 'Boucles d\'oreilles 106', '', '', ''),
(107, 107, 1, 'Boucles d\'oreilles 107', '', '', ''),
(108, 108, 1, 'Boucles d\'oreilles 108', '', '', ''),
(109, 109, 1, 'Boucles d\'oreilles 109', '', '', ''),
(110, 110, 1, 'Boucles d\'oreilles 110', '', '', ''),
(112, 112, 1, 'Boucles d\'oreilles 112', '', '', ''),
(113, 113, 1, 'Boucles d\'oreilles 113', '', '', ''),
(116, 116, 1, 'Boucles d\'oreilles 116', '', '', ''),
(117, 117, 1, 'Boucles d\'oreilles 117', '', '', ''),
(118, 118, 1, 'Boucles d\'oreilles 118', '', '', ''),
(120, 120, 1, 'Torques 120', '', '<p>Torque en tube aluminium et perles.</p>', ''),
(133, 133, 1, 'Pendentifs 133', '', '', ''),
(125, 125, 1, 'Colliers 125', '', '<p>Pi&egrave;ces en zinc bross&eacute;, nou&eacute;es sur un cordon.</p>', ''),
(126, 126, 1, 'Colliers 126', '', '<p>Pi&egrave;ces en zinc bross&eacute;, nou&eacute;es sur un ruban de soie.</p>', ''),
(128, 128, 1, 'Colliers 128', '', '<p>Pi&egrave;ces en zinc bross&eacute;, nou&eacute;es sur un lien de soie.</p>', ''),
(129, 129, 1, 'Colliers 129', '', '<p>Pi&egrave;ces en zinc bross&eacute;, nou&eacute;es sur un ruban de soie.</p>', ''),
(131, 131, 1, 'Colliers 131', '', '<p>Pi&egrave;ces en zinc bross&eacute;, no&eacute;es sur un cordon.</p>', ''),
(166, 166, 1, 'Torques 166', '', '<p>Torque en aluminium et perles.</p>', ''),
(134, 134, 1, 'Pendentifs 134', '', '', ''),
(135, 135, 1, 'Pendentifs 135', '', '', ''),
(136, 136, 1, 'Pendentifs 136', '', '', ''),
(137, 137, 1, 'Pendentifs 137', '', '', ''),
(138, 138, 1, 'Pendentifs 138', '', '', ''),
(139, 139, 1, 'Pendentifs 139', '', '', ''),
(140, 140, 1, 'Pendentifs 140', '', '', ''),
(142, 142, 1, 'Pendentifs 142', '', '', ''),
(143, 143, 1, 'Pendentifs 143', '', '', ''),
(145, 145, 1, 'Boucles d\'oreilles 145', '', '', ''),
(149, 149, 1, 'Boucles d\'oreilles 149', '', '', ''),
(151, 151, 1, 'Boucles d\'oreilles 151', '', '', ''),
(152, 152, 1, 'Boucles d\'oreilles 152', '', '', ''),
(153, 153, 1, 'Boucles d\'oreilles 153', '', '', ''),
(154, 154, 1, 'Boucles d\'oreilles 154', '', '', ''),
(155, 155, 1, 'Boucles d\'oreilles 155', '', '', ''),
(169, 169, 1, 'Colliers 169', '', '<p>Pi&egrave;ces en zinc bross&eacute;, mont&eacute;es sur des anneaux, enfil&eacute;es sur un tube souple en plastique.</p>', ''),
(171, 171, 1, 'Boucles d\'oreilles 171', '', '', ''),
(172, 172, 1, 'Torques 172', '', '<p>Torque en aluminium et perles.</p>', ''),
(173, 173, 1, 'Torques 173', '', '<p>Torque en aluminium et perles.</p>', ''),
(175, 175, 1, 'Boucles d\'oreilles 175', '', '', ''),
(176, 176, 1, 'Boucles d\'oreilles 176', '', '', ''),
(191, 191, 1, 'Boucles d\'oreilles 191', '', '', ''),
(178, 178, 1, 'Boucles d\'oreilles 178', '', '', ''),
(179, 179, 1, 'Boucles d\'oreilles 179', '', '', ''),
(180, 180, 1, 'Boucles d\'oreilles 180', '', '', ''),
(181, 181, 1, 'Boucles d\'oreilles 181', '', '', ''),
(182, 182, 1, 'Boucles d\'oreilles 182', '', '', ''),
(183, 183, 1, 'Boucles d\'oreilles 183', '', '', ''),
(184, 184, 1, 'Boucles d\'oreilles 184', '', '', ''),
(185, 185, 1, 'Boucles d\'oreilles 185', '', '', ''),
(188, 188, 1, 'Boucles d\'oreilles 188', '', '', ''),
(192, 192, 1, 'Boucles d\'oreilles 192', '', '', ''),
(193, 193, 1, 'Boucles d\'oreilles 193', '', '', ''),
(194, 194, 1, 'Boucles d\'oreilles 194', '', '', ''),
(195, 195, 1, 'Boucles d\'oreilles 195', '', '', ''),
(196, 196, 1, 'Boucles d\'oreilles 196', '', '', ''),
(197, 197, 1, 'Boucles d\'oreilles 197', '', '', ''),
(223, 223, 1, 'plastron perles 223', '', '', ''),
(224, 224, 1, 'plastron perles 224', '', '', ''),
(225, 225, 1, 'plastron perles 225', '', '', ''),
(227, 227, 1, 'plastron perles 227', '', '', ''),
(228, 228, 1, 'plastron perles 228', '', '', ''),
(229, 229, 1, 'plastron perles 229', '', '', ''),
(230, 230, 1, 'plastron perles 230', '', '', ''),
(231, 231, 1, 'plastron perles 231', '', '', ''),
(232, 232, 1, 'plastron perles 232', '', '', ''),
(233, 233, 1, 'plastron perles 233', '', '', ''),
(234, 234, 1, 'plastron perles 234', '', '', ''),
(235, 235, 1, 'plastron perles 235', '', '', ''),
(236, 236, 1, 'plastron perles 236', '', '', ''),
(237, 237, 1, 'plastron perles 237', '', '', ''),
(238, 238, 1, 'plastron perles 238', '', '', ''),
(239, 239, 1, 'plastron perles 239', '', '', ''),
(240, 240, 1, 'Torques 240', '', '<p>Torques en tube, scoubidou et p&eacute;rles, pass&eacute; sur un lien.</p>', '');

-- --------------------------------------------------------

--
-- Structure de la table `profil`
--

CREATE TABLE `profil` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `profil`
--

INSERT INTO `profil` (`id`, `nom`) VALUES
(1, 'superadministrateur'),
(2, 'gestionnairecommande'),
(3, 'gestionnairecatalogue');

-- --------------------------------------------------------

--
-- Structure de la table `profildesc`
--

CREATE TABLE `profildesc` (
  `id` int(11) NOT NULL,
  `profil` int(11) NOT NULL,
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `postscriptum` text NOT NULL,
  `lang` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `profildesc`
--

INSERT INTO `profildesc` (`id`, `profil`, `titre`, `chapo`, `description`, `postscriptum`, `lang`) VALUES
(1, 1, 'Super administrateur', '', '', '', 1),
(2, 2, 'Gestionnaire des commandes', '', '', '', 1),
(3, 3, 'Gestionnaire du catalogue', '', '', '', 1),
(4, 1, 'Super administrator', '', '', '', 2),
(5, 2, 'Order manager', '', '', '', 2),
(6, 3, 'Catalog manager', '', '', '', 2),
(7, 1, 'Super administrador', '', '', '', 3),
(8, 2, 'Gestión de los pedidos', '', '', '', 3),
(9, 3, 'Gestión del catalogo', '', '', '', 3);

-- --------------------------------------------------------

--
-- Structure de la table `promo`
--

CREATE TABLE `promo` (
  `id` int(11) NOT NULL,
  `code` text NOT NULL,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `valeur` float NOT NULL DEFAULT '0',
  `mini` float NOT NULL DEFAULT '0',
  `utilise` int(11) NOT NULL DEFAULT '0',
  `limite` smallint(6) NOT NULL DEFAULT '0',
  `datefin` date NOT NULL,
  `actif` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `promoutil`
--

CREATE TABLE `promoutil` (
  `id` int(11) NOT NULL,
  `promo` int(11) NOT NULL,
  `commande` int(11) NOT NULL,
  `code` text NOT NULL,
  `type` smallint(6) NOT NULL,
  `valeur` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `raisondesc`
--

CREATE TABLE `raisondesc` (
  `id` int(11) NOT NULL,
  `raison` int(11) NOT NULL,
  `lang` int(11) NOT NULL,
  `court` text NOT NULL,
  `long` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `raisondesc`
--

INSERT INTO `raisondesc` (`id`, `raison`, `lang`, `court`, `long`) VALUES
(1, 1, 1, 'Mme', 'Madame'),
(2, 2, 1, 'Mlle', 'Mademoiselle'),
(3, 3, 1, 'M', 'Monsieur'),
(4, 1, 2, 'Mrs', 'Madam'),
(5, 2, 2, 'Miss', 'Miss'),
(6, 3, 2, 'Mr', 'Sir');

-- --------------------------------------------------------

--
-- Structure de la table `reecriture`
--

CREATE TABLE `reecriture` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `fond` varchar(255) NOT NULL,
  `param` varchar(255) NOT NULL,
  `lang` int(11) NOT NULL,
  `actif` smallint(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `reecriture`
--

INSERT INTO `reecriture` (`id`, `url`, `fond`, `param`, `lang`, `actif`) VALUES
(1, '1-collages.html', 'rubrique', '&id_rubrique=1', 1, 0),
(2, '2-bijoux.html', 'rubrique', '&id_rubrique=2', 1, 0),
(3, '3-boucles-d-oreilles.html', 'rubrique', '&id_rubrique=3', 1, 1),
(4, '4-colliers.html', 'rubrique', '&id_rubrique=4', 1, 1),
(5, '1-boucles-d-oreilles-boucles-d-oreilles-1.html', 'produit', '&id_produit=1&id_rubrique=5', 1, 0),
(6, '2-collages-collage-1.html', 'produit', '&id_produit=2&id_rubrique=1', 1, 0),
(7, '3-boucles-d-oreilles-boucles-d-oreilles-2.html', 'produit', '&id_produit=3&id_rubrique=3', 1, 1),
(8, '4-boucles-d-oreilles-boucles-d-oreilles-3.html', 'produit', '&id_produit=4&id_rubrique=3', 1, 1),
(9, '5-boucles-d-oreilles-boucles-d-oreilles-4.html', 'produit', '&id_produit=5&id_rubrique=3', 1, 1),
(10, '6-boucles-d-oreilles-boucles-d-oreilles-5.html', 'produit', '&id_produit=6&id_rubrique=3', 1, 1),
(11, '7-boucles-d-oreilles-boucles-d-oreilles-6.html', 'produit', '&id_produit=7&id_rubrique=3', 1, 1),
(12, '8-boucles-d-oreilles-boucles-d-oreilles-7.html', 'produit', '&id_produit=8&id_rubrique=3', 1, 1),
(13, '9-boucles-d-oreilles-boucles-d-oreilles-8.html', 'produit', '&id_produit=9&id_rubrique=3', 1, 1),
(14, '10-boucles-d-oreilles-boucles-d-oreilles-9.html', 'produit', '&id_produit=10&id_rubrique=3', 1, 1),
(15, '11-boucles-d-oreilles-boucles-d-oreilles-10.html', 'produit', '&id_produit=11&id_rubrique=3', 1, 1),
(16, '12-boucles-d-oreilles-boucles-d-oreilles-11.html', 'produit', '&id_produit=12&id_rubrique=3', 1, 1),
(17, '13-boucles-d-oreilles-boucles-d-oreilles-12.html', 'produit', '&id_produit=13&id_rubrique=3', 1, 1),
(18, '1--bo-contenu.html', 'contenu', '&id_contenu=1&id_dossier=1', 1, 0),
(19, '5-2014.html', 'rubrique', '&id_rubrique=5', 1, 0),
(20, '6-2013.html', 'rubrique', '&id_rubrique=6', 1, 0),
(21, '8-2014-boucles-d-oreilles-7.html', 'produit', '&id_produit=8&id_rubrique=5', 1, 0),
(22, '7-accueil.html', 'rubrique', '&id_rubrique=7', 1, 0),
(23, '7-accueil.html', 'nexisteplus', '&id_rubrique=7&ancienfond=rubrique', 1, 1),
(24, '9-2014-boucles-d-oreilles-8.html', 'produit', '&id_produit=9&id_rubrique=5', 1, 0),
(25, '10-2014-boucles-d-oreilles-9.html', 'produit', '&id_produit=10&id_rubrique=5', 1, 0),
(26, '11-2014-boucles-d-oreilles-10.html', 'produit', '&id_produit=11&id_rubrique=5', 1, 0),
(27, '12-2014-boucles-d-oreilles-11.html', 'produit', '&id_produit=12&id_rubrique=5', 1, 0),
(28, '13-2014-boucles-d-oreilles-12.html', 'produit', '&id_produit=13&id_rubrique=5', 1, 0),
(29, '3-2013-boucles-d-oreilles-2.html', 'produit', '&id_produit=3&id_rubrique=6', 1, 0),
(30, '4-2013-boucles-d-oreilles-3.html', 'produit', '&id_produit=4&id_rubrique=6', 1, 0),
(31, '5-2013-boucles-d-oreilles-4.html', 'produit', '&id_produit=5&id_rubrique=6', 1, 0),
(32, '6-2013-boucles-d-oreilles-5.html', 'produit', '&id_produit=6&id_rubrique=6', 1, 0),
(33, '7-2013-boucles-d-oreilles-6.html', 'produit', '&id_produit=7&id_rubrique=6', 1, 0),
(34, '8-colliers.html', 'rubrique', '&id_rubrique=8', 1, 0),
(35, '14-colliers-collier-perle.html', 'produit', '&id_produit=14&id_rubrique=4', 1, 0),
(36, '8-colliers.html', 'nexisteplus', '&id_rubrique=8&ancienfond=rubrique', 1, 1),
(37, '1-mes-contenus.html', 'dossier', '&id_dossier=1', 1, 0),
(38, '1--bo-contenu.html', 'nexisteplus', '&id_contenu=1&id_dossier=1&ancienfond=contenu', 1, 1),
(39, '2-mes-contenus-la-boutique-ketmie.html', 'contenu', '&id_contenu=2&id_dossier=2', 1, 0),
(40, '2-index.html', 'dossier', '&id_dossier=2', 1, 0),
(41, '1-mes-contenus.html', 'nexisteplus', '&id_dossier=1&ancienfond=dossier', 1, 1),
(42, '9-tous-les-produits.html', 'rubrique', '&id_rubrique=9', 1, 0),
(43, '15-collages-collage-2.html', 'produit', '&id_produit=15&id_rubrique=1', 1, 0),
(44, '3-2013-boucles-d-oreilles-2.html', 'nexisteplus', '&id_produit=3&id_rubrique=6&ancienfond=produit', 1, 1),
(45, '4-2013-boucles-d-oreilles-3.html', 'nexisteplus', '&id_produit=4&id_rubrique=6&ancienfond=produit', 1, 1),
(46, '5-2013-boucles-d-oreilles-4.html', 'nexisteplus', '&id_produit=5&id_rubrique=6&ancienfond=produit', 1, 1),
(47, '6-2013-boucles-d-oreilles-5.html', 'nexisteplus', '&id_produit=6&id_rubrique=6&ancienfond=produit', 1, 1),
(48, '7-2013-boucles-d-oreilles-6.html', 'nexisteplus', '&id_produit=7&id_rubrique=6&ancienfond=produit', 1, 1),
(49, '6-2013.html', 'nexisteplus', '&id_rubrique=6&ancienfond=rubrique', 1, 1),
(50, '11-2014-boucles-d-oreilles-10.html', 'nexisteplus', '&id_produit=11&id_rubrique=5&ancienfond=produit', 1, 1),
(51, '1-boucles-d-oreilles-boucles-d-oreilles-1.html', 'nexisteplus', '&id_produit=1&id_rubrique=5&ancienfond=produit', 1, 1),
(52, '8-2014-boucles-d-oreilles-7.html', 'nexisteplus', '&id_produit=8&id_rubrique=5&ancienfond=produit', 1, 1),
(53, '9-2014-boucles-d-oreilles-8.html', 'nexisteplus', '&id_produit=9&id_rubrique=5&ancienfond=produit', 1, 1),
(54, '10-2014-boucles-d-oreilles-9.html', 'nexisteplus', '&id_produit=10&id_rubrique=5&ancienfond=produit', 1, 1),
(55, '12-2014-boucles-d-oreilles-11.html', 'nexisteplus', '&id_produit=12&id_rubrique=5&ancienfond=produit', 1, 1),
(56, '13-2014-boucles-d-oreilles-12.html', 'nexisteplus', '&id_produit=13&id_rubrique=5&ancienfond=produit', 1, 1),
(57, '5-2014.html', 'nexisteplus', '&id_rubrique=5&ancienfond=rubrique', 1, 1),
(58, '14-colliers-collier-perle.html', 'nexisteplus', '&id_produit=14&id_rubrique=4&ancienfond=produit', 1, 1),
(59, '10-2009.html', 'rubrique', '&id_rubrique=10', 1, 0),
(60, '11-2010.html', 'rubrique', '&id_rubrique=11', 1, 0),
(61, '16-2009-boucles-d-oreilles-1.html', 'produit', '&id_produit=16&id_rubrique=10', 1, 0),
(62, '17-2009-iuyuyiuy.html', 'produit', '&id_produit=17&id_rubrique=10', 1, 0),
(63, '17-2009-iuyuyiuy.html', 'nexisteplus', '&id_produit=17&id_rubrique=10&ancienfond=produit', 1, 1),
(64, '16-2009-boucles-d-oreilles-1.html', 'nexisteplus', '&id_produit=16&id_rubrique=10&ancienfond=produit', 1, 1),
(65, '10-2009.html', 'nexisteplus', '&id_rubrique=10&ancienfond=rubrique', 1, 1),
(66, '11-2010.html', 'nexisteplus', '&id_rubrique=11&ancienfond=rubrique', 1, 1),
(67, '12-2009.html', 'rubrique', '&id_rubrique=12', 1, 1),
(68, '13-2010.html', 'rubrique', '&id_rubrique=13', 1, 1),
(69, '14-2008.html', 'rubrique', '&id_rubrique=14', 1, 1),
(70, '15-2011.html', 'rubrique', '&id_rubrique=15', 1, 1),
(71, '16-2012.html', 'rubrique', '&id_rubrique=16', 1, 1),
(72, '17-2013.html', 'rubrique', '&id_rubrique=17', 1, 1),
(73, '18-2014.html', 'rubrique', '&id_rubrique=18', 1, 1),
(74, '19-torcs.html', 'rubrique', '&id_rubrique=19', 1, 1),
(75, '20-pendentifs.html', 'rubrique', '&id_rubrique=20', 1, 1),
(76, '18-2008-.html', 'produit', '&id_produit=18&id_rubrique=12', 1, 1),
(77, '19-2008-.html', 'produit', '&id_produit=19&id_rubrique=12', 1, 1),
(78, '20-2009-boucles-d-oreilles-3.html', 'produit', '&id_produit=20&id_rubrique=12', 1, 1),
(79, '21-2009-boucles-d-oreilles-4.html', 'produit', '&id_produit=21&id_rubrique=12', 1, 1),
(80, '22-2010-boucles-d-oreilles-15.html', 'produit', '&id_produit=22&id_rubrique=13', 1, 0),
(81, '23-2009-boucles-d-oreilles-5.html', 'produit', '&id_produit=23&id_rubrique=12', 1, 1),
(82, '24-colliers-collier-1.html', 'produit', '&id_produit=24&id_rubrique=4', 1, 1),
(83, '25-colliers-colliers2.html', 'produit', '&id_produit=25&id_rubrique=4', 1, 1),
(84, '26-torcs-torcs.html', 'produit', '&id_produit=26&id_rubrique=19', 1, 1),
(85, '27-torcs-torcs.html', 'produit', '&id_produit=27&id_rubrique=19', 1, 1),
(86, '28-2009-boucles-d-oreilles-6.html', 'produit', '&id_produit=28&id_rubrique=12', 1, 1),
(87, '29-2009-boucles-d-oreilles-7.html', 'produit', '&id_produit=29&id_rubrique=12', 1, 1),
(88, '30-2009-boucles-d-oreilles-8.html', 'produit', '&id_produit=30&id_rubrique=12', 1, 1),
(89, '31-2009-boucles-d-oreilles-9.html', 'produit', '&id_produit=31&id_rubrique=12', 1, 1),
(90, '32-2009-boucles-d-oreilles-10.html', 'produit', '&id_produit=32&id_rubrique=12', 1, 1),
(91, '33-2009-boucles-d-oreilles-11.html', 'produit', '&id_produit=33&id_rubrique=12', 1, 1),
(92, '34-2009-boucles-d-oreilles-12.html', 'produit', '&id_produit=34&id_rubrique=12', 1, 0),
(93, '34-2009-boucles-d-oreilles-12.html', 'nexisteplus', '&id_produit=34&id_rubrique=12&ancienfond=produit', 1, 1),
(94, '35-2009-boucles-d-oreilles-12.html', 'produit', '&id_produit=35&id_rubrique=12', 1, 1),
(95, '22-2010-boucles-d-oreilles-15.html', 'nexisteplus', '&id_produit=22&id_rubrique=13&ancienfond=produit', 1, 1),
(96, '36-2010-boucles-d-oreilles-13.html', 'produit', '&id_produit=36&id_rubrique=13', 1, 1),
(97, '37-2010-boucles-d-oreilles-14.html', 'produit', '&id_produit=37&id_rubrique=13', 1, 0),
(98, '37-2010-boucles-d-oreilles-14.html', 'nexisteplus', '&id_produit=37&id_rubrique=13&ancienfond=produit', 1, 1),
(99, '38-2010-boucles-d-oreilles-14.html', 'produit', '&id_produit=38&id_rubrique=13', 1, 0),
(100, '39-2010-boucles-d-oreilles-15.html', 'produit', '&id_produit=39&id_rubrique=13', 1, 1),
(101, '38-2010-boucles-d-oreilles-14.html', 'nexisteplus', '&id_produit=38&id_rubrique=13&ancienfond=produit', 1, 1),
(102, '40-2010-boucles-d-oreilles-14.html', 'produit', '&id_produit=40&id_rubrique=13', 1, 0),
(103, '41-2010-boucles-d-oreilles-16.html', 'produit', '&id_produit=41&id_rubrique=13', 1, 1),
(104, '42-2010-boucles-d-oreilles-17.html', 'produit', '&id_produit=42&id_rubrique=13', 1, 1),
(105, '43-2010-boucles-d-oreilles-18.html', 'produit', '&id_produit=43&id_rubrique=13', 1, 0),
(106, '43-2010-boucles-d-oreilles-18.html', 'nexisteplus', '&id_produit=43&id_rubrique=13&ancienfond=produit', 1, 1),
(107, '44-2010-boucles-d-oreilles-18.html', 'produit', '&id_produit=44&id_rubrique=13', 1, 0),
(108, '40-2010-boucles-d-oreilles-14.html', 'nexisteplus', '&id_produit=40&id_rubrique=13&ancienfond=produit', 1, 1),
(109, '45-2010-boucles-d-oreilles-14.html', 'produit', '&id_produit=45&id_rubrique=13', 1, 0),
(110, '21-boucles-d-oreilles-19.html', 'rubrique', '&id_rubrique=21', 1, 0),
(111, '46-2010-boucles-d-oreilles-19.html', 'produit', '&id_produit=46&id_rubrique=13', 1, 0),
(112, '47-2010-boucles-d-oreilles-20.html', 'produit', '&id_produit=47&id_rubrique=13', 1, 0),
(113, '47-2010-boucles-d-oreilles-20.html', 'nexisteplus', '&id_produit=47&id_rubrique=13&ancienfond=produit', 1, 1),
(114, '48-2010-boucles-d-oreilles-20.html', 'produit', '&id_produit=48&id_rubrique=13', 1, 0),
(115, '49-2010-boucles-d-oreilles-21.html', 'produit', '&id_produit=49&id_rubrique=13', 1, 1),
(116, '50-2010-boucles-d-oreilles-22.html', 'produit', '&id_produit=50&id_rubrique=13', 1, 1),
(117, '51-2010-boucles-d-oreilles-23.html', 'produit', '&id_produit=51&id_rubrique=13', 1, 1),
(118, '52-2010-boucles-d-oreilles-24.html', 'produit', '&id_produit=52&id_rubrique=13', 1, 1),
(119, '53-2010-boucles-d-oreilles-25.html', 'produit', '&id_produit=53&id_rubrique=13', 1, 1),
(120, '54-2010-boucles-d-oreilles-26.html', 'produit', '&id_produit=54&id_rubrique=13', 1, 1),
(121, '55-2010-boucles-d-oreilles-27.html', 'produit', '&id_produit=55&id_rubrique=13', 1, 1),
(122, '56-2011-boucles-d-oreilles-28.html', 'produit', '&id_produit=56&id_rubrique=15', 1, 1),
(123, '57-2011-boucles-d-oreilles-29.html', 'produit', '&id_produit=57&id_rubrique=15', 1, 0),
(124, '57-2011-boucles-d-oreilles-29.html', 'nexisteplus', '&id_produit=57&id_rubrique=15&ancienfond=produit', 1, 1),
(125, '58-2011-boucles-d-oreilles-29.html', 'produit', '&id_produit=58&id_rubrique=15', 1, 1),
(126, '59-2011-boucles-d-oreilles-30.html', 'produit', '&id_produit=59&id_rubrique=15', 1, 1),
(127, '60-2011-boucles-d-oreilles-31.html', 'produit', '&id_produit=60&id_rubrique=15', 1, 1),
(128, '61-2011-boucles-d-oreilles-32.html', 'produit', '&id_produit=61&id_rubrique=15', 1, 1),
(129, '62-2011-boucles-d-oreilles-34.html', 'produit', '&id_produit=62&id_rubrique=15', 1, 0),
(130, '62-2011-boucles-d-oreilles-34.html', 'nexisteplus', '&id_produit=62&id_rubrique=15&ancienfond=produit', 1, 1),
(131, '63-2011-boucles-d-oreilles-33.html', 'produit', '&id_produit=63&id_rubrique=15', 1, 1),
(132, '64-2011-boucles-d-oreilles-34.html', 'produit', '&id_produit=64&id_rubrique=15', 1, 1),
(133, '65-2011-boucles-d-oreilles-35.html', 'produit', '&id_produit=65&id_rubrique=15', 1, 1),
(134, '66-2011-boucles-d-oreilles-36.html', 'produit', '&id_produit=66&id_rubrique=15', 1, 1),
(135, '67-2011-boucles-d-oreilles-37.html', 'produit', '&id_produit=67&id_rubrique=15', 1, 0),
(136, '67-2011-boucles-d-oreilles-37.html', 'nexisteplus', '&id_produit=67&id_rubrique=15&ancienfond=produit', 1, 1),
(137, '68-2011-boucles-d-oreilles-37.html', 'produit', '&id_produit=68&id_rubrique=15', 1, 0),
(138, '69-2011-boucles-d-oreilles-38.html', 'produit', '&id_produit=69&id_rubrique=15', 1, 1),
(139, '70-2011-boucles-d-oreilles-39.html', 'produit', '&id_produit=70&id_rubrique=15', 1, 1),
(140, '71-2011-boucles-d-oreilles-40.html', 'produit', '&id_produit=71&id_rubrique=15', 1, 1),
(141, '72-2011-boucles-d-oreilles-41.html', 'produit', '&id_produit=72&id_rubrique=15', 1, 1),
(142, '73-2011-boucles-d-oreilles-42.html', 'produit', '&id_produit=73&id_rubrique=15', 1, 1),
(143, '74-2012-boucles-d-oreilles-43.html', 'produit', '&id_produit=74&id_rubrique=16', 1, 1),
(144, '75-2012-boucles-d-oreilles-44.html', 'produit', '&id_produit=75&id_rubrique=16', 1, 1),
(145, '76-2012-boucles-d-oreilles-45.html', 'produit', '&id_produit=76&id_rubrique=16', 1, 0),
(146, '76-2012-boucles-d-oreilles-45.html', 'nexisteplus', '&id_produit=76&id_rubrique=16&ancienfond=produit', 1, 1),
(147, '77-2012-boucles-d-oreilles-45.html', 'produit', '&id_produit=77&id_rubrique=16', 1, 1),
(148, '78-2012-boucles-d-oreilles-46.html', 'produit', '&id_produit=78&id_rubrique=16', 1, 1),
(149, '79-2012-boucles-d-oreilles-47.html', 'produit', '&id_produit=79&id_rubrique=16', 1, 1),
(150, '80-2012-boucles-d-oreilles-48.html', 'produit', '&id_produit=80&id_rubrique=16', 1, 1),
(151, '81-2012-boucles-d-oreilles-49.html', 'produit', '&id_produit=81&id_rubrique=16', 1, 1),
(152, '82-2012-boucles-d-oreilles-50.html', 'produit', '&id_produit=82&id_rubrique=16', 1, 1),
(153, '83-2012-boucles-d-oreilles-51.html', 'produit', '&id_produit=83&id_rubrique=16', 1, 0),
(154, '83-2012-boucles-d-oreilles-51.html', 'nexisteplus', '&id_produit=83&id_rubrique=16&ancienfond=produit', 1, 1),
(155, '84-2012-boucles-d-oreilles-51.html', 'produit', '&id_produit=84&id_rubrique=16', 1, 1),
(156, '85-2013-boucles-d-oreilles-52.html', 'produit', '&id_produit=85&id_rubrique=17', 1, 1),
(157, '86-2013-boucles-d-oreilles-53.html', 'produit', '&id_produit=86&id_rubrique=17', 1, 1),
(158, '87-2013-boucles-d-oreilles-54.html', 'produit', '&id_produit=87&id_rubrique=17', 1, 1),
(159, '88-2013-boucles-d-oreilles-55.html', 'produit', '&id_produit=88&id_rubrique=17', 1, 1),
(160, '89-2013-boucles-d-oreilles-56.html', 'produit', '&id_produit=89&id_rubrique=17', 1, 1),
(161, '90-2013-boucles-d-oreilles-57.html', 'produit', '&id_produit=90&id_rubrique=17', 1, 0),
(162, '90-2013-boucles-d-oreilles-57.html', 'nexisteplus', '&id_produit=90&id_rubrique=17&ancienfond=produit', 1, 1),
(163, '91-2013-boucles-d-oreilles-57.html', 'produit', '&id_produit=91&id_rubrique=17', 1, 0),
(164, '91-2013-boucles-d-oreilles-57.html', 'nexisteplus', '&id_produit=91&id_rubrique=17&ancienfond=produit', 1, 1),
(165, '92-2013-boucles-d-oreilles-57.html', 'produit', '&id_produit=92&id_rubrique=17', 1, 0),
(166, '93-2013-boucles-d-oreilles-58.html', 'produit', '&id_produit=93&id_rubrique=17', 1, 1),
(167, '94-2013-boucles-d-oreilles-59.html', 'produit', '&id_produit=94&id_rubrique=17', 1, 1),
(168, '95-2013-boucles-d-oreilles-60.html', 'produit', '&id_produit=95&id_rubrique=17', 1, 1),
(169, '96-2013-boucles-d-oreilles-61.html', 'produit', '&id_produit=96&id_rubrique=17', 1, 1),
(170, '97-2013-boucles-d-oreilles-62.html', 'produit', '&id_produit=97&id_rubrique=17', 1, 1),
(171, '98-2013-boucles-d-oreilles-63.html', 'produit', '&id_produit=98&id_rubrique=17', 1, 1),
(172, '99-2013-boucles-d-oreilles-64.html', 'produit', '&id_produit=99&id_rubrique=17', 1, 0),
(173, '100-2014-boucles-d-oreilles-65.html', 'produit', '&id_produit=100&id_rubrique=18', 1, 1),
(174, '101-2014-boucles-d-oreilles-66.html', 'produit', '&id_produit=101&id_rubrique=18', 1, 1),
(175, '102-2014-boucles-d-oreilles-67.html', 'produit', '&id_produit=102&id_rubrique=18', 1, 1),
(176, '103-2014-boucles-d-oreilles-68.html', 'produit', '&id_produit=103&id_rubrique=18', 1, 1),
(177, '104-2014-boucles-d-oreilles-69.html', 'produit', '&id_produit=104&id_rubrique=18', 1, 1),
(178, '105-2014-boucles-d-oreilles-70.html', 'produit', '&id_produit=105&id_rubrique=18', 1, 1),
(179, '106-2014-boucles-d-oreilles-71.html', 'produit', '&id_produit=106&id_rubrique=18', 1, 1),
(180, '107-2014-boucles-d-oreilles-72.html', 'produit', '&id_produit=107&id_rubrique=18', 1, 1),
(181, '108-2014-boucles-d-oreilles-73.html', 'produit', '&id_produit=108&id_rubrique=18', 1, 1),
(182, '109-2014-boucles-d-oreilles-74.html', 'produit', '&id_produit=109&id_rubrique=18', 1, 1),
(183, '110-2014-boucles-d-oreilles-75.html', 'produit', '&id_produit=110&id_rubrique=18', 1, 1),
(184, '111-2014-boucles-d-oreilles-77.html', 'produit', '&id_produit=111&id_rubrique=18', 1, 0),
(185, '111-2014-boucles-d-oreilles-77.html', 'nexisteplus', '&id_produit=111&id_rubrique=18&ancienfond=produit', 1, 1),
(186, '112-2014-boucles-d-oreilles-76.html', 'produit', '&id_produit=112&id_rubrique=18', 1, 1),
(187, '22-2015.html', 'rubrique', '&id_rubrique=22', 1, 0),
(188, '22-2015.html', 'nexisteplus', '&id_rubrique=22&ancienfond=rubrique', 1, 1),
(189, '23-2015.html', 'rubrique', '&id_rubrique=23', 1, 1),
(190, '113-2015-boucles-d-oreilles-77.html', 'produit', '&id_produit=113&id_rubrique=23', 1, 1),
(191, '114-2015-boucles-d-oreilles-78.html', 'produit', '&id_produit=114&id_rubrique=23', 1, 0),
(192, '114-2015-boucles-d-oreilles-78.html', 'nexisteplus', '&id_produit=114&id_rubrique=23&ancienfond=produit', 1, 1),
(193, '115-2015-boucles-d-oreilles-78.html', 'produit', '&id_produit=115&id_rubrique=23', 1, 0),
(194, '115-2015-boucles-d-oreilles-78.html', 'nexisteplus', '&id_produit=115&id_rubrique=23&ancienfond=produit', 1, 1),
(195, '116-2015-boucles-d-oreilles-78.html', 'produit', '&id_produit=116&id_rubrique=23', 1, 1),
(196, '117-2015-boucles-d-oreilles-79.html', 'produit', '&id_produit=117&id_rubrique=23', 1, 1),
(197, '118-2015-boucles-d-oreilles-80.html', 'produit', '&id_produit=118&id_rubrique=23', 1, 1),
(198, '119-colliers-colliers-3.html', 'produit', '&id_produit=119&id_rubrique=4', 1, 0),
(199, '119-colliers-colliers-3.html', 'nexisteplus', '&id_produit=119&id_rubrique=4&ancienfond=produit', 1, 1),
(200, '120-torcs-torcs-3.html', 'produit', '&id_produit=120&id_rubrique=19', 1, 1),
(201, '121-torcs-torcs-4.html', 'produit', '&id_produit=121&id_rubrique=19', 1, 0),
(202, '121-torcs-torcs-4.html', 'nexisteplus', '&id_produit=121&id_rubrique=19&ancienfond=produit', 1, 1),
(203, '122-torcs-torcs-4.html', 'produit', '&id_produit=122&id_rubrique=19', 1, 0),
(204, '122-torcs-torcs-4.html', 'nexisteplus', '&id_produit=122&id_rubrique=19&ancienfond=produit', 1, 1),
(205, '123-torcs-torcs-4.html', 'produit', '&id_produit=123&id_rubrique=19', 1, 0),
(206, '124-colliers-colliers-3.html', 'produit', '&id_produit=124&id_rubrique=4', 1, 0),
(207, '124-colliers-colliers-3.html', 'nexisteplus', '&id_produit=124&id_rubrique=4&ancienfond=produit', 1, 1),
(208, '125-colliers-colliers-3.html', 'produit', '&id_produit=125&id_rubrique=4', 1, 1),
(209, '126-colliers-collier-4.html', 'produit', '&id_produit=126&id_rubrique=4', 1, 1),
(210, '127-colliers-collier-5.html', 'produit', '&id_produit=127&id_rubrique=4', 1, 0),
(211, '128-colliers-collier-6.html', 'produit', '&id_produit=128&id_rubrique=4', 1, 1),
(212, '129-colliers-collier-7.html', 'produit', '&id_produit=129&id_rubrique=4', 1, 1),
(213, '130-colliers-collier-8.html', 'produit', '&id_produit=130&id_rubrique=4', 1, 0),
(214, '130-colliers-collier-8.html', 'nexisteplus', '&id_produit=130&id_rubrique=4&ancienfond=produit', 1, 1),
(215, '131-colliers-collier-8.html', 'produit', '&id_produit=131&id_rubrique=4', 1, 1),
(216, '132-colliers-collier-9.html', 'produit', '&id_produit=132&id_rubrique=4', 1, 0),
(217, '123-torcs-torcs-4.html', 'nexisteplus', '&id_produit=123&id_rubrique=19&ancienfond=produit', 1, 1),
(218, '133-pendentifs-pendentifs-1.html', 'produit', '&id_produit=133&id_rubrique=20', 1, 1),
(219, '134-pendentifs-pendentifs-2.html', 'produit', '&id_produit=134&id_rubrique=20', 1, 1),
(220, '135-pendentifs-pendentifs-3.html', 'produit', '&id_produit=135&id_rubrique=20', 1, 1),
(221, '136-pendentifs-pendentifs-4.html', 'produit', '&id_produit=136&id_rubrique=20', 1, 1),
(222, '137-pendentifs-pendentifs-5.html', 'produit', '&id_produit=137&id_rubrique=20', 1, 1),
(223, '138-pendentifs-pendentifs-6.html', 'produit', '&id_produit=138&id_rubrique=20', 1, 1),
(224, '139-pendentifs-pendentifs-7.html', 'produit', '&id_produit=139&id_rubrique=20', 1, 1),
(225, '140-pendentifs-pendentifs-8.html', 'produit', '&id_produit=140&id_rubrique=20', 1, 1),
(226, '141-pendentifs-pendentifs-9.html', 'produit', '&id_produit=141&id_rubrique=20', 1, 0),
(227, '141-pendentifs-pendentifs-9.html', 'nexisteplus', '&id_produit=141&id_rubrique=20&ancienfond=produit', 1, 1),
(228, '142-pendentifs-pendentifs-9.html', 'produit', '&id_produit=142&id_rubrique=20', 1, 1),
(229, '143-pendentifs-pendentifs-10.html', 'produit', '&id_produit=143&id_rubrique=20', 1, 1),
(230, '45-2010-boucles-d-oreilles-14.html', 'nexisteplus', '&id_produit=45&id_rubrique=13&ancienfond=produit', 1, 1),
(231, '144-2010-boucles-d-oreilles-14.html', 'produit', '&id_produit=144&id_rubrique=13', 1, 0),
(232, '145-2015-boucles-d-oreilles-81.html', 'produit', '&id_produit=145&id_rubrique=23', 1, 1),
(233, '127-colliers-collier-5.html', 'nexisteplus', '&id_produit=127&id_rubrique=4&ancienfond=produit', 1, 1),
(234, '132-colliers-collier-9.html', 'nexisteplus', '&id_produit=132&id_rubrique=4&ancienfond=produit', 1, 1),
(235, '21-boucles-d-oreilles-19.html', 'nexisteplus', '&id_rubrique=21&ancienfond=rubrique', 1, 1),
(236, '146-torcs-torcs-4.html', 'produit', '&id_produit=146&id_rubrique=19', 1, 0),
(237, '99-2013-boucles-d-oreilles-64.html', 'nexisteplus', '&id_produit=99&id_rubrique=17&ancienfond=produit', 1, 1),
(238, '147-2013-boucles-d-oreilles-64.html', 'produit', '&id_produit=147&id_rubrique=17', 1, 1),
(239, '148-2015-boucles-d-oreilles-82.html', 'produit', '&id_produit=148&id_rubrique=23', 1, 0),
(240, '148-2015-boucles-d-oreilles-82.html', 'nexisteplus', '&id_produit=148&id_rubrique=23&ancienfond=produit', 1, 1),
(241, '149-2015-boucles-d-oreilles-82.html', 'produit', '&id_produit=149&id_rubrique=23', 1, 1),
(242, '150-2015-boucles-d-oreilles-83.html', 'produit', '&id_produit=150&id_rubrique=23', 1, 0),
(243, '150-2015-boucles-d-oreilles-83.html', 'nexisteplus', '&id_produit=150&id_rubrique=23&ancienfond=produit', 1, 1),
(244, '151-2015-boucles-d-oreilles-83.html', 'produit', '&id_produit=151&id_rubrique=23', 1, 1),
(245, '152-2015-boucles-d-oreilles-84.html', 'produit', '&id_produit=152&id_rubrique=23', 1, 1),
(246, '153-2015-boucles-d-oreilles-85.html', 'produit', '&id_produit=153&id_rubrique=23', 1, 1),
(247, '154-2015-boucles-d-oreilles-86.html', 'produit', '&id_produit=154&id_rubrique=23', 1, 1),
(248, '155-2015-boucles-d-oreilles-87.html', 'produit', '&id_produit=155&id_rubrique=23', 1, 1),
(249, '156-2015-boucles-d-oreilles-88.html', 'produit', '&id_produit=156&id_rubrique=23', 1, 0),
(250, '156-2015-boucles-d-oreilles-88.html', 'nexisteplus', '&id_produit=156&id_rubrique=23&ancienfond=produit', 1, 1),
(251, '46-2010-boucles-d-oreilles-19.html', 'nexisteplus', '&id_produit=46&id_rubrique=13&ancienfond=produit', 1, 1),
(252, '157-2010-boucles-d-oreilles-19.html', 'produit', '&id_produit=157&id_rubrique=13', 1, 1),
(253, '44-2010-boucles-d-oreilles-18.html', 'nexisteplus', '&id_produit=44&id_rubrique=13&ancienfond=produit', 1, 1),
(254, '48-2010-boucles-d-oreilles-20.html', 'nexisteplus', '&id_produit=48&id_rubrique=13&ancienfond=produit', 1, 1),
(255, '158-2010-boucles-d-oreilles-20.html', 'produit', '&id_produit=158&id_rubrique=13', 1, 1),
(256, '159-2010-boucles-d-oreilles-18.html', 'produit', '&id_produit=159&id_rubrique=13', 1, 1),
(257, '144-2010-boucles-d-oreilles-14.html', 'nexisteplus', '&id_produit=144&id_rubrique=13&ancienfond=produit', 1, 1),
(258, '160-2010-boucles-d-oreilles-14.html', 'produit', '&id_produit=160&id_rubrique=13', 1, 0),
(259, '160-2010-boucles-d-oreilles-14.html', 'nexisteplus', '&id_produit=160&id_rubrique=13&ancienfond=produit', 1, 1),
(260, '161-2010-boucles-d-oreilles-14.html', 'produit', '&id_produit=161&id_rubrique=13', 1, 0),
(261, '161-2010-boucles-d-oreilles-14.html', 'nexisteplus', '&id_produit=161&id_rubrique=13&ancienfond=produit', 1, 1),
(262, '162-2010-boucles-d-oreilles-14.html', 'produit', '&id_produit=162&id_rubrique=13', 1, 0),
(263, '162-2010-boucles-d-oreilles-14.html', 'nexisteplus', '&id_produit=162&id_rubrique=13&ancienfond=produit', 1, 1),
(264, '163-2010-boucles-d-oreilles-14.html', 'produit', '&id_produit=163&id_rubrique=13', 1, 1),
(265, '68-2011-boucles-d-oreilles-37.html', 'nexisteplus', '&id_produit=68&id_rubrique=15&ancienfond=produit', 1, 1),
(266, '164-2011-boucles-d-oreilles-37.html', 'produit', '&id_produit=164&id_rubrique=15', 1, 1),
(267, '92-2013-boucles-d-oreilles-57.html', 'nexisteplus', '&id_produit=92&id_rubrique=17&ancienfond=produit', 1, 1),
(268, '165-2013-boucles-d-oreilles-57.html', 'produit', '&id_produit=165&id_rubrique=17', 1, 1),
(269, '146-torcs-torcs-4.html', 'nexisteplus', '&id_produit=146&id_rubrique=19&ancienfond=produit', 1, 1),
(270, '166-torcs-torcs-4.html', 'produit', '&id_produit=166&id_rubrique=19', 1, 1),
(271, '167-torcs-torcs-5.html', 'produit', '&id_produit=167&id_rubrique=19', 1, 0),
(272, '168-torcs-torcs-6.html', 'produit', '&id_produit=168&id_rubrique=19', 1, 0),
(273, '168-torcs-torcs-6.html', 'nexisteplus', '&id_produit=168&id_rubrique=19&ancienfond=produit', 1, 1),
(274, '167-torcs-torcs-5.html', 'nexisteplus', '&id_produit=167&id_rubrique=19&ancienfond=produit', 1, 1),
(275, '169-colliers-colliers-9.html', 'produit', '&id_produit=169&id_rubrique=4', 1, 1),
(276, '170-colliers-colliers-10.html', 'produit', '&id_produit=170&id_rubrique=4', 1, 0),
(277, '171-2015-boucles-d-oreilles-88.html', 'produit', '&id_produit=171&id_rubrique=23', 1, 1),
(278, '172-torcs-torcs-5.html', 'produit', '&id_produit=172&id_rubrique=19', 1, 1),
(279, '173-torcs-torcs-6.html', 'produit', '&id_produit=173&id_rubrique=19', 1, 1),
(280, '174-2008-boucles-d-oreilles-89.html', 'produit', '&id_produit=174&id_rubrique=14', 1, 0),
(281, '174-2008-boucles-d-oreilles-89.html', 'nexisteplus', '&id_produit=174&id_rubrique=14&ancienfond=produit', 1, 1),
(282, '175-2008-boucles-d-oreilles-89.html', 'produit', '&id_produit=175&id_rubrique=14', 1, 1),
(283, '176-2008-boucles-d-oreilles-90.html', 'produit', '&id_produit=176&id_rubrique=14', 1, 1),
(284, '177-2008-boucles-d-oreilles-91.html', 'produit', '&id_produit=177&id_rubrique=14', 1, 0),
(285, '178-2008-boucles-d-oreilles-92.html', 'produit', '&id_produit=178&id_rubrique=14', 1, 1),
(286, '179-2008-boucles-d-oreilles-93.html', 'produit', '&id_produit=179&id_rubrique=14', 1, 1),
(287, '180-2008-boucles-d-oreilles-94.html', 'produit', '&id_produit=180&id_rubrique=14', 1, 1),
(288, '181-2008-boucles-d-oreilles-95.html', 'produit', '&id_produit=181&id_rubrique=14', 1, 1),
(289, '182-2008-boucles-d-oreilles-96.html', 'produit', '&id_produit=182&id_rubrique=14', 1, 1),
(290, '183-2008-boucles-d-oreilles-97.html', 'produit', '&id_produit=183&id_rubrique=14', 1, 1),
(291, '184-2008-boucles-d-oreilles-98.html', 'produit', '&id_produit=184&id_rubrique=14', 1, 1),
(292, '185-2008-boucles-d-oreilles-99.html', 'produit', '&id_produit=185&id_rubrique=14', 1, 1),
(293, '186-2008-boucles-d-oreilles-100.html', 'produit', '&id_produit=186&id_rubrique=14', 1, 0),
(294, '186-2008-boucles-d-oreilles-100.html', 'nexisteplus', '&id_produit=186&id_rubrique=14&ancienfond=produit', 1, 1),
(295, '187-2008-boucles-d-oreilles-100.html', 'produit', '&id_produit=187&id_rubrique=14', 1, 0),
(296, '187-2008-boucles-d-oreilles-100.html', 'nexisteplus', '&id_produit=187&id_rubrique=14&ancienfond=produit', 1, 1),
(297, '188-2008-boucles-d-oreilles-100.html', 'produit', '&id_produit=188&id_rubrique=14', 1, 1),
(298, '189-2008-boucles-d-oreilles-101.html', 'produit', '&id_produit=189&id_rubrique=14', 1, 0),
(299, '190--.html', 'produit', '&id_produit=190&id_rubrique=0', 1, 0),
(300, '189-2008-boucles-d-oreilles-101.html', 'nexisteplus', '&id_produit=189&id_rubrique=14&ancienfond=produit', 1, 1),
(301, '177-2008-boucles-d-oreilles-91.html', 'nexisteplus', '&id_produit=177&id_rubrique=14&ancienfond=produit', 1, 1),
(302, '191-2008-boucles-d-oreilles-91.html', 'produit', '&id_produit=191&id_rubrique=14', 1, 1),
(303, '192-2008-boucles-d-oreilles-101.html', 'produit', '&id_produit=192&id_rubrique=14', 1, 1),
(304, '193-2008-boucles-d-oreilles-102.html', 'produit', '&id_produit=193&id_rubrique=14', 1, 1),
(305, '194-2008-boucles-d-oreilles-103.html', 'produit', '&id_produit=194&id_rubrique=14', 1, 1),
(306, '195-2008-boucles-d-oreilles-104.html', 'produit', '&id_produit=195&id_rubrique=14', 1, 1),
(307, '196-2008-boucles-d-oreilles-105.html', 'produit', '&id_produit=196&id_rubrique=14', 1, 1),
(308, '197-2012-boucles-d-oreilles-106.html', 'produit', '&id_produit=197&id_rubrique=16', 1, 1),
(309, '', 'nexisteplus', '&ancienfond=', 0, 1),
(310, '', 'nexisteplus', '&ancienfond=', 0, 1),
(311, '26-test-carac-bo.html', 'rubrique', '&id_rubrique=26', 1, 0),
(312, '27-enfanr-carac-bo.html', 'rubrique', '&id_rubrique=27', 1, 0),
(313, '198-enfanr-carac-bo-test-carac-par-defaut.html', 'produit', '&id_produit=198&id_rubrique=27', 1, 0),
(314, '26-test-carac-bo.html', 'nexisteplus', '&id_rubrique=26&ancienfond=rubrique', 1, 1),
(315, '198-enfanr-carac-bo-test-carac-par-defaut.html', 'nexisteplus', '&id_produit=198&id_rubrique=27&ancienfond=produit', 1, 1),
(316, '27-enfanr-carac-bo.html', 'nexisteplus', '&id_rubrique=27&ancienfond=rubrique', 1, 1),
(317, '190--.html', 'nexisteplus', '&id_produit=190&id_rubrique=0&ancienfond=produit', 1, 1),
(318, '199-2008-test-autoref.html', 'produit', '&id_produit=199&id_rubrique=14', 1, 0),
(319, '199-2008-test-autoref.html', 'nexisteplus', '&id_produit=199&id_rubrique=14&ancienfond=produit', 1, 1),
(320, '3--images.html', 'contenu', '&id_contenu=3&id_dossier=0', 1, 0),
(321, '15-collages-collage-2.html', 'nexisteplus', '&id_produit=15&id_rubrique=1&ancienfond=produit', 1, 1),
(322, '9-tous-les-produits.html', 'nexisteplus', '&id_rubrique=9&ancienfond=rubrique', 1, 1),
(323, '24-accueil.html', 'rubrique', '&id_rubrique=24', 1, 0),
(324, '25-boutique.html', 'rubrique', '&id_rubrique=25', 1, 0),
(325, '26-contact.html', 'rubrique', '&id_rubrique=26', 1, 0),
(326, '27-gallerie.html', 'rubrique', '&id_rubrique=27', 1, 0),
(327, '28-bijoux.html', 'rubrique', '&id_rubrique=28', 1, 0),
(328, '29-collages.html', 'rubrique', '&id_rubrique=29', 1, 0),
(329, '170-colliers-colliers-10.html', 'nexisteplus', '&id_produit=170&id_rubrique=4&ancienfond=produit', 1, 1),
(330, '102-boucles-d-oreilles-boucles-d-oreilles-102.html', 'produit', '&id_produit=102&id_rubrique=3', 1, 1),
(331, '30-plastron-perles.html', 'rubrique', '&id_rubrique=30', 1, 1),
(332, '198-collages-collages-198.html', 'produit', '&id_produit=198&id_rubrique=1', 1, 0),
(333, '2-collages-collage-1.html', 'nexisteplus', '&id_produit=2&id_rubrique=1&ancienfond=produit', 1, 1),
(334, '199-collages-collages-199.html', 'produit', '&id_produit=199&id_rubrique=1', 1, 0),
(335, '200-collages-collages-200.html', 'produit', '&id_produit=200&id_rubrique=1', 1, 0),
(336, '201-collages-collages-201.html', 'produit', '&id_produit=201&id_rubrique=1', 1, 0),
(337, '202-collages-collages-202.html', 'produit', '&id_produit=202&id_rubrique=1', 1, 0),
(338, '203-collages-collages-203.html', 'produit', '&id_produit=203&id_rubrique=1', 1, 0),
(339, '204-collages-collages-204.html', 'produit', '&id_produit=204&id_rubrique=1', 1, 0),
(340, '205-collages-collages-205.html', 'produit', '&id_produit=205&id_rubrique=1', 1, 0),
(341, '206-collages-collages-206.html', 'produit', '&id_produit=206&id_rubrique=1', 1, 0),
(342, '207-collages-collages-207.html', 'produit', '&id_produit=207&id_rubrique=1', 1, 0),
(343, '208-collages-collages-208.html', 'produit', '&id_produit=208&id_rubrique=1', 1, 0),
(344, '209-collages-collages-209.html', 'produit', '&id_produit=209&id_rubrique=1', 1, 0),
(345, '210-collages-collages-210.html', 'produit', '&id_produit=210&id_rubrique=1', 1, 0),
(346, '211-collages-collages-211.html', 'produit', '&id_produit=211&id_rubrique=1', 1, 0),
(347, '212-collages-collages-212.html', 'produit', '&id_produit=212&id_rubrique=1', 1, 0),
(348, '213-collages-collages-213.html', 'produit', '&id_produit=213&id_rubrique=1', 1, 0),
(349, '214-collages-collages-214.html', 'produit', '&id_produit=214&id_rubrique=1', 1, 0),
(350, '215-collages-collages-215.html', 'produit', '&id_produit=215&id_rubrique=1', 1, 0),
(351, '216-collages-collages-216.html', 'produit', '&id_produit=216&id_rubrique=1', 1, 0),
(352, '217-collages-collages-217.html', 'produit', '&id_produit=217&id_rubrique=1', 1, 0),
(353, '218-collages-collages-218.html', 'produit', '&id_produit=218&id_rubrique=1', 1, 0),
(354, '219-collages-collages-219.html', 'produit', '&id_produit=219&id_rubrique=1', 1, 0),
(355, '220-collages-collages-220.html', 'produit', '&id_produit=220&id_rubrique=1', 1, 0),
(356, '221-collages-collages-221.html', 'produit', '&id_produit=221&id_rubrique=1', 1, 0),
(357, '221-collages-collages-221.html', 'nexisteplus', '&id_produit=221&id_rubrique=1&ancienfond=produit', 1, 1),
(358, '222-collages-collages-222.html', 'produit', '&id_produit=222&id_rubrique=1', 1, 0),
(359, '222-collages-collages-222.html', 'nexisteplus', '&id_produit=222&id_rubrique=1&ancienfond=produit', 1, 1),
(360, '223-plastron-perles-plastron-perles-223.html', 'produit', '&id_produit=223&id_rubrique=30', 1, 1),
(361, '224-plastron-perles-plastron-perles-224.html', 'produit', '&id_produit=224&id_rubrique=30', 1, 1),
(362, '225-plastron-perles-plastron-perles-225.html', 'produit', '&id_produit=225&id_rubrique=30', 1, 1),
(363, '226-plastron-perles-plastron-perles-226.html', 'produit', '&id_produit=226&id_rubrique=30', 1, 0),
(364, '226-plastron-perles-plastron-perles-226.html', 'nexisteplus', '&id_produit=226&id_rubrique=30&ancienfond=produit', 1, 1),
(365, '227-plastron-perles-plastron-perles-227.html', 'produit', '&id_produit=227&id_rubrique=30', 1, 1),
(366, '228-plastron-perles-plastron-perles-228.html', 'produit', '&id_produit=228&id_rubrique=30', 1, 1),
(367, '229-plastron-perles-plastron-perles-229.html', 'produit', '&id_produit=229&id_rubrique=30', 1, 1),
(368, '230-plastron-perles-plastron-perles-230.html', 'produit', '&id_produit=230&id_rubrique=30', 1, 1),
(369, '231-plastron-perles-plastron-perles-231.html', 'produit', '&id_produit=231&id_rubrique=30', 1, 1),
(370, '232-plastron-perles-plastron-perles-232.html', 'produit', '&id_produit=232&id_rubrique=30', 1, 1),
(371, '233-plastron-perles-plastron-perles-233.html', 'produit', '&id_produit=233&id_rubrique=30', 1, 1),
(372, '234-plastron-perles-plastron-perles-234.html', 'produit', '&id_produit=234&id_rubrique=30', 1, 1),
(373, '235-plastron-perles-plastron-perles-235.html', 'produit', '&id_produit=235&id_rubrique=30', 1, 1),
(374, '236-plastron-perles-plastron-perles-236.html', 'produit', '&id_produit=236&id_rubrique=30', 1, 1),
(375, '237-plastron-perles-plastron-perles-237.html', 'produit', '&id_produit=237&id_rubrique=30', 1, 1),
(376, '238-plastron-perles-plastron-perles-238.html', 'produit', '&id_produit=238&id_rubrique=30', 1, 1),
(377, '239-plastron-perles-plastron-perles-239.html', 'produit', '&id_produit=239&id_rubrique=30', 1, 1),
(378, '240-torques-torques-240.html', 'produit', '&id_produit=240&id_rubrique=19', 1, 1),
(379, '152-boucles-d-oreilles-boucles-d-oreilles-152.html', 'produit', '&id_produit=152&id_rubrique=3', 1, 1),
(380, '106-boucles-d-oreilles-boucles-d-oreilles-106.html', 'produit', '&id_produit=106&id_rubrique=3', 1, 1),
(381, '24-accueil.html', 'nexisteplus', '&id_rubrique=24&ancienfond=rubrique', 1, 1),
(382, '25-boutique.html', 'nexisteplus', '&id_rubrique=25&ancienfond=rubrique', 1, 1),
(383, '199-collages-collages-199.html', 'nexisteplus', '&id_produit=199&id_rubrique=1&ancienfond=produit', 1, 1),
(384, '198-collages-collages-198.html', 'nexisteplus', '&id_produit=198&id_rubrique=1&ancienfond=produit', 1, 1),
(385, '200-collages-collages-200.html', 'nexisteplus', '&id_produit=200&id_rubrique=1&ancienfond=produit', 1, 1),
(386, '201-collages-collages-201.html', 'nexisteplus', '&id_produit=201&id_rubrique=1&ancienfond=produit', 1, 1),
(387, '202-collages-collages-202.html', 'nexisteplus', '&id_produit=202&id_rubrique=1&ancienfond=produit', 1, 1),
(388, '203-collages-collages-203.html', 'nexisteplus', '&id_produit=203&id_rubrique=1&ancienfond=produit', 1, 1),
(389, '204-collages-collages-204.html', 'nexisteplus', '&id_produit=204&id_rubrique=1&ancienfond=produit', 1, 1),
(390, '205-collages-collages-205.html', 'nexisteplus', '&id_produit=205&id_rubrique=1&ancienfond=produit', 1, 1),
(391, '206-collages-collages-206.html', 'nexisteplus', '&id_produit=206&id_rubrique=1&ancienfond=produit', 1, 1),
(392, '207-collages-collages-207.html', 'nexisteplus', '&id_produit=207&id_rubrique=1&ancienfond=produit', 1, 1),
(393, '208-collages-collages-208.html', 'nexisteplus', '&id_produit=208&id_rubrique=1&ancienfond=produit', 1, 1),
(394, '209-collages-collages-209.html', 'nexisteplus', '&id_produit=209&id_rubrique=1&ancienfond=produit', 1, 1),
(395, '210-collages-collages-210.html', 'nexisteplus', '&id_produit=210&id_rubrique=1&ancienfond=produit', 1, 1),
(396, '211-collages-collages-211.html', 'nexisteplus', '&id_produit=211&id_rubrique=1&ancienfond=produit', 1, 1),
(397, '212-collages-collages-212.html', 'nexisteplus', '&id_produit=212&id_rubrique=1&ancienfond=produit', 1, 1),
(398, '213-collages-collages-213.html', 'nexisteplus', '&id_produit=213&id_rubrique=1&ancienfond=produit', 1, 1),
(399, '214-collages-collages-214.html', 'nexisteplus', '&id_produit=214&id_rubrique=1&ancienfond=produit', 1, 1),
(400, '215-collages-collages-215.html', 'nexisteplus', '&id_produit=215&id_rubrique=1&ancienfond=produit', 1, 1),
(401, '216-collages-collages-216.html', 'nexisteplus', '&id_produit=216&id_rubrique=1&ancienfond=produit', 1, 1),
(402, '217-collages-collages-217.html', 'nexisteplus', '&id_produit=217&id_rubrique=1&ancienfond=produit', 1, 1),
(403, '218-collages-collages-218.html', 'nexisteplus', '&id_produit=218&id_rubrique=1&ancienfond=produit', 1, 1),
(404, '219-collages-collages-219.html', 'nexisteplus', '&id_produit=219&id_rubrique=1&ancienfond=produit', 1, 1),
(405, '220-collages-collages-220.html', 'nexisteplus', '&id_produit=220&id_rubrique=1&ancienfond=produit', 1, 1),
(406, '1-collages.html', 'nexisteplus', '&id_rubrique=1&ancienfond=rubrique', 1, 1),
(407, '2-bijoux.html', 'nexisteplus', '&id_rubrique=2&ancienfond=rubrique', 1, 1),
(408, '27-gallerie.html', 'nexisteplus', '&id_rubrique=27&ancienfond=rubrique', 1, 1),
(409, '28-bijoux.html', 'nexisteplus', '&id_rubrique=28&ancienfond=rubrique', 1, 1),
(410, '29-collages.html', 'nexisteplus', '&id_rubrique=29&ancienfond=rubrique', 1, 1),
(411, '26-contact.html', 'nexisteplus', '&id_rubrique=26&ancienfond=rubrique', 1, 1),
(412, '2-mes-contenus-la-boutique-ketmie.html', 'nexisteplus', '&id_contenu=2&id_dossier=2&ancienfond=contenu', 1, 1),
(413, '2-index.html', 'nexisteplus', '&id_dossier=2&ancienfond=dossier', 1, 1),
(414, '3--images.html', 'nexisteplus', '&id_contenu=3&id_dossier=0&ancienfond=contenu', 1, 1),
(415, '3-cms.html', 'dossier', '&id_dossier=3', 1, 0),
(416, '4--conditions-generales-de-vente.html', 'contenu', '&id_contenu=4&id_dossier=0', 1, 1),
(417, '5--informations-legales.html', 'contenu', '&id_contenu=5&id_dossier=0', 1, 1),
(418, '6--stephanie-mousquey.html', 'contenu', '&id_contenu=6&id_dossier=0', 1, 1),
(419, '3-cms.html', 'nexisteplus', '&id_dossier=3&ancienfond=dossier', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `rubcaracteristique`
--

CREATE TABLE `rubcaracteristique` (
  `id` int(11) NOT NULL,
  `rubrique` int(11) NOT NULL DEFAULT '0',
  `caracteristique` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `rubcaracteristique`
--

INSERT INTO `rubcaracteristique` (`id`, `rubrique`, `caracteristique`) VALUES
(104, 3, 6);

-- --------------------------------------------------------

--
-- Structure de la table `rubdeclinaison`
--

CREATE TABLE `rubdeclinaison` (
  `id` int(11) NOT NULL,
  `rubrique` int(11) NOT NULL DEFAULT '0',
  `declinaison` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `rubdeclinaison`
--

INSERT INTO `rubdeclinaison` (`id`, `rubrique`, `declinaison`) VALUES
(5, 3, 0);

-- --------------------------------------------------------

--
-- Structure de la table `rubrique`
--

CREATE TABLE `rubrique` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `lien` text NOT NULL,
  `ligne` smallint(6) NOT NULL DEFAULT '0',
  `classement` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `rubrique`
--

INSERT INTO `rubrique` (`id`, `parent`, `lien`, `ligne`, `classement`) VALUES
(3, 0, 'BO', 1, 1),
(4, 0, 'COL', 1, 2),
(19, 0, 'TOR', 1, 3),
(20, 0, 'PEN', 0, 4),
(30, 0, 'PLP', 1, 5);

-- --------------------------------------------------------

--
-- Structure de la table `rubriquedesc`
--

CREATE TABLE `rubriquedesc` (
  `id` int(11) NOT NULL,
  `rubrique` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `postscriptum` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `rubriquedesc`
--

INSERT INTO `rubriquedesc` (`id`, `rubrique`, `lang`, `titre`, `chapo`, `description`, `postscriptum`) VALUES
(3, 3, 1, 'Boucles d\'oreilles', '', '<p>Les boucles d\'oreilles sont en zinc (m&eacute;tal gris), finition bros&eacute;e. Disponibles en diff&eacute;rentes tailles. Attaches en argent.</p>', ''),
(4, 4, 1, 'Colliers', '', '<p>Les pi&egrave;ces mont&eacute;es sur les colliers sont en zinc, finition bross&eacute;e. Les liens peuvent &ecirc;tre en ruban de soie, simple cordon ou tube souple en plastique.</p>', ''),
(19, 19, 1, 'Torques', '', '<p>Les torques ont une base en aluminium agr&eacute;ment&eacute;e de pi&egrave;ces en zinc bross&eacute; ou de perles.</p>', ''),
(20, 20, 1, 'Pendentifs', '', '<p>Motifs d&eacute;coup&eacute;s dans du zinc, finition bross&eacute;e. Mont&eacute; sur une chaine.</p>', ''),
(30, 30, 1, 'Plastrons perles', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `smtpconfig`
--

CREATE TABLE `smtpconfig` (
  `id` int(11) NOT NULL,
  `serveur` varchar(255) NOT NULL,
  `port` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `secure` varchar(30) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `smtpconfig`
--

INSERT INTO `smtpconfig` (`id`, `serveur`, `port`, `username`, `password`, `secure`, `active`) VALUES
(1, 'ssl0.ovh.net', 465, 'stephanie@ketmie.com', 'MbwYkuMd', '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

CREATE TABLE `statut` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `statut`
--

INSERT INTO `statut` (`id`, `nom`) VALUES
(1, 'nonpaye'),
(2, 'paye'),
(3, 'traitement'),
(4, 'envoye'),
(5, 'annule');

-- --------------------------------------------------------

--
-- Structure de la table `statutdesc`
--

CREATE TABLE `statutdesc` (
  `id` int(11) NOT NULL,
  `statut` int(11) NOT NULL DEFAULT '0',
  `lang` int(11) NOT NULL DEFAULT '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `statutdesc`
--

INSERT INTO `statutdesc` (`id`, `statut`, `lang`, `titre`, `chapo`, `description`) VALUES
(23, 1, 1, 'Non payé', '', ''),
(24, 2, 1, 'payé', '', ''),
(25, 3, 1, 'Traitement', '', ''),
(26, 4, 1, 'Envoyé', '', ''),
(27, 5, 1, 'Annulé', '', ''),
(28, 1, 2, 'Not paid', '', ''),
(29, 2, 2, 'Paid', '', ''),
(30, 3, 2, 'Processed', '', ''),
(31, 4, 2, 'Sent', '', ''),
(32, 5, 2, 'Canceled', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `declidisp` int(11) NOT NULL DEFAULT '0',
  `produit` int(11) NOT NULL DEFAULT '0',
  `valeur` int(11) NOT NULL DEFAULT '0',
  `surplus` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `transzone`
--

CREATE TABLE `transzone` (
  `id` int(11) NOT NULL,
  `transport` int(11) NOT NULL DEFAULT '0',
  `zone` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `transzone`
--

INSERT INTO `transzone` (`id`, `transport`, `zone`) VALUES
(1, 2, 1),
(2, 19, 1),
(3, 19, 2),
(10, 19, 10),
(11, 19, 11),
(12, 19, 3),
(13, 19, 4),
(14, 19, 5),
(15, 19, 6),
(16, 19, 7),
(17, 19, 8),
(18, 19, 9);

-- --------------------------------------------------------

--
-- Structure de la table `variable`
--

CREATE TABLE `variable` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `valeur` text NOT NULL,
  `protege` smallint(6) NOT NULL,
  `cache` smallint(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `variable`
--

INSERT INTO `variable` (`id`, `nom`, `valeur`, `protege`, `cache`) VALUES
(1, 'emailcontact', 'stephanie@ketmie.com', 1, 0),
(2, 'emailfrom', 'stephanie@ketmie.com', 1, 0),
(3, 'nomsite', 'Ketmie', 1, 0),
(4, 'urlsite', 'http://localhost:4501', 0, 1),
(5, 'tva', '19.6', 1, 0),
(6, 'style_chem', '/template/style_editeur.css', 0, 0),
(7, 'rsspass', 'nxYSQwGOR5ipm7EtLJdKABlF6N0hP93MoruIXWyq', 1, 0),
(8, 'memcache', '0', 1, 0),
(9, 'version', '1534', 1, 1),
(10, 'rewrite', '0', 1, 0),
(11, 'prx_show_time', '0', 0, 1),
(12, 'prx_use_cache', '1', 0, 1),
(13, 'prx_allow_debug', '1', 0, 1),
(14, 'prx_cache_file_lifetime', '240', 0, 1),
(15, 'prx_cache_check_period', '240', 0, 1),
(16, 'prx_cache_check_time', '1516896966', 0, 1),
(17, 'verifstock', '0', 1, 0),
(18, 'un_domaine_par_langue', '0', 0, 1),
(19, 'action_si_trad_absente', '1', 0, 1),
(20, 'utilisercacheplugin', '0', 1, 0),
(21, 'emailscommande', 'stephanie@ketmie.com', 1, 0),
(22, 'sanitize_admin', '0', 1, 1),
(23, 'htmlpurifier_whiteList', 'www.youtube.com/embed/\nplayer.vimeo.com/video/\nmaps.google.*/', 1, 1),
(24, 'tlog_destinationhtml_style', 'text-align: left; font-size: 12px; font-weight: normal; line-height: 14px; float: none; display:block; color: #000; background-color: #fff; font-family: Courier New, courier,fixed;', 1, 1),
(25, 'tlog_mail_adresses', 'commande@monsite.com', 1, 1),
(26, 'tlog_destinationpopup_template', '<!--\r\nTemplate par defaut pour la destination TlogDestinationPopup\r\n-->\r\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\r\n<head>\r\n    <title>Debug THELIA</title>\r\n\r\n	<style type=\"text/css\">\r\n\r\n	body, h1, h2, td, th, p {\r\n	    font-family: \"Courier New\", courier, fixed;\r\n	    font-weight: normal;\r\n	    font-size: 0.9em;\r\n	    margin: 0;\r\n	    padding: 0;\r\n	}\r\n\r\n	h1 {\r\n	    background-color: #868F99;\r\n	    border-bottom: 2px solid #127AED;\r\n	    color: #FFFFFF;\r\n	    font-family: Arial,Helvetica,sans-serif;\r\n	    font-weight: bold;\r\n	    line-height: 20px;\r\n	    padding: 5px;\r\n	}\r\n\r\n	pre {\r\n		margin: 0;\r\n	}\r\n\r\n	.paire {\r\n		background-color: #EBEDEE;\r\n		padding: 5px;\r\n		border-bottom: 1px dotted #fff;\r\n	}\r\n\r\n	.impaire {\r\n		background-color: #D4DADD;\r\n		padding: 5px;\r\n		border-bottom: 1px dotted #fff;\r\n	}\r\n	</style>\r\n</head>\r\n\r\n<body>\r\n	<h1>Thelia Debug</h1>\r\n	<pre>#DEBUGTEXT</pre>\r\n</body>\r\n</html>\r\n', 1, 1),
(27, 'tlog_destinationpopup_height', '600', 1, 1),
(28, 'tlog_destinationpopup_width', '600', 1, 1),
(29, 'tlog_destinationfichier_path', '/var/www/html/client/tlog/log-thelia.txt', 1, 1),
(40, 'emailnoreply', 'noreply@ketmie.com', 0, 0),
(30, 'tlog_destinationfichier_mode', 'A', 1, 1),
(31, 'tlog_niveau', '2', 1, 1),
(32, 'tlog_prefixe', '#NUM: #NIVEAU [#FICHIER:#FONCTION()] {#LIGNE} #DATE #HEURE: ', 1, 1),
(33, 'tlog_show_redirect', '0', 1, 1),
(34, 'tlog_ip', '', 1, 1),
(35, 'tlog_files', '*', 1, 1),
(36, 'tlog_destinations', 'TlogDestinationNull;TlogDestinationFichier', 1, 1),
(37, 'passphrase', 'kj2#lk mt8; c2o', 0, 0),
(38, 'mailtemplateid', '7', 0, 0),
(39, 'proddebug', '0', 0, 0),
(41, 'qualite_vignettes_png', '9', 0, 0),
(42, 'qualite_vignettes_jpeg', '100', 0, 0),
(43, 'thumbsize', '90', 0, 0),
(44, 'numnews', '6', 0, 0),
(45, 'prix-collage', '130', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `venteadr`
--

CREATE TABLE `venteadr` (
  `id` int(11) NOT NULL,
  `raison` smallint(6) NOT NULL DEFAULT '0',
  `entreprise` text NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `adresse1` varchar(40) NOT NULL DEFAULT '',
  `adresse2` varchar(40) NOT NULL DEFAULT '',
  `adresse3` varchar(40) NOT NULL DEFAULT '',
  `cpostal` varchar(10) NOT NULL DEFAULT '',
  `ville` varchar(30) NOT NULL DEFAULT '',
  `tel` text NOT NULL,
  `pays` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `venteadr`
--

INSERT INTO `venteadr` (`id`, `raison`, `entreprise`, `nom`, `prenom`, `adresse1`, `adresse2`, `adresse3`, `cpostal`, `ville`, `tel`, `pays`) VALUES
(97, 1, '', 'Debug-Client', 'John', '19 rue du parc', 'résidence X', 'Comp n°2', '75900', 'Paris IX', '0124356854  0624356854', 64),
(98, 3, '', 'Volt', 'Raphaël', '23 place Jules Ferry', 'Résidence Rohan', 'Bâtiement Nord , étage 1', '56100', 'LORIENT', '0674654493', 64),
(99, 1, '', 'Debug-Client', 'John', '19 rue du parc', 'résidence X', 'Comp n°2', '75900', 'Paris IX', '0124356854  0624356854', 64),
(100, 1, '', 'Debug-Client', 'John', '19 rue du parc', 'résidence X', 'Comp n°2', '75900', 'Paris IX', '0124356854  0624356854', 64),
(101, 1, '', 'Debug-Client', 'John', '19 rue du parc', 'résidence X', 'Comp n°2', '75900', 'Paris IX', '0124356854  0624356854', 64),
(102, 1, '', 'Debug-Client', 'John', '19 rue du parc', 'résidence X', 'Comp n°2', '75900', 'Paris IX', '0124356854  0624356854', 64),
(103, 1, '', 'Debug-Client', 'John', '19 rue du parc', 'résidence X', 'Comp n°2', '75900', 'Paris IX', '0124356854  0624356854', 64),
(104, 1, '', 'Debug-Client', 'John', '19 rue du parc', 'résidence X', 'Comp n°2', '75900', 'Paris IX', '0124356854  0624356854', 64),
(105, 1, '', 'Nouveau', 'Clien', 'mlkmmlk', '', '', '29000', 'Qoiuu', '000000000  ', 64),
(106, 1, '', 'Nouveau', 'Clien', 'mlkmmlk', '', '', '29000', 'Qoiuu', '000000000  ', 64),
(107, 1, '', 'kjh', 'kjh', 'kjh', 'kjh', 'kjh', '200', 'kjh', '00000  00000', 64),
(108, 1, '', 'kjh', 'kjh', 'kjh', 'kjh', 'kjh', '200', 'kjh', '00000  00000', 64);

-- --------------------------------------------------------

--
-- Structure de la table `ventedeclidisp`
--

CREATE TABLE `ventedeclidisp` (
  `id` int(11) NOT NULL,
  `venteprod` int(11) NOT NULL,
  `declidisp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ventedeclidisp`
--

INSERT INTO `ventedeclidisp` (`id`, `venteprod`, `declidisp`) VALUES
(50, 114, 22),
(51, 118, 24),
(52, 120, 23);

-- --------------------------------------------------------

--
-- Structure de la table `venteprod`
--

CREATE TABLE `venteprod` (
  `id` int(11) NOT NULL,
  `ref` text NOT NULL,
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT '0',
  `prixu` float NOT NULL DEFAULT '0',
  `tva` float NOT NULL DEFAULT '0',
  `commande` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `venteprod`
--

INSERT INTO `venteprod` (`id`, `ref`, `titre`, `chapo`, `description`, `quantite`, `prixu`, `tva`, `commande`, `parent`) VALUES
(114, 'BJX-BO-0085', 'Boucles d\'oreilles 52 <br />zinc | 6.00 cm ', '', '', 1, 55, 19.6, 49, 0),
(115, 'BJX-PEN-0139', 'Pendentifs 7 <br />', '', '', 1, 65, 19.6, 49, 0),
(116, 'CLG-0002', 'Collage 1 <br />', '', '', 1, 220, 19.6, 49, 0),
(117, 'BJX-PEN-0139', 'Pendentifs 139 <br />', '', '', 1, 65, 19.6, 50, 0),
(118, 'BJX-BO-0112', 'Boucles d\'oreilles 112 <br />zinc | 4.00 cm ', '', '', 1, 52, 19.6, 51, 0),
(119, 'BJX-PEN-0136', 'Pendentifs 136 <br />', '', '', 1, 50, 19.6, 52, 0),
(120, 'BJX-BO-0033', 'Boucles d\'oreilles 33 <br />zinc | 6.00 cm ', '', '', 1, 54, 19.6, 53, 0),
(121, 'CLG-0207', 'Collages 207 <br />', '', '', 1, 130, 19.6, 54, 0);

-- --------------------------------------------------------

--
-- Structure de la table `zone`
--

CREATE TABLE `zone` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `unite` float NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `zone`
--

INSERT INTO `zone` (`id`, `nom`, `unite`) VALUES
(1, 'France', 0),
(2, 'inter zone 1', 0),
(3, 'inter Zone 2', 0),
(4, 'inter Zone 3', 0),
(5, 'inter Zone 4', 0),
(6, 'inter Zone 5', 0),
(7, 'inter Zone 6', 0),
(8, 'inter Zone 7', 0),
(9, 'inter Zone 8', 0),
(10, 'Outre-Mer DOM', 0),
(11, 'Outre-Mer TOM', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accessoire`
--
ALTER TABLE `accessoire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produit` (`produit`);

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client` (`client`);

--
-- Index pour la table `autorisation`
--
ALTER TABLE `autorisation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `autorisationdesc`
--
ALTER TABLE `autorisationdesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autorisation` (`autorisation`,`lang`);

--
-- Index pour la table `autorisation_administrateur`
--
ALTER TABLE `autorisation_administrateur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `administrateur` (`administrateur`),
  ADD KEY `autorisation` (`autorisation`);

--
-- Index pour la table `autorisation_modules`
--
ALTER TABLE `autorisation_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `administrateur` (`administrateur`),
  ADD KEY `module` (`module`);

--
-- Index pour la table `autorisation_profil`
--
ALTER TABLE `autorisation_profil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil` (`profil`),
  ADD KEY `autorisation` (`autorisation`);

--
-- Index pour la table `bo_carac`
--
ALTER TABLE `bo_carac`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `caracdisp`
--
ALTER TABLE `caracdisp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caracteristique` (`caracteristique`);

--
-- Index pour la table `caracdispdesc`
--
ALTER TABLE `caracdispdesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caracdisp` (`caracdisp`,`lang`);

--
-- Index pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `affiche` (`affiche`);

--
-- Index pour la table `caracteristiquedesc`
--
ALTER TABLE `caracteristiquedesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caracteristique` (`caracteristique`,`lang`);

--
-- Index pour la table `caracval`
--
ALTER TABLE `caracval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caracteristique` (`caracteristique`,`caracdisp`),
  ADD KEY `caracteristique_valeur` (`caracteristique`,`valeur`(64)),
  ADD KEY `caracteristique_produit` (`caracteristique`,`produit`);

--
-- Index pour la table `carac_price`
--
ALTER TABLE `carac_price`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref` (`ref`(30));

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client` (`client`),
  ADD KEY `ref` (`ref`(30));

--
-- Index pour la table `contenu`
--
ALTER TABLE `contenu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dossier` (`dossier`),
  ADD KEY `ligne` (`ligne`),
  ADD KEY `id_ligne` (`id`,`ligne`);

--
-- Index pour la table `contenuassoc`
--
ALTER TABLE `contenuassoc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `objet` (`objet`,`type`,`contenu`);

--
-- Index pour la table `contenudesc`
--
ALTER TABLE `contenudesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contenu` (`contenu`,`lang`);
ALTER TABLE `contenudesc` ADD FULLTEXT KEY `recherche` (`titre`,`chapo`,`description`,`postscriptum`);

--
-- Index pour la table `dbtest`
--
ALTER TABLE `dbtest`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `declidisp`
--
ALTER TABLE `declidisp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `declinaison` (`declinaison`);

--
-- Index pour la table `declidispdesc`
--
ALTER TABLE `declidispdesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `declidisp` (`declidisp`,`lang`);

--
-- Index pour la table `declinaison`
--
ALTER TABLE `declinaison`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `declinaisondesc`
--
ALTER TABLE `declinaisondesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `declinaison` (`declinaison`,`lang`);

--
-- Index pour la table `devise`
--
ALTER TABLE `devise`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produit` (`produit`),
  ADD KEY `rubrique` (`rubrique`),
  ADD KEY `contenu` (`contenu`),
  ADD KEY `dossier` (`dossier`);

--
-- Index pour la table `documentdesc`
--
ALTER TABLE `documentdesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document` (`document`,`lang`);

--
-- Index pour la table `dossier`
--
ALTER TABLE `dossier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`parent`),
  ADD KEY `ligne` (`ligne`);

--
-- Index pour la table `dossierdesc`
--
ALTER TABLE `dossierdesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dossier` (`dossier`,`lang`);
ALTER TABLE `dossierdesc` ADD FULLTEXT KEY `recherche` (`titre`,`chapo`,`description`,`postscriptum`);

--
-- Index pour la table `exdecprod`
--
ALTER TABLE `exdecprod`
  ADD PRIMARY KEY (`id`),
  ADD KEY `declidisp` (`declidisp`),
  ADD KEY `produit` (`declidisp`,`produit`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produit` (`produit`),
  ADD KEY `rubrique` (`rubrique`),
  ADD KEY `contenu` (`contenu`),
  ADD KEY `dossier` (`dossier`);

--
-- Index pour la table `imagedesc`
--
ALTER TABLE `imagedesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image` (`image`,`lang`);

--
-- Index pour la table `lang`
--
ALTER TABLE `lang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `defaut` (`defaut`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messagedesc`
--
ALTER TABLE `messagedesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message` (`message`,`lang`);

--
-- Index pour la table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `actif` (`actif`),
  ADD KEY `nom` (`nom`(64)),
  ADD KEY `type_nom` (`type`,`actif`,`nom`(64));

--
-- Index pour la table `modulesdesc`
--
ALTER TABLE `modulesdesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plugin` (`plugin`(64),`lang`);

--
-- Index pour la table `pays`
--
ALTER TABLE `pays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zone` (`zone`),
  ADD KEY `defaut` (`defaut`);

--
-- Index pour la table `paysdesc`
--
ALTER TABLE `paysdesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pays` (`pays`,`lang`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref` (`ref`(30)),
  ADD KEY `ligne` (`ligne`),
  ADD KEY `rubrique` (`rubrique`);

--
-- Index pour la table `produitdesc`
--
ALTER TABLE `produitdesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produit` (`produit`,`lang`);
ALTER TABLE `produitdesc` ADD FULLTEXT KEY `recherche` (`titre`,`chapo`,`description`,`postscriptum`);

--
-- Index pour la table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `profildesc`
--
ALTER TABLE `profildesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil` (`profil`,`lang`);

--
-- Index pour la table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilise` (`utilise`),
  ADD KEY `code` (`code`(64));

--
-- Index pour la table `promoutil`
--
ALTER TABLE `promoutil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promo` (`promo`),
  ADD KEY `commande` (`commande`);

--
-- Index pour la table `raisondesc`
--
ALTER TABLE `raisondesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raison` (`raison`,`lang`);

--
-- Index pour la table `reecriture`
--
ALTER TABLE `reecriture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url` (`url`,`lang`);

--
-- Index pour la table `rubcaracteristique`
--
ALTER TABLE `rubcaracteristique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rubrique` (`rubrique`,`caracteristique`),
  ADD KEY `caracteristique` (`caracteristique`);

--
-- Index pour la table `rubdeclinaison`
--
ALTER TABLE `rubdeclinaison`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rubrique` (`rubrique`,`declinaison`);

--
-- Index pour la table `rubrique`
--
ALTER TABLE `rubrique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`parent`),
  ADD KEY `ligne` (`ligne`);

--
-- Index pour la table `rubriquedesc`
--
ALTER TABLE `rubriquedesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rubrique` (`rubrique`,`lang`);
ALTER TABLE `rubriquedesc` ADD FULLTEXT KEY `recherche` (`titre`,`chapo`,`description`,`postscriptum`);

--
-- Index pour la table `smtpconfig`
--
ALTER TABLE `smtpconfig`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `statut`
--
ALTER TABLE `statut`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `statutdesc`
--
ALTER TABLE `statutdesc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `statut` (`statut`,`lang`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `declidisp` (`declidisp`),
  ADD KEY `produit` (`produit`),
  ADD KEY `produit_valeur` (`produit`,`valeur`),
  ADD KEY `declidisp_valeur` (`declidisp`,`valeur`);

--
-- Index pour la table `transzone`
--
ALTER TABLE `transzone`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transport` (`transport`),
  ADD KEY `zone` (`zone`);

--
-- Index pour la table `variable`
--
ALTER TABLE `variable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nom` (`nom`(64));

--
-- Index pour la table `venteadr`
--
ALTER TABLE `venteadr`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ventedeclidisp`
--
ALTER TABLE `ventedeclidisp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venteprod` (`venteprod`),
  ADD KEY `declidisp` (`declidisp`);

--
-- Index pour la table `venteprod`
--
ALTER TABLE `venteprod`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande` (`commande`);

--
-- Index pour la table `zone`
--
ALTER TABLE `zone`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `accessoire`
--
ALTER TABLE `accessoire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `autorisation`
--
ALTER TABLE `autorisation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `autorisationdesc`
--
ALTER TABLE `autorisationdesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT pour la table `autorisation_administrateur`
--
ALTER TABLE `autorisation_administrateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `autorisation_modules`
--
ALTER TABLE `autorisation_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `autorisation_profil`
--
ALTER TABLE `autorisation_profil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `bo_carac`
--
ALTER TABLE `bo_carac`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT pour la table `caracdisp`
--
ALTER TABLE `caracdisp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT pour la table `caracdispdesc`
--
ALTER TABLE `caracdispdesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `caracteristiquedesc`
--
ALTER TABLE `caracteristiquedesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `caracval`
--
ALTER TABLE `caracval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;
--
-- AUTO_INCREMENT pour la table `carac_price`
--
ALTER TABLE `carac_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT pour la table `contenu`
--
ALTER TABLE `contenu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `contenuassoc`
--
ALTER TABLE `contenuassoc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `contenudesc`
--
ALTER TABLE `contenudesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `dbtest`
--
ALTER TABLE `dbtest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `declidisp`
--
ALTER TABLE `declidisp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `declidispdesc`
--
ALTER TABLE `declidispdesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `declinaison`
--
ALTER TABLE `declinaison`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `declinaisondesc`
--
ALTER TABLE `declinaisondesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `devise`
--
ALTER TABLE `devise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `documentdesc`
--
ALTER TABLE `documentdesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `dossier`
--
ALTER TABLE `dossier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `dossierdesc`
--
ALTER TABLE `dossierdesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `exdecprod`
--
ALTER TABLE `exdecprod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=509;
--
-- AUTO_INCREMENT pour la table `imagedesc`
--
ALTER TABLE `imagedesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `lang`
--
ALTER TABLE `lang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `messagedesc`
--
ALTER TABLE `messagedesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT pour la table `modulesdesc`
--
ALTER TABLE `modulesdesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `pays`
--
ALTER TABLE `pays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=269;
--
-- AUTO_INCREMENT pour la table `paysdesc`
--
ALTER TABLE `paysdesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=838;
--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;
--
-- AUTO_INCREMENT pour la table `produitdesc`
--
ALTER TABLE `produitdesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;
--
-- AUTO_INCREMENT pour la table `profil`
--
ALTER TABLE `profil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `profildesc`
--
ALTER TABLE `profildesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `promo`
--
ALTER TABLE `promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `promoutil`
--
ALTER TABLE `promoutil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `raisondesc`
--
ALTER TABLE `raisondesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `reecriture`
--
ALTER TABLE `reecriture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=420;
--
-- AUTO_INCREMENT pour la table `rubcaracteristique`
--
ALTER TABLE `rubcaracteristique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;
--
-- AUTO_INCREMENT pour la table `rubdeclinaison`
--
ALTER TABLE `rubdeclinaison`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `rubrique`
--
ALTER TABLE `rubrique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT pour la table `rubriquedesc`
--
ALTER TABLE `rubriquedesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT pour la table `smtpconfig`
--
ALTER TABLE `smtpconfig`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `statut`
--
ALTER TABLE `statut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `statutdesc`
--
ALTER TABLE `statutdesc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `transzone`
--
ALTER TABLE `transzone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT pour la table `variable`
--
ALTER TABLE `variable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT pour la table `venteadr`
--
ALTER TABLE `venteadr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
--
-- AUTO_INCREMENT pour la table `ventedeclidisp`
--
ALTER TABLE `ventedeclidisp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT pour la table `venteprod`
--
ALTER TABLE `venteprod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
--
-- AUTO_INCREMENT pour la table `zone`
--
ALTER TABLE `zone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
