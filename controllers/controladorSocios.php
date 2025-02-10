

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
            
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $dni = $_POST['dni'];
                $nombre = ucwords(strtolower($_POST['nombre'])); 
                $apellidos = ucwords(strtolower($_POST['apellidos']));
                $fecha_nac = $_POST['fecha_nac'];
                $tarifa = $_POST['tarifa'];
                $fecha_alta = $_POST['fecha_alta'];
                $telefono = $_POST['telefono'] ?? null;
                $email = strtolower($_POST['email']) ?? null;
                $cuenta_bancaria = $_POST['cuenta_bancaria'] ?? null;

                
                try {


                    $dni_valido= Persona::validarDni($dni); 

                if($dni_valido){

                    $insertado = Socio::addSocio($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $fecha_alta, $cuenta_bancaria);
                    
                        if ($insertado){

                            $msg = "$nombre ha sido registrado con éxito";
                            header('Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg=' . $msg);
                            exit;

                        } 
                    
                }

                } catch (PDOException $e) {

                    //Si la insercción da error por este codigo "23000" es porque el dni ya esta registrado
                    if ($e->getCode() == 23000) {

                        $msg =  "Excepción PDO: el socio con el el dni $dni ya está registrado";

                       
                    } else {
                        //Mensaje  de error desconocido de PDOexcecption
                        $msg = $e->getMessage(); 
                    }

                   
                  
                }catch(Exception $e){
                    $msg = $e->getMessage(); 
                }

                //redirigimos con mensaje de error por alguna excepcion capturada
                header('Location: /proyecto_gym_MVC/view/socios/addSocio.php?msg=' . $msg);
                exit;


              
            }


    }



        public static function eliminarSocio(){

        
            
            
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
           
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $dni = $_POST['dni'];
                $nombre = ucwords(strtolower($_POST['nombre'])); 
                $apellidos = ucwords(strtolower($_POST['apellidos']));
                $fecha_nac = $_POST['fecha_nac'];
                $telefono = $_POST['telefono'] ?? null;
                $email = strtolower($_POST['email']) ?? null;
                $tarifa = $_POST['tarifa'];
                $fecha_alta = $_POST['fecha_alta'];
                $cuenta_bancaria = $_POST['cuenta_bancaria'] ?? null;
    
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




        public static function incribirClase(){
            

            if(isset($_POST['dni_socio'])) {


                $dni_socio = $_POST['dni_socio']; //enviado por oculto
                $id_clase = $_POST['id_clase'];


    
                try {

                    $inscrito = Socio::inscribirClase($dni_socio, $id_clase);

                    if($inscrito){
                        $msg = "socio $dni_socio inscrito con éxito en la clase $id_clase"; 
                        header('Location: /proyecto_gym_MVC/view/clases/clasesSocios.php?msg=' . $msg);
                        exit;
                    }

                } catch(PDOException $e) {
                    $msg = urlencode($e->getMessage());
                   
                } catch(Exception $e) {
                    $msg = urlencode($e->getMessage());
                   
                }
    
              
            } else {
                $msg = "No se ha proporcionado el DNI del socio.";
              
            }

            //Si no ha se ha enconrado ningun socio, o algún error ha hecho que el dni no se envie en el input hidden, redirigimos a la vista
            //con mensaje de error.
            header('Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg=' . $msg);
            exit;
            


        }


        public static function desapuntarClase(){

            if(isset($_POST['dni_socio'])) {


                $dni_socio = $_POST['dni_socio']; //enviado por oculto
                $id_clase = $_POST['id_clase'];

    
                try {

                    $desapuntar = Socio::desapuntarClase($dni_socio, $id_clase);

                    if($desapuntar){
                        $msg = "$dni_socio ha sido despuntado con éxito de la clase"; 
                    }

                } catch(PDOException $e) {
                    $msg = urlencode($e->getMessage());
                   
                }catch(Exception $e) {
                    $msg = urlencode($e->getMessage());
                   
                }
    
              
            } else {
                $msg = "No se ha proporcionado el DNI del socio.";
              
            }

            //Si no ha se ha enconrado ningun socio, o algún error ha hecho que el dni no se envie en el input hidden, redirigimos a la vista
            //con mensaje de error.
            header('Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg=' . $msg);
            exit;

        }



        //Retoran las clases donde esta inscrito el socio para el formulario de desapuntar socio de una clase

        public  static function get_clases_inscrito($dni_socio){

            try{

                $clases_inscrito = Socio::get_clases_inscrito($dni_socio) ?? []; 

                return $clases_inscrito; 


            } catch(PDOException $e) {
                    $msg = urlencode($e->getMessage());
                   
                }catch(Exception $e) {
                    $msg = urlencode($e->getMessage());
                   
                }

            }


        }


?>