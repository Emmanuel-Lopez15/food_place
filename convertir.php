<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_POST['Id_usuario'];
    $sql = "UPDATE registro SET rol = 'admin' WHERE Id_usuario = $id_user";
    if ($conexion->query($sql) === TRUE) {
        header("Location: prueba.php"); // Redirige a la página principal
    } else {
        echo "Error: " . $conexion->error;
    }
}
?>
