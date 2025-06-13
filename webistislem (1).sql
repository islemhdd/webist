-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 juin 2025 à 17:49
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
-- Base de données : `webistislem`
--

-- --------------------------------------------------------

--
-- Structure de la table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` int(11) NOT NULL,
  `medecin_id` int(11) NOT NULL,
  `type_medecin` enum('médecin générale','dentiste','psycho') NOT NULL,
  `date_appointment` datetime NOT NULL,
  `motif` text DEFAULT NULL,
  `status` enum('programmé','terminé','annulé') NOT NULL DEFAULT 'programmé',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
-- Structure de la table `consignes`
--

CREATE TABLE `consignes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `motif` varchar(255) NOT NULL,
  `start` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `convoncus`
--

CREATE TABLE `convoncus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule` bigint(20) UNSIGNED DEFAULT NULL,
  `psy` text DEFAULT NULL,
  `medGen` text DEFAULT NULL,
  `chirDent` text DEFAULT NULL,
  `avisSpe` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `convoncus`
--

INSERT INTO `convoncus` (`id`, `matricule`, `psy`, `medGen`, `chirDent`, `avisSpe`, `created_at`, `updated_at`) VALUES
(1, 2022056, 'Dr. Psychologue', NULL, NULL, NULL, '2025-05-25 20:58:34', '2025-05-25 20:58:34'),
(2, 2022064, 'lnjkb', 'Dr. Généraliste', 'bkb', 'kbjjb', '2025-05-25 20:58:34', '2025-05-26 08:09:20'),
(3, 2022131, 'Dr. Psy Autre', NULL, NULL, NULL, '2025-05-25 20:58:34', '2025-05-25 20:58:34'),
(4, 2022003, NULL, 'Dr. Médecin', NULL, NULL, '2025-05-25 20:58:34', '2025-05-25 20:58:34'),
(5, 2022002, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 2022055, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 2022250, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `exemptions`
--

CREATE TABLE `exemptions` (
  `matricule` bigint(20) UNSIGNED DEFAULT NULL,
  `motif` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `exemptions`
--

INSERT INTO `exemptions` (`matricule`, `motif`, `date_debut`, `date_fin`, `created_at`, `updated_at`) VALUES
(2022005, 'exemption rangers', '2025-05-24', '2025-05-31', '2025-05-23 12:16:55', '2025-05-23 12:16:55'),
(2022055, 'Une exemption d effort physique', '2025-05-25', '2025-06-01', '2025-05-25 20:59:41', '2025-05-25 20:59:41'),
(2022096, 'Une exemption de rasage de barbe', '2025-05-25', '2025-05-28', '2025-05-25 20:59:41', '2025-05-25 20:59:41'),
(2022002, 'Une exemption du port de rangers', '2025-05-25', '2025-06-08', '2025-05-25 20:59:41', '2025-05-25 20:59:41'),
(2022120, 'Une exemption de rasage de barbe', '2025-05-25', '2025-05-30', '2025-05-25 20:59:41', '2025-05-25 20:59:41'),
(2022064, 'Une exemption d effort physique', '2025-05-25', '2025-06-04', '2025-05-25 20:59:41', '2025-05-25 20:59:41'),
(2022250, 'exemption effort physique', '2025-06-01', '2025-06-28', '2025-06-01 10:00:12', '2025-06-01 10:00:12'),
(2022003, 'exemption rasage barbe', '2025-06-01', '2025-06-10', '2025-06-01 10:00:38', '2025-06-01 10:00:38');

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

--
-- Déchargement des données de la table `expulsions`
--

INSERT INTO `expulsions` (`id`, `matricule`, `motif_expulsion`, `date_expulsion`, `description`, `created_at`, `updated_at`) VALUES
(1, 2022002, 'Comportement inapproprié', '2025-05-23', 'Description détaillée de lexpulsion #1', '2025-05-27 17:50:21', '2025-05-27 17:50:21'),
(2, 2022004, 'Non-respect du règlement', '2025-05-15', 'Description détaillée de lexpulsion #3', '2025-05-27 17:50:21', '2025-05-27 17:50:21'),
(3, 2022003, 'Absence injustifiée', '2025-05-19', 'Description détaillée de lexpulsion #2', '2025-05-27 17:51:15', '2025-05-27 17:51:15'),
(4, 2022055, 'Absence', '2025-05-29', 'Grade 1 test expulsion #1', '2025-05-29 11:25:34', '2025-05-29 11:25:34'),
(5, 2022056, 'Retard', '2025-05-29', 'Grade 1 test expulsion #2', '2025-05-29 11:25:34', '2025-05-29 11:25:34'),
(6, 2022096, 'Bavardage', '2025-05-29', 'Grade 2 test expulsion #1', '2025-05-29 11:25:34', '2025-05-29 11:25:34'),
(7, 2022250, 'Bavardage', '2025-06-01', 'jb', '2025-06-01 11:41:38', NULL),
(8, 2022011, 'Manque de respect', '2025-06-01', ',', '2025-06-01 11:43:11', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
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
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `liste_rdvs`
--

CREATE TABLE `liste_rdvs` (
  `matricule` int(11) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `type_medecin` enum('médecin générale','dentiste','psycho','chef_médecin') DEFAULT NULL,
  `service` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `liste_rdvs`
--

INSERT INTO `liste_rdvs` (`matricule`, `motif`, `type_medecin`, `service`, `date`, `created_at`, `updated_at`) VALUES
(2022055, 'consultation', 'médecin générale', 'Médecine générale', '2025-05-25', '2025-05-25 20:58:34', '2025-05-25 20:58:34'),
(2022096, 'urgences', 'médecin générale', 'Urgences', '2025-05-25', '2025-05-25 20:58:34', '2025-05-25 20:58:34'),
(2022120, 'urgences', 'médecin générale', 'Urgences', '2025-05-25', '2025-05-25 20:58:34', '2025-05-25 20:58:34'),
(2022250, 'consultation', 'médecin générale', 'reanimation', '2025-06-01', '2025-06-01 09:58:49', '2025-06-01 09:58:49'),
(2022250, 'urgences', 'médecin générale', 'orl', '2025-06-01', '2025-06-01 09:59:05', '2025-06-01 09:59:05'),
(2022003, 'urgences', 'médecin générale', 'neurologie', '2025-06-01', '2025-06-01 09:59:41', '2025-06-01 09:59:41'),
(2022250, 'consultation', 'médecin générale', 'psychiatrie', '2025-06-02', '2025-06-02 13:27:34', '2025-06-02 13:27:34'),
(2022250, 'urgences', 'psycho', 'neurologie', '2025-06-02', '2025-06-02 13:51:40', '2025-06-02 13:51:40'),
(2022002, 'urgences', 'psycho', 'neurologie', '2025-06-02', '2025-06-02 14:01:17', '2025-06-02 14:01:17'),
(2022250, 'consultation', 'dentiste', 'chirurgie_dentaire', '2025-06-02', '2025-06-02 16:12:54', '2025-06-02 16:12:54'),
(2022250, 'consultation', 'chef_médecin', 'radiologie', '2025-06-03', '2025-06-02 23:12:22', '2025-06-02 23:12:22'),
(2022002, 'urgences', 'chef_médecin', 'anesthesie', '2025-06-03', '2025-06-02 23:14:42', '2025-06-02 23:14:42');

-- --------------------------------------------------------

--
-- Structure de la table `list_lock`
--

CREATE TABLE `list_lock` (
  `id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '0001_01_01_000003_create_password_reset_tokens_table', 1),
(5, '0001_01_01_000004_create_session_table', 1),
(6, '2024_01_01_000001_create_roles_table', 1),
(7, '2024_01_02_000000_create_sections_table', 1),
(8, '2024_05_15_185257_create_eleves_table', 1),
(9, '2024_06_01_000000_create_appointments_table', 1),
(10, '2025_04_16_201557_create_liste_rdvs_table', 1),
(11, '2025_04_23_230937_create_exemptions_table', 1),
(12, '2025_04_24_002618_create_patients_table', 1),
(13, '2025_05_05_204343_create_convoncus_table', 1),
(14, '2025_05_22_001042_create_notifications_table', 1),
(15, '2025_05_26_205207_create_sanctions_table', 1),
(16, '2025_05_27_182910_create_expulsions_table', 1),
(17, '2025_05_27_183024_create_r_h_p_s_table', 1),
(18, '2025_06_01_093822_update_reports_table_fix_status_and_columns', 1),
(19, '2025_06_02_100000_create_consignes_table', 1),
(20, '2025_06_02_110000_create_sorties_table', 1),
(21, '2025_06_02_120000_create_list_lock_table', 1),
(22, '2025_06_02_140000_modify_type_medecin_enum_column', 1);

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
('2a64297b-71a8-45fb-b03d-3d4dfd440bb2', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":22,\"title\":\"jfj\",\"message\":\"New report: jfj\",\"url\":\"http://127.0.0.1:8000/1/show/22\"}', NULL, '2025-06-01 09:23:48', '2025-06-01 09:23:48'),
('2f341257-0310-42d6-850e-0298c5aa878e', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 6, '{\"report_id\":17,\"title\":\"ojln\",\"corps\":\"ijkl\",\"status\":\"DIV\"}', NULL, '2025-05-23 01:03:17', '2025-05-23 01:03:17'),
('7f647ec7-5077-429c-a68d-86fd4aebd62d', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":21,\"title\":\"kl\",\"message\":\"New report: kl\",\"url\":\"http://127.0.0.1:8000/1/show/21\"}', NULL, '2025-06-01 09:16:12', '2025-06-01 09:16:12');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule` bigint(20) UNSIGNED DEFAULT NULL,
  `valider` tinyint(4) NOT NULL DEFAULT 0,
  `valider_rhp` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=non validé par RHP, 1=validé par RHP',
  `validated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `motif_suppression` text DEFAULT NULL,
  `type_medecin` enum('médecin générale','dentiste','psycho') DEFAULT NULL,
  `avis_medecin` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `corps` varchar(255) NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_medical` tinyint(1) DEFAULT NULL,
  `officer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `destination` bigint(20) UNSIGNED DEFAULT NULL,
  `AvisChef_de_compagnie` text DEFAULT NULL,
  `AvisChef_de_batallaint` text DEFAULT NULL,
  `AvisChef_de_brigade` text DEFAULT NULL,
  `AvisDirecteur_général` text DEFAULT NULL,
  `motif` text DEFAULT NULL,
  `refused` tinyint(1) NOT NULL DEFAULT 0,
  `AvisChef_division` text DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rhps`
--

CREATE TABLE `rhps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `date_assignation` date NOT NULL,
  `periode` enum('matin','apres_midi') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `rhps`
--

INSERT INTO `rhps` (`id`, `officer_id`, `date_assignation`, `periode`, `notes`, `created_at`, `updated_at`) VALUES
(1, 8, '2025-05-28', 'matin', NULL, '2025-05-28 07:18:05', '2025-05-28 07:18:05'),
(2, 8, '2025-05-29', 'matin', NULL, '2025-05-28 09:35:51', '2025-05-28 09:35:51'),
(3, 1, '2025-05-28', 'apres_midi', NULL, '2025-05-28 13:18:11', '2025-05-28 13:18:11'),
(4, 1, '2025-06-01', 'matin', NULL, '2025-06-01 15:43:56', '2025-06-01 15:43:56'),
(5, 8, '2025-06-03', 'matin', NULL, '2025-06-02 23:17:07', '2025-06-02 23:17:07');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Chef de batallaint', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(2, 'Chef de compagnie', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(3, 'Chef de brigade', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(4, 'Lab', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(5, 'Médecin Chef', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(6, 'Directeur général', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(7, 'Chef division', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(8, 'Directeur des études', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(9, 'Psychologue', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(10, 'Dentiste', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(11, 'Médecin généraliste', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(12, 'RHP', '2025-06-11 03:47:16', '2025-06-11 03:47:16'),
(13, 'Admin', '2025-06-11 03:47:16', '2025-06-11 03:47:16');

-- --------------------------------------------------------

--
-- Structure de la table `sanctions`
--

CREATE TABLE `sanctions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `matricule` bigint(20) UNSIGNED NOT NULL,
  `name` enum('consigne','arret','blame','avert') NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `motif` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `num` enum('1','2','3') NOT NULL,
  `companie` enum('1','2','3','4','5','6') NOT NULL,
  `bat` enum('1','2','3') NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sections`
--

INSERT INTO `sections` (`id`, `num`, `companie`, `bat`, `officer_id`, `created_at`, `updated_at`) VALUES
(321, '1', '2', '3', 1, '2025-05-22 20:18:07', '2025-05-22 20:18:07'),
(341, '1', '4', '3', 8, '2025-03-20 03:50:30', '2025-03-20 03:50:48'),
(342, '2', '4', '3', 8, '2025-03-20 03:50:30', '2025-03-20 03:50:48'),
(343, '3', '4', '3', 8, '2025-03-20 03:50:30', '2025-03-20 03:50:48'),
(351, '1', '5', '3', 1, '2025-03-20 03:50:30', '2025-03-20 03:50:48'),
(352, '2', '5', '3', 1, '2025-03-20 03:50:30', '2025-03-20 03:50:48'),
(353, '3', '5', '3', 1, '2025-03-20 03:50:30', '2025-03-20 03:50:48'),
(361, '1', '6', '3', 1, '2025-03-20 03:50:30', '2025-03-20 03:50:48'),
(362, '2', '6', '3', 1, '2025-03-20 03:50:30', '2025-03-20 03:50:48');

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
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `choix` varchar(30) NOT NULL,
  `remarque` text DEFAULT NULL,
  `from` timestamp NULL DEFAULT NULL,
  `to` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sorties`
--

INSERT INTO `sorties` (`id`, `student_id`, `choix`, `remarque`, `from`, `to`, `created_at`, `updated_at`) VALUES
(1, 2022250, 'sam', NULL, '2025-06-14 09:00:00', '2025-06-14 23:00:00', '2025-06-13 16:46:30', '2025-06-13 16:46:30'),
(2, 2022002, 'ven', NULL, '2025-06-20 09:00:00', '2025-06-20 23:00:00', '2025-06-13 16:47:03', '2025-06-13 16:47:03');

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

CREATE TABLE `students` (
  `matricule` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `grade` enum('1','2','3') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `consigned` tinyint(1) DEFAULT NULL,
  `choix` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `students`
--

INSERT INTO `students` (`matricule`, `nom`, `prenom`, `grade`, `created_at`, `updated_at`, `deleted_at`, `section_id`, `consigned`, `choix`) VALUES
(2022002, 'Faris Ibrahim', '', '3', '2025-02-26 03:52:01', '2025-06-13 16:47:03', NULL, 361, 0, 'ven'),
(2022003, 'Youssef Darwish', '', '3', '2025-02-26 03:52:01', '2025-04-21 03:53:31', NULL, 361, 0, NULL),
(2022004, 'Walid Yazbek', '', '3', '2025-02-26 03:52:01', '2025-04-21 03:54:19', NULL, 361, 0, NULL),
(2022005, 'Youssef Qasem', '', '3', '2025-02-26 03:52:01', '2025-04-21 03:54:36', NULL, 361, 0, NULL),
(2022006, 'Ali Darwish', '', '3', '2025-02-26 03:52:01', '2025-04-21 03:54:50', NULL, 361, 0, NULL),
(2022007, 'Youssef Ibrahim', '', '3', '2025-02-26 03:52:01', '2025-04-19 16:46:11', NULL, 361, 0, NULL),
(2022011, 'Ahmed Al-Masri', '', '3', '2025-02-26 03:52:01', '2025-05-23 01:23:41', NULL, 351, 1, 'ven'),
(2022055, 'Walid Zahran', '', '1', '2025-03-19 19:24:21', '2025-04-19 16:46:11', NULL, 342, 0, '48h'),
(2022056, 'Hassan Haddad', '', '1', '2025-03-19 19:24:57', '2025-04-19 16:46:11', NULL, 351, 1, '48h'),
(2022064, 'Youssef Qasem', '', '1', '2025-03-19 19:24:48', '2025-04-19 16:46:11', NULL, 352, 1, 'sam'),
(2022096, 'Ziad Nasser', '', '2', '2025-03-19 19:24:51', '2025-04-19 16:46:11', NULL, 362, 1, 'ven'),
(2022104, 'Youssef Yazbek', '', '1', '2025-03-19 19:24:21', '2025-04-19 16:46:11', NULL, 342, 1, 'sam'),
(2022120, 'Ziad Darwish', '', '2', '2025-03-19 19:24:44', '2025-04-19 16:46:11', NULL, 352, 1, 'sam'),
(2022131, 'Faris Qasem', '', '2', '2025-03-19 19:24:46', '2025-04-19 16:46:11', NULL, 352, 0, 'sam'),
(2022250, 'Guezouli', 'Mohamed anes', '3', NULL, '2025-06-13 16:46:30', NULL, 353, NULL, 'sam');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` int(11) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `bat` enum('0','1','2','3') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `role_id`, `bat`) VALUES
(1, 'Boutaba Malik', '$2y$12$KBSPc2OWVBhINrgkluYEjOSDTgiFwfN5WXYgoIwXxcdZQj17XIvbC', 'agZ1InKrwbygFuwTg0oeDJd54Ugrngvfrt1KSKJJqlQwg5E3PFVYviA1rdai', '2025-05-21 18:31:30', '2025-05-21 18:31:30', 770004546, 2, '3'),
(3, 'Dekiche', '$2y$12$KBSPc2OWVBhINrgkluYEjOSDTgiFwfN5WXYgoIwXxcdZQj17XIvbC', NULL, '2025-05-21 18:31:30', '2025-05-21 18:31:30', 770004546, 1, '3'),
(4, 'Boumaaza Salim', '$2y$12$KBSPc2OWVBhINrgkluYEjOSDTgiFwfN5WXYgoIwXxcdZQj17XIvbC', NULL, '2025-05-21 18:31:30', '2025-05-21 18:31:30', 770004546, 3, '0'),
(5, 'Dr. Médecin Chef', '$2y$12$KBSPc2OWVBhINrgkluYEjOSDTgiFwfN5WXYgoIwXxcdZQj17XIvbC', NULL, '2025-05-21 18:31:30', '2025-06-01 20:30:54', 770004546, 5, '0'),
(6, 'lab', '$2y$12$KBSPc2OWVBhINrgkluYEjOSDTgiFwfN5WXYgoIwXxcdZQj17XIvbC', NULL, '2025-05-21 18:31:30', '2025-05-21 18:31:30', 770004546, 4, '0'),
(8, 'Hamadou Hossem', '$2y$12$KBSPc2OWVBhINrgkluYEjOSDTgiFwfN5WXYgoIwXxcdZQj17XIvbC', NULL, '2025-05-21 18:31:30', '2025-05-21 18:31:30', 770004546, 1, '2'),
(9, 'Directeur des études', '$2y$12$KBSPc2OWVBhINrgkluYEjOSDTgiFwfN5WXYgoIwXxcdZQj17XIvbC', NULL, '2025-05-27 17:28:02', '2025-05-27 17:46:11', 12345678, 8, '0'),
(10, 'Dr. Psychologue', '$2y$12$KBSPc2OWVBhINrgkluYEjOSDTgiFwfN5WXYgoIwXxcdZQj17XIvbC', NULL, '2025-06-01 21:58:49', '2025-06-01 20:30:55', 770001001, 9, '0'),
(11, 'Dr. Dentiste', '$2y$12$KBSPc2OWVBhINrgkluYEjOSDTgiFwfN5WXYgoIwXxcdZQj17XIvbC', NULL, '2025-06-01 21:58:49', '2025-06-01 20:30:57', 770001002, 10, '0'),
(12, 'Dr. Généraliste', '$2y$12$KBSPc2OWVBhINrgkluYEjOSDTgiFwfN5WXYgoIwXxcdZQj17XIvbC', NULL, '2025-06-01 21:58:49', '2025-06-01 20:30:58', 770001003, 11, '0'),
(13, 'Directeur general', '$2y$12$KBSPc2OWVBhINrgkluYEjOSDTgiFwfN5WXYgoIwXxcdZQj17XIvbC', NULL, '2025-05-21 18:31:30', '2025-05-21 18:31:30', 770004546, 6, '0');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

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
-- Index pour la table `consignes`
--
ALTER TABLE `consignes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consignes_student_id_foreign` (`student_id`);

--
-- Index pour la table `convoncus`
--
ALTER TABLE `convoncus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `convoncus_matricule_foreign` (`matricule`);

--
-- Index pour la table `exemptions`
--
ALTER TABLE `exemptions`
  ADD KEY `exemptions_matricule_foreign` (`matricule`);

--
-- Index pour la table `expulsions`
--
ALTER TABLE `expulsions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expulsions_matricule_foreign` (`matricule`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
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
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
-- Index pour la table `rhps`
--
ALTER TABLE `rhps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rhps_date_assignation_periode_unique` (`date_assignation`,`periode`),
  ADD KEY `rhps_officer_id_foreign` (`officer_id`);

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
  ADD KEY `sanctions_matricule_foreign` (`matricule`);

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
  ADD KEY `sorties_student_id_foreign` (`student_id`);

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
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `consignes`
--
ALTER TABLE `consignes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `convoncus`
--
ALTER TABLE `convoncus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `expulsions`
--
ALTER TABLE `expulsions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `rhps`
--
ALTER TABLE `rhps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `sanctions`
--
ALTER TABLE `sanctions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT pour la table `sorties`
--
ALTER TABLE `sorties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `consignes`
--
ALTER TABLE `consignes`
  ADD CONSTRAINT `consignes_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`matricule`);

--
-- Contraintes pour la table `convoncus`
--
ALTER TABLE `convoncus`
  ADD CONSTRAINT `convoncus_matricule_foreign` FOREIGN KEY (`matricule`) REFERENCES `students` (`matricule`);

--
-- Contraintes pour la table `exemptions`
--
ALTER TABLE `exemptions`
  ADD CONSTRAINT `exemptions_matricule_foreign` FOREIGN KEY (`matricule`) REFERENCES `students` (`matricule`) ON DELETE CASCADE;

--
-- Contraintes pour la table `expulsions`
--
ALTER TABLE `expulsions`
  ADD CONSTRAINT `expulsions_matricule_foreign` FOREIGN KEY (`matricule`) REFERENCES `students` (`matricule`) ON DELETE CASCADE;

--
-- Contraintes pour la table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_matricule_foreign` FOREIGN KEY (`matricule`) REFERENCES `students` (`matricule`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_destination_foreign` FOREIGN KEY (`destination`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reports_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `reports_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`matricule`);

--
-- Contraintes pour la table `rhps`
--
ALTER TABLE `rhps`
  ADD CONSTRAINT `rhps_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sanctions`
--
ALTER TABLE `sanctions`
  ADD CONSTRAINT `sanctions_matricule_foreign` FOREIGN KEY (`matricule`) REFERENCES `students` (`matricule`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `sorties`
--
ALTER TABLE `sorties`
  ADD CONSTRAINT `sorties_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`matricule`);

--
-- Contraintes pour la table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
