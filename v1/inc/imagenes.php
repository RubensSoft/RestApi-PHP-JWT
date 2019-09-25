<?php

class Imagenes {

    public static function crearCarpetaSiNoExiste($ruta) {
        $rutaCarpetaAbsoluta = $_SERVER["DOCUMENT_ROOT"] . '/restapi/' . $ruta;
        if (!file_exists($rutaCarpetaAbsoluta)) {
            mkdir($rutaCarpetaAbsoluta, 0777, true);
        }
    }

    public static function eliminaCarpetaRecursivo($rutaAbsoluta) {

        if (file_exists($rutaAbsoluta)) {
            $files = scandir($rutaAbsoluta); // Devuelve un vector con todos los archivos y directorios
            foreach ($files as $f) {
                if (is_file($rutaAbsoluta . "/" . $f)) {
                    if (unlink($rutaAbsoluta . "/" . $f)) {
                        
                    }
                }
            }
            //borramos el directorio
            rmdir($rutaAbsoluta);
        }
    }

    public static function subir($nombrePost, $ruta) {

        // saco la extension de la imagen y le cambio el nombre
        $path_parts = pathinfo($_FILES[$nombrePost]['name']);
        $fileExtension = $path_parts['extension'];
        $nombreImagen = $nombrePost . "." . $fileExtension;

        // saco la ruta de subida y la ruta relativa para guardarla en bd
        $rutaCarpetaAbsoluta = $_SERVER["DOCUMENT_ROOT"] . '/restapi/' . $ruta;
        $rutaGuardarBD = $ruta . "/" . $nombreImagen;
        $rutaDestinoSubida = $rutaCarpetaAbsoluta . "/" . $nombreImagen;

        // elimino la image si ya existe
        if (file_exists($rutaDestinoSubida)) {
            unlink($rutaDestinoSubida);
        }

     
        //  Optimización de imagenes 
        // 
        // Esta parte de código ha sido creada por el autor que se indica a continuación, 
        // y es una adaptación realizada por mi, también se da un enlace para ver el original.
        // 
        // creada por Alber
        // 2018
        // Administrador de datoweb
        // https://datoweb.com/post/2921/como-redimensionar-o-reducir-peso-de-imagenes-con-php

        $max_ancho = 400;
        $max_alto = 400;

        if (isset($_FILES[$nombrePost])) {

            //Funciones optimizar imagenes
            //Ruta de la carpeta donde se guardarán las imagenes
            $patch = $ruta;

            // para sacar el tipo de imagen cuando viene de android
            $info = getimagesize($_FILES[$nombrePost]['tmp_name']);
            $type = $info['mime'];

            if ($_FILES[$nombrePost]['type'] == 'image/png' || $_FILES[$nombrePost]['type'] == 'image/jpeg' || $_FILES[$nombrePost]['type'] == 'image/gif' || $type = 'image/png' || $type = 'image/jpeg' || $type == 'image/gif') {

                $medidasimagen = getimagesize($_FILES[$nombrePost]['tmp_name']);
                //Si las imagenes tienen una resolución y un peso aceptable se suben tal cual
                if ($medidasimagen[0] < $max_ancho && $_FILES[$nombrePost]['size'] < 1500) { // 400 15kb
                    if (move_uploaded_file($_FILES[$nombrePost]['tmp_name'], $rutaDestinoSubida)) {
                        $resultado = $rutaGuardarBD;
                    } else {
                        $resultado = "default";
                    }
                } else {

                    //Si no, se generan nuevas imagenes optimizadas
                    $nombrearchivo = $_FILES[$nombrePost]['name'];

                    //Redimensionar
                    $rtOriginal = $_FILES[$nombrePost]['tmp_name'];

                    if ($_FILES[$nombrePost]['type'] == 'image/jpeg' || $type == 'image/jpeg') {
                        $original = imagecreatefromjpeg($rtOriginal);
                    } else if ($_FILES[$nombrePost]['type'] == 'image/png' || $type == 'image/png') {
                        $original = imagecreatefrompng($rtOriginal);
                    } else if ($_FILES[$nombrePost]['type'] == 'image/gif' || $type == 'image/gif') {
                        $original = imagecreatefromgif($rtOriginal);
                    }


                    list($ancho, $alto) = getimagesize($rtOriginal);

                    $x_ratio = $max_ancho / $ancho;
                    $y_ratio = $max_alto / $alto;


                    if (($ancho <= $max_ancho) && ($alto <= $max_alto)) {
                        $ancho_final = $ancho;
                        $alto_final = $alto;
                    } elseif (($x_ratio * $alto) < $max_alto) {
                        $alto_final = ceil($x_ratio * $alto);
                        $ancho_final = $max_ancho;
                    } else {
                        $ancho_final = ceil($y_ratio * $ancho);
                        $alto_final = $max_alto;
                    }

                    $lienzo = imagecreatetruecolor($ancho_final, $alto_final);

                    imagecopyresampled($lienzo, $original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);


                    if ($_FILES[$nombrePost]['type'] == 'image/jpeg' || $type == 'image/jpeg') {
                        imagejpeg($lienzo, $rutaDestinoSubida);
                        $resultado = $rutaGuardarBD;
                    } else if ($_FILES[$nombrePost]['type'] == 'image/png' || $type == 'image/png') {
                        imagepng($lienzo, $rutaDestinoSubida);
                        $resultado = $rutaGuardarBD;
                    } else if ($_FILES[$nombrePost]['type'] == 'image/gif' || $type == 'image/gif') {
                        imagegif($lienzo, $rutaDestinoSubida);
                        $resultado = $rutaGuardarBD;
                    } else {
                        $resultado = "default";
                    }
                }
            } else {
                echo "fichero no soportado";
                $resultado = "default";
            }
        } else {
            echo " no habia ninguna imagen ";
        }

        //
        // Fin de la parte de Optimización de imagenes 
        //
        
        return $resultado;
    }

    public static function convertirImagenBase64($rutaImagen) {
        $rutaAbsoluta = $_SERVER["DOCUMENT_ROOT"] . '/restapi/' . $rutaImagen;
        $type = pathinfo($rutaAbsoluta, PATHINFO_EXTENSION);
        $data = file_get_contents($rutaAbsoluta);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

}
