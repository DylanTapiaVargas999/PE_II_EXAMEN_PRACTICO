<?php 
require_once 'config/db.php';

class Mision {
    // Propiedades privadas de la clase
    private $id_mision; // ID único de la misión
    private $mision; // Descripción o nombre de la misión
    private $codigo; // Código de la misión (relacionado con el plan estratégico)
    private $id_usuario; // ID del usuario que creó la misión
    private $db; // Conexión a la base de datos

    // Constructor que establece la conexión a la base de datos
    public function __construct() {
        $this->db = Database::conexion(); // Establece la conexión con la base de datos
    }

    // Métodos getters para obtener los valores de las propiedades privadas
    public function getIdMision() {
        return $this->id_mision;
    }

    public function getMision() {
        return $this->mision;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    // Métodos setters para establecer los valores de las propiedades privadas
    // Se utiliza `return $this` para permitir el encadenamiento de métodos
    public function setIdMision($id_mision) {
        $this->id_mision = intval($id_mision); // Convierte el valor a entero
        return $this;
    }

    public function setMision($mision) {
        $this->mision = $this->db->real_escape_string(trim($mision)); // Elimina espacios y previene inyecciones SQL
        return $this;
    }

    public function setCodigo($codigo) {
        $this->codigo = $this->db->real_escape_string(trim($codigo)); // Elimina espacios y previene inyecciones SQL
        return $this;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = intval($id_usuario); // Convierte el valor a entero
        return $this;
    }

    // Método para guardar una misión en la base de datos
    public function guardar() {
        // Validaciones básicas
        if (empty($this->mision) || empty($this->id_usuario)) { // Asegura que 'mision' e 'id_usuario' no estén vacíos
            return false; // Retorna false si alguna de las condiciones no se cumple
        }

        // Verificar que tenemos un código de plan (debería venir de la sesión)
        if (empty($this->codigo)) {
            // Si no hay código establecido, intentamos obtenerlo de la sesión
            if (isset($_SESSION['plan_codigo']) && !empty($_SESSION['plan_codigo'])) {
                $this->codigo = $_SESSION['plan_codigo']; // Obtiene el código de plan desde la sesión
            } else {
                return false; // Si no se encuentra el código, retorna false
            }
        }

        // Verificación de que el código de plan existe en la tabla 'plan_estrategico'
        $sql_check = "SELECT 1 FROM plan_estrategico WHERE codigo = '{$this->db->real_escape_string($this->codigo)}' LIMIT 1";
        $resultado = $this->db->query($sql_check);
        
        if (!$resultado || $resultado->num_rows == 0) {
            return false; // Si no se encuentra el código de plan, retorna false
        }

        // Inserta la misión en la base de datos
        $sql = "INSERT INTO mision VALUES(
            NULL, 
            '{$this->getMision()}', 
            '{$this->getCodigo()}', 
            {$this->getIdUsuario()}
        )";

        $guardado = $this->db->query($sql);
        
        if ($guardado) {
            $this->id_mision = $this->db->insert_id; // Asigna el ID generado a la misión
            return true; // Retorna true si la misión fue guardada correctamente
        }
        
        return false; // Si hubo un error al guardar, retorna false
    }

    // Método para obtener las misiones de un usuario
    public function obtenerPorUsuario($id_usuario) {
        $sql = "SELECT * FROM mision 
                WHERE id_usuario = $id_usuario 
                ORDER BY id_mision DESC"; // Ordena las misiones por ID en orden descendente
        $resultado = $this->db->query($sql);
        
        return $resultado ? $resultado : false; // Retorna los resultados o false si no hay misiones
    }

    // Método para obtener una misión por su ID
    public function obtenerUno($id_mision) {
        $sql = "SELECT * FROM mision WHERE id_mision = $id_mision LIMIT 1"; // Busca una misión específica por ID
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_object() : false; // Retorna el objeto de la misión o false si no existe
    }

    // Método para actualizar una misión existente
    public function actualizar() {
        if (empty($this->id_mision) || empty($this->mision) || empty($this->id_usuario)) {
            return false; // Asegura que todos los campos necesarios estén completos
        }

        $sql = "UPDATE mision SET
                mision = '{$this->getMision()}', 
                codigo = '{$this->getCodigo()}'
                WHERE id_mision = {$this->getIdMision()}
                AND id_usuario = {$this->getIdUsuario()}"; // Actualiza los datos de la misión en la base de datos

        return $this->db->query($sql); // Ejecuta la consulta de actualización
    }

    // Método para eliminar una misión
    public function eliminar() {
        $sql = "DELETE FROM mision 
                WHERE id_mision = {$this->getIdMision()} 
                AND id_usuario = {$this->getIdUsuario()}"; // Elimina la misión especificada

        return $this->db->query($sql); // Ejecuta la consulta de eliminación
    }

    // Método adicional para obtener una misión por su ID y usuario
    public function obtenerPorIdYUsuario($id_mision, $id_usuario) {
        $sql = "SELECT * FROM mision 
                WHERE id_mision = $id_mision 
                AND id_usuario = $id_usuario 
                LIMIT 1"; // Verifica que la misión pertenezca al usuario

        $resultado = $this->db->query($sql);
        
        return $resultado ? $resultado->fetch_object() : false; // Retorna el objeto de la misión o false si no existe
    }

    // Método para generar un código único aleatorio
    private function generarCodigoUnico($longitud = 10) {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Caracteres disponibles para el código
        $codigo = '';
        
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)]; // Genera el código aleatorio
        }

        // Verificar si el código ya existe en la base de datos
        $sql = "SELECT id_mision FROM mision WHERE codigo = '$codigo'";
        $resultado = $this->db->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            return $this->generarCodigoUnico($longitud); // Llama a la función recursivamente si el código ya existe
        }

        return $codigo; // Retorna el código único generado
    }

    // Método para verificar si un código de plan ya existe
    public function existeCodigoPlan($codigo) {
        $codigo = $this->db->real_escape_string($codigo); // Previene inyecciones SQL
        $sql = "SELECT id_mision FROM mision WHERE codigo = '$codigo' LIMIT 1";
        $resultado = $this->db->query($sql);
        return $resultado && $resultado->num_rows > 0; // Retorna true si el código existe
    }

    // Método para obtener misiones por código de plan y usuario
    public function obtenerPorCodigoPlan($codigo_plan, $id_usuario) {
        $sql = "SELECT * FROM mision 
                WHERE codigo = '{$this->db->real_escape_string($codigo_plan)}'
                AND id_usuario = {$id_usuario}
                ORDER BY id_mision DESC"; // Ordena las misiones por ID en orden descendente
        
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado : false; // Retorna los resultados o false si no hay misiones
    }
}
?>
