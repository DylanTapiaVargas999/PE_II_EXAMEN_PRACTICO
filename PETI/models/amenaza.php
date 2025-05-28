<?php
require_once 'config/db.php';

class Amenaza {
    private $id_amenaza;
    private $amenaza;
    private $codigo;
    private $id_usuario;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Getters
    public function getIdAmenaza() {
        return $this->id_amenaza;
    }

    public function getAmenaza() {
        return $this->amenaza;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    // Setters
    public function setIdAmenaza($id) {
        $this->id_amenaza = intval($id);
        return $this;
    }

    public function setAmenaza($amenaza) {
        $this->amenaza = $this->db->real_escape_string(trim($amenaza));
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
        if (empty($this->amenaza) || empty($this->id_usuario)) {
            return false;
        }

        if (empty($this->codigo) && isset($_SESSION['plan_codigo'])) {
            $this->codigo = $_SESSION['plan_codigo'];
        }

        $sql = "INSERT INTO amenaza VALUES(
            NULL, 
            '{$this->getAmenaza()}', 
            '{$this->getCodigo()}', 
            {$this->getIdUsuario()}
        )";
        
        $guardado = $this->db->query($sql);
        
        if ($guardado) {
            $this->id_amenaza = $this->db->insert_id;
            return true;
        }
        return false;
    }

    public function obtenerPorUsuario($id_usuario) {
        $sql = "SELECT * FROM amenaza 
                WHERE id_usuario = $id_usuario 
                ORDER BY id_amenaza DESC";
        return $this->db->query($sql);
    }

    public function obtenerPorCodigo($codigo) {
        $sql = "SELECT * FROM amenaza 
                WHERE codigo = '{$this->db->real_escape_string($codigo)}'";
        return $this->db->query($sql);
    }

    public function eliminar() {
        $sql = "DELETE FROM amenaza 
                WHERE id_amenaza = {$this->getIdAmenaza()} 
                AND id_usuario = {$this->getIdUsuario()}";
        return $this->db->query($sql);
    }

    public function actualizar() {
        $sql = "UPDATE amenaza SET 
                amenaza = '{$this->getAmenaza()}'
                WHERE id_amenaza = {$this->getIdAmenaza()} 
                AND id_usuario = {$this->getIdUsuario()}";
        return $this->db->query($sql);
    }

    public function obtenerPorIdYUsuario($id_amenaza, $id_usuario) {
        $sql = "SELECT * FROM amenaza 
                WHERE id_amenaza = $id_amenaza 
                AND id_usuario = $id_usuario 
                LIMIT 1";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_object() : false;
    }
}
?>