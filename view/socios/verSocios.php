<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Verifica si existe una variable de sesión 'nombre'
session_start(); 
if (!isset($_SESSION['nombre'])) {
    // Si la sesión no contiene 'nombre', redirige al usuario a la página de inicio de sesión
    header('location: ../login_recepcionista.php');
    exit(); 
}


require_once '../../controllers/controladorSocios.php';

// Si nos encontramos en esta vista, porque el controlador nos ha dervidado desde el método filtar socios, mostramos solo los socios filtrados
if(isset($socios_filtrados)){

    $socios = $socios_filtrados;

   

}else{

    // sino, mostramos todos los socios
    $socios = controladorSocios::mostrarTodosSocios();
    
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Socio</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">
    
</head>
<body>
    
        <fieldset id='form_socios'>
            <legend>filtro de búsqueda </legend>

                <!-- formulario para filtrar socios -->
                    <form method="POST" action="router_socios.php?action=filtrar_socios">

                        <select  name="propiedad" required>
                            <option value="dni">DNI</option>
                            <option value="nombre">Nombre</option>
                            <option value="apellidos">Apellidos</option>
                            <option value="tarifa" selected>Tarifa</option>
                        </select>
                        <input type="text"  name="valor" required>
                        <button type='submit' name='filtrar_socios'>Filtrar socios</button>

                    <!--volver al menú principal-->
                        <div id='divEnlace'>
                            <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>volver al menú principal</span>
                            </a>
                        </div>
                    </form>

       
        </fieldset>

    

    <div id='todoSocios'>
        <!-- Formulario para refrescar la página y volver a ver todos los socios -->
            <a  href="/proyecto_gym_MVC/view/socios/verSocios.php">

        <!-- icono dos perosnas -->  
        
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            Mostrar todos los socios
        
            </a>
    
    </div>

    
    
    <!--Mensajes de exito o error cuando somo redigidos desde el controlador trás haber ejecutado una acción desde este script-->
    <?php

        if(isset($_GET['msg'])){

            echo "<p>".$_GET['msg']."</p>"; 

        }

    ?>

   

    <br>

        <?php if (!empty($socios)) { ?>
            <table>
                <h1>Socios del gimnasio</h1>
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>tarifa</th>
                        <th>cuenta bancaria</th>
                        <th>fecha alta</th>
                        <th>Gestionar socio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($socios as $socio) { ?>
                        <tr>
                            <td><?= htmlspecialchars($socio->__get('dni')) ?></td>
                            <td><?= htmlspecialchars($socio->__get('nombre')) ?></td>
                            <td><?= htmlspecialchars($socio->__get('apellidos')) ?></td>
                            <td><?= htmlspecialchars($socio->__get('fecha_nac')) ?></td>
                            <td><?= htmlspecialchars($socio->__get('telefono')) ?? 'dato no aportado' ?></td>
                            <td><?= htmlspecialchars($socio->__get('email')) ?? 'dato no aportado' ?></td>
                            <td><?= htmlspecialchars($socio->__get('tarifa')) ?></td>
                            <td><?= $socio->__get('cuenta_bancaria') ?? 'dato no aportado' ?></td>
                            <td><?= htmlspecialchars($socio->__get('fecha_alta')) ?></td>
                            <td>

                            <!--modificar un socio de la BBDD-->
                                <form method="post" action="router_socios.php?action=mostrarFormularioModificar">
                                <!--enviamos por oculto al formulario del socio que deseamos modificar-->
                                <input type="hidden" name="dni_socio" value="<?php echo $socio->__get('dni'); ?>"> 
                                <button type='submit'>
                                    <!-- Icono configuración -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                    </svg>
                                    modificar
                                </button>

                                </form>
                                
                            
                            <!--eliminar un socio de la BBDD-->

                                <form method="post" action="router_socios.php?action=eliminarSocio">
                                            <!--enviamos por oculto al formulario del socio que deseamos eliminar-->
                                            <input type="hidden" name="dni_socio" value="<?php echo $socio->__get('dni'); ?>"> 
                                                <!-- Icono basura -->
                                            <button type='submit'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                </svg>
                                                eliminar
                                        
                                            </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No hay socios disponibles</p>
        <?php } ?>
    </div>

    

</body>
</html>