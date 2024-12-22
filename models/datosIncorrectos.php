<?php

/* The class "datosIncorrectos" extends the Exception class in PHP to handle and provide custom error
messages for incorrect data validation. */
    class datosIncorrectos extends Exception{

        /*Cuando lazamos la excepción desde los métodos que válidan los datos como dni, o la cuenta del banco: pasamos un mensaje de error en su constructor
        utilizaremos el constructor de la clase padre Excepción para que getMessage() pueda devolver la información que queramos sobre el */
        function __construct($mensajeError)
        {
            parent::__construct($mensajeError); 
        }

        public function datosIncorrectos(){

        
            return $this->getMessage(); 
            
        }
    }

?>