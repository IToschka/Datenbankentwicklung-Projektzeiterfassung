-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Sep 2021 um 13:07
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

DELIMITER $$
--
-- Funktionen
--
CREATE DEFINER=`root`@`localhost` FUNCTION `GeneratePNR` () RETURNS MEDIUMINT(6) Begin
DECLARE MaxPnr MEDIUMINT(6);

    Select Max(PNR) Into MaxPnr
    From employee;
    If MaxPnr is Null Then
    SET MaxPnr = 0;
    Else 
    SET  MaxPnr = MaxPnr + 1;
    End if;
      return MaxPnr;
  End$$

CREATE DEFINER=`root`@`localhost` FUNCTION `GetAveragePerProject` (`sumInSec` INT, `projectId` INT) RETURNS INT(11) Begin
DECLARE NumberOfValues INT;	
DECLARE Average INT;
	SELECT COUNT(PNR) INTO NumberOfValues FROM employeeproject WHERE ProjectID = projectId; 
    SET Average = sumInSec / NumberOfValues;
    return Average;      
End$$

CREATE DEFINER=`root`@`localhost` FUNCTION `GetAverageTotal` (`sumInSec` INT) RETURNS INT(11) Begin
DECLARE NumberOfValues INT;	
DECLARE Average INT;
	SELECT COUNT(PNR) INTO NumberOfValues FROM employee WHERE PNR != '0';
    SET Average = sumInSec / NumberOfValues;
    return Average;      
End$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `employee`
--

CREATE TABLE `employee` (
  `PNR` mediumint(6) NOT NULL,
  `Firstname` varchar(50) NOT NULL,
  `Lastname` varchar(50) NOT NULL,
  `CoreWorkingTimeFrom` time NOT NULL DEFAULT '08:00:00',
  `CoreWorkingTimeTo` time NOT NULL DEFAULT '16:00:00',
  `HiringDate` date NOT NULL,
  `WeeklyWorkingHours` tinyint(4) NOT NULL,
  `LastDateEntered` date DEFAULT NULL,
  `ProjectManager` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `employee`
--

INSERT INTO `employee` (`PNR`, `Firstname`, `Lastname`, `CoreWorkingTimeFrom`, `CoreWorkingTimeTo`, `HiringDate`, `WeeklyWorkingHours`, `LastDateEntered`, `ProjectManager`) VALUES
(0, 'Admin', 'Admin', '00:00:00', '00:01:00', '1999-01-01', 20, '1998-12-31', 1),
(1, 'Max', 'Mustermann', '08:00:00', '16:00:00', '2002-06-01', 40, '2021-09-01', 1),
(2, 'Annika', 'Arnold', '06:00:00', '15:00:00', '2010-03-01', 30, '2021-09-03', 0),
(3, 'Bernd', 'Buchmann', '08:00:00', '15:00:00', '2001-05-01', 35, '2021-09-06', 0),
(4, 'Carolin', 'Chu', '07:00:00', '16:00:00', '2021-09-01', 35, '2021-08-31', 0),
(5, 'Daniela', 'Dolderer', '08:00:00', '16:00:00', '2000-02-01', 40, '2021-09-10', 1),
(6, 'Elmar', 'Ellinger', '10:00:00', '18:00:00', '1998-06-01', 25, '2021-09-14', 0),
(7, 'Franziska', 'Fischbach', '08:00:00', '17:00:00', '2003-07-01', 30, '2021-08-27', 1);

--
-- Trigger `employee`
--
DELIMITER $$
CREATE TRIGGER `SetProjectManagerToAdmin` BEFORE DELETE ON `employee` FOR EACH ROW BEGIN

UPDATE project SET ProjectManagerPNR = "0" WHERE ProjectManagerPNR = old.pnr;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `employeeproject`
--

CREATE TABLE `employeeproject` (
  `PNR` mediumint(6) NOT NULL,
  `ProjectID` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `employeeproject`
--

INSERT INTO `employeeproject` (`PNR`, `ProjectID`) VALUES
(1, 1),
(1, 2),
(1, 5),
(2, 1),
(2, 2),
(2, 3),
(3, 2),
(3, 3),
(4, 3),
(4, 4),
(5, 4),
(5, 5),
(7, 4),
(7, 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `login`
--

CREATE TABLE `login` (
  `PNR` mediumint(6) NOT NULL,
  `Password` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `login`
--

INSERT INTO `login` (`PNR`, `Password`) VALUES
(0, '$2y$10$qPOHjo7pX2WrfY9IHULEkOBORAUPQB.tgDhvZDdzsEJh9M3xc3jSy'),
(1, '$2y$10$pU8fCcXgG9JbjY2BPHd9q.spPFzNbRLQ0qf7jfL6zSaqXXKyOxOwG'),
(2, '$2y$10$f5YTQn7zNFJvWMUC1Hvyx.X2Xo/y7ScUETiwLiq3EZqj0km6bwMdm'),
(3, '$2y$10$iOL5/eOHVmsBszLJowXTheUud1TfAct1FHZAL65PxXvkhp6Oy2UqC'),
(4, '$2y$10$ze.xoARsb133IGD37wGH9.dgqfcEtBnB3DxGcM6UbtrrmCYNgJIXW'),
(5, '$2y$10$lf3wH0mE3DZFxiy4ii92M./GIPMebOWI3RCdo6rjplpEuCnRimZhO'),
(6, '$2y$10$PEVzjXYW7zf.2.i9AO4XI.IGlIMAH8dlO9ye1b.rSEj2bzHMFZVYK'),
(7, '$2y$10$orSe0B/c.LH8KcY4W0zwLeGSRWYC4DoXf8IZq7YQODIPykb0i6426');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `project`
--

CREATE TABLE `project` (
  `ProjectID` mediumint(9) NOT NULL,
  `ProjectName` varchar(50) NOT NULL,
  `ProjectManagerPNR` mediumint(6) NOT NULL,
  `BeginDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `project`
--

INSERT INTO `project` (`ProjectID`, `ProjectName`, `ProjectManagerPNR`, `BeginDate`) VALUES
(1, 'Gebäudesanierung', 1, '2021-09-02'),
(2, 'S/4 HANA Conversion', 5, '2022-03-20'),
(3, 'Entwicklung Bauteil X', 7, '2021-08-02'),
(4, 'Testprojekt', 5, '2022-03-20'),
(5, 'Softwareauswahl Einkaufsprozesse', 1, '2021-06-07');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `projecttask`
--

CREATE TABLE `projecttask` (
  `ProjectTaskID` int(11) NOT NULL,
  `ProjectID` mediumint(9) NOT NULL,
  `TaskDescription` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `projecttask`
--

INSERT INTO `projecttask` (`ProjectTaskID`, `ProjectID`, `TaskDescription`) VALUES
(1, 1, 'Auswahl eines Anbieters, der unseren qualikativen Anforderungen gewachsen ist.'),
(1, 2, 'Ansatz für die Konvertierung wählen.'),
(1, 3, 'Zeichnung des Bauteils erstellen.'),
(1, 5, 'Marktanalyse durchführen.'),
(2, 1, 'Planung der Raumwechsel.'),
(2, 2, 'Aufteilung der Zuständigkeitsbereiche.'),
(2, 3, 'Technische Machbarkeit abklären und konkreten Bauplan erstellen.'),
(2, 5, 'Anbeiter vergleichen.'),
(3, 2, 'Schulungen durchführen für die Fachbereiche.'),
(3, 3, 'Lieferantenauswahl und Einkauf der Bauteile.'),
(3, 5, 'Nutzwertanalyse durchführen.'),
(4, 3, 'Bauphase'),
(5, 3, 'Testphase'),
(6, 3, 'Übergang zur Serienproduktion managen.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `timerecording`
--

CREATE TABLE `timerecording` (
  `PNR` mediumint(6) NOT NULL,
  `ProjectID` mediumint(9) NOT NULL,
  `ProjectTaskID` int(11) NOT NULL,
  `RecordingDate` date NOT NULL,
  `TaskBegin` time DEFAULT NULL,
  `TaskEnd` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `timerecording`
--

INSERT INTO `timerecording` (`PNR`, `ProjectID`, `ProjectTaskID`, `RecordingDate`, `TaskBegin`, `TaskEnd`) VALUES
(1, 1, 1, '2021-09-06', '07:00:00', '08:00:00'),
(1, 1, 1, '2021-09-07', '07:00:00', '08:00:00'),
(1, 1, 1, '2021-09-08', '07:00:00', '08:00:00'),
(1, 1, 1, '2021-09-09', '07:00:00', '08:00:00'),
(1, 1, 1, '2021-09-10', '07:00:00', '08:00:00'),
(1, 1, 1, '2021-09-13', '07:00:00', '08:00:00'),
(1, 1, 1, '2021-09-14', '07:00:00', '08:00:00'),
(1, 1, 1, '2021-09-15', '07:00:00', '08:00:00'),
(1, 1, 1, '2021-09-16', '07:00:00', '08:00:00'),
(1, 1, 1, '2021-09-20', '07:00:00', '08:00:00'),
(1, 1, 1, '2021-09-21', '07:00:00', '08:00:00'),
(1, 1, 2, '2021-09-06', '10:00:00', '11:00:00'),
(1, 1, 2, '2021-09-07', '10:00:00', '11:00:00'),
(1, 1, 2, '2021-09-08', '10:00:00', '11:00:00'),
(1, 1, 2, '2021-09-09', '10:00:00', '11:00:00'),
(1, 1, 2, '2021-09-10', '10:00:00', '11:00:00'),
(1, 1, 2, '2021-09-13', '10:00:00', '11:00:00'),
(1, 1, 2, '2021-09-14', '10:00:00', '11:00:00'),
(1, 1, 2, '2021-09-15', '10:00:00', '11:00:00'),
(1, 1, 2, '2021-09-16', '10:00:00', '11:00:00'),
(1, 1, 2, '2021-09-20', '10:00:00', '11:00:00'),
(1, 1, 2, '2021-09-21', '10:00:00', '11:00:00'),
(1, 1, 3, '2021-09-06', '11:00:00', '12:00:00'),
(1, 1, 3, '2021-09-07', '11:00:00', '12:00:00'),
(1, 1, 3, '2021-09-08', '11:00:00', '12:00:00'),
(1, 1, 3, '2021-09-09', '11:00:00', '12:00:00'),
(1, 1, 3, '2021-09-10', '11:00:00', '12:00:00'),
(1, 1, 3, '2021-09-13', '11:00:00', '12:00:00'),
(1, 1, 3, '2021-09-14', '11:00:00', '12:00:00'),
(1, 1, 3, '2021-09-15', '11:00:00', '12:00:00'),
(1, 1, 3, '2021-09-16', '11:00:00', '12:00:00'),
(1, 1, 3, '2021-09-20', '11:00:00', '12:00:00'),
(1, 1, 3, '2021-09-21', '11:00:00', '12:00:00'),
(1, 2, 1, '2021-09-06', '13:00:00', '14:00:00'),
(1, 2, 1, '2021-09-07', '13:00:00', '14:00:00'),
(1, 2, 1, '2021-09-08', '13:00:00', '14:00:00'),
(1, 2, 1, '2021-09-13', '13:00:00', '14:00:00'),
(1, 2, 1, '2021-09-14', '13:00:00', '14:00:00'),
(1, 2, 2, '2021-09-06', '15:00:00', '16:00:00'),
(1, 2, 2, '2021-09-07', '15:00:00', '16:00:00'),
(1, 2, 2, '2021-09-08', '15:00:00', '16:00:00'),
(1, 2, 2, '2021-09-13', '15:00:00', '16:00:00'),
(1, 2, 2, '2021-09-14', '15:00:00', '16:00:00'),
(2, 2, 1, '2021-09-06', '16:00:00', '17:00:00'),
(2, 3, 2, '2021-09-06', '10:00:00', '11:00:00'),
(2, 3, 2, '2021-09-08', '06:00:00', '17:00:00'),
(2, 3, 2, '2021-09-10', '07:00:00', '18:00:00'),
(2, 3, 2, '2021-09-14', '05:00:00', '10:00:00'),
(2, 3, 2, '2021-09-16', '07:00:00', '08:00:00'),
(2, 3, 2, '2021-09-20', '07:00:00', '12:00:00'),
(2, 3, 3, '2021-09-07', '07:00:00', '17:00:00'),
(2, 3, 3, '2021-09-09', '07:00:00', '16:00:00'),
(2, 3, 3, '2021-09-13', '06:00:00', '15:00:00'),
(2, 3, 3, '2021-09-15', '07:00:00', '08:00:00'),
(2, 3, 3, '2021-09-21', '07:00:00', '15:00:00');

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
  ADD UNIQUE KEY `Projektname` (`ProjectName`),
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
  ADD PRIMARY KEY (`PNR`,`ProjectID`,`ProjectTaskID`,`RecordingDate`),
  ADD KEY `ProjectTaskID` (`ProjectTaskID`),
  ADD KEY `ProjectID` (`ProjectID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `project`
--
ALTER TABLE `project`
  MODIFY `ProjectID` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`ProjectManagerPNR`) REFERENCES `employee` (`PNR`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `projecttask`
--
ALTER TABLE `projecttask`
  ADD CONSTRAINT `projecttask_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `timerecording`
--
ALTER TABLE `timerecording`
  ADD CONSTRAINT `timerecording_ibfk_1` FOREIGN KEY (`PNR`) REFERENCES `employee` (`PNR`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `timerecording_ibfk_2` FOREIGN KEY (`ProjectTaskID`) REFERENCES `projecttask` (`ProjectTaskID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `timerecording_ibfk_3` FOREIGN KEY (`ProjectID`) REFERENCES `projecttask` (`ProjectID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
