CREATE DATABASE IF NOT EXISTS alumnos_db;
USE alumnos_db;
CREATE TABLE IF NOT EXISTS alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(100),
    fecha_nacimiento DATE
);
