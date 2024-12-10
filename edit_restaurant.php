<?php
include('conexion.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NameR = $conexion->real_escape_string($_POST["NameR"] ?? '');
    $CityR = $conexion->real_escape_string($_POST["CityR"] ?? '');
    $StateR = $conexion->real_escape_string($_POST["StateR"] ?? '');
    $CountryR = $conexion->real_escape_string($_POST["CountryR"] ?? '');
    $PhoneR = $conexion->real_escape_string($_POST["PhoneR"] ?? '');
    $CostR = $conexion->real_escape_string($_POST["CostR"] ?? '');

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $name = $conexion->real_escape_string($_POST['name']);
        $score = $conexion->real_escape_string($_POST['score']);
        $price = $conexion->real_escape_string($_POST['price']);
        $postcode = $conexion->real_escape_string($_POST['postcode']);

        $sql_update = "UPDATE restaurants SET name=?, score=?, price=?, city=?, state_country=?, country=?, postcode=?, phone=? WHERE id=?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("sisssssii", $name, $score, $price, $CityR, $StateR, $CountryR, $postcode, $PhoneR, $id);

        if ($stmt_update->execute()) {
            // Redirige después de la actualización
            header("Location: admin_home.php");
            exit;
        } else {
            $error_message = "Error al actualizar el restaurante.";
        }
    }
}


$row = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM restaurants WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
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
    <title>Food Place - Edit Restaurant</title>
    
</head>

<body>
    <header>
        <div class="header-container2">
            <img src="assets/Logo.png" alt="Food Place Logo">
        </div>
    </header>

    <main>
        <?php if (!empty($row)): ?>
            <form class="editar_restaurante" method="POST">
                <!-- Botón de regresar -->
                <a href="admin_home.php" id="backBtn_admin" title="Go Back">
                    <i class="fa fa-arrow-left"></i>
                </a>

                <h2>Edit Restaurant</h2>

                <!-- Fila 1: Nombre del restaurante y puntuación -->
                <div class="form-row">
                    <input type="text" name="name" placeholder="Restaurant Name" value="<?php echo $row['name']; ?>"
                        required>
                    <input type="number" name="score" placeholder="Score" value="<?php echo $row['score']; ?>" required>
                </div>

                <!-- Fila 2: Precio y código postal -->
                <div class="form-row">
                    <input type="text" name="price" placeholder="Price" value="<?php echo $row['price']; ?>" required>
                    <input type="text" name="postcode" placeholder="Postcode" value="<?php echo $row['postcode']; ?>"
                        required>
                </div>

                <!-- Fila 3: Ciudad y estado -->
                <div class="form-row">
                    <input type="text" name="CityR" placeholder="City" value="<?php echo $row['city']; ?>" required>
                    <input type="text" name="StateR" placeholder="State" value="<?php echo $row['state_country']; ?>"
                        required>
                </div>

                <!-- Fila 4: País y teléfono -->
                <div class="form-row">
                    <input type="text" name="CountryR" placeholder="Country" value="<?php echo $row['country']; ?>"
                        required>
                    <input type="text" name="PhoneR" placeholder="Phone" value="<?php echo $row['phone']; ?>" required>
                </div>

                <button type="submit">Update</button>


            </form>
        <?php endif; ?>
    </main>

    <footer class="pie-pagina">
        <div class="grupo-2">
            <small>&copy; 2024 <b>Centro de Investigación ITI 4-1</b> - All Rights Reserved.</small>
        </div>
    </footer>
</body>

</html>