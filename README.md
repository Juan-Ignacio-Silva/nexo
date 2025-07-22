# Nexo — Tienda Online (MVC en PHP)

## Descripción

Nexo es una aplicación web tipo tienda online construida en PHP utilizando el patrón de arquitectura MVC. Permite la gestión de usuarios, productos, carritos de compra, pedidos, favoritos, reseñas y perfiles de vendedor.

---

## Tecnologías utilizadas

- PHP 8.x
- MySQL
- HTML5 / CSS3 / JavaScript
- Apache (XAMPP recomendado)

---

## Estructura del proyecto
`/`
- `app/`
	- `config/` # Configuración general y de base de datos
	- `controllers/` # Controladores que manejan la lógica de negocio
	- `db/` # Scripts de la base de datos
	- `models/` # Clases de acceso a datos
	- `views/` # Vistas HTML/PHP organizadas por módulos
		- `admin/`
		- `home/`
		- `templates/` # Fragmentos reutilizables (header, footer, etc.)
		- `usuario/`
	- `core/` # Clases del núcleo: router, sesiones, autenticación, etc
- `public/` # Archivos públicos y punto de entrada del sitio
	- `css/`
	- `js/`
	- `images/`
	- `index.php`
-	`README.md`

> Nota: El directorio `vendor/` ha sido excluido porque contiene dependencias locales de Composer.

---

## Base de datos

La base de datos `nexodb` incluye las siguientes tablas:

- `usuarios`: contiene la información del usuario y su dirección.
- `producto`: productos disponibles en la tienda.
- `pedido`: pedidos realizados por usuarios, relacionados con productos.
- `carrito`: carritos asociados a usuarios.
- `carrito_items`: ítems dentro de un carrito (producto + cantidad).
- `favoritos`: productos marcados como favoritos por los usuarios.
- `reseña`: comentarios y calificaciones de productos por los usuarios.
- `vendedor`: información adicional de usuarios con perfil vendedor.

La estructura permite relaciones como:
- Un usuario puede tener muchos pedidos, carritos, reseñas y productos favoritos.
- Cada producto puede estar en múltiples carritos y pedidos.
- Un vendedor está asociado a una  cuenta de usuario.

> Ver archivo `app/db/` para el script de creación completo.

---

## Instalación

1. Clonar el repositorio:
```bash
  git clone juan-dev https://github.com/Juan-Ignacio-Silva/nexo.git
```
2. Copiar el proyecto dentro de la carpeta htdocs/ (si usás XAMPP).

3. Importar la base de datos:
	- Crear una base de datos llamada nexodb.
	- Importar el script SQL disponible en app/db/.

4. Configurar las credenciales de conexión en:
```bash
   app/config/database.php
```
5. Acceder desde el navegador:
```bash
   http://localhost/nexo/public
```
---
##Módulos implementados
- Autenticación de usuarios
- Gestión de productos
- Carrito de compras
- Pedidos
- Favoritos
- Sistema de reseñas
- Perfil de vendedor

---

##Créditos
Proyecto desarrollado para la materia Programación (2025) por:
- Juan Ignacio Silva
- Lautaro Caballero
- Santiago Peraza
- Marcos Rodríguez
