

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

CREATE TABLE administrador (
	id int PRIMARY KEY AUTO_INCREMENT,
    correo VARCHAR(50) REFERENCES profesor(correo)
);

/*profesores
admin, clave: admin
javi, clae: javi
*/
INSERT INTO profesor (correo, nombre, clave, imagenAvatar) VALUES ("admin", "Administrador", "$2y$10$32WEtaR4R3ZoEi79AypxP.RDmfcODFHHKgIfPj1IUjmNYU3VaVT.e", "assets/img/profesor/admin/imagenAvatar.png'");
INSERT INTO profesor (correo, nombre, clave, imagenAvatar) VALUES ("javi", "Javier", "$2y$10$Hc6rsouHTlnbJAA2g9tkeOsCsLJpbAtiox3kmBuQ/Ye1Ww2r1aIJO", "assets/img/profesor/javi/imagenAvatar.png'");


CREATE TABLE puntoMapa (
        id int PRIMARY KEY AUTO_INCREMENT,
    latitud VARCHAR(50),
    longitud VARCHAR(50)
);


CREATE TABLE centroEducativo (
	id int PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(105),
    provincia VARCHAR(50),
    poblacion VARCHAR(50),
    calle VARCHAR(105),
    imagen VARCHAR(100),
    id_puntoMapa int REFERENCES puntoMapa(id)
);


CREATE TABLE clase ( 
            id int PRIMARY KEY AUTO_INCREMENT, 
    curso VARCHAR(7),  
    grupo CHARACTER, 
    publica BOOLEAN,  
    imagenAvatar VARCHAR(100),
        id_profesor VARCHAR(50) REFERENCES profesor(correo),
        id_centroEducativo int REFERENCES centroEducativo(id)
);


CREATE TABLE grupo (
            id int PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(20),
    imagenAvatar VARCHAR(100),
    puntos INT,
        id_clase int REFERENCES clase(id)
);


CREATE TABLE salubria (
    id int PRIMARY KEY AUTO_INCREMENT,
    id_puntoMapa int REFERENCES puntoMapa(id),
    id_clase int REFERENCES clase(id)

);

CREATE TABLE entrenamiento (
    id int PRIMARY KEY AUTO_INCREMENT,
    id_puntoMapa int REFERENCES puntoMapa(id),
    id_clase int REFERENCES clase(id)
);



CREATE TABLE elementoMagico (
    id INT PRIMARY KEY AUTO_INCREMENT, 
    nombre VARCHAR(50)
);

INSERT INTO elementoMagico (nombre) VALUES ("agua");
INSERT INTO elementoMagico (nombre) VALUES ("fuego");
INSERT INTO elementoMagico (nombre) VALUES ("tierra");
INSERT INTO elementoMagico (nombre) VALUES ("aire");
INSERT INTO elementoMagico (nombre) VALUES ("electrico");
INSERT INTO elementoMagico (nombre) VALUES ("hielo");
INSERT INTO elementoMagico (nombre) VALUES ("planta");
INSERT INTO elementoMagico (nombre) VALUES ("roca");



CREATE TABLE habilidad (
        id int PRIMARY KEY AUTO_INCREMENT,
    nivel int,
    vida int,
    vidaTotal int,
    ataqueFisico int,
    ataqueMagico int,
    defensaFisica int,
    defensaMagica int,
    velocidad int,
    finta int,
    puntosElementoMagicoAprendiendo int, 
    id_elementoMagicoAprendiendo INT REFERENCES elementoMagico(id),
    id_elementoMagicoEquipado INT REFERENCES elementoMagico(id)

);


CREATE TABLE habilidadElementoMagico (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_habilidad INT REFERENCES habilidad(id),
    id_elementoMagico INT REFERENCES elementoMagico(id)
);



CREATE TABLE alumno (
        /*id int PRIMARY KEY AUTO_INCREMENT,*/
        correo VARCHAR(100) PRIMARY KEY,
    nombre VARCHAR(20),
    apellidos varchar(50),
    sexo tinyint(4),
    fechaNacimiento VARCHAR(20),
    fechaRegistro VARCHAR(20),
    clave VARCHAR(150),
    imagenAvatar VARCHAR(100),
    imagenBatalla VARCHAR(100),
    puntos INT,
    puntosClase INT,
    oro INT, 
        id_habilidad int REFERENCES habilidad(id),
        id_puntoMapa int REFERENCES puntoMapa(id),
        id_clase int REFERENCES clase(id), 
        id_grupo int REFERENCES grupo(id)
);



CREATE TABLE frasesHabitosSaludables(
    id INT PRIMARY KEY AUTO_INCREMENT,
    frase varchar(300)
);


CREATE TABLE historia (
        id INT PRIMARY KEY AUTO_INCREMENT,
    textoInicio VARCHAR(250),
    imagenInicio VARCHAR(100),	
    textoFin VARCHAR(250),
    imagenFin VARCHAR(100)
);


CREATE TABLE recorrido (
	id int PRIMARY KEY AUTO_INCREMENT,
    publico boolean,
    nombre VARCHAR(50),
    tipoDificultadDinamica varchar(20),
    activo BOOLEAN,
    imagen VARCHAR(100),
    fechaHoraRegistro VARCHAR(20),
    distanciaTotal VARCHAR(20),
    tiempoTotal VARCHAR(20),
        id_profesor VARCHAR(50) REFERENCES profesor(correo), 
        id_clase int REFERENCES clase(id) 
);


CREATE TABLE puntoControl (
	id INT PRIMARY KEY AUTO_INCREMENT,
        fechaRegistro VARCHAR(20),
        idDemonio int, /*1 alien, 2 devil, ...*/ 
        id_profesor VARCHAR(50) REFERENCES profesor(correo), /*creador*/
        id_puntoMapa int REFERENCES puntoMapa(id),
        id_habilidad int REFERENCES habilidad(id),
        id_historia int REFERENCES historia(id),
        id_frasesHabitosSaludables int REFERENCES frasesHabitosSaludables(id),

        distanciaDesdePuntoAnterior VARCHAR(20),
        tiempoDesdePuntoAnterior VARCHAR(20),

        id_recorrido int REFERENCES recorrido(id),
        orden int,

        dificultad int,
        elementoMagico int
        
);


create table alumnoCompletaRecorrido (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_recorrido int REFERENCES recorrido(id),
    id_grupo int REFERENCES grupo(id),
    correo varchar(100) references alumno(correo),
    nombreApellidos varchar(71),
    tiempo int,
    fecha DATETIME
);



INSERT INTO frasesHabitosSaludables (frase) VALUES ("A todo el mundo le gusta estar en la cama. Duerme al menos 8 horas durante la noche para ser el mejor.");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Los que mas ligan son los que se duchan todos los días.");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Si tienes hambre comete una fruta y no dulces. Déjale los dulces a los vagos que quieran enfermarse y que se le caigan los dientes.");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("A que no eres capaz de hacer que alguien haga ejercicio contigo, hoy, durante al menos 1 hora.");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Seguro que hoy consigues comer mas verduras. Confío en ti!");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Vete a jugar a la calle, al menos 1 hora. A que no lo consigues...");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Vete a jugar a algun deporte. Al menos 1 hora. Eso hacen Messi y C.Ronaldo cada día.");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("No seas como ese mequetrefe, ten una botella de agua a mano siempre. ");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Hoy vas a comprarte una manzana en vez de una palmera de chocolate. Confío en que lo harás.");
/*INSERT INTO frasesHabitosSaludables (frase) VALUES ("Mi tio fumaba tanto que tuvieron que operarle de cáncer de pulmón.");*/
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Es bueno tener frutos secos para comer cuando te entra hambre.");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Eres tan crack que seguro te pediste agua en vez de una gaseosa!");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("No es muy inteligente fumar, si fumas te apesta el aliento y ademas es caro.");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Si quieres lastimar a alguien dale de beber coca-cola.");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Lo mejor sería que cada dia puedas jugar 1 hora al día por lo menos.");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Si has terminado de comer cepíllate los dientes, no seas guarro!!!");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Lo ideal es tomes 8 vasos de agua al día mas o menos.");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("¿Que es mejor para estar sano un vaso de agua o un refresco?");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("Seguro que eres tan vago que ni comes las 5 frutas que deberías comer en 1 día. Te apuesto lo que quieras que no lo haces.");
INSERT INTO frasesHabitosSaludables (frase) VALUES ("¿Está mal tener en el desayuno un huevo cocido?");
