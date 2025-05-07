<?php
session_start();
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
echo '<pre>';
print_r($usuario);
echo '</pre>';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GameVault - Página Principal</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="./disenio.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1f1f1f;
            font-family: 'Press Start 2P', cursive;
            color: white;
        }
        header {
            background-color: #1f1f1f;
            padding: 30px 60px; 
            display: flex;
            align-items: center;
            justify-content: space-between; 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1020; 
        }
        .navbar {
            background-color: #292929;
            padding: 10px 0;
            display: flex;
            justify-content: center;
            gap: 20px;
            position: fixed;
            top: 70px;  
            left: 0;
            width: 100%;
            z-index: 1010; 
        }
        .card-text {
            font-family: 'Roboto', sans-serif; 
            font-size: 1rem; 
            text-align: justify;
            color: white !important;
            margin-top: 0.5rem;
            line-height: 1.4; 
        }
        main {
            padding-top: 70px; 
        }
        .carousel-mini {
            max-width: 800px;
            margin: 40px auto;
        }
        .carousel-mini .carousel-item img {
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
        .titulo-seccion {
            color: white;
            margin-top: 40px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2rem;
        }
        .card {
            background-color: #333;
            border: none;
            border-radius: 10px;
            color: white;
        }
        .card-title {
            font-size: 1.2rem;
        }
        .card-body {
            text-align: center;
        }
        .card-text {
            font-size: 0.9rem;
        }
        .btn-outline-info {
            border: 1px solid #e67e22;
            color: #e67e22;
            transition: 0.3s;
        }
        .btn-outline-info:hover {
            background-color: #e67e22;
            color: white;
        }
        footer {
            background-color: #333;
            padding: 15px 0;
            
        }
        .navbar-nav .nav-link.active {
            color: #ffcc00;
        }
        
    </style>
</head>
<body>
<header class="encabezado d-flex align-items-center justify-content-between px-4 py-2 bg-dark text-white">
    <div class="d-flex align-items-center gap-3">
        <a href="main.php">
            <img src="assets/img/logo.jpeg" alt="Logo GameVault" class="logo-img" style="height: 60px;">
        </a>
        <h1 class="fs-4 m-0">GameVault</h1>
    </div>
    
    <div class="header-icons d-flex align-items-center gap-4 position-relative">
        <?php if ($usuario): ?>
            <span class="text-white fw-bold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($usuario); ?></span>
        <?php endif; ?>
        
        <i class="fas fa-user-circle fs-4" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
        
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="dropdownUser">
            <?php if ($usuario): ?>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a class="dropdown-item" href="login.php">Iniciar Sesión</a></li>
                <li><a class="dropdown-item" href="register.php">Registrarse</a></li>
            <?php endif; ?>
        </ul>

        <i class="fas fa-shopping-cart fs-4" style="cursor: pointer;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCarrito"></i>
    </div>

</header>

<nav class="navbar navbar-expand-md navbar-dark bg-secondary px-4">
    <div class="navbar-nav gap-3">
        <a class="nav-link active" href="main.php">Inicio</a>
        <a class="nav-link" href="ofertas.php">Ofertas</a>
        <a class="nav-link" href="preventas.php">Preventas</a>
        <a class="nav-link" href="catalogo.php">Videojuegos</a>
        <a class="nav-link" href="coleccionables.php">Coleccionables</a>
    </div>
</nav>

<main class="container my-5">
    <h2 class="titulo-seccion">Últimas noticias del mundo gamer</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 justify-content-center">
        <div class="col">
            <div class="card h-100">
                <img src="assets/img/gtavi.jpeg" class="card-img-top" alt="GTA VI">
                <div class="card-body">
                    <h5 class="card-title">GTA VI: ¡Fecha de lanzamiento confirmada!</h5>
                    <p class="card-text">Rockstar finalmente anuncia la salida de GTA VI para 2025. ¡La espera valió la pena!</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <img src="assets/img/silksong.jpeg" class="card-img-top" alt="Silksong">
                <div class="card-body">
                    <h5 class="card-title">Silksong: ¿El indie más esperado?</h5>
                    <p class="card-text">Hollow Knight: Silksong sigue generando hype. Se revelan nuevos enemigos y escenarios.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <img src="assets/img/nintendi2.jpeg" class="card-img-top" alt="Nintendo Switch 2">
                <div class="card-body">
                    <h5 class="card-title">Nintendo Switch 2: primeros detalles</h5>
                    <p class="card-text">Nintendo revela los primeros detalles de su nueva consola: más potencia y compatibilidad con juegos anteriores.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <img src="assets/img/ps-portal.jpeg" class="card-img-top" alt="PS Portal">
                <div class="card-body">
                    <h5 class="card-title">Sony actualiza PS Portal de PS5</h5>
                    <p class="card-text">PlayStation Portal se actualiza con mejoras en el streaming sin necesidad de PS5. ¡Más comodidad!</p>
                </div>
            </div>
        </div>
    </div>
    <section class="mas-vendidos">
        <h2 class="titulo-seccion">Más Vendidos</h2>
        <div id="carouselMasVendidos" class="carousel slide carousel-mini" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/img/ea.jpg" class="d-block w-100" alt="EA">
                </div>
                <div class="carousel-item">
                    <img src="assets/img/god1.jpg" class="d-block w-100" alt="GOW">
                </div>
                <div class="carousel-item">
                    <img src="assets/img/asasins1.jpg" class="d-block w-100" alt="Assassin's Creed">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselMasVendidos" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselMasVendidos" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </section>
</main>

<footer class="text-center bg-dark text-white py-4 mt-5">
    <small>&copy; 2025 GameVault - Todos los derechos reservados</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<div class="offcanvas offcanvas-end bg-dark text-white" tabindex="-1" id="offcanvasCarrito" aria-labelledby="offcanvasCarritoLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasCarritoLabel">Mi Carrito</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">
    <div id="carrito-contenido" class="mb-3">
    </div>
    <div class="mt-auto">
        <button id="btn-pagar" type="button" class="btn btn-warning w-100 mt-3">Ir a pagar</button>
    </div>
  </div>
</div>
<script src="./nuevoCarrito.js" defer></script>
</body>
</html>
