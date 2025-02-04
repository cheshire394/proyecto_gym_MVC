

<?php
//Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir las clases necesarias para gestionar personas, trabajadores, clases, monitores y socios.
require_once __DIR__ . '/../models/datosIncorrectos.php'; //excepción personalizada
require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Socio.php';

class controladorSocios
{


    public static function mostrarTodosSocios(){

        require_once('conexionBBDD.php'); 

            $socios = Socio::verSocios($conn);

            return $socios;
    }


    public static function filtrarSocios(){

        require_once('conexionBBDD.php'); 

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){ 

            $propiedad = $_POST['propiedad']; 
            $valor = $_POST['valor']; 
    
        try{
            
            
            $socios_filtrados = Socio::filtrarSocios($conn, $propiedad, $valor);
            return $socios_filtrados;


    
        
        }catch(PDOException $e) {
            $msg = urlencode($e->getMessage());
            header('Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg=' . $msg);
            exit;
        }

        }

       
    
    }


    public static function addSocio(){

        //Rescatamos los valores del formulario: 
            require_once('conexionBBDD.php');
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $dni = $_POST['dni'];
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $fecha_nac = $_POST['fecha_nac'];
                $telefono = $_POST['telefono'];
                $email = $_POST['email'];
                $tarifa = $_POST['tarifa'];
                $fecha_alta = $_POST['fecha_alta'];
                $cuenta_bancaria = $_POST['cuenta_bancaria'] ?? null; //La cuenta bancaria no es un dato requerido
    
                try {
                    $insertado = Socio::addSocio($conn, $dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $fecha_alta, $cuenta_bancaria);
                    
                    if ($insertado) {
                        $msg = "Los datos del socio han sido actualizados correctamente.";
                    } else {
                        $msg = "No se pudo actualizar el socio.";
                    }

                } catch(PDOException $e) {
                    $msg = urlencode($e->getMessage());
                }
    
                header('Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg=' . $msg);
                exit;
            }


    }



        public static function eliminarSocio(){

        
            require_once('conexionBBDD.php'); 
            
            if(isset($_POST['dni_socio'])){

                $dni = $_POST['dni_socio']; 

                //retorna un booleano 
                $eliminado = Socio::eliminarSocio($conn, $dni);
            }

            
            if($eliminado){
                $msg = "Socio eliminado con éxito"; 
                header( 'Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg='.$msg);
            } 
            else{

                $msg = "error al modificar el socio"; 
                header( 'Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg='.$msg);
            }  
            
           
        }

        // Este método rescata los valores del socio que el usuario quiere modificar para mostrarlos en el formulario: 
        public static function mostrarFormularioModificar() {
            require_once('conexionBBDD.php');
    
            if(isset($_POST['dni_socio'])) {


                $dni = $_POST['dni_socio'];
    
                try {
                    $socio = Socio::buscarSocio($conn, $dni);
                } catch(PDOException $e) {
                    $msg = urlencode($e->getMessage());
                    header('Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg=' . $msg);
                    exit;
                }
    
                if($socio) {
                    // Enviar datos del socio a la vista a traves de una variable de session
                    session_start();
                    $_SESSION['socio'] = $socio;  

                    //redirigir al formulario si tse ha encontrado el socio para modificar los datos
                    header('Location: /proyecto_gym_MVC/view/socios/modificarSocio.php');
                    exit;
                } else {
                    $msg = "Socio no encontrado.";
                   
                }
            } else {
                $msg = "No se ha proporcionado el DNI del socio.";
              
            }

            //Si no ha se ha enconrado ningun socio, o algún error ha hecho que el dni no se envie en el input hidden, redirigimos a la vista
            //con mensaje de error.
            header('Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg=' . $msg);
            exit;
        }



    //Este método recoge los valores que el usurio ha modificado y los aplica a la BBDD
        public static function modificarSocio() {
            require_once('conexionBBDD.php');
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $dni = $_POST['dni'];
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $fecha_nac = $_POST['fecha_nac'];
                $telefono = $_POST['telefono'];
                $email = $_POST['email'];
                $tarifa = $_POST['tarifa'];
                $fecha_alta = $_POST['fecha_alta'];
                $cuenta_bancaria = $_POST['cuenta_bancaria'];
    
                try {
                    $modificado = Socio::modificarSocio($conn, $dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $fecha_alta, $cuenta_bancaria);
                    
                    if ($modificado) {
                        $msg = "Los datos del socio han sido actualizados correctamente.";
                    } else {
                        $msg = "No se pudo actualizar el socio.";
                    }
                } catch(PDOException $e) {
                    $msg = urlencode($e->getMessage());
                }
    
                header('Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg=' . $msg);
                exit;
            }
        }




      
    
    }


  
        
    


?>