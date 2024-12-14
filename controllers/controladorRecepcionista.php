<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';

class ControladorRecepcionista
{

    public function registro()
    {
        // Verificar que se han enviado los datos por POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . '/../view/login_recepcionista.php';
            return;
        }

        echo "PRUEBA: estoy en regitro de controllers.php";


        // Recuperar datos del formulario
        $datosRecepcionista = [
            'dni' => $_POST['dni'],
            'nombre' => $_POST['nombre'],
            'apellidos' => $_POST['apellidos'],
            'fecha_nac' => $_POST['fecha_nac'],
            'telefono' => $_POST['telefono'],
            'email' => $_POST['email'],
            'cuenta_bancaria' => $_POST['cuenta_bancaria'],
            'funcion' => $_POST['funcion'],
            'sueldo' => $_POST['sueldo'],
            'horas_extra' => $_POST['horas_extra'],
            'jornada' => $_POST['jornada'],
            'password' => $_POST['password']
        ];

        // Intentar registrar
        $resultado = Trabajador::registrar($datosRecepcionista);

        if ($resultado) {
            // Registro exitoso
            header("Location: /MVC2/view/login_recepcionista.php?action=login");
            exit();
        } else {

            session_start();
            $_SESSION['dni'] = $_POST['dni'];
            header("Location: /MVC2/view/registro_recepcionista.php?error=dni_existente");
            exit;


            exit();
        }
    }


    public function login()
    {
        // Incluir la vista si el método no es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . '/../view/login_recepcionista.php';
            return;
        }

        // Recuperar datos del formulario
        $dni = $_POST['dni'] ?? '';
        $password = $_POST['password'] ?? '';

        // Llamar al método del modelo para verificar credenciales
        if (Trabajador::login($dni, $password)) {

            // Credenciales correctas: redirigir a bienvenida
            header('Location: /MVC2/view/bienvenida_recepcionista.php');
            exit;
        } else {

            // Credenciales incorrectas: mostrar error
            header('Location: /MVC2/view/login_recepcionista.php?error=credenciales_incorrectas');
            exit;
        }
    }

    public function logout()
    {
        // Destruir la sesión
        session_start();
        session_unset();
        session_destroy();

        // Redirigir al formulario de login
        header('Location: /MVC2/view/login_recepcionista.php');
        exit;
    }


    //NO FUNCIONA
    /* public function olvidado($dni){

      Trabajador::olvidado($dni); 
      header("Location: /MVC2/view/registro_recepcionista.php?error=nuevo_dni"); 
      exit; 

    }*/
}
