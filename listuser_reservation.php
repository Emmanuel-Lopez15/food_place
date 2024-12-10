<?php
require 'conexion.php';
session_start();

// Colocar cuando quede lo de sesión
if (!isset($_SESSION['user'])) {
    $_SESSION['error_message'] = "Inicia sesión."; 
    header("Location: form.php"); 
    exit(); // Termina la ejecución del script.
}

$User = $_SESSION['user'];

// Parámetros de paginación (1 reserva por página)
$records_per_page = 5; // Número de registros por página
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$start = ($page - 1) * $records_per_page; // Calcular el inicio de los registros para la página actual

// Consultar el total de reservas del usuario
$total_records_query = "SELECT COUNT(*) AS total FROM reserva WHERE user_name='$User'";
$total_records_result = $conexion->query($total_records_query);
$total_records = $total_records_result->fetch_assoc()['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_records / $records_per_page);

// Consultar las reservas del usuario para la página actual
$checkQuery = "SELECT * FROM reserva WHERE user_name='$User' LIMIT $start, $records_per_page";
$result = $conexion->query($checkQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <link rel="icon" href="assets/ico.ico">
    <title>Food Place</title>
</head>

<body>
<header>
    <div class="header-container">
        <button class="menu-btn" onclick="toggleMenu()">
            <img src="assets/Logo.png" alt="Food Place Logo">
        </button>
        <nav class="side-menu" id="sideMenu">
            <button class="close-btn" onclick="toggleMenu()"><i class="fa-regular fa-rectangle-xmark"></i></button>
            <ul>
                <li><a href="user_home.php"><i class="fa-solid fa-chair">&nbsp; &nbsp;</i>Restaurants</a></li>
                <li><a href="logout.php"><i class="fa fa-sign-out">&nbsp; &nbsp;</i>Log Out</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="container2">
        <?php
        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<thead>
                        <tr>
                            <th>Reservation Details</th>
                        </tr>
                    </thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>";
                echo "<p><strong>Restaurant:</strong> " . $row["name_restaurant"] . "</p>";
                echo "<p><strong>Date & Hour:</strong> " . $row["date_reserve"] . " / " . $row["time_reserve"] . "</p>";
                echo "<p><strong>Price:</strong> $" . $row["cost_restaurant"] . "</p>";
                echo "<p><strong>Location:</strong> " . $row["city_restaurant"] . " " . $row["state_restaurant"] . " " . $row["country_restaurant"] . "</p>";
                echo "<p><strong>Restaurant Phone:</strong> " . $row["phone_restaurant"] . "</p>";
                echo "<p><strong>Note:</strong> " . $row["note_reserve"] . "</p>";
                echo "</td>";
                echo "</tr>";
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo "<p style='text-align: center;'>There are no reservations</p>";
        }
        ?>

        <!-- Paginación -->
        <div class='pagination'>
            <?php
            // Botón de "Anterior"
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "' class='prev'>« Previous</a>";
            } else {
                echo "<span class='prev disabled'>« Previous</span>";
            }

            // Mostrar la página actual
            echo "<span class='current-page'>Page $page of $total_pages</span>";

            // Botón de "Siguiente"
            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . "' class='next'>Next »</a>";
            } else {
                echo "<span class='next disabled'>Next »</span>";
            }
            ?>
        </div>
    </div>

    <button id="scrollTopBtn" title="Go to top" onclick="scrollToTop()">
        <i class="fa-solid fa-arrow-up-long"></i>
    </button>
</main>

<footer class="pie-pagina">
    <div class="grupo-2">
        <small>&copy; 2024 <b>- Centro de Investigación ITI 4-1</b> - All Rights Reserved.</small>
    </div>
</footer>

<script>
    const scrollTopBtn = document.getElementById('scrollTopBtn');

    window.onscroll = function () {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            scrollTopBtn.style.display = 'block';
        } else {
            scrollTopBtn.style.display = 'none';
        }
    };

    function scrollToTop() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        const sideMenu = document.getElementById('sideMenu');
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            sideMenu.classList.remove('open');
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });

    function toggleMenu() {
        const sideMenu = document.getElementById('sideMenu');
        sideMenu.classList.toggle('open');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sideMenu = document.getElementById('sideMenu');
        sideMenu.classList.remove('open');
    });
</script>

</body>
</html>
