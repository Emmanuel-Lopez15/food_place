<?php
session_start();

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "";
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : "";

// Limpia de mensajes
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);
?>



<!-- Código HTML para la página -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="assets/ico.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <title>Food Place</title>

</head>

<body>

<header>
        <div class="header-container">
            <button class="menu-btn">
                <img src="assets/Logo.png" alt="Food Place Logo">
            </button>
            
        </div>
    </header>


    <main>
        <div class="container">
            <!-- Formulario de Login -->
            <div class="container-form">
                <form class="sign-in" action="login.php" method="POST">
                    <!-- Formulario de inicio de sesión que envía datos a login.php -->

                    <h2>Login</h2>
                    <span>Use your username and password</span>
                    <div class="container-input">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="container-input">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" placeholder="Password" maxlength="8" required>
                    </div>
                    <button class="button" type="submit">Sign In</button>
                </form>
            </div>


            <!-- Formulario de Registro -->
            <div class="container-form">
                <form class="sign-up" action="register.php" method="POST">
                    <!-- Formulario de registro que envía datos a register_user.php -->

                    <h2>Register</h2>
                    <span>Use your username, email and password</span>
                    <div class="container-input">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="container-input">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="container-input">
                        <ion-icon name="lock-closed-outline"></ion-icon> <!-- Icono para la contraseña -->
                        <input type="password" name="password" placeholder="Password (8 characters)" maxlength="8"
                            required>
                    </div>
                    <div class="container-input">
                        <ion-icon name="lock-closed-outline"></ion-icon> <!-- Icono para la contraseña -->
                        <input type="password" name="confirm_password" placeholder="Confirm your password" maxlength="8"
                            required>
                        <input type="hidden" value="usuario" name="rol">
                    </div>
                    <button class="button" type="submit">Sign Up</button> <!-- Botón para enviar el formulario -->
                </form>
            </div>


            <div class="container-welcome">
                <div class="welcome-sign-up welcome">
                    <p>Haven't you registered yet?</p>
                    <button class="button" id="btn-sign-up">Sign Up</button>
                </div>
                <div class="welcome-sign-in welcome">
                    <p>Already have an account?</p>
                    <button class="button" id="btn-sign-in">Sign In</button>
                </div>
            </div>

        </div>
        

        <button id="scrollTopBtn" title="Go to top" onclick="scrollToTop()">
            <i class="fa-solid fa-arrow-up-long"></i>
        </button>

    </main>

    <!-- Scripts para funcionalidad adicional -->
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <footer class="pie-pagina">
        <div class="grupo-2">
            <small>&copy; 2024 <b>- Centro de Investigación ITI 4-1</b> - All Rights Reserved.</small>
        </div>
    </footer>


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