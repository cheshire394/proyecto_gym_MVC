<?php

require_once __DIR__ . '/../models/datosIncorrectos.php'; //excepción personalizada
require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Monitor.php';
require_once __DIR__ . '/../models/Clase.php';

class ControladorClases {

    
    /**
     * The function `addClase` in PHP processes form data to add a new class, redirects to a success
     * message or error message page based on the outcome, and handles exceptions.
     */
    public static function addClase() {

        //recogemos los valores del formulario (son required, no es necesario comprobar que existan):
        $dni_monitor = $_POST['dni_monitor'];
        $nombre_actividad = $_POST['nombre_actividad'];
        $dia_semana = $_POST['dia_semana'];
        $hora_inicio = $_POST['hora_inicio'];

        try {

            
                $exitoso = Clase::addClase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio);


                //Mensaje de exito, redirige al horario para ver la nueva clase añadida: 
                if ($exitoso) {
                    header('Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=addClase');
                    exit;
                } 

                //este método puede capturar excepciones en la manipulacion de getter y setter, y tambíen captura Excepción si los datos de los monitores no se han actualizado correctamente,
                //enviamos la información de error al usuario a tráves del url.
            } catch (Exception $e) {
            $mensaje = urlencode($e->getMessage());
            header("Location: /proyecto_gym_MVC/view/clases/addClase.php?msg=$mensaje");
            exit;

        }
    }




  /**
   * The function `mostrar_todas_Clases` retrieves all gym classes, sorts them by schedule, and returns
   * the sorted list.
   * 
   * @return The `mostrar_todas_Clases` function is returning the result of the `ordenarHorario` method
   * called on the `` array after retrieving the classes' schedule using the `getHorario_gym`
   * method.
   */
    public static function mostrar_todas_Clases() {
            try {
                
                $clases = Clase::getHorario_gym();

                $ordenar_horario = Clase::ordenarHorario($clases); 
            
                return $ordenar_horario; 


            } catch (datosIncorrectos $e) {
                $mensaje = urlencode($e->datosIncorrectos());
                header("Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=$mensaje");
                exit;
            } catch (Exception $e) {
                $mensaje = urlencode($e->getMessage());
                header("Location: /proyecto_gym_MVC/view/clases//verClases.php?msg=$mensaje");
                exit;
            }
    }




   /**
    * The function `mostrar_clases_filtradas` in PHP processes user input to filter classes based on a
    * specified property, handling exceptions and redirecting with error messages if needed.
    * 
    * @return The `mostrar_clases_filtradas` function is returning the variable ``,
    * which contains the result of calling the `Clase::Clases_filtradas` method with the filtered
    * property and value as parameters.
    */
    public static function mostrar_clases_filtradas() {

            //recogermos el valor del formulario (required): 
            $propiedad_filtrada = $_POST['propiedad_filtrada'];


            //Ajustamos la entrada al método, dependiendo del parametro recibido, ya que dni, necesita converir a mayusculas: 
            if ($propiedad_filtrada == 'dni_monitor') {
                $valor_filtrado = htmlentities(strtoupper(trim($_POST['valor_filtrado'])));
            } else {
                $valor_filtrado = htmlentities(strtolower(trim($_POST['valor_filtrado'])));
            }

            try {


                $clases_filtradas = Clase::Clases_filtradas($propiedad_filtrada, $valor_filtrado);

                return $clases_filtradas; 

            //Datos incorrectos, envia los mensajes de error por parte de usuario y exception son mensaje de error provocados por nuestro código
            } catch (datosIncorrectos $e) {
                $mensaje = urlencode($e->datosIncorrectos());
                header("Location: /proyecto_gym_MVC/view/clases/clasesFiltro.php?msg=$mensaje");
                exit;
            } catch (Exception $e) {
                $mensaje = urlencode($e->getMessage());
                header("Location: /proyecto_gym_MVC/view/clases//clasesFiltro.php?msg=$mensaje");
                exit;
            }
    }





  /**
   * The function `sustituirMonitor` in PHP handles the substitution of a monitor for a class based on
   * user input, redirecting to different pages based on success or error.
   */
    public static function sustituirMonitor() {

        //recogemos los valores del formulario: 
        $dni_monitor_sustituto = $_POST['dni_monitor'];
        $dia_semana = $_POST['dia_semana'];
        $hora_inicio = $_POST['hora_inicio'];

        try {

            $exitoso = Clase::sustituirMonitor($dni_monitor_sustituto, $dia_semana, $hora_inicio);

            if ($exitoso) {
                header('Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=sustituido');
                exit;
            }


            //Datos incorrectos, envia los mensajes de error por parte de usuario, exception son mensaje de error provocados por nuestro código
        } catch (datosIncorrectos $e) {
            $mensaje = urlencode($e->datosIncorrectos());
            header("Location: /proyecto_gym_MVC/view/clases/sustituirMonitor.php?msg=$mensaje");
            exit;
        } catch (Exception $e) {
            $mensaje = urlencode($e->getMessage());
            header("Location: /proyecto_gym_MVC/view/clases/sustituirMonitor.php?msg=$mensaje");
            exit;
        }
    }




   /**
    * The function `eliminarDisciplina` in PHP is used to delete a discipline from a gym project,
    * handling exceptions and redirecting based on success or failure.
    */
    public static function eliminarDisciplina() {

        $nombre_actividad = $_POST['nombre_actividad'];

        try {
            $exitoso = Clase::eliminarDisciplina($nombre_actividad);

            if ($exitoso) {
                header('Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=eliminadaDisciplina');
                exit;
            }


            //La excepción saltará si no existe ninguna clase guardada el json, o no hay ninguna clase con el nombre de actividad seleccionado
        } catch (datosIncorrectos $e) {
            $mensaje = urlencode($e->datosIncorrectos());
            header("Location: /proyecto_gym_MVC/view/clases/eliminarDisciplina.php?msg=$mensaje");
            exit;
        }
    }


    /**
     * The function `eliminarClase` in PHP collects form values, constructs an `id_clase` to delete,
     * attempts to delete the class using the `Clase` class, and redirects based on success or failure.
     */
    public static function eliminarClase() {

        //recogemos los valores del formulario
        $dia = $_POST['dia_semana'];
        $hora = $_POST['hora_inicio']; 

        //construimod el id_clase a eliminar: 
        $id_clase=$dia."-".$hora; 

        try {

            $exitoso = Clase::eliminarClase($id_clase);

            if ($exitoso) {
                header('Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=eliminarClase');
                exit;
            }

            //La excepción saltará si no existe ninguna clase guardada el json, o bien no hay ninguna clase con ese id
        } catch (datosIncorrectos $e) {
            $mensaje = urlencode($e->datosIncorrectos());
            header("Location: /proyecto_gym_MVC/view/clases/eliminarClase.php?msg=$mensaje");
            exit;
        }
    }
}
