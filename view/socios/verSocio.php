<!-- Página para buscar/ver Socios-->

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../controllers/controladorSocios.php';
// Obtener todos los monitores o un array vacio si no hay:
$socios = controladorSocios::mostrarTodosSocios();
 
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
        <legend>filtro:</legend>
        <form method="POST" action="index_socios.php?action=verSocio">
        
        <input type="text" id="valor" name="valor" required>
            <select id="campo" name="campo" required>
                <option value="dni">DNI</option>
                <option value="nombre">Nombre</option>
                <option value="apellidos" selected>Apellidos</option>
                <option value="telefono">Teléfono</option>
                <option value="tarifa">Tarifa</option>
            
            </select>
                <input type="hidden" name="action" value="verSocio">
                <input type="submit" value="Buscar">
          
        </form>
    </fieldset>

 
    <br>
  
    <?php if (!empty($socios)) { ?>
        <table>
            <h1>socios del gimnasio</>
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>edad</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>tarifa</th>
                    <th>cuenta bancaria</th>
                    <th>fecha alta</th>
                    <th>Gestionar socio<th>
                    
                   
                </tr>
            </thead>
            <tbody>
                <?php 
                
                
                foreach($socios as  $socio) {
                    
                    
                ?>
                    <tr>
                        <td><?= htmlspecialchars($socio->__get('dni')) ?></td>
                        <td><?= htmlspecialchars($socio->__get('nombre')) ?></td>
                        <td><?= htmlspecialchars($socio->__get('apellidos')) ?></td>
                        <td><?= htmlspecialchars($socio->__get('edad')) ?></td>
                        <td><?= htmlspecialchars($socio->__get('fecha_nac')) ?></td>
                        <td><?= htmlspecialchars($socio->__get('telefono')) ?></td>
                        <td><?= htmlspecialchars($socio->__get('email')) ?></td>
                        <td><?= htmlspecialchars($socio->__get('tarifa')) ?></td>
                        <td><?= htmlspecialchars($socio->__get('cuenta_bancaria')) ?></td>
                        <td><?= htmlspecialchars($socio->__get('fecha_alta')) ?></td>
                        <td>
                        <button>

                        <form  method='post' action="index_socios.php?action=eliminarSocio">
                            <!-- Icono configuración -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                        </form>
                           
                        </button>
                        <button>
                            <!-- Icono basura -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            </svg>
                            
                        </button>
                      
                        </td>
                      
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No hay socios diponibles</p>
    <?php } ?>

        

    <script src='../../stylos/filtroSocio.js'></script>
 

</body>

</html>