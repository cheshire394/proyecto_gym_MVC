#!/bin/bash

# Actualizar los repositorios y paquetes
sudo apt update && sudo apt upgrade -y

# Instalar el servidor MySQL
sudo apt install -y mysql-server

# Instalar Apache
sudo apt install -y apache2

# Instalar PHP y el módulo de Apache para PHP
sudo apt install php libapache2-mod-php php-mysql

# Instalar PhpMyAdmin
sudo apt install -y phpmyadmin

# Iniciar el servicio de Apache
sudo service apache2 start

# Crear usuario y otorgar privilegios en MySQL
{
echo "CREATE USER 'alumno'@'localhost' IDENTIFIED BY 'alumno';"
echo "GRANT ALL PRIVILEGES ON *.* TO 'alumno'@'localhost' WITH GRANT OPTION;"
echo "FLUSH PRIVILEGES;"
} | sudo mysql -u root

# Ejecutar el archivo BBDD.sql en MySQL
sudo mysql -u root < /var/www/html/proyecto_gym_MVC/data/BBDD.sql

