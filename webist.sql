-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 30 déc. 2025 à 17:30
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `webist`
--

-- --------------------------------------------------------

--
-- Structure de la table `arrets`
--

CREATE TABLE `arrets` (
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `sanction_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `expulsions`
--

CREATE TABLE `expulsions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule` bigint(20) UNSIGNED NOT NULL,
  `motif_expulsion` varchar(255) NOT NULL,
  `date_expulsion` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `list_lock`
--

CREATE TABLE `list_lock` (
  `id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `list_lock`
--

INSERT INTO `list_lock` (`id`, `status`) VALUES
(1, 0),
(2, 0),
(3, 0);

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2022_06_10_004514_create_roles_table', 1),
(2, '2023_01_02_000000_create_users_table', 1),
(3, '2023_01_03_000000_create_sections_table', 1),
(4, '2023_01_03_000000_create_students_table', 1),
(5, '2023_01_05_000000_create_sanctions_table', 1),
(6, '2023_01_06_000000_create_reports_table', 1),
(7, '2023_01_10_000000_create_patients_table', 1),
(8, '2023_01_11_000000_create_sorties_table', 1),
(9, '2023_01_13_000000_create_jobs_table', 1),
(10, '2023_01_14_000000_create_cache_table', 1),
(11, '2023_01_15_000000_create_sessions_table', 1),
(12, '2025_05_27_182910_create_expulsions_table', 1),
(13, '2025_06_01_093822_update_reports_table_fix_status_and_columns', 1),
(14, '2025_06_10_000000_create_list_lock_table', 1),
(15, '2025_06_10_124350_create_notifications_table', 1),
(16, '2025_12_28_000001_create_arrets_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0cb64501-804b-47ae-9c04-37af9c37de45', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 4, '{\"report_id\":29,\"title\":\"hiii3\",\"message\":\"New report: hiii3\",\"url\":\"http:\\/\\/localhost:8000\\/1\\/show\\/29\"}', NULL, '2025-12-30 01:41:03', '2025-12-30 01:41:03'),
('22e8bed2-5cc8-4062-9747-1a3780e71773', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":29,\"title\":\"hiii3\",\"message\":\"New report: hiii3\",\"url\":\"http:\\/\\/localhost:8000\\/1\\/show\\/29\"}', NULL, '2025-12-30 01:40:02', '2025-12-30 01:40:02'),
('5fa64a2c-2afb-496e-be15-bd7c355a27b9', 'App\\Notifications\\DesitionMade', 'App\\Models\\Officer', 9, '{\"report_id\":28,\"title\":\"SFK\\/JNER\",\"message\":\"New report: SFK\\/JNER\",\"url\":\"http:\\/\\/localhost:8000\\/9\\/show\\/28\"}', NULL, '2025-12-30 01:28:03', '2025-12-30 01:28:03'),
('66cbecdd-fbc6-49c8-8d24-db431c5213ae', 'App\\Notifications\\DesitionMade', 'App\\Models\\Officer', 9, '{\"report_id\":28,\"title\":\"SFK\\/JNER\",\"message\":\"New report: SFK\\/JNER\",\"url\":\"http:\\/\\/localhost:8000\\/9\\/show\\/28\"}', NULL, '2025-12-29 12:49:44', '2025-12-29 12:49:44'),
('a5a78306-0e99-44be-addc-e53fc9c73747', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 9, '{\"report_id\":29,\"title\":\"hiii3\",\"message\":\"New report: hiii3\",\"url\":\"http:\\/\\/localhost:8000\\/1\\/show\\/29\"}', NULL, '2025-12-30 01:42:34', '2025-12-30 01:42:34'),
('a6ce7dcd-f507-4744-926b-5037027339d2', 'App\\Notifications\\DesitionMade', 'App\\Models\\Officer', 1, '{\"report_id\":29,\"title\":\"hiii3\",\"message\":\"New report: hiii3\",\"url\":\"http:\\/\\/localhost:8000\\/1\\/show\\/29\"}', NULL, '2025-12-30 01:43:51', '2025-12-30 01:43:51'),
('d091571e-6fc4-4b24-b163-cc8ad7720653', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":29,\"title\":\"hiii3\",\"message\":\"New report: hiii3\",\"url\":\"http:\\/\\/localhost:8000\\/1\\/show\\/29\"}', NULL, '2025-12-30 01:39:42', '2025-12-30 01:39:42'),
('f186b0e8-581d-47ae-a8a6-b491337811a1', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":27,\"title\":\"DJDJD\",\"message\":\"New report: DJDJD\",\"url\":\"http:\\/\\/localhost:8000\\/1\\/show\\/27\"}', NULL, '2025-12-29 12:46:47', '2025-12-29 12:46:47'),
('fd8d652e-9ba5-4b01-a7a8-422a8fb41e49', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 6, '{\"report_id\":29,\"title\":\"hiii3\",\"message\":\"New report: hiii3\",\"url\":\"http:\\/\\/localhost:8000\\/1\\/show\\/29\"}', NULL, '2025-12-30 01:41:48', '2025-12-30 01:41:48');

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule` bigint(20) UNSIGNED NOT NULL,
  `valider` tinyint(4) NOT NULL DEFAULT 0,
  `validated_at` timestamp NULL DEFAULT NULL,
  `motif_suppression` varchar(255) DEFAULT NULL,
  `type_medecin` varchar(255) DEFAULT NULL,
  `avis_medecin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `corps` varchar(255) NOT NULL,
  `is_medical` tinyint(1) NOT NULL DEFAULT 0,
  `destination` bigint(20) UNSIGNED DEFAULT NULL,
  `AvisChef_de_compagnie` text DEFAULT NULL,
  `AvisChef_de_batallaint` text DEFAULT NULL,
  `AvisChef_de_brigade` text DEFAULT NULL,
  `AvisDirecteur_général` text DEFAULT NULL,
  `AvisChef_division` text DEFAULT NULL,
  `motif` text DEFAULT NULL,
  `refused` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `arret` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reports`
--

INSERT INTO `reports` (`id`, `student_id`, `officer_id`, `status`, `title`, `corps`, `is_medical`, `destination`, `AvisChef_de_compagnie`, `AvisChef_de_batallaint`, `AvisChef_de_brigade`, `AvisDirecteur_général`, `AvisChef_division`, `motif`, `refused`, `created_at`, `updated_at`, `deleted_at`, `arret`) VALUES
(20, 2022003, 1, 'Chef de batallaint', 'dcsdc', 'dcoisjd', 0, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-30 08:46:37', '2025-05-30 08:46:39', NULL, 0),
(21, 2022004, 1, 'Chef de batallaint', 'jhbkjh', 'jklbjhklkkhgvj', 0, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-30 11:54:55', '2025-05-30 11:54:56', NULL, 0),
(22, 2022004, 1, 'DONE', 'miohbj,', 'jhkvbhbgh ', 0, 1, 'bbbbbbb', 'zelfhsdcfvze', 'dkjcnslqdc sd', 'ui<sjklcn<sdklc<s', 'KKKKKKKKKKKKKK', NULL, 0, '2025-05-30 11:55:12', '2025-05-30 12:58:11', NULL, 0),
(23, 2022002, 1, 'Chef de batallaint', 'ksdlcn', 'ZeofdjZEMOFNEQR', 0, 3, 'LFNVDMFVNQVFS', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-17 19:56:09', '2025-07-17 19:56:31', NULL, 0),
(27, 2022002, 1, 'Chef de batallaint', 'DJDJD', 'SDFIOQEJHFILQER', 0, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-12-29 12:46:47', '2025-12-29 12:46:47', NULL, 0),
(28, 2022003, 9, 'DONE', 'SFK/JNER', 'SDFVJKLQSDHFLKAERF', 0, 9, NULL, NULL, NULL, 'arret de2026-01-10 au 2026-01-11', NULL, NULL, 0, '2025-12-29 12:49:44', '2025-12-30 01:28:00', NULL, 1),
(29, 2022005, 1, 'DONE', 'hiii3', 'Directeur généralDirecteur généralDirecteur généralDirecteur généralDirecteur généralDirecteur', 0, 1, 'ihjihihojhkh', 'azertyuiop', '123456789', 'arret de2025-12-31 au 2026-01-11', '231234567890', NULL, 0, '2025-12-30 01:39:42', '2025-12-30 01:43:51', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Chef de compagnie', NULL, '2025-03-19 11:24:41', '2025-03-19 11:24:41'),
(2, 'Chef de batallaint', NULL, '2025-03-19 11:24:41', '2025-03-19 11:24:41'),
(3, 'Chef de brigade', NULL, '2025-03-19 11:24:41', '2025-03-19 11:24:41'),
(4, 'Chef division', NULL, '2025-03-19 11:24:41', '2025-03-19 11:24:41'),
(5, 'Medecin', NULL, '2025-03-19 11:24:41', '2025-03-19 11:24:41'),
(6, 'Directeur général', NULL, '2025-03-19 11:24:41', '2025-03-19 11:24:41');

-- --------------------------------------------------------

--
-- Structure de la table `sanctions`
--

CREATE TABLE `sanctions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `motif` text NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `report_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sanctions`
--

INSERT INTO `sanctions` (`id`, `matricule`, `type`, `motif`, `date_debut`, `date_fin`, `created_at`, `updated_at`, `report_id`) VALUES
(1, 2022003, 'arret', 'sdlcjskldv', '2025-12-04', '2026-01-04', '2025-12-29 16:19:55', '2025-12-29 16:19:55', 28),
(2, 2022003, 'arret', 'hizedilqzehrfioqehrfoiqhzm', '2026-01-10', '2026-01-11', '2025-12-30 01:28:04', '2025-12-30 01:28:04', 28),
(3, 2022005, 'arret', '1234567890', '2025-12-31', '2026-01-11', '2025-12-30 01:43:51', '2025-12-30 01:43:51', 29);

-- --------------------------------------------------------

--
-- Structure de la table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bat` int(11) NOT NULL,
  `companie` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `officer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sections`
--

INSERT INTO `sections` (`id`, `bat`, `companie`, `num`, `officer_id`, `created_at`, `updated_at`) VALUES
(321, 3, 2, 1, 1, '2025-05-22 18:18:07', '2025-05-22 18:18:07'),
(341, 3, 4, 1, 8, '2025-03-20 01:50:30', '2025-03-20 01:50:48'),
(342, 3, 4, 2, 8, '2025-03-20 01:50:30', '2025-03-20 01:50:48'),
(343, 3, 4, 3, 8, '2025-03-20 01:50:30', '2025-03-20 01:50:48'),
(351, 3, 5, 1, 1, '2025-03-20 01:50:30', '2025-03-20 01:50:48'),
(352, 3, 5, 2, 1, '2025-03-20 01:50:30', '2025-03-20 01:50:48'),
(353, 3, 5, 3, 1, '2025-03-20 01:50:30', '2025-03-20 01:50:48'),
(361, 3, 6, 1, 1, '2025-03-20 01:50:30', '2025-03-20 01:50:48'),
(362, 3, 6, 2, 1, '2025-03-20 01:50:30', '2025-03-20 01:50:48');

--
-- Déclencheurs `sections`
--
DELIMITER $$
CREATE TRIGGER `before_insert_section` BEFORE INSERT ON `sections` FOR EACH ROW BEGIN
                SET NEW.id = NEW.bat * 100 + NEW.companie * 10 + NEW.num;
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_section` BEFORE UPDATE ON `sections` FOR EACH ROW BEGIN
                SET NEW.id = NEW.bat * 100 + NEW.companie * 10 + NEW.num;
            END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sorties`
--

CREATE TABLE `sorties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule` bigint(20) UNSIGNED NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `choix` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

CREATE TABLE `students` (
  `matricule` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `grade` enum('1','2','3') NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `consigned` tinyint(1) NOT NULL DEFAULT 0,
  `choix` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `students`
--

INSERT INTO `students` (`matricule`, `nom`, `prenom`, `grade`, `section_id`, `consigned`, `choix`, `created_at`, `updated_at`) VALUES
(2022002, 'Faris Ibrahim', '', '3', 361, 0, NULL, '2025-02-26 01:52:01', '2025-04-21 21:38:31'),
(2022003, 'Youssef Darwish', '', '3', 361, 0, 'sam', '2025-02-26 01:52:01', '2025-07-17 19:54:19'),
(2022004, 'Walid Yazbek', '', '3', 361, 0, 'ven', '2025-02-26 01:52:01', '2025-05-30 14:58:40'),
(2022005, 'Youssef Qasem', '', '3', 361, 0, NULL, '2025-02-26 01:52:01', '2025-04-21 01:54:36'),
(2022006, 'Ali Darwish', '', '3', 361, 0, NULL, '2025-02-26 01:52:01', '2025-04-21 01:54:50'),
(2022007, 'Youssef Ibrahim', '', '3', 361, 0, NULL, '2025-02-26 01:52:01', '2025-04-19 14:46:11'),
(2022008, 'Youssef Zahran', '', '3', 361, 0, NULL, '2025-02-26 01:52:01', '2025-04-19 14:46:11'),
(2022009, 'Faris Darwish', '', '3', 361, 0, NULL, '2025-02-26 01:52:01', '2025-04-19 14:46:11'),
(2022010, 'Omar Nasser', '', '3', 361, 0, NULL, '2025-02-26 01:52:01', '2025-04-19 14:46:11'),
(2022011, 'Ahmed Al-Masri', '', '3', 351, 1, 'ven', '2025-02-26 01:52:01', '2025-05-22 23:23:41'),
(2022012, 'Ziad Nasser', '', '3', 351, 0, 'sam', '2025-02-26 01:52:01', '2025-05-30 14:09:01'),
(2022013, 'Ahmed Ibrahim', '', '3', 351, 0, 'sam', '2025-02-26 01:52:01', '2025-05-30 14:09:00'),
(2022014, 'Hassan Bakr', '', '3', 351, 0, 'sam', '2025-02-26 01:52:01', '2025-05-30 14:08:59'),
(2022015, 'Khaled Yazbek', '', '3', 351, 0, NULL, '2025-02-26 01:52:01', '2025-04-19 14:46:11'),
(2022016, 'Amr Yazbek', '', '3', 351, 0, NULL, '2025-02-26 01:52:01', '2025-04-19 14:46:11'),
(2022017, 'Amr Bakr', '', '3', 351, 0, 'ven', '2025-02-26 01:52:01', '2025-05-30 14:58:28'),
(2022018, 'Faris Ibrahim', '', '3', 351, 0, NULL, '2025-02-26 01:52:01', '2025-04-19 14:46:11'),
(2022019, 'Amr Ibrahim', '', '3', 351, 0, NULL, '2025-02-26 01:52:01', '2025-04-19 14:46:11'),
(2022020, 'Walid Ibrahim', '', '3', 351, 0, NULL, '2025-02-26 01:52:01', '2025-04-19 14:46:11'),
(2022041, 'Ali Al-Masri', '', '3', 361, 1, NULL, '2025-03-19 17:25:07', '2025-04-19 14:46:11'),
(2022054, 'Walid Ibrahim', '', '3', 361, 1, NULL, '2025-03-19 17:25:06', '2025-04-19 14:46:11'),
(2022055, 'Walid Zahran', '', '1', 342, 0, '48h', '2025-03-19 17:24:21', '2025-04-19 14:46:11'),
(2022056, 'Hassan Haddad', '', '1', 351, 1, '48h', '2025-03-19 17:24:57', '2025-04-19 14:46:11'),
(2022064, 'Youssef Qasem', '', '1', 352, 1, 'sam', '2025-03-19 17:24:48', '2025-04-19 14:46:11'),
(2022077, 'Amr Yazbek', '', '1', 351, 1, '48h', '2025-03-19 17:24:59', '2025-04-19 14:46:11'),
(2022078, 'Omar Qasem', '', '3', 362, 1, NULL, '2025-03-19 17:24:54', '2025-04-19 14:46:11'),
(2022096, 'Ziad Nasser', '', '2', 362, 1, 'ven', '2025-03-19 17:24:51', '2025-04-19 14:46:11'),
(2022099, 'Ziad Darwish', '', '3', 362, 1, NULL, '2025-03-19 17:24:53', '2025-04-19 14:46:11'),
(2022104, 'Youssef Yazbek', '', '1', 342, 1, 'sam', '2025-03-19 17:24:21', '2025-04-19 14:46:11'),
(2022120, 'Ziad Darwish', '', '2', 352, 1, 'sam', '2025-03-19 17:24:44', '2025-04-19 14:46:11'),
(2022131, 'Faris Qasem', '', '2', 352, 0, 'sam', '2025-03-19 17:24:46', '2025-04-19 14:46:11'),
(2022168, 'Amr Al-Farouq', '', '1', 342, 0, 'ven', '2025-03-19 17:24:21', '2025-04-19 14:46:11'),
(2022169, 'Khaled Zahran', '', '2', 341, 1, 'ven', '2025-03-19 17:24:39', '2025-04-19 14:46:11'),
(2022185, 'Ahmed Bakr', '', '1', 353, 0, 'sam', '2025-03-19 17:24:27', '2025-04-19 14:46:11'),
(2022194, 'Ziad Al-Farouq', '', '1', 342, 0, 'sam', '2025-03-19 17:24:20', '2025-04-19 14:46:11'),
(2022220, 'Omar Al-Farouq', '', '2', 351, 1, '48h', '2025-03-19 17:24:58', '2025-04-19 14:46:11'),
(2022259, 'Ziad Bakr', '', '3', 352, 1, NULL, '2025-03-19 17:24:45', '2025-04-19 14:46:11'),
(2022276, 'Youssef Bakr', '', '3', 361, 0, NULL, '2025-03-19 17:25:03', '2025-04-19 14:46:11'),
(2022285, 'Amr Al-Masri', '', '2', 343, 1, 'sam', '2025-03-19 17:24:32', '2025-04-19 14:46:11'),
(2022321, 'Hassan Al-Masri', '', '2', 342, 0, 'sam', '2025-03-19 17:24:24', '2025-04-19 14:46:11'),
(2022322, 'Ali Al-Masri', '', '3', 343, 1, NULL, '2025-03-19 17:24:34', '2025-04-19 14:46:11'),
(2022332, 'Omar Ibrahim', '', '3', 353, 0, NULL, '2025-03-19 17:24:29', '2025-04-19 14:46:11'),
(2022343, 'Walid Haddad', '', '1', 353, 0, '48h', '2025-03-19 17:24:26', '2025-04-19 14:46:11'),
(2022358, 'Ali Haddad', '', '2', 341, 1, 'ven', '2025-03-19 17:24:41', '2025-04-19 14:46:11'),
(2022359, 'Walid Bakr', '', '1', 362, 0, 'ven', '2025-03-19 17:24:55', '2025-04-19 14:46:12'),
(2022360, 'Ziad Darwish', '', '2', 352, 0, 'sam', '2025-03-19 17:24:48', '2025-04-19 14:46:12'),
(2022364, 'Ali Haddad', '', '2', 352, 1, 'ven', '2025-03-19 17:24:47', '2025-04-19 14:46:12'),
(2022375, 'Omar Yazbek', '', '1', 341, 1, '48h', '2025-03-19 17:24:38', '2025-04-19 14:46:12'),
(2022387, 'Amr Bakr', '', '2', 353, 1, 'sam', '2025-03-19 17:24:29', '2025-04-19 14:46:12'),
(2022397, 'Ziad Qasem', '', '2', 351, 1, 'sam', '2025-03-19 17:25:02', '2025-04-19 14:46:12'),
(2022414, 'Faris Zahran', '', '1', 353, 1, 'ven', '2025-03-19 17:24:30', '2025-04-19 14:46:12'),
(2022424, 'Amr Yazbek', '', '2', 342, 1, 'ven', '2025-03-19 17:24:23', '2025-04-19 14:46:12'),
(2022427, 'Walid Nasser', '', '2', 361, 1, 'ven', '2025-03-19 17:25:08', '2025-04-19 14:46:12'),
(2022440, 'Ziad Ibrahim', '', '2', 343, 0, 'sam', '2025-03-19 17:24:31', '2025-04-19 14:46:12'),
(2022456, 'Ziad Yazbek', '', '2', 353, 1, 'ven', '2025-03-19 17:24:26', '2025-04-19 14:46:12'),
(2022460, 'Faris Yazbek', '', '1', 352, 1, '48h', '2025-03-19 17:24:46', '2025-04-19 14:46:12'),
(2022468, 'Amr Darwish', '', '3', 341, 0, 'sam', '2025-03-19 17:24:42', '2025-05-30 12:59:14'),
(2022471, 'Youssef Bakr', '', '3', 343, 1, NULL, '2025-03-19 17:24:37', '2025-04-19 14:46:12'),
(2022478, 'Ziad Al-Masri', '', '2', 351, 1, 'sam', '2025-03-19 17:25:00', '2025-04-19 14:46:12'),
(2022479, 'Ahmed Darwish', '', '2', 362, 1, 'ven', '2025-03-19 17:24:55', '2025-04-19 14:46:12'),
(2022497, 'Omar Al-Farouq', '', '1', 362, 0, 'ven', '2025-03-19 17:24:50', '2025-04-19 14:46:12'),
(2022513, 'Ahmed Al-Farouq', '', '3', 343, 0, NULL, '2025-03-19 17:24:36', '2025-04-21 22:47:08'),
(2022519, 'Khaled Qasem', '', '3', 351, 1, NULL, '2025-03-19 17:25:01', '2025-04-19 14:46:12'),
(2022525, 'Amr Haddad', '', '2', 351, 0, 'ven', '2025-03-19 17:24:58', '2025-05-30 14:58:35'),
(2022527, 'Ziad Al-Masri', '', '3', 361, 0, 'ven', '2025-03-19 17:25:05', '2025-07-17 19:54:52'),
(2022545, 'Ahmed Qasem', '', '3', 341, 1, NULL, '2025-03-19 17:24:43', '2025-04-19 14:46:12'),
(2022549, 'Khaled Nasser', '', '2', 341, 0, 'sam', '2025-03-19 17:24:41', '2025-04-19 14:46:12'),
(2022554, 'Ahmed Yazbek', '', '1', 352, 1, '48h', '2025-03-19 17:24:45', '2025-04-19 14:46:12'),
(2022567, 'Ali Nasser', '', '3', 342, 0, NULL, '2025-03-19 17:24:20', '2025-04-19 14:46:12'),
(2022568, 'Faris Haddad', '', '1', 361, 1, 'ven', '2025-03-19 17:25:04', '2025-04-19 14:46:12'),
(2022580, 'Walid Al-Farouq', '', '3', 341, 1, NULL, '2025-03-19 17:24:43', '2025-04-19 14:46:12'),
(2022598, 'Walid Darwish', '', '1', 362, 0, '48h', '2025-03-19 17:24:53', '2025-04-19 14:46:12'),
(2022604, 'Amr Al-Masri', '', '3', 343, 1, NULL, '2025-03-19 17:24:34', '2025-04-19 14:46:12'),
(2022606, 'Omar Darwish', '', '1', 351, 0, 'sam', '2025-03-19 17:24:57', '2025-04-19 14:46:12'),
(2022609, 'Hassan Al-Masri', '', '2', 352, 1, 'ven', '2025-03-19 17:24:49', '2025-04-19 14:46:12'),
(2022652, 'Ahmed Ibrahim', '', '2', 351, 1, 'ven', '2025-03-19 17:25:00', '2025-04-19 14:46:12'),
(2022681, 'Faris Qasem', '', '2', 342, 0, '48h', '2025-03-19 17:24:23', '2025-04-19 14:46:12'),
(2022686, 'Hassan Nasser', '', '2', 343, 0, '48h', '2025-03-19 17:24:33', '2025-04-19 14:46:12'),
(2022701, 'Hassan Qasem', '', '2', 351, 0, 'sam', '2025-03-19 17:25:02', '2025-04-19 14:46:12'),
(2022705, 'Khaled Al-Farouq', '', '2', 341, 1, 'ven', '2025-03-19 17:24:39', '2025-04-19 14:46:12'),
(2022719, 'Faris Yazbek', '', '3', 361, 1, NULL, '2025-03-19 17:25:05', '2025-04-19 14:46:12'),
(2022726, 'Walid Ibrahim', '', '2', 353, 0, 'ven', '2025-03-19 17:24:28', '2025-04-19 14:46:12'),
(2022730, 'Omar Al-Farouq', '', '2', 362, 0, 'ven', '2025-03-19 17:24:52', '2025-04-19 14:46:12'),
(2022733, 'Youssef Darwish', '', '3', 341, 0, NULL, '2025-03-19 17:24:38', '2025-04-19 14:46:12'),
(2022740, 'Faris Darwish', '', '2', 361, 1, 'sam', '2025-03-19 17:25:06', '2025-04-19 14:46:12'),
(2022794, 'Walid Bakr', '', '1', 342, 0, 'ven', '2025-03-19 17:24:22', '2025-04-19 14:46:12'),
(2022800, 'Walid Yazbek', '', '2', 343, 1, '48h', '2025-03-19 17:24:32', '2025-04-19 14:46:12'),
(2022812, 'Amr Al-Farouq', '', '3', 361, 0, NULL, '2025-03-19 17:25:07', '2025-04-19 14:46:12'),
(2022813, 'Ahmed Zahran', '', '2', 343, 1, 'ven', '2025-03-19 17:24:35', '2025-04-19 14:46:12'),
(2022819, 'Omar Ibrahim', '', '1', 353, 0, 'sam', '2025-03-19 17:24:30', '2025-04-19 14:46:12'),
(2022830, 'Youssef Al-Farouq', '', '3', 362, 1, NULL, '2025-03-19 17:24:51', '2025-04-19 14:46:12'),
(2022859, 'Hassan Al-Masri', '', '2', 342, 0, '48h', '2025-03-19 17:24:24', '2025-04-19 14:46:12'),
(2022883, 'Hassan Bakr', '', '2', 343, 1, 'ven', '2025-03-19 17:24:36', '2025-04-19 14:46:12'),
(2022886, 'Youssef Zahran', '', '1', 361, 0, 'sam', '2025-03-19 17:25:04', '2025-04-19 14:46:12'),
(2022899, 'Youssef Ibrahim', '', '2', 362, 1, 'sam', '2025-03-19 17:24:56', '2025-04-19 14:46:12'),
(2022956, 'Faris Zahran', '', '2', 341, 1, 'sam', '2025-03-19 17:24:40', '2025-04-19 14:46:12'),
(2022958, 'Youssef Zahran', '', '2', 352, 0, 'sam', '2025-03-19 17:24:50', '2025-04-19 14:46:12'),
(2022962, 'Faris Nasser', '', '2', 353, 0, 'sam', '2025-03-19 17:24:27', '2025-04-19 14:46:12');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `bat` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `phone`, `bat`, `role_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'bou', '$2y$12$swRt6UaMIFOVAU.F8VyYp.ujtBFkrjEI7HixyP6bmIFkFMsBQ4.Oi', '770004546', '3', 1, 'dSJ9RHa5W50CMGIHwRzggjMzn5cjBboEI3zkuY5ezW3KT6RMfP84GGHWh0rN', '2025-05-21 16:31:30', '2025-05-21 16:31:30'),
(3, 'da9', '$2y$12$swRt6UaMIFOVAU.F8VyYp.ujtBFkrjEI7HixyP6bmIFkFMsBQ4.Oi', '770004546', '3', 2, 'BqobYAaGcHDuxFZcLjVS0AkVOdz6PtlHy4jLl2fn9tNteYcbKLZ2vTYAuIcK', '2025-05-21 16:31:30', '2025-05-21 16:31:30'),
(4, 'bouma3', '$2y$12$swRt6UaMIFOVAU.F8VyYp.ujtBFkrjEI7HixyP6bmIFkFMsBQ4.Oi', '770004546', '0', 3, 'AxhE5LiYbHcRu1wKBGmI0w1fMlH1Swxq4xylo0r7cxaUIgfD4rtsib8tFq6A', '2025-05-21 16:31:30', '2025-05-21 16:31:30'),
(5, 'MED', '$2y$12$swRt6UaMIFOVAU.F8VyYp.ujtBFkrjEI7HixyP6bmIFkFMsBQ4.Oi', '770004546', '0', 5, NULL, '2025-05-21 16:31:30', '2025-05-21 16:31:30'),
(6, 'lab', '$2y$12$swRt6UaMIFOVAU.F8VyYp.ujtBFkrjEI7HixyP6bmIFkFMsBQ4.Oi', '770004546', '0', 4, 'nt8cqKrFbtyWINCfCfQNkRLNBR6ufSzgAIjGzwjSCW8DR6CTUdYdx9Cqrfy5', '2025-05-21 16:31:30', '2025-05-21 16:31:30'),
(8, 'ham', '$2y$12$swRt6UaMIFOVAU.F8VyYp.ujtBFkrjEI7HixyP6bmIFkFMsBQ4.Oi', '770004546', '2', 1, NULL, '2025-05-21 16:31:30', '2025-05-21 16:31:30'),
(9, 'DG', '$2y$12$swRt6UaMIFOVAU.F8VyYp.ujtBFkrjEI7HixyP6bmIFkFMsBQ4.Oi', '770004546', '0', 6, 'GzN8TLIO1l3yrdOylfwMppZiYmNtWZXZUEhjojYC87qRg4GXg77tHvvybMZ0', '2025-05-21 16:31:30', '2025-05-21 16:31:30');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `arrets`
--
ALTER TABLE `arrets`
  ADD PRIMARY KEY (`report_id`,`sanction_id`),
  ADD KEY `arrets_sanction_id_foreign` (`sanction_id`),
  ADD KEY `arrets_created_by_foreign` (`created_by`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `expulsions`
--
ALTER TABLE `expulsions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expulsions_matricule_foreign` (`matricule`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `list_lock`
--
ALTER TABLE `list_lock`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Index pour la table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patients_matricule_foreign` (`matricule`);

--
-- Index pour la table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_student_id_foreign` (`student_id`),
  ADD KEY `reports_officer_id_foreign` (`officer_id`),
  ADD KEY `reports_destination_foreign` (`destination`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sanctions`
--
ALTER TABLE `sanctions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sanctions_matricule_foreign` (`matricule`),
  ADD KEY `report_id` (`report_id`);

--
-- Index pour la table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sections_officer_id_foreign` (`officer_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `sorties`
--
ALTER TABLE `sorties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sorties_matricule_foreign` (`matricule`);

--
-- Index pour la table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`matricule`),
  ADD KEY `students_section_id_foreign` (`section_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `expulsions`
--
ALTER TABLE `expulsions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `sanctions`
--
ALTER TABLE `sanctions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT pour la table `sorties`
--
ALTER TABLE `sorties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `arrets`
--
ALTER TABLE `arrets`
  ADD CONSTRAINT `arrets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `arrets_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `arrets_sanction_id_foreign` FOREIGN KEY (`sanction_id`) REFERENCES `sanctions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `expulsions`
--
ALTER TABLE `expulsions`
  ADD CONSTRAINT `expulsions_matricule_foreign` FOREIGN KEY (`matricule`) REFERENCES `students` (`matricule`) ON DELETE CASCADE;

--
-- Contraintes pour la table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_matricule_foreign` FOREIGN KEY (`matricule`) REFERENCES `students` (`matricule`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_destination_foreign` FOREIGN KEY (`destination`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `reports_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `reports_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`matricule`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sanctions`
--
ALTER TABLE `sanctions`
  ADD CONSTRAINT `sanctions_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sanctions_matricule_foreign` FOREIGN KEY (`matricule`) REFERENCES `students` (`matricule`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `sorties`
--
ALTER TABLE `sorties`
  ADD CONSTRAINT `sorties_matricule_foreign` FOREIGN KEY (`matricule`) REFERENCES `students` (`matricule`);

--
-- Contraintes pour la table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
