SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `rgsu` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `rgsu`;

CREATE TABLE `pets` (
  `id` int NOT NULL,
  `code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nickname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `breed_name_id` int DEFAULT NULL,
  `gender` int DEFAULT NULL,
  `age` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pets_relatives` (
  `id` int NOT NULL,
  `parent_id` int NOT NULL,
  `children_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pets_rewards` (
  `id` int NOT NULL,
  `pet_id` int NOT NULL,
  `rewards_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `rewards` (
  `id` int NOT NULL,
  `reward_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `show_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `types_breeds` (
  `id` int NOT NULL,
  `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `breed_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `login` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users_pets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `pet_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `breed_name_id` (`breed_name_id`);

ALTER TABLE `pets_relatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pets_relatives_ibfk_1` (`parent_id`),
  ADD KEY `children_id` (`children_id`);

ALTER TABLE `pets_rewards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pets_rewards_ibfk_1` (`pet_id`),
  ADD KEY `rewards_id` (`rewards_id`);

ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `types_breeds`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

ALTER TABLE `users_pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_pets_ibfk_1` (`pet_id`),
  ADD KEY `users_pets_ibfk_2` (`user_id`);


ALTER TABLE `pets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `pets_relatives`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `pets_rewards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `rewards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `types_breeds`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `users_pets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;


ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`breed_name_id`) REFERENCES `types_breeds` (`id`);

ALTER TABLE `pets_relatives`
  ADD CONSTRAINT `pets_relatives_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `pets` (`id`),
  ADD CONSTRAINT `pets_relatives_ibfk_2` FOREIGN KEY (`children_id`) REFERENCES `pets` (`id`);

ALTER TABLE `pets_rewards`
  ADD CONSTRAINT `pets_rewards_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`),
  ADD CONSTRAINT `pets_rewards_ibfk_2` FOREIGN KEY (`rewards_id`) REFERENCES `rewards` (`id`);

ALTER TABLE `users_pets`
  ADD CONSTRAINT `users_pets_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`),
  ADD CONSTRAINT `users_pets_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
