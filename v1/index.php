<?php

//echo getcwd()."   "; 
require_once "database/database.php";

//use Firebase\JWT;
// require php-jwt por no usar composer
require_once "../../php-jwt/src/BeforeValidException.php";
require_once "../../php-jwt/src/ExpiredException.php";
require_once "../../php-jwt/src/SignatureInvalidException.php";
require_once "../../php-jwt/src/JWT.php";
require_once "inc/token.php";


// permito el acceso a recursos desde un origen distinto
// habilito los metodos get, post, put y delete
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
//$headers = apache_request_headers();
//
//
// Obtengo el metodo, la tabla(controlador) y el id(si existe)
// [0] http://tfgapp.mooo.com/
// [1] restapi/
// [2] v1/
// [3] controlador[?idx=x]/
// [4] [accion] (iniciarSesion)
$metodo = $_SERVER['REQUEST_METHOD'];
//$ua = $_SERVER['HTTP_USER_AGENT'];
/* if(strstr($_SERVER['HTTP_USER_AGENT'],'iPod') ||   strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad') || strstr($_SERVER['HTTP_USER_AGENT'],'Android')){ 
  //do something...
  } */
$uri = $_SERVER['REQUEST_URI'];
$uri = explode("/", $uri);
if (isset($uri[3]) && $uri[3] != "") {
    $controlador = $uri[3];
    // si hay parametros get separarlos del controlador
    // alumno?idGrupo=1
    if (count($_GET) > 0) {
        $controladorConVariable = explode("?", $controlador);
        if (isset($controladorConVariable[1])) {
            $controlador = $controladorConVariable[0];
        }
    }
}
$id = "";
$accion = "";
if (isset($uri[4]) && $uri[4] != "") {

    if ($uri[4] == "iniciarSesion" || $uri[4] == "iniciarsesion") {
        $accion = "iniciarSesion";
    } else {
        $id = $uri[4];

        if (count($_GET) > 0) {
            $idConVariable = explode("?", $id);
            if (isset($idConVariable[1])) {
                $id = $idConVariable[0];
            }
        }
    }
}


//
// en la mayoria de hosting gratuitos solo puedo usar peticones get y post
// compruebo si estas peticiones tienen el metodo sobrecargado
$headers = apache_request_headers();
//$metodoSobrecargado = @sscanf($headers["X-HTTP-Method-Override"], '%s');
$metodoSobrecargado = @sscanf($headers["x-http-method-override"], '%s');
if ($metodoSobrecargado != null) {
    //echo "el metodo sobrecargado es " . $metodoSobrecargado[0];
    $metodo = $metodoSobrecargado[0];
}


// si no existe controlador o accion muestro error
if (!isset($controlador)) {
    imprimeError("40x", "No existe el objeto " + $controlador);
} else {

    // creo el controlador
    $controller = $controlador;
    require_once "controller/$controller.controller.php";
    $controller = ucwords($controller) . 'Controller';

    // si va a iniciar sesion
    if ($accion == "iniciarSesion" && $metodo == "POST" && ($controlador == "alumno" || $controlador == "profesor")) {
        $controller = new $controller(null);
        $res = $controller->$accion();

        // registro
        // si va a registrarse como alumno/jugador o profesor
    } else if (($controlador == "alumno" || $controlador == "profesor") && $metodo == "POST") {
        $controller = new $controller(null);
        $res = $controller->insertar();
        echo $res;
        // permito get de alumnos para comprobar si el correo existe
    } else if ($controlador == "alumno" && $metodo == "GET" && $id != "") {
        $controller = new $controller(null);
        $res = $controller->seleccionar($id);
        imprimeJson($res);
    } else {

        // compruebo el token
        // @ para que no muestre aviso (notice) si no existe el index authorization
        $jwt = @sscanf($headers["authorization"], 'Bearer %s');
        $token = MiToken::devuelveToken($jwt[0]);


        if ($token != null) {

            // creo el controlador pasandole el token
            $controller = new $controller($token);

            $nuevoToken = null;

            // llamo a la funcion segun el metodo
            switch ($metodo) {
                case 'GET':
                    $res = $controller->seleccionar($id);
                    break;

                case 'POST':
                    $res = $controller->insertar();
                    break;

                case 'PUT':
                    // al hacer put los parametros llegan de otra forma
                    // los saco de la entrada estandar y creo un array asociativo 
                    // lo paso al metodo editar para no tener que hacerlo en el editar de cada clase
                    $rawPostData = file_get_contents('php://input');
                    parse_str($rawPostData, $postData);
                    $res = $controller->editar($postData);

                    break;

                case 'DELETE':
                    $res = $controller->eliminar($id);

                    break;
            }



            switch ($metodo) {
                case "GET":
                    if ($nuevoToken != null) {
                        // a la respuesta le tengo que añadir el token
                        echo "{'status' : 'success','resp':" . json_encode($res) . ",'token':" . json_encode($nuevoToken) . "}";
                    } else {
                        // devuelvo respuesta normal sin token refrescado
                        imprimeJson($res);
                    }
                    break;
                case "POST":
                case "PUT":
                case "DELETE":
                    if ($nuevoToken != null) {
                        // a la respuesta le tengo que añadir el token
                        echo "{'status' : 'success','resp':" . $res . ",'token':" . json_encode($nuevoToken) . "}";
                    } else {
                        // devuelvo respuesta normal sin token refrescado
                        echo $res;
                    }
                    break;
            }
        } else {
            // token no valido
            // devolver 401 y volver a pedir iniciar sesion
            //echo "mi error 401";
            imprimeError("401", "El token no es valido, vuelve a iniciar sesión.");
        }
    }
}

function imprimeJson($res) {
    echo json_encode($res);
}

function imprimeError($error, $mensaje) {

    $responseError = array(
        "error" => $error,
        "mensaje" => $mensaje,
    );

    echo json_encode($responseError);
}

?>