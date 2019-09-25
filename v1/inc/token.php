<?php

define('SERVER', "RUTA_SERVIDOR");
define('SECRET_KEY', '*****');  /// secret key can be a random string and keep in secret from anyone
define('ALGORITHM', 'HS256');

class MiToken {

    public static function devuelveToken($token) {
        try {

            $secretKey = base64_decode(SECRET_KEY);
            $DecodedDataArray = JWT::decode($token, $secretKey, array(ALGORITHM));
            $token = json_decode(json_encode($DecodedDataArray));

            return $token;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function creaToken($tipo, $usuario) {

        $tokenId = base64_encode(mcrypt_create_iv(32));
        $issuedAt = time();
        $notBefore = $issuedAt;
        // a la hora de expiracion no le añado nada para poder usar el token directamente 
        // si le pongo mas tiempo tengo que esperar para poder usar el token
        $expire = $notBefore + 7200; // añado 1 hora
        $serverName = SERVER;

        // crea el array data para usuario
        // almaceno los datos del usuario para identificarlo 
        $datos = [
            'id' => $usuario->getCorreo(),
            'name' => $usuario->getNombre(),
            'tipo' => $tipo
        ];

        // almaceno algunos datos para saber lo que puede editar
        // y no tener que consultar en la base de datos para cada operacion
        if ($tipo == "alumno") {
            $datos['idHabilidad'] = $usuario->getIdHabilidad();
            $datos['idPuntoMapa'] = $usuario->getIdPuntoMapa();
            $datos['idClase'] = $usuario->getIdClase();
            $datos['idGrupo'] = $usuario->getidGrupo();
        }

        $data = [
            'iat' => $issuedAt, // cuando se genero el token
            'jti' => $tokenId, // identificador del token
            'iss' => $serverName, // servidor
            'nbf' => $notBefore, // se podra usar no antes de
            'exp' => $expire, // cuando expira
            'data' => $datos
        ];
        $secretKey = base64_decode(SECRET_KEY);


        $jwt = JWT::encode(
                        $data, //Data to be encoded in the JWT
                        $secretKey, // The signing key
                        ALGORITHM
        );
        $unencodedArray = ['jwt' => $jwt];

        return $unencodedArray;
    }

    public static function compruebaSiRefrescarToken($tokenDevuelto) {
        /*
          Si el token expira en más de X minutos, considera que no es necesario renovarlo. Devuelve el mismo token.
          Si el token expiró hace menos de X minutos, genera un nuevo token y lo devuelve igual que en el procedimiento de login
          Si el token no es válido o expiró hace más tiempo que el umbral de tolerancia, devuelves un header 401, que tu front debe interpretar como: "redirigir al login".
         */

        // si el token es valido (si no, no lo puedo desencriptat)
        // y caduca en menos de 10 minutos (y como sigo usandolo lo renuevo)
        if ($tokenDevuelto->exp - 7200 > time()) {
            //if (time()+(60*10)  > $tokenDevuelto->exp)
            // creo nuevo token
            $usuario;

            if ($tokenDevuelto->data->tipo == "profesor") {
                $usuario = new Profesor();
                $usuario->setCorreo($tokenDevuelto->data->correo);
                $usuario->setNombre($tokenDevuelto->data->nombre);
            }

            if ($tokenDevuelto->data->tipo == "alumno") {
                $usuario = new Alumno();

                $usuario->setCorreo($tokenDevuelto->data->correo);
                $usuario->setNombre($tokenDevuelto->data->nombre);

                $usuario->setIdHabilidad($tokenDevuelto->data->idHabilidad);
                $usuario->setIdPuntoMapa($tokenDevuelto->data->idPuntoMapa);
                $usuario->setIdClase($tokenDevuelto->data->idClase);
                $usuario->setIdGrupo($tokenDevuelto->data->idGrupo);
            }

            $res = $this->creaToken($tokenDevuelto->data->tipo, $usuario);
            return $res;
        }

        return null;
    }

}
