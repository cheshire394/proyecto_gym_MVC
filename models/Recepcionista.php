<?php

require_once __DIR__ . '/../data/conexionBBDD.php';

class Recepcionista extends Trabajador {

    
    const EUROS_HORA=30;

    private $password;//para el logeo
    
    function __construct() {
        // Constructor vacío para fetchObject
    }

        

    public function __set($name, $value) {

        if (property_exists($this, $name)) {

            $this->$name = $value; 

        } else {
            throw new Exception('ERROR EN EL SETTER TRABAJADOR: LA PROPIEDAD QUE DESEAS MODIFICAR NO EXISTE'); 
        }
    }

    public function __get($name) {

        if (property_exists($this, $name)) {
            return $this->$name; 

        } else {
            throw new Exception('ERROR EN EL GETTER TRABAJADOR: LA PROPIEDAD QUE DESEAS OBTENER NO EXISTE');
        }
    }

   
    public static function registrar($nombre, $apellidos, $dni, $fecha_nac, $telefono, $email, $cuenta_bancaria, $funcion, $sueldo, $horas_extra, $jornada, $password) {
        
            global $conn; 

            // Hashear la contraseña antes de almacenarla
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    
            // Sentencia SQL para la inserción
            $sql_insert = "INSERT INTO RECEPCIONISTAS (nombre, apellidos, dni, fecha_nac, telefono, email, cuenta_bancaria, funcion, sueldo, horas_extra, jornada, password) 
                           VALUES (:nombre, :apellidos, :dni, :fecha_nac, :telefono, :email, :cuenta_bancaria, :funcion, :sueldo, :horas_extra, :jornada, :password)";
    
            $stmt_insert = $conn->prepare($sql_insert);
    
            // Vincular parámetros
            $stmt_insert->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt_insert->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt_insert->bindParam(':dni', $dni, PDO::PARAM_STR);
            $stmt_insert->bindParam(':fecha_nac', $fecha_nac, PDO::PARAM_STR);
            $stmt_insert->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt_insert->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_insert->bindParam(':cuenta_bancaria', $cuenta_bancaria, PDO::PARAM_STR);
            $stmt_insert->bindParam(':funcion', $funcion, PDO::PARAM_STR);
            $stmt_insert->bindParam(':sueldo', $sueldo, PDO::PARAM_STR);
            $stmt_insert->bindParam(':horas_extra', $horas_extra, PDO::PARAM_STR);
            $stmt_insert->bindParam(':jornada', $jornada, PDO::PARAM_STR);
            $stmt_insert->bindParam(':password', $password_hashed, PDO::PARAM_STR);
    
            // Ejecutar la inserción
            if(!$stmt_insert->execute()){

                throw new PDOException('Excepción PDO: error al ejecutar la inserción de la recepcionista en la BBDD');
            }


            return true;
    
    }
    
   

    public static function login($dni, $password) {

        global $conn;  

        $query = "SELECT nombre, password FROM RECEPCIONISTAS WHERE dni = :dni";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
        $stmt->execute();
    
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario['nombre']; // Devolvemos solo el nombre de la recepcionista
        } else {
            return false; // Credenciales incorrectas
        }
    }

    public static function verRecepcionistas(){

        global $conn; 
        
        $sql = "SELECT * FROM RECEPCIONISTAS"; 

       if(!$stmt = $conn->prepare($sql)) throw new PDOException('Error al preparar la consuta que obtiene todas los recepcionistas en verRecepcionistas'); 
       if(!$stmt->execute()) throw new PDOException("Excepción PDO: error al ejecutar la consulta en verRecepcionistas"); 

        $recepcionistas=[]; 
        while($recepcionista = $stmt->fetchObject('Recepcionista')){
            
            $recepcionistas[]=$recepcionista; 

        }

        return $recepcionistas;


    }
      

}


?>