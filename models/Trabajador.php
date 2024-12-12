<?php
require_once('datosIncorrectos.php'); 

class Trabajador extends Persona {

    const  HORAS_EXTRA=15; 
    private $funcion; 
    private $sueldo; 
    private $jornada; 
    private $horas_extra; 
    private $cuenta_bancaria;
    protected static $file = __DIR__ . '/../data/recepcionistas.json';

    private static $trabajadores = [
        'recepcionistas' => [],
        'monitores'=> [],
    ]; 

    
      
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
    
    //los objetos son copiados en el array por referencia implicitamente, esto hace que el array que almacena los objetos
    // actualice sus datos cuando ejecutamos un setter sobre cualquier objeto. 
    if($funcion == 'recepcionista')self::$trabajadores['recepcionistas'][$this->dni]=$this; 
    else self::$trabajadores['monitores'][$this->dni]=$this; 
}

        

    public function __set($name, $value) {
        if (property_exists($this, $name)) {

            
            if($name == 'jornada' && ($this->__get('jornada') - $value < 0)) $this->$name = 0; 
            else{

                $this->$name = $value; 
            }
          
            if($name == 'horas_extra') $this->cobrarHorasExtra();
            
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

   
    //METODOS PARA MANEJAR EN CONTROLADOR: 

    public static function getAllRecepcionistas() {
        // Verificar si el archivo JSON existe
        if (!file_exists(self::$file)) {
            // Crear un archivo JSON vacío si no existe
            file_put_contents(self::$file, json_encode([]));
        }

        // Leer el contenido del archivo JSON
        $jsonContent = file_get_contents(self::$file);
        
        // Decodificar el JSON en un array
        $recepcionistas = json_decode($jsonContent, true);

        // Devolver el array de recepcionistas (vacío si no hay ninguno)
        return $recepcionistas ?: [];
    }

    //METODOS PARA MANEJAR EN CONTROLADOR: 
    
    public static function registrar($datos) {
        // Obtener recepcionistas existentes
        $recepcionistas = self::getAllRecepcionistas();

        // Verificar si ya existe un recepcionista con ese DNI
        foreach ($recepcionistas as $recepcionista) {
            if ($recepcionista['dni'] === $datos['dni']) {
                return false; // Ya existe
            }
        }

        // Hashear la contraseña
        $datos['password'] = password_hash($datos['password'], PASSWORD_BCRYPT);

        // Añadir nuevo recepcionista
        $recepcionistas[] = $datos;

        // Guardar en el JSON
        file_put_contents(self::$file, json_encode($recepcionistas, JSON_PRETTY_PRINT));

        return true;
    }

    public static function login($dni, $password) {
        // Obtener recepcionistas del JSON
        $recepcionistas = self::getAllRecepcionistas();

        // Buscar si el DNI coincide
        foreach ($recepcionistas as $recepcionista) {
            if ($recepcionista['dni'] === $dni && password_verify($password, $recepcionista['password'])) {
                // Guardar información del usuario en la sesión
                session_start();
                $_SESSION['dni'] = $dni;
                $_SESSION['nombre'] = $recepcionista['nombre'];
                return true;
            }
        }

        // Credenciales no válidas
        return false;
    }

    
    //NO FUNCIONA
   /* public static function olvidado($dni) {
        $recepcionistas = self::getAllRecepcionistas(); 

        // Si encontramos la recepcionista con ese DNI, la eliminamos
        foreach ($recepcionistas as $index => $recepcionista) {
            if ($recepcionista['dni'] == $dni) {
                // Eliminar la recepcionista del array
                unset($recepcionistas[$index]);

                // Guardar el array actualizado en el archivo JSON
                file_put_contents(self::$file, json_encode(array_values($recepcionistas), JSON_PRETTY_PRINT));

    
            }
        }

    }*/



    //será necesario trabajar con ellos en la clase --> "Clases.php", para asignarles clases y mas funciones. 
    public static function getTrabajadoresMonitores()
    {
        return SELF::$trabajadores['monitores'];
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

    public static function mostrarTrabajadores() {
        foreach (self::$trabajadores as $key => $Arr_obj) {
            
            echo "<h3>Trabajadores con función <b>$key:</b></h3><br>";
    
            foreach ($Arr_obj as $objeto) {
                
                $propiedades = get_object_vars($objeto); //obtenemos las propiedades del objeto
    
                foreach ($propiedades as $propiedad => $valor) {
                    echo "<b>$propiedad</b>: $valor<br>";
                }
    
                echo "<br>"; 
            }
        }
    }


    //Este metodo solo será llamado desde setter cuando se modifique las horas extra
    private function cobrarHorasExtra(){
        
        $aumento = $this->__get('horas_extra') * self::HORAS_EXTRA + $this->__get('sueldo'); 
        $this->__set('sueldo', $aumento);   
    }


    

}


?>