USE nexodb;

DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
  idusuarios VARCHAR(256) NOT NULL,
  nombre VARCHAR(45) NOT NULL,
  apellido VARCHAR(45) NOT NULL,
  email VARCHAR(80) NOT NULL,
  password VARCHAR(256) NOT NULL,
  telefono VARCHAR(45) NOT NULL,
  role VARCHAR(45) NOT NULL,
  ciudad VARCHAR(45) NOT NULL,
  nombreCalle VARCHAR(45) NOT NULL,
  numeroCasa INT(11) NOT NULL,
  PRIMARY KEY (idusuarios),
  UNIQUE (email),
  INDEX (ciudad),
  INDEX (role)
);
