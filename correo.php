<?php
require 'conexion.php';
session_start();


require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    
    //PDF---------------------------------------------------------------------------------------------------

    if (!isset($_SESSION['reservation_id'])) {
        // Si no hay un ID de reserva, redirige al home
        header("Location: home.php");
        exit();
    }
    
    $reservationId = $_SESSION['reservation_id']; // Recuperar el ID de la reserva
    
    // Consulta para obtener los datos de la reserva
    $query = "SELECT * FROM reserva WHERE id = $reservationId";
    $result = $conexion->query($query);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    
        $clientName = $data['name_reserve']; //Nombres del cliente
        $clientLastName = $data['lastname_reserve']; //Nombres del apellido del cliente
        $clientPhone = $data['phone_reserve']; //Numero del cliente
        $clientEmail = $data['email_reserve']; //Mail del cliente
    
        //Enviar correo
        if (!$clientEmail || !$clientName) {
            throw new Exception('Datos inválidos.');
        }
    
        // servidor SMTP
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'foodplace.2005@gmail.com'; 
        $mail->Password = 'ynwq izyg qlev rekl'; // Contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587;
    
        // correo
        $mail->setFrom('foodplace.2005@gmail.com', 'FoodPlace');
        $mail->addAddress($clientEmail, $clientName); 
        $mail->Subject = 'Reserve to the name of ' . $clientName ." ". $clientLastName;
        $mail->Body = "Hi $clientName "." "."$clientLastName ,\n\nWe've received your reserve.\n\nGreeting,\nFoodPlace.";
    
        $mail->send();
        echo "<script>alert('Mail sent correctly!');</script>";

    } else {
        echo "<script>alert('Reservation not found');</script>";
        header("Location: home.php");
        exit();
    }
    //------------------------------------------------------------------------------------------------------
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>

