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
    <link rel="stylesheet" href="../Fronted/css/style_crud.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- Paginacion y filtrados -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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
            <div class="tabla-crud">
                <div class="tabla-header">
                    <h2>Gestión de Categorias</h2>
                    <button id="btnInsertar">Insertar Categoria</button>
                </div>

                <!-- Modal -->
                <div id="modalCategoria" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2 id="modalTitulo">Insertar Categoria</h2>

                        <form method="POST" action="../Backend/CRUDs/categoriasCRUD.php?accion=guardar">
                            <input type="hidden" name="IdCategoria" id="formIdCategoria">

                            <label>Nombre:</label>
                            <input type="text" name="Nombre" id="formNombre" required><br>

                            <label>Descripcion:</label><br>
                            <textarea name="Descripcion" id="formDescripcion" cols="40" rows="5"></textarea>

                            <div style="margin-top:15px;">
                                <button type="submit" name="insertar">Guardar</button>
                                <button type="button" id="btnCancelar">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <table style="width:100%; margin-top:10px;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Fecha Creacion</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once '../Backend/conexion.php';
                        $res = $link->query("
                            SELECT IdCategoria, Nombre, Descripcion, Estado, FechaCreacion
                            FROM TCategorias
                            WHERE Estado = 1
                        ");
                        $contador=0;
                        while ($row = $res->fetch_assoc()) {
                            $estadoText = $row['Estado'] == 1 ? 'Habilitado' : ($row['Estado'] == 0 ? 'Inhabilitado' : 'Borrado');
                            $contador++;
                            echo "<tr>
                        <td>$contador</td>
                        <td>{$row['Nombre']}</td>
                        <td>{$row['Descripcion']}</td>
                        <td>$estadoText</td>
                        <td>{$row['FechaCreacion']}</td>
                        <td>
                            <button class='btnEditar' data-id='{$row['IdCategoria']}'>Editar</button>
                            <button class='btnBorrar' data-id='{$row['IdCategoria']}'>Borrar</button>
                        </td>
                    </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('table').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50],
                "scrollX": true,
                "scrollY": false,    
                "fixedHeader": false,
                "language": {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });
    </script>
    
    <script>
        const fecha = new Date().toLocaleDateString('es-BO', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('fecha').innerText = fecha.charAt(0).toUpperCase() + fecha.slice(1);

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

        const btnInsertar = document.getElementById('btnInsertar');
        const modal = document.getElementById('modalCategoria');
        const spanClose = modal.querySelector('.close');
        const btnCancelar = document.getElementById('btnCancelar');
        const modalTitulo = document.getElementById('modalTitulo');

        btnInsertar.addEventListener('click', () => {
            modal.style.display = 'flex';
            modalTitulo.textContent = 'Insertar Categoria';
            modal.querySelector('form').reset();
            document.getElementById('formIdCategoria').value = '';
        });

        spanClose.onclick = () => modal.style.display = 'none';
        btnCancelar.onclick = () => modal.style.display = 'none';
        window.onclick = (e) => {
            if (e.target == modal) modal.style.display = 'none';
        }

        document.querySelectorAll('.btnEditar').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                fetch(`../Backend/CRUDs/categoriasCRUD.php?accion=get&id=${id}`)
                    .then(res => res.json())
                    .then(data => {
                        modal.style.display = 'flex';
                        modalTitulo.textContent = 'Editar Categoria';
                        document.getElementById('formIdCategoria').value = data.IdCategoria;
                        document.getElementById('formNombre').value = data.Nombre;
                        document.getElementById('formDescripcion').value = data.Descripcion;
                    });
            });
        });


        document.querySelectorAll('.btnBorrar').forEach(btn => {
            btn.addEventListener('click', () => {
                if (confirm("¿Seguro que quieres borrar esta Categoria?")) {
                    const id = btn.getAttribute('data-id');
                    window.location.href = `../Backend/CRUDs/categoriasCRUD.php?accion=borrar&id=${id}`;
                }
            });
        });
    </script>
</body>

</html>