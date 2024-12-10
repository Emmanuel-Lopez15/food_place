<?php
// Conexión a la base de datos
require 'conexion.php';
session_start();

//Colocar cuando quede lo de sesion 
if (!isset($_SESSION['user'])) {
    $_SESSION['error_message'] = "Log In First."; 
    header("Location: form.php"); 
    exit(); 
 }

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "";
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : "";

// Limpia de mensajes
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);


// Valor del input de búsqueda
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$isNumeric = is_numeric($query); // Si la entrada es un número

// Obtener el número de página desde el parámetro 'page', si no existe, asignar 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$results_per_page = 15;
$starting_limit = ($page - 1) * $results_per_page;

// Calcular la consulta de búsqueda
if ($isNumeric) {
    // Buscar por score
    $search_sql = "SELECT id, name, score, price, city, state_country, country, phone, imagen 
                   FROM restaurants 
                   WHERE score >= ? 
                   LIMIT ?, ?";
    $stmt = $conexion->prepare($search_sql);
    $stmt->bind_param('dii', $query, $starting_limit, $results_per_page);
} else {
    // Buscar por nombre, ciudad, estado o país
    $likeQuery = '%' . $query . '%';
    $search_sql = "SELECT id, name, score, price, city, state_country, country, phone
                   FROM restaurants 
                   WHERE name LIKE ? OR city LIKE ? OR state_country LIKE ? OR country LIKE ? 
                   LIMIT ?, ?";
    $stmt = $conexion->prepare($search_sql);
    $stmt->bind_param('ssssii', $likeQuery, $likeQuery, $likeQuery, $likeQuery, $starting_limit, $results_per_page);
}

$stmt->execute();
$result = $stmt->get_result();

// Variable para detectar si hay resultados
$noResults = $result->num_rows === 0 ? true : false;

// Obtener el total de resultados para la paginación
if ($isNumeric) {
    // Buscar por score
    $count_sql = "SELECT COUNT(*) FROM restaurants WHERE score >= ?";
    $stmt_count = $conexion->prepare($count_sql);
    $stmt_count->bind_param('d', $query);
} else {
    // Buscar por nombre, ciudad, estado o país
    $count_sql = "SELECT COUNT(*) FROM restaurants WHERE name LIKE ? OR city LIKE ? OR state_country LIKE ? OR country LIKE ?";
    $stmt_count = $conexion->prepare($count_sql);
    $stmt_count->bind_param('ssss', $likeQuery, $likeQuery, $likeQuery, $likeQuery);
}

$stmt_count->execute();
$stmt_count->bind_result($total_results);
$stmt_count->fetch();
$total_pages = ceil($total_results / $results_per_page);

// Mostrar la paginación con solo los botones "Anterior" y "Siguiente"
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
                    <li><a href="add_restaurant.php"><i class="fa-solid fa-plus">&nbsp; &nbsp;</i>Add Restaurant</a></li>
                    <li><a href="listadmin_reservation.php"><i class="fa-solid fa-book-open">&nbsp; &nbsp;</i>Reservations</a></li>
                    <li><a href="prueba.php"><i class="fa-solid fa-users">&nbsp; &nbsp;</i>Users</a></li>
                    <li><a href="logout.php"><i class="fa fa-sign-out">&nbsp; &nbsp;</i>Log Out</a></li>
                </ul>
            </nav>

            <form class="search-form" id="search-form" onsubmit="event.preventDefault(); performSearch();">
                <input type="text" id="search-input" name="query"
                    placeholder="Search by name, city, state, country or score"
                    value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                <button type="button" id="searchButton" onclick="performSearch()"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
    </header>

    <main>
        <div id="results">
            <?php
            if (!$noResults) {
                echo '<div class="table-container">';
                echo '<table class="styled-table" id="restaurants-table">';
                echo '<thead>
                        <tr>
                            <th>ID</th>
                            <th>Restaurant Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>';
                echo '<tbody>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td>';
                    echo '<p><strong>Name:</strong> ' . htmlspecialchars($row['name']) . '</p>';
                    echo '<p><strong>Score:</strong> ' . htmlspecialchars($row['score']) . ' ★ </p>';
                    echo '<p><strong>Price:</strong> $' . htmlspecialchars($row['price']) . '</p>';
                    echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state_country']) . ', ' . htmlspecialchars($row['country']) . '</p>';
                    echo '<p><strong>Phone:</strong> ' . htmlspecialchars($row['phone']) . '</p>';
                    echo '</td>';
                    echo '<td>';
                    echo '<a href="edit_restaurant.php?id=' . $row['id'] . '"><button class="edit-btn">Edit</button></a>';
                    echo ' ';
                    echo '<a href="delete_restaurant.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this restaurant?\')"><button class="delete-btn">Delete</button></a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            }
            ?>
        </div>

        <div class='pagination'>
            <?php if ($page > 1): ?>
                <a href='?query=<?php echo urlencode($query); ?>&page=<?php echo ($page - 1); ?>' class='prev'>«
                    Previous</a>
            <?php else: ?>
                <span class='prev disabled'>« Previous</span>
            <?php endif; ?>

            <span class='current-page'>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>

            <?php if ($page < $total_pages): ?>
                <a href='?query=<?php echo urlencode($query); ?>&page=<?php echo ($page + 1); ?>' class='next'>Next »</a>
            <?php else: ?>
                <span class='next disabled'>Next »</span>
            <?php endif; ?>
        </div>

        <button id="scrollTopBtn" title="Go to top" onclick="scrollToTop()">
            <i class="fa-solid fa-arrow-up-long"></i>
        </button>
    </main>

    <!-- Overlay de alerta para sin resultados -->
    <div class="alert-overlay" id="alertOverlay">
        <div class="alert-box">
            <h2>No results!</h2>
            <p>No restaurants found with your search.</p>
            <button onclick="closeAlert()">Close</button>
        </div>
    </div>

    <footer class="pie-pagina">
        <div class="grupo-2">
            <small>&copy; 2024 <b>- Centro de Investigación ITI 4-1</b> - All Rights Reserved.</small>
        </div>
    </footer>

    <script>
        let lastScrollTop = 0; // Variable para almacenar la última posición del scroll

        // Función que detecta la dirección del scroll
        window.addEventListener('scroll', function() {
            const sideMenu = document.getElementById('sideMenu');
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            // Si estamos bajando (scroll hacia abajo), ocultar el menú
            if (currentScroll > lastScrollTop) {
                sideMenu.classList.remove('open'); // Ocultar menú cuando bajamos
            }

            // Actualiza la última posición del scroll
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Evita que se convierta en un valor negativo
        });

        // Función para controlar el toggle del menú (se activa al hacer click en el logo)
        function toggleMenu() {
            const sideMenu = document.getElementById('sideMenu');
            sideMenu.classList.toggle('open');
        }

        // Inicializa el menú en estado cerrado (al cargar la página)
        document.addEventListener('DOMContentLoaded', function() {
            const sideMenu = document.getElementById('sideMenu');
            sideMenu.classList.remove('open'); // Asegura que el menú esté cerrado al inicio
        });
    </script>

    <script>
        const alertOverlay = document.getElementById('alertOverlay');
        const searchInput = document.getElementById('search-input');

        // Función para mostrar la alerta
        function showAlert() {
            alertOverlay.style.display = 'flex';
        }

        // Función para cerrar la alerta y redirigir a user_home.php
        function closeAlert() {
            alertOverlay.style.display = 'none';
            searchInput.value = ''; // Reiniciar el campo de búsqueda
            window.location.href = 'admin_home.php'; // Redirigir al inicio


        }

        function performSearch() {
            const query = searchInput.value.trim();

            if (query.length > 0) {
                window.location.href = '?query=' + encodeURIComponent(query) + '&page=1';
            } else {
                window.location.href = 'admin_home.php';
            }
        }

        // Detectar "sin resultados" desde PHP
        const noResults = <?php echo $result->num_rows === 0 ? 'true' : 'false'; ?>;
        if (noResults) {
            searchInput.value = ''; // Limpiar el input si no hay resultados
            showAlert();
        }
    </script>



    <script>
        // Botón hacia arriba
        const scrollTopBtn = document.getElementById('scrollTopBtn');

        // Mostrar/ocultar el botón según el scroll
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollTopBtn.style.display = 'block';
            } else {
                scrollTopBtn.style.display = 'none';
            }
        };

        // Función para ir al inicio de la página
        function scrollToTop() {
            document.body.scrollTop = 0; // Para navegadores Safari
            document.documentElement.scrollTop = 0; // Para Chrome, Firefox, IE y Opera
        }
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