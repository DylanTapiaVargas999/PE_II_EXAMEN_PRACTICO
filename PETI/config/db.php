<?php

class Database {
    public static function conexion() {
        // Intentar la conexión
        $db = mysqli_connect("localhost:3306", "root", "", "bd_plan");

        // Comprobar si hay errores de conexión
        if (!$db) {
            die("Error de conexión: " . mysqli_connect_error());
        }

        // Establecer el conjunto de caracteres
        $db->query("SET NAMES 'utf8'");

        return $db;
    }
}
