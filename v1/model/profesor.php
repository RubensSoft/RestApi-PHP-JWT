<?php

class Profesor implements JsonSerializable {

    private $correo;
    private $nombre;
    private $apellidos;
    private $telefono;
    private $clave;
    private $imagenAvatar;

    function __construct() {
        
    }

    function crear($correo, $nombre, $apellidos, $telefono, $clave, $imagenAvatar) {
        $this->correo = $correo;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->telefono = $telefono;
        $this->clave = $clave;
        $this->imagenAvatar = $imagenAvatar;
    }
    
    function getImagenAvatar() {
        return $this->imagenAvatar;
    }

    function setImagenAvatar($imagenAvatar) {
        $this->imagenAvatar = $imagenAvatar;
    }

    
    function getCorreo() {
        return $this->correo;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellidos() {
        return $this->apellidos;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getClave() {
        return $this->clave;
    }

    function setCorreo($correo) {
        $this->correo = $correo;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellidos($apellidos) {
        $this->apellidos = $apellidos;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }

}
