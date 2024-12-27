<?php

require_once __DIR__ . '/../models/datosIncorrectos.php'; //excepción personalizada
require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Monitor.php';
require_once __DIR__ . '/../models/Clase.php';


class ControladorTrabajadores {

    public static function verMonitores() {

        $monitores = Monitor::monitoresJSON() ?? [];  


    return $monitores; 

}


public static function verRecepcionistas() {

    $recepcionistas = Trabajador::crearObjetosRecepcionista() ?? [];  


return $recepcionistas; 

}


}