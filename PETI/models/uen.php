<?php
require_once 'config/db.php';

class Uen {
    private $id_uen;
    private $uen;
    private $codigo;
    private $id_usuario;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Getters
    public function getIdUen() { return $this->id_uen; }
    public function getUen() { return $this->uen; }
    public function getCodigo() { return $this->codigo; }
    public function getIdUsuario() { return $this->id_usuario; }

    // Setters
    public function setIdUen($id_uen) {
        $this->id_uen = intval($id_uen);
        return $this;
    }

    public function setUen($uen) {
        $this->uen = $this->db->real_escape_string(trim($uen));
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

    // CRUD Operations
    public function guardar() {
        if (empty($this->uen) || empty($this->codigo) || empty($this->id_usuario)) {
            return false;
        }

        $sql = "INSERT INTO uen VALUES(NULL, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi', $this->uen, $this->codigo, $this->id_usuario);
        
        if ($stmt->execute()) {
            $this->id_uen = $stmt->insert_id;
            return true;
        }
        return false;
    }

    public function obtenerPorUsuarioYPlan($id_usuario, $codigo_plan) {
        $sql = "SELECT * FROM uen WHERE id_usuario = ? AND codigo = ? ORDER BY id_uen DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is', $id_usuario, $codigo_plan);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function obtenerUno($id_uen, $id_usuario) {
        $sql = "SELECT * FROM uen WHERE id_uen = ? AND id_usuario = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $id_uen, $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_object();
    }

    public function actualizar() {
        $sql = "UPDATE uen SET uen = ?, codigo = ? WHERE id_uen = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssii', $this->uen, $this->codigo, $this->id_uen, $this->id_usuario);
        return $stmt->execute();
    }

    public function eliminar() {
        $sql = "DELETE FROM uen WHERE id_uen = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $this->id_uen, $this->id_usuario);
        return $stmt->execute();
    }
}
?>