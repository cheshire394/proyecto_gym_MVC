

<?php
// Visualizar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../data/conexionBBDD.php';

final class Socio extends Persona
{

    const cuota_tarifa1 = 20.00; 
    const cuota_tarifa2 = 25.00; 
    const cuota_tarifa3 = 30.00; 
    const cuota_ilimitada = 45.00; 

    private $cuota_mensual; 
    private $tarifa;
    private $fecha_alta;
          
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
       

        global $conn; 
        
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


        global $conn; 

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


    private static function calcular_cuota_mensual($tarifa){
         //Adaptamos la cuota a la tarifa seleccionada:
            switch($tarifa){
                case '1': 
                    $cuota_mensual= Socio::cuota_tarifa1; 
                    break;
                case '2':
                    $cuota_mensual= Socio::cuota_tarifa2; 
                    break;
                case '3':
                    $cuota_mensual= Socio::cuota_tarifa3;
                    break;
                default:
                $cuota_mensual= Socio::cuota_ilimitada; 
                break; 
            }

            return $cuota_mensual; 
    }

    
    public static function addSocio($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $fecha_alta, $cuenta_bancaria){


        global $conn; 


        //calculamos la cuota segun la tarifa
        $cuota_mensual= self::calcular_cuota_mensual($tarifa); 


        // Insertado en la BBDDD
        $sql = "INSERT INTO SOCIOS (dni, nombre, apellidos, fecha_nac, telefono, email, tarifa, cuota_mensual,fecha_alta, cuenta_bancaria) 
        VALUES (:dni, :nombre, :apellidos, :fecha_nac, :telefono, :email, :tarifa, $cuota_mensual, :fecha_alta, :cuenta_bancaria)";

       
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

        global $conn; 

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

        global $conn; 

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

        //calculamos la cuota segun la tarifa (dato no introducido por el usuario)
        $cuota_mensual= self::calcular_cuota_mensual($tarifa);

        global $conn; 
        // update en la BBDD
         $sql = "UPDATE SOCIOS SET
         nombre = :nombre,
         apellidos = :apellidos,
         fecha_nac = :fecha_nac,
         telefono = :telefono,
         email = :email,
         tarifa = :tarifa,
         cuota_mensual = $cuota_mensual,
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



    //Devuelve todas las clases en las que esta inscrito el socio (para el formulario cuando se desapunta de una clase) Y Paara lanzar excepcion 
     //si incribimos un socio que en una clase que ya esta inscrito.

     public static function get_clases_inscrito($dni_socio) {
        
        global $conn; 
    
        // Definir la consulta para obtener las clases en las que está inscrito el socio
        $sql = "SELECT id_clase FROM SOCIOS_CLASES  WHERE dni_socio = :dni_socio";
    
        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dni_socio', $dni_socio);
    
        // Ejecutar la consulta y verificar si hubo algún error
        if (!$stmt->execute()) {
            throw new PDOException('Excepción PDO al ejecutar la consulta de clases inscritas');
        }
    
        
        // Obtener el resultado como un array de clases
            $clases = $stmt->fetchAll(PDO::FETCH_COLUMN);

    
        // Retornar el resultado (un array de clases)
        return $clases;
    }
    

   

    public static function inscribirClase($dni_socio, $id_clase){

        global $conn; 

        $clases_inscrito = Socio::get_clases_inscrito($dni_socio); 

        if(in_array($id_clase, $clases_inscrito)) throw new Exception("Excepción: $dni_socio ya está inscrito en la clase del $id_clase"); 

        

            // Obtenemos la tarifa del socio
            $sql = "SELECT tarifa FROM SOCIOS WHERE dni = :dni_socio";

           if(!$stmt = $conn->prepare($sql))throw new PDOException('Excepción PDO al preparar la consulta de tarifa del socio'); 

            $stmt->bindParam(':dni_socio', $dni_socio);

            if(!$stmt->execute()) throw new PDOException('Excepción PDO al ejecutar la consulta de tarifa del socio'); 

            $tarifa = $stmt->fetchColumn();


            // 2. Contar las clases en las que ya está inscrito el socio
            $cantidad_clases= count($clases_inscrito); 

            // 3. Verificar si el socio ya ha alcanzado el límite de clases según su tarifa
            if ($cantidad_clases >= $tarifa) {
                throw new Exception("$dni_socio ha alcanzado el límite de clases permitidas según su tarifa.");
            }

            // 4. Insertar al socio en la nueva clase
            $insertar= "INSERT INTO SOCIOS_CLASES (dni_socio, id_clase) VALUES (:dni_socio, :id_clase)"; 
            $stmt = $conn->prepare($insertar);
            $stmt->bindParam(':dni_socio', $dni_socio);
            $stmt->bindParam(':id_clase', $id_clase);
            if(!$stmt->execute()) throw new PDOException('Excepción PDO al ejecutar la consulta número de clases apuntado'); 

            return true; 

    }


     
    public static function desapuntarClase($dni_socio, $id_clase) {
        
        global $conn; 
    

        // Eliminamos la inscripción del socio en la clase
        $delete = "DELETE FROM SOCIOS_CLASES WHERE dni_socio = :dni_socio AND id_clase = :id_clase";
        $stmt = $conn->prepare($delete);
        $stmt->bindParam(':dni_socio', $dni_socio);
        $stmt->bindParam(':id_clase', $id_clase);
        
        if (!$stmt->execute()) {
            throw new PDOException('Excepción PDO al ejecutar la consulta de eliminación de inscripción.');
        }
    
        return true;
    }


   



}





