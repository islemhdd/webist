-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 26 déc. 2025 à 17:42
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
  `start` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `consignes`
--

INSERT INTO `consignes` (`id`, `student_id`, `motif`, `start`) VALUES
(2, 2022002, 'badd', '2025-05-24 02:49:35');

-- --------------------------------------------------------

--
-- Structure de la table `convoncus`
--

CREATE TABLE `convoncus` (
  `matricule` bigint(20) UNSIGNED DEFAULT NULL,
  `psy` text DEFAULT NULL,
  `medGen` text DEFAULT NULL,
  `chirDent` text DEFAULT NULL,
  `avisSpe` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `exemptions`
--

INSERT INTO `exemptions` (`matricule`, `motif`, `date_debut`, `date_fin`, `created_at`, `updated_at`) VALUES
(2022005, 'exemption rangers', '2025-05-24', '2025-05-31', '2025-05-23 11:16:55', '2025-05-23 11:16:55');

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

--
-- Déchargement des données de la table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(16, '7d20ef17-ebf2-4ea3-8c67-79affe2df3d1', 'database', 'default', '{\"uuid\":\"7d20ef17-ebf2-4ea3-8c67-79affe2df3d1\",\"displayName\":\"App\\\\Notifications\\\\ReportArival\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Officer\\\";s:2:\\\"id\\\";a:1:{i:0;i:3;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:30:\\\"App\\\\Notifications\\\\ReportArival\\\":3:{s:8:\\\"reportId\\\";i:2;s:6:\\\"report\\\";a:16:{s:2:\\\"id\\\";i:2;s:5:\\\"title\\\";s:5:\\\"mlfv;\\\";s:5:\\\"corps\\\";s:9:\\\"m,fvlkerv\\\";s:10:\\\"student_id\\\";i:2022002;s:4:\\\"read\\\";i:0;s:10:\\\"deleted_at\\\";N;s:10:\\\"created_at\\\";s:27:\\\"2025-04-15T21:56:02.000000Z\\\";s:10:\\\"updated_at\\\";s:27:\\\"2025-04-15T21:56:02.000000Z\\\";s:6:\\\"status\\\";s:7:\\\"CREATED\\\";s:10:\\\"is_medical\\\";N;s:10:\\\"officer_id\\\";i:2;s:11:\\\"destination\\\";N;s:5:\\\"Avis1\\\";N;s:5:\\\"Avis2\\\";N;s:5:\\\"Avis3\\\";N;s:5:\\\"Avis4\\\";N;}s:2:\\\"id\\\";s:36:\\\"20345779-87a7-4ea5-84d3-da8078bc79f8\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\"}}', 'PDOException: SQLSTATE[42S02]: Base table or view not found: 1146 Table \'laravel101.notifications\' doesn\'t exist in C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php:47\nStack trace:\n#0 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(47): PDO->prepare(\'insert into `no...\')\n#1 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(809): Illuminate\\Database\\MySqlConnection->Illuminate\\Database\\{closure}(\'insert into `no...\', Array)\n#2 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(776): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#3 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#4 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3720): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#5 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2204): Illuminate\\Database\\Query\\Builder->insert(Array)\n#6 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1339): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#7 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1167): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#8 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(370): Illuminate\\Database\\Eloquent\\Model->save()\n#9 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#10 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(367): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#11 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#12 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(147): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\Officer), Object(App\\Notifications\\ReportArival))\n#13 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(105): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\Officer), \'4dcb14e8-3c18-4...\', Object(App\\Notifications\\ReportArival), \'database\')\n#14 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#15 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(100): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#16 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\ReportArival), Array)\n#17 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(118): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\ReportArival), Array)\n#18 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#19 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#21 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#22 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#23 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(125): Illuminate\\Container\\Container->call(Array)\n#24 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#25 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#27 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#28 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#31 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#32 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#33 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(391): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(177): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#36 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#37 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#38 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#41 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#42 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#43 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#44 C:\\xampp\\htdocs\\webist\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#45 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#46 C:\\xampp\\htdocs\\webist\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\webist\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\xampp\\htdocs\\webist\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 C:\\xampp\\htdocs\\webist\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#52 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S02]: Base table or view not found: 1146 Table \'laravel101.notifications\' doesn\'t exist (Connection: mysql, SQL: insert into `notifications` (`id`, `type`, `data`, `read_at`, `notifiable_id`, `notifiable_type`, `updated_at`, `created_at`) values (20345779-87a7-4ea5-84d3-da8078bc79f8, App\\Notifications\\ReportArival, {\"id\":2,\"title\":\"mlfv;\"}, ?, 3, App\\Models\\Officer, 2025-04-20 23:55:43, 2025-04-20 23:55:43)) in C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:822\nStack trace:\n#0 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(776): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#1 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#2 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3720): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#3 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2204): Illuminate\\Database\\Query\\Builder->insert(Array)\n#4 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1339): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#5 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1167): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#6 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(370): Illuminate\\Database\\Eloquent\\Model->save()\n#7 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#8 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(367): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#9 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#10 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(147): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\Officer), Object(App\\Notifications\\ReportArival))\n#11 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(105): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\Officer), \'4dcb14e8-3c18-4...\', Object(App\\Notifications\\ReportArival), \'database\')\n#12 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#13 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(100): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#14 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\ReportArival), Array)\n#15 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(118): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\ReportArival), Array)\n#16 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#17 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#18 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#19 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#20 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#21 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(125): Illuminate\\Container\\Container->call(Array)\n#22 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#24 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#26 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(169): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(126): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#28 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(120): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#31 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(441): Illuminate\\Queue\\Jobs\\Job->fire()\n#32 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(391): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(177): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#36 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#37 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#38 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#39 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#40 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(754): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#41 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#42 C:\\xampp\\htdocs\\webist\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#44 C:\\xampp\\htdocs\\webist\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\webist\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\webist\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\xampp\\htdocs\\webist\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1234): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\xampp\\htdocs\\webist\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#50 {main}', '2025-04-21 00:55:43');

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
-- Structure de la table `list_lock`
--

CREATE TABLE `list_lock` (
  `id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(28, '0001_01_01_000000_create_users_table', 1),
(29, '0001_01_01_000001_create_cache_table', 1),
(30, '0001_01_01_000002_create_jobs_table', 1),
(31, '0001_01_01_000004_create_session_table', 1),
(32, '2024_05_15_185257_create_eleves_table', 1),
(33, '2025_04_16_201557_create_liste_rdvs_table', 1),
(34, '2025_04_23_230937_create_exemptions_table', 1),
(35, '2025_04_24_002618_create_patients_table', 1),
(36, '2025_05_05_204343_create_convoncus_table', 1),
(38, '2025_05_22_001042_create_notifications_table', 2),
(39, '2025_05_26_191017_add_fields_to_patients_table', 3),
(41, '2025_05_27_182833_add_valider_rhp_to_patients_table', 3),
(42, '2025_05_27_182910_create_expulsions_table', 3),
(43, '2025_05_27_183024_create_r_h_p_s_table', 3),
(44, '2025_05_26_205207_create_sanctions_table', 4);

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
('2f341257-0310-42d6-850e-0298c5aa878e', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 6, '{\"report_id\":17,\"title\":\"ojln\",\"corps\":\"ijkl\",\"status\":\"DIV\"}', NULL, '2025-05-23 00:03:17', '2025-05-23 00:03:17'),
('3b0b37c7-8bfd-4cb9-bc6b-7955dda8cce7', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 4, '{\"report_id\":16,\"title\":\"kljnlk\",\"corps\":\"oiubibuioblhkj\",\"status\":\"DIV\"}', NULL, '2025-05-22 21:47:02', '2025-05-22 21:47:02'),
('3c203939-24a4-44ed-8677-c3f719f19e1d', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":20,\"title\":\"dcsdc\",\"message\":\"New report: dcsdc\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/1\\/show\\/20\"}', NULL, '2025-05-30 09:46:38', '2025-05-30 09:46:38'),
('61118f80-78e5-4acf-b91e-9b2409f920ab', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":23,\"title\":\"ksdlcn\",\"message\":\"New report: ksdlcn\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/1\\/show\\/23\"}', NULL, '2025-07-17 20:56:11', '2025-07-17 20:56:11'),
('6fc2224f-1725-42c7-af60-6d23babcabb1', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 4, '{\"report_id\":17,\"title\":\"ojln\",\"corps\":\"ijkl\",\"status\":\"CBr\"}', NULL, '2025-05-23 00:00:39', '2025-05-23 00:00:39'),
('71e38f91-33c8-43d4-9bc4-8487ffd76703', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":18,\"title\":\"ttitit\",\"corps\":\"seqfliuhqsfk:vqnf\",\"status\":\"CBt\"}', NULL, '2025-05-22 23:53:02', '2025-05-22 23:53:02'),
('76b6e24d-fe0c-4156-b601-049b6ca00df6', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":18,\"title\":\"ttitit\",\"corps\":\"seqfliuhqsfk:vqnf\",\"status\":\"CC\"}', NULL, '2025-05-22 23:52:48', '2025-05-22 23:52:48'),
('a8e4efea-dbe4-4f5d-a166-d3d79eb6b4e5', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 4, '{\"report_id\":18,\"title\":\"ttitit\",\"corps\":\"seqfliuhqsfk:vqnf\",\"status\":\"DIV\"}', NULL, '2025-05-22 23:53:53', '2025-05-22 23:53:53'),
('a9dfdc63-d6bb-43ab-b73c-c4c8c4928700', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":17,\"title\":\"ojln\",\"corps\":\"ijkl\",\"status\":\"CC\"}', NULL, '2025-05-22 04:16:13', '2025-05-22 04:16:13'),
('aa70bbdd-468e-4cf6-955c-dfc21a5c64f4', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 4, '{\"report_id\":16,\"title\":\"kljnlk\",\"corps\":\"oiubibuioblhkj\",\"status\":\"DIV\"}', NULL, '2025-05-22 19:51:09', '2025-05-22 19:51:09'),
('c72b5411-1ec1-4a9a-9aa4-267c486cd082', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":21,\"title\":\"jhbkjh\",\"message\":\"New report: jhbkjh\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/1\\/show\\/21\"}', NULL, '2025-05-30 12:54:56', '2025-05-30 12:54:56'),
('e4ee1c2d-427f-4f67-b9b6-9130e7d1d5fc', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 4, '{\"report_id\":16,\"title\":\"kljnlk\",\"corps\":\"oiubibuioblhkj\",\"status\":\"DIV\"}', NULL, '2025-05-22 19:52:35', '2025-05-22 19:52:35'),
('e74c5f8f-d1e6-4629-8c44-2e8ba31c50f2', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 6, '{\"report_id\":16,\"title\":\"kljnlk\",\"corps\":\"oiubibuioblhkj\",\"status\":\"DIV\"}', NULL, '2025-05-22 21:57:27', '2025-05-22 21:57:27'),
('efd7df2b-3ca4-4a0f-b358-4c7263d776bc', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 4, '{\"report_id\":17,\"title\":\"ojln\",\"corps\":\"ijkl\",\"status\":\"CBr\"}', NULL, '2025-05-23 00:02:19', '2025-05-23 00:02:19'),
('f40dd28b-c881-42d5-886f-6be5b38369f9', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 0, '{\"report_id\":16,\"title\":\"kljnlk\",\"corps\":\"oiubibuioblhkj\",\"status\":\"DIV\"}', NULL, '2025-05-22 22:14:18', '2025-05-22 22:14:18'),
('f7d17540-404a-4fbc-a17a-7f6c818d7ee0', 'App\\Notifications\\ReportArival', 'App\\Models\\Officer', 3, '{\"report_id\":23,\"title\":\"ksdlcn\",\"message\":\"New report: ksdlcn\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/1\\/show\\/23\"}', NULL, '2025-07-17 20:56:31', '2025-07-17 20:56:31');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `patients`
--

INSERT INTO `patients` (`id`, `matricule`, `valider`, `valider_rhp`, `validated_at`, `created_at`, `updated_at`, `motif_suppression`, `type_medecin`, `avis_medecin`) VALUES
(1, 2022003, 1, 0, NULL, '2025-05-23 16:42:37', '2025-05-23 16:42:37', NULL, NULL, NULL),
(2, 2022004, 0, 0, '2025-05-24 00:20:09', '2025-05-23 16:42:37', '2025-05-24 00:20:09', NULL, NULL, NULL),
(4, 2022104, 0, 0, NULL, '2025-05-23 16:43:06', '2025-05-23 16:42:37', NULL, NULL, NULL),
(5, 2022004, 2, 0, '2025-05-24 00:20:09', '2025-05-23 16:42:37', '2025-05-24 00:20:09', NULL, NULL, NULL),
(6, 2022002, 0, 0, NULL, '2025-07-17 20:55:16', '2025-07-17 20:55:16', NULL, NULL, NULL),
(7, 2022002, 0, 0, NULL, '2025-07-17 20:55:20', '2025-07-17 20:55:20', NULL, NULL, NULL);

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
  `status` enum('Chef de compagnie','Chef de batallaint','Chef de brigade','Chef division','Directeur général','DONE','REFUSED') NOT NULL,
  `is_medical` tinyint(1) DEFAULT NULL,
  `officer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `destination` bigint(20) UNSIGNED DEFAULT NULL,
  `AvisChef_de_compagnie` text DEFAULT NULL,
  `AvisChef_de_batallaint` text DEFAULT NULL,
  `AvisChef_de_brigade` text DEFAULT NULL,
  `AvisDirecteur_général` text DEFAULT NULL,
  `motif` text DEFAULT NULL,
  `refused` tinyint(1) DEFAULT 0,
  `AvisChef_division` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reports`
--

INSERT INTO `reports` (`id`, `title`, `corps`, `student_id`, `deleted_at`, `created_at`, `updated_at`, `status`, `is_medical`, `officer_id`, `destination`, `AvisChef_de_compagnie`, `AvisChef_de_batallaint`, `AvisChef_de_brigade`, `AvisDirecteur_général`, `motif`, `refused`, `AvisChef_division`) VALUES
(20, 'dcsdc', 'dcoisjd', 2022003, NULL, '2025-05-30 09:46:37', '2025-05-30 09:46:39', 'Chef de batallaint', 0, 1, 3, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(21, 'jhbkjh', 'jklbjhklkkhgvj', 2022004, NULL, '2025-05-30 12:54:55', '2025-05-30 12:54:56', 'Chef de batallaint', 0, 1, 3, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(22, 'miohbj,', 'jhkvbhbgh ', 2022004, NULL, '2025-05-30 12:55:12', '2025-05-30 13:58:11', 'DONE', 0, 1, 1, 'bbbbbbb', 'zelfhsdcfvze', 'dkjcnslqdc sd', 'ui<sjklcn<sdklc<s', NULL, 0, 'KKKKKKKKKKKKKK'),
(23, 'ksdlcn', 'ZeofdjZEMOFNEQR', 2022002, NULL, '2025-07-17 20:56:09', '2025-07-17 20:56:31', 'Chef de batallaint', 0, 1, 3, 'LFNVDMFVNQVFS', NULL, NULL, NULL, NULL, 0, NULL);

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
(1, 'Chef de compagnie', '2025-03-19 12:24:41', '2025-03-19 12:24:41'),
(2, 'Chef de batallaint', '2025-03-19 12:24:41', '2025-03-19 12:24:41'),
(3, 'Chef de brigade', '2025-03-19 12:24:41', '2025-03-19 12:24:41'),
(4, 'Chef division', '2025-03-19 12:24:41', '2025-03-19 12:24:41'),
(5, 'Medecin', '2025-03-19 12:24:41', '2025-03-19 12:24:41'),
(6, 'Directeur général', '2025-03-19 12:24:41', '2025-03-19 12:24:41');

-- --------------------------------------------------------

--
-- Structure de la table `sanctions`
--

CREATE TABLE `sanctions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `matricule` bigint(20) UNSIGNED NOT NULL,
  `type` enum('consigne','arret','blame','avert') NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `motif` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sanctions`
--

INSERT INTO `sanctions` (`id`, `created_at`, `updated_at`, `matricule`, `type`, `date_debut`, `date_fin`, `motif`) VALUES
(1, '2025-05-30 16:47:49', '2025-06-30 16:47:49', 2022002, 'arret', '2025-05-30', '2025-06-30', 'lalala');

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
(321, '1', '2', '3', 1, '2025-05-22 19:18:07', '2025-05-22 19:18:07'),
(341, '1', '4', '3', 8, '2025-03-20 02:50:30', '2025-03-20 02:50:48'),
(342, '2', '4', '3', 8, '2025-03-20 02:50:30', '2025-03-20 02:50:48'),
(343, '3', '4', '3', 8, '2025-03-20 02:50:30', '2025-03-20 02:50:48'),
(351, '1', '5', '3', 1, '2025-03-20 02:50:30', '2025-03-20 02:50:48'),
(352, '2', '5', '3', 1, '2025-03-20 02:50:30', '2025-03-20 02:50:48'),
(353, '3', '5', '3', 1, '2025-03-20 02:50:30', '2025-03-20 02:50:48'),
(361, '1', '6', '3', 1, '2025-03-20 02:50:30', '2025-03-20 02:50:48'),
(362, '2', '6', '3', 1, '2025-03-20 02:50:30', '2025-03-20 02:50:48');

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

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('55tDcIPjXg3AWJ7NneX5wJSNZRHqzyDl3WzccCid', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOHhsWE5uR1ZUYkxyVHUzV0NnMXg2TDRVNU1xUkNFWTV4ZmpHTWNueSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwLzEvcHJpbmNpcGFsZSI7fX0=', 1748127380),
('aTLm2HzPuJQmTjs1432AmD2ZuHWxqItcunpqzAAJ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 OPR/118.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRTNBVkJlejVpR0Q5bk9lREI4QW9HNXVDdVVWTXdMUERGVmhUMGh3dyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9saXN0LWluZmVybWVyaWUvMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1748032285),
('m6SzYqm26qjSUxeyMVyxz9tgqasYNP4n0qVfnWl9', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNWdwSHBnM2h0Y1FqOHluTEZhemR6a3JJM2hySkNEMEoxSEJsU1paVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwLzEvY29ucyI7fX0=', 1748086122),
('n6EHKtI0k35kbACcRPr3xRVn9Qno12MrI9NH0HQd', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVU1JVFg5Z0VJV3ZjN2lPQktqRGxBakMyTzcxYjdyWkI2dTVISXk3TyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYXRpZW50cz92YWxpZGF0aW9uPTEiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1748047252),
('t1rpfmjgEiqEXm6rqZXVNzGwybsOHBt4LTyOKuwz', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 OPR/118.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSFJSS1ZkOWo1NGtLT0V6ZDZpZE1Pc2RKazFvdEhYYW1kVmtQdlUyRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9saXN0LWluZmVybWVyaWUvMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1748045421);

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
(15, 2022012, 'sam', NULL, '2025-05-31 07:00:00', '2025-05-31 21:00:00', '2025-03-24 04:36:06', '2025-05-30 15:09:01'),
(16, 2022606, 'sam', NULL, '2025-03-29 09:00:00', '2025-03-29 23:00:00', '2025-03-24 04:41:13', '2025-03-24 04:41:13'),
(17, 2022606, 'sam', NULL, '2025-03-29 09:00:00', '2025-03-29 23:00:00', '2025-03-24 04:41:25', '2025-03-24 04:41:25'),
(18, 2022016, 'ven', NULL, '2025-03-28 09:00:00', '2025-03-28 23:00:00', '2025-03-24 04:46:44', '2025-03-24 04:46:44'),
(19, 2022013, 'ven', NULL, '2025-03-28 09:00:00', '2025-03-28 23:00:00', '2025-03-24 04:47:14', '2025-03-24 04:47:14'),
(24, 2022002, '48h', NULL, '2025-04-17 09:00:00', '2025-04-19 23:00:00', '2025-03-24 04:57:20', '2025-04-14 10:58:33'),
(25, 2022360, 'sam', NULL, '2025-03-29 09:00:00', '2025-03-29 23:00:00', '2025-03-24 04:58:40', '2025-03-24 04:58:56'),
(26, 2022019, 'sam', NULL, '2025-03-29 09:00:00', '2025-03-29 23:00:00', '2025-03-24 10:31:17', '2025-03-24 10:31:17'),
(27, 2022005, '48h', NULL, '2025-03-27 09:00:00', '2025-03-29 23:00:00', '2025-03-24 10:32:37', '2025-03-24 10:32:37'),
(28, 2022015, 'sam', NULL, '2025-03-29 09:00:00', '2025-03-29 23:00:00', '2025-03-24 13:21:04', '2025-03-24 13:21:04'),
(33, 2022011, '48h', NULL, '2025-04-10 09:00:00', '2025-04-12 23:00:00', '2025-04-07 03:31:17', '2025-04-07 03:32:20'),
(34, 2022011, 'ven', NULL, '2025-04-11 09:00:00', '2025-04-11 23:00:00', '2025-04-10 11:24:32', '2025-04-10 11:24:32'),
(35, 2022567, 'ven', NULL, '2025-04-18 09:00:00', '2025-04-18 23:00:00', '2025-04-14 06:04:39', '2025-04-14 06:04:45'),
(36, 2022812, 'ven', NULL, '2025-04-18 09:00:00', '2025-04-18 23:00:00', '2025-04-14 06:05:09', '2025-04-14 06:05:10'),
(37, 2022003, 'sam', NULL, '2025-04-26 09:00:00', '2025-04-26 23:00:00', '2025-04-14 06:19:30', '2025-04-21 02:53:31'),
(38, 2022013, 'sam', NULL, '2025-04-19 09:00:00', '2025-04-19 23:00:00', '2025-04-14 10:01:21', '2025-04-14 10:01:21'),
(39, 2022011, 'sam', NULL, '2025-04-19 09:00:00', '2025-04-19 23:00:00', '2025-04-14 10:55:11', '2025-04-14 10:55:11'),
(40, 2022002, 'ven', NULL, '2025-04-25 09:00:00', '2025-04-25 23:00:00', '2025-04-15 11:01:54', '2025-04-21 02:53:32'),
(41, 2022005, 'sam', NULL, '2025-04-26 09:00:00', '2025-04-26 23:00:00', '2025-04-16 09:45:34', '2025-04-21 02:54:36'),
(42, 2022011, 'ven', NULL, '2025-04-25 09:00:00', '2025-04-25 23:00:00', '2025-04-21 02:53:18', '2025-04-21 02:53:18'),
(43, 2022004, 'sam', NULL, '2025-04-26 09:00:00', '2025-04-26 23:00:00', '2025-04-21 02:54:19', '2025-04-21 02:54:19'),
(44, 2022006, 'ven', NULL, '2025-04-25 09:00:00', '2025-04-25 23:00:00', '2025-04-21 02:54:49', '2025-04-21 02:54:50'),
(45, 2022002, 'sam', NULL, '2025-04-26 09:00:00', '2025-04-26 23:00:00', '2025-04-21 02:55:17', '2025-04-21 02:55:17'),
(46, 2022002, 'ven', NULL, '2025-04-25 09:00:00', '2025-04-25 23:00:00', '2025-04-21 22:38:31', '2025-04-21 22:38:31'),
(47, 2022513, 'ven', NULL, '2025-04-25 09:00:00', '2025-04-25 23:00:00', '2025-04-21 23:47:06', '2025-04-21 23:47:08'),
(48, 2022011, 'ven', NULL, '2025-05-30 09:00:00', '2025-05-30 23:00:00', '2025-05-23 00:23:41', '2025-05-23 00:23:41'),
(49, 2022014, 'sam', NULL, '2025-05-31 07:00:00', '2025-05-31 21:00:00', '2025-05-23 00:23:48', '2025-05-30 15:08:59'),
(50, 2022013, 'sam', NULL, '2025-05-31 07:00:00', '2025-05-31 21:00:00', '2025-05-23 14:30:46', '2025-05-30 15:09:00'),
(51, 2022468, 'sam', NULL, '2025-05-31 07:00:00', '2025-05-31 21:00:00', '2025-05-30 13:59:14', '2025-05-30 13:59:14'),
(52, 2022017, 'ven', NULL, '2025-06-06 07:00:00', '2025-06-06 21:00:00', '2025-05-30 15:58:28', '2025-05-30 15:58:28'),
(53, 2022525, 'ven', NULL, '2025-06-06 07:00:00', '2025-06-06 21:00:00', '2025-05-30 15:58:35', '2025-05-30 15:58:35'),
(54, 2022004, 'ven', NULL, '2025-06-06 07:00:00', '2025-06-06 21:00:00', '2025-05-30 15:58:40', '2025-05-30 15:58:40'),
(55, 2022003, 'sam', NULL, '2025-07-19 07:00:00', '2025-07-19 21:00:00', '2025-07-17 20:54:19', '2025-07-17 20:54:19'),
(56, 2022527, 'ven', NULL, '2025-07-18 07:00:00', '2025-07-18 21:00:00', '2025-07-17 20:54:52', '2025-07-17 20:54:52');

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

CREATE TABLE `students` (
  `matricule` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `grade` enum('1','2','3') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `consigned` tinyint(1) DEFAULT NULL,
  `choix` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `students`
--

INSERT INTO `students` (`matricule`, `nom`, `grade`, `created_at`, `updated_at`, `deleted_at`, `section_id`, `consigned`, `choix`, `prenom`) VALUES
(2022002, 'Faris Ibrahim', '3', '2025-02-26 02:52:01', '2025-04-21 22:38:31', NULL, 361, 0, NULL, ''),
(2022003, 'Youssef Darwish', '3', '2025-02-26 02:52:01', '2025-07-17 20:54:19', NULL, 361, 0, 'sam', ''),
(2022004, 'Walid Yazbek', '3', '2025-02-26 02:52:01', '2025-05-30 15:58:40', NULL, 361, 0, 'ven', ''),
(2022005, 'Youssef Qasem', '3', '2025-02-26 02:52:01', '2025-04-21 02:54:36', NULL, 361, 0, NULL, ''),
(2022006, 'Ali Darwish', '3', '2025-02-26 02:52:01', '2025-04-21 02:54:50', NULL, 361, 0, NULL, ''),
(2022007, 'Youssef Ibrahim', '3', '2025-02-26 02:52:01', '2025-04-19 15:46:11', NULL, 361, 0, NULL, ''),
(2022008, 'Youssef Zahran', '3', '2025-02-26 02:52:01', '2025-04-19 15:46:11', NULL, 361, 0, NULL, ''),
(2022009, 'Faris Darwish', '3', '2025-02-26 02:52:01', '2025-04-19 15:46:11', NULL, 361, 0, NULL, ''),
(2022010, 'Omar Nasser', '3', '2025-02-26 02:52:01', '2025-04-19 15:46:11', NULL, 361, 0, NULL, ''),
(2022011, 'Ahmed Al-Masri', '3', '2025-02-26 02:52:01', '2025-05-23 00:23:41', NULL, 351, 1, 'ven', ''),
(2022012, 'Ziad Nasser', '3', '2025-02-26 02:52:01', '2025-05-30 15:09:01', NULL, 351, 0, 'sam', ''),
(2022013, 'Ahmed Ibrahim', '3', '2025-02-26 02:52:01', '2025-05-30 15:09:00', NULL, 351, 0, 'sam', ''),
(2022014, 'Hassan Bakr', '3', '2025-02-26 02:52:01', '2025-05-30 15:08:59', NULL, 351, 0, 'sam', ''),
(2022015, 'Khaled Yazbek', '3', '2025-02-26 02:52:01', '2025-04-19 15:46:11', NULL, 351, 0, NULL, ''),
(2022016, 'Amr Yazbek', '3', '2025-02-26 02:52:01', '2025-04-19 15:46:11', NULL, 351, 0, NULL, ''),
(2022017, 'Amr Bakr', '3', '2025-02-26 02:52:01', '2025-05-30 15:58:28', NULL, 351, 0, 'ven', ''),
(2022018, 'Faris Ibrahim', '3', '2025-02-26 02:52:01', '2025-04-19 15:46:11', NULL, 351, 0, NULL, ''),
(2022019, 'Amr Ibrahim', '3', '2025-02-26 02:52:01', '2025-04-19 15:46:11', NULL, 351, 0, NULL, ''),
(2022020, 'Walid Ibrahim', '3', '2025-02-26 02:52:01', '2025-04-19 15:46:11', NULL, 351, 0, NULL, ''),
(2022041, 'Ali Al-Masri', '3', '2025-03-19 18:25:07', '2025-04-19 15:46:11', NULL, 361, 1, NULL, ''),
(2022054, 'Walid Ibrahim', '3', '2025-03-19 18:25:06', '2025-04-19 15:46:11', NULL, 361, 1, NULL, ''),
(2022055, 'Walid Zahran', '1', '2025-03-19 18:24:21', '2025-04-19 15:46:11', NULL, 342, 0, '48h', ''),
(2022056, 'Hassan Haddad', '1', '2025-03-19 18:24:57', '2025-04-19 15:46:11', NULL, 351, 1, '48h', ''),
(2022064, 'Youssef Qasem', '1', '2025-03-19 18:24:48', '2025-04-19 15:46:11', NULL, 352, 1, 'sam', ''),
(2022077, 'Amr Yazbek', '1', '2025-03-19 18:24:59', '2025-04-19 15:46:11', NULL, 351, 1, '48h', ''),
(2022078, 'Omar Qasem', '3', '2025-03-19 18:24:54', '2025-04-19 15:46:11', NULL, 362, 1, NULL, ''),
(2022096, 'Ziad Nasser', '2', '2025-03-19 18:24:51', '2025-04-19 15:46:11', NULL, 362, 1, 'ven', ''),
(2022099, 'Ziad Darwish', '3', '2025-03-19 18:24:53', '2025-04-19 15:46:11', NULL, 362, 1, NULL, ''),
(2022104, 'Youssef Yazbek', '1', '2025-03-19 18:24:21', '2025-04-19 15:46:11', NULL, 342, 1, 'sam', ''),
(2022120, 'Ziad Darwish', '2', '2025-03-19 18:24:44', '2025-04-19 15:46:11', NULL, 352, 1, 'sam', ''),
(2022131, 'Faris Qasem', '2', '2025-03-19 18:24:46', '2025-04-19 15:46:11', NULL, 352, 0, 'sam', ''),
(2022168, 'Amr Al-Farouq', '1', '2025-03-19 18:24:21', '2025-04-19 15:46:11', NULL, 342, 0, 'ven', ''),
(2022169, 'Khaled Zahran', '2', '2025-03-19 18:24:39', '2025-04-19 15:46:11', NULL, 341, 1, 'ven', ''),
(2022185, 'Ahmed Bakr', '1', '2025-03-19 18:24:27', '2025-04-19 15:46:11', NULL, 353, 0, 'sam', ''),
(2022194, 'Ziad Al-Farouq', '1', '2025-03-19 18:24:20', '2025-04-19 15:46:11', NULL, 342, 0, 'sam', ''),
(2022220, 'Omar Al-Farouq', '2', '2025-03-19 18:24:58', '2025-04-19 15:46:11', NULL, 351, 1, '48h', ''),
(2022259, 'Ziad Bakr', '3', '2025-03-19 18:24:45', '2025-04-19 15:46:11', NULL, 352, 1, NULL, ''),
(2022276, 'Youssef Bakr', '3', '2025-03-19 18:25:03', '2025-04-19 15:46:11', NULL, 361, 0, NULL, ''),
(2022285, 'Amr Al-Masri', '2', '2025-03-19 18:24:32', '2025-04-19 15:46:11', NULL, 343, 1, 'sam', ''),
(2022321, 'Hassan Al-Masri', '2', '2025-03-19 18:24:24', '2025-04-19 15:46:11', NULL, 342, 0, 'sam', ''),
(2022322, 'Ali Al-Masri', '3', '2025-03-19 18:24:34', '2025-04-19 15:46:11', NULL, 343, 1, NULL, ''),
(2022332, 'Omar Ibrahim', '3', '2025-03-19 18:24:29', '2025-04-19 15:46:11', NULL, 353, 0, NULL, ''),
(2022343, 'Walid Haddad', '1', '2025-03-19 18:24:26', '2025-04-19 15:46:11', NULL, 353, 0, '48h', ''),
(2022358, 'Ali Haddad', '2', '2025-03-19 18:24:41', '2025-04-19 15:46:11', NULL, 341, 1, 'ven', ''),
(2022359, 'Walid Bakr', '1', '2025-03-19 18:24:55', '2025-04-19 15:46:12', NULL, 362, 0, 'ven', ''),
(2022360, 'Ziad Darwish', '2', '2025-03-19 18:24:48', '2025-04-19 15:46:12', NULL, 352, 0, 'sam', ''),
(2022364, 'Ali Haddad', '2', '2025-03-19 18:24:47', '2025-04-19 15:46:12', NULL, 352, 1, 'ven', ''),
(2022375, 'Omar Yazbek', '1', '2025-03-19 18:24:38', '2025-04-19 15:46:12', NULL, 341, 1, '48h', ''),
(2022387, 'Amr Bakr', '2', '2025-03-19 18:24:29', '2025-04-19 15:46:12', NULL, 353, 1, 'sam', ''),
(2022397, 'Ziad Qasem', '2', '2025-03-19 18:25:02', '2025-04-19 15:46:12', NULL, 351, 1, 'sam', ''),
(2022414, 'Faris Zahran', '1', '2025-03-19 18:24:30', '2025-04-19 15:46:12', NULL, 353, 1, 'ven', ''),
(2022424, 'Amr Yazbek', '2', '2025-03-19 18:24:23', '2025-04-19 15:46:12', NULL, 342, 1, 'ven', ''),
(2022427, 'Walid Nasser', '2', '2025-03-19 18:25:08', '2025-04-19 15:46:12', NULL, 361, 1, 'ven', ''),
(2022440, 'Ziad Ibrahim', '2', '2025-03-19 18:24:31', '2025-04-19 15:46:12', NULL, 343, 0, 'sam', ''),
(2022456, 'Ziad Yazbek', '2', '2025-03-19 18:24:26', '2025-04-19 15:46:12', NULL, 353, 1, 'ven', ''),
(2022460, 'Faris Yazbek', '1', '2025-03-19 18:24:46', '2025-04-19 15:46:12', NULL, 352, 1, '48h', ''),
(2022468, 'Amr Darwish', '3', '2025-03-19 18:24:42', '2025-05-30 13:59:14', NULL, 341, 0, 'sam', ''),
(2022471, 'Youssef Bakr', '3', '2025-03-19 18:24:37', '2025-04-19 15:46:12', NULL, 343, 1, NULL, ''),
(2022478, 'Ziad Al-Masri', '2', '2025-03-19 18:25:00', '2025-04-19 15:46:12', NULL, 351, 1, 'sam', ''),
(2022479, 'Ahmed Darwish', '2', '2025-03-19 18:24:55', '2025-04-19 15:46:12', NULL, 362, 1, 'ven', ''),
(2022497, 'Omar Al-Farouq', '1', '2025-03-19 18:24:50', '2025-04-19 15:46:12', NULL, 362, 0, 'ven', ''),
(2022513, 'Ahmed Al-Farouq', '3', '2025-03-19 18:24:36', '2025-04-21 23:47:08', NULL, 343, 0, NULL, ''),
(2022519, 'Khaled Qasem', '3', '2025-03-19 18:25:01', '2025-04-19 15:46:12', NULL, 351, 1, NULL, ''),
(2022525, 'Amr Haddad', '2', '2025-03-19 18:24:58', '2025-05-30 15:58:35', NULL, 351, 0, 'ven', ''),
(2022527, 'Ziad Al-Masri', '3', '2025-03-19 18:25:05', '2025-07-17 20:54:52', NULL, 361, 0, 'ven', ''),
(2022545, 'Ahmed Qasem', '3', '2025-03-19 18:24:43', '2025-04-19 15:46:12', NULL, 341, 1, NULL, ''),
(2022549, 'Khaled Nasser', '2', '2025-03-19 18:24:41', '2025-04-19 15:46:12', NULL, 341, 0, 'sam', ''),
(2022554, 'Ahmed Yazbek', '1', '2025-03-19 18:24:45', '2025-04-19 15:46:12', NULL, 352, 1, '48h', ''),
(2022567, 'Ali Nasser', '3', '2025-03-19 18:24:20', '2025-04-19 15:46:12', NULL, 342, 0, NULL, ''),
(2022568, 'Faris Haddad', '1', '2025-03-19 18:25:04', '2025-04-19 15:46:12', NULL, 361, 1, 'ven', ''),
(2022580, 'Walid Al-Farouq', '3', '2025-03-19 18:24:43', '2025-04-19 15:46:12', NULL, 341, 1, NULL, ''),
(2022598, 'Walid Darwish', '1', '2025-03-19 18:24:53', '2025-04-19 15:46:12', NULL, 362, 0, '48h', ''),
(2022604, 'Amr Al-Masri', '3', '2025-03-19 18:24:34', '2025-04-19 15:46:12', NULL, 343, 1, NULL, ''),
(2022606, 'Omar Darwish', '1', '2025-03-19 18:24:57', '2025-04-19 15:46:12', NULL, 351, 0, 'sam', ''),
(2022609, 'Hassan Al-Masri', '2', '2025-03-19 18:24:49', '2025-04-19 15:46:12', NULL, 352, 1, 'ven', ''),
(2022652, 'Ahmed Ibrahim', '2', '2025-03-19 18:25:00', '2025-04-19 15:46:12', NULL, 351, 1, 'ven', ''),
(2022681, 'Faris Qasem', '2', '2025-03-19 18:24:23', '2025-04-19 15:46:12', NULL, 342, 0, '48h', ''),
(2022686, 'Hassan Nasser', '2', '2025-03-19 18:24:33', '2025-04-19 15:46:12', NULL, 343, 0, '48h', ''),
(2022701, 'Hassan Qasem', '2', '2025-03-19 18:25:02', '2025-04-19 15:46:12', NULL, 351, 0, 'sam', ''),
(2022705, 'Khaled Al-Farouq', '2', '2025-03-19 18:24:39', '2025-04-19 15:46:12', NULL, 341, 1, 'ven', ''),
(2022719, 'Faris Yazbek', '3', '2025-03-19 18:25:05', '2025-04-19 15:46:12', NULL, 361, 1, NULL, ''),
(2022726, 'Walid Ibrahim', '2', '2025-03-19 18:24:28', '2025-04-19 15:46:12', NULL, 353, 0, 'ven', ''),
(2022730, 'Omar Al-Farouq', '2', '2025-03-19 18:24:52', '2025-04-19 15:46:12', NULL, 362, 0, 'ven', ''),
(2022733, 'Youssef Darwish', '3', '2025-03-19 18:24:38', '2025-04-19 15:46:12', NULL, 341, 0, NULL, ''),
(2022740, 'Faris Darwish', '2', '2025-03-19 18:25:06', '2025-04-19 15:46:12', NULL, 361, 1, 'sam', ''),
(2022794, 'Walid Bakr', '1', '2025-03-19 18:24:22', '2025-04-19 15:46:12', NULL, 342, 0, 'ven', ''),
(2022800, 'Walid Yazbek', '2', '2025-03-19 18:24:32', '2025-04-19 15:46:12', NULL, 343, 1, '48h', ''),
(2022812, 'Amr Al-Farouq', '3', '2025-03-19 18:25:07', '2025-04-19 15:46:12', NULL, 361, 0, NULL, ''),
(2022813, 'Ahmed Zahran', '2', '2025-03-19 18:24:35', '2025-04-19 15:46:12', NULL, 343, 1, 'ven', ''),
(2022819, 'Omar Ibrahim', '1', '2025-03-19 18:24:30', '2025-04-19 15:46:12', NULL, 353, 0, 'sam', ''),
(2022830, 'Youssef Al-Farouq', '3', '2025-03-19 18:24:51', '2025-04-19 15:46:12', NULL, 362, 1, NULL, ''),
(2022859, 'Hassan Al-Masri', '2', '2025-03-19 18:24:24', '2025-04-19 15:46:12', NULL, 342, 0, '48h', ''),
(2022883, 'Hassan Bakr', '2', '2025-03-19 18:24:36', '2025-04-19 15:46:12', NULL, 343, 1, 'ven', ''),
(2022886, 'Youssef Zahran', '1', '2025-03-19 18:25:04', '2025-04-19 15:46:12', NULL, 361, 0, 'sam', ''),
(2022899, 'Youssef Ibrahim', '2', '2025-03-19 18:24:56', '2025-04-19 15:46:12', NULL, 362, 1, 'sam', ''),
(2022956, 'Faris Zahran', '2', '2025-03-19 18:24:40', '2025-04-19 15:46:12', NULL, 341, 1, 'sam', ''),
(2022958, 'Youssef Zahran', '2', '2025-03-19 18:24:50', '2025-04-19 15:46:12', NULL, 352, 0, 'sam', ''),
(2022962, 'Faris Nasser', '2', '2025-03-19 18:24:27', '2025-04-19 15:46:12', NULL, 353, 0, 'sam', '');

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
(0, 'DG', '$2y$12$tZAs9A2TY23N/1KLsNvs7eqajIA1v81kT646CCvLCXZ7zhtpd/qg2', NULL, '2025-05-21 17:31:30', '2025-05-21 17:31:30', 770004546, 6, '0'),
(1, 'bou', '$2y$12$swRt6UaMIFOVAU.F8VyYp.ujtBFkrjEI7HixyP6bmIFkFMsBQ4.Oi', 'dsrMaZgVPTlbsB4Iwy578rSXOWB0Z4NxPmlm2AlmU4iAmEqK57J7ezWW7J8h', '2025-05-21 17:31:30', '2025-05-21 17:31:30', 770004546, 1, '3'),
(3, 'da9', '$2y$12$tZAs9A2TY23N/1KLsNvs7eqajIA1v81kT646CCvLCXZ7zhtpd/qg2', NULL, '2025-05-21 17:31:30', '2025-05-21 17:31:30', 770004546, 2, '3'),
(4, 'bouma3', '$2y$12$tZAs9A2TY23N/1KLsNvs7eqajIA1v81kT646CCvLCXZ7zhtpd/qg2', NULL, '2025-05-21 17:31:30', '2025-05-21 17:31:30', 770004546, 3, '0'),
(5, 'MED', '$2y$12$tZAs9A2TY23N/1KLsNvs7eqajIA1v81kT646CCvLCXZ7zhtpd/qg2', NULL, '2025-05-21 17:31:30', '2025-05-21 17:31:30', 770004546, 5, '0'),
(6, 'lab', '$2y$12$tZAs9A2TY23N/1KLsNvs7eqajIA1v81kT646CCvLCXZ7zhtpd/qg2', NULL, '2025-05-21 17:31:30', '2025-05-21 17:31:30', 770004546, 4, '0'),
(8, 'ham', '$2y$12$tZAs9A2TY23N/1KLsNvs7eqajIA1v81kT646CCvLCXZ7zhtpd/qg2', NULL, '2025-05-21 17:31:30', '2025-05-21 17:31:30', 770004546, 1, '2');

--
-- Index pour les tables déchargées
--

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
  ADD KEY `consigns_student_id_foreign` (`student_id`);

--
-- Index pour la table `convoncus`
--
ALTER TABLE `convoncus`
  ADD KEY `matricule` (`matricule`);

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
  ADD KEY `officer_id` (`officer_id`),
  ADD KEY `destination` (`destination`);

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
  ADD KEY `sorties_student_id_foreign` (`student_id`),
  ADD KEY `sorties_choix_id_foreign` (`choix`);

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
-- AUTO_INCREMENT pour la table `consignes`
--
ALTER TABLE `consignes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `expulsions`
--
ALTER TABLE `expulsions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=559;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `rhps`
--
ALTER TABLE `rhps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `sanctions`
--
ALTER TABLE `sanctions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT pour la table `sorties`
--
ALTER TABLE `sorties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `students`
--
ALTER TABLE `students`
  MODIFY `matricule` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2023043;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `consignes`
--
ALTER TABLE `consignes`
  ADD CONSTRAINT `consigns_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`matricule`);

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
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`destination`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reports_ibfk_3` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
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
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `sorties`
--
ALTER TABLE `sorties`
  ADD CONSTRAINT `sorties_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`matricule`);

--
-- Contraintes pour la table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
