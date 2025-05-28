<?php
require_once 'config/db.php';

class Oportunidad {
    private $id_oportunidad;
    private $oportunidad;
    private $codigo;
    private $id_usuario;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Getters
    public function getIdOportunidad() {
        return $this->id_oportunidad;
    }

    public function getOportunidad() {
        return $this->oportunidad;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    // Setters
    public function setIdOportunidad($id) {
        $this->id_oportunidad = intval($id);
        return $this;
    }

    public function setOportunidad($oportunidad) {
        $this->oportunidad = $this->db->real_escape_string(trim($oportunidad));
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
        if (empty($this->oportunidad) || empty($this->id_usuario)) {
            return false;
        }

        if (empty($this->codigo) && isset($_SESSION['plan_codigo'])) {
            $this->codigo = $_SESSION['plan_codigo'];
        }

        $sql = "INSERT INTO oportunidad VALUES(
            NULL, 
            '{$this->getOportunidad()}', 
            '{$this->getCodigo()}', 
            {$this->getIdUsuario()}
        )";
        
        $guardado = $this->db->query($sql);
        
        if ($guardado) {
            $this->id_oportunidad = $this->db->insert_id;
            return true;
        }
        return false;
    }

    public function obtenerPorUsuario($id_usuario) {
        $sql = "SELECT * FROM oportunidad 
                WHERE id_usuario = $id_usuario 
                ORDER BY id_oportunidad DESC";
        return $this->db->query($sql);
    }

    public function obtenerPorCodigo($codigo) {
        $sql = "SELECT * FROM oportunidad 
                WHERE codigo = '{$this->db->real_escape_string($codigo)}'";
        return $this->db->query($sql);
    }

    public function eliminar() {
        $sql = "DELETE FROM oportunidad 
                WHERE id_oportunidad = {$this->getIdOportunidad()} 
                AND id_usuario = {$this->getIdUsuario()}";
        return $this->db->query($sql);
    }

    public function actualizar() {
        $sql = "UPDATE oportunidad SET 
                oportunidad = '{$this->getOportunidad()}'
                WHERE id_oportunidad = {$this->getIdOportunidad()} 
                AND id_usuario = {$this->getIdUsuario()}";
        return $this->db->query($sql);
    }

    public function obtenerPorIdYUsuario($id_oportunidad, $id_usuario) {
        $sql = "SELECT * FROM oportunidad 
                WHERE id_oportunidad = $id_oportunidad 
                AND id_usuario = $id_usuario 
                LIMIT 1";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_object() : false;
    }
}
?>