# Nexo — Tienda Online (MVC en PHP)

## Descripción

Nexo es una aplicación web tipo tienda online construida en PHP utilizando el patrón de arquitectura MVC. Permite la gestión de usuarios, productos, carritos de compra, pedidos, favoritos, reseñas y perfiles de vendedor.

---

## Tecnologías utilizadas

- PHP 8.x
- HTML5 / CSS3 / JavaScript
- Docker
- PostgreSQL

---

## Estructura del proyecto
`/`
- `app/`
	- `controllers/` # Controladores que manejan la lógica de negocio
	- `models/` # Clases de acceso a datos
	- `views/` # Vistas HTML/PHP organizadas por módulos
	- `core/` # Clases del núcleo: router, sesiones, autenticación, etc
- `public/` # Archivos públicos y punto de entrada del sitio
	- `css/`
	- `js/`
	- `images/`
	- `.htaccess`
	- `index.php`
-	`README.md`
-	`.env.example` #Archivo de ejemplo del .env, este cual contiene las credenciales.

> Nota: El directorio `vendor/` ha sido excluido porque contiene dependencias locales de Composer.

---

## Instalación

1. Clonar el repositorio:
```bash
  git clone juan-dev https://github.com/Juan-Ignacio-Silva/nexo.git
```
2. Crear archivo .env en la carpeta raiz del proyecto y pegar el .env.example con crendenciales a gusto.
3. Instalar composer en nuestra maquina
4. Abrir el directorio en una terminal
	- Ejecutar el siguiente comando:
```bash
  composer install
```
	- Luego ejecutar los siguentes comandos:
```bash
  docker compose build
```
	- Terminada la ejecución anterior, ejecutar lo siguiente:
```bash
  docker compose up -d
```
5. Por ultimo entrar al localhost:8080.

---

## Créditos
Proyecto desarrollado para la materia Programación (2025) por:
- Juan Ignacio Silva
- Lautaro Caballero
- Santiago Peraza
- Marcos Rodríguez
