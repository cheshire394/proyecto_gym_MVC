<?php


class Trabajador extends Persona {

    const RUTA_JSON_RECEPCIONISTAS = __DIR__ . '/../data/recepcionistas.json';
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

            if($name == 'horas_extra') $this->cobrarHorasExtra();

            if($name == 'jornada'){

                $actualizar_sueldo= $value * Trabajador::EUROS_HORA; 
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

   
    //METODOS PARA MANEJAR EL LOGEO DE LA TRABAJADORA RECEPCIONISTA:

    public static function getAllRecepcionistas() {

        // si el archivo JSON existe
        if (!file_exists(self::RUTA_JSON_RECEPCIONISTAS)) {

            // Creamos un archivo JSON vacío si no existe, si es el primer logeo
            file_put_contents(self::RUTA_JSON_RECEPCIONISTAS, json_encode([]));
        }

        // Leer el contenido del archivo JSON
        $jsonContent = file_get_contents(self::RUTA_JSON_RECEPCIONISTAS);
        
        // Decodificar el JSON en un array
        $recepcionistas = json_decode($jsonContent, true);

        // Devolver el array de recepcionistas (vacío si no hay ninguno)
        return $recepcionistas ?: [];
    }

   
    


    public static function registrar($datos) {
         
        //Array con las recepcionistas del JSON
        $recepcionistas = self::getAllRecepcionistas();

        // Verificamos si ya existe un recepcionista con ese DNI
        foreach ($recepcionistas as $recepcionista) {
            if ($recepcionista['dni'] === $datos['dni']) {
                return false; // Ya existe, salimos del registro con mensaje informátivo
            }
        }

        // encriptar las contraseña, en el json.
        $datos['password'] = password_hash($datos['password'], PASSWORD_BCRYPT);

        // Añadimos nueva recepcionista
        $recepcionistas[] = $datos;

        // Guardar en el JSON
        file_put_contents(self::RUTA_JSON_RECEPCIONISTAS, json_encode($recepcionistas, JSON_PRETTY_PRINT));

        return true; //registro exitoso
    }



    public static function login($dni, $password) {

        //Array con las recepcionistas del JSON
        $recepcionistas = self::getAllRecepcionistas();

        // Buscamos si el DNI  y contraseña conincide con las guardadas en el fichero: 
        foreach ($recepcionistas as $recepcionista) {
            if ($recepcionista['dni'] === $dni && password_verify($password, $recepcionista['password'])) {

                // Iniciamos session: 
                session_start();
                $_SESSION['dni'] = $dni;
                $_SESSION['nombre'] = $recepcionista['nombre'];
                return true;
            }
        }

        // Credenciales no válidas
        return false;
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
    
        throw new datosIncorrectos('ERROR: LA CUENTA BANCARIA INTRODUCIDA NO ES VÁLIDA');
    }

    

    //Este metodo solo será llamado desde setter cuando se modifique las horas extra
    private function cobrarHorasExtra(){
        
        $aumento = $this->__get('horas_extra') * self::EUROS_HORA + $this->__get('sueldo'); 
        $this->__set('sueldo', $aumento);   
    }


    

}


?>