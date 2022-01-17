DROP DATABASE IF EXISTS `abcmaivticket`;
CREATE DATABASE IF NOT EXISTS `abcmaivticket` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `abcmaivticket`;


CREATE TABLE `enterprises` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientNumber` int(10) NOT NULL UNIQUE,
  `name` varchar(150) NOT NULL,
  `siret` varchar(14) NOT NULL UNIQUE,
  `website` text NOT NULL,
  `address` text NOT NULL,
  CONSTRAINT pk_enterprise PRIMARY KEY (`id`)
);


CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `id_enterprise` int(11) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `admin` int(1) NOT NULL DEFAULT 0 ,
  `blocked` int(1) NOT NULL,
  `dateCreated` datetime NOT NULL DEFAULT NOW(),
  CONSTRAINT pk_users PRIMARY KEY (`id`),
  CONSTRAINT `fk_users_enterprises` FOREIGN KEY (`id_enterprise`) REFERENCES `enterprises`(`id`)
);

ALTER TABLE `users`
    ADD CONSTRAINT ck_admin CHECK (`admin` IN(0, 1));

CREATE TABLE `mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `website_concerned` varchar(255) NOT NULL,
  `object` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `speedcall` tinyint(1) NOT NULL,
  `callDate` varchar(10) DEFAULT NULL,
  `callHour` varchar(11) DEFAULT NULL,
  `sendDate` datetime NOT NULL DEFAULT NOW(),
  CONSTRAINT pk_mails PRIMARY KEY (`id`),
  CONSTRAINT `fk_mails_users` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `recovering` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `id_user` int(11) NOT NULL UNIQUE,
 `code` varchar(6) NOT NULL,
 `sendDate` datetime NOT NULL DEFAULT NOW(),
 CONSTRAINT pk_mails PRIMARY KEY (`id`),
 CONSTRAINT `fk_recovering_users` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`)
);


ALTER TABLE `mails`
  ADD CONSTRAINT ck_object CHECK (`object` IN('Modification du site', 'Facturation', 'Dysfonctionnement', 'Besoin d\'assistance', 'Autre'));

ALTER TABLE `mails`
  ADD CONSTRAINT ck_callhour CHECK (`callHour` IN('14h-14h30', '14h30-15h', '15h-15h30', '15h30-16h', '16h-16h30', '16h30-17h') OR `callHOUR` = NULL);




INSERT INTO `enterprises`(`clientNumber`, `name`, `siret`, `website`, `address`) VALUES ('202120','MonEntreprise','12345678912345','www.test.fr','06600 ANTIBES');
INSERT INTO `enterprises`(`clientNumber`, `name`, `siret`, `website`, `address`) VALUES ('202121','MonEntreprise1','12345678912350','www.test1.fr; www.stop.com','06600 ANTIBES');

INSERT INTO `users`(`lastname`, `firstname`, `id_enterprise`, `email`, `phone`, `password`, `secret`, `admin`, `blocked`, `dateCreated`)
  VALUES ('ROOT','Root', 1,'root@root.com','060102030405','f2d81a260dea8a100dd517984e53c56a7523d96942a834b9cdc249bd4e8c7aa9','d74a263cc481e8c1a43ecb9365ed43a433d48b621636023629', 1, 0, NOW());
INSERT INTO `users`(`lastname`, `firstname`, `id_enterprise`, `email`, `phone`, `password`, `secret`, `admin`, `blocked`, `dateCreated`)
  VALUES ('ROOT1','Root1', 2,'root1@root.com','060102030405','f2d81a260dea8a100dd517984e53c56a7523d96942a834b9cdc249bd4e8c7aa9','d74a263cc481e8c1a43ecb9365ed43a433d48b621636023629', 0, 0, NOW());

INSERT INTO `mails`(`id_user`, `website_concerned`, `object`, `message`, `speedcall`, `sendDate`) VALUES (1, 'www.test.fr', 'Facturation', 'Test message', 0, NOW());
INSERT INTO `mails`(`id_user`, `website_concerned`, `object`, `message`, `speedcall`, `sendDate`) VALUES (2, 'www.test1.fr', 'Autre', 'Test message', 0, NOW());
INSERT INTO `mails`(`id_user`, `website_concerned`, `object`, `message`, `speedcall`, `callDate`, `callHour`, `sendDate`) VALUES (2, 'www.test1.fr', 'Modification du site', 'Test message', 1, '2021-11-24', '15h30-16h' ,  NOW());
INSERT INTO `mails`(`id_user`, `website_concerned`, `object`, `message`, `speedcall`, `callDate`, `callHour`, `sendDate`) VALUES (2, 'www.stop.com', 'Facturation', 'Test message', 1, '2021-11-24', '15h30-16h' ,  NOW());


/* Tâche BDD pour supprimer les recovery de plus de 24h (toutes les heures) */
DROP EVENT IF EXISTS `remove_recovering`;
CREATE DEFINER=`root`@`localhost` EVENT `remove_recovering` ON SCHEDULE EVERY 1 HOUR STARTS '2021-11-17 09:54:24' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM `recovering` WHERE id IN (SELECT id FROM `recovering` WHERE sendDate < DATE_SUB(NOW(), INTERVAL 1 day));