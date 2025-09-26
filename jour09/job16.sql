-- sélectionne le nom de l'étage, le nom et la capacité de la plus grande salle
USE jour09;
SELECT e.nom AS etage, s.nom AS `Biggest Room`, s.capacite
FROM salles s
JOIN etage e ON s.id_etage = e.id
WHERE s.capacite = (SELECT MAX(capacite) FROM salles)
LIMIT 1;