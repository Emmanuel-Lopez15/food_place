<?php

require("conexion.php");
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name= $conexion->real_escape_string($_POST["username"]);
    $pass=$conexion->real_escape_string($_POST["password"]);

    $sql1 = "SELECT * FROM registro WHERE user= '$name' and pass = '$pass' and rol = 'admin'";
    $result = $conexion->query($sql1);
    $sql2 = "SELECT * FROM registro WHERE user= '$name' and pass = '$pass' and rol = 'usuario'";
    $result2 = $conexion->query($sql2);
    

    if ($result->num_rows > 0) {
        session_start();
        $_SESSION['user'] = $name; 
        header("Location: admin_home.php"); //Redireccionar al inicio del sistema
        
    } else if ($result2->num_rows > 0) {
        session_start();
        $_SESSION['user'] = $name; 
        header("Location: user_home.php"); //Redireccionar al inicio del sistema

    }else{
        $_SESSION['error_message'] = "User or password incorrect.";   
        header("Location: form.php"); 
        exit();
    }

}

$conexion->close();
?>