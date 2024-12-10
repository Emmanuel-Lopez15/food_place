
<?php
require 'conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos comunes
    $NameR = $conexion->real_escape_string($_POST["NameR"]);  
    $CityR = $conexion->real_escape_string($_POST["CityR"]);
    $StateR = $conexion->real_escape_string($_POST["StateR"]);
    $CountryR = $conexion->real_escape_string($_POST["CountryR"]);  
    $PhoneR = $conexion->real_escape_string($_POST["PhoneR"]);
    $CostR = $conexion->real_escape_string($_POST["CostR"]);

    $Name_reserve = $conexion->real_escape_string($_POST["name"]);  
    $Lastname_reserve = $conexion->real_escape_string($_POST["lastname"]);
    $Phone_reserve = $conexion->real_escape_string($_POST["phone"]);
    $Email_reserve = $conexion->real_escape_string($_POST["email"]);  
    $Note_reserve = $conexion->real_escape_string($_POST["note"]);
    $DateR = $conexion->real_escape_string($_POST["DateR"]);
    $TimeR = $conexion->real_escape_string($_POST["TimeR"]);
    $User = $conexion->real_escape_string($_POST["Username"]);
    $action = $_POST["action"]; // Verificar si es reserva normal o con factura

    // Validar que la fecha ingresada no sea anterior a la actual
    $fecha_actual = new DateTime(); // Fecha y hora actual
    $fecha_reserva = new DateTime($DateR); // Convertir la fecha ingresada a objeto DateTime

    if ($fecha_reserva < $fecha_actual) {
        $_SESSION['error_message'] = "The reservation date cannot be earlier than the current date.";
        header("Location: form_reservation.php");
        exit();
    }

    // Verificar si ya existe una reserva para esa fecha
    $checkQuery = "SELECT * FROM reserva WHERE date_reserve = '$DateR' AND time_reserve = '$TimeR' AND user_name = '$User'";
    $result = $conexion->query($checkQuery);

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "You already have a reservation with that date and time";
        header("Location: user_home.php"); 
        exit();
    } else {
        // Insertar reserva en la base de datos
        $sql = "INSERT INTO reserva (user_name, name_reserve, lastname_reserve, phone_reserve, email_reserve, note_reserve, name_restaurant, date_reserve, time_reserve, cost_restaurant, city_restaurant, state_restaurant, country_restaurant, phone_restaurant)
                VALUES ('$User', '$Name_reserve', '$Lastname_reserve', '$Phone_reserve', '$Email_reserve', '$Note_reserve', '$NameR', '$DateR', '$TimeR', '$CostR', '$CityR', '$StateR', '$CountryR', '$PhoneR')";

        if ($conexion->query($sql) === TRUE) {
            // Recuperar el ID de la reserva recién creada
            $reservationId = $conexion->insert_id;
            if ($action === "invoice") {
                $_SESSION['reservation_id'] = $reservationId; // Guardar el ID de la reserva en la sesión
                include ('correoinvoice.php');
                header("Location: user_home.php"); // Redirigir para mandar correo con factura
                $_SESSION['success_message'] = "The reserve is already created.";   
                exit();
            } else {
                $_SESSION['reservation_id'] = $reservationId; // Guardar el ID de la reserva en la sesión
                include ('correo.php');
                header("Location: user_home.php"); // Redirigir para mandar correo solamente
                $_SESSION['success_message'] = "The reserve is already created.";
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Error haciendo la reservación: " . $conexion->error;
            header("Location: user_home.php");
            exit();
        }
    }
}

$conexion->close();
?>
