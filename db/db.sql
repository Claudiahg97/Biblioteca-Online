CREATE DATABASE Biblioteca;

USE Biblioteca;

CREATE TABLE usuarios (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,    
    email VARCHAR(50) NOT NULL,    
    passw VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE libros {
    isbn INT NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(25) NOT NULL,
    autor VARCHAR(50) NOT NULL,
    categoria VARCHAR(25),
    fecha DATE,
    link VARCHAR(255),
    descripcion VARCHAR(255),
    email VARCHAR(50) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (emailUsuario) REFERENCES usuarios(email)
} 