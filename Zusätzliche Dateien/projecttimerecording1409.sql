-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 14. Sep 2021 um 17:34
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
(0, 'Admin', '', '00:00:00', '00:00:00', '2000-01-01', 0, NULL, 1),
(1, 'Max', 'Mustermann', '08:00:00', '16:00:00', '2002-06-06', 42, '2021-09-03', 1),
(2, 'Annika', 'Arnold', '06:00:00', '15:00:00', '2010-08-22', 25, '2021-09-02', 0),
(3, 'Bernd', 'Bader', '07:00:00', '16:00:00', '2003-07-27', 35, '2021-08-24', 1),
(4, 'Daniela', 'Dolderer', '09:00:00', '17:00:00', '2011-08-02', 32, '2021-09-01', 0),
(5, 'Carolin', 'Chu', '12:00:00', '16:00:00', '2021-08-01', 20, '2021-07-30', 0);

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
(3, 1),
(3, 3),
(3, 4),
(3, 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `login`
--

CREATE TABLE `login` (
  `PNR` mediumint(6) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `login`
--

INSERT INTO `login` (`PNR`, `Password`) VALUES
(1, 'Hallo'),
(2, 'IchhabekeinenPaln'),
(3, 'Hallo'),
(4, 'DHbw2019!'),
(5, 'ERtz789!');

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
(1, 'Gebäudesanierung', 2, '2021-09-02'),
(2, 'S/4 HANA Conversion', 4, '2022-03-20'),
(3, 'Entwicklung Bauteil X', 2, '2021-08-02'),
(4, 'Testprojekt', 4, '2022-03-20'),
(5, 'Softwareauswahl Einkaufsprozesse', 3, '2021-06-07');

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
(3, 1, 1, '2021-09-06', '07:00:00', '08:00:00'),
(3, 1, 1, '2021-09-07', '07:00:00', '08:00:00'),
(3, 1, 1, '2021-09-08', '07:00:00', '08:00:00'),
(3, 1, 1, '2021-09-13', '07:00:00', '08:00:00'),
(3, 1, 1, '2021-09-14', '07:00:00', '08:00:00'),
(3, 1, 1, '2021-09-15', '07:00:00', '08:00:00'),
(3, 1, 1, '2021-09-16', '07:00:00', '08:00:00'),
(3, 1, 1, '2021-09-17', '07:00:00', '08:00:00'),
(3, 1, 1, '2021-09-20', '07:00:00', '08:00:00'),
(3, 1, 2, '2021-09-06', '10:00:00', '11:00:00'),
(3, 1, 2, '2021-09-07', '10:00:00', '11:00:00'),
(3, 1, 2, '2021-09-08', '10:00:00', '11:00:00'),
(3, 1, 2, '2021-09-13', '10:00:00', '11:00:00'),
(3, 1, 2, '2021-09-14', '10:00:00', '11:00:00'),
(3, 1, 2, '2021-09-15', '10:00:00', '11:00:00'),
(3, 1, 2, '2021-09-16', '10:00:00', '11:00:00'),
(3, 1, 2, '2021-09-17', '10:00:00', '11:00:00'),
(3, 1, 2, '2021-09-20', '10:00:00', '11:00:00'),
(3, 1, 3, '2021-09-06', '13:00:00', '14:00:00'),
(3, 1, 3, '2021-09-07', '13:00:00', '14:00:00'),
(3, 1, 3, '2021-09-08', '13:00:00', '14:00:00'),
(3, 1, 3, '2021-09-13', '13:00:00', '14:00:00'),
(3, 1, 3, '2021-09-14', '13:00:00', '14:00:00'),
(3, 1, 3, '2021-09-15', '13:00:00', '14:00:00'),
(3, 1, 3, '2021-09-16', '13:00:00', '14:00:00'),
(3, 1, 3, '2021-09-17', '13:00:00', '14:00:00'),
(3, 1, 3, '2021-09-20', '13:00:00', '14:00:00');

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
