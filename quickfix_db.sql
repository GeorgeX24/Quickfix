-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Ned 27. zář 2026, 23:08
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `quickfix_db`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `nazev` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `kategorie`
--

INSERT INTO `kategorie` (`id`, `nazev`) VALUES
(1, 'Elektro'),
(2, 'Instalatérské'),
(3, 'Vybavení'),
(4, 'Jiné');

-- --------------------------------------------------------

--
-- Struktura tabulky `uzivatele`
--

CREATE TABLE `uzivatele` (
  `id` int(11) NOT NULL,
  `jmeno` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `heslo` varchar(255) NOT NULL,
  `role` enum('registrovaný','admin') DEFAULT 'registrovaný',
  `vytvoreno` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `uzivatele`
--

INSERT INTO `uzivatele` (`id`, `jmeno`, `email`, `heslo`, `role`, `vytvoreno`) VALUES
(1, 'Admin', 'admin@quickfix.cz', '$2y$10$o4RHlJTeg517FFWrhqcBReoB8T28py6mpwmAs7/9GZ8GLYZlzAEaG', 'admin', '2026-09-19 18:03:21'),
(2, 'Jan Novák', 'jan.novak@example.com', '$2y$10$GtM4iNoCyrRIglcai63hFeIoQPgwE27xBH140W3ErsDdJPu1IXA2G', 'registrovaný', '2026-09-19 18:05:30');

-- --------------------------------------------------------

--
-- Struktura tabulky `zavady`
--

CREATE TABLE `zavady` (
  `id` int(11) NOT NULL,
  `uzivatel_id` int(11) NOT NULL,
  `kategorie_id` int(11) NOT NULL,
  `popis` text NOT NULL,
  `misto` varchar(255) NOT NULL,
  `priorita` enum('Nízká','Střední','Vysoká') DEFAULT 'Střední',
  `stav` enum('Nahlášeno','V řešení','Opraveno') DEFAULT 'Nahlášeno',
  `fotografie` varchar(255) DEFAULT NULL,
  `poznamka_technika` text DEFAULT NULL,
  `vytvoreno` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `zavady`
--

INSERT INTO `zavady` (`id`, `uzivatel_id`, `kategorie_id`, `popis`, `misto`, `priorita`, `stav`, `fotografie`, `poznamka_technika`, `vytvoreno`) VALUES
(1, 1, 2, 'Teče voda z topení', 'Budova dva, před uč. č. 34', 'Vysoká', 'Opraveno', NULL, 'Vyměněné těsnění u ventilu, topení je opraveno a plně funkční.', '2026-09-20 20:19:54'),
(2, 2, 4, 'Spadla omitka ze stropu', 'Budova jedna, uč. č. 6', 'Nízká', 'V řešení', 'zavada_6ab96ecfec530.jpg', 'Suť uklizena, zedník přijde v úterý ráno', '2026-09-20 20:25:23'),
(3, 1, 1, 'Nefunguje Počítač číslo 11, nechce se zapnout', 'Budova jedna, uč. č. 15', 'Střední', 'Nahlášeno', NULL, '', '2026-09-20 20:27:52');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexy pro tabulku `zavady`
--
ALTER TABLE `zavady`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uzivatel_id` (`uzivatel_id`),
  ADD KEY `kategorie_id` (`kategorie_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pro tabulku `zavady`
--
ALTER TABLE `zavady`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `zavady`
--
ALTER TABLE `zavady`
  ADD CONSTRAINT `zavady_ibfk_1` FOREIGN KEY (`uzivatel_id`) REFERENCES `uzivatele` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `zavady_ibfk_2` FOREIGN KEY (`kategorie_id`) REFERENCES `kategorie` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
