DROP DATABASE IF EXISTS coop_project;

DROP USER 'user_coopproject'@'localhost';

#Manque les indexs

# Création de la base de donnée
CREATE DATABASE coop_project CHARACTER SET utf8 COLLATE utf8_general_ci;

USE coop_project;

# Création de l'utilisateur et l'assignation de ses droits
CREATE USER 'user_coopproject'@'localhost' IDENTIFIED BY 'CarotteGeante123';

GRANT SELECT,INSERT,UPDATE,CREATE,DELETE,ALTER,INDEX
ON coop_project.*
TO 'user_coopproject'@'localhost'
IDENTIFIED BY 'CarotteGeante123';
