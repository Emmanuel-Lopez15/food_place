<?php

require 'conexion.php';
//crear session
session_start();
//Colocar cuando quede lo de sesion 
//Colocar cuando quede lo de sesion 
if (!isset($_SESSION['user'])) {
    $_SESSION['error_message'] = "Log In First.."; 
    header("Location: form.php"); 
    exit(); 
 }

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "";
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : "";

// Limpia de mensajes
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);

//--------------------------------------------------------------------------------------------------

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $NameR = $conexion->real_escape_string($_POST["NameR"]);
        $CityR = $conexion->real_escape_string($_POST["CityR"]);
        $StateR = $conexion->real_escape_string($_POST["StateR"]);
        $CountryR = $conexion->real_escape_string($_POST["CountryR"]);
        $PhoneR = $conexion->real_escape_string($_POST["PhoneR"]);
        $CostR = $conexion->real_escape_string($_POST["CostR"]);
        $Username = $_SESSION['user'];
    }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="assets/ico.ico">
    <title>Food Place - Reservation</title>
</head>

<body>
    <!-- Encabezado -->
    <header>
        <div class="header-container2">
            <img src="assets/Logo.png" alt="Food Place Logo">
        </div>
    </header>

    <!-- Contenido principal -->
    <main>
        <form class="reserva_pago" action="user_reservation.php" method="POST">
            <!-- Botón de regresar -->
            <a href="user_home.php" id="backBtn" title="Go Back">
                <i class="fa fa-arrow-left"></i>
            </a>

            <!-- Título del formulario -->
            <h2>Make Your Reservation</h2>

            <!-- Campos ocultos para datos predefinidos -->
            <input type="hidden" name="NameR" value="<?php echo $NameR; ?>">
            <input type="hidden" name="CityR" value="<?php echo $CityR; ?>">
            <input type="hidden" name="StateR" value="<?php echo $StateR; ?>">
            <input type="hidden" name="CountryR" value="<?php echo $CountryR; ?>">
            <input type="hidden" name="CostR" value="<?php echo $CostR; ?>">
            <input type="hidden" name="PhoneR" value="<?php echo $PhoneR; ?>">
            <input type="hidden" name="Username" value="<?php echo $Username; ?>">

            <!-- Fila 1: Nombre y apellido -->
            <div class="form-row">
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="lastname" placeholder="Last Name" required>
            </div>

            <!-- Fila 2: Teléfono y correo -->
            <div class="form-row">
                <input type="text" name="phone" placeholder="Personal Phone" required>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <!-- Fila 3: Nota y fecha -->
            <div class="form-row">
                <input type="text" name="note" placeholder="Extra Note">
                <input type="date" name="DateR" required>
            </div>

            <!-- Fila 4: Hora y método de pago -->
            <div class="form-row">
                <input type="time" name="TimeR" required>
                <input type="text" name="PayR" placeholder="Interbank Key" pattern="[0-9]{15,16}|(([0-9]{4}\s){3}[0-9]{3,4})" required>
            </div>

            <!-- Botón de reservación -->
            <div class="form-buttons">
                <button class="button" type="submit" name="action" value="reserve">Reservation</button>
                <button class="button" type="submit" name="action" value="invoice">Reservation with bill</button>
            </div> 
        </form>
    </main>

    <!-- Modal para alertas -->
    <div class="modal" id="alertModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Food Place says:</h3>
                <button class="modal-close-btn" onclick="closeModal()">X</button>
            </div>
            <div class="modal-body">
                <?php
                if (!empty($error_message)) {
                    echo '<div class="alert alert-danger" id="alertMessage">' . $error_message . '</div>';
                }
                if (!empty($success_message)) {
                    echo '<div class="alert alert-success" id="alertMessage">' . $success_message . '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="pie-pagina">
        <div class="grupo-2">
            <small>&copy; 2024 <b>Centro de Investigación ITI 4-1</b> - All Rights Reserved.</small>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="script.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var alertModal = document.getElementById("alertModal");
            var alertMessage = document.getElementById("alertMessage");

            if (alertMessage) {
                alertModal.style.display = "flex"; // Mostrar modal
            }

            window.closeModal = function () {
                alertModal.style.display = "none";
            };
        });
    </script>

    
    <!-- Modal para alertas -->
    <div class="modal" id="alertModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Food Place says: </h3>
                <button class="modal-close-btn" onclick="closeModal()">X</button>
            </div>
            <div class="modal-body">
                <?php
                if (!empty($error_message)) {
                    echo '<div class="alert alert-danger" id="alertMessage">' . $error_message . '</div>';
                }
                if (!empty($success_message)) {
                    echo '<div class="alert alert-success" id="alertMessage">' . $success_message . '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var alertModal = document.getElementById("alertModal");
            var alertMessage = document.getElementById("alertMessage");

            if (alertMessage) {
                alertModal.style.display = "flex"; //'flex' para centrar el modal
            }

            window.closeModal = function () {
                alertModal.style.display = "none";
            };
        });
    </script>
</body>

</html>
