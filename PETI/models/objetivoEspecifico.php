<?php
require_once 'config/db.php';

class ObjetivoEspecifico {
    private $id_especifico;
    private $objetivo;
    private $codigo;
    private $id_general;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Getters
    public function getIdEspecifico() { return $this->id_especifico; }
    public function getObjetivo() { return $this->objetivo; }
    public function getCodigo() { return $this->codigo; }
    public function getIdGeneral() { return $this->id_general; }

    // Setters
    public function setIdEspecifico($id_especifico) {
        $this->id_especifico = intval($id_especifico);
        return $this;
    }

    public function setObjetivo($objetivo) {
        $this->objetivo = $this->db->real_escape_string(trim($objetivo));
        return $this;
    }

    public function setCodigo($codigo) {
        $this->codigo = $this->db->real_escape_string(trim($codigo));
        return $this;
    }

    public function setIdGeneral($id_general) {
        $this->id_general = intval($id_general);
        return $this;
    }

    // CRUD Operations
    public function guardar() {
        if (empty($this->objetivo) || empty($this->codigo) || empty($this->id_general)) {
            return false;
        }

        $sql = "INSERT INTO objetivo_especifico VALUES(NULL, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi', $this->objetivo, $this->codigo, $this->id_general);
        return $stmt->execute();
    }

    public function obtenerPorGeneral($id_general) {
        $sql = "SELECT * FROM objetivo_especifico WHERE id_general = ? ORDER BY id_especifico DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id_general);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function obtenerUno($id_especifico, $id_general) {
        $sql = "SELECT * FROM objetivo_especifico WHERE id_especifico = ? AND id_general = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $id_especifico, $id_general);
        $stmt->execute();
        return $stmt->get_result()->fetch_object();
    }

    public function actualizar() {
        $sql = "UPDATE objetivo_especifico SET objetivo = ?, codigo = ? WHERE id_especifico = ? AND id_general = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssii', $this->objetivo, $this->codigo, $this->id_especifico, $this->id_general);
        return $stmt->execute();
    }

    public function eliminar() {
        $sql = "DELETE FROM objetivo_especifico WHERE id_especifico = ? AND id_general = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $this->id_especifico, $this->id_general);
        return $stmt->execute();
    }

    public function eliminarPorGeneral($id_general) {
        $sql = "DELETE FROM objetivo_especifico WHERE id_general = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id_general);
        return $stmt->execute();
    }
}
?>