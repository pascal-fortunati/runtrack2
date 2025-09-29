-- Récupérer le nom des salles et le nom de leur étage
USE jour09;
SELECT s.nom AS salle, e.nom AS etage
FROM salles s
LEFT JOIN etage e ON s.id_etage = e.id;
