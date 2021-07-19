-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 19. Jul 2021 um 10:19
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
-- Tabellenstruktur für Tabelle `employee`
--

CREATE TABLE `employee` (
  `PNR` mediumint(6) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `CoreWorkingTimeFrom` time NOT NULL DEFAULT '08:00:00',
  `CoreWorkingTimeTo` time NOT NULL DEFAULT '16:00:00',
  `HiringDate` date NOT NULL,
  `LastDateEntered` date DEFAULT NULL,
  `ProjectManager` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `employee`
--

INSERT INTO `employee` (`PNR`, `Name`, `CoreWorkingTimeFrom`, `CoreWorkingTimeTo`, `HiringDate`, `LastDateEntered`, `ProjectManager`) VALUES
(0, 'Admin', '00:00:00', '00:00:00', '2000-01-01', NULL, 1),
(1000, 'Hans Müller', '08:00:00', '16:00:00', '2021-06-06', NULL, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `employeeproject`
--

CREATE TABLE `employeeproject` (
  `PNR` mediumint(6) NOT NULL,
  `ProjectID` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `login`
--

CREATE TABLE `login` (
  `PNR` mediumint(6) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `project`
--

CREATE TABLE `project` (
  `ProjectID` mediumint(9) NOT NULL,
  `Projektname` varchar(50) NOT NULL,
  `ProjectManagerPNR` mediumint(6) NOT NULL,
  `BeginDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `projecttask`
--

CREATE TABLE `projecttask` (
  `ProjectTaskID` int(11) NOT NULL,
  `ProjectID` mediumint(9) NOT NULL,
  `Description` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `timerecording`
--

CREATE TABLE `timerecording` (
  `PNR` mediumint(6) NOT NULL,
  `ProjectID` mediumint(9) NOT NULL,
  `ProjectTaskID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `TaskBegin` time DEFAULT NULL,
  `TaskEnd` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`PNR`);

--
-- Indizes für die Tabelle `employeeproject`
--
ALTER TABLE `employeeproject`
  ADD PRIMARY KEY (`PNR`,`ProjectID`),
  ADD KEY `ProjectID` (`ProjectID`);

--
-- Indizes für die Tabelle `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`PNR`);

--
-- Indizes für die Tabelle `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`ProjectID`),
  ADD UNIQUE KEY `Projektname` (`Projektname`),
  ADD KEY `ProjectManagerPNR` (`ProjectManagerPNR`);

--
-- Indizes für die Tabelle `projecttask`
--
ALTER TABLE `projecttask`
  ADD PRIMARY KEY (`ProjectTaskID`,`ProjectID`),
  ADD KEY `ProjectID` (`ProjectID`);

--
-- Indizes für die Tabelle `timerecording`
--
ALTER TABLE `timerecording`
  ADD PRIMARY KEY (`PNR`,`ProjectID`,`ProjectTaskID`,`Date`),
  ADD KEY `ProjectTaskID` (`ProjectTaskID`),
  ADD KEY `ProjectID` (`ProjectID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `project`
--
ALTER TABLE `project`
  MODIFY `ProjectID` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `employeeproject`
--
ALTER TABLE `employeeproject`
  ADD CONSTRAINT `employeeproject_ibfk_1` FOREIGN KEY (`PNR`) REFERENCES `employee` (`PNR`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employeeproject_ibfk_2` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`PNR`) REFERENCES `employee` (`PNR`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`ProjectManagerPNR`) REFERENCES `employee` (`PNR`);

--
-- Constraints der Tabelle `projecttask`
--
ALTER TABLE `projecttask`
  ADD CONSTRAINT `projecttask_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `timerecording`
--
ALTER TABLE `timerecording`
  ADD CONSTRAINT `timerecording_ibfk_1` FOREIGN KEY (`PNR`) REFERENCES `employee` (`PNR`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `timerecording_ibfk_2` FOREIGN KEY (`ProjectTaskID`) REFERENCES `projecttask` (`ProjectTaskID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `timerecording_ibfk_3` FOREIGN KEY (`ProjectID`) REFERENCES `projecttask` (`ProjectID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
