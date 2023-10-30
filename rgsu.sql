SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `rgsu` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `rgsu`;

CREATE TABLE `owners` (
  `id` int NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `owners_pets` (
  `id` int NOT NULL,
  `owner_id` int NOT NULL,
  `pet_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pets` (
  `id` int NOT NULL,
  `code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nickname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `breed_name_id` int DEFAULT NULL,
  `gender` int DEFAULT NULL,
  `age` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pets_relatives` (
  `id` int NOT NULL,
  `parent_code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `children_code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pets_rewards` (
  `id` int NOT NULL,
  `pet_id` int NOT NULL,
  `reward_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `rewards` (
  `id` int NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `types_breeds` (
  `id` int NOT NULL,
  `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `breed_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `login` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `owners`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `owners_pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owners_pets_ibfk_1` (`pet_id`),
  ADD KEY `owners_pets_ibfk_2` (`owner_id`);

ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `breed_name_id` (`breed_name_id`);

ALTER TABLE `pets_relatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pets_relatives_ibfk_2` (`children_code`),
  ADD KEY `pets_relatives_ibfk_3` (`parent_code`);

ALTER TABLE `pets_rewards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reward_id` (`reward_id`),
  ADD KEY `pets_rewards_ibfk_3` (`pet_id`);

ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `types_breeds`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);


ALTER TABLE `owners_pets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `pets_relatives`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `pets_rewards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;


ALTER TABLE `owners_pets`
  ADD CONSTRAINT `owners_pets_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `owners_pets_ibfk_2` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`breed_name_id`) REFERENCES `types_breeds` (`id`);

ALTER TABLE `pets_relatives`
  ADD CONSTRAINT `pets_relatives_ibfk_2` FOREIGN KEY (`children_code`) REFERENCES `pets` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pets_relatives_ibfk_3` FOREIGN KEY (`parent_code`) REFERENCES `pets` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pets_rewards`
  ADD CONSTRAINT `pets_rewards_ibfk_3` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pets_rewards_ibfk_4` FOREIGN KEY (`reward_id`) REFERENCES `rewards` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
