

<?php

// Comprueba si se pasó un error de credenciales incorrectas a través de la URL.
if (isset($_GET['error']) && $_GET['error'] == 'credenciales_incorrectas') {
    // Muestra un mensaje de error y redirige automáticamente al login después de 2 segundos.
    echo "<p style='color: red;'> Error, las credenciales no son correctas, redirigiendo al registro automáticamente</p>";
    header('Refresh: 2 login_recepcionista.php');
}
?>


<!-- HTML con el formulario -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        body{
            background-image: url('../img/fondo3.jpg');
            background-repeat: no-repeat;
            background-size: cover;
        }

        fieldset{
            background-color:white;
            float: left;
            margin-left: 15em;
            margin-top: 25em;
            padding: 1em;
            border-radius: 8px;
            
            
        }

        form{
            justify-content: center;
            text-align: center;
        }

        h2{
          
            color:#b7475a; 
        }
        button{
            background-color: #b7475a;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            padding: 1em;
            margin:1.4em; 
        }
        button:hover{
            background-color: #bdbcbd;
            transform:translateY(3px);
            transition: 0.5s;
            color:black; 
        }
        input{
            padding: 0.75em;
            font-size: large;
        }
        label{
            font-size: x-large;
        }
      
    </style>
</head>


<body>
    
 

<fieldset>

<h2>Inicio de Sesión recepcionista</h2>

    <form method="POST" action="/proyecto_gym_MVC/router.php?action=login">
        <!-- Acceso -->
            <label for="dni">DNI</label><br>
            <input type="text" id="dni" name="dni" value="16280029P" placeholder="16280029P"><br><br>

            <label for="password">Contraseña</label><br>
            <input type="password" id="password" name="password" value="123" placeholder="123" required><br>
            <button type="submit" name="login">Iniciar Sesión</button>
            <br>
            <a href="registro_recepcionista.php">Registrar Recepcionista</a>
        

        <br>
    
    </form>
      <!--Mensajes de error o exito en el registro y logeo de la recepcionista-->
      <?php     
                    if (isset($_GET['msg'])) {
                        echo "<p style='color: #b7475a;'><strong>".htmlspecialchars($_GET['msg'])."</strong></p>";
                    
                        } 
    ?>

</fieldset>

    <br>

</body>

</html>