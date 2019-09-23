# RestApi-PHP-JWT

API en PHP para recibir peticiones HTTP (GET,POST,PUT,DELETE) desde cualquier aplicación.

Esta API es la que uso en mi proyecto para la aplicación "Runapp" y por lo tanto se puede simplificar quitando objetos y adaptar para usar en otros proyecto. Para más información de este proyecto ver: 
https://tfgapp.000webhostapp.com


- GET Devuelve los datos en JSON.
- POST,PUT,DELETE devuelven el identificador del objeto insertado, modificado o eliminado.
- Permite subir imagenes, se almacenan en el servidor y se devuelven como texto base 64.
- Uso de controladores y DAO, para separar el control de los datos del acceso.
- Uso de PDO (PHP) para usar consultas parametrizadas y aumentar la seguridad de la la base de datos a la que accede la RestApi.
- Permite peticiones GET y POST con el método sobrecargado, debido a que la mayoria de hosting gratuitos sólo permiten peticiones GET y POST.
- Uso una implementación de JWT (JSON Web Token) para generar un token cuando el usuario inicia sesión, este token será enviado al usuario y lo reenviará en cada petición, de esta forma podemos saber que usuario ha realizado cada petición. 
En la página jwt.io podemos encontrar varias implementaciones para diferentes lenguajes, he usado la implementación PHP creada por Brent Shaffer (https://github.com/bshaffer), en concreto: https://github.com/firebase/php-jwt

- Para editar un objeto uso la petición PUT, pero para subir imagenes al servidor hay que hacerlo con POST, por lo tanto para editar imagenes realizo una petición POST con el campo "imagenesPUT" para saber que en realidad estoy editando.



## Comenzando 🚀

Instrucciones para obtener una copia del proyecto en funcionamento en tu máquina local. 
Mira **Deployment** para conocer como desplegar el proyecto.


### Pre-requisitos 📋

Un servidor web local como XAMPP o instalar por tu cuenta: 
Apache 2.4.41, MariaDB 10.4.6, PHP 7.1.32, phpMyAdmin 4.9.0.1, OpenSSL 1.0.2.
(https://www.apachefriends.org/es/download.html)

Puedes realizar peticiones Get escribiendo directamente en la ruta del navegador,
o instalar una extensión para Chrome como POSTMAN (https://www.getpostman.com/) para realizar peticiones HTTP.
Obviamente, también puedes realizar las peticiones directamente desde la aplicación/es que usarán esta RestApi.
Por ejemplo, desde una aplicación web puedes usar peticiones ajax usando JQuery, o desde una aplicación móvil Android
puedes usar la librería Loopj.



### Instalación 🔧

Una vez descargado el proyecto:
-Arranca los servicios de Apache y MySQL/MariaDB desde el panel de Control de Xampp.

- Crea la base de datos, ejecutando el script "backup.sql" en phpmyadmin.
Como verás hemos creado un usuario con una clave ya codificada, para poder usar la RestApi y crear nuevos usuarios y/u otros objetos.
El usuario creado es admin y su clave es admin.
Esto lo hago así, ya que la RestAPi solo recibe peticiones de un usuario que se haya identificado previamente (para protegerla de peticiones externas), por lo que no se pueden crear usuarios (realizando peticiones http) sin antes habernos identificado.

-Edita el archivo Database/database.php
Escribe tu servidor, base de datos, usuario y contraseña.
$this->pdo = new PDO('mysql:host=SERVIDOR;dbname=NOMBRE_BASE_DATOS;charset=utf8', 'USUARIO_BASE_DATOS', 'CONTRASEÑA');

-Edita el archivo inc/token.php, en la línea: 
define('SERVER', "RUTA_SERVIDOR");
escribir la ruta del servidor (http://localhost 0 https://webhosting.subdominio.com)


## Ejecutando las pruebas ⚙️

Para realizar una petición sencilla y obtener el resultado, podemos escrbir en el navegador 
localhost/restapi/v1/profesor/admin y veremos el resultado: 
{"error":"401","mensaje":"El token no es válido, vuelve a iniciar sesión."}


NOTA ADICIONAL:
Auque anteriormente he mencionado que no es posible realizar ninguna petición sin iniciar sesión, existe una única petición que se puede realizar, el GET de alumnos, para comprobar si el alumno existe en el sistema a la hora de registrar un nuevo alumno.
Ejemplo de petición Get de alumno: localhost/restapi/v1/alumno/correo
Para el pforesor (administrador) no he realizado el posible get para comprobar si existe. 


Iniciar Sesión usando POSTMAN

Realizar una petición con el token recibido




## Deployment 📦

Para desplegar este proyecto, podemos subirlo a un hosting y usarlo desde ahí en lugar de en local.
Debemos crear la base de datos en el hosting y, al igual que en local, cambiar en el archivo database.php los datos del servidor, nombre de la base de datos, usuario y clave.
Normalmente, el hoting nos crea un usuario ftp para subir los archivos al servidor.

Una vez subido podemos usar de nuevo POTMAN.


## Construido con 🛠️

* PHP usando PDO y DAO
* SQL


## Contribuyendo 🖇️
## Wiki 📖
## Versionado 📌


## Autores ✒️

* **José Rubén Castro Soriano** - *Trabajo Inicial* - [RubensSoft](https://github.com/RubensSoft)

También puedes mirar la lista de todos los [contribuyentes](https://github.com/RestApi-PHP-JWT/contributors) quíenes han participado en este proyecto. 

## Licencia 📄

Este proyecto está bajo la Licencia GNU (General Public License) - mira el archivo [LICENSE.md](LICENSE.md) para detalles.

## Expresiones de Gratitud 🎁

* Gracias a Brent Shaffer (https://github.com/bshaffer)
por su implementación JWT en PHP (https://github.com/firebase/php-jwt).






