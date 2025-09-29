-- Sélectionne les étudiants dont le prénom commence par T
USE jour09;
SELECT * FROM etudiants
WHERE prenom LIKE 'T%';
