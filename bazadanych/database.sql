-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2025 at 03:17 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vinyle`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `albumy`
--

CREATE TABLE `albumy` (
  `id` int(11) NOT NULL,
  `tytuł` varchar(255) NOT NULL,
  `opis` text DEFAULT NULL,
  `gatunek_id` int(11) DEFAULT NULL,
  `data_wydania` int(11) DEFAULT NULL,
  `ilosc_plyt` int(11) DEFAULT NULL,
  `piosenki` text DEFAULT NULL,
  `cena` decimal(10,2) DEFAULT NULL,
  `zdjecie` varchar(255) DEFAULT NULL,
  `wykonawca` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `zdjecie2` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `albumy`
--

INSERT INTO `albumy` (`id`, `tytuł`, `opis`, `gatunek_id`, `data_wydania`, `ilosc_plyt`, `piosenki`, `cena`, `zdjecie`, `wykonawca`, `created_at`, `updated_at`, `zdjecie2`) VALUES
(1, 'Ride the lighting', 'test opisu', 2, 1984, 1, 'A1\nFight Fire With Fire\nRide The Lightning\nFor Whom The Bell Tolls\nFade To Black\n\n\nB1\nTrapped Under Ice\nEscape\nCreeping Death\nThe Call Of Ktulu', 100.00, 'uploads/1741522589_R-6091189-1410824757-6361.jpg', 'Metallica', '2025-03-10 15:30:31', '2025-03-10 15:31:27', NULL),
(18, 'Ride the lighting2', '', 6, 0, 0, '', 0.00, 'uploads/1741719360_ezgif-3-38d3c87a91.gif', 'test', '2025-03-11 18:56:00', '2025-03-11 18:56:00', NULL),
(19, 'testowa muzyka', '', 1, 0, 0, '', 0.00, 'uploads/1741719396_2022-04-09_17.02.30.png', 'testowy wykonawcatt', '2025-03-11 18:56:36', '2025-03-11 18:56:36', NULL),
(21, 'zabojstwo liryczne', '', 5, 2014, 2, '', 2000.00, 'uploads/1741955220_600x600bb (6).jpg', 'sentino', '2025-03-14 12:27:00', '2025-03-14 12:27:00', 'uploads/1741955220_600x600bb (2).jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `gatunki`
--

CREATE TABLE `gatunki` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gatunki`
--

INSERT INTO `gatunki` (`id`, `nazwa`) VALUES
(6, 'Elektroniczna'),
(5, 'Hip-Hop'),
(3, 'Jazz'),
(7, 'Klasyczna'),
(2, 'Metal'),
(4, 'Pop'),
(1, 'Rock'),
(8, 'Składanka');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `albumy`
--
ALTER TABLE `albumy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tytuł` (`tytuł`),
  ADD KEY `wykonawca` (`wykonawca`),
  ADD KEY `cena` (`cena`),
  ADD KEY `data_wydania` (`data_wydania`),
  ADD KEY `gatunek_id` (`gatunek_id`);

--
-- Indeksy dla tabeli `gatunki`
--
ALTER TABLE `gatunki`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nazwa` (`nazwa`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albumy`
--
ALTER TABLE `albumy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `gatunki`
--
ALTER TABLE `gatunki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `albumy`
--
ALTER TABLE `albumy`
  ADD CONSTRAINT `albumy_ibfk_1` FOREIGN KEY (`gatunek_id`) REFERENCES `gatunki` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;