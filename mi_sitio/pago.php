<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['carrito'])) {
        $_SESSION['carrito'] = $data['carrito'];
        echo json_encode(['status' => 'success']);
        exit;
    }
}

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$usuario = $_SESSION['usuario'];
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

if (count($carrito) == 0) {
    $mensaje_error = "No tienes productos en el carrito.";
} else {
    $mensaje_error = "";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proceso de Compra - GameVault</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            background-color: #333;
            border: none;
            border-radius: 10px;
        }
        .modal-dialog{
            color:black;
        }
    </style>
</head>
<body>

<header class="encabezado d-flex align-items-center justify-content-between px-4 py-2 bg-dark text-white">
    <h1 class="fs-4 m-0">GameVault</h1>
</header>

<main class="container my-5">
    <div class="mt-3 text-center">
        <a href="catalogo.php" class="btn btn-outline-light">Volver al Catálogo</a>
    </div>
    <h2 class="titulo-seccion">Resumen de tu Pedido</h2>

    <?php if ($mensaje_error): ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $mensaje_error; ?>
        </div>
    <?php else: ?>
        <div class="row">
        <div class="col-12">
            <h4>Productos en tu carrito:</h4>
            <table class="table table-dark">
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
                    $total = 0;
                    foreach ($carrito as $producto) {
                        $subtotal = $producto['precio'] * $producto['cantidad'];
                        $total += $subtotal;
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
        </div>
    </div>

    <!-- Formulario de compra debajo -->
    <div class="row mt-4">
        <div class="col-12 col-md-8 offset-md-2">
            <h4>Datos de Envío:</h4>
            <form id="form-compra" method="POST" action="comprobante.php" onsubmit="return validarFormulario()">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" required>
                </div>

                <h4>Selecciona el Método de Pago:</h4>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metodo_pago" id="paypal" value="paypal" required>
                    <label class="form-check-label" for="paypal">PayPal</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metodo_pago" id="tarjeta" value="tarjeta" required>
                    <label class="form-check-label" for="tarjeta">Tarjeta de Crédito/Débito</label>
                </div>

                <div id="tarjeta-info" style="display:none;">
                    <div class="mb-3">
                        <label for="nombre_tarjeta" class="form-label">Nombre en la tarjeta</label>
                        <input type="text" class="form-control" id="nombre_tarjeta" name="nombre_tarjeta" required>
                    </div>
                    <div class="mb-3">
                        <label for="numero_tarjeta" class="form-label">Número de la tarjeta</label>
                        <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" required pattern="\d{16}" maxlength="16" inputmode="numeric">
                    </div>
                    <div class="mb-3">
                        <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento (MM/AA)</label>
                        <input type="text" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                    </div>
                    <div class="mb-3">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="cvv" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 mt-3">Confirmar Compra</button>
            </form>
        </div>
    </div>

    <?php endif; ?>
</main>

<div class="modal fade" id="modalError" tabindex="-1" aria-labelledby="modalErrorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalErrorLabel">¡Error de Pago!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalMensaje">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
    const tarjetaRadio = document.getElementById('tarjeta');
    const paypalRadio = document.getElementById('paypal');
    const tarjetaInfo = document.getElementById('tarjeta-info');
    const tarjetaInputs = document.querySelectorAll('#tarjeta-info input');

    tarjetaRadio.addEventListener('change', function() {
        if (tarjetaRadio.checked) {
            tarjetaInfo.style.display = 'block';
            tarjetaInputs.forEach(input => input.required = true);
        }
    });

    paypalRadio.addEventListener('change', function() {
        if (paypalRadio.checked) {
            tarjetaInfo.style.display = 'none';
            tarjetaInputs.forEach(input => input.required = false);
        }
    });

    function validarFormulario() {
        const metodoPago = document.querySelector('input[name="metodo_pago"]:checked').value;
        let mensajeError = '';

        if (metodoPago === 'tarjeta') {
            const numeroTarjeta = document.getElementById('numero_tarjeta').value.trim();
            const fechaVencimiento = document.getElementById('fecha_vencimiento').value.trim();

            if (!/^\d{16}$/.test(numeroTarjeta)) {
                mensajeError = 'El número de tarjeta debe tener exactamente 16 dígitos.';
            }

            const partesFecha = fechaVencimiento.split('/');
            if (partesFecha.length !== 2) {
                mensajeError = 'La fecha de vencimiento debe estar en formato MM/AA.';
            }

            const mes = parseInt(partesFecha[0], 10);
            const anio = parseInt('20' + partesFecha[1], 10); 
            const fechaActual = new Date();
            const mesActual = fechaActual.getMonth() + 1; 
            const anioActual = fechaActual.getFullYear();

            if (isNaN(mes) || isNaN(anio) || mes < 1 || mes > 12) {
                mensajeError = 'El mes de vencimiento debe ser entre 01 y 12.';
            }

            if (anio < anioActual || (anio === anioActual && mes < mesActual)) {
                mensajeError = 'La tarjeta está vencida.';
            }
        }

        if (mensajeError) {
            document.getElementById('modalMensaje').textContent = mensajeError;
            var myModal = new bootstrap.Modal(document.getElementById('modalError'));
            myModal.show();
            return false;  
        }
        return true;  
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
