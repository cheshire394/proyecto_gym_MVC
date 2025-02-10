
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Recepcionista</title>
    <style>
        body{
            background-image: url('../img/fondo1.jpg');
            background-repeat: no-repeat;
            background-size: cover;
        }

        fieldset{
            background-color:white;
            float: right;
            margin-right: 15em;
            margin-top: 10em;
            padding: 1em;
            border-radius: 8px;
            
            
        }

        form{
         
            text-align: left;
        }

        h2{
          
            margin-right: 15em;
            color: #b7475a; 
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
            padding: 0.5em;
            margin: 0.70em;
           
        }
        label{
            font-size: x-large;
        }

      
    </style>
</head>
<body>
   
    
    <!--Enviará los datos mediante el método POST al archivo index.php con un parámetro action=registro.-->
   
   
    <fieldset>
        <h2>Registro recepcionista</h2>
        <br>
         <!--Mensajes de error o exito en el registro y logeo de la recepcionista-->
            <?php     
                    if (isset($_GET['msg'])) {
                        echo "<p style='color: #b7475a;'><strong>".htmlspecialchars($_GET['msg'])."</strong></p>";
                    
                        } 
            ?>
        <form method="POST" action="/proyecto_gym_MVC/router.php?action=registro">
        
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value='recepcionista' required><br>

                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" value='de prueba' required><br>

                <label for="dni">DNI</label>
                <input type="text" id="dni" name="dni" value='04371618P' required><br>

                <label for="fecha_nac">Fecha de nacimiento</label>
                <input type="date" id="fecha_nac" name="fecha_nac" value="<?php echo date('2000-01-01');?>" required><br>

                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" value='675849393' required><br>

                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value='rececionista_ejemplo@gmail.com' required><br>

                <label for="cuenta_bancaria">Cuenta Bancaria</label>
                <input type="text" id="cuenta_bancaria" name="cuenta_bancaria" value='ES17 2096 4469 4968 4983 46430' required><br>

                <label for="funcion">Función</label>
                <input type="text" id="funcion" name="funcion" value='recepcionista' required><br>

                <label for="sueldo">Sueldo</label>
                <input type="number" id="sueldo" name="sueldo" value='1500' required><br>

                <label for="jornada">Jornada</label>
                <input type="number" id="jornada" name="jornada" min=1 max=40  value=40 required><br>
         
            <br>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required><br><br>
        
            
            <button type="submit" name="registrar">Registrar</button>
            <p style='color: grey';>* todos los campos son obligatorios</p>

               <!-- Enlace para ir al login -->
            <br>
        <a href="index.php">Login recepción</a>
        </form>
    

    <br>

 
   

   

   
</body>
</html>