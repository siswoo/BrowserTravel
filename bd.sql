DROP DATABASE IF EXISTS browserTravel;
CREATE DATABASE browserTravel;
USE browserTravel;

DROP TABLE IF EXISTS humedad;
CREATE TABLE humedad (
	id INT AUTO_INCREMENT,
	ciudad VARCHAR(250) NOT NULL,
	lon VARCHAR(250) NOT NULL,
	lat VARCHAR(250) NOT NULL,
	humedad VARCHAR(250) NOT NULL,
	hora_creacion DATE NOT NULL,
	fecha_creacion DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE humedad CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;