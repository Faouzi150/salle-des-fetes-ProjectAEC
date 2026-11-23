CREATE DATABASE IF NOT EXISTS `palais_des_fetes`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `palais_des_fetes`;

CREATE TABLE IF NOT EXISTS `salles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(120) NOT NULL,
  `categorie` VARCHAR(80) NOT NULL,
  `capacite` INT UNSIGNED NOT NULL,
  `superficie` INT UNSIGNED NOT NULL,
  `tarif` INT UNSIGNED NOT NULL,
  `resume` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `formules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `salle_id` INT UNSIGNED NOT NULL,
  `nom` VARCHAR(120) NOT NULL,
  `tarif` INT UNSIGNED NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_formules_salle_id` (`salle_id`),
  CONSTRAINT `fk_formules_salle`
    FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `reservations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `salle_id` INT UNSIGNED NOT NULL,
  `formule_nom` VARCHAR(120) DEFAULT NULL,
  `date_evenement` DATE NOT NULL,
  `nb_invites` INT UNSIGNED NOT NULL,
  `nom_client` VARCHAR(120) NOT NULL,
  `email_client` VARCHAR(160) NOT NULL,
  `telephone_client` VARCHAR(40) NOT NULL,
  `commentaire` TEXT DEFAULT NULL,
  `montant_estime` INT UNSIGNED NOT NULL,
  `date_creation` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_reservations_salle_date` (`salle_id`, `date_evenement`),
  KEY `idx_reservations_salle_id` (`salle_id`),
  CONSTRAINT `fk_reservations_salle`
    FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `salles` (`id`, `nom`, `categorie`, `capacite`, `superficie`, `tarif`, `resume`, `description`) VALUES
(1, 'Salon Royal', 'Mariage & Gala', 350, 450, 1800,
 'Grande salle lumineuse avec scène équipée, piste de danse et terrasse privée.',
 'Le Salon Royal offre un cadre raffiné avec hauteur sous plafond de 6 mètres, piste de danse en parquet massif, scène sonorisée, cuisine traiteur équipée et parking privé sécurisé de 120 places.'),
(2, 'Jardin d''Hiver', 'Réception', 150, 210, 950,
 'Verrière élégante idéale pour les cocktails, fiançailles et dîners intimes.',
 'Espace baigné de lumière naturelle donnant sur un parc arboré, parfait pour les réceptions familiales, cocktails dînatoires et cérémonies privées.'),
(3, 'Espace Horizon', 'Séminaire', 90, 130, 600,
 'Salle moderne dotée d''un vidéoprojecteur, sonorisation et accès PMR.',
 'Salle modulable conçue pour les séminaires d''entreprise, conférences, ateliers et assemblées avec équipement audiovisuel et accès PMR.');

INSERT IGNORE INTO `formules` (`id`, `salle_id`, `nom`, `tarif`, `description`) VALUES
(1, 1, 'Pack Son & Lumière', 350, 'Système audio professionnel, micros sans fil et jeux de lumière programmables.'),
(2, 1, 'Décoration Florale', 480, 'Centres de tables, arche d''accueil et habillage complet aux couleurs de l''événement.'),
(3, 2, 'Pack Cocktail & Ambiance', 260, 'Éclairage chaleureux, espace bar et sonorisation douce.'),
(4, 2, 'Décoration Florale', 390, 'Compositions végétales et touches florales adaptées à la verrière.'),
(5, 3, 'Pack Conférence Pro', 220, 'Vidéoprojecteur haute luminosité, pupitre, micros et prise de son.'),
(6, 3, 'Pause Café & Accueil', 180, 'Espace d''accueil dédié avec boissons chaudes et viennoiseries.');
