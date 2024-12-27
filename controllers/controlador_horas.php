<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/datosIncorrectos.php'; //excepción personalizada
require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Monitor.php';
require_once __DIR__ . '/../models/Clase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_horas'])) {

    $monitores = Monitor::monitoresJSON() ?? []; 

    //Obtenemos los datos del formulario: 
    $dni = $_POST['dni'];
    $horasExtra = (int)$_POST['horas_extra']; 

    if (isset($monitores[$dni])) {
    
        //horas extra (el sueldo se calculará al redirigir la pagina, que se crean nuevamente los objetos con este cambio aplicado)
        $monitores[$dni]->__set('horas_extra', $horasExtra);
    
        Monitor::guardarMonitoresEnJSON($monitores);
    }

   
    header("Location: /proyecto_gym_MVC/view/trabajadores/verTrabajadores.php");
    exit;
}
?>
