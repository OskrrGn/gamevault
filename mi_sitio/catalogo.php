<?php
session_start();
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

$conn = new mysqli("localhost", "root", "", "sitio_web");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$filtro = "";
$parametros = [];

if (!empty($_GET['busqueda'])) {
    $filtro .= " AND titulo LIKE ?";
    $parametros[] = "%" . $_GET['busqueda'] . "%";
}

if (!empty($_GET['genero'])) {
    $filtro .= " AND genero = ?";
    $parametros[] = $_GET['genero'];
}

if (!empty($_GET['plataforma'])) {
    $filtro .= " AND plataforma = ?";
    $parametros[] = $_GET['plataforma'];
}

$sql_videojuegos = "SELECT * FROM videojuegos WHERE 1=1 $filtro";
$stmt_videojuegos = $conn->prepare($sql_videojuegos);
if ($parametros) {
    $tipos = str_repeat("s", count($parametros));
    $stmt_videojuegos->bind_param($tipos, ...$parametros);
}
$stmt_videojuegos->execute();
$resultado_videojuegos = $stmt_videojuegos->get_result();

$sql_coleccionables = "SELECT * FROM coleccionables";
$resultado_coleccionables = $conn->query($sql_coleccionables);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GameVault - Catálogo</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="./disenio.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
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
        .card-title {
            font-family: 'Press Start 2P', cursive; 
            color: white !important;
            font-size: 1.5rem; 
            font-weight: 700;  
        }
        .card-text {
            font-family: 'Roboto', sans-serif; 
            color: white !important;
            font-size: 1rem; 
            text-align: justify;
            margin-top: 0.5rem;
            line-height: 1.4; 
        }
        .form-control, .form-select {
            font-size: 0.9rem;
        }
        .form-filtros {
            background-color: #2c2c2c;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .navbar-nav .nav-link.active {
            color: #ffcc00;
        }
        @keyframes vibrar {
            0% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
            100% { transform: translate(0); }
        }
        .vibrar {
            animation: vibrar 0.3s ease;
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
        <a class="nav-link" href="ofertas.php">Ofertas</a>
        <a class="nav-link" href="preventas.php">Preventas</a>
        <a class="nav-link active" href="catalogo.php">Videojuegos</a>
        <a class="nav-link" href="coleccionables.php">Coleccionables</a>
    </div>
</nav>

<main class="container my-5">
    <h2 class="titulo-seccion">Catálogo de Videojuegos</h2>

    <form method="GET" class="form-filtros text-white">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
            <label class="form-label">Buscar</label>
            <input type="text" name="busqueda" class="form-control" placeholder="Título..." value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>">
            </div>
            <div class="col-6 col-md-3">
            <label class="form-label">Género</label>
            <select name="genero" class="form-select">
                <option value="">Todos</option>
                <option value="Acción">Acción</option>
                <option value="Aventura">Aventura</option>
                <option value="RPG">RPG</option>
            </select>
            </div>
            <div class="col-6 col-md-3">
            <label class="form-label">Plataforma</label>
            <select name="plataforma" class="form-select">
                <option value="">Todas</option>
                <option value="PS5">PS5</option>
                <option value="Xbox">Xbox</option>
                <option value="PC">PC</option>
                <option value="Switch">Switch</option>
            </select>
            </div>
            <div class="col-12 col-md-2">
            <button type="submit" class="btn btn-warning w-100 mt-2 mt-md-0">Filtrar</button>
            </div>
        </div>
        </form>



    <h3 class="titulo-seccion">Videojuegos</h3>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php while ($juego = $resultado_videojuegos->fetch_assoc()): ?>
            <div class="col">
                <div class="card h-100 text-white">
                    <img src="<?= htmlspecialchars($juego['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($juego['titulo']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($juego['titulo']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($juego['descripcion']) ?></p>
                        <p class="card-text"><strong>$<?= number_format($juego['precio'], 2) ?></strong></p>
                        <button class="btn btn-outline-success btn-sm agregar-carrito"
                                data-id="<?= $juego['id'] ?>"
                                data-tipo="videojuego"
                                data-titulo="<?= htmlspecialchars($juego['titulo']) ?>"
                                data-precio="<?= $juego['precio'] ?>">
                            Agregar al carrito
                        </button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
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
