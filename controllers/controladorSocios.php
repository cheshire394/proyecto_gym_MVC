<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Clase.php';
require_once __DIR__ . '/../models/Monitor.php';



class controladorSocios {

    public function addSocio() {
        // Obtener los datos del formulario
        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $fecha_nac = $_POST['fecha_nac'];
        $edad = $_POST['edad'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $tarifa = $_POST['tarifa'];
        $fecha_alta = $_POST['fecha_alta'];
        $fecha_baja = isset($_POST['fecha_baja']) ? $_POST['fecha_baja'] : null; // Campo opcional
        $reservas_clases = $_POST['reservas_clases'];
        $cuenta_bancaria = $_POST['cuenta_bancaria'];
        

        // Llamada a la clase para agregar el socio
        $exitoso = Socio::addSocio(
            $dni,
            $nombre,
            $apellidos,
            $fecha_nac,
            $edad,
            $telefono,
            $email,
            $tarifa,
            $fecha_alta,
            $fecha_baja,
            $reservas_clases,
            $cuenta_bancaria
        );

        if ($exitoso) {
            // Registro correcto: redirigir a bienvenida
            header('Location: /MVC2/view/socios/bienvenida_recepcion.php?msg=addSocio');
            exit;
        } else {
            // Error al agregar socio
            header('Location: /MVC2/view/socios/addSocio.php?msg=errorAddSocio');
            exit;
        }
    }

    public function modificarSocio() {
        // Obtener los datos del formulario
        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $fecha_nac = $_POST['fecha_nac'];
        $edad = $_POST['edad'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $tarifa = $_POST['tarifa'];
        $fecha_alta = $_POST['fecha_alta'];
        $fecha_baja = isset($_POST['fecha_baja']) ? $_POST['fecha_baja'] : null; // Campo opcional
        $reservas_clases = $_POST['reservas_clases'];
        $cuenta_bancaria = $_POST['cuenta_bancaria'];
    
        // Validación de campos
        if (empty($dni) || empty($nombre) || empty($apellidos) || empty($fecha_nac) || empty($edad) || empty($telefono) || empty($email) || empty($tarifa) || empty($fecha_alta) || empty($reservas_clases) || empty($cuenta_bancaria)) {
            // Si faltan campos importantes, redirigir con mensaje de error
            header('Location: /MVC2/view/socios/modificarSocio.php?msg=errorCamposVacios');
            exit;
        }
    
        // Obtener los datos del archivo JSON de socios
        $sociosJson = json_decode(file_get_contents(__DIR__ . '/../data/socios.json'), true);
    
        // Comprobar si el archivo JSON contiene socios
        if ($sociosJson !== null) {
            // Buscar si el socio con el DNI ya existe
            $socioExistente = null;
            foreach ($sociosJson as &$socio) {
                if ($socio['dni'] === $dni) {
                    $socioExistente = &$socio;  // Encontramos el socio y lo asignamos a la variable $socioExistente
                    break;
                }
            }
    
            if ($socioExistente) {
                // Modificar los datos del socio
                $socioExistente['nombre'] = $nombre;
                $socioExistente['apellidos'] = $apellidos;
                $socioExistente['fecha_nac'] = $fecha_nac;
                $socioExistente['edad'] = $edad;
                $socioExistente['telefono'] = $telefono;
                $socioExistente['email'] = $email;
                $socioExistente['tarifa'] = $tarifa;
                $socioExistente['fecha_alta'] = $fecha_alta;
                $socioExistente['fecha_baja'] = $fecha_baja;
                $socioExistente['reservas_clases'] = $reservas_clases;
                $socioExistente['cuenta_bancaria'] = $cuenta_bancaria;
    
                // Guardar el array actualizado en el archivo JSON
                file_put_contents(__DIR__ . '/../data/socios.json', json_encode($sociosJson, JSON_PRETTY_PRINT));
    
                // Modificación exitosa: redirigir a una página de éxito
                header('Location: /MVC2/view/socios/modificarSocio.php?msg=modSocio');
                exit;
            } else {
                // Si el socio no existe
                header('Location: /MVC2/view/socios/modificarSocio.php?msg=socioNoEncontrado');
                exit;
            }
        } else {
            // Error al leer el archivo JSON o está vacío
            echo "Error al leer el archivo de socios.";
            exit;
        }
    }
}
    
?>