<?php
// Datos de conexión a la base de datos
$servidor = "localhost"; // Dirección del servidor MySQL
$usuario = "root"; // Nombre de usuario para la base de datos
$contraseña = ""; // Contraseña para la base de datos
$nombre_base_datos = "sitio_web"; // Nombre de la base de datos

// Establecemos la conexión
$conexion = mysqli_connect($servidor, $usuario, $contraseña, $nombre_base_datos);

// Verificamos si la conexión fue exitosa
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
