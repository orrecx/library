-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 08. Dez 2017 um 23:30
-- Server-Version: 10.1.28-MariaDB
-- PHP-Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `library`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tb_books`
--

CREATE TABLE `tb_books` (
  `book_id` int(10) UNSIGNED NOT NULL,
  `book_title` varchar(128) NOT NULL,
  `book_author` varchar(128) NOT NULL,
  `book_onloan` tinyint(1) NOT NULL,
  `book_duedate` date NOT NULL,
  `borrower_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tb_borrowers`
--

CREATE TABLE `tb_borrowers` (
  `borrower_id` int(10) UNSIGNED NOT NULL,
  `borrower_name` varchar(128) NOT NULL,
  `borrower_adresse` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tb_borrowers`
--

INSERT INTO `tb_borrowers` (`borrower_id`, `borrower_name`, `borrower_adresse`) VALUES
(1, 'Atangana', 'biyemassi, rond point express'),
(2, 'ngo nyemb', 'edea, bas sanaga'),
(3, 'simo', 'bafoussam, derriere le marche'),
(4, 'Saggat', 'vog atangana mballa');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tb_books`
--
ALTER TABLE `tb_books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `borrower_id` (`borrower_id`);

--
-- Indizes für die Tabelle `tb_borrowers`
--
ALTER TABLE `tb_borrowers`
  ADD PRIMARY KEY (`borrower_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tb_books`
--
ALTER TABLE `tb_books`
  MODIFY `book_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tb_borrowers`
--
ALTER TABLE `tb_borrowers`
  MODIFY `borrower_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `tb_books`
--
ALTER TABLE `tb_books`
  ADD CONSTRAINT `tb_books_ibfk_1` FOREIGN KEY (`borrower_id`) REFERENCES `tb_borrowers` (`borrower_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
