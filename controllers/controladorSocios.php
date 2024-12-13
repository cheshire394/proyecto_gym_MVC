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
    public function verSocio() {
        // Ruta absoluta al archivo de vista principal
        $file = $_SERVER['DOCUMENT_ROOT'] . '/proyecto_gym_MVC/view/socios/verSocio.php';
    
        // Verificar si la vista principal existe
        if (file_exists($file)) {
            include $file;
        } else {
            echo "El archivo no se encuentra en la ruta: $file";
            return; // Salir si el archivo no existe
        }
    
        // Verificar si se recibieron los datos del formulario
        if (isset($_POST['campo']) && isset($_POST['valor'])) {
            $campo = $_POST['campo'];
            $valor = $_POST['valor'];
    
            // Ruta al archivo JSON de socios
            $jsonPath = __DIR__ . '/../data/socios.json';
    
            // Verificar si el archivo JSON existe y se puede leer
            if (!file_exists($jsonPath)) {
                echo "<p>El archivo de socios no existe.</p>";
                return;
            }
    
            // Leer y decodificar el archivo JSON
            $sociosJson = json_decode(file_get_contents($jsonPath), true);
    
            // Verificar si el JSON es válido
            if (!is_array($sociosJson)) {
                echo "<p>No se pudo leer el archivo de socios o el formato JSON es incorrecto.</p>";
                return;
            }
    
            // Verificar que el campo sea válido
            if (!array_key_exists($campo, $sociosJson[0])) {
                echo "<p>El campo seleccionado no es válido.</p>";
                return;
            }
    
            // Filtrar los socios por el campo y el valor
            $sociosEncontrados = array_filter($sociosJson, function($socio) use ($campo, $valor) {
                return isset($socio[$campo]) && stripos($socio[$campo], $valor) !== false;
            });
    
            // Incluir la vista de filtro con los resultados
            $filtroView = __DIR__ . '/../view/socios/filtroSocio.php';
            if (file_exists($filtroView)) {
                include $filtroView;
            } else {
                echo "<p>No se encontró la vista de filtro de socios.</p>";
            }
        } else {
            echo "<p>No se han recibido datos para realizar la búsqueda.</p>";
        }
    }
    public function mostrarTodos() {
        
            // Ruta absoluta al archivo de vista principal
            $file = $_SERVER['DOCUMENT_ROOT'] . '/proyecto_gym_MVC/view/socios/verSocio.php';
            // Verificar si la vista principal existe

            if (file_exists($file)) {
                include $file;
            } else {
                echo "El archivo no se encuentra en la ruta: $file";
                return; // Salir si el archivo no existe
            }

            // Ruta al archivo JSON
            $rutaJson = __DIR__ . '/../data/socios.json';
        
            // Leer y decodificar los datos del archivo JSON
            $sociosJson = json_decode(file_get_contents($rutaJson), true);
        
            // Validar que los datos sean correctos
            if (!$sociosJson) {
                echo "<p>No se pudieron cargar los datos de los socios o el archivo JSON está vacío.</p>";
                return;
            }
        
            // Incluir la vista y pasarle los datos de los socios
            include __DIR__ . '/../view/socios/todosSocios.php';
        }
        
    }
    
?>