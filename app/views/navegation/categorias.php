<?php

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
            background-color: #E1E9EF;

        }

        .header {
            margin-bottom: 48px;
        }

        .header h1 {
            color: #1E293B;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }

        .header p {
            color: #64748B;
            font-size: 1rem;
            font-weight: 400;
        }

        .categories-container {
            background: white;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-collapse: collapse;
        }

        .category-item {
            padding: 32px 24px;
            border-right: 1px solid #E2E8F0;
            border-bottom: 1px solid #E2E8F0;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background: white;
        }

        .category-item:nth-child(4n) {
            border-right: none;
        }

        .category-item:nth-last-child(-n+4) {
            border-bottom: none;
        }

        .category-item:hover {
            background-color: #F8FAFC;
            transform: translateY(-1px);
        }

        .category-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent-color);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .category-item:hover::before {
            transform: scaleX(1);
        }

        .category-icon {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            background: var(--bg-color);
            transition: all 0.2s ease;
        }

        .category-icon svg {
            width: 28px;
            height: 28px;
            color: var(--icon-color);
        }

        .category-item:hover .category-icon {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px var(--accent-color);
        }

        .category-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1E293B;
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .category-description {
            font-size: 0.875rem;
            color: #64748B;
            line-height: 1.4;
        }

        /* Colores para cada categoría usando la paleta proporcionada */
        .category-item:nth-child(1) {
            --accent-color: #01203A;
            --bg-color: #EEF8FF;
            --icon-color: #01203A;
        }

        .category-item:nth-child(2) {
            --accent-color: #14334C;
            --bg-color: #F1F5F9;
            --icon-color: #14334C;
        }

        .category-item:nth-child(3) {
            --accent-color: #024678;
            --bg-color: #DBEAFE;
            --icon-color: #024678;
        }

        .category-item:nth-child(4) {
            --accent-color: #02678E;
            --bg-color: #E0F2FE;
            --icon-color: #02678E;
        }

        .category-item:nth-child(5) {
            --accent-color: #0F172A;
            --bg-color: #F1F5F9;
            --icon-color: #0F172A;
        }

        .category-item:nth-child(6) {
            --accent-color: #475569;
            --bg-color: #F8FAFC;
            --icon-color: #475569;
        }

        .category-item:nth-child(7) {
            --accent-color: #1E40AF;
            --bg-color: #DBEAFE;
            --icon-color: #1E40AF;
        }

        .category-item:nth-child(8) {
            --accent-color: #1E3A8A;
            --bg-color: #EFF6FF;
            --icon-color: #1E3A8A;
        }

        @media (max-width: 1024px) {
            .categories-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .category-item:nth-child(3n) {
                border-right: none;
            }

            .category-item:nth-child(4n) {
                border-right: 1px solid #E2E8F0;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px 16px;
            }

            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .category-item {
                padding: 24px 16px;
            }

            .category-item:nth-child(3n) {
                border-right: 1px solid #E2E8F0;
            }

            .category-item:nth-child(2n) {
                border-right: none;
            }

            .category-item:nth-child(4n) {
                border-right: none;
            }

            .header h1 {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 480px) {
            .categories-grid {
                grid-template-columns: 1fr;
            }

            .category-item {
                border-right: none !important;
            }
        }

        .loading {
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="categories-container">
            <div class="categories-grid" id="categoriesGrid">
                <!-- Las categorías se cargarán dinámicamente aquí -->
            </div>
        </div>
    </div>

    <script>
        function obtenerCategorias() {
            fetch('<?= BASE_URL ?>/categoria/obtenerCategorias')
                .then(res => res.json())
                .then(data => {
                    const contenedorCat = document.getElementById('categoriesGrid');
                    // contenedorCat.innerHTML = '';

                    if (data.status === 'success') {
                        data.categorias.forEach(cat => {
                            contenedorCat.innerHTML += `
                            <div class="category-item" data-id="${cat.id}">
                                <img src="${cat.icono_url}" class="category-icon">
                                <h3 class="category-title">${cat.nombre}</h3>
                            </div>
                        `;
                            // Si quiero el icono
                            // ${cat.icono_url || 'https://via.placeholder.com/80'}" alt="${cat.nombre}
                        });
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            obtenerCategorias();
        })
    </script>
</body>

</html>