<?php
session_start();

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "";
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : "";

// Limpia de mensajes
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
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
                    <li><a href="#"><i class="fa-solid fa-chair">&nbsp; &nbsp;</i>Reservations</a></li>
                    <li><a href="#"><i class="fa-solid fa-arrow-right-from-bracket">&nbsp; &nbsp;</i>Sign out</a></li>
                </ul>
            </nav>

            <a href="form.php"><button class="btn-welcome" type="button" ><i class="fa-solid fa-address-card"></i>&nbsp; &nbsp;WELCOME !!!</button></a>

        </div>
    </header>

    <main>
        <div id="results">
            <?php include 'index_restaurants.php'; ?>
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
            <small>&copy; 2024 <b>- ITI 4-1 Research Center</b> - All Rights Reserved.</small>
        </div>
    </footer>

    <script>
        const alertOverlay = document.getElementById('alertOverlay');
        const searchInput = document.getElementById('search-input');

        // Función para mostrar la alerta
        function showAlert() {
            alertOverlay.style.display = 'flex';
        }

        // Función para cerrar la alerta y redirigir a home.php
        function closeAlert() {
            alertOverlay.style.display = 'none';
            searchInput.value = ''; // Reiniciar el campo de búsqueda
            // Redirigir a home.php después de cerrar la alerta
            window.location.href = 'user_home.php';
        }

        // Detectar "sin resultados" desde PHP
        const noResults = <?php echo $result->num_rows === 0 ? 'true' : 'false'; ?>;
        if (noResults) {
            showAlert();
        }

        window.onload = () => {
            const noResultsElement = document.getElementById('no-results');
            if (noResultsElement && noResultsElement.textContent === 'true') {
                showAlert();
            }
        };

        // Búsqueda por voz
        const startVoiceButton = document.getElementById('start-voice');
        const searchButton = document.getElementById('searchButton');

        // Función de búsqueda por voz
        function startVoiceSearch() {
            // Cambiar el ícono a "escuchar" (ear)
            startVoiceButton.innerHTML = '<i class="fa-solid fa-ear-listen"></i>';

            const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.lang = 'es-ES'; // Idioma español
            recognition.start(); // Inicia la escucha de la voz

            recognition.onresult = function (event) {
                // Obtener la transcripción de lo que se dijo
                const transcript = event.results[0][0].transcript;

                // Poner lo que se dijo en el campo de búsqueda
                document.getElementById('search-input').value = transcript;

                // Ejecutar la búsqueda con la transcripción
                performSearch();
            };

            recognition.onerror = function (event) {
                console.error('Error de voz:', event.error);
                // Volver al ícono de micrófono si ocurre un error
                startVoiceButton.innerHTML = '<i class="fa-solid fa-microphone"></i>';
            };

            recognition.onend = function () {
                // Volver al ícono de micrófono cuando el reconocimiento termine
                startVoiceButton.innerHTML = '<i class="fa-solid fa-microphone"></i>';
            };
        }


        // Llamada de búsqueda normal
        function performSearch() {
            const query = searchInput.value.trim();

            if (query.length > 0) {
                // Siempre redirigir a la página 1 en una nueva búsqueda
                window.location.href = '?query=' + encodeURIComponent(query) + '&page=1';
            } else {
                // Si el campo de búsqueda está vacío, redirigir al home.php
                window.location.href = 'user_home.php';
            }
        }


        // Asociar la búsqueda por voz
        startVoiceButton.onclick = startVoiceSearch;

        // Búsqueda normal por clic en el botón
        searchButton.onclick = performSearch;

    </script>

    <script>
        let lastScrollTop = 0; // Variable para almacenar la última posición del scroll

        // Función que detecta la dirección del scroll
        window.addEventListener('scroll', function () {
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
        document.addEventListener('DOMContentLoaded', function () {
            const sideMenu = document.getElementById('sideMenu');
            sideMenu.classList.remove('open'); // Asegura que el menú esté cerrado al inicio
        });
    </script>

    <script>
        // Botón hacia arriba
        const scrollTopBtn = document.getElementById('scrollTopBtn');

        // Mostrar/ocultar el botón según el scroll
        window.onscroll = function () {
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
            <h3>Cine - Poli says: </h3>
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
    document.addEventListener("DOMContentLoaded", function() {
        var alertModal = document.getElementById("alertModal");
        var alertMessage = document.getElementById("alertMessage");

        if (alertMessage) {
            alertModal.style.display = "flex"; //'flex' para centrar el modal
        }

        window.closeModal = function() {
            alertModal.style.display = "none";
        };
    });
</script>

</body>

</html>