# Host: localhost  (Version 8.0.19)
# Date: 2023-12-17 18:53:08
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "cats"
#

CREATE TABLE `cats` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `age` int DEFAULT NULL,
  `sex` int DEFAULT NULL,
  `mother` int DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

#
# Data for table "cats"
#

INSERT INTO `cats` VALUES (1,'Муська',5,2,1),(2,'Барсик',5,1,1),(108,'Стефан',1,1,1),(109,'Уголек',5,1,1),(110,'Бэнчик',6,1,1),(111,'Матильда',3,2,1),(112,'Васька',2,1,1),(113,'Федька',1,1,111),(114,'Рыжик',2,1,1),(115,'Маня',1,2,1),(116,'Машка',1,2,1);

#
# Structure for table "fathers_connect"
#

CREATE TABLE `fathers_connect` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `father_id` int DEFAULT NULL,
  `child_id` int DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

#
# Data for table "fathers_connect"
#

INSERT INTO `fathers_connect` VALUES (7,2,14),(33,2,1),(34,3,1),(35,2,1),(36,3,1),(39,3,2),(40,2,1),(41,3,1),(42,2,108),(43,2,108),(44,2,109),(45,2,112),(46,109,112),(47,110,112),(48,2,113),(49,108,114),(50,109,114),(51,2,115),(52,110,116),(53,112,116);

#
# Structure for table "pages"
#

CREATE TABLE `pages` (
  `Id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

#
# Data for table "pages"
#


#
# Structure for table "sex_type"
#

CREATE TABLE `sex_type` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

#
# Data for table "sex_type"
#

INSERT INTO `sex_type` VALUES (1,'male'),(2,'female');
