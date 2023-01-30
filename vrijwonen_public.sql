-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 30 jan 2023 om 22:00
-- Serverversie: 10.4.25-MariaDB
-- PHP-versie: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vrijwonen_public`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `citiename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `cities`
--

INSERT INTO `cities` (`id`, `citiename`) VALUES
(106, '\'s-Gravenhage'),
(131, '\'s-Hertogenbosch'),
(2, 'Aa en Hunze'),
(3, 'Aalsmeer'),
(4, 'Aalten'),
(5, 'Achtkarspelen'),
(6, 'Alblasserdam'),
(7, 'Albrandswaard'),
(8, 'Alkmaar'),
(9, 'Almelo'),
(10, 'Almere'),
(11, 'Alphen aan den Rijn'),
(12, 'Alphen-Chaam'),
(13, 'Altena'),
(14, 'Ameland'),
(15, 'Amersfoort'),
(16, 'Amstelveen'),
(17, 'Amsterdam'),
(18, 'Apeldoorn'),
(19, 'Arnhem'),
(20, 'Assen'),
(21, 'Asten'),
(22, 'Baarle-Nassau'),
(23, 'Baarn'),
(24, 'Barendrecht'),
(25, 'Barneveld'),
(26, 'Beek'),
(27, 'Beekdaelen'),
(28, 'Beesel'),
(29, 'Berg en Dal'),
(30, 'Bergeijk'),
(31, 'Bergen (L.)'),
(32, 'Bergen (NH.)'),
(33, 'Bergen op Zoom'),
(34, 'Berkelland'),
(35, 'Bernheze'),
(36, 'Best'),
(37, 'Beuningen'),
(38, 'Beverwijk'),
(40, 'Bladel'),
(41, 'Blaricum'),
(42, 'Bloemendaal'),
(43, 'Bodegraven-Reeuwijk'),
(44, 'Boekel'),
(45, 'Borger-Odoorn'),
(46, 'Borne'),
(47, 'Borsele'),
(48, 'Boxtel'),
(49, 'Breda'),
(50, 'Brielle'),
(51, 'Bronckhorst'),
(52, 'Brummen'),
(53, 'Brunssum'),
(54, 'Bunnik'),
(55, 'Bunschoten'),
(56, 'Buren'),
(57, 'Capelle aan den IJssel'),
(58, 'Castricum'),
(59, 'Coevorden'),
(60, 'Cranendonck'),
(61, 'Culemborg'),
(62, 'Dalfsen'),
(63, 'Dantumadiel'),
(39, 'De Bilt'),
(94, 'De Fryske Marren'),
(244, 'De Ronde Venen'),
(330, 'De Wolden'),
(64, 'Delft'),
(125, 'Den Helder'),
(65, 'Deurne'),
(66, 'Deventer'),
(67, 'Diemen'),
(68, 'Dijk en Waard'),
(69, 'Dinkelland'),
(70, 'Doesburg'),
(71, 'Doetinchem'),
(72, 'Dongen'),
(73, 'Dordrecht'),
(74, 'Drechterland'),
(75, 'Drimmelen'),
(76, 'Dronten'),
(77, 'Druten'),
(78, 'Duiven'),
(79, 'Echt-Susteren'),
(80, 'Edam-Volendam'),
(81, 'Ede'),
(82, 'Eemnes'),
(83, 'Eemsdelta'),
(84, 'Eersel'),
(85, 'Eijsden-Margraten'),
(86, 'Eindhoven'),
(87, 'Elburg'),
(88, 'Emmen'),
(89, 'Enkhuizen'),
(90, 'Enschede'),
(91, 'Epe'),
(92, 'Ermelo'),
(93, 'Etten-Leur'),
(95, 'Geertruidenberg'),
(96, 'Geldrop-Mierlo'),
(97, 'Gemert-Bakel'),
(98, 'Gennep'),
(99, 'Gilze en Rijen'),
(100, 'Goeree-Overflakkee'),
(101, 'Goes'),
(102, 'Goirle'),
(103, 'Gooise Meren'),
(104, 'Gorinchem'),
(105, 'Gouda'),
(107, 'Groningen'),
(108, 'Gulpen-Wittem'),
(109, 'Haaksbergen'),
(110, 'Haarlem'),
(111, 'Haarlemmermeer'),
(112, 'Halderberge'),
(113, 'Hardenberg'),
(114, 'Harderwijk'),
(115, 'Hardinxveld-Giessendam'),
(116, 'Harlingen'),
(117, 'Hattem'),
(118, 'Heemskerk'),
(119, 'Heemstede'),
(120, 'Heerde'),
(121, 'Heerenveen'),
(122, 'Heerlen'),
(123, 'Heeze-Leende'),
(124, 'Heiloo'),
(126, 'Hellendoorn'),
(127, 'Hellevoetsluis'),
(128, 'Helmond'),
(129, 'Hendrik-Ido-Ambacht'),
(130, 'Hengelo'),
(139, 'Het Hogeland'),
(132, 'Heumen'),
(133, 'Heusden'),
(134, 'Hillegom'),
(135, 'Hilvarenbeek'),
(136, 'Hilversum'),
(137, 'Hoeksche Waard'),
(138, 'Hof van Twente'),
(140, 'Hollands Kroon'),
(141, 'Hoogeveen'),
(142, 'Hoorn'),
(143, 'Horst aan de Maas'),
(144, 'Houten'),
(145, 'Huizen'),
(146, 'Hulst'),
(147, 'IJsselstein'),
(148, 'Kaag en Braassem'),
(149, 'Kampen'),
(150, 'Kapelle'),
(151, 'Katwijk'),
(152, 'Kerkrade'),
(153, 'Koggenland'),
(154, 'Krimpen aan den IJssel'),
(155, 'Krimpenerwaard'),
(156, 'Laarbeek'),
(157, 'Land van Cuijk'),
(158, 'Landgraaf'),
(159, 'Landsmeer'),
(160, 'Lansingerland'),
(161, 'Laren'),
(162, 'Leeuwarden'),
(163, 'Leiden'),
(164, 'Leiderdorp'),
(165, 'Leidschendam-Voorburg'),
(166, 'Lelystad'),
(167, 'Leudal'),
(168, 'Leusden'),
(169, 'Lingewaard'),
(170, 'Lisse'),
(171, 'Lochem'),
(172, 'Loon op Zand'),
(173, 'Lopik'),
(174, 'Losser'),
(175, 'Maasdriel'),
(176, 'Maasgouw'),
(177, 'Maashorst'),
(178, 'Maassluis'),
(179, 'Maastricht'),
(180, 'Medemblik'),
(181, 'Meerssen'),
(182, 'Meierijstad'),
(183, 'Meppel'),
(184, 'Middelburg'),
(185, 'Midden-Delfland'),
(186, 'Midden-Drenthe'),
(187, 'Midden-Groningen'),
(188, 'Moerdijk'),
(189, 'Molenlanden'),
(190, 'Montferland'),
(191, 'Montfoort'),
(192, 'Mook en Middelaar'),
(193, 'Neder-Betuwe'),
(194, 'Nederweert'),
(195, 'Nieuwegein'),
(196, 'Nieuwkoop'),
(197, 'Nijkerk'),
(198, 'Nijmegen'),
(199, 'Nissewaard'),
(200, 'Noardeast-Frysl?n\r'),
(201, 'Noord-Beveland'),
(202, 'Noordenveld'),
(203, 'Noordoostpolder'),
(204, 'Noordwijk'),
(205, 'Nuenen, Gerwen en Nederwetten'),
(206, 'Nunspeet'),
(207, 'Oegstgeest'),
(208, 'Oirschot'),
(209, 'Oisterwijk'),
(210, 'Oldambt'),
(211, 'Oldebroek'),
(212, 'Oldenzaal'),
(213, 'Olst-Wijhe'),
(214, 'Ommen'),
(215, 'Oost Gelre'),
(216, 'Oosterhout'),
(217, 'Ooststellingwerf'),
(218, 'Oostzaan'),
(219, 'Opmeer'),
(220, 'Opsterland'),
(221, 'Oss'),
(222, 'Oude IJsselstreek'),
(223, 'Ouder-Amstel'),
(224, 'Oudewater'),
(225, 'Overbetuwe'),
(226, 'Papendrecht'),
(227, 'Peel en Maas'),
(228, 'Pekela'),
(229, 'Pijnacker-Nootdorp'),
(230, 'Purmerend'),
(231, 'Putten'),
(232, 'Raalte'),
(233, 'Reimerswaal'),
(234, 'Renkum'),
(235, 'Renswoude'),
(236, 'Reusel-De Mierden'),
(237, 'Rheden'),
(238, 'Rhenen'),
(239, 'Ridderkerk'),
(240, 'Rijssen-Holten'),
(241, 'Rijswijk'),
(242, 'Roerdalen'),
(243, 'Roermond'),
(245, 'Roosendaal'),
(246, 'Rotterdam'),
(247, 'Rozendaal'),
(248, 'Rucphen'),
(270, 'S?dwest-Frysl?n\r'),
(249, 'Schagen'),
(250, 'Scherpenzeel'),
(251, 'Schiedam'),
(252, 'Schiermonnikoog'),
(253, 'Schouwen-Duiveland'),
(254, 'Simpelveld'),
(255, 'Sint-Michielsgestel'),
(256, 'Sittard-Geleen'),
(257, 'Sliedrecht'),
(258, 'Sluis'),
(259, 'Smallingerland'),
(260, 'Soest'),
(261, 'Someren'),
(262, 'Son en Breugel'),
(263, 'Stadskanaal'),
(264, 'Staphorst'),
(265, 'Stede Broec'),
(266, 'Steenbergen'),
(267, 'Steenwijkerland'),
(268, 'Stein'),
(269, 'Stichtse Vecht'),
(271, 'Terneuzen'),
(272, 'Terschelling'),
(273, 'Texel'),
(274, 'Teylingen'),
(275, 'Tholen'),
(276, 'Tiel'),
(277, 'Tilburg'),
(278, 'Tubbergen'),
(279, 'Twenterand'),
(280, 'Tynaarlo'),
(281, 'Tytsjerksteradiel'),
(282, 'Uitgeest'),
(283, 'Uithoorn'),
(284, 'Urk'),
(285, 'Utrecht'),
(286, 'Utrechtse Heuvelrug'),
(287, 'Vaals'),
(288, 'Valkenburg aan de Geul'),
(289, 'Valkenswaard'),
(290, 'Veendam'),
(291, 'Veenendaal'),
(292, 'Veere'),
(293, 'Veldhoven'),
(294, 'Velsen'),
(295, 'Venlo'),
(296, 'Venray'),
(297, 'Vijfheerenlanden'),
(298, 'Vlaardingen'),
(299, 'Vlieland'),
(300, 'Vlissingen'),
(301, 'Voerendaal'),
(302, 'Voorschoten'),
(303, 'Voorst'),
(304, 'Vught'),
(305, 'Waadhoeke'),
(306, 'Waalre'),
(307, 'Waalwijk'),
(308, 'Waddinxveen'),
(309, 'Wageningen'),
(310, 'Wassenaar'),
(311, 'Waterland'),
(312, 'Weert'),
(313, 'Weesp'),
(314, 'West Betuwe'),
(315, 'West Maas en Waal'),
(316, 'Westerkwartier'),
(317, 'Westerveld'),
(318, 'Westervoort'),
(319, 'Westerwolde'),
(320, 'Westland'),
(321, 'Weststellingwerf'),
(322, 'Westvoorne'),
(323, 'Wierden'),
(324, 'Wijchen'),
(325, 'Wijdemeren'),
(326, 'Wijk bij Duurstede'),
(327, 'Winterswijk'),
(328, 'Woensdrecht'),
(329, 'Woerden'),
(331, 'Wormerland'),
(332, 'Woudenberg'),
(333, 'Zaanstad'),
(334, 'Zaltbommel'),
(335, 'Zandvoort'),
(336, 'Zeewolde'),
(337, 'Zeist'),
(338, 'Zevenaar'),
(339, 'Zoetermeer'),
(340, 'Zoeterwoude'),
(341, 'Zuidplas'),
(342, 'Zundert'),
(343, 'Zutphen'),
(344, 'Zwartewaterland'),
(345, 'Zwijndrecht'),
(346, 'Zwolle');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `connectprop`
--

CREATE TABLE `connectprop` (
  `id` int(11) NOT NULL,
  `objectid` int(11) NOT NULL,
  `propertieid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `objectid` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `replyemail` varchar(255) NOT NULL,
  `message` varchar(1020) NOT NULL,
  `handled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `objects`
--

CREATE TABLE `objects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `adress` varchar(255) NOT NULL,
  `postcode` char(6) NOT NULL,
  `cityid` int(11) NOT NULL,
  `description` varchar(1020) NOT NULL,
  `mainimage` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL,
  `image4` varchar(255) NOT NULL,
  `image5` varchar(255) NOT NULL,
  `sold` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `propertie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `properties`
--

INSERT INTO `properties` (`id`, `propertie`) VALUES
(1, 'Dicht bij het bos'),
(2, 'Dicht bij de stad'),
(3, 'Dicht bij zee'),
(4, 'In het Heuvelland'),
(5, 'Aan het water'),
(6, 'Inclusief overname inventaris'),
(7, 'Zwembad op het park'),
(8, 'Winkel op het park'),
(9, 'Entertainment op het park'),
(10, 'Op een privépark');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordhash` varchar(255) NOT NULL,
  `sessionkey` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `staff`
--

INSERT INTO `staff` (`id`, `username`, `email`, `passwordhash`, `sessionkey`, `admin`) VALUES
(1, 'Admin1', 'admin@example.com', '$2y$10$A2DliPh8T237e0JzSBSIae2KiweLfFqbKIxbqDbPKjD5PJSzTQ7.C', '', 1),
(7, 'User1', 'user@example.com', '$2y$10$MhXn/2WWQj6eQLTRcjajAeSHHeMT5uQDoULApWOL1otG8GBUm8yDK', '', 0);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `citiename` (`citiename`);

--
-- Indexen voor tabel `connectprop`
--
ALTER TABLE `connectprop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_objects` (`objectid`),
  ADD KEY `fk_propertie` (`propertieid`);

--
-- Indexen voor tabel `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_OBJECT_ID` (`objectid`);

--
-- Indexen voor tabel `objects`
--
ALTER TABLE `objects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adress` (`adress`),
  ADD KEY `fk_city` (`cityid`);

--
-- Indexen voor tabel `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=347;

--
-- AUTO_INCREMENT voor een tabel `connectprop`
--
ALTER TABLE `connectprop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT voor een tabel `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `objects`
--
ALTER TABLE `objects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT voor een tabel `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `connectprop`
--
ALTER TABLE `connectprop`
  ADD CONSTRAINT `fk_objects` FOREIGN KEY (`objectid`) REFERENCES `objects` (`id`),
  ADD CONSTRAINT `fk_propertie` FOREIGN KEY (`propertieid`) REFERENCES `properties` (`id`);

--
-- Beperkingen voor tabel `inquiries`
--
ALTER TABLE `inquiries`
  ADD CONSTRAINT `FK_OBJECT_ID` FOREIGN KEY (`objectid`) REFERENCES `objects` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `objects`
--
ALTER TABLE `objects`
  ADD CONSTRAINT `fk_city` FOREIGN KEY (`cityid`) REFERENCES `cities` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
