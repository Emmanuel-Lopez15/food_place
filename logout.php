<?php
    session_start();
    session_unset();
    session_destroy();
    $_SESSION['sesion'] = "sesion cerrada";
    header('Location: index.php');
?>