<?php

class Utilidades {
    public static function eliminarSesion($nombre){
        if(isset($_SESSION[$nombre])){
            $_SESSION[$nombre] = null;
            unset($_SESSION[$nombre]);
        }

        return $nombre;
    }

    public static function esAdmin(){
        if(!isset($_SESSION['admin'])){
            header('Location: '.base_url);
        } else {
            return true;
        }
    }



    public static function estaLogueado(){
        if(!isset($_SESSION['identity'])){
            header("Location: ".base_url);
        } 
    }


    public static function verificarSesion() {
        if (!isset($_SESSION['identity'])) {
            header("Location: ".base_url."usuario/iniciarSesion");
            exit();
        }
    }
    
    public static function verificarPlan() {
        if (!isset($_SESSION['plan_codigo'])) {
            header("Location: ".base_url."planEstrategico/seleccionar");
            exit();
        }
    }
    
    public static function mostrarError($error) {
        if (isset($error)) {
            echo '<div class="alert alert-danger">'.$error.'</div>';
        }
    }
    
    public static function mostrarExito($exito) {
        if (isset($exito)) {
            echo '<div class="alert alert-success">'.$exito.'</div>';
        }
    }
}
