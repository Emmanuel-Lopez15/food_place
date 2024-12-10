<?php
 
 require 'conexion.php';
 session_start();

 //Colocar hasta que exista boton de eliminar reserva
 //if ($_SERVER["REQUEST_METHOD"] == "POST") {

 //recuperar el id de la reservación  y cambiar formato a $id = $conexion->real_escape_string($_POST["id"]);  
 $id = 4; 


 $checkQuery = "SELECT * FROM reserva WHERE id='$id'";
 $result = $conexion->query($checkQuery);

 if ($result->num_rows > 0) {
    $sql = "DELETE FROM reserva WHERE id='$id'";
        if ($conexion->query($sql) === TRUE) {
            $_SESSION['success_message'] = "Reservación eliminada con exito";
            exit();
        } else {
            $_SESSION['error_message'] = "Error eliminando la reservación: " . $conexion->error;
            exit();
        }
   
 } else {
    $_SESSION['error_message'] = "No se encontró la reservación" . $conexion->error;
    exit();
    
 }

 //}
 header("Location: listuser_reservation.php");
 $conexion->close();
 ?>
