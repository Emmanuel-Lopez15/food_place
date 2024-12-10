<?php

require("conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conexion->real_escape_string($_POST["username"]);
    $email = $conexion->real_escape_string($_POST["email"]);
    $pass = $conexion->real_escape_string($_POST["password"]); 
    $conf = $conexion->real_escape_string($_POST["confirm_password"]); 
    $rol = $conexion->real_escape_string($_POST["rol"]);

    $verificarUsuario = $conexion->query("SELECT * FROM registro WHERE email='$email' OR user='$name'");

    if ($verificarUsuario->num_rows > 0) {
        $_SESSION['error_message'] = "The user is already registered."; 
        header("Location: form.php");
    } else {
        if($pass == $conf){

            $sql = "INSERT INTO registro (user, email, pass, confpassword, rol) 
            VALUES ('$name', '$email', '$pass', '$conf', '$rol')";

            if ($conexion->query($sql) === TRUE){
                $_SESSION['success_message'] = "Register success"; 
                $_SESSION['user'] = $name;
                header("Location: form.php"); 
                exit();

            } else {
                $_SESSION['error_message'] = "Failed in the register";
            }
            header("Location: form.php"); 
            exit();
        } else{
            $_SESSION['error_message'] = "The passwords are not the same";
            header("Location: form.php"); 
            exit();
        }
    }
}

$conexion->close();
?>