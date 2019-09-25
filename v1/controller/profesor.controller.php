<?php

require_once 'model/dao/profesorDao.php';
require_once 'model/profesor.php';

require_once 'inc/imagenes.php';

class ProfesorController {

    private $model;
    private $token;

    public function __CONSTRUCT($token) {
        $this->token = $token;
        $this->model = new ProfesorDao();
    }

    private function leerPostProfesor($postData) {
        // put envia la info de otra forma
        if ($postData != NULL) {
            $_POST = $postData;
        }

        $profesor = new Profesor();

        if (isset($_POST["correo"]))
            $profesor->setCorreo($_POST["correo"]);
        if (isset($_POST["nombre"]))
            $profesor->setNombre($_POST["nombre"]);
        if (isset($_POST["apellidos"]))
            $profesor->setApellidos($_POST["apellidos"]);
        if (isset($_POST["telefono"]))
            $profesor->setTelefono($_POST["telefono"]);
        if (isset($_POST['clave']))
            $profesor->setClave($_POST["clave"]);

        return $profesor;
    }

    private function subirImagenesProfesor($correoProfesor) {

        $profesor = new Profesor();
        $profesor->setCorreo($correoProfesor);
        $ruta = 'assets/img/profesor/' . $correoProfesor;

        Imagenes::crearCarpetaSiNoExiste($ruta);

        if (!empty($_FILES["imagenAvatar"]["tmp_name"])) {
            $rutaSubida = Imagenes::subir("imagenAvatar", $ruta);
            $profesor->setImagenAvatar($rutaSubida);
        } else {
            $profesor->setImagenAvatar("default");
        }

        return $profesor;
    }

    public function seleccionar($correo) {

        try {
            if ($correo != "") {
                $resultado = $this->model->selectPorId($correo);
            } else {
                $resultado = $this->model->selectAll();
            }

            //paso las cadenas con las rutas a la imagen en base64
            if ($resultado != null) {
                if (is_array($resultado)) {
                    foreach ($resultado as $p) {
                        if ($p->getImagenAvatar() != "default") {
                            $p->setImagenAvatar(Imagenes::convertirImagenBase64($p->getImagenAvatar()));
                        }
                    }
                } else {
                    if ($resultado->getImagenAvatar() != "default") {
                        $resultado->setImagenAvatar(Imagenes::convertirImagenBase64($resultado->getImagenAvatar()));
                    }
                }
            }


            return $resultado;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function iniciarSesion() {
        $profesor = $this->leerPostProfesor(NULL);
        $profesorbd = $this->model->selectPorId($profesor->getCorreo());


        // si existe el profesor con ese correo
        if ($profesorbd != null) {
            // si la clave es correcta

            if (password_verify($profesor->getClave(), $profesorbd->getClave())) {
                // compruebo si necesita crear el hash
                if (password_needs_rehash($profesorbd->getClave(), PASSWORD_DEFAULT)) {
                    // hago el hash y guardo la nueva contraseña
                    $profesorbd->clave = password_hash($profesorbd->getClave(), self::HASH);
                    $this->model->update($profesorbd);
                }
                // paso la imagen a b64
                if ($profesorbd->getImagenAvatar() != "default" && $profesorbd->getImagenAvatar() != "") {
                    $profesorbd->setImagenAvatar(Imagenes::convertirImagenBase64($profesorbd->getImagenAvatar()));
                }

                // creo el token
                $unencodedArray = MiToken::creaToken("profesor", $profesorbd);

                echo json_encode(array(
                    'status' => "success",
                    'resp' => $profesorbd,
                    'token' => $unencodedArray
                ));
                
            } else {
                return null;
            }
        }
    }

    public function insertar() {
        try {

            // solo para subir imagenes cuando se edita un profesor
            if (isset($_POST["imagenesPut"])) {
                $profesorConImagenes = $this->subirImagenesProfesor($_POST["correo"]);
                $resultado = $this->model->update($profesorConImagenes);
            } else {

                // leo los datos del profesor
                $profesor = $this->leerPostProfesor(NULL);

                // encripto la clave
                $claveEncriptada = password_hash($profesor->getClave(), PASSWORD_DEFAULT);
                $profesor->setClave($claveEncriptada);

                // inserto el profesor
                $idProfesorInsertado = $this->model->insert($profesor);

                // subo imagenes y acutalizo el profesor
                $profesorConImagenes = $this->subirImagenesProfesor($idProfesorInsertado);
                $resultado = $this->model->update($profesorConImagenes);
            }
            return $resultado;
        } catch (Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function editar($postData) {
        try {
            $profesor = $this->leerPostProfesor($postData);

            if ($profesor->getClave()) {
                // encripto la clave
                $claveEncriptada = password_hash($profesor->getClave(), PASSWORD_DEFAULT);
                $profesor->setClave($claveEncriptada);
            }

            $resultado = $this->model->update($profesor);
            return $resultado;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function eliminar($correo) {
        try {
            $resultado = $this->model->delete($correo);

            // elimino la carpeta del profesor
            if ($resultado != null) {
                $rutaCarpetaAbsoluta = $_SERVER["DOCUMENT_ROOT"] . "/restapi/assets/img/profesor/" . $correo;
                Imagenes::eliminaCarpetaRecursivo($rutaCarpetaAbsoluta);
            }

            return $resultado;
        } catch (Exception $e) {
            return null;
        }
    }

}
