
<?php
abstract class Trabajador extends Persona {

    /* Creamos las propiedades como 'protected' porque esta clase es abstracta y no va a crear objetos directamente. 
   No es necesario definir getters ni setters en esta clase, ya que las propiedades deben ser accesibles solo por 
   las clases que la hereden. Esto permite que las clases hijas puedan acceder y modificar estas propiedades. */
   protected $jornada;
   protected $sueldo;
   protected $funcion;


    function __construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $jornada, $sueldo, $funcion) {

        parent::__construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email); 
        $this->jornada=$jornada;
        $this->sueldo= $sueldo;
        $this->funcion=$funcion; 
    }

 
    

    // Método mágico abstracto para obtener el valor de $name
    abstract public function __get($name);
    
    // Método mágico abstracto para asignar el valor de $name a $value
    abstract public function __set($name, $value);
}
?>