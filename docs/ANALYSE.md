---

### 3. Contenu de `docs/ANALYSE.md` (`ProjetAEC/docs/ANALYSE.md`)

```markdown
# Analyse du Projet — Salle des Fêtes

## 1. Présentation du thème et contrat de binôme

### 1.1 Thème validé
Conception et développement d'un site web dynamique pour la gestion et la promotion d'une **salle des fêtes** (*[Nom de la salle des fêtes, ex. : Espace Prestige / Palais des Fêtes]*). Le site permet aux visiteurs de consulter les espaces disponibles, filtrer selon la capacité ou le tarif, découvrir les formules (traiteur, sonorisation, décoration) et effectuer une demande de réservation, tandis que les gestionnaires administrent l'ensemble des données via un espace protégé.

### 1.2 Contrat de binôme
- **Membre 1 :** [Nom Prénom 1]
- **Membre 2 :** [Nom Prénom 2]
- **Règles de fonctionnement :**
  - Travail sur des branches dédiées (`feature/...`) à partir de `main`.
  - Chaque fusion (*Pull Request*) sur `main` est relue par l'autre membre du binôme.
  - Pose systématique d'une étiquette de version (`v0.1`, `v0.2`, etc.) à la fin de chaque jalon validé.
  - Synchronisation régulière avant et après chaque session de travail.

---

## 2. Objets et dictionnaire des données

Le système s'articule autour de **4 entités principales** (dont 3 entités métier fortement reliées et 1 entité pour la gestion des accès) :

### 2.1 Entité `salles` (Table principale — minimum 10 enregistrements d'essai)
Représente les différents espaces ou salons proposés à la location.
- `id_salle` (INT, clé primaire, auto-incrémentée) : identifiant unique de la salle.
- `nom` (VARCHAR(100), non nul) : nom de la salle (ex. : *Salon Royal*, *Jardin d'Hiver*).
- `type_evenement` (VARCHAR(60), non nul) : ambiance ou usage principal (Mariage, Conférence, Anniversaire, Gala).
- `capacite_max` (INT, non nul) : nombre maximal d'invités accueillis.
- `superficie_m2` (INT, non nul) : surface en mètres carrés.
- `tarif_jour` (DECIMAL(10,2), non nul) : prix de location de base pour une journée/soirée.
- `equipements` (TEXT) : équipements inclus (climatisation, scène, parking, loge).
- `description` (TEXT, non nul) : présentation détaillée de la salle.
- `image_url` (VARCHAR(255)) : chemin vers la photo principale de la salle.
- `disponible` (TINYINT(1), défaut 1) : état d'ouverture à la réservation.

### 2.2 Entité `formules`
Représente les prestations complémentaires associables à une réservation.
- `id_formule` (INT, clé primaire, auto-incrémentée) : identifiant unique de la formule.
- `intitule` (VARCHAR(100), non nul) : nom de la prestation (ex. : *Pack Son & Lumière*, *Menu Gastronomique*, *Décoration Florale*).
- `categorie` (VARCHAR(50), non nul) : catégorie (Restauration, Technique, Décoration, Accueil).
- `prix_forfait` (DECIMAL(10,2), non nul) : tarif forfaitaire de la prestation.
- `description` (TEXT, non nul) : contenu détaillé de la formule.

### 2.3 Entité `reservations`
Représente les demandes et dossiers de réservation effectués par les clients.
- `id_reservation` (INT, clé primaire, auto-incrémentée) : identifiant unique de la réservation.
- `id_salle` (INT, clé étrangère -> `salles.id_salle`, non nul) : salle réservée.
- `id_formule` (INT, clé étrangère -> `formules.id_formule`, nullable) : formule choisie en option.
- `nom_client` (VARCHAR(100), non nul) : nom et prénom du demandeur.
- `email_client` (VARCHAR(150), non nul) : adresse courriel de contact.
- `telephone_client` (VARCHAR(30), non nul) : numéro de téléphone.
- `date_evenement` (DATE, non nul) : date souhaitée pour l'événement.
- `nb_invites` (INT, non nul) : nombre de personnes prévues.
- `statut` (VARCHAR(30), défaut `'en_attente'`) : état du dossier (`en_attente`, `confirmee`, `annulee`).
- `commentaire` (TEXT) : besoins particuliers exprimés par le client.
- `date_creation` (DATETIME, défaut CURRENT_TIMESTAMP) : horodatage de la demande.

### 2.4 Entité `utilisateurs` (Étape 10)
Représente les comptes permettant de distinguer les accès publics/clients et l'administration.
- `id_utilisateur` (INT, clé primaire, auto-incrémentée) : identifiant unique.
- `nom_complet` (VARCHAR(100), non nul) : nom affiché.
- `email` (VARCHAR(150), unique, non nul) : identifiant de connexion.
- `mot_de_passe` (VARCHAR(255), non nul) : empreinte sécurisée (`password_hash`).
- `role` (VARCHAR(20), défaut `'client'`) : rôle attribué (`admin` ou `client`).

---

## 3. Relations et schéma de la base de données

### 3.1 Règles de gestion et cardinalités
1. **Salle — Réservation (1,N) :** Une `salle` peut être associée à zéro ou plusieurs `reservations` (`reservations.id_salle` référence `salles.id_salle`). Une réservation porte obligatoirement sur une salle.
2. **Formule — Réservation (0,N) :** Une `formule` peut être sélectionnée dans zéro ou plusieurs `reservations` (`reservations.id_formule` référence `formules.id_formule`). Une réservation peut inclure une formule optionnelle.
3. **Utilisateur — Rôles :** Les comptes `admin` ont accès à la zone de gestion (CRUD complet sur les salles, formules et statuts des réservations). Les comptes `client` (ou visiteurs) peuvent consulter le catalogue et soumettre des demandes.

### 3.2 Schéma relationnel (MCD / MLD simplifié)

+-----------------------+          +---------------------------+          +-----------------------+
|        salles         |          |       reservations        |          |       formules        |
+-----------------------+          +---------------------------+          +-----------------------+
| PK id_salle           |<---+     | PK id_reservation         |     +--->| PK id_formule         |
|    nom                |    +-----| FK id_salle (NOT NULL)    |     |    |    intitule           |
|    type_evenement     |          | FK id_formule (NULL)      |-----+    |    categorie          |
|    capacite_max       |          |    nom_client             |          |    prix_forfait       |
|    superficie_m2      |          |    email_client           |          |    description        |
|    tarif_jour         |          |    telephone_client       |          +-----------------------+
|    equipements        |          |    date_evenement         |
|    description        |          |    nb_invites             |          +-----------------------+
|    image_url          |          |    statut                 |          |     utilisateurs      |
|    disponible         |          |    commentaire            |          +-----------------------+
+-----------------------+          |    date_creation          |          | PK id_utilisateur     |
                                   +---------------------------+          |    nom_complet        |
                                                                          |    email (UNIQUE)     |
                                                                          |    mot_de_passe       |
                                                                          |    role               |
                                                                          +-----------------------+