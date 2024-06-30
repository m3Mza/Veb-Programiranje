-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 30, 2024 at 07:54 PM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sajt_baza`
--

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

CREATE TABLE `korisnici` (
  `id` int(11) NOT NULL,
  `korisnicko_ime` varchar(50) NOT NULL,
  `lozinka` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`id`, `korisnicko_ime`, `lozinka`) VALUES
(1, 'Mirko', '1234'),
(4, 'RobiVicMajstor', 'pikaso321');

-- --------------------------------------------------------

--
-- Table structure for table `recenzije`
--

CREATE TABLE `recenzije` (
  `recenzija_id` int(11) NOT NULL,
  `recept_ime` varchar(255) DEFAULT NULL,
  `lajk` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recenzije`
--

INSERT INTO `recenzije` (`recenzija_id`, `recept_ime`, `lajk`) VALUES
(1, 'Test za Ime', 2),
(4, 'Natašina čorba od krompira i jaja', 6);

-- --------------------------------------------------------

--
-- Table structure for table `recepti`
--

CREATE TABLE `recepti` (
  `id` int(11) NOT NULL,
  `ime` varchar(255) NOT NULL,
  `opis` text,
  `kategorija` varchar(50) DEFAULT NULL,
  `dijeta` varchar(50) DEFAULT NULL,
  `instrukcije` text,
  `napravio` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recepti`
--

INSERT INTO `recepti` (`id`, `ime`, `opis`, `kategorija`, `dijeta`, `instrukcije`, `napravio`) VALUES
(6, 'Test za Ime', 'aaaaa', 'Meso', 'Vegan', '1,2,3,4', 'RobiVicMajstor'),
(7, 'Natašina čorba od krompira i jaja', 'Lagana čorbica', 'Kuvano', 'Vegeterijanac', 'Skuvati jaja u zasebnoj vodi, oguliti. U drugu vodu skuvati krompir, vratiti jaja, sipati vegetu i pasirani paradajz, dokuvati još malko', 'Mirko');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_korisnici`
-- (See below for the actual view)
--
CREATE TABLE `view_korisnici` (
`id` int(11)
,`korisnicko_ime` varchar(50)
,`lozinka` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_recenzije`
-- (See below for the actual view)
--
CREATE TABLE `view_recenzije` (
`recenzija_id` int(11)
,`recept_ime` varchar(255)
,`lajk` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_recepti`
-- (See below for the actual view)
--
CREATE TABLE `view_recepti` (
`id` int(11)
,`ime` varchar(255)
,`opis` text
,`kategorija` varchar(50)
,`dijeta` varchar(50)
,`instrukcije` text
,`napravio` varchar(50)
);

-- --------------------------------------------------------

--
-- Structure for view `view_korisnici`
--
DROP TABLE IF EXISTS `view_korisnici`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_korisnici`  AS SELECT `korisnici`.`id` AS `id`, `korisnici`.`korisnicko_ime` AS `korisnicko_ime`, `korisnici`.`lozinka` AS `lozinka` FROM `korisnici``korisnici`  ;

-- --------------------------------------------------------

--
-- Structure for view `view_recenzije`
--
DROP TABLE IF EXISTS `view_recenzije`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_recenzije`  AS SELECT `recenzije`.`recenzija_id` AS `recenzija_id`, `recenzije`.`recept_ime` AS `recept_ime`, `recenzije`.`lajk` AS `lajk` FROM `recenzije``recenzije`  ;

-- --------------------------------------------------------

--
-- Structure for view `view_recepti`
--
DROP TABLE IF EXISTS `view_recepti`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_recepti`  AS SELECT `recepti`.`id` AS `id`, `recepti`.`ime` AS `ime`, `recepti`.`opis` AS `opis`, `recepti`.`kategorija` AS `kategorija`, `recepti`.`dijeta` AS `dijeta`, `recepti`.`instrukcije` AS `instrukcije`, `recepti`.`napravio` AS `napravio` FROM `recepti``recepti`  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recenzije`
--
ALTER TABLE `recenzije`
  ADD PRIMARY KEY (`recenzija_id`),
  ADD KEY `recept_ime` (`recept_ime`);

--
-- Indexes for table `recepti`
--
ALTER TABLE `recepti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ime` (`ime`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `korisnici`
--
ALTER TABLE `korisnici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `recenzije`
--
ALTER TABLE `recenzije`
  MODIFY `recenzija_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `recepti`
--
ALTER TABLE `recepti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recenzije`
--
ALTER TABLE `recenzije`
  ADD CONSTRAINT `recenzije_ibfk_1` FOREIGN KEY (`recept_ime`) REFERENCES `recepti` (`ime`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
