<!-- La clase `Socio` representa a un miembro con información personal, detalles de membresía y métodos para gestionar los datos del socio. -->

<?php
// Visualizar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


final class Socio extends Persona
{

   
 
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

     private $tarifa;
     private $fecha_alta;
     private $cuenta_bancaria;
    
        // Constructor vacío para poder crear los objetos con PDO --> fetchObject
    public function __construct() {
        // No hacer nada aquí
        
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
            throw new Exception("ERROR: No es posible modificar la fecha de alta de un socio.");
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


    public static function verSocios($conn){
       
        

        $sql = "SELECT * FROM SOCIOS"; 

        //Podemosusar query, por que en esta consulta el usuario no inyecta ningun dato a traves del formulario
        $resultados = $conn->query($sql); 

        $socios=[]; 
        while($socio = $resultados->fetchObject('Socio')){
            
            $socios[]=$socio; 

        }

        return $socios;

    }



    public static function filtrarSocios($conn, $propiedad, $valor){

        $propiedades_validas = ['dni', 'nombre', 'apellidos', 'tarifa']; 
        if(!in_array($propiedad, $propiedades_validas)) throw new PDOException('Se ha manipulado el formulario desde el inspector'); 

        $sql = "SELECT * FROM SOCIOS WHERE $propiedad = ?"; 

        $stmt = $conn->prepare($sql); 


    
        $stmt->bindParam(1, $valor); 

        if(!$stmt->execute()){
            throw new PDOException('Excepción PDO al ejecutar la consulta en la BBDD para filtrar usuarios'); 
        }


        $socios_filtrados=[]; 
        while($socio = $stmt->fetchObject('Socio')){
            $socios_filtrados[] = $socio;
        }

       

        return $socios_filtrados;

        
     
        
    }

    
    public static function addSocio($conn, $dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $fecha_alta, $cuenta_bancaria){

        // Insertado en la BBDDD
        $sql = "INSERT INTO SOCIOS (dni, nombre, apellidos, fecha_nac, telefono, email, tarifa, fecha_alta, cuenta_bancaria) 
        VALUES (:dni, :nombre, :apellidos, :fecha_nac, :telefono, :email, :tarifa, :fecha_alta, :cuenta_bancaria)";

       
            $stmt = $conn->prepare($sql);

            // Vincular los parámetros para evitar errores de inyección en la BBDD
            $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_nac', $fecha_nac, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':tarifa', $tarifa, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_alta', $fecha_alta, PDO::PARAM_STR);
            $stmt->bindParam(':cuenta_bancaria', $cuenta_bancaria, PDO::PARAM_STR);


            // Ejecutar la consulta o lanzar excepción en caso de error
           if($stmt->execute()) return true; 
           else throw new PDOException('Excepción PDO al ejecutar la modificación del socio'); 


}





    public static function eliminarSocio($conn, $dni) {
        $sql = "DELETE FROM SOCIOS WHERE DNI = ?"; 
        $stmt = $conn->prepare($sql);
        
        // Vincular el parámetro
        $stmt->bindParam(1, $dni, PDO::PARAM_STR);
        
        // Ejecutar la declaración
        if ($stmt->execute()) {
            return true; // Éxito
        } else {
            return false; // Fallo
        }
    }

    public static function buscarSocio($conn, $dni) {

        $sql = "SELECT * FROM SOCIOS WHERE DNI = ?"; 
        $stmt = $conn->prepare($sql);
        
        // Vincular el parámetro
        $stmt->bindParam(1, $dni, PDO::PARAM_STR);
        
        // Ejecutar la declaración
        if ($stmt->execute()) {
            // Recuperar el socio
            $socio = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verificar si se encontró el socio
            if ($socio) {
                return $socio; // Retornar el socio encontrado
            } else {
                return false; // No se encontró el socio
            }
        } else {
            throw new PDOException('Error en la ejecución de la consulta desde buscarSocio'); 
        }
    }

    public static function modificarSocio($conn, $dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $fecha_alta, $cuenta_bancaria){

         
        // update en la BBDD
         $sql = "UPDATE SOCIOS SET
         nombre = :nombre,
         apellidos = :apellidos,
         fecha_nac = :fecha_nac,
         telefono = :telefono,
         email = :email,
         tarifa = :tarifa,
         fecha_alta = :fecha_alta,
         cuenta_bancaria = :cuenta_bancaria
        WHERE dni = :dni";



            $stmt = $conn->prepare($sql);

            // Vincular los parámetros para evitar errores de inyección en la BBDD
            $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_nac', $fecha_nac, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':tarifa', $tarifa, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_alta', $fecha_alta, PDO::PARAM_STR);
            $stmt->bindParam(':cuenta_bancaria', $cuenta_bancaria, PDO::PARAM_STR);


            // Ejecutar la consulta o lanzar excepción en caso de error
           if($stmt->execute()) return true; 
           else throw new PDOException('Excepción PDO al ejecutar la modificación del socio'); 

    }




}





