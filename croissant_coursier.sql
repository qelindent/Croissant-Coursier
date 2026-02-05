-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 02, 2025 at 12:52 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `croissant_coursier`
--

-- --------------------------------------------------------

--
-- Table structure for table `boulangeries`
--

CREATE TABLE `boulangeries` (
  `id` int NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `image_url` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `boulangeries`
--

INSERT INTO `boulangeries` (`id`, `nom`, `image_url`) VALUES
(1, 'Au Flocon d\'Avoine', 'vitrine1.jpg'),
(2, 'Boulangerie \'O', 'vitrine2.jpg'),
(3, 'Le Grain Bio', 'vitrine3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `boulangeries_details`
--

CREATE TABLE `boulangeries_details` (
  `id` int NOT NULL,
  `boulangerie_id` int NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `horaires` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `boulangeries_details`
--

INSERT INTO `boulangeries_details` (`id`, `boulangerie_id`, `adresse`, `horaires`) VALUES
(1, 1, '14 Rue Basse, 68130 Carsparch', 'Lun-Sam : 7h-19h'),
(2, 2, '6 Grand Rue, 68700 Aspach-Michelbach', 'Mar-Dim : 8h-20h'),
(3, 3, '4 rue de Belfort, 68130 Tagsdorf', 'Lun-Ven : 6h30-18h30');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int NOT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favoris_boulangeries`
--

CREATE TABLE `favoris_boulangeries` (
  `id` int NOT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `bakery_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `favoris_boulangeries`
--

INSERT INTO `favoris_boulangeries` (`id`, `user_email`, `bakery_name`) VALUES
(27, 'auflocondavoine@admin.fr', 'Au Flocon d\'Avoine'),
(23, 'user@hotmail.fr', 'Au Flocon d\'Avoine'),
(24, 'user@hotmail.fr', 'Boulangerie \'O');

-- --------------------------------------------------------

--
-- Table structure for table `favoris_produits`
--

CREATE TABLE `favoris_produits` (
  `user_email` varchar(255) NOT NULL,
  `product_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favoris_recettes`
--

CREATE TABLE `favoris_recettes` (
  `id` int NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `recette_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `favoris_recettes`
--

INSERT INTO `favoris_recettes` (`id`, `user_email`, `recette_id`) VALUES
(22, 'user@hotmail.fr', 0),
(29, 'user@hotmail.fr', 6);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `items` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `carte` varchar(20) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `rue` varchar(255) DEFAULT NULL,
  `expiration` varchar(10) DEFAULT NULL,
  `ccv` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `email`, `items`, `created_at`, `nom`, `prenom`, `carte`, `pays`, `region`, `ville`, `rue`, `expiration`, `ccv`) VALUES
(30, 'user@hotmail.fr', '2,2,2,2,2,6,4', '2025-05-22 09:41:49', 'Paul', 'Jean', '**** **** **** 2/26', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(31, 'user@hotmail.fr', '1,2,2,2,3,3,3,5,4', '2025-05-22 11:07:14', 'Paul', 'Jean', '**** **** **** 2/26', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(32, 'user@hotmail.fr', '2,2', '2025-05-26 08:53:14', 'Paul', 'Jean', '**** **** **** 2/26', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(33, 'user@hotmail.fr', '2,2', '2025-05-26 09:00:02', 'hello', 'Florian', '4564678676987967', 'France', 'Bas-Rhin', 'Mulhouse', '98 rue des inconnus', '02/25', '284'),
(34, 'user@hotmail.fr', '2', '2025-05-26 09:00:19', 'Paul', 'Jean', '**** **** ****', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(35, 'user@hotmail.fr', '2,2,3,3,3,3', '2025-05-26 09:06:18', 'Paul', 'Jean', '**** **** ****', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(36, 'user@hotmail.fr', '1,1,1,1,1,1,5', '2025-05-26 14:06:19', 'Paul', 'Jean', '**** **** ****', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(37, 'user@hotmail.fr', '2,3,3,3,3', '2025-05-26 14:23:42', 'Paul', 'Jean', '**** **** ****', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(38, 'user@hotmail.fr', '2', '2025-05-27 08:11:30', 'Paul', 'Jean', '**** **** ****', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(39, 'user@hotmail.fr', '5', '2025-05-27 08:43:49', 'Paul', 'Jean', '**** **** ****', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(40, 'user@hotmail.fr', '2', '2025-05-28 06:39:34', 'Paul', 'Jean', '**** **** **** ****', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(41, 'user@hotmail.fr', '2,2,2', '2025-05-28 06:42:06', 'Paul', 'Jean', '**** **** **** ****', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(42, 'user@hotmail.fr', '2', '2025-05-28 06:49:31', 'Paul', 'Jean', '**** **** **** ****', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière', '02/25', '889'),
(44, 'auflocondavoine@admin.fr', '2', '2025-06-02 07:13:21', 'Boulangerie', 'Au Flocon D\'avoine', '**** **** **** 7967', 'France', 'Haut-Rhin', 'Alkirch', 'rue de la Mayonnaise', '02/25', '112'),
(45, 'auflocondavoine@admin.fr', '4,3,3,3,1,1,1,1,1', '2025-06-02 12:51:23', 'Boulangerie', 'Au Flocon D\'avoine', '**** **** **** 7967', 'France', 'Haut-Rhin', 'Alkirch', 'rue de la Mayonnaise', '02/25', '112');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `boulangerie` varchar(255) NOT NULL,
  `image_url` text,
  `poids` varchar(50) DEFAULT NULL,
  `description` text,
  `allergenes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `boulangerie`, `image_url`, `poids`, `description`, `allergenes`) VALUES
(1, 'Pain aux graines', '2.50', 'Au Flocon d\'Avoine', 'recette1.jpg', '500g', 'Pain complet aux graines de lin, tournesol et pavot, riche en fibres.', 'gluten, graines'),
(2, 'Pain de seigle', '3.20', 'Au Flocon d\'Avoine', 'produit2.jpg', '400g', 'Pain rustique au goût prononcé de seigle.', 'gluten'),
(3, 'Pain semoule', '1.80', 'Boulangerie \'O', 'produit3.jpg', '300g', 'Pain à la semoule de blé dur, léger et doré.', 'gluten'),
(4, 'Brioche orientale', '3.50', 'Boulangerie \'O', 'recette4.jpg', '350g', 'Brioche moelleuse parfumée à la fleur d’oranger.', 'gluten, œuf, lait'),
(5, 'Éclair au chocolat', '2.20', 'Le Grain Bio', 'produit5.jpg', '150g', 'Éclair au chocolat bio, pâte à choux légère, garniture fondante.', 'gluten, œuf, lait'),
(6, 'Brioche bio', '3.80', 'Le Grain Bio', 'produit6.jpg', '350g', 'Brioche artisanale 100% bio, légère et parfumée.', 'gluten, œuf, lait');

-- --------------------------------------------------------

--
-- Table structure for table `recettes`
--

CREATE TABLE `recettes` (
  `id` int NOT NULL,
  `boulangeries_id` int DEFAULT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `recettes`
--

INSERT INTO `recettes` (`id`, `boulangeries_id`, `titre`, `description`, `image_url`, `date_creation`) VALUES
(1, 11, 'Pain aux Graines Maison', 'Un pain croustillant, parsemé de graines (tournesol, lin, pavot), parfait pour accompagner vos tartines du matin ou vos repas légers.\r\n\r\n⚠️ Allergènes\r\nGluten, graines (pouvant contenir des traces de sésame).\r\n\r\n📝 Ingrédients\r\n\r\n    400g de farine T65\r\n\r\n    100g de farine complète\r\n\r\n    10g de sel\r\n\r\n    7g de levure sèche\r\n\r\n    300ml d’eau tiède\r\n\r\n    50g de graines variées (tournesol, lin, pavot)\r\n\r\n👨‍🍳 Préparation\r\n1️⃣ Mélanger les ingrédients secs\r\n\r\n    Mélanger les farines, le sel et les graines dans un saladier.\r\n    2️⃣ Dissoudre la levure\r\n\r\n    Dans un bol, dissoudre la levure dans un peu d’eau tiède.\r\n    3️⃣ Incorporer l’eau et la levure\r\n\r\n    Verser l’eau et la levure dans le saladier et mélanger jusqu’à obtenir une pâte homogène.\r\n    4️⃣ Pétrir la pâte\r\n\r\n    Pétrir pendant 10 minutes jusqu’à ce qu’elle soit souple et élastique.\r\n    5️⃣ Première levée\r\n\r\n    Couvrir et laisser lever pendant 1h30.\r\n    6️⃣ Façonner le pain\r\n\r\n    Former une boule ou un bâtard et déposer sur une plaque farinée.\r\n    7️⃣ Deuxième levée\r\n\r\n    Laisser lever 45 minutes.\r\n    8️⃣ Cuisson\r\n\r\n    Préchauffer le four à 220°C. Enfourner pour 30 à 35 minutes.\r\n\r\n', 'recette1.jpg', '2025-06-02 10:36:25'),
(2, 11, 'Soupe paysanne', 'Une soupe rustique et généreuse, avec des légumes frais et des herbes parfumées. Parfaite pour réchauffer les soirées fraîches, elle s’accompagne idéalement d’un pain de campagne.\r\n\r\n⚠️ Allergènes\r\nPeut contenir des traces de céleri et gluten (selon accompagnement).\r\n\r\n📝 Ingrédients\r\n\r\n    2 carottes\r\n\r\n    2 pommes de terre\r\n\r\n    1 oignon\r\n\r\n    1 poireau\r\n\r\n    1 branche de céleri\r\n\r\n    1 litre de bouillon de légumes\r\n\r\n    2 c. à soupe d’huile d’olive\r\n\r\n    Sel, poivre, herbes de Provence\r\n\r\n👨‍🍳 Préparation\r\n1️⃣ Préparer les légumes\r\n\r\n    Éplucher les carottes, pommes de terre et l’oignon. Les couper en dés.\r\n    2️⃣ Faire revenir l’oignon\r\n\r\n    Dans une grande marmite, faire chauffer l’huile d’olive et faire suer l’oignon émincé.\r\n    3️⃣ Ajouter les légumes\r\n\r\n    Incorporer les carottes, pommes de terre, poireau et céleri. Faire revenir 5 minutes.\r\n    4️⃣ Ajouter le bouillon\r\n\r\n    Verser le bouillon chaud et ajouter les herbes de Provence. Saler et poivrer.\r\n    5️⃣ Cuire la soupe\r\n\r\n    Laisser mijoter à feu moyen 30 minutes.\r\n    6️⃣ Mixer (optionnel)\r\n\r\n    Mixer légèrement pour une texture plus onctueuse, ou laisser en morceaux.\r\n    7️⃣ Servir\r\n\r\n    Servir bien chaud, accompagné de pain de campagne.', 'recette2.jpg', '2025-06-02 10:36:25'),
(3, 13, 'Tajine poulet et semoule', 'Un tajine savoureux et parfumé aux épices orientales, accompagné de semoule légère et moelleuse. Un plat complet et convivial.\r\n\r\n⚠️ Allergènes\r\nGluten (semoule), fruits à coque (selon garniture optionnelle).\r\n\r\n📝 Ingrédients\r\n\r\n    4 cuisses de poulet\r\n\r\n    2 oignons\r\n\r\n    2 carottes\r\n\r\n    1 courgette\r\n\r\n    2 c. à soupe d’huile d’olive\r\n\r\n    1 c. à café de cannelle\r\n\r\n    1 c. à café de cumin\r\n\r\n    1 c. à café de curcuma\r\n\r\n    Sel, poivre\r\n\r\n    100g de pois chiches cuits\r\n\r\n    150g de semoule moyenne\r\n\r\n    250ml de bouillon de volaille\r\n\r\n👨‍🍳 Préparation\r\n1️⃣ Faire revenir le poulet\r\n\r\n    Dans un tajine ou une cocotte, faire chauffer l’huile d’olive et dorer les cuisses de poulet sur toutes les faces.\r\n    2️⃣ Ajouter les légumes\r\n\r\n    Émincer les oignons, couper les carottes et la courgette en morceaux, les ajouter au tajine.\r\n    3️⃣ Assaisonner\r\n\r\n    Ajouter la cannelle, le cumin, le curcuma, sel et poivre. Bien mélanger.\r\n    4️⃣ Ajouter les pois chiches et le bouillon\r\n\r\n    Incorporer les pois chiches et verser le bouillon chaud. Couvrir et laisser mijoter 45 minutes.\r\n    5️⃣ Préparer la semoule\r\n\r\n    Verser la semoule dans un saladier, ajouter une pincée de sel et un filet d’huile d’olive.\r\n    6️⃣ Hydrater la semoule\r\n\r\n    Verser 250ml d’eau bouillante, couvrir et laisser gonfler 5 minutes. Égrainer à la fourchette.\r\n    7️⃣ Servir\r\n\r\n    Dresser la semoule dans une assiette et disposer le tajine par-dessus.', 'recette3.jpg', '2025-06-02 10:36:25'),
(4, 13, 'Brioche Orientale Maison', 'Une brioche moelleuse délicatement parfumée à la fleur d’oranger et parsemée de pistaches concassées, idéale pour accompagner un thé ou un café.\r\n\r\n⚠️ Allergènes\r\nGluten, œufs, lait, fruits à coque (pistaches).\r\n\r\n📝 Ingrédients\r\n\r\n    400g de farine T55\r\n\r\n    3 œufs\r\n\r\n    80g de sucre\r\n\r\n    100g de beurre mou\r\n\r\n    100ml de lait tiède\r\n\r\n    1 sachet de levure boulangère\r\n\r\n    1 c. à soupe de fleur d’oranger\r\n\r\n    50g de pistaches concassées\r\n\r\n👨‍🍳 Préparation\r\n1️⃣ Préparer la pâte\r\n\r\n    Mélanger la farine, la levure et le sucre dans un saladier.\r\n    2️⃣ Ajouter les œufs et le lait\r\n\r\n    Ajouter les œufs et le lait tiède. Bien mélanger.\r\n    3️⃣ Incorporer le beurre et la fleur d’oranger\r\n\r\n    Ajouter le beurre en petits morceaux et la fleur d’oranger.\r\n    4️⃣ Pétrir\r\n\r\n    Pétrir 10 minutes jusqu’à obtention d’une pâte lisse.\r\n    5️⃣ Première levée\r\n\r\n    Couvrir et laisser lever 1h30 dans un endroit chaud.\r\n    6️⃣ Façonner et garnir\r\n\r\n    Former une tresse ou des boules et parsemer de pistaches concassées.\r\n    7️⃣ Deuxième levée\r\n\r\n    Laisser lever encore 45 minutes.\r\n    8️⃣ Cuisson\r\n\r\n    Préchauffer le four à 180°C. Enfourner 25 à 30 minutes.', 'recette4.jpg', '2025-06-02 10:36:25'),
(5, 14, 'Dessert chocolat gourmand', 'Un dessert intense et crémeux au chocolat noir, relevé de notes de vanille et de noisettes grillées pour encore plus de gourmandise.\r\n\r\n⚠️ Allergènes\r\nLait, œufs, fruits à coque (noisettes).\r\n\r\n📝 Ingrédients\r\n\r\n    200g de chocolat noir\r\n\r\n    100ml de crème liquide\r\n\r\n    50g de beurre\r\n\r\n    2 œufs\r\n\r\n    50g de sucre\r\n\r\n    50g de noisettes concassées\r\n\r\n    1 c. à café d’extrait de vanille\r\n\r\n👨‍🍳 Préparation\r\n1️⃣ Faire fondre le chocolat\r\n\r\n    Dans une casserole, faire fondre le chocolat et le beurre à feu doux.\r\n    2️⃣ Ajouter la crème\r\n\r\n    Hors du feu, ajouter la crème liquide et bien mélanger.\r\n    3️⃣ Incorporer les œufs et le sucre\r\n\r\n    Battre les œufs avec le sucre et la vanille, puis incorporer au chocolat fondu.\r\n    4️⃣ Ajouter les noisettes\r\n\r\n    Ajouter les noisettes concassées et mélanger délicatement.\r\n    5️⃣ Verser dans des ramequins\r\n\r\n    Répartir la préparation dans des ramequins beurrés.\r\n    6️⃣ Cuisson\r\n\r\n    Préchauffer le four à 180°C et cuire 15 minutes.\r\n    7️⃣ Déguster\r\n\r\n    Servir tiède ou froid, selon préférence.\r\n\r\n', 'recette5.jpg', '2025-06-02 10:36:25'),
(6, 14, 'Brioche du Dimanche (Fourrée au Fromage Rôti)', 'Une brioche généreuse et gourmande, garnie d’un fromage rôti crémeux au cœur, parfaite pour partager lors des repas du dimanche.\r\n\r\n⚠️ Allergènes\r\nGluten, œufs, lait.\r\n\r\n📝 Ingrédients\r\n\r\n    400g de farine T55\r\n\r\n    2 œufs\r\n\r\n    100g de beurre\r\n\r\n    50g de sucre\r\n\r\n    100ml de lait tiède\r\n\r\n    1 sachet de levure boulangère\r\n\r\n    1 petit fromage crémeux (ex. reblochon ou camembert)\r\n\r\n👨‍🍳 Préparation\r\n1️⃣ Préparer la pâte\r\n\r\n    Dans un saladier, mélanger la farine, la levure et le sucre.\r\n    2️⃣ Ajouter les œufs et le lait\r\n\r\n    Incorporer les œufs et le lait tiède.\r\n    3️⃣ Ajouter le beurre\r\n\r\n    Ajouter le beurre mou en morceaux et pétrir 10 minutes.\r\n    4️⃣ Première levée\r\n\r\n    Laisser lever la pâte 1h30 sous un torchon.\r\n    5️⃣ Façonner\r\n\r\n    Étaler la pâte, déposer le fromage entier au centre et refermer en soudant bien les bords.\r\n    6️⃣ Deuxième levée\r\n\r\n    Laisser lever 30 minutes.\r\n    7️⃣ Cuisson\r\n\r\n    Préchauffer le four à 180°C. Enfourner pour 30 à 35 minutes, jusqu’à ce que la brioche soit dorée et le fromage fondant.', 'recette6.jpg', '2025-06-02 10:36:25');

-- --------------------------------------------------------

--
-- Table structure for table `recette_produit`
--

CREATE TABLE `recette_produit` (
  `recette_id` int NOT NULL,
  `produit_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `recette_produit`
--

INSERT INTO `recette_produit` (`recette_id`, `produit_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6);

-- --------------------------------------------------------

--
-- Table structure for table `site_config`
--

CREATE TABLE `site_config` (
  `id` int NOT NULL,
  `logo_url` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `site_config`
--

INSERT INTO `site_config` (`id`, `logo_url`) VALUES
(1, 'logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `carte` varchar(20) DEFAULT NULL,
  `boulangerie` varchar(100) DEFAULT NULL,
  `ccv` varchar(5) DEFAULT NULL,
  `expiration` varchar(10) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `rue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `password`, `created_at`, `email`, `role`, `nom`, `prenom`, `carte`, `boulangerie`, `ccv`, `expiration`, `pays`, `region`, `ville`, `rue`) VALUES
(11, '$2y$10$FW00Z6I.3Feb.0sgBsyDaObSC10PBsdXBVzon1vuIk6FltUd/3w9K', '2025-05-21 07:43:55', 'auflocondavoine@admin.fr', 'admin', 'Boulangerie', 'Au Flocon D\'avoine', '**** **** **** 7967', 'Au Flocon d\'Avoine', '112', '02/25', 'France', 'Haut-Rhin', 'Alkirch', 'rue de la Mayonnaise'),
(12, '$2y$10$L0NjHBZ4NOGIaA2GuwCe6eikvR85frOQlxJa01sHp82bv42cYj862', '2025-05-21 07:57:42', 'user@hotmail.fr', 'user', 'Paul', 'Jean', '**** **** **** ****', NULL, '889', '02/25', 'France', 'Haut-Rhin', 'Brunstatt', '32 rue de molière'),
(13, '$2y$10$/V6OU9WSgLP0sseVYVMUT.TTuNHiLDBlwD1tupqMP7zjop3cDIbsG', '2025-05-22 07:26:54', 'boulangerieo@admin.fr', 'admin', 'Boulangerie', 'O', NULL, 'Boulangerie \'O', NULL, NULL, NULL, NULL, NULL, NULL),
(14, '$2y$10$tIl4fa5v9rfzu6V61UteBuJU16niZauWruGMcGszttGNX7g7awst2', '2025-05-22 07:26:54', 'legrainbio@admin.fr', 'admin', 'Boulangerie', 'Bio', NULL, 'Le Grain Bio', NULL, NULL, NULL, NULL, NULL, NULL),
(15, '$2y$10$e3seNaxezhwDmGtfdIhxyOmNpdqyHIhADQk0oHPubtRBczD1p9deu', '2025-05-22 11:44:39', 'megamania45643@gmail.fr', 'user', 'Bouchier', 'Florian', '**** **** **** 7967', NULL, '284', '08/25', 'France', 'Bas-Rhin', 'Strasbourg', '98 rue des inconnus'),
(16, '$2y$10$Ujuy.bMNZPdCX29NtNYfgeU7t0unl7fc1b5jZH9E2rESjGPV1x2Zu', '2025-05-22 11:58:15', 'oasisgamerxxx8846@estvideo.org', 'user', 'Abdelkader', 'Ibrahim', '5132630071485336', NULL, '774', '04/26', 'France', 'Haut-rhin', 'Saint-Louis', '21 rue de la clairière'),
(17, '$2y$10$d4rRQoge8clrVVqR3jOknOS7fQyWUt0tfMlrMu3Pqs2fTTisT7f/a', '2025-05-22 12:01:47', 'callofdutygamerfunny@hotmail.fr', 'user', 'Bournier', 'Mathilde', '5130665877412351', NULL, '284', '02/25', 'France', 'Haut-Rhin', 'Mulhouse', '21 rue des faisans'),
(18, '$2y$10$uYmR94v5HiUtJn0owOcybOF6WC/j1tMd48Iwwt4PcS3qZOyMJFtIy', '2025-05-22 12:04:05', 'yoyoyohaha@sfr.com', 'user', 'Trocet', 'Anthony', '4564678676987967', NULL, '774', '04/26', 'France', 'Bas-Rhin', 'Strasbourg', '5 rue des chanceux'),
(19, '$2y$10$Z0/C9TLfQLE5ipmegYyLAOmrFUne2zQCF2/.hnithU9BSg6BBSU1u', '2025-05-22 12:09:40', 'drouliusboulius@outlook.fr', 'user', 'Trechier', 'Yannis', '514669995887542', NULL, '731', '05/27', 'France', 'Haut-Rhin', 'Mulhouse', '22 rue de l\'armistice'),
(20, '$2y$10$d3Re2Di5xfoBk5fREF6CaO62IgBhXtn5/8rm7ZwBMji9pJotScmxa', '2025-05-26 13:46:34', 'admin@exemple.com', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, '$2y$10$X7SUh2NwJhiT4AR9FxtiDOrxV1QuMVVAVgiA13Nt2yjEbMNxglS.W', '2025-05-26 13:46:34', 'user1@hotmail.fr', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boulangeries`
--
ALTER TABLE `boulangeries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Indexes for table `boulangeries_details`
--
ALTER TABLE `boulangeries_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boulangerie_id` (`boulangerie_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favoris_boulangeries`
--
ALTER TABLE `favoris_boulangeries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favoris` (`user_email`,`bakery_name`);

--
-- Indexes for table `favoris_produits`
--
ALTER TABLE `favoris_produits`
  ADD PRIMARY KEY (`user_email`,`product_id`),
  ADD KEY `favoris_produits_ibfk_2` (`product_id`);

--
-- Indexes for table `favoris_recettes`
--
ALTER TABLE `favoris_recettes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favori` (`user_email`,`recette_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_email_fk` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recettes`
--
ALTER TABLE `recettes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boulangeries_id` (`boulangeries_id`);

--
-- Indexes for table `recette_produit`
--
ALTER TABLE `recette_produit`
  ADD PRIMARY KEY (`recette_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Indexes for table `site_config`
--
ALTER TABLE `site_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boulangeries`
--
ALTER TABLE `boulangeries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `boulangeries_details`
--
ALTER TABLE `boulangeries_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `favoris_boulangeries`
--
ALTER TABLE `favoris_boulangeries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `favoris_recettes`
--
ALTER TABLE `favoris_recettes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `recettes`
--
ALTER TABLE `recettes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `site_config`
--
ALTER TABLE `site_config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `boulangeries_details`
--
ALTER TABLE `boulangeries_details`
  ADD CONSTRAINT `boulangeries_details_ibfk_1` FOREIGN KEY (`boulangerie_id`) REFERENCES `boulangeries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favoris_produits`
--
ALTER TABLE `favoris_produits`
  ADD CONSTRAINT `favoris_produits_ibfk_1` FOREIGN KEY (`user_email`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `favoris_produits_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_email_fk` FOREIGN KEY (`email`) REFERENCES `users` (`email`);

--
-- Constraints for table `recettes`
--
ALTER TABLE `recettes`
  ADD CONSTRAINT `recettes_ibfk_1` FOREIGN KEY (`boulangeries_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `recette_produit`
--
ALTER TABLE `recette_produit`
  ADD CONSTRAINT `recette_produit_ibfk_1` FOREIGN KEY (`recette_id`) REFERENCES `recettes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recette_produit_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
