<?php 
require_once 'config/db.php';

class Valor {
    private $id_valor;
    private $valor;
    private $codigo;
    private $id_usuario;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Getters
    public function getIdValor() {
        return $this->id_valor;
    }

    public function getValor() {
        return $this->valor;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    // Setters
    public function setIdValor($id_valor) {
        $this->id_valor = intval($id_valor);
        return $this;
    }

    public function setValor($valor) {
        $this->valor = $this->db->real_escape_string(trim($valor));
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

    public function guardar() {
        if (empty($this->valor) || empty($this->id_usuario)) {
            return false;
        }

        if (empty($this->codigo)) {
            if (isset($_SESSION['plan_codigo']) && !empty($_SESSION['plan_codigo'])) {
                $this->codigo = $_SESSION['plan_codigo'];
            } else {
                return false;
            }
        }

        $sql_check = "SELECT 1 FROM plan_estrategico WHERE codigo = '{$this->db->real_escape_string($this->codigo)}' LIMIT 1";
        $resultado = $this->db->query($sql_check);
        
        if (!$resultado || $resultado->num_rows == 0) {
            return false;
        }

        $sql = "INSERT INTO valores VALUES(
            NULL, 
            '{$this->getValor()}', 
            '{$this->getCodigo()}', 
            {$this->getIdUsuario()}
        )";

        $guardado = $this->db->query($sql);
        
        if ($guardado) {
            $this->id_valor = $this->db->insert_id;
            return true;
        }
        
        return false;
    }

    public function obtenerPorUsuario($id_usuario) {
        $sql = "SELECT * FROM valores 
                WHERE id_usuario = $id_usuario 
                ORDER BY id_valor DESC";
        $resultado = $this->db->query($sql);
        
        return $resultado ? $resultado : false;
    }

    public function obtenerUno($id_valor) {
        $sql = "SELECT * FROM valores WHERE id_valor = $id_valor LIMIT 1";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_object() : false;
    }

    public function actualizar() {
        if (empty($this->id_valor) || empty($this->valor) || empty($this->id_usuario)) {
            return false;
        }

        $sql = "UPDATE valores SET
                valor = '{$this->getValor()}', 
                codigo = '{$this->getCodigo()}'
                WHERE id_valor = {$this->getIdValor()}
                AND id_usuario = {$this->getIdUsuario()}";

        return $this->db->query($sql);
    }

    public function eliminar() {
        $sql = "DELETE FROM valores 
                WHERE id_valor = {$this->getIdValor()} 
                AND id_usuario = {$this->getIdUsuario()}";

        return $this->db->query($sql);
    }

    public function obtenerPorIdYUsuario($id_valor, $id_usuario) {
        $sql = "SELECT * FROM valores 
                WHERE id_valor = $id_valor 
                AND id_usuario = $id_usuario 
                LIMIT 1";

        $resultado = $this->db->query($sql);
        
        return $resultado ? $resultado->fetch_object() : false;
    }

    public function obtenerPorCodigoPlan($codigo_plan, $id_usuario) {
        $sql = "SELECT * FROM valores 
                WHERE codigo = '{$this->db->real_escape_string($codigo_plan)}'
                AND id_usuario = {$id_usuario}
                ORDER BY id_valor DESC";
        
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado : false;
    }
}
?>