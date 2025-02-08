<?php


require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Recepcionista.php';

class ControladorRecepcionista
{

   

        public static function registro()
        {
            if (isset($_POST['registrar'])) {
                $nombre = $_POST['nombre'];
                $apellidos = $_POST['apellidos'];
                $dni = $_POST['dni'];
                $fecha_nac = $_POST['fecha_nac'];
                $telefono = $_POST['telefono'];
                $email = $_POST['email'];
                $cuenta_bancaria = $_POST['cuenta_bancaria'];
                $funcion = $_POST['funcion'];
                $sueldo = $_POST['sueldo'];
                $horas_extra = $_POST['horas_extra'];
                $jornada = $_POST['jornada'];
                $password = $_POST['password'];
        
                require_once('conexionBBDD.php');
        
                try {

                    $dni_valido = Persona::validarDni($dni); //lanza error de tipo Exception o true

                if($dni_valido){
                    // Intentar registrar al recepcionista
                   $resistrar= Recepcionista::registrar($conn, $nombre, $apellidos, $dni, $fecha_nac, $telefono, $email, $cuenta_bancaria, $funcion, $sueldo, $horas_extra, $jornada, $password);
                    

                   if($resistrar){
                            // Si el registro es exitoso, redirigir con mensaje de éxito a la pagina de logeo
                                header("Location: /proyecto_gym_MVC/view/login_recepcionista.php?msg=recepcionista registrada correctamente");
                                exit();

                   }

                }
                
                    
                }catch (PDOException $e) {

                    //Si la insercción da error por este codigo "23000" es porque el dni ya esta registrado
                    if ($e->getCode() == 23000) {

                        $msg =  "Excepción PDO: el DNI introducido ya está registrado";

                       
                    } else {
                        $msg = "Excepción PDO: la recepcionista no ha sido registrada"; 
                    }


                }catch(Exception $e){
                    $msg = $e->getMessage(); 
                }

                //Redirigir con mensaje de error
                header("Location: /proyecto_gym_MVC/view/registro_recepcionista.php?msg=$msg");
                exit();
            }
        }
        
        public static function login() {
            if (isset($_POST['login'])) {
                try {
                    // Recuperamos los datos del formulario
                    $dni = $_POST['dni'];
                    $password = $_POST['password'];
        
                    require_once('conexionBBDD.php');
                    // Llamar al método del modelo para verificar credenciales
                    $usuario = Recepcionista::login($conn, $dni, $password);
        
                    // Verificar si el usuario existe
                    if ($usuario) {
                        session_start();
                        $_SESSION['nombre'] = $usuario; // Guardar el usuario en la sesión
                        // Redirigir a la página de inicio de sesión
                        header("Location: /proyecto_gym_MVC/view/bienvenida_recepcionista.php");
                        exit;
                    } else {
                        header("Location: /proyecto_gym_MVC/view/login_recepcionista.php?msg=Credenciales incorrectas");
                        exit;
                    }


                } catch (PDOException $e) {
                    $msg = $e->getMessage(); 
                    header("Location: /proyecto_gym_MVC/view/login_recepcionista.php?msg=$msg");
                    exit; 
                } catch (Exception $e) {
                    $msg = $e->getMessage();  
                    header("Location: /proyecto_gym_MVC/view/login_recepcionista.php?msg=$msg");
                    exit; 
                }
            }
        }
        

  
    
  /**
   * The function `logout` in PHP destroys the session and redirects the user to the login form for a
   * receptionist in a gym project.
   */
    public static function logout()
    {
        // Destruir la sesión
        session_start();
        session_unset();
        session_destroy();

        // Redirigir al formulario de login
        header('Location: /proyecto_gym_MVC/view/login_recepcionista.php');
        exit;
    }



    


}
