<?php
require_once('tcpdf/tcpdf.php');  
require_once __DIR__ . '/vendor/autoload.php';

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$usuario = $_SESSION['usuario'];
if (is_string($usuario)) {
    $usuario = json_decode($usuario, true);
}

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
if (is_string($carrito)) {
    $carrito = json_decode($carrito, true);
}

if (count($carrito) == 0) {
    die("No hay productos en el carrito.");
}

// Datos de la compra
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$metodo_pago = $_POST['metodo_pago']; // paypal o tarjeta

// Registro de la compra en la base de datos
$conexion = new mysqli('localhost', 'root', '', 'sitio_web');
$fecha = date('Y-m-d H:i:s');
$total = 0;
foreach ($carrito as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
}

// Inserta el pedido en la base de datos
$query = "INSERT INTO pedidos (usuario_id, nombre, direccion, telefono, total, metodo_pago, fecha) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($query);
$stmt->bind_param("isssdss", $usuario['id'], $nombre, $direccion, $telefono, $total, $metodo_pago, $fecha);
$stmt->execute();

// Recupera el ID del pedido recién creado
$pedido_id = $conexion->insert_id;

// Guarda los productos en la tabla de 'detalle_pedidos'
foreach ($carrito as $producto) {
    $subtotal = $producto['precio'] * $producto['cantidad'];
    $query = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iiid", $pedido_id, $producto['id'], $producto['cantidad'], $producto['precio']);
    $stmt->execute();
}


$pdf = new TCPDF();  
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Título
$pdf->Cell(0, 10, 'Comprobante de Compra', 0, 1, 'C');
$pdf->Ln(10);

// Detalles de la compra
$pdf->Cell(0, 10, 'Nombre: ' . $nombre, 0, 1);
$pdf->Cell(0, 10, 'Direccion: ' . $direccion, 0, 1);
$pdf->Cell(0, 10, 'Telefono: ' . $telefono, 0, 1);

// Productos comprados
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Productos:', 0, 1);
foreach ($carrito as $producto) {
    $pdf->Cell(0, 10, $producto['titulo'] . ' - $' . number_format($producto['precio'], 2) . ' x ' . $producto['cantidad'], 0, 1);
}

// Total
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Total: $' . number_format($total, 2), 0, 1);

// Definir la ruta para guardar el archivo (Asegúrate de que este directorio existe y tiene permisos de escritura)
$save_path = 'C:/xampp/htdocs/mi_sitio/comprobantes/comprobante_' . $pedido_id . '.pdf';  // Ruta absoluta

// Guardar el PDF en el servidor
$pdf->Output($save_path, 'F'); // 'F' para guardar en el servidor

// Enviar el PDF por correo (opcional)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Si usas Composer, o incluye PHPMailer

$mail = new PHPMailer(true);
try {
    // Verificar si los datos del usuario existen antes de intentar enviar el correo
    if (isset($usuario['email']) && isset($usuario['nombre'])) {
        $mail->setFrom('tu-email@dominio.com', 'GameVault');
        $mail->addAddress($usuario['email'], $usuario['nombre']);
        $mail->addAttachment($save_path);  // Adjuntar el PDF generado

        $mail->Subject = 'Comprobante de Compra';
        $mail->Body    = 'Gracias por tu compra en GameVault. Adjunto encontrarás el comprobante de tu compra.';

        $mail->send();
        echo 'El correo ha sido enviado con el comprobante.';
    } else {
        echo 'No se encontraron los datos del usuario para enviar el correo.';
    }
} catch (Exception $e) {
    echo 'Error al enviar el correo: ', $mail->ErrorInfo;
}

// Vaciar el carrito después de completar la compra
unset($_SESSION['carrito']);
?>
