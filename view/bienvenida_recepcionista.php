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
    <script src="../stylos/script.js"></script>
    <link rel="stylesheet" href="../stylos/styles.css">

    <style>
        /* Estilos para la sección de iconos sociales */
        .iconos-sociales {
            margin-bottom: 20px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .titulo-redes {
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 12px;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.5px;
        }

        .contenedor-iconos {
            display: flex;
            justify-content: center;
            gap: 20px;
            align-items: center;
        }

        .icono-social {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.1);
            color: #000000; /* Negro por defecto */
        }

        .icono-social:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .icono-social:visited {
            color: #000000; /* Mantiene el color negro incluso después de visitar */
        }

        .icono-github {
            width: 24px;
            height: 24px;
            fill: currentColor;
        }

        .icono-linkedin {
            width: 24px;
            height: 24px;
            fill: currentColor;
        }

        .icono-github:hover {
            color: #5f09c4; /* Púrpura al hacer hover */
        }

        .icono-linkedin:hover {
            color: #0049ae; /* Azul LinkedIn al hacer hover */
        }
    </style>

</head>

<body>
    <div class="main-container">
        <aside class="sidebar">
            <!-- ICONOS SOCIALES MEJORADOS -->
            <div class="iconos-sociales">
                <div class="titulo-redes">Mis Redes Sociales</div>
                <div class="contenedor-iconos">

                    <!-- Icono LinkedIn -->
                    <a href="https://www.linkedin.com/in/aliciadelsazcotallo/" target="_blank" title="Visitar mi LinkedIn" class="icono-social">
                        <svg class="icono-linkedin" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>

                    <!-- Icono GitHub -->
                    <a href="https://github.com/cheshire394" target="_blank" title="Visitar mi GitHub" class="icono-social">
                        <svg class="icono-github" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 0.297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                        </svg>
                    </a>
                </div>
            </div>

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