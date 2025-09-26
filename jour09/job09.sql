-- Sélectionne les étudiants de moins de 18 ans
USE jour09;
SELECT * FROM etudiants
WHERE naissance > DATE_SUB(CURDATE(), INTERVAL 18 YEAR);
