<?php
session_start();
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GameVault - Ofertas</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="./disenio.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1f1f1f;
            font-family: 'Press Start 2P', cursive;
        }
        header {
            background-color: #1f1f1f;
            padding: 20px;
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
        main {
            padding-top: 70px; 
        }
        .card-text {
            font-family: 'Roboto', sans-serif; 
            font-size: 1rem; 
            text-align: justify;
            color: white !important;
            margin-top: 0.5rem;
            line-height: 1.4; 
        }
        .carousel-mini {
            max-width: 1000px;
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
        }

        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            text-align: center;
        }

        .btn-outline-warning {
            color: #ffcc00;
            border-color: #ffcc00;
        }

        .btn-outline-warning:hover {
            background-color: #ffcc00;
            color: #333;
        }

        .footer {
            background-color: #333;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .navbar {
            background-color: #222;
        }
        
        .navbar-nav .nav-link {
            color: white;
        }

        .navbar-nav .nav-link.active {
            color: #ffcc00;
        }
        
        .oferta-tag {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff3333;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
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
        <a class="nav-link" href="main.php">Inicio</a>
        <a class="nav-link active" href="ofertas.php">Ofertas</a>
        <a class="nav-link" href="preventas.php">Preventas</a>
        <a class="nav-link" href="catalogo.php">Videojuegos</a>
        <a class="nav-link" href="coleccionables.php">Coleccionables</a>
    </div>
</nav>



<main class="container my-5">
    <section class="mas-vendidos">
    <h2 class="titulo-seccion">¡Ofertas Exclusivas por Tiempo Limitado!</h2>
    <div id="carouselMasVendidos" class="carousel slide carousel-mini" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/img/resident.jpg" class="d-block w-100" alt="resident">
            </div>
            <div class="carousel-item">
                <img src="assets/img/slay.jpg" class="d-block w-100" alt="slay">
            </div>
            <div class="carousel-item">
                <img src="assets/img/days.jpg" class="d-block w-100" alt="days">
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
<h2 class="titulo-seccion">¡Ofertas Especiales!</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
        <div class="col">
            <div class="card h-100 text-white">
                <div class="position-relative">
                    <img src="assets/img/eldenring.jpg" class="card-img-top" alt="Elden Ring">
                    <span class="oferta-tag">-40%</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Elden Ring</h5>
                    <p class="card-text">¡Oferta especial! De $1,299 a $779.40</p>
                    <p class="text-warning">Disponible hasta agotar existencias</p>
                </div>
            </div>
        </div>
        
        <div class="col">
            <div class="card h-100 text-white">
                <div class="position-relative">
                    <img src="assets/img/mario.jpg" class="card-img-top" alt="Super Mario Wonder">
                    <span class="oferta-tag">-30%</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Super Mario Wonder</h5>
                    <p class="card-text">¡Precio especial! De $1,399 a $979.30</p>
                    <p class="text-warning">Oferta válida hasta 30/06/2025</p>
                </div>
            </div>
        </div>
        
        <div class="col">
            <div class="card h-100 text-white">
                <div class="position-relative">
                    <img src="assets/img/cyberpunk.jpg" class="card-img-top" alt="Cyberpunk 2077">
                    <span class="oferta-tag">-50%</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Cyberpunk 2077</h5>
                    <p class="card-text">¡Gran descuento! De $999 a $499.50</p>
                    <p class="text-warning">Incluye todas las actualizaciones</p>
                </div>
            </div>
        </div>
    </div>    
</main>

<footer class="footer">
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