CREATE DATABASE tp_flight CHARACTER SET utf8mb4;

USE tp_flight;

-- CREATE TABLE etudiant (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nom VARCHAR(100),
--     prenom VARCHAR(100),
--     email VARCHAR(100),
--     age INT
-- );

-- Table des clients
CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    date_naissance DATE
);

-- Table des types de prêt
CREATE TABLE type_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    taux FLOAT NOT NULL CHECK (taux >= 0)
);

-- Table des prêts
CREATE TABLE pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    type_pret_id INT NOT NULL,
    montant DECIMAL(12,2) NOT NULL CHECK (montant > 0),
    date_debut DATE NOT NULL,
    duree_mois INT NOT NULL CHECK (duree_mois > 0),
    statut ENUM('en_cours', 'termine') DEFAULT 'en_cours',

    FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE CASCADE,
    FOREIGN KEY (type_pret_id) REFERENCES type_pret(id) ON DELETE CASCADE
);

-- Table des fonds de la banque (capital disponible)
CREATE TABLE fond (
    id INT AUTO_INCREMENT PRIMARY KEY,
    montant DECIMAL(15,2) NOT NULL CHECK (montant > 0),
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- script

-- faire la somme des montants dans fonds
SELECT SUM(montant) AS total_montant
FROM ta_table;
-- par jour 
SELECT DATE(date_ajout) AS jour, SUM(montant) AS total_montant
FROM ta_table
GROUP BY DATE(date_ajout);

-- cote prets
CREATE TABLE type_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    taux FLOAT NOT NULL,
    delai INT 
);

ALTER TABLE pret DROP FOREIGN KEY pret_ibfk_2;
DROP TABLE type_pret;
DROP TABLE pret;
DROP TABLE type_pret;

CREATE TABLE type_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    taux FLOAT NOT NULL,
    delai INT 
);

INSERT INTO type_pret (nom, taux, delai) VALUES 
('Pret court terme', 0.02, 6),
('Pret long terme', 0.03, 12),
('Pret tres long terme', 0.05, 24);

-- ajout client 
INSERT INTO client (nom, prenom, email, date_naissance) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', '1980-01-15'),
('Martin', 'Sophie', 'sophie.martin@example.com', '1990-05-23'),
('Durand', 'Paul', 'paul.durand@example.com', '1975-12-01'),
('Lemoine', 'Claire', 'claire.lemoine@example.com', '1988-03-30'),
('Moreau', 'David', 'david.moreau@example.com', '1992-07-11'),
('Rousseau', 'Alice', 'alice.rousseau@example.com', '1985-09-20'),
('Girard', 'Thomas', 'thomas.girard@example.com', '1978-11-05'),
('Bertrand', 'Marie', 'marie.bertrand@example.com', '1995-02-14'),
('Fournier', 'Luc', 'luc.fournier@example.com', '1983-04-18'),
('Blanc', 'Julie', 'julie.blanc@example.com', '1991-08-09'),
('Gauthier', 'Marc', 'marc.gauthier@example.com', '1987-06-27'),
('Leclerc', 'Emma', 'emma.leclerc@example.com', '1993-10-03'),
('Perrin', 'Nicolas', 'nicolas.perrin@example.com', '1979-01-29'),
('Chevalier', 'Laura', 'laura.chevalier@example.com', '1986-12-12'),
('Marchand', 'Alexandre', 'alexandre.marchand@example.com', '1994-05-08'),
('Henry', 'Camille', 'camille.henry@example.com', '1982-07-22'),
('Leroy', 'Maxime', 'maxime.leroy@example.com', '1990-09-17'),
('Faure', 'Elodie', 'elodie.faure@example.com', '1984-11-30'),
('Noël', 'Julien', 'julien.noel@example.com', '1977-03-05'),
('Colin', 'Isabelle', 'isabelle.colin@example.com', '1996-01-10');

-- gestion des prets client
CREATE TABLE pret_client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    id_type_pret INT NOT NULL,
    montant FLOAT NOT NULL,
date_pret DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES client(id),
    FOREIGN KEY (id_type_pret) REFERENCES type_pret(id)
);

-- somme des interets par mois et annee
SELECT 
    DATE_FORMAT(p.date_pret, '%Y-%m') AS mois_annee,
    SUM(p.montant * t.taux / 12) AS interets_mensuels
FROM pret_client p
JOIN type_pret t ON p.id_type_pret = t.id
WHERE p.date_pret BETWEEN :date_debut AND :date_fin
GROUP BY mois_annee
ORDER BY mois_annee;

-- ajout pret_client
INSERT INTO pret_client (id_client, id_type_pret, montant, date_pret) VALUES
(1, 1, 2000000, '2025-01-05'),
(2, 2, 5000000, '2025-02-10'),
(3, 3, 12000000, '2025-03-15'),
(4, 1, 1500000, '2025-04-20'),
(5, 2, 3000000, '2025-05-25'),
(6, 3, 7000000, '2025-06-05'),
(7, 1, 1000000, '2025-06-15');

-- remboursement 

CREATE TABLE remboursement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pret INT NOT NULL,
    mois INT NOT NULL,
    date_remboursement DATE,
    montant_paye FLOAT NOT NULL,
    FOREIGN KEY (id_pret) REFERENCES pret_client(id)
);

-- assurance
ALTER TABLE pret_client ADD COLUMN assurance FLOAT DEFAULT 0;

---
--creation type de pret avec differents taux (CRUD + PAGES + BASE)
--gestion de pret pour client 
    -- limite min : 1 000 000 ar
    -- laisser 30 % reste a la banque (n'authoriser un ou des prets que si le pret permet de laisser encore 30% de fonds a la banque) 
    -- historique de pret 
    -- mihena ny vola ny banque refa effectue le pret
    -- choix de type de pret
    -- formulaires d insertion (AJAX + BASE + FORMULAIRE)
    -- utiliser -- faire la somme des montants dans fonds
-- SELECT SUM(montant) AS total_montant
-- FROM ta_table;
    -- une table pour gerer les prets par client (liste, formulaire d insertion de pret, etc ... tout ce qui pourrait etre necessaire dans une gestion de pret)
    --calculs des interets de telle sorte que :
        -- si on emprunte une somme 12000000 sur 12 mois, interet = 0.03
        -- ca fait (12000000 ar / 12 mois) + (12000000*0.03/12) 

---
-- avec MVC 
-- creer:
    -- tableau pour afficher les interets gagnes par mois par l EF
    -- filtre (mois/annee debut mois/annee fin)
    -- +graphique a partir du tableau

---
-- ergonomie + IHM
-- systeme de remboursement : annuite constante
-- 1 pret : +assurance en % si < 6 mois
-- simulation pour verification : excel 
-- validation de pret : interface finance
-- genereation de pdf a donner au client pour un pret 
    -- releve en tableau mensuel
    -- +reste a payer 
    -- +interet
-- 1 client : +r prets possibles
-- delai 1 mois pour remboursement
    -- a inserer (select option oui ou non)



