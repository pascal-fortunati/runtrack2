-- sélectionne prenom, nom, naissance des étudiantes
USE jour09;
SELECT prenom, nom, naissance
FROM etudiants
WHERE sexe = 'Femme';
