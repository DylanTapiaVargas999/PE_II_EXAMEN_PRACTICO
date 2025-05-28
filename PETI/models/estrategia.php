<?php
require_once 'config/db.php';

class Estrategia {
    private $id_estrategia;
    private $codigo;
    private $id_usuario;
    private $datos;
    private $db;

    public function __construct() {
        $this->db = Database::conexion();
    }

    // Setters
    public function setIdEstrategia($id) {
        $this->id_estrategia = $this->db->real_escape_string($id);
        return $this;
    }
    public function setCodigo($codigo) {
        $this->codigo = $this->db->real_escape_string($codigo);
        return $this;
    }
    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $this->db->real_escape_string($id_usuario);
        return $this;
    }
    public function setDatos($datos) {
        $this->datos = $datos;
        return $this;
    }

    // Guardar la matriz
    public function guardarMatriz($fo, $af, $od, $ad) {
        $fo_json = json_encode($fo);
        $af_json = json_encode($af);
        $od_json = json_encode($od);
        $ad_json = json_encode($ad);

        // Verificar si ya existe una matriz para este código y usuario
        $sql_check = "SELECT id_estrategia FROM estrategias WHERE codigo = '{$this->codigo}' AND id_usuario = '{$this->id_usuario}' LIMIT 1";
        $result = $this->db->query($sql_check);

        if ($result && $result->num_rows > 0) {
            // Ya existe, actualiza
            $row = $result->fetch_assoc();
            $id = $row['id_estrategia'];
            $sql_update = "UPDATE estrategias SET fo='$fo_json', af='$af_json', od='$od_json', ad='$ad_json'
                           WHERE id_estrategia = '$id'";
            return $this->db->query($sql_update);
        } else {
            // No existe, inserta
            $sql_insert = "INSERT INTO estrategias (codigo, id_usuario, fo, af, od, ad)
                           VALUES ('{$this->codigo}', '{$this->id_usuario}', '$fo_json', '$af_json', '$od_json', '$ad_json')";
            return $this->db->query($sql_insert);
        }
    }

    // Obtener por código
    public function obtenerPorCodigoPlan($codigo, $id_usuario) {
        $sql = "SELECT * FROM estrategias WHERE codigo = '$codigo' AND id_usuario = '$id_usuario' ORDER BY id_estrategia DESC";
        return $this->db->query($sql);
    }

    // Obtener por ID
    public function obtenerPorIdYUsuario($id_estrategia, $id_usuario) {
        $sql = "SELECT * FROM estrategias WHERE id_estrategia = '$id_estrategia' AND id_usuario = '$id_usuario' LIMIT 1";
        $result = $this->db->query($sql);
        return ($result && $result->num_rows > 0) ? $result->fetch_object() : false;
    }

    // Eliminar
    public function eliminar() {
        $sql = "DELETE FROM estrategias WHERE id_estrategia = '{$this->id_estrategia}' AND id_usuario = '{$this->id_usuario}'";
        return $this->db->query($sql);
    }
}
?>
