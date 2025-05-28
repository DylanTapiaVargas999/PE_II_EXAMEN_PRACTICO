<?php
require_once 'config/db.php';

class EncuestaPorter {
    private $id_encuesta_porter;
    private $p1;
    private $p2;
    private $p3;
    private $p4;
    private $p5;
    private $p6;
    private $p7;
    private $p8;
    private $p9;
    private $p10;
    private $p11;
    private $p12;
    private $p13;
    private $p14;
    private $p15;
    private $p16;
    private $p17;
    private $codigo;
    private $id_usuario;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Getters
    public function getIdEncuestaPorter() {
        return $this->id_encuesta_porter;
    }

    public function getP1() { return $this->p1; }
    public function getP2() { return $this->p2; }
    public function getP3() { return $this->p3; }
    public function getP4() { return $this->p4; }
    public function getP5() { return $this->p5; }
    public function getP6() { return $this->p6; }
    public function getP7() { return $this->p7; }
    public function getP8() { return $this->p8; }
    public function getP9() { return $this->p9; }
    public function getP10() { return $this->p10; }
    public function getP11() { return $this->p11; }
    public function getP12() { return $this->p12; }
    public function getP13() { return $this->p13; }
    public function getP14() { return $this->p14; }
    public function getP15() { return $this->p15; }
    public function getP16() { return $this->p16; }
    public function getP17() { return $this->p17; }
    public function getCodigo() { return $this->codigo; }
    public function getIdUsuario() { return $this->id_usuario; }

    // Setters
    public function setIdEncuestaPorter($id) {
        $this->id_encuesta_porter = intval($id);
        return $this;
    }

    public function setP1($value) { $this->p1 = intval($value); return $this; }
    public function setP2($value) { $this->p2 = intval($value); return $this; }
    public function setP3($value) { $this->p3 = intval($value); return $this; }
    public function setP4($value) { $this->p4 = intval($value); return $this; }
    public function setP5($value) { $this->p5 = intval($value); return $this; }
    public function setP6($value) { $this->p6 = intval($value); return $this; }
    public function setP7($value) { $this->p7 = intval($value); return $this; }
    public function setP8($value) { $this->p8 = intval($value); return $this; }
    public function setP9($value) { $this->p9 = intval($value); return $this; }
    public function setP10($value) { $this->p10 = intval($value); return $this; }
    public function setP11($value) { $this->p11 = intval($value); return $this; }
    public function setP12($value) { $this->p12 = intval($value); return $this; }
    public function setP13($value) { $this->p13 = intval($value); return $this; }
    public function setP14($value) { $this->p14 = intval($value); return $this; }
    public function setP15($value) { $this->p15 = intval($value); return $this; }
    public function setP16($value) { $this->p16 = intval($value); return $this; }
    public function setP17($value) { $this->p17 = intval($value); return $this; }
    
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
        $sql = "INSERT INTO encuesta_porter VALUES(
            NULL, 
            {$this->getP1()}, 
            {$this->getP2()}, 
            {$this->getP3()}, 
            {$this->getP4()}, 
            {$this->getP5()}, 
            {$this->getP6()}, 
            {$this->getP7()}, 
            {$this->getP8()}, 
            {$this->getP9()}, 
            {$this->getP10()}, 
            {$this->getP11()}, 
            {$this->getP12()}, 
            {$this->getP13()}, 
            {$this->getP14()}, 
            {$this->getP15()}, 
            {$this->getP16()}, 
            {$this->getP17()}, 
            '{$this->getCodigo()}', 
            {$this->getIdUsuario()}
        )";
        
        $guardado = $this->db->query($sql);
        
        if ($guardado) {
            $this->id_encuesta_porter = $this->db->insert_id;
            return true;
        }
        return false;
    }

    public function obtenerPorUsuario($id_usuario) {
        $sql = "SELECT * FROM encuesta_porter 
                WHERE id_usuario = $id_usuario 
                ORDER BY id_encuesta_porter DESC";
        return $this->db->query($sql);
    }

    public function obtenerUno($id_encuesta) {
        $sql = "SELECT * FROM encuesta_porter 
                WHERE id_encuesta_porter = $id_encuesta 
                LIMIT 1";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_object() : false;
    }

    public function obtenerPorCodigo($codigo) {
        $sql = "SELECT * FROM encuesta_porter 
                WHERE codigo = '{$this->db->real_escape_string($codigo)}' 
                LIMIT 1";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_object() : false;
    }

    public function actualizar() {
        $sql = "UPDATE encuesta_porter SET 
                p1 = {$this->getP1()}, 
                p2 = {$this->getP2()}, 
                p3 = {$this->getP3()}, 
                p4 = {$this->getP4()}, 
                p5 = {$this->getP5()}, 
                p6 = {$this->getP6()}, 
                p7 = {$this->getP7()}, 
                p8 = {$this->getP8()}, 
                p9 = {$this->getP9()}, 
                p10 = {$this->getP10()}, 
                p11 = {$this->getP11()}, 
                p12 = {$this->getP12()}, 
                p13 = {$this->getP13()}, 
                p14 = {$this->getP14()}, 
                p15 = {$this->getP15()}, 
                p16 = {$this->getP16()}, 
                p17 = {$this->getP17()}, 
                codigo = '{$this->getCodigo()}'
                WHERE id_encuesta_porter = {$this->getIdEncuestaPorter()} 
                AND id_usuario = {$this->getIdUsuario()}";
        
        return $this->db->query($sql);
    }

    public function obtenerPorIdYUsuario($id_encuesta, $id_usuario) {
        $sql = "SELECT * FROM encuesta_porter 
                WHERE id_encuesta_porter = $id_encuesta 
                AND id_usuario = $id_usuario 
                LIMIT 1";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_object() : false;
    }
}
?>