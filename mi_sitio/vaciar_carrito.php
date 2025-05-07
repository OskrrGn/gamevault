<?php
session_start();

// Vaciar solo el carrito
unset($_SESSION['carrito']);

// Redirigir a catálogo
header('Location: catalogo.php');
exit();
?>
