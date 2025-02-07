

<?php
//Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir las clases necesarias para gestionar personas, trabajadores, clases, monitores y socios.

require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Socio.php';

class controladorSocios
{


    public static function mostrarTodosSocios(){

            $socios = Socio::verSocios();

            return $socios;
    }


    public static function filtrarSocios(){

        require_once('conexionBBDD.php'); 

        if (isset($_POST['filtrar_socios'])){ 

            $propiedad = $_POST['propiedad']; 
            $valor = $_POST['valor']; 
    
        try{
            
            
            $socios_filtrados = Socio::filtrarSocios($propiedad, $valor);
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
                $nombre = ucwords($_POST['nombre']); 
                $apellidos = ucwords($_POST['apellidos']);
                $fecha_nac = $_POST['fecha_nac'];
                $tarifa = $_POST['tarifa'];
                $fecha_alta = $_POST['fecha_alta'];
                $telefono = $_POST['telefono'] ?? null;
                $email = $_POST['email'] ?? null;
                $cuenta_bancaria = $_POST['cuenta_bancaria'] ?? null;
    
                try {
                    $insertado = Socio::addSocio($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $fecha_alta, $cuenta_bancaria);
                    
                    if ($insertado) {
                        $msg = "$nombre ha sido registrado con éxito";
                    } 

                } catch (PDOException $e) {

                    //Si la insercción da error por este codigo "23000" es porque el dni ya esta registrado
                    if ($e->getCode() == 23000) {

                        $msg =  "Excepción PDO: el socio con el el dni $dni ya está registrado";

                       
                    } else {
                        //Mensaje  de error desconocido
                        $msg = $e->getMessage(); 
                    }

                    header('Location: /proyecto_gym_MVC/view/socios/addSocio.php?msg=' . $msg);
                    exit;
                  
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
                $eliminado = Socio::eliminarSocio($dni);
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

        // Este método rescata los valores del socio que el usuario quiere modificar para mostrarlos en el formulario modificar socio y facilitar al usuario 
        //la modificación de datos: 
        public static function mostrarFormularioModificar() {
            require_once('conexionBBDD.php');
    
            if(isset($_POST['dni_socio'])) {


                $dni = $_POST['dni_socio'];
    
                try {
                    $socio = Socio::buscarSocio($dni);
                } catch(PDOException $e) {
                    $msg = urlencode($e->getMessage());
                    header('Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg=' . $msg);
                    exit;
                }
    
                if($socio) {
                    // Enviar datos del socio a la vista a traves de una variable de session, para que el formulario ya este compleatado con los datos del socio
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
                $nombre = ucwords($_POST['nombre']);
                $apellidos = ucwords($_POST['apellidos']);
                $fecha_nac = $_POST['fecha_nac'];
                $telefono = $_POST['telefono'];
                $email = $_POST['email'];
                $tarifa = $_POST['tarifa'];
                $fecha_alta = $_POST['fecha_alta'];
                $cuenta_bancaria = $_POST['cuenta_bancaria'];
    
                try {
                    $modificado = Socio::modificarSocio($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $fecha_alta, $cuenta_bancaria);
                    
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