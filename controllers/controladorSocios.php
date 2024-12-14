<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Clase.php';
require_once __DIR__ . '/../models/Monitor.php';
require_once __DIR__ . '/../models/Socio.php';


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
        $cuenta_bancaria = $_POST['cuenta_bancaria'];
        

        // Llamada a la clase para agregar el socio
        $exitoso = Socio::addSocio(
            $dni,
            $nombre,
            $apellidos,
            $fecha_nac,
            $telefono,
            $email,
            $tarifa,
            $cuenta_bancaria,
        );

        if ($exitoso) {
            // Registro correcto: redirigir a bienvenida
            header('Location: /view/bienvenida_recepcionista.php?msg=addSocio');
            exit;
        } else {
            // Error al agregar socio
            header('Location:/../view/socios/addSocio.php?msg=errorAddSocio');
            exit;
        }
    }
        public function modificarSocio() {
            try {
                // Obtener y validar los datos del formulario
                $dni = $_POST['dni'];
                if (!$dni) {
                    throw new Exception("El DNI es obligatorio.");
                }
    
                // Campos opcionales
                $nombre = $_POST['nombre'] ?? null;
                $apellidos = $_POST['apellidos'] ?? null;
                $fecha_nac = $_POST['fecha_nac'] ?? null;
                $edad = $_POST['edad'] ?? null;
                $telefono = $_POST['telefono'] ?? null;
                $email = $_POST['email'] ?? null;
                $tarifa = $_POST['tarifa'] ?? null;
                $fecha_alta = $_POST['fecha_alta'] ?? null;
                $fecha_baja = $_POST['fecha_baja'] ?? null;
                $cuenta_bancaria = $_POST['cuenta_bancaria'] ?? null;
    
                // Llamar al modelo para modificar el socio
                $mensaje = Socio::modificarSocio(
                    $dni, $nombre, $apellidos, $fecha_nac, $edad,
                    $telefono, $email, $tarifa, $fecha_alta, $fecha_baja, $cuenta_bancaria
                );
    
                // Redirigir con mensaje de éxito
                header('Location: /../view/bienvenida_recepcionista.php?msg=' . urlencode($mensaje));
                exit;
            } catch (Exception $e) {
                // Redirigir con mensaje de error
                header('Location: /../view/bienvenida_recepcionista.php?msg=' . urlencode($e->getMessage()));
                exit;
            }
        }
    
        public function verSocio() {
            $file = $_SERVER['DOCUMENT_ROOT'] . '/view/socios/verSocio.php';
    
            if (!file_exists($file)) {
                echo "El archivo no se encuentra en la ruta: $file";
                return;
            }
    
            include $file;
    
            if (isset($_POST['campo'], $_POST['valor'])) {
                $campo = $_POST['campo'];
                $valor = $_POST['valor'];
    
                try {
                    $sociosEncontrados = Socio::filtrarSocios($campo, $valor);
                    $filtroView = __DIR__ . '/../view/socios/filtroSocio.php';
    
                    if (file_exists($filtroView)) {
                        include $filtroView;
                    } else {
                        echo "<p>No se encontró la vista de filtro de socios.</p>";
                    }
                } catch (Exception $e) {
                    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            } else {
                echo "<p>No se han recibido datos para realizar la búsqueda.</p>";
            }
        }
    
    public function mostrarTodos() {
        
            // Ruta absoluta al archivo de vista principal
            $file = $_SERVER['DOCUMENT_ROOT'] . '/view/socios/verSocio.php';
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