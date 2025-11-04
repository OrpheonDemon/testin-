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
                    <h2>Gestión de Libros</h2>
                    <button id="btnInsertar">Insertar Libro</button>
                </div>

                <!-- Modal -->
                <div id="modalLibro" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2 id="modalTitulo">Insertar Libro</h2>

                        <form method="POST" action="../Backend/CRUDs/librosCRUD.php?accion=guardar" enctype="multipart/form-data">
                            <input type="hidden" name="IdLibro" id="formIdLibro">

                            <label>Titulo:</label>
                            <input type="text" name="Titulo" id="formTitulo" required><br>

                            <label>Autor:</label>
                            <select name="IdAutor" id="formAutor" required>
                                <option value="">Seleccione un Autor</option>
                                <?php
                                require_once '../Backend/conexion.php';
                                $resAutores = $link->query("SELECT IdAutor, Nombre FROM TAutores WHERE Estado = 1");
                                while ($autor = $resAutores->fetch_assoc()) {
                                    echo "<option value='{$autor['IdAutor']}'>{$autor['Nombre']}</option>";
                                }
                                ?>
                            </select><br>

                            <label>Editorial:</label>
                            <select name="IdEditorial" id="formEditorial" required>
                                <option value="">Seleccione una Editorial</option>
                                <?php
                                $resEditoriales = $link->query("SELECT IdEditorial, Nombre FROM TEditoriales WHERE Estado = 1");
                                while ($editorial = $resEditoriales->fetch_assoc()) {
                                    echo "<option value='{$editorial['IdEditorial']}'>{$editorial['Nombre']}</option>";
                                }
                                ?>
                            </select><br>

                            <label>Categoria:</label>
                            <select name="IdCategoria" id="formCategoria" required>
                                <option value="">Seleccione una Categoria</option>
                                <?php
                                $resCategorias = $link->query("SELECT IdCategoria, Nombre FROM TCategorias WHERE Estado = 1");
                                while ($categoria = $resCategorias->fetch_assoc()) {
                                    echo "<option value='{$categoria['IdCategoria']}'>{$categoria['Nombre']}</option>";
                                }
                                ?>
                            </select><br>

                            <label>Tipo de libro:</label>
                            <select name="TipoLibro" id="formTipoLibro" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="Virtual">Virtual</option>
                                <option value="Fisico">Físico</option>
                            </select><br>


                            <div id="divRutaDoc" style="display:none;">
                                <label>Subir Documento PDF:</label>
                                <input type="file" name="RutaDoc" accept=".pdf"><br>
                            </div>

                            <div id="divUbicacionStock" style="display:none;">
                                <label>Ubicación:</label>
                                <select name="IdUbicacion" id="formUbicacion">
                                    <option value="">Seleccione una ubicación</option>
                                    <?php
                                    $resUbicaciones = $link->query("SELECT IdUbicacion, Area, Piso, Bloque, Referencia FROM TUbicaciones WHERE Estado = 1");
                                    while ($ub = $resUbicaciones->fetch_assoc()) {
                                        echo "<option value='{$ub['IdUbicacion']}'>{$ub['Area']} - Piso {$ub['Piso']} - Bloque {$ub['Bloque']}</option>";
                                    }
                                    ?>
                                </select><br>

                                <label>Stock:</label>
                                <input type="number" name="Stock" id="formStockFisico" min="1"><br>
                            </div>


                            <div style="margin-top:15px;">
                                <button type="submit" name="insertar">Guardar</button>
                                <button type="button" id="btnCancelar">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <?php
                    $tipo = $_GET['tipo'] ?? 'Fisico';
                    $textoBoton = $tipo === 'Fisico' ? 'Mostrar Libros Virtuales' : 'Mostrar Libros Físicos';
                ?>
                <button id="btnToggleTipo"><?php echo $textoBoton; ?></button>
                
                <?php

                if ($tipo === 'Fisico') {
                    $res = $link->query("
                                SELECT l.IdLibro, l.Titulo, a.Nombre AS Autor, e.Nombre AS Editorial, 
                                    c.Nombre AS Categoria, u.Area AS Ubicacion, u.Piso, u.Bloque, u.Referencia,
                                    l.Stock, l.Estado, l.FechaCreacion
                                FROM TLibros l
                                LEFT JOIN TAutores a ON l.IdAutor = a.IdAutor
                                LEFT JOIN TEditoriales e ON l.IdEditorial = e.IdEditorial
                                LEFT JOIN TCategorias c ON l.IdCategoria = c.IdCategoria
                                LEFT JOIN TUbicaciones u ON l.IdUbicacion = u.IdUbicacion
                                WHERE l.Estado = 1 AND l.IdUbicacion IS NOT NULL
                            ");
                } else {
                    $res = $link->query("
                                SELECT l.IdLibro, l.Titulo, a.Nombre AS Autor, e.Nombre AS Editorial, 
                                    c.Nombre AS Categoria, l.RutaDoc, l.Estado, l.FechaCreacion
                                FROM TLibros l
                                LEFT JOIN TAutores a ON l.IdAutor = a.IdAutor
                                LEFT JOIN TEditoriales e ON l.IdEditorial = e.IdEditorial
                                LEFT JOIN TCategorias c ON l.IdCategoria = c.IdCategoria
                                WHERE l.Estado = 1 AND l.RutaDoc IS NOT NULL
                            ");
                }
                echo "<table style='width:100%; margin-top:10px;'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Editorial</th>
                            <th>Categoría</th>";
                if ($tipo === 'Fisico') {
                    echo "<th>Ubicación</th><th>Stock</th>";
                } else {
                    echo "<th>RutaDoc</th>";
                }

                echo "<th>Estado</th>
                      <th>FechaCreación</th>
                      <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>";
                $contador=0;
                while ($row = $res->fetch_assoc()) {
                    $estadoText = $row['Estado'] == 1 ? 'Habilitado' : ($row['Estado'] == 2 ? 'Inhabilitado' : 'Borrado');
                    $contador++;
                    echo "<tr>
                    <td>$contador</td>
                    <td>{$row['Titulo']}</td>
                    <td>{$row['Autor']}</td>
                    <td>{$row['Editorial']}</td>
                    <td>{$row['Categoria']}</td>";

                    if ($tipo === 'Fisico') {
                        $ubic = $row['Ubicacion'] . " - Piso {$row['Piso']} - Bloque {$row['Bloque']}";
                        echo "<td>$ubic</td><td>{$row['Stock']}</td>";
                    } else {
                        echo "<td>{$row['RutaDoc']}</td>";
                    }

                    echo "<td>$estadoText</td>
                          <td>{$row['FechaCreacion']}</td>
                          <td>
                            <button class='btnEditar' data-id='{$row['IdLibro']}'>Editar</button>
                            <button class='btnBorrar' data-id='{$row['IdLibro']}'>Borrar</button>
                          </td>
                        </tr>";
                }

                echo "</tbody></table>";
                ?>
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

        //Boton get por tipo
        let tipoActual = '<?php echo $tipo; ?>'; // Inicial desde PHP

        document.getElementById('btnToggleTipo').addEventListener('click', () => {
            // Alternar tipo
            let tipoNuevo = tipoActual === 'Fisico' ? 'Virtual' : 'Fisico';

            window.location.href = "crud_libro.php?tipo=" + tipoNuevo;
        });



        const btnInsertar = document.getElementById('btnInsertar');
        const modal = document.getElementById('modalLibro');
        const spanClose = modal.querySelector('.close');
        const btnCancelar = document.getElementById('btnCancelar');
        const modalTitulo = document.getElementById('modalTitulo');

        const tipoLibroSelect = document.getElementById('formTipoLibro');
        const divRutaDoc = document.getElementById('divRutaDoc');
        const divUbicacionStock = document.getElementById('divUbicacionStock');
        const stockInput = document.getElementById('formStock');

        btnInsertar.addEventListener('click', () => {
            modal.style.display = 'flex';
            modalTitulo.textContent = 'Insertar Libro';
            modal.querySelector('form').reset();
            document.getElementById('formIdLibro').value = '';
        });

        tipoLibroSelect.addEventListener('change', () => {
            if (tipoLibroSelect.value === 'Virtual') {
                divRutaDoc.style.display = 'block';
                divUbicacionStock.style.display = 'none';
                stockInput.value = 1; // stock por default
            } else if (tipoLibroSelect.value === 'Fisico') {
                divRutaDoc.style.display = 'none';
                divUbicacionStock.style.display = 'block';
                stockInput.value = ''; // editable por usuario
            } else {
                divRutaDoc.style.display = 'none';
                divUbicacionStock.style.display = 'none';
                stockInput.value = '';
            }
        });

        spanClose.onclick = () => modal.style.display = 'none';
        btnCancelar.onclick = () => modal.style.display = 'none';
        window.onclick = (e) => {
            if (e.target == modal) modal.style.display = 'none';
        }

        document.querySelectorAll('.btnEditar').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                fetch(`../Backend/CRUDs/librosCRUD.php?accion=get&id=${id}`)
                    .then(res => res.json())
                    .then(data => {
                        modal.style.display = 'flex';
                        modalTitulo.textContent = 'Editar Libro';
                        document.getElementById('formIdLibro').value = data.IdLibro;
                        document.getElementById('formTitulo').value = data.Titulo;
                        document.getElementById('formAutor').value = data.IdAutor;
                        document.getElementById('formEditorial').value = data.IdEditorial;
                        document.getElementById('formCategoria').value = data.IdCategoria;

                        tipoLibroSelect.value = data.TipoLibro;
                        tipoLibroSelect.dispatchEvent(new Event('change'));

                        if (data.TipoLibro === 'Virtual') {
                            if (data.RutaDoc) {
                                divRutaDoc.querySelector('.archivo-actual')?.remove();
                                const a = document.createElement('a');
                                a.href = "../" + data.RutaDoc;
                                a.textContent = "Archivo actual";
                                a.classList.add('archivo-actual');
                                a.target = "_blank";
                                divRutaDoc.appendChild(a);
                            }
                        } else if (data.TipoLibro === 'Fisico') {
                            stockInput.value = data.Stock;
                            document.getElementById('formIdUbicacion').value = data.IdUbicacion;
                        }
                    });
            });
        });


        document.querySelectorAll('.btnBorrar').forEach(btn => {
            btn.addEventListener('click', () => {
                if (confirm("¿Seguro que quieres borrar este libro?")) {
                    const id = btn.getAttribute('data-id');
                    window.location.href = `../Backend/CRUDs/librosCRUD.php?accion=borrar&id=${id}`;
                }
            });
        });
    </script>
</body>

</html>