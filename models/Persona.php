<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

abstract class Persona {

    /*Creamos las propiedades como protected, porque esta clase, no va a crear ningún objeto, y no necesita getter ni setter, necesitamos que sus 
    hijos puedan acceder a ellas. */
    protected $dni; 
    protected $nombre; 
    protected $apellidos;
    protected $fecha_nac; 
    protected $edad; 
    protected $telefono; 
    protected $email; 

    function __construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email) {

        if($this->validarDni($dni))$this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->fecha_nac = $fecha_nac;
        $this->edad = $this->calcularEdad(); 
        $this->telefono = $telefono;  
        $this->email = $email; 
    }

    private function validarDni($dni){

        require_once('datosIncorrectos.php'); 
        $check_dni  = false; 
        $dni_numeros = substr($dni, 0, 8); 
        $letraIntroducida = substr($dni, -1, 1); 

        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        $letraCorrecta = $letras[intval($dni_numeros) % 23]; 

        if($letraIntroducida == $letraCorrecta) $check_dni=true; 
        else throw new datosIncorrectos('DNI INTRODUCIDO NO VÁLIDO'); 

        return $check_dni; 
    }

    private function calcularEdad() { 
        $today = new DateTime(); 
        $fecha_nac = new DateTime($this->fecha_nac); 
        $diferencia = $today->diff($fecha_nac); 
        return $diferencia->y;
    }

    abstract public function __get($name);
    abstract public function __set($name, $value);
}

?>
