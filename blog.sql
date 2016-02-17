-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Hoszt: 127.0.0.1
-- Létrehozás ideje: 2016. Feb 17. 16:56
-- Szerver verzió: 5.6.17
-- PHP verzió: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `blog`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post` text COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `post` (`post`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=77 ;

--
-- A tábla adatainak kiíratása `post`
--

INSERT INTO `post` (`id`, `post`) VALUES
(70, 'Post text 6'),
(71, 'Post text 7'),
(72, 'Post text 8');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `posttotag`
--

CREATE TABLE IF NOT EXISTS `posttotag` (
  `tag_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=100 ;

--
-- A tábla adatainak kiíratása `posttotag`
--

INSERT INTO `posttotag` (`tag_id`, `post_id`, `id`) VALUES
(0, 0, 96),
(11434, 0, 97),
(114, 0, 98),
(115, 0, 99);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `tag` tinytext COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=117 ;

--
-- A tábla adatainak kiíratása `tag`
--

INSERT INTO `tag` (`ID`, `tag`) VALUES
(26, 'tag6'),
(27, 't6'),
(28, 'hat'),
(29, 'tag7'),
(30, 't7'),
(31, 'hetes'),
(32, 'tag8'),
(33, 't8'),
(34, 'nyolc'),
(114, 't21'),
(115, 'tag21'),
(116, 'tt21');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
