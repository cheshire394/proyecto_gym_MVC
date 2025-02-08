<!-- La clase `Socio` representa a un miembro con información personal, detalles de membresía y métodos para gestionar los datos del socio. -->

<?php
// Visualizar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


final class Socio extends Persona
{

   
     private $tarifa;
     private $fecha_alta;
     private $cuenta_bancaria;
    
        
    public function __construct() {
        
        // Constructor vacío para poder crear los objetos con PDO --> fetchObject
    }


 
    public function __set($name, $value)
    {
    

        // Verifica si la propiedad existe y asigna el valor
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            // Lanza una excepción si la propiedad no existe
            throw new Exception("ERROR: La propiedad '$name' no existe en la clase Socio.");
        }
    }


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


    public static function verSocios(){
       

        require_once  __DIR__ . '/../data/conexionBBDD.php'; 
        
        $sql = "SELECT * FROM SOCIOS"; 


       $stmt = $conn->prepare($sql);
       $stmt->execute();

        $socios=[]; 
        while($socio = $stmt->fetchObject('Socio')){
            
            $socios[]=$socio; 

        }

        return $socios;

    }



    public static function filtrarSocios($propiedad, $valor){



        $propiedades_validas = ['dni', 'nombre', 'apellidos', 'tarifa']; 
        if(!in_array($propiedad, $propiedades_validas)) throw new PDOException('Se ha manipulado el formulario desde el inspector'); 


        require_once  __DIR__ . '/../data/conexionBBDD.php'; 

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

    
    public static function addSocio($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $fecha_alta, $cuenta_bancaria){


        require_once  __DIR__ . '/../data/conexionBBDD.php'; 
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
           else throw new PDOException('Excepción PDO al ejecutar la insercción del socio en la BBDD'); 


}





    public static function eliminarSocio($dni) {

        require_once  __DIR__ . '/../data/conexionBBDD.php'; 
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

    public static function buscarSocio($dni) {

        require_once  __DIR__ . '/../data/conexionBBDD.php'; 

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

    public static function modificarSocio($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $fecha_alta, $cuenta_bancaria){

        require_once  __DIR__ . '/../data/conexionBBDD.php'; 
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


    public static function inscribirClase($id_clase){

       

    }




}





