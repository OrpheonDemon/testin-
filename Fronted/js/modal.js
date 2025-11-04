const modal = document.getElementById('modalUsuario');
const spanClose = document.querySelector('.close');
const modalNombre = document.getElementById('modalNombre');
const modalCorreo = document.getElementById('modalCorreo');
const modalCelular = document.getElementById('modalCelular');
const modalGrado = document.getElementById('modalGrado');
const modalVerificaciones = document.getElementById('modalVerificaciones');
const modalIdUsuario = document.getElementById('modalIdUsuario');

document.querySelectorAll('.btn-abrir').forEach(button => {
    button.addEventListener('click', () => {
        const idUsuario = button.getAttribute('data-id');
        modalIdUsuario.value = idUsuario;

        fetch(`../Backend/getUsuarioDatos.php?id=${idUsuario}`)
            .then(res => res.json())
            .then(data => {
                modalNombre.textContent = data.nombreCompleto;
                modalCorreo.textContent = data.correo;
                modalCelular.textContent = data.celular;
                modalGrado.textContent = data.grado;

                modalVerificaciones.innerHTML = "<h3>Documentos de verificación</h3>";
                const contenedorFlex = document.createElement('div');
                contenedorFlex.classList.add('verificaciones-flex');
                
                const titulos = ["CI / Carnet de Identidad", "Documento de Verificación"];

                data.archivos.forEach((file, index) => {
                    let item = document.createElement('div');
                    item.classList.add('verificacion-item');

                    let titulo = document.createElement('h4');
                    titulo.textContent = titulos[index] || `Documento ${index+1}`;
                    item.appendChild(titulo);

                    if(file.tipo === 'imagen') {
                        let img = document.createElement('img');
                        img.src = file.ruta;
                        img.style.maxWidth = "100%";
                        img.style.height = "auto";
                        item.appendChild(img);
                    } else if(file.tipo === 'pdf') {
                        let iframe = document.createElement('iframe');
                        iframe.src = file.ruta;
                        iframe.width = "100%";
                        iframe.height = "300";
                        item.appendChild(iframe);
                    }

                    contenedorFlex.appendChild(item);
                });

                modalVerificaciones.appendChild(contenedorFlex);
                modal.style.display = 'flex';
            })
            .catch(err => console.error(err));
    });
});

spanClose.onclick = () => modal.style.display = 'none';
window.onclick = (e) => { if (e.target == modal) modal.style.display = 'none'; };
