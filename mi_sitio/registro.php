<?php
include('includes/conexion.php');

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $email = mysqli_real_escape_string($conexion, $_POST['correo']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $fecha_nacimiento = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);
    $genero = mysqli_real_escape_string($conexion, $_POST['genero']);
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);  
    $password = password_hash(mysqli_real_escape_string($conexion, $_POST['password']), PASSWORD_BCRYPT);
    $query = "INSERT INTO usuarios (nombre, apellido, usuario, email, telefono, fecha_nacimiento, genero, direccion, password) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, 'sssssssss', $nombre, $apellido, $usuario, $email, $telefono, $fecha_nacimiento, $genero, $direccion, $password); 

    if (mysqli_stmt_execute($stmt)) {
        $exito = "<p>Registro exitoso. <a href='login.php'>Inicia sesión aquí.</a></p>";
    } else {
        $error = "Error al registrar el usuario: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - GameVault</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/design.css">
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <h1 class="display-4 text-center">Registro de Usuario</h1>
            <form method="POST" class="mt-4 mx-auto" style="max-width: 400px;">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group mt-3">
            <label for="usuario">Usuario</label>
            <input type="text" class="form-control" id="usuario" name="usuario" required>
        </div>
        <div class="form-group mt-3">
            <label for="contraseña">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group mt-3">
            <label for="apellido">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>
        <div class="form-group mt-3">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required>
        </div>
        <div class="form-group mt-3">
            <label for="telefono">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required>
        </div>
        <div class="form-group mt-3">
            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
        </div>
        <div class="form-group mt-3">
            <label for="genero">Género</label>
            <input type="text" class="form-control" id="genero" name="genero" required>
        </div>
        <div class="form-group mt-3">
            <label for="correo">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </div>
    </form>
       <?php if ($error): ?>
            <div class="alert alert-danger mt-3 text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($exito): ?>
            <div class="alert alert-success mt-3 text-center">
                <?php echo $exito; ?>
            </div>
            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
            </div>
        <?php endif; ?>

        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-secondary">Regresar al Inicio</a>
        </div>
    </div>

    <footer class="text-center mt-5">
        <small>&copy; 2025 GameVault - Todos los derechos reservados</small>
    </footer>
</body>
</html>
