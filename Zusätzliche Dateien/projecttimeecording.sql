-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 25. Aug 2021 um 11:49
-- Server-Version: 10.4.19-MariaDB
-- PHP-Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `projecttimerecording`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `projecttask`
--

CREATE TABLE `projecttask` (
  `ProjectTaskID` int(11) NOT NULL,
  `ProjectID` mediumint(9) NOT NULL,
  `Description` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `projecttask`
--

INSERT INTO `projecttask` (`ProjectTaskID`, `ProjectID`, `Description`) VALUES
(1, 1, 'Auswahl eines Anbieters, der unseren qualikativen Anforderungen gewachsen ist.'),
(1, 2, 'Ansatz für die Konvertierung wählen.'),
(1, 3, 'Zeichnung des Bauteils erstellen.'),
(2, 1, 'Planung der Raumwechsel.'),
(2, 2, 'Aufteilung der Zuständigkeitsbereiche.'),
(2, 3, 'Technische Machbarkeit abklären und konkreten Bauplan erstellen.'),
(3, 2, 'Schulungen durchführen für die Fachbereiche'),
(3, 3, 'Lieferantenauswahl und Einkauf der Bauteile.'),
(4, 3, 'Bauphase'),
(5, 3, 'Testphase'),
(6, 3, 'Übergang zur Serienproduktion managen.');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `projecttask`
--
ALTER TABLE `projecttask`
  ADD PRIMARY KEY (`ProjectTaskID`,`ProjectID`),
  ADD KEY `ProjectID` (`ProjectID`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `projecttask`
--
ALTER TABLE `projecttask`
  ADD CONSTRAINT `projecttask_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
