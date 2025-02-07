
<?php
abstract class Persona {

    /* Creamos las propiedades como 'protected' porque esta clase es abstracta y no va a crear objetos directamente. 
   No es necesario definir getters ni setters en esta clase, ya que las propiedades deben ser accesibles solo por 
   las clases que la hereden. Esto permite que las clases hijas puedan acceder y modificar estas propiedades. */
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
        $this->telefono = $telefono;  
        $this->email = $email; 
    }

 
    private function validarDni($dni){

       
        $check_dni  = false; 
        $dni_numeros = substr($dni, 0, 8); 
        $letraIntroducida = substr($dni, -1, 1); 

        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        $letraCorrecta = $letras[intval($dni_numeros) % 23]; 

        if($letraIntroducida == $letraCorrecta) $check_dni=true; 
        else throw new Exception('DNI INTRODUCIDO NO VÁLIDO'); 

        return $check_dni; 
    }

   
  


    // Método mágico abstracto para obtener el valor de $name
    abstract public function __get($name);
    
    // Método mágico abstracto para asignar el valor de $name a $value
    abstract public function __set($name, $value);
}
?>