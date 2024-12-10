<?php
include('conexion.php');

// Consultar usuarios
$sqlUsuarios = "SELECT * FROM registro WHERE rol = 'usuario'";
$resultUsuarios = $conexion->query($sqlUsuarios);

// Consultar administradores
$sqlAdmins = "SELECT * FROM registro WHERE rol = 'admin'";
$resultAdmins = $conexion->query($sqlAdmins);
?>

<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <link rel="icon" href="assets/ico.ico">
    <title>Food Place</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            /* Asegura que el ancho y alto incluyan el relleno y el borde */
            font-family: 'Open Sans', sans-serif;
            /* Aplica la fuente importada */
            text-decoration: none;
            /* Elimina el subrayado de los enlaces */
        }

        /*------------------GENERAL------------------*/
        /*------------------GENERAL------------------*/

        main {
            flex: 1;
            background: #fff;
            /* Color de fondo principal */
            padding: 0;
            /* Espaciado interno del contenido */
            position: relative;
            /* Posición relativa para contener elementos absolutos */
            margin-top: 10px;
            /* Espacio superior para evitar que el contenido quede detrás del encabezado */
            padding-bottom: 50px;
        }

        html,
        body {
            scroll-behavior: smooth;
            height: 100%;
            /* Asegura que el body ocupe toda la altura de la pantalla */
            margin: 0;
            /* Elimina los márgenes predeterminados */
            display: flex;
            flex-direction: column;
            /* Alinea el contenido en una columna */
        }

        h2 {
            font-family: 'Montserrat', sans-serif;
            font-style: italic;
            font-weight: bold;
            color: #00034d;
        }


        /*---------CABECERA---------- */
        /*---------CABECERA---------- */

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 20px;
            background-color: #fff;
            color: #00034d;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .header-container {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .menu-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .menu-btn img {
            height: 100px;
            width: auto;
        }

        /*---------MENU LATERAL---------- */
        /*---------MENU LATERAL---------- */
        /* Side menu */
        .side-menu {
            position: fixed;
            top: 140px;
            /* Ajusta esto según la altura de tu header (si el header es de 80px) */
            left: -250px;
            /* El menú comienza fuera de la pantalla */
            width: 200px;
            /* Ajusta el ancho al tamaño del logo */
            height: 488px;
            /* Resta la altura del header para que el menú ocupe el espacio restante */
            background-color: #00034d;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            /* Alinea el contenido al inicio */
            transition: left 0.1s ease-in-out;
            padding-top: 10px;
            z-index: 1;
        }

        /* Estilo del botón "X" */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        /* Estilo para la lista */
        .side-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        /* Estilo de los elementos de la lista */
        .side-menu ul li {
            margin: 20px 0;
            text-align: center;
            padding: 10px 0;
            /* Añade padding para hacer que el área del hover sea más grande */
            width: 100%;
            /* Asegura que el hover cubra todo el ancho */
        }

        /* Estilo de los enlaces */
        .side-menu ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            display: block;
            /* Asegura que el enlace ocupe todo el espacio dentro del li */
            padding: 10px 0;
            /* Añade padding para que el área del hover cubra el tamaño del contenedor */
            width: 100%;
            /* Asegura que el área del hover ocupe todo el ancho */
        }

        /* Efecto hover para los enlaces */
        .side-menu ul li a:hover {
            background-color: #181c8a;
        }

        /* Menú visible */
        .side-menu.open {
            left: 0;
            /* Muestra el menú */
        }


        .container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 15px;
            padding: 0 80px;

        }

        .table-container {
            width: 50%;
            margin: 20px;

        }

        table {
            width: 100%;
            border-collapse: collapse;
            align-items: center;

        }

        /* Títulos de columnas centrados */
        th {
            text-align: center;
            font-weight: bold;
            background-color: #00034d;
            color: white;
        }

        /* Columna de ID - Estrecha y centrada */
        th:first-child,
        td:first-child {
            text-align: center;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .convert-button {
            background-color: #00034d;
            padding: 8px 12px;
            margin: 5px 0;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            color: #fff;
        }

        .convert-button:hover {
            background-color: #1f24b1;
        }

        /* Centrando las celdas y el botón */
        .centered {
            text-align: center;
            align-items: center;
        }

        /* Estilo para el título dentro de la tabla */
        .table-title {
            background-color: #00034d;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 18px;
            padding: 8px;
        }

        .pie-pagina .grupo-2 {
            background-color: #00034d;
            padding: 5px 5px;
            text-align: center;
            color: #fff;
            width: 100%;
            z-index: 10;
            margin-top: auto;
        }

        .pie-pagina .grupo-2 small {
            font-size: 12px;
        }

        /*--------------BOTON DE INICIO----------------*/
        /*--------------BOTON DE INICIO----------------*/

        #scrollTopBtn {
            display: none;
            /* Se oculta por defecto */
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #00034d;
            color: white;
            border: none;
            outline: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            font-size: 20px;
        }

        #scrollTopBtn:hover {
            background-color: #14176d;
        }

        /* Evita que el botón se superponga al footer */
        @media (min-width: 768px) {
            #scrollTopBtn {
                margin-bottom: 20px;
                /* Para mantener separación desde el footer */
            }
        }
    </style>
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
                    <li><a href="admin_home.php"><i class="fa-solid fa-chair">&nbsp; &nbsp;</i>Restaurants</a></li>
                    <li><a href="add_restaurant.php"><i class="fa-solid fa-plus">&nbsp; &nbsp;</i>Add Restaurant</a></li>
                    <li><a href="listadmin_reservation.php"><i class="fa-solid fa-book-open">&nbsp; &nbsp;</i>Reservations</a></li>
                    <li><a href="logout.php"><i class="fa fa-sign-out">&nbsp; &nbsp;</i>Log Out</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <!-- Tabla de Usuarios -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th colspan="4" class="table-title">User Information</th>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultUsuarios && $resultUsuarios->num_rows > 0) {
                        while ($row = $resultUsuarios->fetch_assoc()) { ?>
                            <tr>
                                <td class="centered"><?php echo $row['Id_usuario']; ?></td>
                                <td><?php echo $row['user']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td class="centered">
                                    <form action="convertir.php" method="POST">
                                        <input type="hidden" name="Id_usuario" value="<?php echo $row['Id_usuario']; ?>">
                                        <button type="submit" class="convert-button">Convert to Admin</button>
                                    </form>
                                </td>
                            </tr>
                    <?php }
                    } else {
                        echo "<tr><td colspan='4'>There are no users available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Tabla de Administradores -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th colspan="3" class="table-title">Administrator Information</th>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultAdmins && $resultAdmins->num_rows > 0) {
                        while ($row = $resultAdmins->fetch_assoc()) { ?>
                            <tr>
                                <td class="centered"><?php echo $row['Id_usuario']; ?></td>
                                <td><?php echo $row['user']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                            </tr>
                    <?php }
                    } else {
                        echo "<tr><td colspan='3'>There are no administrators available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>



    <button id="scrollTopBtn" title="Go to top" onclick="scrollToTop()">
        <i class="fa-solid fa-arrow-up-long"></i>
    </button>

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

</body>

</html>