-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2019 at 02:20 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myci`
--

-- --------------------------------------------------------

--
-- Table structure for table `sent_push`
--

CREATE TABLE `sent_push` (
  `sql_id` bigint(20) NOT NULL,
  `momt` enum('MO','MT') DEFAULT NULL,
  `sender` varchar(20) DEFAULT NULL,
  `receiver` varchar(20) DEFAULT NULL,
  `udhdata` blob,
  `msgdata` text,
  `time` bigint(20) DEFAULT NULL,
  `smsc_id` varchar(255) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `account` varchar(255) DEFAULT NULL,
  `id` bigint(20) DEFAULT NULL,
  `sms_type` bigint(20) DEFAULT NULL,
  `mclass` bigint(20) DEFAULT NULL,
  `mwi` bigint(20) DEFAULT NULL,
  `coding` bigint(20) DEFAULT NULL,
  `compress` bigint(20) DEFAULT NULL,
  `validity` bigint(20) DEFAULT NULL,
  `deferred` bigint(20) DEFAULT NULL,
  `dlr_mask` bigint(20) DEFAULT NULL,
  `dlr_url` varchar(255) DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `alt_dcs` bigint(20) DEFAULT NULL,
  `rpi` bigint(20) DEFAULT NULL,
  `charset` varchar(255) DEFAULT NULL,
  `boxc_id` varchar(255) DEFAULT NULL,
  `binfo` varchar(255) DEFAULT NULL,
  `meta_data` text,
  `foreign_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sent_push`
--

INSERT INTO `sent_push` (`sql_id`, `momt`, `sender`, `receiver`, `udhdata`, `msgdata`, `time`, `smsc_id`, `service`, `account`, `id`, `sms_type`, `mclass`, `mwi`, `coding`, `compress`, `validity`, `deferred`, `dlr_mask`, `dlr_url`, `pid`, `alt_dcs`, `rpi`, `charset`, `boxc_id`, `binfo`, `meta_data`, `foreign_id`) VALUES
(12094968, '', '33234', '9779869951859', '', 'ACK%2F', 1552465390, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdedabee85.53016857', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', 'B2EEB2D0'),
(12094969, '', '33234', '9779848192375', '', 'ACK%2F', 1552465390, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdedb6ef82.54197270', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '5A232091'),
(12094970, '', '33234', '9779864963816', '', 'ACK%2F', 1552465390, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdedba1e88.95550116', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '2D5369D2'),
(12094971, '', '33234', '9779848704447', '', 'ACK%2F', 1552465390, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee0080d1.66977263', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', 'DED79E23'),
(12094972, '', '33234', '9779842879287', '', 'ACK%2F', 1552465390, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdede0dec3.61324085', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '5A232151'),
(12094973, '', '33234', '9779862140827', '', 'ACK%2F', 1552465390, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee0007f2.72475224', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '2D5369F2'),
(12094993, 'MT', 'PrimeLife', '9779806795837', '', 'Dear+GOVINDA+Ji%2C+we+wishing+you+a+very+Happy+Birthday.+Wish+you+a+blissful+and+prosperous+year+ahead.%0AThank+You.%0A-+PrimeLife', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88bdee317a93.46731389', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474659'),
(12094994, 'MT', 'PrimeLife', '9779806285850', '', 'Dear+SUSMITA+Ji%2C+we+wishing+you+a+very+Happy+Birthday.+Wish+you+a+blissful+and+prosperous+year+ahead.%0AThank+You.%0A-+PrimeLife', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88bdee329698.42258006', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474660'),
(12094995, '', 'PrimeLife', '9779804976562', '', 'ACK%2F', 1552465391, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee080772.73433931', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065018403725'),
(12094996, '', 'PrimeLife', '9779806462412', '', 'ACK%2F', 1552465391, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee0c7680.47564523', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065018406347'),
(12094997, '', 'PrimeLife', '9779817513614', '', 'ACK%2F', 1552465391, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee07dc21.55816735', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065018412380'),
(12094998, '', 'PrimeLife', '9779806795837', '', 'ACK%2F', 1552465391, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee317a93.46731389', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065018415124'),
(12094999, '', 'PrimeLife', '9779815006516', '', 'ACK%2F', 1552465391, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee0e5152.13541995', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065018412940'),
(12095000, '', 'PrimeLife', '9779811888645', '', 'ACK%2F', 1552465391, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee104f83.60353301', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065018413762'),
(12095001, '', 'PrimeLife', '9779816873469', '', 'ACK%2F', 1552465391, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee1051b8.65259123', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065018415063'),
(12095002, '', 'PrimeLife', '9779806285850', '', 'ACK%2F', 1552465391, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee329698.42258006', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065018422327'),
(12095003, '', '33234', '9779849708456', '', 'ACK%2F', 1552465391, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee088855.56037788', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '2D5370E2'),
(12095004, '', '33234', '9779848740244', '', 'ACK%2F', 1552465391, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee101847.11183412', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '5A232B91'),
(12095005, '', '33234', '9779841134830', '', 'ACK%2F', 1552465391, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee07efc7.19351076', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', 'DED7A5F3'),
(12095006, '', '33234', '9779842691921', '', 'ACK%2F', 1552465391, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee038c58.85642565', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', 'B2EEBFD0'),
(12095007, '', '33234', '9779851009175', '', 'ACK%2F', 1552465391, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee106f85.34452558', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '5A232BA1'),
(12095008, '', '33234', '9779848277995', '', 'ACK%2F', 1552465391, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee0dec60.01096497', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '2D5370C2'),
(12095009, '', '33234', '9779843015567', '', 'ACK%2F', 1552465391, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee2c5ae0.92212051', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', 'DED7A5E3'),
(12095010, '', '33234', '9779851060105', '', 'ACK%2F', 1552465391, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88bdee0a1e46.17518265', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '2D5370D2'),
(12095011, 'MT', 'MuktinathBB', '9779809847588', '', '2075%2F11%2F29%0D%0ASaving%0D%0APS%3A21000%2F21000%0D%0A+', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be086b4d27.41874418', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474661'),
(12095012, '', 'MuktinathBB', '9779809847588', '', 'ACK%2F', 1552465417, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be086b4d27.41874418', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065044478724'),
(12095013, 'MT', '33234', '9779841433089', '', 'Premium+due+from+2019-03-13+To+2019-03-13+under+policy+no+110015291+has+been+paid++on++2019-03-13+%2F+14%3A15%3A07++at+++Kalanki+Branch+in++CSH.+LIC+Nepal', NULL, 'NTC3234', 'user3234', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be1bc27a24.19605849', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474662'),
(12095014, '', '33234', '9779841433089', '', 'ACK%2F', 1552465436, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be1bc27a24.19605849', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', 'B2F07940'),
(12095015, 'MT', 'smsSewa', '9779802779034', '', 'Yagya+Kumari+Regmi%0D%0AJugal+Milko+Pachadi+%0D%0A9804034775%0D%0ANo+Signal', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be3f7de4d4.12798103', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474663'),
(12095016, '', 'smsSewa', '9779802779034', '', 'ACK%2F', 1552465472, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be3f7de4d4.12798103', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065099606496'),
(12095017, 'MT', '33234', '9779845482851', '', 'Premium+due+from+2019-03-27+To+2019-03-27+under+policy+no+210009797+has+been+paid++on++2019-03-13+%2F+14%3A15%3A45++at+++Narayangarh+Branch+in++CSH.+LIC+Nepal', NULL, 'NTC3234', 'user3234', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be41c7dcd9.38385680', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474664'),
(12095018, '', '33234', '9779845482851', '', 'ACK%2F', 1552465474, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be41c7dcd9.38385680', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', 'B2F1DF90'),
(12095019, 'MT', '33234', '9779866532245', '', 'Dear+Sirjana%2C+Thanks+for+Renewing+PrimeLife+Policy+(1830004039)+Premium+Rs+7302+Next+Renewal+Payment+Date+is+13-03-20.++-PrimeLife+(01-4441414)', NULL, 'NTC3234', 'user3234', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be48652a31.16117861', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474665'),
(12095020, '', '33234', '9779866532245', '', 'ACK%2F', 1552465481, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be48652a31.16117861', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '5A262931'),
(12095021, 'MT', '33234', '9779855057167', '', 'Your+account+0100002BBS+has+been+debited+by+NPR.+14500+on+13%2F03%2F2019+2%3A10%3A12+PM(Paid+to%3A+Gajendra+Singh+Tomar+(+Shankar+Skey)%0D%0ABCSS', NULL, 'NTC3234', 'user3234', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be4eaa1583.33590656', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474666'),
(12095022, '', '33234', '9779855057167', '', 'ACK%2F', 1552465487, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be4eaa1583.33590656', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', 'DED963F3'),
(12095023, 'MT', '33234', '9779843492297', '', 'Your+Proposal+No+2018058670+is+accepted+with+policy+110063706+with+DOC+2019-03-12%2C+Premium+Rs.+36785+(YLY).+LIC+Nepal', NULL, 'NTC3234', 'user3234', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be502b91e5.91745439', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474667'),
(12095024, '', '33234', '9779843492297', '', 'ACK%2F', 1552465488, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be502b91e5.91745439', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '2D553D82'),
(12095025, 'MT', '33234', '9779841332141', '', 'Dear+Milan%2C+Thanks+for+Renewing+PrimeLife+Policy+(1020003250)+Premium+Rs+33855+Next+Renewal+Payment+Date+is+11-03-20.++-PrimeLife+(01-4441414)', NULL, 'NTC3234', 'user3234', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be58ea4166.06403374', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474668'),
(12095026, '', '33234', '9779841332141', '', 'ACK%2F', 1552465497, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be58ea4166.06403374', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', 'DED994E3'),
(12095027, 'MT', 'MuktinathBB', '9779806564992', '', '2075%2F11%2F29%0D%0ASaving%0D%0AWS%3A5%2F5%0D%0AGS%3A100%2F2867%0D%0AFS%3A200%2F4092%0D%0AMP%3A400%2F10800%0D%0APS%3A300%2F2697%0D%0ATotal%3A1005%0D%0ANext+Meet%3A2075%2F12%2F25%0D%0A+', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be59608a81.06409674', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474669'),
(12095028, 'MT', 'MuktinathBB', '9779825150799', '', '2075%2F11%2F29%0D%0ALoan%0D%0AGE%3A2910%2F25000%0D%0ASaving%0D%0AWS%3A5%2F5%0D%0AGS%3A100%2F3911%0D%0AFS%3A200%2F1981%0D%0AMP%3A400%2F14400%0D%0APS%3A385%2F4257%0D%0ATotal%3A4000%0D%0ANext+Meet%3A2075%2F12%2F25%0D%0A+', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be596fd259.11905539', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474670'),
(12095029, 'MT', 'MuktinathBB', '9779825187523', '', '2075%2F11%2F29%0D%0ALoan%0D%0AGE%3A4353%2F8330%0D%0ASaving%0D%0AWS%3A5%2F5%0D%0AGS%3A100%2F3910%0D%0AFS%3A100%2F3911%0D%0AMP%3A400%2F14400%0D%0APS%3A542%2F9049%0D%0ATotal%3A5500%0D%0ANext+Meet%3A2075%2F12%2F25%0D%0A+', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be597ebb93.20396498', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474671'),
(12095030, 'MT', 'MuktinathBB', '9779815171060', '', '2075%2F11%2F29%0D%0ALoan%0D%0AGE%3A1940%2F16666%0D%0ASaving%0D%0AWS%3A5%2F5%0D%0AGS%3A100%2F3911%0D%0AFS%3A100%2F3910%0D%0AMP%3A400%2F14400%0D%0APS%3A465%2F3586%0D%0ATotal%3A3010%0D%0ANext+Meet%3A2075%2F12%2F25%0D%0A+', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be598ddeb4.73898622', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474672'),
(12095031, 'MT', 'MuktinathBB', '9779806715808', '', '2075%2F11%2F29%0D%0ALoan%0D%0AGE%3A14551%2F125000%0D%0ASaving%0D%0AWS%3A5%2F5%0D%0AGS%3A100%2F3910%0D%0AFS%3A100%2F3911%0D%0AMP%3A400%2F14400%0D%0APS%3A844%2F21920%0D%0ATotal%3A16000%0D%0ANext+Meet%3A2075%2F12%2F25%0D%0A+', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be599a01f3.43527795', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474673'),
(12095032, 'MT', 'MuktinathBB', '9779827102083', '', '2075%2F11%2F29%0D%0ALoan%0D%0AGE%3A4478%2F16664%0D%0ASaving%0D%0AWS%3A5%2F5%0D%0AGS%3A100%2F3559%0D%0AFS%3A100%2F3557%0D%0AMP%3A400%2F13200%0D%0APS%3A917%2F7656%0D%0ATotal%3A6000%0D%0ANext+Meet%3A2075%2F12%2F25%0D%0A+', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be59a44962.93258828', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474674'),
(12095033, 'MT', 'MuktinathBB', '9779826134372', '', '2075%2F11%2F29%0D%0ALoan%0D%0AGE%3A9950%2F99992%0D%0ASaving%0D%0AWS%3A5%2F5%0D%0AGS%3A100%2F6393%0D%0AFS%3A100%2F909%0D%0AMP%3A200%2F11200%0D%0APS%3A645%2F22338%0D%0ATotal%3A11000%0D%0ASR%3AP%0D%0ANext+Meet%3A2075%2F12%2F25%0D%0A+', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be59ae83b9.15796052', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474675'),
(12095034, '', 'MuktinathBB', '9779806564992', '', 'ACK%2F', 1552465498, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be59608a81.06409674', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065125688975'),
(12095035, '', 'MuktinathBB', '9779825150799', '', 'ACK%2F', 1552465498, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be596fd259.11905539', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065125691048'),
(12095036, '', 'MuktinathBB', '9779806715808', '', 'ACK%2F', 1552465498, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be599a01f3.43527795', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065125702508'),
(12095037, '', 'MuktinathBB', '9779815171060', '', 'ACK%2F', 1552465498, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be598ddeb4.73898622', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065125708926'),
(12095038, '', 'MuktinathBB', '9779825187523', '', 'ACK%2F', 1552465498, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be597ebb93.20396498', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065125708891'),
(12095039, '', 'MuktinathBB', '9779827102083', '', 'ACK%2F', 1552465498, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be59a44962.93258828', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065125710820'),
(12095040, '', 'MuktinathBB', '9779826134372', '', 'ACK%2F', 1552465498, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be59ae83b9.15796052', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065125712177'),
(12095041, 'MT', 'LICNepal', '9779811544917', '', 'Premium+due+from+2018-03-28+To+2018-09-28+under+policy+no+150048467+has+been+paid++on++2019-03-13+%2F+14%3A16%3A10++at+++Butwal+Branch+in++CSH.+LIC+Nepal', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88be5b564899.88772050', NULL, NULL, NULL, '', 'sqlbox', '', '', '8474676'),
(12095042, '', 'LICNepal', '9779811544917', '', 'ACK%2F', 1552465500, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88be5b564899.88772050', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056065127701654'),
(12096933, 'MT', 'smsSewa', '9779822402350', '', '%09.%09%3F%09%24%09%40%00+%09h%09f%09m%09k%00%2F%09g%09g%00%2F%09h%09o%00+%09.%09%3E%00+%09%24%09*%09%3E%09%07%09%15%09K%00+%09%2C%09%1A%09%24%09.%09%3E%00+%090%09A%00+%09g%09m%09k%09g%09f%00+%09%1C%09.%09M%09.%09%3E%00+%09%17%090%09%3F%09%0F%09%15%09K%00+%09%1B%09d%00%0A%09%1B%09%3F%09.%09G%09%15%00+', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, 31, '5c88c884318079.64930755', NULL, NULL, NULL, 'UTF-16BE', 'sqlbox', '', '', '8475622'),
(12096934, '', 'smsSewa', '9779822402350', '', 'ACK%2F', 1552468101, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88c884318079.64930755', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056067728424374'),
(12096935, 'MT', 'LICNepal', '9779803937975', '', 'Loan+Amount+Rs+69000+sanctioned+on+2019-03-13+towards+LOAN+on+your+Policy+250801629.+LIC+Nepal', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88c885ec6502.26150210', NULL, NULL, NULL, '', 'sqlbox', '', '', '8475623'),
(12096936, '', 'LICNepal', '9779803937975', '', 'ACK%2F', 1552468102, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88c885ec6502.26150210', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056067729429701'),
(12096937, 'MT', '33234', '9779844014814', '', 'Dear+Shyam%2C+Namaste!+Your+Renewal+premium+of+Rs.36830.00+was+received+for+Policy+7110000040.Next+due+date+is+14+Mar+2020.ThankYou%2C+JyotiLife+01-4445941', NULL, 'NTC3234', 'user3234', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88c88b215206.20485582', NULL, NULL, NULL, '', 'sqlbox', '', '', '8475624'),
(12096938, '', '33234', '9779844014814', '', 'ACK%2F', 1552468108, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88c88b215206.20485582', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', 'DF0693D3'),
(12096939, 'MT', '33234', '9779845087600', '', 'Your+account+0100542BSS+has+been+debited+by+NPR.+12410+on+13%2F03%2F2019+2%3A53%3A56+PM(Paid+to%3A+Bharat%0D%0ABCSS', NULL, 'NTC3234', 'user3234', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88c88d0a3448.41374870', NULL, NULL, NULL, '', 'sqlbox', '', '', '8475625'),
(12096940, '', '33234', '9779845087600', '', 'ACK%2F', 1552468109, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88c88d0a3448.41374870', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', 'B3524A30'),
(12096941, 'MT', '33234', '9779865864869', '', 'Dear+Sneha%2C+Thanks+for+Renewing+PrimeLife+Policy+(1470000745)+Premium+Rs+8989+Next+Renewal+Payment+Date+is+16-02-20.++-PrimeLife+(01-4441414)', NULL, 'NTC3234', 'user3234', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88c88ed75440.46592959', NULL, NULL, NULL, '', 'sqlbox', '', '', '8475626'),
(12096942, '', '33234', '9779865864869', '', 'ACK%2F', 1552468111, 'NTC3234', 'user3234', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88c88ed75440.46592959', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '5A792151'),
(12096943, 'MT', 'LICNepal', '9779816039162', '', 'Your+Proposal+No+2018058616+is+accepted+with+policy+252210341+with+DOC+2019-03-12%2C+Premium+Rs.+2426+(HLY).+LIC+Nepal', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88c8910062f0.97618974', NULL, NULL, NULL, '', 'sqlbox', '', '', '8475627'),
(12096944, '', 'LICNepal', '9779816039162', '', 'ACK%2F', 1552468113, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88c8910062f0.97618974', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056067740470081'),
(12096945, 'MT', 'LICNepal', '9779806593991', '', 'Premium+due+from+2019-03-16+To+2019-03-16+under+policy+no+130059112+has+been+paid++on++2019-03-13+%2F+14%3A59%3A49++at+++Pokhara+Branch+in++CSH.+LIC+Nepal', NULL, 'NCELL', 'bulkuser', '', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 31, '5c88c895b5e013.89974986', NULL, NULL, NULL, '', 'sqlbox', '', '', '8475628'),
(12096946, '', 'LICNepal', '9779806593991', '', 'ACK%2F', 1552468118, 'NCELL', 'bulkuser', '', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 8, '5c88c895b5e013.89974986', NULL, NULL, NULL, '', 'sqlbox', '', '?smpp_resp??orig_msg?dlr_mask=31&', '6056067745487880'),
(12096947, NULL, 'easy', '9861585016', NULL, 'hello this is sandip', NULL, 'NCELL', 'Bulk User', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sent_push`
--
ALTER TABLE `sent_push`
  ADD PRIMARY KEY (`sql_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sent_push`
--
ALTER TABLE `sent_push`
  MODIFY `sql_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12096948;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
