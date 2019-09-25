<?php

class ProfesorDao {

    private $pdo;

    public function __CONSTRUCT() {
        try {
            $db = Database::getInstance();
            $this->pdo = $db->getConnection();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function selectPorId($correo) {
        $stm = $this->pdo->prepare("SELECT * FROM profesor WHERE correo = ?");
        $stm->execute(array($correo));
        $p = $stm->fetch(PDO::FETCH_OBJ);
        
        if ($p != null) {
            $profesor = new Profesor();
            $profesor->crear($p->correo, $p->nombre, $p->apellidos, $p->telefono, $p->clave, $p->imagenAvatar);
            $res = $profesor;
        } else {
            $res = null;
        }
        return $res;
    }

    public function selectAll() {
        $stm = $this->pdo->prepare("SELECT * FROM profesor");
        $stm->execute();
        $profesoresPdo = $stm->fetchAll(PDO::FETCH_OBJ);
        $profesores = array();
        foreach ($profesoresPdo as $p) {
            $profesor = new Profesor();
            $profesor->crear($p->correo, $p->nombre, $p->apellidos, $p->telefono, $p->clave, $p->imagenAvatar);
            array_push($profesores, $profesor);
        }
        return $profesores;
    }

    public function insert(Profesor $data) {
        $sql = "INSERT INTO profesor (correo,nombre,apellidos,telefono,clave,imagenAvatar) VALUES (?, ?, ?, ?, ?,?)";
        
        $this->pdo->prepare($sql)->execute(
                array($data->getCorreo(), $data->getNombre(), $data->getApellidos(), $data->getTelefono(), $data->getClave(), $data->getImagenAvatar())
        );
        //$idProfesor = $this->pdo->lastInsertId();
        return $data->getCorreo();
    }

    public function update(Profesor $data) {
        $sql = "UPDATE profesor SET 
                    nombre = IFNULL(:nombre, nombre),
                    apellidos = IFNULL(:apellidos, apellidos),
                    telefono = IFNULL(:telefono, telefono),
                    clave = IFNULL(:clave, clave),
                    imagenAvatar = IFNULL(:imagenAvatar, imagenAvatar)
                WHERE correo=:correo";

        $update = $this->pdo->prepare($sql);
        $update->bindValue(':nombre', $data->getNombre(), PDO::PARAM_STR);
        $update->bindValue(':apellidos', $data->getApellidos(), PDO::PARAM_STR);
        $update->bindValue(':telefono', $data->getTelefono(), PDO::PARAM_STR);
        $update->bindValue(':clave', $data->getClave(), PDO::PARAM_STR);
        $update->bindValue(':imagenAvatar', $data->getImagenAvatar(), PDO::PARAM_STR);
        $update->bindValue(':correo', $data->getCorreo(), PDO::PARAM_STR);
        $update->execute();

        return $data->getCorreo();
    }

    public function delete($correo) {
        $stm = $this->pdo->prepare("DELETE FROM profesor WHERE correo = ?");
        $stm->execute(array($correo));
        return $correo;
    }

}
