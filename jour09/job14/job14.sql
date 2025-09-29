-- Sélectionne prenom, nom, naissance des étudiants nés entre 1998 et 2018
USE jour09;
SELECT prenom, nom, naissance
FROM etudiants
WHERE naissance BETWEEN '1998-01-01' AND '2018-12-31';