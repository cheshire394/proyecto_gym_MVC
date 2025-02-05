<?php


class Recepcionista extends Persona {

    
    const EUROS_HORA=30;

    private $funcion; 
    private $sueldo; 
    private $jornada; 
    private $horas_extra; 
    private $cuenta_bancaria;
    
    function __construct(
        $dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, 
        $cuenta_bancaria, $funcion = 'recepcionista', $sueldo = 1100,$horas_extra = 0, $jornada =40) {
    
    
    $this->cuenta_bancaria = $this->validarCuentaBancaria($cuenta_bancaria); 
    $this->funcion = $funcion;
    $this->sueldo = $sueldo;
    $this->horas_extra = $horas_extra;
    $this->jornada = $jornada;

    //propiedades heredadas:
    parent::__construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email);
    

}

        

    public function __set($name, $value) {

        if (property_exists($this, $name)) {

            $this->$name = $value; 


            if($name == 'jornada'){

                $actualizar_sueldo= $value * Recepcionista::EUROS_HORA; 
                $this->__set('sueldo', $actualizar_sueldo); 
            }

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

   
    public static function registrar($conn, $nombre, $apellidos, $dni, $fecha_nac, $telefono, $email, $cuenta_bancaria, $funcion, $sueldo, $horas_extra, $jornada, $password) {
    
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
    
   

    public static function login($conn, $dni, $password) {
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
    

 

    public function validarCuentaBancaria($cuenta) {
        
        $cuenta = trim($cuenta);
        if (substr($cuenta, 0, 2) === 'ES' && strlen($cuenta) === 24) {
            
            $numeros = substr($cuenta, 2);
            /*usamos ctype_digit, porqué es más restrictivo que is_numeric y no permite usar números negativos */
            if (ctype_digit($numeros)) {
                return $cuenta; 
            }
        }
    
        throw new Exception('ERROR: LA CUENTA BANCARIA INTRODUCIDA NO ES VÁLIDA');
    }


    

}


?>