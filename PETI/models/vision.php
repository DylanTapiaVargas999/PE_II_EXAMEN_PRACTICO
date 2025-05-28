<?php 
require_once 'config/db.php';

class Vision {
    private $id_vision;
    private $vision;
    private $codigo;
    private $id_usuario;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Getters
    public function getIdVision() {
        return $this->id_vision;
    }

    public function getVision() {
        return $this->vision;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    // Setters
    public function setIdVision($id_vision) {
        $this->id_vision = intval($id_vision);
        return $this;
    }

    public function setVision($vision) {
        $this->vision = $this->db->real_escape_string(trim($vision));
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
        if (empty($this->vision) || empty($this->id_usuario)) {
            return false;
        }

        if (empty($this->codigo)) {
            if (isset($_SESSION['plan_codigo']) && !empty($_SESSION['plan_codigo'])) {
                $this->codigo = $_SESSION['plan_codigo'];
            } else {
                return false;
            }
        }

        // Verificar que el código de plan existe
        $sql_check = "SELECT 1 FROM plan_estrategico WHERE codigo = '{$this->db->real_escape_string($this->codigo)}' LIMIT 1";
        $resultado = $this->db->query($sql_check);
        
        if (!$resultado || $resultado->num_rows == 0) {
            return false;
        }

        $sql = "INSERT INTO vision VALUES(
            NULL, 
            '{$this->getVision()}', 
            '{$this->getCodigo()}', 
            {$this->getIdUsuario()}
        )";

        $guardado = $this->db->query($sql);
        
        if ($guardado) {
            $this->id_vision = $this->db->insert_id;
            return true;
        }
        
        return false;
    }

    public function obtenerPorUsuario($id_usuario) {
        $sql = "SELECT * FROM vision 
                WHERE id_usuario = $id_usuario 
                ORDER BY id_vision DESC";
        $resultado = $this->db->query($sql);
        
        return $resultado ? $resultado : false;
    }

    public function obtenerUno($id_vision) {
        $sql = "SELECT * FROM vision WHERE id_vision = $id_vision LIMIT 1";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_object() : false;
    }

    public function actualizar() {
        if (empty($this->id_vision) || empty($this->vision) || empty($this->id_usuario)) {
            return false;
        }

        $sql = "UPDATE vision SET
                vision = '{$this->getVision()}', 
                codigo = '{$this->getCodigo()}'
                WHERE id_vision = {$this->getIdVision()}
                AND id_usuario = {$this->getIdUsuario()}";

        return $this->db->query($sql);
    }

    public function eliminar() {
        $sql = "DELETE FROM vision 
                WHERE id_vision = {$this->getIdVision()} 
                AND id_usuario = {$this->getIdUsuario()}";

        return $this->db->query($sql);
    }

    public function obtenerPorIdYUsuario($id_vision, $id_usuario) {
        $sql = "SELECT * FROM vision 
                WHERE id_vision = $id_vision 
                AND id_usuario = $id_usuario 
                LIMIT 1";

        $resultado = $this->db->query($sql);
        
        return $resultado ? $resultado->fetch_object() : false;
    }

    public function obtenerPorCodigoPlan($codigo_plan, $id_usuario) {
        $sql = "SELECT * FROM vision 
                WHERE codigo = '{$this->db->real_escape_string($codigo_plan)}'
                AND id_usuario = {$id_usuario}
                ORDER BY id_vision DESC";
        
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado : false;
    }
}
?>