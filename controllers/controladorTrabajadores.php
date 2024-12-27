<?php

require_once __DIR__ . '/../models/datosIncorrectos.php'; //excepción personalizada
require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Monitor.php';
require_once __DIR__ . '/../models/Clase.php';


class ControladorTrabajadores {
/**
 * The function "verMonitores" returns a JSON array of monitors or an empty array if no monitors are
 * found.
 * 
 * @return The `verMonitores` function is returning an array of monitor data in JSON format. If the
 * `Monitor::monitoresJSON()` method returns a valid array of monitor data, that array is returned.
 * Otherwise, an empty array `[]` is returned.
 */

    public static function verMonitores() {

        $monitores = Monitor::monitoresJSON() ?? [];  
        return $monitores; 

}


/**
 * The function "verRecepcionistas" returns an array of receptionist objects created using the
 * "crearObjetosRecepcionista" method from the Trabajador class in PHP.
 * 
 * @return An array of receptionist objects is being returned. If no receptionists are found, an empty
 * array is returned.
 */
public static function verRecepcionistas() {

    $recepcionistas = Trabajador::crearObjetosRecepcionista() ?? [];  
    return $recepcionistas; 

}


}