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

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #F8FAFC;
            min-height: 100vh;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
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
        // Íconos SVG para cada categoría
        const icons = {
            computer: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>`,
            camera: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>`,
            tv: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4l-2 2M17 4l2 2M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"></path></svg>`,
            watch: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
            gaming: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>`,
            mobile: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a1 1 0 001-1V4a1 1 0 00-1-1H8a1 1 0 00-1 1v16a1 1 0 001 1z"></path></svg>`,
            headphones: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>`,
            accessories: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>`
        };

        // Datos de categorías
        const categories = [
            {
                id: 1,
                title: "Computer & Laptop",
                description: "Computadoras, laptops y accesorios",
                icon: icons.computer
            },
            {
                id: 2,
                title: "Camera & Photos",
                description: "Cámaras y equipos fotográficos",
                icon: icons.camera
            },
            {
                id: 3,
                title: "Television",
                description: "Smart TVs y sistemas de audio",
                icon: icons.tv
            },
            {
                id: 4,
                title: "Smartwatches",
                description: "Relojes inteligentes y wearables",
                icon: icons.watch
            },
            {
                id: 5,
                title: "Gaming",
                description: "Consolas, juegos y accesorios",
                icon: icons.gaming
            },
            {
                id: 6,
                title: "Mobile & Tablets",
                description: "Smartphones y tablets",
                icon: icons.mobile
            },
            {
                id: 7,
                title: "Headphones",
                description: "Auriculares y sistemas de sonido",
                icon: icons.headphones
            },
            {
                id: 8,
                title: "Accessories",
                description: "Cables, cargadores y más",
                icon: icons.accessories
            }
        ];

        // Función para renderizar categorías
        function renderCategories() {
            const grid = document.getElementById('categoriesGrid');
            grid.innerHTML = '';

            categories.forEach((category, index) => {
                const categoryElement = document.createElement('div');
                categoryElement.className = 'category-item loading';
                categoryElement.style.animationDelay = `${index * 0.05}s`;
                
                categoryElement.innerHTML = `
                    <div class="category-icon">
                        ${category.icon}
                    </div>
                    <h3 class="category-title">${category.title}</h3>
                    <p class="category-description">${category.description}</p>
                `;

                categoryElement.addEventListener('click', () => {
                    handleCategoryClick(category);
                });

                grid.appendChild(categoryElement);
            });
        }

        // Función para manejar clicks
        function handleCategoryClick(category) {
            console.log(`Categoría seleccionada: ${category.title}`);
            
            // Efecto visual de click
            const element = event.currentTarget;
            element.style.transform = 'translateY(0px) scale(0.98)';
            
            setTimeout(() => {
                element.style.transform = '';
            }, 150);
            
            // Simular navegación
            alert(`Navegando a: ${category.title}`);
        }

        // API simulada para gestión dinámica
        const CategoryAPI = {
            getAll: () => Promise.resolve(categories),
            
            add: (newCategory) => {
                categories.push({
                    id: categories.length + 1,
                    ...newCategory
                });
                renderCategories();
                return Promise.resolve(newCategory);
            },
            
            update: (id, updates) => {
                const index = categories.findIndex(cat => cat.id === id);
                if (index > -1) {
                    categories[index] = { ...categories[index], ...updates };
                    renderCategories();
                    return Promise.resolve(categories[index]);
                }
                return Promise.reject('Categoría no encontrada');
            },
            
            remove: (id) => {
                const index = categories.findIndex(cat => cat.id === id);
                if (index > -1) {
                    const removed = categories.splice(index, 1)[0];
                    renderCategories();
                    return Promise.resolve(removed);
                }
                return Promise.reject('Categoría no encontrada');
            }
        };

        // Inicializar
        document.addEventListener('DOMContentLoaded', () => {
            renderCategories();
        });

        // Hacer la API disponible globalmente para testing
        window.CategoryAPI = CategoryAPI;
    </script>
</body>
</html>