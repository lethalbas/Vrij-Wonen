-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 25 jan 2023 om 23:32
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
-- Database: `vrijwonen`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `apikeys`
--

CREATE TABLE `apikeys` (
  `id` int(11) NOT NULL,
  `apikey` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `expires` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `citiename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `connectnearfac`
--

CREATE TABLE `connectnearfac` (
  `id` int(11) NOT NULL,
  `objectid` int(11) NOT NULL,
  `nearfacilitieid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `fullname` varchar(255) NOT NULL,
  `replyemail` varchar(255) NOT NULL,
  `message` varchar(1020) NOT NULL,
  `handled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `nearfacilities`
--

CREATE TABLE `nearfacilities` (
  `id` int(11) NOT NULL,
  `facilitie` int(11) NOT NULL
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
  `postcodeid` int(11) NOT NULL,
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
-- Tabelstructuur voor tabel `postcodes`
--

CREATE TABLE `postcodes` (
  `id` int(11) NOT NULL,
  `citieid` int(11) NOT NULL,
  `postcode` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `propertie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `apikeys`
--
ALTER TABLE `apikeys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `apikey` (`apikey`);

--
-- Indexen voor tabel `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `citiename` (`citiename`);

--
-- Indexen voor tabel `connectnearfac`
--
ALTER TABLE `connectnearfac`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_object` (`objectid`),
  ADD KEY `fk_nearfacilitie` (`nearfacilitieid`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `nearfacilities`
--
ALTER TABLE `nearfacilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `objects`
--
ALTER TABLE `objects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adress` (`adress`),
  ADD KEY `fk_postcode` (`postcodeid`);

--
-- Indexen voor tabel `postcodes`
--
ALTER TABLE `postcodes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `postcode` (`postcode`),
  ADD KEY `fk_citieid` (`citieid`);

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
-- AUTO_INCREMENT voor een tabel `apikeys`
--
ALTER TABLE `apikeys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `connectnearfac`
--
ALTER TABLE `connectnearfac`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `connectprop`
--
ALTER TABLE `connectprop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `nearfacilities`
--
ALTER TABLE `nearfacilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `objects`
--
ALTER TABLE `objects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `postcodes`
--
ALTER TABLE `postcodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `connectnearfac`
--
ALTER TABLE `connectnearfac`
  ADD CONSTRAINT `fk_nearfacilitie` FOREIGN KEY (`nearfacilitieid`) REFERENCES `nearfacilities` (`id`),
  ADD CONSTRAINT `fk_object` FOREIGN KEY (`objectid`) REFERENCES `objects` (`id`);

--
-- Beperkingen voor tabel `connectprop`
--
ALTER TABLE `connectprop`
  ADD CONSTRAINT `fk_objects` FOREIGN KEY (`objectid`) REFERENCES `objects` (`id`),
  ADD CONSTRAINT `fk_propertie` FOREIGN KEY (`propertieid`) REFERENCES `properties` (`id`);

--
-- Beperkingen voor tabel `objects`
--
ALTER TABLE `objects`
  ADD CONSTRAINT `fk_postcode` FOREIGN KEY (`postcodeid`) REFERENCES `postcodes` (`id`);

--
-- Beperkingen voor tabel `postcodes`
--
ALTER TABLE `postcodes`
  ADD CONSTRAINT `fk_citieid` FOREIGN KEY (`citieid`) REFERENCES `cities` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
