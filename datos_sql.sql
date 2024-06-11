-- Crear la bbdd
DROP DATABASE IF EXISTS cajuca_database;
CREATE DATABASE cajuca_database;
USE cajuca_database;

-- Crear la tabla para monitores:
CREATE TABLE monitores (
codigo INT (6) AUTO_INCREMENT,
estado INT (1) NOT NULL DEFAULT "3",
DNI_NIF VARCHAR (9) UNIQUE,
nombre VARCHAR(50) NOT NULL,
apellidos VARCHAR(50) NOT NULL,
fecha_nac DATE DEFAULT "1990-12-01",
direccion VARCHAR(250),
cpostal INT (5),
poblacion VARCHAR (50),
provincia VARCHAR (50),
mail VARCHAR (80),
telefono INT (9) NOT NULL,
nsegsocial INT (20),
cuenta VARCHAR (24),
tit_monitor BOOLEAN NOT NULL,
tit_coordinador BOOLEAN NOT NULL,
tit_otros VARCHAR (300),
observaciones VARCHAR (300),
fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT PK_monitores PRIMARY KEY(codigo)
) AUTO_INCREMENT=10000;

-- Crear la tabla para monitores:
CREATE TABLE coordinadores (
codigo INT (6) AUTO_INCREMENT,
DNI_NIF VARCHAR (9) UNIQUE,
CONSTRAINT PK_coordinadores PRIMARY KEY(codigo, DNI_NIF),
CONSTRAINT FK_coordinadores_monitores FOREIGN KEY(DNI_NIF) 
    REFERENCES monitores(DNI_NIF)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) AUTO_INCREMENT=100;

-- Crear la tabla para clientes:
CREATE TABLE clientes (
codigo INT (6) AUTO_INCREMENT,
estado INT (1) NOT NULL DEFAULT "1",
NIF_CIF VARCHAR (12) UNIQUE,
RazonSocial VARCHAR(100),
nombre VARCHAR(50) NOT NULL,
apellidos VARCHAR (50) NOT NULL,
direccion VARCHAR(250),
cpostal INT (5),
poblacion VARCHAR (50),
provincia VARCHAR (50),
mail VARCHAR (80),
telefono_1 INT (9) NOT NULL,
telefono_2 INT (9),
NIF_CIF_fac VARCHAR (12) NOT NULL,
RazonSocial_fac VARCHAR(50) NOT NULL,
nombrecompleto_fac VARCHAR (50) NOT NULL,
direccion_fac VARCHAR(250),
cpostal_fac INT (5),
poblacion_fac VARCHAR (50),
provincia_fac VARCHAR (50),
mail_fac VARCHAR (80),
observaciones VARCHAR (300),
fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT PK_clientes PRIMARY KEY(codigo)
) AUTO_INCREMENT=500000;

-- Crear la tabla para eventos:
CREATE TABLE eventos (
codigo INT (7) AUTO_INCREMENT,
estado INT (1) NOT NULL DEFAULT "1",
codigo_cliente INT(6) NOT NULL,
nombre_evento VARCHAR(100) UNIQUE,
descripcion_evento VARCHAR (300) NOT NULL,
direccion_evento VARCHAR(250),
cpostal INT (5),
poblacion VARCHAR (50),
provincia VARCHAR (50),
fecha_inicio_evento DATE NOT NULL,
fecha_final_evento DATE NOT NULL,
horainicio TIME,
horafinal TIME,
codigo_coordinador INT(6) NOT NULL,
horas_trabajadas INT(2) NOT NULL DEFAULT "3",
horas_desplazamiento INT(2) NOT NULL DEFAULT "1",
horas_montaje INT(2) NOT NULL DEFAULT "1",
horas_desmontaje INT(2) NOT NULL DEFAULT "1",
observaciones VARCHAR (300),
fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT PK_eventos PRIMARY KEY(codigo),
CONSTRAINT FK_eventos_cliente FOREIGN KEY(codigo_cliente) 
    REFERENCES clientes(codigo)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
CONSTRAINT FK_eventos_coordinador FOREIGN KEY(codigo_coordinador) 
    REFERENCES coordinadores(codigo)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) AUTO_INCREMENT=1000000;

-- Crear la tabla para eventos - monitores:
CREATE TABLE eventos_monitores (
codigo_eventos INT (7),
codigo_monitores INT (6),
fecha_inicio_evento DATE NOT NULL,
CONSTRAINT PK_eventos_monitores PRIMARY KEY(codigo_eventos, codigo_monitores),
CONSTRAINT FK_eventos_monitores_eventos FOREIGN KEY(codigo_eventos) 
    REFERENCES eventos(codigo)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
CONSTRAINT FK_eventos_monitores_monitores FOREIGN KEY(codigo_monitores) 
    REFERENCES monitores(codigo)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

-- Crear la tabla para materiales:
CREATE TABLE materiales (
codigo INT (6) AUTO_INCREMENT,
estado INT (1) NOT NULL DEFAULT "1",
tipo_material VARCHAR (30) NOT NULL DEFAULT "variado",
subtipo_material VARCHAR (30) NOT NULL DEFAULT "-",
nombre VARCHAR (30) UNIQUE,
caracteristicas VARCHAR (200) NOT NULL,
observaciones VARCHAR (300),
fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT PK_materiales PRIMARY KEY (codigo)
) AUTO_INCREMENT=200000;

-- Crear la tabla para actividades:
CREATE TABLE actividades (
codigo INT (6) AUTO_INCREMENT,
estado INT (1) NOT NULL DEFAULT "1",
tipo VARCHAR (30) NOT NULL,
subtipo VARCHAR (30) NOT NULL,
etiquetas VARCHAR (100),
nombre VARCHAR (30) UNIQUE,
descripcion VARCHAR (200) NOT NULL,
precio_hora FLOAT (6,2) DEFAULT "0.00",
jugadores INT (2),
observaciones VARCHAR (300),
fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT PK_actividades PRIMARY KEY (codigo)
) AUTO_INCREMENT=300000;

-- Crear la tabla para materiales - actividades:
CREATE TABLE materiales_actividades (
codigo_actividades INT (6),
codigo_materiales INT (6),
cantidad_materiales INT (2) NOT NULL DEFAULT "99",
CONSTRAINT PK_materiales_actividades PRIMARY KEY(codigo_actividades, codigo_materiales),
CONSTRAINT FK_materiales_actividades_materiales FOREIGN KEY(codigo_materiales) 
    REFERENCES materiales(codigo)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
CONSTRAINT FK_materiales_actividades_actividades FOREIGN KEY(codigo_actividades) 
    REFERENCES actividades(codigo)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);


-- Crear la tabla para eventos - actividades:
CREATE TABLE eventos_actividades (
codigo_eventos INT (7),
codigo_actividades INT (6),
cantidad_actividades INT (2) NOT NULL DEFAULT "1",
CONSTRAINT PK_eventos_actividades PRIMARY KEY(codigo_eventos, codigo_actividades),
CONSTRAINT FK_eventos_actividades_eventos FOREIGN KEY(codigo_eventos) 
    REFERENCES eventos(codigo)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
CONSTRAINT FK_eventos_actividades_actividades FOREIGN KEY(codigo_actividades) 
    REFERENCES actividades(codigo)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

-- Crear tabla para usuarios:
CREATE TABLE usuarios (
codigo INT (2) AUTO_INCREMENT,
usuario VARCHAR (30) UNIQUE,
contrasena VARCHAR (250) NOT NULL,
nombre VARCHAR (30) NOT NULL,
apellidos VARCHAR (50) NOT NULL,
estado INT (1) NOT NULL DEFAULT "1",
permisos INT (1) NOT NULL DEFAULT "1",
mail VARCHAR (50) NOT NULL,
observaciones VARCHAR (300),
CONSTRAINT PK_usuarios PRIMARY KEY (codigo)
) AUTO_INCREMENT=1;



