<?php


require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';

class ControladorRecepcionista
{

   /**
    * The `registro` function in PHP handles the registration process for a receptionist, validating
    * and storing the form data, and redirecting based on the registration result.
    * 
    * @return In the provided PHP code snippet, the `registro()` function is checking if the data has
    * been sent via POST method. If the data has not been sent via POST, it includes a login view and
    * returns. If the data has been sent via POST, it retrieves the form data, attempts to register a
    * receptionist using the `Trabajador::registrar()` method, and then based on the
    */
    public function registro()
    {
        // Verificar que se han enviado los datos por POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            
            include __DIR__ . '/proyecto_gym_MVC/view/login_recepcionista.php';
            return;
        }

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
            header("Location: /proyecto_gym_MVC/view/login_recepcionista.php?action=login");
            exit();
        } else {
            
            session_start(); 
            $_SESSION['dni'] = $_POST['dni']; 
            header("Location: /proyecto_gym_MVC/view/registro_recepcionista.php?error=dni_existente");

            exit();
        }
    }


   /**
    * The `login` function in PHP checks credentials submitted via POST request, calls a model method
    * to verify them, and redirects based on the result.
    * 
    * @return If the `['REQUEST_METHOD']` is not 'POST', the function will include the login
    * receptionist view and return. If the method is 'POST', it will retrieve the data from the form,
    * call the model method to verify the credentials, and then redirect either to the welcome page if
    * the credentials are correct or back to the login page with an error message if the credentials
    * are incorrect
    */
    public function login()
    {
        // Incluir la vista si el método no es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . '/proyecto_gym_MVC/view/login_recepcionista.php';
            return;
        }

        // Recuperamos los datos del formulario
        $dni = $_POST['dni'] ?? '';
        $password = $_POST['password'] ?? '';

        // Llamar al método del modelo para verificar credenciales
        if (Trabajador::login($dni, $password)) {

            // Credenciales correctas: redirigir a bienvenida
            header('Location: /proyecto_gym_MVC/view/bienvenida_recepcionista.php');
            exit;
        } else {

            // Credenciales incorrectas: mostrar error
            header('Location: /proyecto_gym_MVC/view/login_recepcionista.php?error=credenciales_incorrectas');
            exit;
        }
    }

  /**
   * The function `logout` in PHP destroys the session and redirects the user to the login form for a
   * receptionist in a gym project.
   */
    public function logout()
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
