<?php
// Definición de las credenciales de conexión a la base de datos
$server = "localhost"; // Dirección del servidor de base de datos
$user = "root";        // Nombre de usuario para conectarse a la base de datos
$pass = "";            // Contraseña del usuario de la base de datos
$db = "foodplace";      // Nombre de la base de datos a la que se va a conectar

// Crear una nueva conexión a la base de datos utilizando MySQLi
$conexion = new mysqli($server, $user, $pass, $db);

// Comprobar si hay errores en la conexión
if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error); // Termina el script y muestra el error en caso de fallar la conexión
}
?>