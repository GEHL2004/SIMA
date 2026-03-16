/* CREACIÓN DE LAS BASE DE DATOS sima*/
/* |  |  |  |  |  |  |  |  | */
/* |  |  |  |  |  |  |  |  | */
/* |  |  |  |  |  |  |  |  | */
/* V  V  V  V  V  V  V  V  V*/

DROP DATABASE IF EXISTS sima;
CREATE DATABASE IF NOT EXISTS sima;
ALTER DATABASE sima CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use sima;

/* CREACIÓN DE LAS ENTIDADES*/
/* |  |  |  |  |  |  |  |  | */
/* |  |  |  |  |  |  |  |  | */
/* |  |  |  |  |  |  |  |  | */
/* V  V  V  V  V  V  V  V  V*/

CREATE TABLE usuarios(
	id_usuario INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    nombres VARCHAR(300) NOT NULL,
    apellidos VARCHAR(300) NOT NULL,
    nombre_user VARCHAR(60) NOT NULL,
    password_user VARCHAR(300) NOT NULL,
    pregunta_secreta VARCHAR(500) NOT NULL,
    respuesta_secreta VARCHAR(500) NOT NULL,
    nivel INT NOT NULL,
    estado INT NOT NULL,
    creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO usuarios(cedula, nombres, apellidos, nombre_user, password_user, pregunta_secreta, respuesta_secreta, nivel, estado) VALUES ('V-00000000', 'ADMIN_ADMIN', 'ADMIN_ADMIN', 'admin', 'HScDomHEu49GSfPSatWXER10MSDJMINwmcbFzBwLYBI=', 'Quien es el administrador?', 'El adminminmin', 1, 1);

CREATE TABLE auditorias(
	id_auditoria INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_usuario INT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    accion TEXT NOT NULL,
    creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE estados (
	id_estado INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nombre_estado VARCHAR(100) NOT NULL,
    creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO estados (nombre_estado) VALUES
('Amazonas'),
('Anzoátegui'),
('Apure'),
('Aragua'),
('Barinas'),
('Bolívar'),
('Carabobo'),
('Cojedes'),
('Delta Amacuro'),
('Distrito Capital'),
('Falcón'),
('Guárico'),
('Lara'),
('Mérida'),
('Miranda'),
('Monagas'),
('Nueva Esparta'),
('Portuguesa'),
('Sucre'),
('Táchira'),
('Trujillo'),
('La Guaira'),
('Yaracuy'),
('Zulia');

CREATE TABLE municipios (
	id_municipio INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_estado INT NOT NULL,
	nombre_municipio VARCHAR(100) NOT NULL,
	creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO municipios(id_estado, nombre_municipio) VALUES
(4, 'Atanasio Girardot'),
(4, 'Bolivar'),
(4, 'Camatagua'),
(4, 'Francisco Linares Alcentara'),
(4, 'Jose Angel Lamas'),
(4, 'Jose Felix Ribas'),
(4, 'Jose Rafael Revenga'),
(4, 'Libertador'),
(4, 'Mario Briceno Iragorry'),
(4, 'Ocumare de la Costa de Oro'),
(4, 'San Casimiro'),
(4, 'San Sebastien'),
(4, 'Santiago Mariño'),
(4, 'Santos Michelena'),
(4, 'Sucre'),
(4, 'Tovar'),
(4, 'Urdaneta'),
(4, 'Zamora');

CREATE TABLE parroquias (
	id_parroquia INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_municipio INT NOT NULL,
	nombre_parroquia VARCHAR(100) NOT NULL,
	creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO parroquias (id_municipio, nombre_parroquia) VALUES
(1, 'Pedro Jose Ovalles'),
(1, 'Joaquin Crespo'),
(1, 'Jose Casanova Godoy'),
(1, 'Madre Maria de San Jose'),
(1, 'Andres Eloy Blanco'),
(1, 'Los Tacarigua'),
(1, 'Las Delicias'),
(1, 'Choroni'),
(2, 'Bolivar'),
(3, 'Camatagua'),
(3, 'Carmen de Cura'),
(4, 'Santa Rita'),
(4, 'Francisco de Miranda'),
(4, 'Mosenor Feliciano Gonzelez'),
(5, 'Santa Cruz'),
(6, 'Jose Felix Ribas'),
(6, 'Castor Nieves Rios'),
(6, 'Las Guacamayas'),
(6, 'Pao de Zerate'),
(6, 'Zuata'),
(7, 'Jose Rafael Revenga'),
(8, 'Palo Negro'),
(8, 'San Martin de Porres'),
(9, 'El Limon'),
(9, 'Caña de Azucar'),
(10, 'Ocumare de la Costa'),
(11, 'San Casimiro'),
(11, 'Guiripa'),
(11, 'Ollas de Caramacate'),
(11, 'Valle Morin'),
(12, 'San Sebastian'),
(13, 'Turmero'),
(13, 'Arevalo Aponte'),
(13, 'Chuao'),
(13, 'Saman de Guere'),
(13, 'Alfredo Pacheco Miranda'),
(14, 'Santos Michelena'),
(14, 'Tiara'),
(15, 'Cagua'),
(15, 'Bella Vista'),
(16, 'Tovar'),
(17, 'Urdaneta'),
(17, 'Las Penitas'),
(17, 'San Francisco de Cara'),
(17, 'Taguay'),
(18, 'Zamora'),
(18, 'Magdaleno'),
(18, 'San Francisco de Asis'),
(18, 'Valles de Tucutunemo'),
(18, 'Augusto Mijares');

CREATE TABLE categorias_especialidades (
    id_categoria_especialidad INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL,
    id_creador INT NOT NULL,
    creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tipos_practica (
    id_tipo_practica INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    codigo VARCHAR(10) UNIQUE,
    id_creador INT NOT NULL,
    creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sistemas_corporales (
    id_sistema_corporal INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    id_creador INT NOT NULL,
    creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE especialidades (
    id_especialidad INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    id_categoria_especialidad INT NOT NULL,
    id_tipo_practica INT NOT NULL,
    id_sistema_corporal INT NOT NULL,
    descripcion TEXT NOT NULL,
    activa BOOLEAN DEFAULT TRUE NOT NULL,
    id_creador INT NOT NULL,
    creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subespecialidades (
    id_subespecialidad INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    descripcion TEXT NOT NULL,
    activa BOOLEAN DEFAULT TRUE NOT NULL,
    id_creador INT NOT NULL,
    creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE especialidades_requeridas_para_subespecialidades(
	id_especialidad_requerida_para_subespecialidad INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_especialidad INT NOT NULL,
    id_subespecialidad INT NOT NULL
);

CREATE TABLE grados_academicos(
	id_grado_academico INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre_grado VARCHAR(300) NOT NULL
);

INSERT INTO grados_academicos(nombre_grado) VALUES
('Médico General'),
('Médico Especialista'),
('Médico Subespecialista'),
('Maestria'),
('Doctorado'),
('Formación Continua');

CREATE TABLE deportes (
    id_deporte INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    es_olimpico BOOLEAN DEFAULT FALSE,
    popularidad VARCHAR(15) NOT NULL,
    deporte_nacional BOOLEAN NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO deportes (nombre, categoria, es_olimpico, popularidad, deporte_nacional) VALUES
('Béisbol', 'Colectivo', TRUE, 'Alta', TRUE),
('Fútbol Campo', 'Colectivo', TRUE, 'Alta', FALSE),
('Baloncesto', 'Colectivo', TRUE, 'Alta', FALSE),
('Voleibol', 'Colectivo', TRUE, 'Alta', FALSE),
('Softbol', 'Colectivo', FALSE, 'Media', FALSE),
('Atletismo', 'Individual', TRUE, 'Media', FALSE),
('Natación', 'Individual', TRUE, 'Media', FALSE),
('Boxeo', 'Individual', TRUE, 'Media', FALSE),
('Tae Kwon Do', 'Individual', TRUE, 'Media', FALSE),
('Judo', 'Individual', TRUE, 'Media', FALSE),
('Ciclismo', 'Individual', TRUE, 'Media', FALSE),
('Bolas Criollas', 'Colectivo', FALSE, 'Media', TRUE),
('Coleo', 'Individual', FALSE, 'Media', TRUE),
('Dominó', 'Por equipos', FALSE, 'Alta', FALSE),
('Surf', 'Individual', TRUE, 'Media', FALSE),
('Halterofilia', 'Individual', TRUE, 'Media', FALSE),
('Tenis', 'Individual', TRUE, 'Media', FALSE),
('Turf', 'Individual', FALSE, 'Alta', FALSE),
('Fútbol sala', 'Colectivo', FALSE, 'Alta', FALSE),
('Golf', 'Individual', TRUE, 'Baja', FALSE),
('Hipismo', 'Individual', FALSE, 'Media', FALSE),
('Pádel', 'Colectivo', FALSE, 'Media', FALSE),
('Ajedrez', 'Individual', TRUE, 'Media', FALSE),
('Squash', 'Individual', FALSE, 'Baja', FALSE),
('Hockey sobre patines', 'Colectivo', TRUE, 'Media', FALSE),
('Kárate', 'Individual', TRUE, 'Media', FALSE),
('Esgrima', 'Individual', TRUE, 'Baja', FALSE),
('Tiro con arco', 'Individual', TRUE, 'Baja', FALSE),
('Ráquetbol', 'Individual', FALSE, 'Baja', FALSE),
('Clavados', 'Individual', TRUE, 'Media', FALSE);

CREATE TABLE medicos(
	id_medico INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	cedula VARCHAR(15) NULL UNIQUE,
    rif VARCHAR(15) NULL UNIQUE,
    impre VARCHAR(20) NULL UNIQUE,
    correo VARCHAR(300) NULL,
    nacionalidad VARCHAR(500),
	nombres VARCHAR(300) NOT NULL,
    apellidos VARCHAR(300) NOT NULL,
    telefono_inicio VARCHAR(4) NOT NULL,
    telefono_restante VARCHAR(7) NOT NULL,
    id_parroquia INT NOT NULL,
    direccion TEXT NOT NULL,
    numero_colegio VARCHAR(100) NOT NULL UNIQUE,
    nombre_foto VARCHAR(100),
    id_grado_academico INT NULL,
    id_creador INT NOT NULL,
	creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE medicos_detalles(
	id_medico_detalles INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_medico INT NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    lugar_nacimiento VARCHAR(500) NOT NULL,
    tipo_sangre VARCHAR(15) NOT NULL,
    universidad_graduado VARCHAR(500) NOT NULL,
    fecha_egreso_universidad DATE NOT NULL,
    fecha_incripcion DATE NOT NULL,
    matricula_ministerio BIGINT NOT NULL,
    lugar_de_trabajo TEXT NOT NULL,
    estado INT NOT NULL
);

CREATE TABLE cambios_estados_medicos(
	id_cambio_estado_medico INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_medico INT NOT NULL,
    estado INT NOT NULL,
    fecha_colocacion DATE NULL,
    fecha_final DATE NULL,
    creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE documentos_medicos(
	id_documento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_medico INT NOT NULL,
    nombre_documento_original LONGTEXT NOT NULL,
    nombre_documento_directorio LONGTEXT NOT NULL,
    estado BOOLEAN NOT NULL
);

CREATE TABLE medicos_especialidades(
	id_medico_especialidad INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_medico INT NOT NULL,
    id_especialidad INT NOT NULL,
    universidad_obtenido VARCHAR(500) NOT NULL,
    fecha_obtencion DATE NOT NULL
);

CREATE TABLE medicos_subespecialidades(
	id_medico_subespecialidad INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_medico INT NOT NULL,
    id_subespecialidad INT NOT NULL,
    universidad_obtenido VARCHAR(500) NOT NULL,
    fecha_obtencion DATE NOT NULL
);

CREATE TABLE medicos_cursos(
	id_medico_curso INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_medico INT NOT NULL,
    nombre_curso VARCHAR(500) NOT NULL,
    universidad_obtenido VARCHAR(500) NOT NULL,
    fecha_obtencion DATE NOT NULL
);

CREATE TABLE medicos_diplomados(
	id_medico_diplomado INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_medico INT NOT NULL,
    nombre_diplomado VARCHAR(500) NOT NULL,
    universidad_obtenido VARCHAR(500) NOT NULL,
    fecha_obtencion DATE NOT NULL
);

CREATE TABLE medicos_deportes(
	id_medico_deporte INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_medico INT NOT NULL,
    id_deporte INT NOT NULL
);

/* RELACIONES ENTRE ENTIDADES*/
/* |  |  |  |  |  |  |  |  | */
/* |  |  |  |  |  |  |  |  | */
/* |  |  |  |  |  |  |  |  | */
/* V  V  V  V  V  V  V  V  V*/

ALTER TABLE auditorias ADD FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario);

ALTER TABLE municipios ADD FOREIGN KEY (id_estado) REFERENCES estados (id_estado);

ALTER TABLE parroquias ADD FOREIGN KEY (id_municipio) REFERENCES municipios (id_municipio);

ALTER TABLE especialidades ADD FOREIGN KEY (id_categoria_especialidad) REFERENCES categorias_especialidades (id_categoria_especialidad);

ALTER TABLE especialidades ADD FOREIGN KEY (id_tipo_practica) REFERENCES tipos_practica (id_tipo_practica);

ALTER TABLE especialidades ADD FOREIGN KEY (id_sistema_corporal) REFERENCES sistemas_corporales (id_sistema_corporal);

ALTER TABLE especialidades_requeridas_para_subespecialidades ADD FOREIGN KEY (id_especialidad) REFERENCES especialidades (id_especialidad);

ALTER TABLE especialidades_requeridas_para_subespecialidades ADD FOREIGN KEY (id_subespecialidad) REFERENCES subespecialidades (id_subespecialidad);

ALTER TABLE medicos ADD FOREIGN KEY (id_grado_academico) REFERENCES grados_academicos(id_grado_academico);

ALTER TABLE medicos ADD FOREIGN KEY (id_creador) REFERENCES usuarios(id_usuario);

ALTER TABLE medicos ADD FOREIGN KEY (id_parroquia) REFERENCES parroquias(id_parroquia);

ALTER TABLE medicos_detalles ADD FOREIGN KEY (id_medico) REFERENCES medicos(id_medico);

ALTER TABLE cambios_estados_medicos ADD FOREIGN KEY (id_medico) REFERENCES medicos(id_medico);

ALTER TABLE documentos_medicos ADD FOREIGN KEY (id_medico) REFERENCES medicos(id_medico);

ALTER TABLE medicos_especialidades ADD FOREIGN KEY (id_medico) REFERENCES medicos(id_medico);

ALTER TABLE medicos_especialidades ADD FOREIGN KEY (id_especialidad) REFERENCES especialidades(id_especialidad);

ALTER TABLE medicos_subespecialidades ADD FOREIGN KEY (id_medico) REFERENCES medicos(id_medico);

ALTER TABLE medicos_subespecialidades ADD FOREIGN KEY (id_subespecialidad) REFERENCES subespecialidades(id_subespecialidad);

ALTER TABLE medicos_cursos ADD FOREIGN KEY (id_medico) REFERENCES medicos(id_medico);

ALTER TABLE medicos_diplomados ADD FOREIGN KEY (id_medico) REFERENCES medicos(id_medico);

ALTER TABLE medicos_deportes ADD FOREIGN KEY (id_deporte) REFERENCES deportes(id_deporte);

ALTER TABLE medicos_deportes ADD FOREIGN KEY (id_medico) REFERENCES medicos(id_medico);