<?php
require_once 'config/db.php';

class Debilidad {
    private $id_debilidad;
    private $debilidad;
    private $codigo;
    private $id_usuario;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Getters
    public function getIdDebilidad() {
        return $this->id_debilidad;
    }

    public function getDebilidad() {
        return $this->debilidad;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    // Setters
    public function setIdDebilidad($id) {
        $this->id_debilidad = intval($id);
        return $this;
    }

    public function setDebilidad($debilidad) {
        $this->debilidad = $this->db->real_escape_string(trim($debilidad));
        return $this;
    }

    public function setCodigo($codigo) {
        $this->codigo = $this->db->real_escape_string(trim($codigo));
        return $this;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = intval($id_usuario);
        return $this;
    }

    // Métodos CRUD
    public function guardar() {
        if (empty($this->debilidad) || empty($this->id_usuario)) {
            return false;
        }

        if (empty($this->codigo) && isset($_SESSION['plan_codigo'])) {
            $this->codigo = $_SESSION['plan_codigo'];
        }

        $sql = "INSERT INTO debilidad VALUES(
            NULL, 
            '{$this->getDebilidad()}', 
            '{$this->getCodigo()}', 
            {$this->getIdUsuario()}
        )";
        
        $guardado = $this->db->query($sql);
        
        if ($guardado) {
            $this->id_debilidad = $this->db->insert_id;
            return true;
        }
        return false;
    }

    public function obtenerPorUsuario($id_usuario) {
        $sql = "SELECT * FROM debilidad 
                WHERE id_usuario = $id_usuario 
                ORDER BY id_debilidad DESC";
        return $this->db->query($sql);
    }

    public function obtenerPorCodigo($codigo) {
        $sql = "SELECT * FROM debilidad 
                WHERE codigo = '{$this->db->real_escape_string($codigo)}'";
        return $this->db->query($sql);
    }

    public function eliminar() {
        $sql = "DELETE FROM debilidad 
                WHERE id_debilidad = {$this->getIdDebilidad()} 
                AND id_usuario = {$this->getIdUsuario()}";
        return $this->db->query($sql);
    }

    // Agregar estos métodos
public function actualizar() {
    $sql = "UPDATE debilidad SET 
            debilidad = '{$this->getDebilidad()}'
            WHERE id_debilidad = {$this->getIdDebilidad()} 
            AND id_usuario = {$this->getIdUsuario()}";
    return $this->db->query($sql);
}

public function obtenerPorIdYUsuario($id_debilidad, $id_usuario) {
    $sql = "SELECT * FROM debilidad 
            WHERE id_debilidad = $id_debilidad 
            AND id_usuario = $id_usuario 
            LIMIT 1";
    $resultado = $this->db->query($sql);
    return $resultado ? $resultado->fetch_object() : false;
}
}
?>