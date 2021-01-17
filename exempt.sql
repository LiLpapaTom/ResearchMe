-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Φιλοξενητής: 127.0.0.1
-- Χρόνος δημιουργίας: 25 Ιουν 2020 στις 20:26:18
-- Έκδοση διακομιστή: 10.4.11-MariaDB
-- Έκδοση PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `exempt`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `article`
--

CREATE TABLE `article` (
  `articleID` int(11) NOT NULL,
  `journal` varchar(500) NOT NULL,
  `volume` tinyint(3) UNSIGNED NOT NULL,
  `number` tinyint(3) UNSIGNED NOT NULL,
  `pages` varchar(10) NOT NULL,
  `doi` varchar(50) NOT NULL,
  `publicationID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `article`
--

INSERT INTO `article` (`articleID`, `journal`, `volume`, `number`, `pages`, `doi`, `publicationID`) VALUES
(1, 'ERCIM News', 255, 59, '-', 'https://ercim-news.ercim.eu/en109/r-i/bringing-bus', 59),
(2, 'IEEE Trans. Serv. Comput.', 7, 60, '614-627', 'https://doi.org/10.1109/TSC.2013.11', 60),
(3, 'Future Gener. Comput. Syst.', 78, 61, '155-175', 'https://doi.org/10.1016/j.future.2016.11.014', 61),
(4, 'Future Gener. Comput. Syst.', 67, 62, '206-226', 'https://doi.org/10.1016/j.future.2016.10.008', 62),
(5, 'IJSSOE', 5, 63, '78-103', 'https://doi.org/10.4018/IJSSOE.2015100104', 63),
(6, 'Trans. Large Scale Data Knowl. Centered Syst.', 20, 64, '59-89', 'https://doi.org/10.1007/978-3-662-46703-9_3', 64),
(7, 'Array', 3, 65, '100011', 'https://doi.org/10.1016/j.array.2019.100011', 65),
(8, 'J. Big Data', 6, 66, '15', 'https://doi.org/10.1186/s40537-019-0178-3', 66),
(9, 'J. Cloud Computing', 8, 67, '18', 'https://doi.org/10.1186/s13677-019-0143-x', 67),
(10, 'J. Cloud Computing', 8, 68, '20', 'https://doi.org/10.1186/s13677-019-0138-7', 68),
(11, 'Publications', 5, 69, '1', 'https://doi.org/10.3390/publications5010001', 69),
(12, 'Service Oriented Computing and Applications', 11, 70, '301-314', 'https://doi.org/10.1007/s11761-017-0210-4', 70),
(13, 'ACM Trans. Interact. Intell. Syst.', 3, 71, '25:1-25:31', 'https://doi.org/10.1145/2559979', 71),
(14, 'ACM Comput. Surv.', 46, 72, '1:1-1:58', 'https://doi.org/10.1145/2522968.2522969', 72),
(15, 'Service Oriented Computing and Applications', 7, 73, '231-257', 'https://doi.org/10.1007/s11761-012-0126-y', 73),
(16, 'Int. J. High Perform. Comput. Appl.', 17, 74, '269-280', 'https://doi.org/10.1177/1094342003173008', 74);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `author`
--

CREATE TABLE `author` (
  `authorID` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `surname` varchar(25) NOT NULL,
  `orcID` varchar(19) NOT NULL,
  `personalURL` varchar(50) NOT NULL,
  `attribute` enum('Professor','Researcher','Technical Personnel','Research Personnel') NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `author`
--

INSERT INTO `author` (`authorID`, `name`, `surname`, `orcID`, `personalURL`, `attribute`, `userID`) VALUES
(1, 'Kyriakos', 'Kritikos', '0000-0001-9633-1610', '-', '', 47),
(2, 'Pawel', 'Skrzypek', '-', '-', '', 47),
(3, 'Feroz', 'Zahid', '-', '-', '', 47),
(4, 'Alexandru', 'Moga', '-', '-', '', 47),
(5, 'Oliviu', 'Matei', '-', '-', '', 47),
(6, 'Manos', 'Papoutsakis', '-', '-', '', 47),
(7, 'Kostas', 'Magoutis', '0000-0002-7288-5923', '-', '', 47),
(8, 'Sotiris', 'Ioannidis', '-', '-', '', 47),
(9, 'Chrysostomos', 'Zeginis', '-', '-', '', 47),
(10, 'Eleni', 'Politaki', '-', '-', '', 47),
(11, 'Dimitris', 'Plexousakis', '-', '-', '', 47),
(12, 'Marta', 'Rózanska', '-', '-', '', 47),
(13, 'Andreas', 'Paravoliasis', '-', '-', '', 47),
(14, 'Rafael', 'Brundo Uriarte', '0000-0003-0750-7376', '-', '', 47),
(15, 'Rocco', 'De Nicola', '-', '-', '', 47),
(16, 'Geir', 'Horn', '-', '-', '', 47),
(17, 'Robert', 'Woitsch', '-', '-', '', 47),
(18, 'Damianos', 'Metallidis', '-', '-', '', 47),
(19, 'Emanuele', 'Laurenzi', '-', '-', '', 47),
(20, 'Frank', 'Griesinger', '-', '-', '', 47),
(21, 'Jörg', 'Domaschka', '-', '-', '', 47),
(22, 'Knut', 'Hinkelmann', '-', '-', '', 47),
(23, 'Sabrina', 'Kurjakovic', '-', '-', '', 47),
(24, 'Benjamin', 'Lammel', '-', '-', '', 47),
(25, 'Pierluigi', 'Plebani', '-', '-', '', 47),
(26, 'Daniel', 'Seybold', '-', '-', '', 47),
(27, 'Nikolay', 'Nikolov', '-', '-', '', 47),
(28, 'Tom', 'Kirkham', '-', '-', '', 47),
(29, 'Philippe', 'Massonet', '-', '-', '', 47),
(30, 'Alessandro', 'Rossini', '-', '-', '', 47),
(31, 'Panagiotis', 'Garefalakis', '-', '-', '', 47),
(32, 'Konstantina', 'Konsolaki', '-', '-', '', 47),
(33, 'Yannis', 'Rousakis', '-', '-', '', 47),
(34, 'Sylvain', 'Kubicki', '0000-0003-2985-0378', '-', '', 47),
(35, 'Eric', 'Dubois 0001', '-', '-', '', 47),
(36, 'Fabio', 'Paternò', '0000-0001-8355-6909', '-', '', 47),
(37, 'Martin', 'Treiber', '-', '-', '', 47),
(38, 'Daniel', 'Schall 0001', '-', '-', '', 47),
(39, 'Schahram', 'Dustdar', '0000-0001-6872-8821', '-', '', 47),
(40, 'Barbara', 'Pernici', '0000-0002-2034-9774', '-', '', 47),
(41, 'Alexandre', 'Mello Ferreira', '-', '-', '', 47),
(42, 'Marco', 'Comuzzi', '0000-0002-6944-4705', '-', '', 47),
(43, 'Somnath', 'Mazumdar', '0000-0002-1751-2569', '-', '', 47),
(44, 'Yiannis', 'Verginadis', '-', '-', '', 47),
(45, 'Joaquin', 'Iranzo', '-', '-', '', 47),
(46, 'Roman', 'Sosa Gonzalez', '-', '-', '', 47),
(47, 'Georgia', 'M. Kapitsaki', '-', '-', '', 47),
(48, 'Bartosz', 'Kryza', '-', '-', '', 47),
(49, 'Costantino', 'Thanos', '-', '-', '', 47),
(50, 'Friederike', 'Klan', '0000-0002-1856-7334', '-', '', 47),
(51, 'George', 'Baryannis', '0000-0002-2118-5812', '-', '', 47),
(52, 'Dimitris', 'Kotzinos', '-', '-', '', 47);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `authors`
--

CREATE TABLE `authors` (
  `authorsID` int(11) NOT NULL,
  `author` int(11) DEFAULT NULL,
  `author2` int(11) DEFAULT NULL,
  `author3` int(11) DEFAULT NULL,
  `author4` int(11) DEFAULT NULL,
  `author5` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Άδειασμα δεδομένων του πίνακα `authors`
--

INSERT INTO `authors` (`authorsID`, `author`, `author2`, `author3`, `author4`, `author5`) VALUES
(1, 1, 2, 3, 1, 1),
(2, 2, 4, 5, 1, 2),
(3, 1, 6, 1, 1, 1),
(4, 7, 1, 6, 8, 7),
(5, 1, 9, 10, 11, 1),
(6, 1, 2, 1, 1, 1),
(7, 2, 12, 1, 2, 2),
(8, 1, 12, 1, 1, 1),
(9, 12, 1, 9, 13, 12),
(10, 1, 14, 15, 1, 1),
(11, 1, 16, 1, 1, 1),
(12, 1, 7, 8, 6, 1),
(13, 1, 2, 1, 1, 1),
(14, 1, 11, 17, 1, 1),
(15, 1, 11, 17, 1, 1),
(16, 1, 14, 1, 1, 1),
(17, 9, 1, 11, 18, 9),
(18, 13, 11, 1, 19, 13),
(19, 1, 9, 20, 1, 1),
(20, 21, 22, 1, 23, 24),
(21, 1, 7, 11, 1, 1),
(22, 11, 25, 1, 11, 11),
(23, 1, 11, 1, 1, 1),
(24, 1, 11, 1, 1, 1),
(25, 1, 20, 26, 21, 1),
(26, 18, 1, 9, 11, 18),
(27, 11, 1, 27, 11, 11),
(28, 1, 27, 28, 1, 1),
(29, 29, 1, 29, 29, 29),
(30, 1, 21, 30, 1, 1),
(31, 9, 1, 11, 9, 9),
(32, 1, 30, 21, 9, 1),
(33, 31, 32, 7, 1, 33),
(34, 1, 11, 1, 1, 1),
(35, 1, 9, 32, 11, 1),
(36, 1, 11, 1, 1, 1),
(37, 1, 34, 35, 9, 1),
(38, 1, 11, 9, 34, 1),
(39, 1, 36, 1, 1, 1),
(40, 1, 37, 38, 39, 1),
(41, 1, 36, 1, 1, 1),
(42, 1, 40, 41, 42, 1),
(43, 25, 42, 1, 11, 25),
(44, 1, 42, 25, 1, 1),
(45, 42, 1, 11, 42, 42),
(46, 1, 11, 1, 1, 1),
(47, 11, 1, 11, 11, 11),
(48, 1, 11, 1, 1, 1),
(49, 1, 7, 6, 1, 1),
(50, 1, 43, 26, 44, 1),
(51, 1, 9, 45, 46, 26),
(52, 1, 30, 47, 21, 1),
(53, 48, 29, 1, 28, 48),
(54, 29, 1, 49, 50, 29),
(55, 49, 51, 1, 11, 49),
(56, 1, 11, 1, 1, 1),
(57, 1, 11, 9, 1, 1),
(58, 52, 1, 11, 52, 52),
(59, 1, 2, 3, 1, 1),
(60, 2, 4, 5, 1, 2),
(61, 1, 6, 1, 1, 1),
(62, 7, 1, 6, 8, 7),
(63, 1, 9, 10, 11, 1),
(64, 1, 2, 1, 1, 1),
(65, 2, 12, 1, 2, 2),
(66, 1, 12, 1, 1, 1),
(67, 12, 1, 9, 13, 12),
(68, 1, 14, 15, 1, 1),
(69, 1, 16, 1, 1, 1),
(70, 1, 7, 8, 6, 1),
(71, 1, 2, 1, 1, 1),
(72, 1, 11, 17, 1, 1),
(73, 1, 11, 17, 1, 1),
(74, 1, 14, 1, 1, 1),
(75, 3, 3, 3, 3, 3),
(76, 5, 5, 5, 5, 5),
(77, 1, 3, 1, 1, 1),
(78, 16, 16, 16, 16, 16);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `book`
--

CREATE TABLE `book` (
  `bookID` int(11) NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `volume` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `series` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `edition` varchar(50) NOT NULL,
  `month` tinyint(3) UNSIGNED NOT NULL,
  `ISBN` varchar(20) NOT NULL,
  `ISSN` varchar(20) NOT NULL,
  `publicationID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `book`
--

INSERT INTO `book` (`bookID`, `publisher`, `volume`, `number`, `series`, `address`, `edition`, `month`, `ISBN`, `ISSN`, `publicationID`) VALUES
(1, 'Book Publisher#1', 10, 0, '', '', '9', 1, '123456', '123456', 75),
(2, 'Book Publisher#2', 0, 10, '', 'Address', '9', 0, '2345', '2345', 76);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `inbook`
--

CREATE TABLE `inbook` (
  `inbookID` int(11) NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `chapter` varchar(20) NOT NULL,
  `pages` varchar(10) NOT NULL,
  `volume` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `series` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `edition` varchar(50) NOT NULL,
  `ISBN` varchar(20) NOT NULL,
  `ISSN` varchar(20) NOT NULL,
  `publicationID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `inbook`
--

INSERT INTO `inbook` (`inbookID`, `publisher`, `chapter`, `pages`, `volume`, `number`, `series`, `type`, `address`, `edition`, `ISBN`, `ISSN`, `publicationID`) VALUES
(1, 'Publisher', '2', '', 5, 0, '', '', '', '', '6789', '6789', 77),
(2, 'Publisher#3', '', '10-60', 6, 0, '', '', '', '', '7890', '7890', 78);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `inproceedings`
--

CREATE TABLE `inproceedings` (
  `inproceedingsID` int(11) NOT NULL,
  `booktitle` varchar(100) NOT NULL,
  `editor` varchar(100) NOT NULL,
  `volume` tinyint(3) UNSIGNED NOT NULL,
  `number` tinyint(3) UNSIGNED NOT NULL,
  `series` varchar(100) NOT NULL,
  `pages` varchar(10) NOT NULL,
  `address` varchar(100) NOT NULL,
  `organization` varchar(100) NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `doi` varchar(50) NOT NULL,
  `publicationID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `inproceedings`
--

INSERT INTO `inproceedings` (`inproceedingsID`, `booktitle`, `editor`, `volume`, `number`, `series`, `pages`, `address`, `organization`, `publisher`, `doi`, `publicationID`) VALUES
(1, 'ESOCC', '-', 1, 1, '-', '56-73', '-', '-', '-', 'https://doi.org/10.1007/978-3-030-44769-4_5', 1),
(2, 'CLOUD', '-', 2, 2, '-', '291-295', '-', '-', '-', 'https://doi.org/10.1109/CLOUD.2019.00056', 2),
(3, 'BIS (1)', '-', 3, 3, '-', '435-449', '-', '-', '-', 'https://doi.org/10.1007/978-3-030-20485-3_34', 3),
(4, 'SERVICES', '-', 4, 4, '-', '200-205', '-', '-', '-', 'https://doi.org/10.1109/SERVICES.2019.00056', 4),
(5, 'UCC Companion', '-', 5, 5, '-', '51-58', '-', '-', '-', 'https://doi.org/10.1145/3368235.3368840', 5),
(6, 'UCC Companion', '-', 6, 6, '-', '161-168', '-', '-', '-', 'https://doi.org/10.1109/UCC-Companion.2018.00051', 6),
(7, 'CAMAD', '-', 7, 7, '-', '1-6', '-', '-', '-', 'https://doi.org/10.1109/CAMAD.2019.8858460', 7),
(8, 'CCGRID', '-', 8, 8, '-', '684-689', '-', '-', '-', 'https://doi.org/10.1109/CCGRID.2019.00088', 8),
(9, 'CLOSER', '-', 9, 9, '-', '300-307', '-', '-', '-', 'https://doi.org/10.5220/0007706503000307', 9),
(10, 'UCC Companion', '-', 10, 10, '-', '21-28', '-', '-', '-', 'https://doi.org/10.1145/3368235.3368833', 10),
(11, 'UCC Companion', '-', 11, 11, '-', '171-173', '-', '-', '-', 'https://doi.org/10.1145/3368235.3370267', 11),
(12, 'CLOSER', '-', 12, 12, '-', '409-417', '-', '-', '-', 'https://doi.org/10.5220/0006684604090417', 12),
(13, 'ESOCC Workshops', '-', 13, 13, '-', '20-34', '-', '-', '-', 'https://doi.org/10.1007/978-3-319-79090-9_2', 13),
(14, 'CloudCom', '-', 14, 14, '-', '266-271', '-', '-', '-', 'https://doi.org/10.1109/CloudCom2018.2018.00059', 14),
(15, 'ESOCC', '-', 15, 15, '-', '170-184', '-', '-', '-', 'https://doi.org/10.1007/978-3-319-99819-0_13', 15),
(16, 'CrossCloud@EuroSys', '-', 16, 16, '-', '2:1-2:6', '-', '-', '-', 'https://doi.org/10.1145/3195870.3195872', 16),
(17, 'TrustCom/BigDataSE', '-', 17, 17, '-', '1730-1737', '-', '-', '-', 'https://doi.org/10.1109/TrustCom/BigDataSE.2018.00', 17),
(18, 'CLOSER', '-', 18, 18, '-', '63-74', '-', '-', '-', 'https://doi.org/10.5220/0006238000630074', 18),
(19, 'CLOSER (Selected Papers)', '-', 19, 19, '-', '237-261', '-', '-', '-', 'https://doi.org/10.1007/978-3-319-94959-8_13', 19),
(20, 'CLOSER', '-', 20, 20, '-', '404-412', '-', '-', '-', 'https://doi.org/10.5220/0006299804040412', 20),
(21, 'CLOSER', '-', 21, 21, '-', '479-486', '-', '-', '-', 'https://doi.org/10.5220/0006306304790486', 21),
(22, 'ESOCC Workshops', '-', 22, 22, '-', '35-52', '-', '-', '-', 'https://doi.org/10.1007/978-3-319-79090-9_3', 22),
(23, 'FiCloud', '-', 23, 23, '-', '241-248', '-', '-', '-', 'https://doi.org/10.1109/FiCloud.2017.12', 23),
(24, 'CAiSE Workshops', '-', 24, 24, '-', '181-192', '-', '-', '-', 'https://doi.org/10.1007/978-3-319-39564-7_18', 24),
(25, 'CloudCom', '-', 25, 25, '-', '431-439', '-', '-', '-', 'https://doi.org/10.1109/CloudCom.2016.0073', 25),
(26, 'Cloud Forward', '-', 26, 26, '-', '24-33', '-', '-', '-', 'https://doi.org/10.1016/j.procs.2016.08.277', 26),
(27, 'Cloud Forward', '-', 27, 27, '-', '84-93', '-', '-', '-', 'https://doi.org/10.1016/j.procs.2016.08.283', 27),
(28, 'DPM/QASA@ESORICS', '-', 28, 28, '-', '47-64', '-', '-', '-', 'https://doi.org/10.1007/978-3-319-29883-2_4', 28),
(29, 'ESOCC', '-', 29, 29, '-', '87-101', '-', '-', '-', 'https://doi.org/10.1007/978-3-319-44482-6_6', 29),
(30, 'ESOCC', '-', 30, 30, '-', '102-117', '-', '-', '-', 'https://doi.org/10.1007/978-3-319-44482-6_7', 30),
(31, 'CLOUD', '-', 31, 31, '-', '686-693', '-', '-', '-', 'https://doi.org/10.1109/CLOUD.2015.96', 31),
(32, 'ICWS', '-', 32, 32, '-', '327-335', '-', '-', '-', 'https://doi.org/10.1109/ICWS.2012.27', 32),
(33, 'WWW (Companion Volume)', '-', 33, 33, '-', '549-550', '-', '-', '-', 'https://doi.org/10.1145/2187980.2188121', 33),
(34, 'SERVICES I', '-', 34, 34, '-', '567-574', '-', '-', '-', 'https://doi.org/10.1109/SERVICES-1.2008.53', 34),
(35, 'COMPSAC (2)', '-', 35, 35, '-', '467-472', '-', '-', '-', 'https://doi.org/10.1109/COMPSAC.2007.181', 35),
(36, 'ECOWS', '-', 36, 36, '-', '181-190', '-', '-', '-', 'https://doi.org/10.1109/ECOWS.2007.20', 36),
(37, 'ICSOC Workshops', '-', 37, 37, '-', '151-164', '-', '-', '-', 'https://doi.org/10.1007/978-3-540-93851-4_15', 37),
(38, 'SMRR', '-', 38, 38, '-', '-', '-', '-', '-', 'http://ceur-ws.org/Vol-243/SMR2-2007-paper9.pdf', 38),
(39, 'ECOWS', '-', 39, 39, '-', '265-274', '-', '-', '-', 'https://doi.org/10.1109/ECOWS.2006.34', 39),
(40, 'ESOCC Workshops', '-', 40, 40, '-', '104-111', '-', '-', '-', 'https://doi.org/10.1007/978-3-319-72125-5_8', 40),
(41, 'ESOCC Workshops', '-', 41, 41, '-', '189-200', '-', '-', '-', 'https://doi.org/10.1007/978-3-319-72125-5_15', 41),
(42, 'Cloud Forward', '-', 42, 42, '-', '53-66', '-', '-', '-', 'https://doi.org/10.1016/j.procs.2015.09.223', 42),
(43, 'Cloud Forward', '-', 43, 43, '-', '103-115', '-', '-', '-', 'https://doi.org/10.1016/j.procs.2015.09.227', 43),
(44, 'CloudCom', '-', 44, 44, '-', '1-9', '-', '-', '-', 'https://doi.org/10.1109/CloudCom.2014.170', 44),
(45, 'ESOCC', '-', 45, 45, '-', '138-147', '-', '-', '-', 'https://doi.org/10.1007/978-3-662-44879-3_10', 45),
(46, 'ESOCC Workshops', '-', 46, 46, '-', '206-220', '-', '-', '-', 'https://doi.org/10.1007/978-3-319-14886-1_19', 46),
(47, 'ESOCC', '-', 47, 47, '-', '188-195', '-', '-', '-', 'https://doi.org/10.1007/978-3-642-40651-5_16', 47),
(48, 'WOD', '-', 48, 48, '-', '3:1-3:6', '-', '-', '-', 'https://doi.org/10.1145/2500410.2500414', 48),
(49, 'WISE', '-', 49, 49, '-', '704-711', '-', '-', '-', 'https://doi.org/10.1007/978-3-642-35063-4_56', 49),
(50, 'ICSOC Workshops', '-', 50, 50, '-', '147-161', '-', '-', '-', 'https://doi.org/10.1007/978-3-642-31875-7_15', 50),
(51, 'ECOWS', '-', 51, 51, '-', '133-140', '-', '-', '-', 'https://doi.org/10.1109/ECOWS.2011.19', 51),
(52, 'CEC', '-', 52, 52, '-', '104-112', '-', '-', '-', 'https://doi.org/10.1109/CEC.2011.27', 52),
(53, 'AVI', '-', 53, 53, '-', '89-92', '-', '-', '-', 'https://doi.org/10.1145/1842993.1843009', 53),
(54, 'EICS', '-', 54, 54, '-', '261-266', '-', '-', '-', 'https://doi.org/10.1145/1822018.1822059', 54),
(55, 'Mashups', '-', 55, 55, '-', '2:1-2:8', '-', '-', '-', 'https://doi.org/10.1145/1944999.1945001', 55),
(56, 'ICSOC/ServiceWave', '-', 56, 56, '-', '99-114', '-', '-', '-', 'https://doi.org/10.1007/978-3-642-10383-4_7', 56),
(57, 'CEC', '-', 57, 57, '-', '137-145', '-', '-', '-', 'https://doi.org/10.1109/CEC.2009.17', 57),
(58, 'ServiceWave', '-', 58, 58, '-', '312-323', '-', '-', '-', 'https://doi.org/10.1007/978-3-540-89897-9_27', 58);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `publication`
--

CREATE TABLE `publication` (
  `publicationID` int(11) NOT NULL,
  `type` enum('Article','Inproceedings','Book','Inbook','Other') NOT NULL,
  `authorsID` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `year` int(4) NOT NULL,
  `note` varchar(100) NOT NULL,
  `month` int(2) NOT NULL,
  `url` varchar(50) NOT NULL,
  `pubKey` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `publication`
--

INSERT INTO `publication` (`publicationID`, `type`, `authorsID`, `title`, `year`, `note`, `month`, `url`, `pubKey`) VALUES
(1, 'Inproceedings', 1, 'Are Cloud Platforms Ready for Multi-cloud?', 2020, '-', 4, 'db/conf/esocc/esocc2020.html#KritikosSZ20', 'conf/esocc/KritikosSZ20'),
(2, 'Inproceedings', 2, 'Towards the Modelling of Hybrid Cloud Applications.', 2019, '-', 12, 'db/conf/IEEEcloud/IEEEcloud2019.html#KritikosSMM19', 'conf/IEEEcloud/KritikosSMM19'),
(3, 'Inproceedings', 3, 'Towards an Optimized, Cloud-Agnostic Deployment of Hybrid Applications.', 2019, '-', 12, 'db/conf/bis/bis2019-1.html#KritikosS19', 'conf/bis/KritikosS19'),
(4, 'Inproceedings', 4, 'Simulation-as-a-Service with Serverless Computing.', 2019, '-', 12, 'db/conf/services/services2019.html#KritikosS19', 'conf/services/KritikosS19'),
(5, 'Inproceedings', 5, 'Are Cloud Modelling Languages Ready for Multi-Cloud?', 2019, '-', 11, 'db/conf/ucc/ucc2019c.html#KritikosS19', 'conf/ucc/KritikosS19'),
(6, 'Inproceedings', 6, 'A Review of Serverless Frameworks.', 2018, '-', 12, 'db/conf/ucc/ucc2018c.html#KritikosS18', 'conf/ucc/KritikosS18'),
(7, 'Inproceedings', 7, 'Towards Configurable Vulnerability Assessment in the Cloud.', 2019, '-', 12, 'db/conf/camad/camad2019.html#KritikosPIM19', 'conf/camad/KritikosPIM19'),
(8, 'Inproceedings', 8, 'Towards Configurable Cloud Application Security.', 2019, '-', 12, 'db/conf/ccgrid/ccgrid2019.html#KritikosPIM19', 'conf/ccgrid/KritikosPIM19'),
(9, 'Inproceedings', 9, 'Towards the Modelling of Adaptation Rules and Histories for Multi-Cloud Applications.', 2019, '-', 6, 'db/conf/closer/closer2019.html#KritikosZPP19', 'conf/closer/KritikosZPP19'),
(10, 'Inproceedings', 10, 'Towards an Integration Methodology for Multi-Cloud Application Management Platforms.', 2019, '-', 11, 'db/conf/ucc/ucc2019c.html#KritikosSR19', 'conf/ucc/KritikosSR19'),
(11, 'Inproceedings', 11, 'Good Bye Vendor Lock-in: Getting your Cloud Applications Multi-Cloud Ready!', 2019, '-', 11, 'db/conf/ucc/ucc2019c.html#RozanskaK19', 'conf/ucc/RozanskaK19'),
(12, 'Inproceedings', 12, 'Towards Multi-cloud SLO Evaluation.', 2018, '-', 4, 'db/conf/closer/closer2018.html#KritikosZPP18', 'conf/closer/KritikosZPP18'),
(13, 'Inproceedings', 6, 'CEP-Based SLO Evaluation.', 2017, '-', 5, 'db/conf/esocc/esocc2017w.html#KritikosZPP17', 'conf/esocc/KritikosZPP17'),
(14, 'Inproceedings', 14, 'Towards Distributed SLA Management with Smart Contracts and Blockchain.', 2018, '-', 12, 'db/conf/cloudcom/cloudcom2018.html#UriarteNK18', 'conf/cloudcom/UriarteNK18'),
(15, 'Inproceedings', 14, 'IaaS Service Selection Revisited.', 2018, '-', 12, 'db/conf/esocc/esocc2018.html#KritikosH18', 'conf/esocc/KritikosH18'),
(16, 'Inproceedings', 16, 'Towards Model-Driven Application Security across Clouds.', 2018, '-', 12, 'db/conf/eurosys/crosscloud2018.html#PapoutsakisKMI', 'conf/eurosys/PapoutsakisKMI18'),
(17, 'Inproceedings', 17, 'Towards Dynamic and Optimal Big Data Placement.', 2018, '-', 12, 'db/conf/trustcom/trustcom2018.html#Kritikos18', 'conf/trustcom/Kritikos18'),
(18, 'Inproceedings', 18, 'Towards Semantic KPI Measurement.', 2017, '-', 6, 'db/conf/closer/closer2017.html#KritikosPW17', 'conf/closer/KritikosPW17'),
(19, 'Inproceedings', 19, 'A Flexible Semantic KPI Measurement System.', 2017, '-', 12, 'db/conf/closer/closer2017s.html#KritikosPW17a', 'conf/closer/KritikosPW17a'),
(20, 'Inproceedings', 20, 'Semantic SLA for Clouds: Combining SLAC and OWL-Q.', 2017, '-', 6, 'db/conf/closer/closer2017.html#KritikosU17', 'conf/closer/KritikosU17'),
(21, 'Inproceedings', 21, 'A Cross-layer Monitoring Solution based on Quality Models.', 2017, '-', 6, 'db/conf/closer/closer2017.html#MetallidisZKP17', 'conf/closer/MetallidisZKP17'),
(22, 'Inproceedings', 22, 'Towards Business-to-IT Alignment in the Cloud.', 2017, '-', 12, 'db/conf/esocc/esocc2017w.html#KritikosLH17', 'conf/esocc/KritikosLH17'),
(23, 'Inproceedings', 23, 'A Cross-Layer BPaaS Adaptation Framework.', 2017, '-', 12, 'db/conf/ficloud/ficloud2017.html#KritikosZGSD17', 'conf/ficloud/KritikosZGSD17'),
(24, 'Inproceedings', 23, 'A Modelling Environment for Business Process as a Service.', 2016, '-', 12, 'db/conf/caise/caisews2016.html#HinkelmannKKLW16', 'conf/caise/HinkelmannKKLW16'),
(25, 'Inproceedings', 25, 'Towards Knowledge-Based Assisted IaaS Selection.', 2016, '-', 12, 'db/conf/cloudcom/cloudcom2016.html#KritikosMP16', 'conf/cloudcom/KritikosMP16'),
(26, 'Inproceedings', 26, 'Semantic SLAs for Services with Q-SLA.', 2016, '-', 12, 'db/conf/cloudforward/cloudforward2016.html#Kritiko', 'conf/cloudforward/KritikosPP16'),
(27, 'Inproceedings', 27, 'An Integrated Meta-model for Cloud Application Security Modelling.', 2016, '-', 12, 'db/conf/cloudforward/cloudforward2016.html#Kritiko', 'conf/cloudforward/KritikosM16'),
(28, 'Inproceedings', 28, 'Security-Based Adaptation of Multi-cloud Applications.', 2015, '-', 12, 'db/conf/esorics/dpm2015.html#KritikosM15', 'conf/esorics/KritikosM15'),
(29, 'Inproceedings', 29, 'Subsumption Reasoning for QoS-Based Service Matchmaking.', 2016, '-', 12, 'db/conf/esocc/esocc2016.html#KritikosP16', 'conf/esocc/KritikosP16'),
(30, 'Inproceedings', 30, 'Towards Combined Functional and Non-functional Semantic Service Discovery.', 2016, '-', 12, 'db/conf/esocc/esocc2016.html#KritikosP16a', 'conf/esocc/KritikosP16a'),
(31, 'Inproceedings', 31, 'Multi-cloud Application Design through Cloud Service Composition.', 2015, '-', 12, 'db/conf/IEEEcloud/IEEEcloud2015.html#KritikosP15', 'conf/IEEEcloud/KritikosP15'),
(32, 'Inproceedings', 32, 'Towards Optimal and Scalable Non-functional Service Matchmaking Techniques.', 2012, '-', 12, 'db/conf/icws/icws2012.html#KritikosP12', 'conf/icws/KritikosP12'),
(33, 'Inproceedings', 33, 'Towards optimizing the non-functional service matchmaking time.', 2012, '-', 12, 'db/conf/www/www2012c.html#KritikosP12', 'conf/www/KritikosP12'),
(34, 'Inproceedings', 23, 'Evaluation of QoS-Based Web Service Matchmaking Algorithms.', 2008, '-', 12, 'db/conf/services/services2008-1.html#KritikosP08', 'conf/services/KritikosP08'),
(35, 'Inproceedings', 35, 'Requirements for QoS-based Web Service Description and Discovery.', 2007, '-', 12, 'db/conf/compsac/compsac2007-2.html#KritikosP07', 'conf/compsac/KritikosP07'),
(36, 'Inproceedings', 23, 'Semantic QoS-based Web Service Discovery Algorithms.', 2007, '-', 12, 'db/conf/ecows/ecows2007.html#KritikosP07', 'conf/ecows/KritikosP07'),
(37, 'Inproceedings', 37, 'A Semantic QoS-Based Web Service Discovery Engine for Over-Constrained QoS Demands.', 2007, '-', 12, 'db/conf/icsoc/icsoc2007w.html#KritikosP07', 'conf/icsoc/KritikosP07'),
(38, 'Inproceedings', 38, 'OWL-Q for Semantic QoS-based Web Service Description and Discovery.', 2007, '-', 5, 'db/conf/semweb/smrr2007.html#KritikosP07', 'conf/semweb/KritikosP07'),
(39, 'Inproceedings', 39, 'Semantic QoS Metric Matching.', 2006, '-', 12, 'db/conf/ecows/ecows2006.html#KritikosP06', 'conf/ecows/KritikosP06'),
(40, 'Inproceedings', 40, 'A DMN-Based Approach for Dynamic Deployment Modelling of Cloud Applications.', 2016, '-', 12, 'db/conf/esocc/esocc2016w.html#GriesingerSDKW16', 'conf/esocc/GriesingerSDKW16'),
(41, 'Inproceedings', 39, 'A Distributed Cross-Layer Monitoring System Based on QoS Metrics Models.', 2016, '-', 12, 'db/conf/esocc/esocc2016w.html#MetallidisKZP16', 'conf/esocc/MetallidisKZP16'),
(42, 'Inproceedings', 42, 'Integration of DSLs and Migration of Models: A Case Study in the Cloud Computing Domain.', 2015, '-', 12, 'db/conf/cloudforward/cloudforward2015.html#Nikolov', 'conf/cloudforward/NikolovRK15'),
(43, 'Inproceedings', 43, 'Security Enforcement for Multi-Cloud Platforms - The Case of PaaSage.', 2015, '-', 12, 'db/conf/cloudforward/cloudforward2015.html#Kritiko', 'conf/cloudforward/KritikosKKM15'),
(44, 'Inproceedings', 44, 'SRL: A Scalability Rule Language for Multi-cloud Environments.', 2014, '-', 12, 'db/conf/cloudcom/cloudcom2014.html#KritikosDR14', 'conf/cloudcom/KritikosDR14'),
(45, 'Inproceedings', 45, 'Event Pattern Discovery for Cross-Layer Adaptation of Multi-cloud Applications.', 2014, '-', 12, 'db/conf/esocc/esocc2014.html#ZeginisKP14', 'conf/esocc/ZeginisKP14'),
(46, 'Inproceedings', 23, 'Towards a Generic Language for Scalability Rules.', 2014, '-', 12, 'db/conf/esocc/esocc2014w.html#DomaschkaKR14', 'conf/esocc/DomaschkaKR14'),
(47, 'Inproceedings', 47, 'Towards Cross-Layer Monitoring of Multi-Cloud Service-Based Applications.', 2013, '-', 12, 'db/conf/esocc/esocc2013.html#ZeginisKGKMP13', 'conf/esocc/ZeginisKGKMP13'),
(48, 'Inproceedings', 23, 'Linked open GeoData management in the cloud.', 2013, '-', 12, 'db/conf/wod/wod2013.html#KritikosRK13', 'conf/wod/KritikosRK13'),
(49, 'Inproceedings', 49, 'Towards Proactive Cross-Layer Service Adaptation.', 2012, '-', 12, 'db/conf/wise/wise2012.html#ZeginisKKP12', 'conf/wise/ZeginisKKP12'),
(50, 'Inproceedings', 50, 'ECMAF: An Event-Based Cross-Layer Service Monitoring and Adaptation Framework.', 2011, '-', 12, 'db/conf/icsoc/icsoc2011w.html#ZeginisKKP11', 'conf/icsoc/ZeginisKKP11'),
(51, 'Inproceedings', 51, 'An Automatic Requirements Negotiation Approach for Business Services.', 2011, '-', 12, 'db/conf/ecows/ecows2011.html#DuboisKK11', 'conf/ecows/DuboisKK11'),
(52, 'Inproceedings', 52, 'A Goal-Based Business Service Selection Approach.', 2011, '-', 12, 'db/conf/wecwis/cec2011.html#KritikosK11', 'conf/wecwis/KritikosK11'),
(53, 'Inproceedings', 53, 'Task-driven service discovery and selection.', 2010, '-', 12, 'db/conf/avi/avi2010.html#KritikosP10', 'conf/avi/KritikosP10'),
(54, 'Inproceedings', 54, 'Service discovery supported by task models.', 2010, '-', 12, 'db/conf/eics/eics2010.html#KritikosP10', 'conf/eics/KritikosP10'),
(55, 'Inproceedings', 55, 'Modeling context-aware and socially-enriched mashups.', 2010, '-', 12, 'db/conf/ecows/mashups2010.html#TreiberKSDP10', 'conf/ecows/TreiberKSDP10'),
(56, 'Inproceedings', 23, 'Energy-Aware Design of Service-Based Applications.', 2009, '-', 12, 'db/conf/icsoc/icsoc2009.html#FerreiraKP09', 'conf/icsoc/FerreiraKP09'),
(57, 'Inproceedings', 57, 'A Semantic Based Framework for Supporting Negotiation in Service Oriented Architectures.', 2009, '-', 12, 'db/conf/wecwis/cec2009.html#ComuzziKP09', 'conf/wecwis/ComuzziKP09'),
(58, 'Inproceedings', 58, 'Semantic-Aware Service Quality Negotiation.', 2008, '-', 12, 'db/conf/servicewave/servicewave2008.html#ComuzziKP', 'conf/servicewave/ComuzziKP08'),
(59, 'Article', 1, 'Bringing Business Processes to the Cloud.', 2017, '-', 5, 'db/journals/ercim/ercim2017.html#KritikosP17', 'journals/ercim/KritikosP17'),
(60, 'Article', 2, 'Novel Optimal and Scalable Nonfunctional Service Matchmaking Techniques.', 2014, '-', 4, 'db/journals/tsc/tsc7.html#KritikosP14', 'journals/tsc/KritikosP14'),
(61, 'Article', 3, 'Reprint of \"Towards a security-enhanced PaaS platform for multi-cloud applications\".', 2018, '-', 2, 'db/journals/fgcs/fgcs78.html#KritikosKKM18', 'journals/fgcs/KritikosKKM18'),
(62, 'Article', 4, 'Towards a security-enhanced PaaS platform for multi-cloud applications.', 2017, '-', 2, 'db/journals/fgcs/fgcs67.html#KritikosKKM17', 'journals/fgcs/KritikosKKM17'),
(63, 'Article', 5, 'Event Pattern Discovery in Multi-Cloud Service-Based Applications.', 2015, '-', 5, 'db/journals/ijssoe/ijssoe5.html#ZeginisKP15', 'journals/ijssoe/ZeginisKP15'),
(64, 'Article', 6, 'A Cloud-Based, Geospatial Linked Data Management System.', 2015, '-', 5, 'db/journals/tlsdkcs/tlsdkcs20.html#KritikosRK15', 'journals/tlsdkcs/KritikosRK15'),
(65, 'Article', 7, 'A survey on vulnerability assessment tools and databases for cloud-based web applications.', 2019, '-', 2, 'db/journals/array/array3.html#KritikosMPI19', 'journals/array/KritikosMPI19'),
(66, 'Article', 8, 'A survey on data storage and placement methodologies for Cloud-Big Data ecosystem.', 2019, '-', 11, 'db/journals/jbd/jbd6.html#MazumdarSKV19', 'journals/jbd/MazumdarSKV19'),
(67, 'Article', 9, 'Multi-cloud provisioning of business processes.', 2019, '-', 3, 'db/journals/jcloudc/jcloudc8.html#KritikosZIGSGD19', 'journals/jcloudc/KritikosZIGSGD19'),
(68, 'Article', 10, 'The cloud application modelling and execution language.', 2019, '-', 3, 'db/journals/jcloudc/jcloudc8.html#AchilleosKRKDOS1', 'journals/jcloudc/AchilleosKRKDOS19'),
(69, 'Article', 11, 'White Paper on Research Data Service Discoverability.', 2017, '-', 12, 'db/journals/publications/publications5.html#Thanos', 'journals/publications/ThanosKKC17'),
(70, 'Article', 12, 'A specification-based QoS-aware design framework for service-based applications.', 2017, '-', 12, 'db/journals/soca/soca11.html#BaryannisKP17', 'journals/soca/BaryannisKP17'),
(71, 'Article', 6, 'Task model-driven realization of interactive application functionality through services.', 2014, '-', 4, 'db/journals/tiis/tiis3.html#KritikosPP14', 'journals/tiis/KritikosPP14'),
(72, 'Article', 14, 'A survey on service quality description.', 2013, '-', 12, 'db/journals/csur/csur46.html#KritikosPPCCBBKPC13', 'journals/csur/KritikosPPCCBBKPC13'),
(73, 'Article', 14, 'Goal-based business service composition.', 2013, '-', 12, 'db/journals/soca/soca7.html#KritikosK013', 'journals/soca/KritikosK013'),
(74, 'Article', 16, 'A Grid Service-Based Infrastructure for Accessing Scientific Collections: The Case of the ARION System.', 2003, '-', 3, 'db/journals/ijhpca/ijhpca17.html#HoustisLPVKS03', 'journals/ijhpca/HoustisLPVKS03'),
(75, 'Book', 75, 'This is a Book#1', 2001, '', 5, '', ''),
(76, 'Book', 76, 'This is a Book#2', 2002, '', 6, '', ''),
(77, 'Inbook', 77, 'This is an Inbook#2', 2020, '', 5, '', ''),
(78, 'Inbook', 78, 'This is an Inbook#3', 2018, '', 5, '', '');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `test`
--

CREATE TABLE `test` (
  `test1` int(11) NOT NULL,
  `test2` int(11) NOT NULL,
  `test3` int(11) NOT NULL,
  `test4` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `unit`
--

CREATE TABLE `unit` (
  `unitID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` enum('Department','Lab') NOT NULL,
  `institution` enum('University','Research') NOT NULL,
  `description` varchar(350) NOT NULL,
  `website` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(300) NOT NULL,
  `role` enum('Admin','Manager','Publisher') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `user`
--

INSERT INTO `user` (`userID`, `username`, `password`, `role`) VALUES
(41, 'asd', '$2y$10$ftokk9dAprn9IEob21ihZeHzdWirokSMOk7y/1onhUxZ0phdjaFhS', 'Admin'),
(43, 'root', '$2y$10$t6oFVqblCI3z/eLQl495dum7lRK/Cqukcj65Wd830gVL64h7FOYvG', 'Admin'),
(45, 'kkrit', '$2y$10$zvywN7ThHm2BPkwSiMrk0.Eios8pJ3bADjN2KlizI3Tqla11NaDl.', 'Admin'),
(46, 'toor', '$2y$10$jJ1p9sEd4kwBpTKdb81W4.vukpTX8oC5P/qkoQVQz60vn/QBoPiha', 'Admin'),
(47, 'user', '$2y$10$lQu50thXHRj/snHfeJdGSeUk.qV1tpKMP.AwsLJcnrG2H1HcCa8Hq', 'Admin');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`articleID`),
  ADD KEY `publicationID` (`publicationID`);

--
-- Ευρετήρια για πίνακα `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`authorID`),
  ADD KEY `userID` (`userID`);

--
-- Ευρετήρια για πίνακα `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`authorsID`),
  ADD KEY `authors_ibfk_3` (`author`),
  ADD KEY `authors_ibfk_4` (`author2`),
  ADD KEY `authors_ibfk_5` (`author3`),
  ADD KEY `authors_ibfk_6` (`author4`),
  ADD KEY `authors_ibfk_7` (`author5`);

--
-- Ευρετήρια για πίνακα `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`bookID`),
  ADD KEY `publicationID` (`publicationID`);

--
-- Ευρετήρια για πίνακα `inbook`
--
ALTER TABLE `inbook`
  ADD PRIMARY KEY (`inbookID`),
  ADD KEY `publicationID` (`publicationID`);

--
-- Ευρετήρια για πίνακα `inproceedings`
--
ALTER TABLE `inproceedings`
  ADD PRIMARY KEY (`inproceedingsID`),
  ADD KEY `publicationID` (`publicationID`);

--
-- Ευρετήρια για πίνακα `publication`
--
ALTER TABLE `publication`
  ADD PRIMARY KEY (`publicationID`),
  ADD KEY `authorsID` (`authorsID`);

--
-- Ευρετήρια για πίνακα `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`unitID`);

--
-- Ευρετήρια για πίνακα `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `article`
--
ALTER TABLE `article`
  MODIFY `articleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT για πίνακα `author`
--
ALTER TABLE `author`
  MODIFY `authorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT για πίνακα `authors`
--
ALTER TABLE `authors`
  MODIFY `authorsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT για πίνακα `book`
--
ALTER TABLE `book`
  MODIFY `bookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT για πίνακα `inbook`
--
ALTER TABLE `inbook`
  MODIFY `inbookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT για πίνακα `inproceedings`
--
ALTER TABLE `inproceedings`
  MODIFY `inproceedingsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT για πίνακα `publication`
--
ALTER TABLE `publication`
  MODIFY `publicationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT για πίνακα `unit`
--
ALTER TABLE `unit`
  MODIFY `unitID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT για πίνακα `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`publicationID`) REFERENCES `publication` (`publicationID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `author`
--
ALTER TABLE `author`
  ADD CONSTRAINT `author_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `authors`
--
ALTER TABLE `authors`
  ADD CONSTRAINT `authors_ibfk_2` FOREIGN KEY (`author`) REFERENCES `author` (`authorID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `authors_ibfk_3` FOREIGN KEY (`author`) REFERENCES `author` (`authorID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `authors_ibfk_4` FOREIGN KEY (`author2`) REFERENCES `author` (`authorID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `authors_ibfk_5` FOREIGN KEY (`author3`) REFERENCES `author` (`authorID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `authors_ibfk_6` FOREIGN KEY (`author4`) REFERENCES `author` (`authorID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `authors_ibfk_7` FOREIGN KEY (`author5`) REFERENCES `author` (`authorID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`publicationID`) REFERENCES `publication` (`publicationID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `inbook`
--
ALTER TABLE `inbook`
  ADD CONSTRAINT `inbook_ibfk_1` FOREIGN KEY (`publicationID`) REFERENCES `publication` (`publicationID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `inproceedings`
--
ALTER TABLE `inproceedings`
  ADD CONSTRAINT `inproceedings_ibfk_1` FOREIGN KEY (`publicationID`) REFERENCES `publication` (`publicationID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `publication`
--
ALTER TABLE `publication`
  ADD CONSTRAINT `publication_ibfk_1` FOREIGN KEY (`authorsID`) REFERENCES `authors` (`authorsID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
