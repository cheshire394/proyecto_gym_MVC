
<!-- Código PHP -->
<?php

// Inicia una nueva sesión o reanuda la sesión existente
session_start();

// Verifica si existe una variable de sesión 'nombre'
if (!isset($_SESSION['nombre'])) {
    header('location: index.php');
    exit(); 
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio recepción</title>

    <!--Letra poppins-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>        
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
   
    <!--letra titulo-->
    <link href="https://fonts.googleapis.com/css2?family=Rock+Salt&display=swap" rel="stylesheet">

     <!--vinculo css-->
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/styles.css">

     <!--vinculo script-->
    <script src="/proyecto_gym_MVC/stylos/script.js" defer></script>

</head>

<body>
    <div class="main-container">
        <aside class="sidebar">
            <!-- GESTION DE CLASESE -->
            <div class="menu-item">
                <button class="menu-button">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="48" height="48">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Gestión de las clases
                </button>
                <div class="submenu">
                    <a href="/proyecto_gym_MVC/view/clases/addClase.php">Añadir una clase</a>
                    <a href="/proyecto_gym_MVC/view/clases/sustituirMonitor.php">Sustituir monitor</a>
                    <a href="/proyecto_gym_MVC/view/clases/verClases.php">Horario</a>
                    <a href="/proyecto_gym_MVC/view/clases/clasesSocios.php">listado clases</a>
                    <a href="/proyecto_gym_MVC/view/clases/eliminarDisciplina.php">Eliminar disciplina</a>
                    <a href="/proyecto_gym_MVC/view/clases/eliminarClase.php">Eliminar una clase</a>
                  
                </div>
            </div>


            <!-- GESTION DE TABAJADORES -->
                        <div class="menu-item">
                <button class="menu-button" onclick="window.location.href='/proyecto_gym_MVC/view/trabajadores/verTrabajadores.php'">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Trabajadores
                </button>
            </div>

            <!-- GESTION DE SOCIOS -->
            <div class="menu-item">
                <button class="menu-button">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
  
                    Gestión de Socios
                </button>
                <div class="submenu">
                    <a href="/proyecto_gym_MVC/view/socios/verSocios.php">socios</a>
                    <a href="/proyecto_gym_MVC/view/socios/addSocio.php">añadir socio</a>
            
                </div>
            </div>


            <!-- PESTAÑA DE CIERRE SESSION -->
            <div class="menu-item">
                <button class="menu-button" onclick="window.location.href='../router.php?action=logout'">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Cerrar Sesión
                </button>
            </div>

            <!-- TEMA CLARO/OSCURO -->
                <div class="theme-toggle" id="theme-toggle-container">
                    <button id="light_btn" class="theme-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          
                                <circle cx="12" cy="12" r="5"/>
                                <line x1="12" y1="1" x2="12" y2="3"/>
                                <line x1="12" y1="21" x2="12" y2="23"/>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                <line x1="1" y1="12" x2="3" y2="12"/>
                                <line x1="21" y1="12" x2="23" y2="12"/>
                                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>

                        Claro
                    
                    </button>
                    <button id="dark_btn" class="theme-button">   
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                            Oscuro
                    </button>
                </div>

          
        </aside>

        <main class="content">
            <h1 class="welcome-header" id="titulo">Club de artes Marciales valor innato</h1>
            

        </main>

    </div>

   
</body>
</html>
