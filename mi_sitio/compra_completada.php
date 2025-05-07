<?php
session_start();
include('conexion.php');

// Verificamos si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

// Si es necesario, puedes recuperar la última compra registrada para mostrar detalles
$query = "SELECT * FROM compras WHERE usuario_id = ? ORDER BY fecha DESC LIMIT 1";
$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $usuario['id']);
$stmt->execute();
$result = $stmt->get_result();
$compra = $result->fetch_assoc();

// Si la compra existe, mostramos el resumen
if ($compra) {
    $productos = json_decode($compra['productos'], true);  // Convertir JSON a array de productos
    $total = 0;
    foreach ($productos as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra Completa</title>
</head>
<body>
    <h1>¡Compra completada con éxito!</h1>
    <p>Gracias por tu compra, <?php echo $usuario['nombre']; ?>.</p>
    
    <h2>Resumen de tu pedido:</h2>
    <ul>
        <?php foreach ($productos as $producto): ?>
            <li><?php echo $producto['titulo']; ?> - $<?php echo $producto['precio']; ?> x <?php echo $producto['cantidad']; ?></li>
        <?php endforeach; ?>
    </ul>
    <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>
    <p>Tu pedido ha sido procesado y estará en camino pronto.</p>
    <a href="catalogo.php">Volver al catálogo</a>
</body>
</html>
