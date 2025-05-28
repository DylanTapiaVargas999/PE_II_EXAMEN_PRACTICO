<?php
require_once 'config/db.php';

class Producto {
    private $id_producto;
    private $nombre;
    private $ventas;
    private $tcm1;
    private $tcm2;
    private $tcm3;
    private $tcm4;
    private $tcm5;
    private $CP_1;
    private $CP_2;
    private $CP_3;
    private $CP_4;
    private $CP_5;
    private $CP_6;
    private $CP_7;
    private $CP_8;
    private $CP_9;
    private $codigo;
    private $EDGS;
    private $id_usuario;
    private $db;
    private $completado;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Getters
    public function getIdProducto() { return $this->id_producto; }
    public function getNombre() { return $this->nombre; }
    public function getVentas() { return $this->ventas; }
    public function getTcm1() { return $this->tcm1; }
    public function getTcm2() { return $this->tcm2; }
    public function getTcm3() { return $this->tcm3; }
    public function getTcm4() { return $this->tcm4; }
    public function getTcm5() { return $this->tcm5; }
    public function getCP1() { return $this->CP_1; }
    public function getCP2() { return $this->CP_2; }
    public function getCP3() { return $this->CP_3; }
    public function getCP4() { return $this->CP_4; }
    public function getCP5() { return $this->CP_5; }
    public function getCP6() { return $this->CP_6; }
    public function getCP7() { return $this->CP_7; }
    public function getCP8() { return $this->CP_8; }
    public function getCP9() { return $this->CP_9; }
    public function getCodigo() { return $this->codigo; }
    public function getEDGS() { return $this->EDGS; }
    public function getIdUsuario() { return $this->id_usuario; }

    // Setters
    public function setIdProducto($id) {
        $this->id_producto = intval($id);
        return $this;
    }

    public function setNombre($nombre) {
        $this->nombre = $this->db->real_escape_string(trim($nombre));
        return $this;
    }

    public function setVentas($ventas) {
        $this->ventas = intval($ventas);
        return $this;
    }

    public function setTcm1($valor) { $this->tcm1 = floatval($valor); return $this; }
    public function setTcm2($valor) { $this->tcm2 = floatval($valor); return $this; }
    public function setTcm3($valor) { $this->tcm3 = floatval($valor); return $this; }
    public function setTcm4($valor) { $this->tcm4 = floatval($valor); return $this; }
    public function setTcm5($valor) { $this->tcm5 = floatval($valor); return $this; }

    public function setCP1($valor) { $this->CP_1 = intval($valor); return $this; }
    public function setCP2($valor) { $this->CP_2 = intval($valor); return $this; }
    public function setCP3($valor) { $this->CP_3 = intval($valor); return $this; }
    public function setCP4($valor) { $this->CP_4 = intval($valor); return $this; }
    public function setCP5($valor) { $this->CP_5 = intval($valor); return $this; }
    public function setCP6($valor) { $this->CP_6 = intval($valor); return $this; }
    public function setCP7($valor) { $this->CP_7 = intval($valor); return $this; }
    public function setCP8($valor) { $this->CP_8 = intval($valor); return $this; }
    public function setCP9($valor) { $this->CP_9 = intval($valor); return $this; }

    public function setCodigo($codigo) {
        $this->codigo = $this->db->real_escape_string(trim($codigo));
        return $this;
    }

    public function setEDGS($valor) {
        $this->EDGS = intval($valor);
        return $this;
    }

    public function setIdUsuario($id) {
        $this->id_usuario = intval($id);
        return $this;
    }

    public function setCompletado($valor) {
        $this->completado = (bool)$valor;
        return $this;
    }
    
    public function getCompletado() {
        return $this->completado;
    }

    // Métodos CRUD
    // Método para guardar todos los datos del producto
    public function guardarProductoCompleto($datos) {
        $sql = "INSERT INTO producto (
            nombre, ventas, tcm1, tcm2, tcm3, tcm4, tcm5,
            CP_1, CP_2, CP_3, CP_4, CP_5, CP_6, CP_7, CP_8, CP_9,
            codigo, EDGS, id_usuario
        ) VALUES (
            '{$this->db->real_escape_string($datos['nombre'])}',
            {$datos['ventas']},
            {$datos['tcm1']},
            {$datos['tcm2']},
            {$datos['tcm3']},
            {$datos['tcm4']},
            {$datos['tcm5']},
            {$datos['cp1']},
            {$datos['cp2']},
            {$datos['cp3']},
            {$datos['cp4']},
            {$datos['cp5']},
            {$datos['cp6']},
            {$datos['cp7']},
            {$datos['cp8']},
            {$datos['cp9']},
            '{$this->db->real_escape_string($datos['codigo'])}',
            {$datos['edgs']},
            {$datos['id_usuario']}
        )";
        
        return $this->db->query($sql);
    }

    // Método para calcular porcentajes sin guardarlos
    public function calcularPorcentajesVentas($codigo_plan) {
        $productos = $this->db->query("SELECT id_producto, nombre, ventas FROM producto WHERE codigo = '{$this->db->real_escape_string($codigo_plan)}'");
        
        $total = 0;
        $datos = [];
        
        // Calcular total primero
        while($p = $productos->fetch_object()) {
            $total += $p->ventas;
            $datos[$p->id_producto] = [
                'nombre' => $p->nombre,
                'ventas' => $p->ventas,
                'porcentaje' => 0
            ];
        }
        
        // Calcular porcentajes
        if($total > 0) {
            foreach($datos as $id => $producto) {
                $datos[$id]['porcentaje'] = ($producto['ventas'] / $total) * 100;
            }
        }
        
        return $datos;
    }

    // Obtener productos con datos completos
    public function obtenerProductosCompletos($codigo_plan, $id_usuario) {
        $sql = "SELECT * FROM producto 
                WHERE codigo = '{$this->db->real_escape_string($codigo_plan)}' 
                AND id_usuario = $id_usuario
                ORDER BY nombre";
        return $this->db->query($sql);
    }

        // Guardar solo datos básicos (primer paso)
    public function guardarBasico() {
        $sql = "INSERT INTO producto (nombre, codigo, id_usuario, completado) VALUES (
            '{$this->db->real_escape_string($this->nombre)}',
            '{$this->db->real_escape_string($this->codigo)}',
            {$this->id_usuario},
            0
        )";
        
        return $this->db->query($sql);
    }
    
    // Obtener productos por plan y usuario
    public function obtenerPorPlan($codigo_plan, $id_usuario) {
        $sql = "SELECT * FROM producto 
                WHERE codigo = '{$this->db->real_escape_string($codigo_plan)}' 
                AND id_usuario = $id_usuario
                ORDER BY nombre";
        
        return $this->db->query($sql);
    }
    
    // Eliminar producto
    public function eliminar() {
        $sql = "DELETE FROM producto 
                WHERE id_producto = {$this->id_producto} 
                AND id_usuario = {$this->id_usuario}";
        
        return $this->db->query($sql);
    }
    
    // Actualizar con todos los datos BCG (segundo paso)
    public function completarDatosBCG($datos) {
        $sql = "UPDATE producto SET
                ventas = {$datos['ventas']},
                tcm1 = {$datos['tcm1']},
                tcm2 = {$datos['tcm2']},
                tcm3 = {$datos['tcm3']},
                tcm4 = {$datos['tcm4']},
                tcm5 = {$datos['tcm5']},
                CP_1 = {$datos['cp1']},
                CP_2 = {$datos['cp2']},
                CP_3 = {$datos['cp3']},
                CP_4 = {$datos['cp4']},
                CP_5 = {$datos['cp5']},
                CP_6 = {$datos['cp6']},
                CP_7 = {$datos['cp7']},
                CP_8 = {$datos['cp8']},
                CP_9 = {$datos['cp9']},
                EDGS = {$datos['edgs']},
                completado = 1
                WHERE id_producto = {$datos['id_producto']}
                AND id_usuario = {$datos['id_usuario']}";
        
        return $this->db->query($sql);
    }

    public function guardarProducto($datos) {
        if (isset($datos['completado']) && $datos['completado']) {
            return $this->guardarProductoCompleto($datos);
        } else {
            $sql = "INSERT INTO producto (nombre, codigo, id_usuario) VALUES (
                '{$this->db->real_escape_string($datos['nombre'])}',
                '{$this->db->real_escape_string($datos['codigo'])}',
                {$datos['id_usuario']}
            )";
            return $this->db->query($sql);
        }
    }

    public function obtenerPorId($id, $id_usuario) {
        $sql = "SELECT * FROM producto 
                WHERE id_producto = $id 
                AND id_usuario = $id_usuario
                LIMIT 1";
        $result = $this->db->query($sql);
        return $result->fetch_object();
    }

    public function actualizarProducto($datos) {
        $sql = "UPDATE producto SET
                nombre = '{$this->db->real_escape_string($datos['nombre'])}',
                ventas = {$datos['ventas']},
                tcm1 = {$datos['tcm1']},
                tcm2 = {$datos['tcm2']},
                tcm3 = {$datos['tcm3']},
                tcm4 = {$datos['tcm4']},
                tcm5 = {$datos['tcm5']},
                CP_1 = {$datos['cp1']},
                CP_2 = {$datos['cp2']},
                CP_3 = {$datos['cp3']},
                CP_4 = {$datos['cp4']},
                CP_5 = {$datos['cp5']},
                CP_6 = {$datos['cp6']},
                CP_7 = {$datos['cp7']},
                CP_8 = {$datos['cp8']},
                CP_9 = {$datos['cp9']},
                EDGS = {$datos['edgs']},
                completado = 1
                WHERE id_producto = {$datos['id_producto']}
                AND id_usuario = {$datos['id_usuario']}";
        
        return $this->db->query($sql);
    }

}