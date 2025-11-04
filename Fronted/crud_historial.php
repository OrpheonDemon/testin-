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
                    <h2>Gestión de Historial de Prestamos</h2>
                    <button id="btnInsertar">Insertar Historials</button>
                </div>

                <!-- Modal Insetar y Editar Historial de Prestamos-->
                <div id="modalHistorial" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2 id="modalTitulo">Insertar Historial</h2>

                        <form method="POST" action="../Backend/CRUDs/historialesCRUD.php?accion=guardar">
                            <input type="hidden" name="IdHistorial" id="formIdHistorial">

                            <select name="IdLibro" id="formLibro" required>
                                <option value="">Seleccione un Libro</option>
                                <?php
                                require_once '../Backend/conexion.php';
                                $resLibros = $link->query("SELECT IdLibro, Titulo FROM TLibros WHERE Estado = 1 AND IdUbicacion IS NOT NULL AND Stock > 0 ");
                                while ($libro = $resLibros->fetch_assoc()) {
                                    echo "<option value='{$libro['IdLibro']}'>{$libro['Titulo']}</option>";
                                }
                                ?>
                            </select><br>

                            <select name="IdUsuario" id="formUsuario" required>
                                <option value="">Seleccione un Usuario</option>
                                <?php
                                $resUsuarios = $link->query("SELECT IdUsuario, Nombres, ApellidoPat, ApellidoMat FROM TUsuarios WHERE Estado = 1");
                                $nombreCompleto = $row['Nombres'] . ' ' . $row['ApellidoPat'] . ' ' . $row['ApellidoMat'];
                                while ($usuario = $resUsuarios->fetch_assoc()) {
                                    $nombreCompleto = $usuario['Nombres'] . ' ' . $usuario['ApellidoPat'] . ' ' . ($usuario['ApellidoMat'] ?? '');
                                    echo "<option value='{$usuario['IdUsuario']}'>$nombreCompleto</option>";
                                }
                                ?>
                            </select><br>

                            <div style="margin-top:15px;">
                                <button type="submit" name="insertar">Guardar</button>
                                <button type="button" id="btnCancelar">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Sancionar -->
                <div id="modalSancion" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2 id="modalTituloSancion">Sancionar Prestamo</h2>

                        <form method="POST" action="../Backend/CRUDs/sancionesCRUD.php?accion=guardar">
                            <input type="hidden" name="IdHistorial" id="formIdHistorialSancion">

                            <label>Tipo de sanción:</label>
                            <input type="text" name="TipoSancion" required><br>

                            <label>Motivo:</label>
                            <textarea name="Motivo" required></textarea><br>

                            <label>Fecha inicio:</label>
                            <input type="date" name="FechaInicio" required><br>

                            <label>Fecha fin:</label>
                            <input type="date" name="FechaFin" required><br>

                            <div style="margin-top:15px;">
                                <button type="submit">Guardar Sanción</button>
                                <button type="button" id="btnCancelarSancion">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Ubicación -->
                <div id="modalUbicacion" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="closeUbicacion">&times;</span>
                        <div class="ubicacion-flex">
                            <div class="ubicacion-info">
                                <h3>Ubicación del Libro</h3>
                                <p id="textoUbicacion"></p>
                            </div>

                            <div class="ubicacion-imagen">
                                <img id="imagenPiso" src="" alt="Mapa del Piso">
                            </div>
                        </div>
                    </div>
                </div>

                <table style="width:100%; margin-top:10px;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Libro</th>
                            <th>Nombre completo</th>
                            <th>Fecha Reserva</th>
                            <th>Fecha Prestamo</th>
                            <th>Fecha Devolucion</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = $link->query("
                        SELECT hp.IdHistorial, l.Titulo as Libro, l.IdUbicacion, 
                            CONCAT(u.Nombres, ' ', u.ApellidoPat, ' ', COALESCE(u.ApellidoMat, '')) as Usuario,
                            hp.FechaReserva, hp.FechaPrestamo, hp.FechaDevolucion, hp.Estado, hp.Observaciones,
                            tU.Area, tU.Piso, tU.Bloque, tU.Referencia
                        FROM THistorialPrestamos hp
                        LEFT JOIN TLibros l ON hp.IdLibro = l.IdLibro
                        LEFT JOIN TUsuarios u ON hp.IdUsuario = u.IdUsuario
                        LEFT JOIN TUbicaciones tU ON l.IdUbicacion = tU.IdUbicacion
                        WHERE hp.Estado != 4;
                        ");
                        $contador = 0;
                        while ($row = $res->fetch_assoc()) {
                            switch ($row['Estado']) {
                                case 0:
                                    $estadoText = 'Reserva';
                                    break;
                                case 1:
                                    $estadoText = 'Préstamo Activo';
                                    break;
                                case 2:
                                    $estadoText = 'Devuelto';
                                    break;
                                case 3:
                                    $estadoText = 'Sancionado';
                                    break;
                                default:
                                    $estadoText = 'Desconocido';
                            }
                            $contador++;
                            echo "<tr>
                        <td>$contador</td>
                        <td>{$row['Libro']}</td>
                        <td>{$row['Usuario']}</td>
                        <td>{$row['FechaReserva']}</td>
                        <td>{$row['FechaPrestamo']}</td>
                        <td>{$row['FechaDevolucion']}</td>
                        <td>$estadoText</td>
                        <td>{$row['Observaciones']}</td>
                        <td>";
                            if ($row['Estado'] == 0) {
                                echo "<button class='btnAceptarPrestamo' data-id='{$row['IdHistorial']}'>Aceptar Préstamo</button> ";
                                echo "<button class='btnVerUbicacion' 
                                        data-area='{$row['Area']}' 
                                        data-piso='{$row['Piso']}' 
                                        data-bloque='{$row['Bloque']}' 
                                        data-referencia='{$row['Referencia']}'>Ver Ubicación</button>";
                            } elseif ($row['Estado'] == 1) {
                                echo "<button class='btnMarcarDevuelto' data-id='{$row['IdHistorial']}'>Marcar Devuelto</button> ";
                                echo "<button class='btnSancionar' data-id='{$row['IdHistorial']}'>Sancionar</button>";
                            } elseif ($row['Estado'] == 2) {
                                echo "<button class='btnSancionar' data-id='{$row['IdHistorial']}'>Sancionar</button>";
                            } elseif ($row['Estado'] == 3) {
                                echo "<span>Sancionado</span>";
                            }

                            echo " <button class='btnEditar' data-id='{$row['IdHistorial']}'>Editar</button>";
                            echo " <button class='btnBorrar' data-id='{$row['IdHistorial']}'>Borrar</button>";
                            echo "</td>
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

        //Modal para Insertar y Editar Historial de Prestamos
        const btnInsertar = document.getElementById('btnInsertar');
        const modal = document.getElementById('modalHistorial');
        const spanClose = modal.querySelector('.close');
        const btnCancelar = document.getElementById('btnCancelar');
        const modalTitulo = document.getElementById('modalTitulo');

        btnInsertar.addEventListener('click', () => {
            modal.style.display = 'flex';
            modalTitulo.textContent = 'Insertar Historial';
            modal.querySelector('form').reset();
            document.getElementById('formIdHistorial').value = '';
        });

        spanClose.onclick = () => modal.style.display = 'none';
        btnCancelar.onclick = () => modal.style.display = 'none';
        window.onclick = (e) => {
            if (e.target == modal) modal.style.display = 'none';
        }

        document.querySelectorAll('.btnEditar').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                fetch(`../Backend/CRUDs/historialesCRUD.php?accion=get&id=${id}`)
                    .then(res => res.json())
                    .then(data => {
                        modal.style.display = 'flex';
                        modalTitulo.textContent = 'Editar Historial';
                        document.getElementById('formIdHistorial').value = data.IdHistorial;
                        document.getElementById('formLibro').value = data.IdLibro;
                        document.getElementById('formUsuario').value = data.IdUsuario;
                    });
            });
        });
        //

        // Modal de Insertar Sanciones
        const modalSancion = document.getElementById('modalSancion');
        const spanCloseSancion = modalSancion.querySelector('.close');
        const btnCancelarSancion = document.getElementById('btnCancelarSancion');

        document.querySelectorAll('.btnSancionar').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                modalSancion.style.display = 'flex';
                document.getElementById('formIdHistorialSancion').value = id;
            });
        });

        spanCloseSancion.onclick = () => modalSancion.style.display = 'none';
        btnCancelarSancion.onclick = () => modalSancion.style.display = 'none';
        window.onclick = (e) => {
            if (e.target == modalSancion) modalSancion.style.display = 'none';
        }

        //

        //Modal Ubicaciones
        const modalUbicacion = document.getElementById('modalUbicacion');
        const closeUbicacion = modalUbicacion.querySelector('.closeUbicacion');
        const textoUbicacion = document.getElementById('textoUbicacion');
        const imagenPiso = document.getElementById('imagenPiso');

        document.querySelectorAll('.btnVerUbicacion').forEach(btn => {
            btn.addEventListener('click', () => {
                const area = btn.dataset.area;
                const piso = btn.dataset.piso;
                const bloque = btn.dataset.bloque;
                const referencia = btn.dataset.referencia;

                textoUbicacion.textContent = `Área: ${area}, Piso: ${piso}, Bloque: ${bloque}, Referencia: ${referencia}`;
                // Puedes mapear imágenes según piso
                if (piso >= 1 && piso <= 4) {
                    imagenPiso.src = `../Fronted/img/img${piso}.png`;
                } else {
                    imagenPiso.src = ''; // si no hay imagen, se deja vacío
                }

                modalUbicacion.style.display = 'flex';
            });
        });

        closeUbicacion.onclick = () => modalUbicacion.style.display = 'none';
        window.onclick = e => {
            if (e.target == modalUbicacion) modalUbicacion.style.display = 'none';
        }

        //

        document.querySelectorAll('.btnBorrar').forEach(btn => {
            btn.addEventListener('click', () => {
                if (confirm("¿Seguro que quieres borrar este historial?")) {
                    const id = btn.getAttribute('data-id');
                    window.location.href = `../Backend/CRUDs/historialesCRUD.php?accion=borrar&id=${id}`;
                }
            });
        });

        document.querySelectorAll('.btnAceptarPrestamo').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                if (confirm("¿Desea aceptar el préstamo de este libro?")) {
                    window.location.href = `../Backend/CRUDs/historialesCRUD.php?accion=aceptar&id=${id}`;
                }
            });
        });

        document.querySelectorAll('.btnMarcarDevuelto').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                if (confirm("¿Desea marcar este préstamo como devuelto?")) {
                    window.location.href = `../Backend/CRUDs/historialesCRUD.php?accion=devuelto&id=${id}`;
                }
            });
        });
    </script>
</body>

</html>