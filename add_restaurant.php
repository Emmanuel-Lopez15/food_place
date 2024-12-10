<?php
// Esto para recuperar mensaje de alerta
session_start();

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "";
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : "";

// Limpia de mensajes
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);
//--------------------------------------------------------------------------------------------------

include('conexion.php');

if ($_POST) {
    $name_rest = $_POST['name_rest'];
    $score = $_POST['score'];
    $price = $_POST['price'];
    $city = $_POST['city'];
    $state_country = $_POST['state_country'];
    $country = $_POST['country'];
    $postcode = $_POST['postcode'];
    $phone = $_POST['phone'];

    $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));

    $verificarRestaurant = $conexion->query("SELECT * FROM restaurants WHERE name='$name_rest' AND postcode='$postcode' ");

    if ($verificarRestaurant->num_rows > 0) {
        $_SESSION['error_message'] = "The restaurant is already registered."; 
        header("Location: admin_home.php");

    } else {
        $sql = "INSERT INTO restaurants (name, score, price, city, state_country, country, postcode, phone, imagen) 
        VALUES ('$name_rest', $score, $price, '$city', '$state_country', '$country', '$postcode', '$phone', '$imagen')";

        if ($conexion->query($sql) === TRUE) {
            $_SESSION['success_message'] = "New restaurant added successfully"; 
            header("Location: add_restaurant.php");  
        } else {
            $_SESSION['error_message'] = "Error adding restaurant";  
            header("Location: add_restaurant.php"); 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="assets/ico.ico">
    <title>Add Restaurant</title>
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
        <form class="reserva_pago" method="POST" enctype="multipart/form-data">
            <!-- Botón de regresar -->
            <a href="admin_home.php" id="backBtn" title="Go Back">
                <i class="fa fa-arrow-left"></i>
            </a>

            <!-- Título del formulario -->
            <h2>Add New Restaurant</h2>

            <!-- Fila 1: Nombre del restaurante y puntuación -->
            <div class="form-row">
                <input type="text" name="name_rest" placeholder="Restaurant Name" class="form-control" required>
                <input type="number" name="score" min="1" max="5" placeholder="Score (1-5)" class="form-control" required>
            </div>

            <!-- Fila 2: Precio y ciudad -->
            <div class="form-row">
                <input type="text" name="price" placeholder="Price" class="form-control" required>
                <input type="text" name="city" placeholder="City" class="form-control" required>
            </div>

            <!-- Fila 3: Estado/país y país -->
            <div class="form-row">
                <input type="text" name="state_country" placeholder="State/Country" class="form-control" required>
                <input type="text" name="country" placeholder="Country" class="form-control" required>
            </div>

            <!-- Fila 4: Código postal y teléfono -->
            <div class="form-row">
                <input type="text" name="postcode" placeholder="Postcode" class="form-control" required>
                <input type="text" name="phone" placeholder="Phone" class="form-control" required>
            </div>
            <!-- Fila 5: Imagen-->
            <div class="form-row">
            <input type="file" name="imagen" placeholder="Imagen" class="form-control" accept="image/*" required>
            </div>

            <!-- Botón de agregar restaurante -->
            <div class="form-buttons">
                <button class="button" type="submit">Add Restaurant</button>
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
</body>

</html>
