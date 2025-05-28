<?php
require_once 'config/db.php';

class ObjetivoGeneral {
    private $id_general;
    private $objetivo;
    private $codigo;
    private $id_usuario;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Getters
    public function getIdGeneral() { return $this->id_general; }
    public function getObjetivo() { return $this->objetivo; }
    public function getCodigo() { return $this->codigo; }
    public function getIdUsuario() { return $this->id_usuario; }

    // Setters
    public function setIdGeneral($id_general) {
        $this->id_general = intval($id_general);
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

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = intval($id_usuario);
        return $this;
    }

    // CRUD Operations
    public function guardar() {
        if (empty($this->objetivo) || empty($this->codigo) || empty($this->id_usuario)) {
            return false;
        }

        $sql = "INSERT INTO objetivo_general VALUES(NULL, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi', $this->objetivo, $this->codigo, $this->id_usuario);
        
        if ($stmt->execute()) {
            $this->id_general = $stmt->insert_id;
            return true;
        }
        return false;
    }

    public function obtenerPorUsuarioYPlan($id_usuario, $codigo_plan) {
        $sql = "SELECT * FROM objetivo_general WHERE id_usuario = ? AND codigo = ? ORDER BY id_general DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is', $id_usuario, $codigo_plan);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function obtenerUno($id_general, $id_usuario) {
        $sql = "SELECT * FROM objetivo_general WHERE id_general = ? AND id_usuario = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $id_general, $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_object();
    }

    public function actualizar() {
        $sql = "UPDATE objetivo_general SET objetivo = ?, codigo = ? WHERE id_general = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssii', $this->objetivo, $this->codigo, $this->id_general, $this->id_usuario);
        return $stmt->execute();
    }

    public function eliminar() {
        // Primero eliminamos los objetivos específicos asociados
        $especifico = new ObjetivoEspecifico();
        $especifico->eliminarPorGeneral($this->id_general);

        // Luego eliminamos el objetivo general
        $sql = "DELETE FROM objetivo_general WHERE id_general = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $this->id_general, $this->id_usuario);
        return $stmt->execute();
    }

    public function obtenerConEspecificos($id_usuario, $codigo_plan) {
        $sql = "SELECT og.*, oe.id_especifico, oe.objetivo as objetivo_especifico 
                FROM objetivo_general og
                LEFT JOIN objetivo_especifico oe ON og.id_general = oe.id_general
                WHERE og.id_usuario = ? AND og.codigo = ?
                ORDER BY og.id_general, oe.id_especifico";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is', $id_usuario, $codigo_plan);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $objetivos = [];
        
        while ($row = $result->fetch_assoc()) {
            if (!isset($objetivos[$row['id_general']])) {
                $objetivos[$row['id_general']] = [
                    'id_general' => $row['id_general'],
                    'objetivo' => $row['objetivo'],
                    'codigo' => $row['codigo'],
                    'especificos' => []
                ];
            }
            
            if ($row['id_especifico']) {
                $objetivos[$row['id_general']]['especificos'][] = [
                    'id_especifico' => $row['id_especifico'],
                    'objetivo' => $row['objetivo_especifico']
                ];
            }
        }
        
        return $objetivos;
    }
}
?>