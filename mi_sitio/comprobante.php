<?php
session_start();

if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    header('Location: index.php');
    exit();
}

$nombre = $_POST['nombre'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$metodo_pago = $_POST['metodo_pago'] ?? '';

$carrito = $_SESSION['carrito'];
$total = 0;
foreach ($carrito as $producto) {
    $subtotal = $producto['precio'] * $producto['cantidad'];
    $total += $subtotal;
}

$numero_recibo = rand(1000, 9999);
$numero_pedido = rand(10000, 99999);
$fecha_entrega = date('Y-m-d', strtotime('+7 days'));

require_once('tcpdf/tcpdf.php');

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 28);  
$pdf->Cell(0, 10, 'Comprobante de Compra', 0, 1, 'C');
$pdf->Ln(10);

$logo_path = 'assets/img/logo.jpeg'; 
$pdf->SetFont('helvetica', 'B', 24); 
$pdf->Cell(95, 10, 'GameVault', 0, 0, 'L');
$pdf->Image($logo_path, 170, 20, 30, 30, 'JPEG');
$pdf->Ln(40);

$pdf->SetFont('helvetica', 'B', 12); 
$pdf->Cell(50, 10, 'Nombre:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, $nombre, 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 12); 
$pdf->Cell(50, 10, 'Dirección:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 12); 
$pdf->Cell(0, 10, $direccion, 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 12);  
$pdf->Cell(50, 10, 'Teléfono:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 12); 
$pdf->Cell(0, 10, $telefono, 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 12);  
$pdf->Cell(50, 10, 'Método de Pago:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 12);  
$pdf->Cell(0, 10, ucfirst($metodo_pago), 0, 1, 'L');

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);  
$pdf->Cell(50, 10, 'Número de Recibo:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 12);  
$pdf->Cell(0, 10, $numero_recibo, 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 12);  
$pdf->Cell(50, 10, 'Número de Pedido:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 12);  
$pdf->Cell(0, 10, $numero_pedido, 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 12);  
$pdf->Cell(50, 10, 'Fecha de Compra:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 12);  
$pdf->Cell(0, 10, date('Y-m-d'), 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 12);  
$pdf->Cell(50, 10, 'Fecha de Entrega:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 12);  
$pdf->Cell(0, 10, $fecha_entrega, 0, 1, 'L');

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);  
$pdf->Cell(0, 10, 'Productos Comprados:', 0, 1);
$pdf->SetFont('helvetica', '', 12);  
foreach ($carrito as $producto) {
    $subtotal = $producto['precio'] * $producto['cantidad'];
    $pdf->Cell(0, 10, $producto['titulo'] . ' - $' . number_format($producto['precio'], 2) . ' x ' . $producto['cantidad'], 0, 1);
}

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12); 
$pdf->Cell(0, 10, 'Total: $' . number_format($total, 2), 0, 1);

$ruta_carpeta = 'comprobantes';
if (!file_exists($ruta_carpeta)) {
    mkdir($ruta_carpeta, 0777, true);
}

$save_path = __DIR__ . '/comprobantes/comprobante_' . $numero_pedido . '.pdf';
$pdf->Output($save_path, 'F');

$_SESSION['comprobante_pdf'] = $save_path;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra Exitosa - GameVault</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1f1f1f;
            font-family: 'Press Start 2P', cursive;
            color: white;
        }
        .titulo-seccion {
            margin-top: 40px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2rem;
        }
        .card {
            background-color: white;
            border: none;
            border-radius: 10px;
        }
        .comprobante {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        .comprobante img {
            width: 100px;
            height: auto;
        }
        .alerta {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
        .form-control {
            background-color: gray;
        }
    </style>
</head>
<body>

<header class="encabezado d-flex align-items-center justify-content-between px-4 py-2 bg-dark text-white">
    <h1 class="fs-4 m-0">GameVault</h1>
</header>

<main class="container my-5">
    <h2 class="titulo-seccion">Compra Exitosa</h2>
    <div class="card comprobante">
        <div class="d-flex justify-content-between">
            <h4>GameVault</h4>
            <img src="assets/img/logo.jpeg" alt="Logo GameVault">
        </div>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></p>
        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($direccion); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></p>
        <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars(ucfirst($metodo_pago)); ?></p>

        <p><strong>Número de recibo:</strong> <?php echo $numero_recibo; ?></p>
        <p><strong>Número de pedido:</strong> <?php echo $numero_pedido; ?></p>
        <p><strong>Fecha actual:</strong> <?php echo date('Y-m-d'); ?></p>
        <p><strong>Fecha de entrega:</strong> <?php echo $fecha_entrega; ?></p>
        <h4 class="mt-4">Productos Comprados:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($carrito as $producto) {
                    $subtotal = $producto['precio'] * $producto['cantidad'];
                    echo "<tr>
                            <td>{$producto['titulo']}</td>
                            <td>{$producto['cantidad']}</td>
                            <td>\${$producto['precio']}</td>
                            <td>\${$subtotal}</td>
                        </tr>";
                }
                ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <form action="enviar_comprobante.php" method="POST">
            <label for="email">Enviar a tu correo:</label>
            <input type="email" name="email" required>
            <button type="submit">Enviar Comprobante</button>
        </form>

        <?php if (isset($_GET['enviado']) && $_GET['enviado'] == 1): ?>
            <div class="alerta" id="alertaMensaje" style="display: block;">
                <div class="alert alert-success" role="alert">
                    El comprobante ha sido enviado correctamente.
                </div>
                <a href="#" onclick="volverAlCatalogo()" class="btn btn-light">Volver al Catálogo</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    function volverAlCatalogo() {
        localStorage.removeItem('carrito'); 
        window.location.href = 'vaciar_carrito.php';
    }
</script>

</body>
</html>
