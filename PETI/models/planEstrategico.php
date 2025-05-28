<?php

class PlanEstrategico {
    private $id;
    private $codigo;
    private $titulo;
    private $id_usuario;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $this->db->real_escape_string($codigo);
        return $this;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $this->db->real_escape_string($titulo);
        return $this;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
        return $this;
    }

    public function guardar() {
        $codigo = $this->generarCodigoUnico();

        $sql = "INSERT INTO plan_estrategico VALUES(
            NULL,
            '$codigo',
            '{$this->getTitulo()}',
            {$this->getIdUsuario()}
        )";

        $guardar = $this->db->query($sql);
        return $guardar ? true : false;
    }


    public function obtenerTodosPorUsuario($idUsuario) {
        $sql = "SELECT * FROM plan_estrategico WHERE id_usuario = $idUsuario ORDER BY id DESC";
        $result = $this->db->query($sql);
        return $result;
    }

    public function obtenerUno($id) {
        $sql = "SELECT * FROM plan_estrategico WHERE id = $id";
        $result = $this->db->query($sql);
        return $result->fetch_object();
    }

    public function actualizar() {
        $sql = "UPDATE plan_estrategico SET
                codigo = '{$this->getCodigo()}',
                titulo = '{$this->getTitulo()}',
                id_usuario = {$this->getIdUsuario()}
                WHERE id = {$this->getId()}";

        $actualizar = $this->db->query($sql);
        return $actualizar ? true : false;
    }

    public function eliminar() {
        $sql = "DELETE FROM plan_estrategico WHERE id = {$this->getId()}";
        $eliminar = $this->db->query($sql);
        return $eliminar ? true : false;
    }

    private function generarCodigoUnico($longitud = 10) {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = '';
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }

        // Verificar si ya existe en la BD
        $sql = "SELECT id FROM plan_estrategico WHERE codigo = '$codigo'";
        $resultado = $this->db->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            return $this->generarCodigoUnico($longitud); // Recursivo si ya existe
        }

        return $codigo;
    }


}
