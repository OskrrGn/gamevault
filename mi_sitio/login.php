<?php
session_start();
include("includes/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password = mysqli_real_escape_string($conexion, $_POST['password']);  
    $query = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, 's', $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) { 
            $_SESSION['usuario'] = $usuario;
            header("Location: main.php"); 
            exit();
        } else {
            $error = "La contraseña es incorrecta.";
        }
    } else {
        $error = "El usuario no existe.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="./disenio.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GameVault</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
    <div class="container text-center mt-5">
        <h1 class="display-4">Iniciar sesión</h1>
        <?php
        if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
        ?>
        <form action="login.php" method="POST" class="mt-4 mx-auto" style="max-width: 400px;">
            <input type="text" name="usuario" placeholder="Usuario" class="form-control mb-3" required>
            <input type="password" name="password" placeholder="Contraseña" class="form-control mb-3" required>
            <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
        </form>
        <div class="mt-4">
            <a href="registro.php" class="btn btn-secondary btn-lg">Registrarse</a>
        </div>
    </div>

    <footer class="text-center mt-5">
        <small>&copy; 2025 GameVault - Todos los derechos reservados</small>
    </footer>
</body>
</html>
