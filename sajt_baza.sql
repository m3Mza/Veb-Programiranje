-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 12, 2024 at 02:46 PM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `DodajRecept` (IN `p_ime` VARCHAR(255), IN `p_opis` TEXT, IN `p_instrukcije` TEXT, IN `p_dijeta` VARCHAR(255), IN `p_napravio` VARCHAR(255), IN `p_kategorija` VARCHAR(255))   BEGIN
    DECLARE v_recept_id INT;
    DECLARE v_kategorija_id INT;

    -- Ubacivanje u recepti tabelu sa kategorijom
    INSERT INTO recepti (ime, opis, instrukcije, dijeta, napravio, kategorija)
    VALUES (p_ime, p_opis, p_instrukcije, p_dijeta, p_napravio, p_kategorija); -- Dodano p_kategorija

    -- Uzima id od recepta
    SET v_recept_id = LAST_INSERT_ID();

    -- Poklapanje id kategorije
    SELECT id INTO v_kategorija_id
    FROM kategorije
    WHERE naziv = p_kategorija;

    -- Ako se poklope ID onda ubacuje recept i u drugu tabelu
    IF v_kategorija_id IS NOT NULL THEN
        INSERT INTO kategorije_recepata (recept_id, kategorija_id)
        VALUES (v_recept_id, v_kategorija_id);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegistracijaKorisnika` (IN `korisnickoIme` VARCHAR(255), IN `lozinka` VARCHAR(255))   BEGIN
    DECLARE korisnikPostoji INT;

    -- Proveri da li korisničko ime već postoji
    SELECT COUNT(*) INTO korisnikPostoji 
    FROM korisnici 
    WHERE korisnicko_ime = korisnickoIme;

    IF korisnikPostoji > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Korisničko ime je zauzeto.';
    ELSE
        INSERT INTO korisnici (korisnicko_ime, lozinka) VALUES (korisnickoIme, lozinka);
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `kategorije`
--

CREATE TABLE `kategorije` (
  `id` int(11) NOT NULL,
  `naziv` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kategorije`
--

INSERT INTO `kategorije` (`id`, `naziv`) VALUES
(1, 'Meso'),
(2, 'Dezerti'),
(3, 'Pasta'),
(4, 'Testo'),
(5, 'Piće'),
(6, 'Riba'),
(7, 'Salate'),
(8, 'Grickalice'),
(9, 'Sosevi'),
(10, 'Kuvano');

-- --------------------------------------------------------

--
-- Table structure for table `kategorije_recepata`
--

CREATE TABLE `kategorije_recepata` (
  `recept_id` int(11) NOT NULL,
  `kategorija_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kategorije_recepata`
--

INSERT INTO `kategorije_recepata` (`recept_id`, `kategorija_id`) VALUES
(13, 3),
(7, 10);

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
(9, 'Mirko', '12345');

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
(7, 'Natašina čorba od krompira i jaja', 'Lagana čorbica', 'Kuvano', 'Vegeterijanac', 'Skuvati jaja u zasebnoj vodi, oguliti. U drugu vodu skuvati krompir, vratiti jaja, sipati vegetu i pasirani paradajz, dokuvati još malko', 'Mirko'),
(13, 'Test za kategoriju', '123124', 'Pasta', 'Vegeterijanac', '21512351', 'Mirko');

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
-- Structure for view `view_recepti`
--
DROP TABLE IF EXISTS `view_recepti`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_recepti`  AS SELECT `recepti`.`id` AS `id`, `recepti`.`ime` AS `ime`, `recepti`.`opis` AS `opis`, `recepti`.`kategorija` AS `kategorija`, `recepti`.`dijeta` AS `dijeta`, `recepti`.`instrukcije` AS `instrukcije`, `recepti`.`napravio` AS `napravio` FROM `recepti``recepti`  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategorije`
--
ALTER TABLE `kategorije`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategorije_recepata`
--
ALTER TABLE `kategorije_recepata`
  ADD PRIMARY KEY (`recept_id`,`kategorija_id`),
  ADD KEY `kategorija_id` (`kategorija_id`);

--
-- Indexes for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `kategorije`
--
ALTER TABLE `kategorije`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `korisnici`
--
ALTER TABLE `korisnici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `recepti`
--
ALTER TABLE `recepti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kategorije_recepata`
--
ALTER TABLE `kategorije_recepata`
  ADD CONSTRAINT `kategorije_recepata_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `recepti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kategorije_recepata_ibfk_2` FOREIGN KEY (`kategorija_id`) REFERENCES `kategorije` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
