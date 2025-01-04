<!-- La clase `controladorSocios` gestiona la adición, modificación, visualización y eliminación de socios, con manejo de errores y redirección. -->

<?php
//Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir las clases necesarias para gestionar personas, trabajadores, clases, monitores y socios.
require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Clase.php';
require_once __DIR__ . '/../models/Monitor.php';
require_once __DIR__ . '/../models/Socio.php';

class controladorSocios
{
    /**
     * La función `addSocio` procesa los datos del formulario para agregar un nuevo socio con los detalles 
     * especificados y redirige al usuario según si el proceso fue exitoso o no.
     *
     * @return void
     *
     * @throws Exception Si ocurre un error durante la adición de un nuevo socio, la función lanzará 
     * una excepción.
     */
    public function addSocio()
    {
        try {
            // Obtener los datos del formulario
            $dni = $_POST['dni'];
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $fecha_nac = $_POST['fecha_nac'];
            $telefono = $_POST['telefono'];
            $email = $_POST['email'];
            $tarifa = $_POST['tarifa'];
            $cuenta_bancaria = $_POST['cuenta_bancaria'];

            // Llamada al método addSocio de la clase Socio
            $resultado = Socio::addSocio(
                $dni,
                $nombre,
                $apellidos,
                $fecha_nac,
                $telefono,
                $email,
                $tarifa,
                $cuenta_bancaria
            );

            // Si $resultado no es true, significa que hubo un error
            if ($resultado !== true) {
                //******************************************************************* RUTAS ***************************************************************************
                // Redirigir de vuelta al formulario con el mensaje de error
                header('Location: ../socios/addSocio.php?error=' . urlencode($resultado));
                exit;
            }
            //******************************************************************* RUTAS ***************************************************************************
            // Si todo fue correcto
            header('Location: ../socios/addSocio.php?exito=Socio añadido correctamente');
            exit;
        } catch (Exception $e) {
            // Capturar cualquier otra excepción y redirigir con el mensaje de error
            //******************************************************************* RUTAS ***************************************************************************
            header('Location: ../socios/addSocio.php?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * La función `modificarSocio` se utiliza para actualizar la información de un socio existente en el archivo JSON,
     * con manejo de errores y redirección según si el proceso es exitoso o no.
     * 
     * @return void
     *
     * @throws Exception Si ocurre un error durante el proceso de modificación, la función lanzará una excepción.
     */
    public function modificarSocio()
    {
        // Verificar si se recibieron datos del formulario mediante el método POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos del formulario
            $dni = $_POST['dni'];  // DNI del socio a modificar
            $nombre = $_POST['nombre'];  // Nombre del socio
            $apellidos = $_POST['apellidos'];  // Apellidos del socio
            $fecha_nac = $_POST['fecha_nac'];  // Fecha de nacimiento del socio
            $telefono = $_POST['telefono'];  // Número de teléfono del socio
            $email = $_POST['email'];  // Correo electrónico del socio
            $tarifa = $_POST['tarifa'];  // Tarifa asignada al socio
            $fecha_alta = $_POST['fecha_alta'];  // Fecha de alta del socio
            $fecha_baja = $_POST['fecha_baja'];  // Fecha de baja del socio (si se aplica)
            $cuenta_bancaria = $_POST['cuenta_bancaria'];  // Número de cuenta bancaria del socio

            // Cargar los socios desde el archivo JSON
            //******************************************************************* RUTAS ***************************************************************************
            $jsonFile = __DIR__ . '/../data/socios.json'; // Ruta al archivo JSON con los datos de los socios

            // Verificar si el archivo de datos existe
            if (file_exists($jsonFile)) {
                // Cargar los socios desde el archivo JSON
                $socios = json_decode(file_get_contents($jsonFile), true);

                // Verificar si los datos del JSON se cargaron correctamente
                if (!$socios) {
                    echo "<p style='color:red'>Error: No se pudieron cargar los datos de los socios.</p>";
                    return;
                }

                // Buscar al socio que se va a modificar por su DNI
                foreach ($socios as &$socio) {
                    if ($socio['dni'] === $dni) {
                        // Modificar los datos del socio con los valores del formulario
                        $socio['nombre'] = $nombre;
                        $socio['apellidos'] = $apellidos;
                        $socio['fecha_nac'] = $fecha_nac;
                        $socio['telefono'] = $telefono;
                        $socio['email'] = $email;
                        $socio['tarifa'] = $tarifa;
                        $socio['fecha_alta'] = $fecha_alta;
                        $socio['fecha_baja'] = $fecha_baja;
                        $socio['cuenta_bancaria'] = $cuenta_bancaria;

                        // Guardar los cambios en el archivo JSON
                        file_put_contents($jsonFile, json_encode($socios, JSON_PRETTY_PRINT));

                        // Redirigir al usuario a la misma página con mensaje de exito
                        //******************************************************************* RUTAS ***************************************************************************
                        header('Location: ../socios/modificarSocio.php?exito=Socio modificado correctamente');
                        exit;
                    }
                }

                // Si no se encuentra el socio con el DNI proporcionado, mostrar mensaje de error
                echo "<p style='color:red'>Error: No se encontró un socio con el DNI proporcionado.</p>";
            } else {
                // Si el archivo de datos no existe, mostrar mensaje de error
                echo "<p style='color:red'>Error: No se encontró el archivo de datos.</p>";
            }
        }
    }

    /**
     * La función `verSocio` verifica la existencia de un archivo, lo incluye y filtra y muestra
     * los datos de los socios según los parámetros proporcionados por el usuario.
     * 
     * @return void
     *
     * Esta función no devuelve un valor explícito, pero genera la salida HTML para mostrar los resultados
     * basados en la búsqueda de un socio según los parámetros proporcionados por el usuario.
     */
    public function verSocio()
    {
        // Ruta absoluta al archivo de vista principal
        //******************************************************************* RUTAS ***************************************************************************
        $file = $_SERVER['DOCUMENT_ROOT'] . '/view/socios/verSocio.php';

        // Verificar si la vista principal existe
        if (!file_exists($file)) {
            echo "El archivo no se encuentra en la ruta: $file";
            return;
        }

        // Incluir la vista verSocio.php para mostrar el formulario o la interfaz de búsqueda
        include $file;

        // Verificar si se han recibido parámetros de búsqueda (campo y valor)
        if (isset($_POST['campo'], $_POST['valor'])) {
            // Asignar los valores del formulario a las variables
            $campo = $_POST['campo'];
            $valor = $_POST['valor'];

            try {
                // Llamar al método `filtrarSocios` para obtener los socios filtrados
                $sociosEncontrados = Socio::filtrarSocios($campo, $valor);

                //******************************************************************* RUTAS ***************************************************************************
                // Ruta de la vista que mostrará los resultados filtrados
                $filtroView = __DIR__ . '/../view/socios/filtroSocio.php';

                // Verificar si la vista de filtro existe
                if (file_exists($filtroView)) {
                    // Incluir la vista para mostrar los resultados
                    include $filtroView;
                } else {
                    // Mostrar mensaje de error si no se encuentra la vista de filtro
                    echo "<p>No se encontró la vista de filtro de socios.</p>";
                }
            } catch (Exception $e) {
                // Mostrar mensaje de error si ocurre una excepción durante el filtrado
                echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            // Mensaje si no se han recibido los datos necesarios para la búsqueda
            echo "<p>No se han recibido datos para realizar la búsqueda.</p>";
        }
    }

    /**
     * La función `mostrarTodos` incluye un archivo de vista si existe, lee y decodifica los datos de un archivo
     * JSON, y valida los datos antes de mostrar un mensaje si hay problemas.
     * 
     * @return void
     *
     * Esta función no devuelve un valor explícito. En su lugar, genera la salida HTML mostrando mensajes si
     * el archivo de vista no se encuentra, si los datos no se pudieron cargar o si el archivo JSON está vacío.
     */
    public function mostrarTodos()
    {
        try {
            // Ruta al archivo JSON
            //******************************************************************* RUTAS ***************************************************************************
            $rutaJson = __DIR__ . '/../data/socios.json';

            // Verificar si el archivo existe
            if (!file_exists($rutaJson)) {
                throw new Exception("El archivo de datos no se encuentra.");
            }

            // Leer y decodificar los datos del archivo JSON
            $sociosJson = json_decode(file_get_contents($rutaJson), true);

            // Validar que los datos del JSON se hayan cargado correctamente
            if (!$sociosJson) {
                throw new Exception("No se pudieron cargar los datos de los socios o el archivo JSON está vacío.");
            }

            // Incluir primero la vista verSocio.php
            //******************************************************************* RUTAS ***************************************************************************
            include $_SERVER['DOCUMENT_ROOT'] . '/view/socios/verSocio.php';

            // Luego incluir la vista que muestra la tabla con todos los socios
            //******************************************************************* RUTAS ***************************************************************************
            include $_SERVER['DOCUMENT_ROOT'] . '/view/socios/todosSocios.php';
        } catch (Exception $e) {
            echo "<p style='color:red'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    /**
     * La función `eliminarSocio` en PHP maneja la eliminación de un socio basado en criterios específicos
     * (como DNI, teléfono o email) y redirige con mensajes de éxito o error según corresponda.
     * 
     * @return void
     *
     * Esta función no devuelve un valor explícito. En su lugar, redirige al usuario a diferentes vistas según
     * si la operación de eliminación fue exitosa o si ocurrió algún error.
     */
    public function eliminarSocio()
    {
        try {
            // Obtener los datos enviados desde el formulario
            $campo = $_POST['campo'] ?? null; // Puede ser dni, telefono o email
            $valor = $_POST['valor'] ?? null;

            // Verificar si ambos datos están presentes
            if (!$campo || !$valor) {
                throw new Exception("Debes seleccionar un campo y proporcionar un valor.");
            }

            // Llamar al método del modelo para eliminar el socio
            $mensaje = Socio::eliminarSocio($campo, $valor);

            //******************************************************************* RUTAS ***************************************************************************
            // Si todo fue correcto
            header('Location: ../socios/eliminarSocio.php?exito=Socio eliminado correctamente');
            exit;
        } catch (Exception $e) {
            //******************************************************************* RUTAS ***************************************************************************
            // Redirigir de vuelta al formulario con el mensaje de error
            header('Location: ../socios/eliminarSocio.php?error=No se puedo eliminar, hubo un error');
            exit;
        }
    }
}
