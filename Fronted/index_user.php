<?php
session_start();
require_once '../Backend/conexion.php';

// ---- FILTRO DE BÚSQUEDA ----
$where = "1=1";
$busqueda = "";
if (isset($_GET['q']) && $_GET['q'] != "") {
  $q = $link->real_escape_string($_GET['q']);
  $where .= " AND (l.Titulo LIKE '%$q%' OR a.Nombre LIKE '%$q%')";
  $busqueda = $q;
}

if (isset($_GET['categoria']) && $_GET['categoria'] != "" && $_GET['categoria'] != "0") {
  $cat = intval($_GET['categoria']);
  $where .= " AND l.IdCategoria = $cat";
}

// ---- PAGINACIÓN ----
$por_pagina = 8;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$inicio = ($pagina - 1) * $por_pagina;

// Contar total de libros
$sqlTotal = "SELECT COUNT(*) as total FROM tlibros l 
             LEFT JOIN tautores a ON l.IdAutor=a.IdAutor 
             WHERE $where";
$totalRes = $link->query($sqlTotal);
$totalLibros = $totalRes->fetch_assoc()['total'];
$totalPaginas = ceil($totalLibros / $por_pagina);

// Obtener libros
$sql = "SELECT l.IdLibro, l.Titulo, l.Estado, l.Stock, l.RutaDoc, c.Nombre as Categoria, a.Nombre as Autor
        FROM tlibros l
        LEFT JOIN tautores a ON l.IdAutor = a.IdAutor
        LEFT JOIN tcategorias c ON l.IdCategoria = c.IdCategoria
        WHERE $where
        ORDER BY l.Titulo ASC
        LIMIT $inicio, $por_pagina";
$libros = $link->query($sql);

// Obtener categorías para el filtro
$cats = $link->query("SELECT IdCategoria, Nombre FROM tcategorias ORDER BY Nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biblioteca Hospitalaria</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Paleta personalizada -->
  <style>
    body {
      background-color: #f8efed;
      /* color5 */
    }

    .navbar-custom {
      background-color: #d40806;
      /* color1 */
      height: 60px;
    }

    .btn-primary {
      background-color: #90071a;
      /* color2 */
      border: none;
    }

    .btn-primary:hover {
      background-color: #08ceff;
      /* color3 */
      color: #000;
    }

    .badge-disponible {
      background-color: #0efff9;
      /* color4 */
      color: #000;
    }

    .card {
      border: 1px solid #90071a22;
    }

    /* Botón notificaciones flotante */
    .btn-notify {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .notify-badge {
      position: absolute;
      top: 8px;
      right: 8px;
      background: red;
      color: white;
      font-size: 14px;
      padding: 2px 6px;
      border-radius: 50%;
    }
  </style>
</head>

<body>

  <!-- Barra de navegación -->
  <nav class="navbar navbar-dark navbar-custom position-relative px-4">
    <!-- Botón perfil (izquierda) -->
    <div class="d-flex position-absolute start-0 ms-3">
      <a href="perfilUsuario.php" class="btn btn-light me-2">
        👤 Perfil
      </a>
      <a href="historial.php" class="btn btn-light">
        📖 Historial
      </a>
    </div>

    <!-- Título centrado -->
    <span class="navbar-brand mx-auto fw-bold text-white fs-4">
      📚 Biblioteca Hospitalaria
    </span>

    <!-- Botón salir (derecha) -->
    <a href="logout.php" class="btn btn-danger position-absolute end-0 me-3">
      Salir
    </a>
  </nav>

  <!-- Contenido principal -->
  <div class="container mt-4">

    <!-- Barra de búsqueda -->
    <form method="get" class="row mb-4">
      <div class="col-md-8">
        <input type="text" name="q" value="<?= htmlspecialchars($busqueda) ?>" class="form-control" placeholder="Buscar libro por título o autor...">
      </div>
      <div class="col-md-4">
        <select class="form-select" name="categoria">
          <option value="0">Filtrar por categoría...</option>
          <?php while ($c = $cats->fetch_assoc()): ?>
            <option value="<?= $c['IdCategoria'] ?>" <?= (isset($_GET['categoria']) && $_GET['categoria'] == $c['IdCategoria']) ? "selected" : "" ?>>
              <?= $c['Nombre'] ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-12 mt-2">
        <button class="btn btn-primary">Buscar</button>
      </div>
    </form>

    <!-- Lista de libros -->
    <div class="row">
      <?php if ($libros->num_rows > 0): ?>
        <?php while ($libro = $libros->fetch_assoc()): ?>
          <div class="col-md-3 mb-3">
            <div class="card h-100 shadow-sm">
              <div class="card-body">
                <div class="d-flex justify-content-center mb-2">
                  <img src="./img/libro1.png" alt="Libro" width="100" height="100">
                </div>
                <h5 class="card-title"><?= htmlspecialchars($libro['Titulo']) ?></h5>
                <p class="card-text"><strong>Autor:</strong> <?= htmlspecialchars($libro['Autor']) ?></p>
                <p class="card-text"><strong>Categoría:</strong> <?= htmlspecialchars($libro['Categoria']) ?></p>
                <a href="./libro.php?id=<?= $libro['IdLibro'] ?>" class="btn btn-primary btn-sm">Ver más</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">No se encontraron libros.</p>
      <?php endif; ?>
    </div>

    <!-- Paginación -->
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item <?= ($pagina <= 1 ? 'disabled' : '') ?>"><a class="page-link" href="?pagina=<?= max(1, $pagina - 1) ?>&q=<?= $busqueda ?>&categoria=<?= isset($_GET['categoria']) ? $_GET['categoria'] : 0 ?>">Anterior</a></li>
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
          <li class="page-item <?= ($i == $pagina ? 'active' : '') ?>"><a class="page-link" href="?pagina=<?= $i ?>&q=<?= $busqueda ?>&categoria=<?= isset($_GET['categoria']) ? $_GET['categoria'] : 0 ?>"><?= $i ?></a></li>
        <?php endfor; ?>
        <li class="page-item <?= ($pagina >= $totalPaginas ? 'disabled' : '') ?>"><a class="page-link" href="?pagina=<?= min($totalPaginas, $pagina + 1) ?>&q=<?= $busqueda ?>&categoria=<?= isset($_GET['categoria']) ? $_GET['categoria'] : 0 ?>">Siguiente</a></li>
      </ul>
    </nav>
  </div>

  <!-- Botón de notificaciones -->
  <a href="notificaciones.php" class="btn btn-warning btn-notify position-fixed">
    🔔
  </a>

</body>

</html>