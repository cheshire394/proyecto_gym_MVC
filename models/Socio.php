<!-- La clase `Socio` representa a un miembro con información personal, detalles de membresía y métodos para gestionar los datos del socio. -->

<?php
// Visualizar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

final class Socio extends Persona
{

    const RUTA_JSON_SOCIOS= __DIR__ . "/../data/socios.json"; 
    private $tarifa;
    private $fecha_alta;
    private $cuenta_bancaria;
    private static $contador_socios = 0;


    //******************************************************************* RUTAS ***************************************************************************
    private static $rutaJSON = __DIR__ . '/../data/socios.json';

    /**
     * Constructor para la clase Socio que valida los datos de entrada, establece valores predeterminados
     * y almacena el miembro en un array de la clase.
     * 
     * @param string $dni El DNI del socio, que debe cumplir con el formato de 8 dígitos seguidos de una letra mayúscula.
     * @param string $nombre El nombre del socio.
     * @param string $apellidos Los apellidos del socio.
     * @param string $fecha_nac La fecha de nacimiento del socio.
     * @param string $telefono El número de teléfono del socio.
     * @param string $email El correo electrónico del socio. Se valida para asegurar que tiene el formato adecuado.
     * @param string $tarifa La tarifa de suscripción del socio, con un valor predeterminado de "1".
     * @param string $cuenta_bancaria El número de cuenta bancaria del socio.
     * 
     */
    function __construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $cuenta_bancaria, $fecha_alta)
    {
     
        $this->tarifa = $tarifa;
        $this->cuenta_bancaria = $cuenta_bancaria;
        $this->fecha_alta = $fecha_alta;

        // Incrementar contador de socios
        self::$contador_socios++;

        // Llamada al constructor de la clase base 
        parent::__construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email);

     
    }


    // Método mágico para establecer propiedades
    /**
     * El método mágico __set en PHP se utiliza para asignar un valor a una propiedad en una clase,
     * aplicando restricciones específicas y manejo de errores.
     * 
     * @param string $name El nombre de la propiedad a la que se le asignará un valor.
     * @param mixed $value El valor que se asignará a la propiedad especificada.
     * 
     * @throws Exception Lanza una excepción si la propiedad no existe en la clase.
     * @throws datosIncorrectos Lanza una excepción si se intenta modificar la propiedad 'fecha_alta'.
     */
    public function __set($name, $value)
    {
        // Evita modificar la propiedad 'fecha_alta'
        if ($name == 'fecha_alta') {
            throw new datosIncorrectos("ERROR: No es posible modificar la fecha de alta de un socio.");
        }

        // Verifica si la propiedad existe y asigna el valor
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            // Lanza una excepción si la propiedad no existe
            throw new Exception("ERROR: La propiedad '$name' no existe en la clase Socio.");
        }
    }


    // Método mágico para obtener propiedades
    /**
     * El método mágico __get en PHP se utiliza para recuperar dinámicamente las propiedades inaccesibles
     * de un objeto.
     * 
     * @param string $name El nombre de la propiedad a obtener.
     * 
     * @return mixed Retorna el valor de la propiedad si existe. Si la propiedad no existe, lanza una excepción con un mensaje de error.
     * 
     * @throws Exception Si la propiedad no existe en la clase, se lanzará una excepción con el mensaje "ERROR: La propiedad '$name' no existe en la clase Socio."
     */
    public function __get($name)
    {
        // Verifica si la propiedad existe en el objeto actual
        if (property_exists($this, $name)) {
            return $this->$name; // Retorna el valor de la propiedad
        } else {
            // Lanza una excepción si la propiedad no existe
            throw new Exception("ERROR: La propiedad '$name' no existe en la clase Socio.");
        }
    }



    public static function crearObjetosSocio() {
        $sociosObjetos = [];
        $socios = self::socioJSON(); 
        foreach ($socios as $dni => $socio) {
            $socioObj = new Socio(
                $socio['dni'],
                $socio['nombre'],
                $socio['apellidos'],
                $socio['fecha_nac'],
                $socio['telefono'],
                $socio['email'],
                $socio['tarifa'],
                $socio['cuenta_bancaria'] ?? 'no aportada',
                $socio['fecha_alta']
            );
            $sociosObjetos[] = $socioObj;
        }
        return $sociosObjetos; 
    }

    public static function socioJSON() {

        // si el archivo JSON existe
        if (!file_exists(self::RUTA_JSON_SOCIOS)) {
            file_put_contents(self::RUTA_JSON_SOCIOS, json_encode([]));
        }

        
        $socios = file_get_contents(self::RUTA_JSON_SOCIOS);
        
        $socios = json_decode($socios, true);

        return $socios ?: [];
    }

   
    
    /**
     * Filtra los socios según un campo y un valor específico.
     * 
     * @param string $campo El campo del socio por el cual se desea filtrar (por ejemplo, 'nombre', 'dni', etc.).
     * @param string $valor El valor que se buscará en el campo especificado.
     * 
     * @return array Un array de socios que coinciden con el filtro, o un array vacío si no hay coincidencias.
     */
    public static function filtrarSocios($campo, $valor)
    {
        // Cargar la lista de socios
        $socios = self::cargarSocios();

        // Filtrar los socios que coincidan con el valor en el campo especificado
        return array_filter($socios, function ($socio) use ($campo, $valor) {
            // Comprobar si el campo existe y si contiene el valor (ignorando mayúsculas/minúsculas)
            return isset($socio[$campo]) && stripos($socio[$campo], $valor) !== false;
        });
    }


    /**
     * Añade un nuevo socio a la lista de socios, verificando que no exista un socio con el mismo DNI.
     * 
     * @param string $dni El DNI del socio.
     * @param string $nombre El nombre del socio.
     * @param string $apellidos Los apellidos del socio.
     * @param string $fecha_nac La fecha de nacimiento del socio.
     * @param string $telefono El teléfono del socio.
     * @param string $email El correo electrónico del socio.
     * @param string $tarifa La tarifa del socio.
     * @param string $cuenta_bancaria La cuenta bancaria del socio.
     * 
     * @return bool|string Retorna true si el socio se añade correctamente, o un mensaje de error si ocurre un problema.
     */
    public static function addSocio(string $dni, string $nombre, string $apellidos, string $fecha_nac, string $telefono, string $email, string $tarifa, string $cuenta_bancaria, string $fecha_alta)
    {
        try {
            // Leer socios existentes
            $sociosJson = self::cargarSocios();

            // Verificar si ya existe un socio con el mismo DNI
            foreach ($sociosJson as $socio) {
                if ($socio['dni'] === $dni) {
                    return "Ya existe un socio con el DNI $dni";
                }
            }

            // Si no existe, crear el nuevo socio
          
            $nuevoSocio = new Socio($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $cuenta_bancaria,$fecha_alta);

            // Guardar el socio en el archivo JSON
            $sociosJson[] = $nuevoSocio->toArray();
            self::guardarSocios($sociosJson);

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Carga la lista de socios desde el archivo JSON.
     * 
     * @return array Retorna un array con la lista de socios. Si el archivo no existe o está vacío, retorna un array vacío.
     */
    private static function cargarSocios(): array
    {
        // Verifica si el archivo JSON existe
        if (!file_exists(self::$rutaJSON)) {
            return []; // Retorna un array vacío si el archivo no existe
        }

        // Lee el contenido del archivo JSON
        $sociosJson = file_get_contents(self::$rutaJSON);

        // Decodifica el JSON en un array asociativo y retorna, o un array vacío si falla
        return json_decode($sociosJson, true) ?? [];
    }

    /**
     * Guarda la lista de socios en el archivo JSON.
     * 
     * @param array $socios Array con la lista de socios a guardar.
     * 
     * @return void
     */
    private static function guardarSocios(array $socios)
    {
        // Convierte el array de socios a JSON y lo guarda en el archivo
        file_put_contents(
            self::$rutaJSON,
            json_encode($socios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * Busca un socio por un campo específico y su valor.
     * 
     * @param string $campo El campo por el cual buscar (por ejemplo, 'dni', 'email', etc.).
     * @param mixed $valor El valor del campo a buscar.
     * 
     * @return array|null Retorna un array con los datos del socio si se encuentra, o null si no existe.
     */
    public static function buscarSocio(string $campo, $valor): ?array
    {
        // Cargar la lista de socios
        $socios = self::cargarSocios();

        // Recorrer los socios y buscar coincidencias
        foreach ($socios as $socio) {
            if ($socio[$campo] === $valor) {
                // Retorna el socio si coincide con el valor buscado
                return $socio;
            }
        }

        // Retorna null si no se encuentra ningún socio
        return null;
    }

    /**
     * Modifica los datos de un socio identificado por su DNI.
     * 
     * Actualiza solo los campos proporcionados y guarda los cambios en el archivo JSON.
     * 
     * @param string $dni DNI del socio a modificar.
     * @param string|null $nombre Nuevo nombre (opcional).
     * @param string|null $apellidos Nuevos apellidos (opcional).
     * @param string|null $fecha_nac Nueva fecha de nacimiento (opcional).
     * @param string|null $telefono Nuevo teléfono (opcional).
     * @param string|null $email Nuevo email (opcional).
     * @param string|null $tarifa Nueva tarifa (opcional).
     * @param string|null $fecha_alta Nueva fecha de alta (opcional).
     * @param string|null $fecha_baja Nueva fecha de baja (opcional).
     * @param string|null $cuenta_bancaria Nueva cuenta bancaria (opcional).
     * 
     * @return string Mensaje de éxito si el socio fue modificado.
     * 
     * @throws Exception Si no se encuentra el socio o si ocurren problemas al cargar los datos.
     */
    public static function modificarSocio(
        $dni,
        $nombre = null,
        $apellidos = null,
        $fecha_nac = null,
        $telefono = null,
        $email = null,
        $tarifa = null,
        $fecha_alta = null,
        $fecha_baja = null,
        $cuenta_bancaria = null
    ) {
        // Ruta al archivo JSON donde se almacenan los socios
        //******************************************************************* RUTAS ***************************************************************************
        $rutaJson = __DIR__ . '/data/socios.json';

        // Leer el archivo JSON y decodificarlo
        $sociosJson = json_decode(file_get_contents($rutaJson), true);

        // Validar si se pudieron cargar los datos
        if (!$sociosJson) {
            throw new Exception("No se pudieron cargar los datos de los socios.");
        }

        // Buscar el socio por DNI
        foreach ($sociosJson as &$socio) {
            if ($socio['dni'] === $dni) {
                // Actualizar los campos proporcionados
                if ($nombre !== null) $socio['nombre'] = $nombre;
                if ($apellidos !== null) $socio['apellidos'] = $apellidos;
                if ($fecha_nac !== null) $socio['fecha_nac'] = $fecha_nac;
                if ($telefono !== null) $socio['telefono'] = $telefono;
                if ($email !== null) $socio['email'] = $email;
                if ($tarifa !== null) $socio['tarifa'] = $tarifa;
                if ($fecha_alta !== null) $socio['fecha_alta'] = $fecha_alta;
                if ($fecha_baja !== null) $socio['fecha_baja'] = $fecha_baja;
                if ($cuenta_bancaria !== null) $socio['cuenta_bancaria'] = $cuenta_bancaria;

                // Guardar los cambios en el archivo JSON
                file_put_contents($rutaJson, json_encode($sociosJson, JSON_PRETTY_PRINT));
                return "Socio con DNI $dni modificado correctamente.";
            }
        }

        // Lanza una excepción si no se encuentra el socio
        throw new Exception("No se encontró un socio con el DNI $dni.");
    }


    /**
     * Convierte las propiedades del objeto en un array asociativo.
     * 
     * @return array Array con las propiedades del objeto.
     */
    private function toArray(): array
    {
        // Retorna un array con las propiedades del objeto
        return [
            'dni' => $this->dni,
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'fecha_nac' => $this->fecha_nac,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'tarifa' => $this->tarifa,
            // Usa la fecha actual si no existe fecha de alta
            'fecha_alta' => $this->fecha_alta ?? date('Y-m-d'),
            'cuenta_bancaria' => $this->cuenta_bancaria,
        ];
    }


    /**
     * Muestra los datos de todos los socios registrados.
     * 
     * @return void
     */
    /*public static function mostrarSocios()
    {
        // Recorre todos los socios almacenados en la propiedad estática $socios
        foreach (self::$socios as $dni => $obj_socio) {
            // Obtiene las propiedades del objeto socio
            $propiedades = get_object_vars($obj_socio);

            // Muestra el encabezado con el DNI del socio
            echo "<b>DATOS DEL SOCIO $dni:</b><br>";

            // Recorre las propiedades del socio
            foreach ($propiedades as $propiedad => $value) {
                // Omite las propiedades que son arrays
                if (is_array($value)) continue;

                // Muestra la propiedad y su valor
                echo $propiedad . " => " . $value . "<br>";
            }

            // Espaciado entre socios
            echo "<br>";
        }
    }*/


    /**
     * Muestra las propiedades del objeto que no sean arrays.
     * 
     * @return void
     */
    public function verSocio()
    {
        // Obtiene todas las propiedades del objeto
        $propiedades = get_object_vars($this);

        // Recorre las propiedades del objeto
        foreach ($propiedades as $propiedad => $value) {
            // Omite las propiedades que son arrays
            if (is_array($value)) continue;

            // Muestra el nombre de la propiedad y su valor
            echo $propiedad . " => " . $value . "<br>";
        }
    }


    /**
     * The function "darBajaSocio" is used to mark a member as inactive by setting the "fecha_baja"
     * property, decreasing the total count of members, adding the member to a list of inactive
     * members, and removing the member from the active members list.*/
    public static function eliminarSocio($campo, $valor)
    {
        try {
            // Cargar los socios desde el archivo JSON
            $socios = self::cargarSocios();

            // Buscar y eliminar el socio con el campo y valor especificados
            $sociosActualizados = array_filter($socios, function ($socio) use ($campo, $valor) {
                return !isset($socio[$campo]) || $socio[$campo] !== $valor;
            });

            // Si no hay cambios, significa que no se encontró el socio
            if (count($socios) === count($sociosActualizados)) {
                throw new Exception("No se encontró ningún socio con $campo igual a '$valor'.");
            }

            // Guardar los socios actualizados en el archivo JSON
            self::guardarSocios(array_values($sociosActualizados));

            return "El socio con $campo igual a '$valor' ha sido eliminado correctamente.";
        } catch (Exception $e) {
            throw new Exception("Error al eliminar el socio: " . $e->getMessage());
        }
    }
}
