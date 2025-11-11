<link rel="stylesheet" href="css/seccion-categorias.css">

<div class="container">
    <div class="max-width">
        <!-- Header -->
        <div class="header">
            <p>Categorías</p>
            <h2>Categorías <span class="highlight">Destacadas</span></h2>
        </div>

        <!-- Categories -->
        <div class="categories">
            <div class="categories-desktop" id="categorias-container">
                <!-- Las categorías se cargarán aquí -->
            </div>
        </div>
    </div>
</div>

<script>
    fetch('<?= BASE_URL ?>/categoria/obtenerTopCategorias')
        .then(res => res.json())
        .then(data => {
            const contenedor = document.getElementById('categorias-container');
            contenedor.innerHTML = ''; // limpia el contenido inicial

            if (data.status === 'success' && data.categorias.length > 0) {
                data.categorias.forEach(cat => {
                    const div = document.createElement('div');
                    div.classList.add('category');
                    div.innerHTML = `
                        <a href="<? BASE_URL ?>productos/categoria/${cat.id_categoria}">
                            <div class="category-icon">
                                <img src="${cat.icono}" alt="${cat.id_categoria}">
                            </div>
                            <h3>${cat.categoria}</h3>
                            <p>Promedio: ${cat.promedio}</p>
                        </a>
                    `;
                    contenedor.appendChild(div);
                });
            } else {
                contenedor.innerHTML = '<p>No hay categorías destacadas.</p>';
            }
        })
        .catch(err => {
            console.error(err);
            document.getElementById('categorias-container').innerHTML =
                '<p>Error al cargar las categorías.</p>';
        });
</script>