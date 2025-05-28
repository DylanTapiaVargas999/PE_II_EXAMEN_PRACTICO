<?php
require_once 'config/db.php';

class Fortaleza {
    private $id_fortaleza;
    private $fortaleza;
    private $codigo;
    private $id_usuario;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Getters
    public function getIdFortaleza() {
        return $this->id_fortaleza;
    }

    public function getFortaleza() {
        return $this->fortaleza;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    // Setters
    public function setIdFortaleza($id) {
        $this->id_fortaleza = intval($id);
        return $this;
    }

    public function setFortaleza($fortaleza) {
        $this->fortaleza = $this->db->real_escape_string(trim($fortaleza));
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
        if (empty($this->fortaleza) || empty($this->id_usuario)) {
            return false;
        }

        if (empty($this->codigo) && isset($_SESSION['plan_codigo'])) {
            $this->codigo = $_SESSION['plan_codigo'];
        }

        $sql = "INSERT INTO fortaleza VALUES(
            NULL, 
            '{$this->getFortaleza()}', 
            '{$this->getCodigo()}', 
            {$this->getIdUsuario()}
        )";
        
        $guardado = $this->db->query($sql);
        
        if ($guardado) {
            $this->id_fortaleza = $this->db->insert_id;
            return true;
        }
        return false;
    }

    public function obtenerPorUsuario($id_usuario) {
        $sql = "SELECT * FROM fortaleza 
                WHERE id_usuario = $id_usuario 
                ORDER BY id_fortaleza DESC";
        return $this->db->query($sql);
    }

    public function obtenerPorCodigo($codigo) {
        $sql = "SELECT * FROM fortaleza 
                WHERE codigo = '{$this->db->real_escape_string($codigo)}'";
        return $this->db->query($sql);
    }

    public function eliminar() {
        $sql = "DELETE FROM fortaleza 
                WHERE id_fortaleza = {$this->getIdFortaleza()} 
                AND id_usuario = {$this->getIdUsuario()}";
        return $this->db->query($sql);
    }
    // Agregar estos métodos
public function actualizar() {
    $sql = "UPDATE fortaleza SET 
            fortaleza = '{$this->getFortaleza()}'
            WHERE id_fortaleza = {$this->getIdFortaleza()} 
            AND id_usuario = {$this->getIdUsuario()}";
    return $this->db->query($sql);
}

public function obtenerPorIdYUsuario($id_fortaleza, $id_usuario) {
    $sql = "SELECT * FROM fortaleza 
            WHERE id_fortaleza = $id_fortaleza 
            AND id_usuario = $id_usuario 
            LIMIT 1";
    $resultado = $this->db->query($sql);
    return $resultado ? $resultado->fetch_object() : false;
}
}
?>