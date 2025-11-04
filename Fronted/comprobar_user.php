<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Fronted/css/style_admin.css">
    <link rel="stylesheet" href="../Fronted/css/style_admin2.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

</head>

<body>
    <label>
        <input type="checkbox" class="menu-toggle">

        <div class="toggle">
            <span class="top_line common"></span>
            <span class="middle_line common"></span>
            <span class="bottom_line common"></span>
        </div>

        <div class="sidebar">
            <h1>MENU</h1>
            <ul>
                <li><a href="./index_admin.php"><span class="material-symbols-rounded">home</span>Inicio</a></li>
                <li class="dropdown-container">
                    <a href="#" class="dropdown-toggle">
                        <span class="material-symbols-rounded">local_hospital</span>
                        <span>Hospital</span>
                        <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="./comprobar_user.php">Comprobaciones</a></li>
                        <li><a href="./crud_usuario.php">Usuarios</a></li>
                        <li><a href="./crud_rol.php">Roles</a></li>
                        <li><a href="./crud_grado.php">Grados</a></li>
                    </ul>
                </li>

                <li class="dropdown-container">
                    <a href="#" class="dropdown-toggle">
                        <span class="material-symbols-rounded">book_ribbon</span>
                        <span>Biblioteca</span>
                        <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="./crud_categoria.php">Categorías</a></li>
                        <li><a href="./crud_autor.php">Autores</a></li>
                        <li><a href="./crud_editorial.php">Editoriales</a></li>
                        <li><a href="./crud_libro.php">Libros</a></li>
                    </ul>
                </li>
                <li class="dropdown-container">
                    <a href="#" class="dropdown-toggle">
                        <span class="material-symbols-rounded">assistant_navigation</span>
                        <span>Ubicación</span>
                        <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="./libros_fisicos.php">Ubi. Libro Fisico</a></li>
                        <li><a href="./crud_ubicacion.php">Reg. Ubicaciones</a></li>
                    </ul>
                </li>
                <li class="dropdown-container">
                    <a href="" class="dropdown-toggle">
                        <span class="material-symbols-rounded">event</span>
                        <span>Prestamos</span>
                        <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="./crud_historial.php">Historial Prestamos</a></li>
                        <li><a href="./crud_sancion.php">Sanciones</a></li>
                    </ul>
                </li>
                <li><a href="./crud_notificacion.php"><span class="material-symbols-rounded">notifications</span>Notificaciones</a></li>
                <li><a href="./logout.php"><span class="material-symbols-rounded">logout</span>Cerrar Sesion</a></li>                
            </ul>
        </div>
    </label>

    <div class="main">
        <div class="topbar">
            <div class="left">
                <h2>Panel del Administrador/Personal Autorizado</h2>
            </div>
            <div class="right">
                <span class="date" id="fecha"></span>
            </div>
        </div>
        <div class="contenido">
            <div class="aprobaciones">
                <?php
                require_once '../Backend/conexion.php';

                $sql = "SELECT IdUsuario, Nombres, ApellidoPat, ApellidoMat, Correo, Celular, IdGrado 
                            FROM TUsuarios 
                            WHERE Estado = 0";

                $result = mysqli_query($link, $sql);

                if (!$result) {
                    echo "<p>Error al traer usuarios: " . mysqli_error($link) . "</p>";
                } else if (mysqli_num_rows($result) === 0) {
                    echo "<p>No hay usuarios pendientes por habilitar.</p>";
                } else {
                    echo "<div class='grid-usuarios'>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $nombreCompleto = $row['Nombres'] . ' ' . $row['ApellidoPat'] . ' ' . $row['ApellidoMat'];
                        echo "<div class='card-usuario'>";
                        echo "<h3>$nombreCompleto</h3>";
                        echo "<p><strong>Correo:</strong> " . $row['Correo'] . "</p>";
                        echo "<p><strong>Celular:</strong> " . $row['Celular'] . "</p>";
                        echo "<button class='btn-abrir' data-id='" . $row['IdUsuario'] . "'>Abrir</button>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modalUsuario" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalNombre"></h2>
            <p><strong>Correo:</strong> <span id="modalCorreo"></span></p>
            <p><strong>Celular:</strong> <span id="modalCelular"></span></p>
            <p><strong>Grado:</strong> <span id="modalGrado"></span></p>
            <div id="modalVerificaciones">
                <h3>Documentos de verificación</h3>
                <div class="verificaciones-flex">
                </div>
            </div>
            <form method="POST" action="../Backend/actualizarEstado.php">
                <div class="modal-roles">
                    <?php
                    require_once '../Backend/conexion.php';
                    $result = $link->query("SELECT IdRol, Nombre FROM TRoles WHERE 1");
                    ?>

                    <select name="rol_usuario">
                        <option value="">Asigne un rol</option>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <option value="<?php echo $row['IdRol']; ?>"><?php echo htmlspecialchars($row['Nombre']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="modal-buttons">
                    <input type="hidden" name="IdUsuario" id="modalIdUsuario">
                    <button type="submit" name="aprobar">Aprobar</button>
                    <button type="submit" name="cancelar">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../Fronted/js/modal.js"></script>
    <script>
        const fecha = new Date().toLocaleDateString('es-BO', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('fecha').innerText = fecha.charAt(0).toUpperCase() + fecha.slice(1);

        const formModal = document.querySelector('#modalUsuario form');
        const botonAprobar = formModal.querySelector('button[name="aprobar"]');

        botonAprobar.addEventListener('click', function(e) {
            const rolSelect = formModal.querySelector('select[name="rol_usuario"]');
            if (!rolSelect.value) {
                e.preventDefault();
                alert("Debe seleccionar un rol antes de aprobar al usuario.");
            }
        });

        function setupDropdowns() {
            const toggleDropdown = (dropdown, menu, isOpen) => {
                dropdown.classList.toggle("open", isOpen);
                menu.style.height = isOpen ? `${menu.scrollHeight}px` : 0;
            };

            const closeAllDropdowns = () => {
                document.querySelectorAll(".dropdown-container.open").forEach(drop => {
                    toggleDropdown(drop, drop.querySelector(".dropdown-menu"), false);
                });
            };

            document.querySelectorAll(".dropdown-toggle").forEach(toggle => {
                toggle.addEventListener("click", e => {
                    e.preventDefault();
                    const dropdown = toggle.closest(".dropdown-container");
                    const menu = dropdown.querySelector(".dropdown-menu");
                    const isOpen = dropdown.classList.contains("open");
                    closeAllDropdowns();
                    toggleDropdown(dropdown, menu, !isOpen);
                });
            });

            document.addEventListener("click", e => {
                if (!e.target.closest(".dropdown-container")) {
                    closeAllDropdowns();
                }
            });
        }

        document.addEventListener("DOMContentLoaded", setupDropdowns);
    </script>
</body>

</html>