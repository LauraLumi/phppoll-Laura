-- Sisesta **** asemele config.php failis määratud andmebaasi nimi

CREATE DATABASE laurakellylumi_phppoll COLLATE utf8mb4_estonian_ci;

USE laurakellylumi_phppoll;

CREATE TABLE questions 
( id_q INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
 question VARCHAR(255) NOT NULL , 
 answer_1 VARCHAR(100) NOT NULL ,
 answer_2 VARCHAR(100) NOT NULL ,
 answer_3 VARCHAR(100) NOT NULL ,
 created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 status INT(1) NOT NULL DEFAULT 0,
 PRIMARY KEY (id_q));

CREATE TABLE answers 
( id_a INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
 id_q INT(11) UNSIGNED NOT NULL ,
 answer INT(2) NOT NULL ,
 date DATETIME NULL DEFAULT CURRENT_TIMESTAMP ,
 IP VARCHAR(20) NULL , 
 PRIMARY KEY (id_a), FOREIGN KEY (id_q) REFERENCES questions(id_q)); 