<?php

class Usuario {
    private $id;
    private $empresa;
    private $correo;
    private $clave;
    private $imagen;
    private $db;

    public function __construct(){
        $this->db = Database::conexion();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getEmpresa() {
        return $this->empresa;
    }

    public function setEmpresa($empresa) {
        $this->empresa = $this->db->real_escape_string($empresa);
        return $this;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function setCorreo($correo) {
        $this->correo = $this->db->real_escape_string($correo);
        return $this;
    }

    public function getClave() {
        return password_hash($this->db->real_escape_string($this->clave), PASSWORD_BCRYPT, ['cost'=>4]);
    }

    public function setClave($clave) {
        $this->clave = $clave;
        return $this;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
        return $this;
    }

    public function guardar() {
        $sql = "INSERT INTO usuario VALUES(NULL, '{$this->getEmpresa()}', '{$this->getCorreo()}', '{$this->getClave()}', ";

        if ($this->imagen != null) {
            $sql .= "'{$this->getImagen()}')";
        } else {
            $sql .= "NULL)";
        }

        $guardar = $this->db->query($sql);
        return $guardar ? true : false;
    }

    public function iniciarSesion() {
        $resultado = false;
        $correo = $this->correo;
        $clave = $this->clave;

        $sql = "SELECT * FROM usuario WHERE correo = '$correo'";
        $iniciarSesion = $this->db->query($sql);

        if ($iniciarSesion && $iniciarSesion->num_rows == 1) {
            $usuario = $iniciarSesion->fetch_object();
            if (password_verify($clave, $usuario->clave)) {
                $resultado = $usuario;
            }
        }

        return $resultado;
    }

    public function actualizar() {
        $sqlVerificar = "SELECT * FROM usuario WHERE correo = '{$this->getCorreo()}' AND id != {$this->getId()}";
        $verificarCorreo = $this->db->query($sqlVerificar);

        if ($verificarCorreo && $verificarCorreo->num_rows > 0) {
            return false; // El correo ya está en uso
        }

        $sql = "UPDATE usuario SET 
                    empresa = '{$this->getEmpresa()}', 
                    correo = '{$this->getCorreo()}'";

        if (!empty($this->imagen)) {
            $sql .= ", imagen = '{$this->getImagen()}'";
        }

        $sql .= " WHERE id = {$this->getId()}";
        $actualizar = $this->db->query($sql);
        return $actualizar ? true : false;
    }
}
