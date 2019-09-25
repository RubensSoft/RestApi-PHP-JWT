

CREATE DATABASE tfg_bd;
USE tfg_bd;

CREATE TABLE profesor (
	correo VARCHAR(50) PRIMARY KEY,
    nombre VARCHAR(20),
    apellidos VARCHAR(30),
    telefono VARCHAR(9),
    clave VARCHAR(150),
    imagenAvatar VARCHAR(100)
);

/*profesor
admin, clave: admin
*/
INSERT INTO profesor (correo, nombre, clave, imagenAvatar) VALUES ("admin", "Administrador", "$2y$10$32WEtaR4R3ZoEi79AypxP.RDmfcODFHHKgIfPj1IUjmNYU3VaVT.e", "assets/img/profesor/admin/imagenAvatar.png'");