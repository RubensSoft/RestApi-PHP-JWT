<?php

/*
 * Clase para devolver los errores que pueden suceder al realizar peticiones a la restapi
 */
class responseError {

    public function __construct($error, $mensaje) {
        $res = array(
            "error" => $error,
            "mensaje" => $mensaje,
        );
    }

}
