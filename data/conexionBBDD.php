<?php


$db_nombre = 'gimnasio';
$db_usuario = '*';
$usuario_pass = '*';
$dsn = "mysql:host=localhost;dbname=$db_nombre"; 

try {
    $conn = new PDO($dsn, $db_usuario, $usuario_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage()); 

}
?>