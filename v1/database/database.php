<?php

class Database {

    private static $_instance;
    private $pdo;

    /**
     * Devuelve la instancia de esta clase
     * si no existe la crea y la devuelve
     *  
     * @return Database instancia de esta clase
     */
    public static function getInstance() {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Crea la conexion a la base de datos
     */
    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=NOMBRE_BASE_DATOS;charset=utf8', 'USUARIO_BASE_DATOS', 'CONTRASEÑA');
        
        // Desactivar preparaciones emuladas. Esto asegura que obtenga declaraciones preparadas.
        // y evitamos inyeccion sql
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Devuelve la conexion a la bd
     *  
     * @return PDO conexion a la bd
     */
    public function getConnection() {
        return $this->pdo;
    }
    
}

?>