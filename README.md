# Nombre del Proyecto MVC

## Descripción

Este proyecto es una aplicación web desarrollada siguiendo el patrón MVC (Modelo-Vista-Controlador). Esta misma es una tienda online de tipo eCommerce (Mercado Libre).

## Tecnologías utilizadas

- PHP 8.x  
- MySQL 
- HTML5, CSS3, JavaScript  
- Servidor Apache (XAMPP)

## Estructura del proyecto

El proyecto está organizado siguiendo el patrón MVC:

- `/app`  
  Carpeta principal con:  
  - `/models` (modelos, acceso a datos)  
  - `/views` (plantillas o vistas)  
  - `/controllers` (controladores que gestionan la lógica y peticiones)
  - `/config` Configuración de base de datos y parámetros globales.
- `/public`  
  Archivos públicos: CSS, JS, imágenes.
- `index.php` Archivo principal.

## Instalación y configuración

1. Clonar el repositorio:
`git clone https://github.com/tuusuario/tu-proyecto.git`

2. Copiar el proyecto a la carpeta raíz de tu servidor local (ej: `htdocs` en XAMPP).

3. Crear la base de datos en MySQL (usar MySQL WorkBench O phpMyAdmin).

4. Importar el archivo `nexodb.sql` que contiene la estructura y datos iniciales.

5. Configurar los datos de conexión a la base en `/config/conexion.php`.

6. Iniciar el servidor Apache y MySQL desde XAMPP.

7. Abrir en el navegador:

## Estructura MVC explicada (breve)

- **Modelo**: Gestiona datos y acceso a la base (consultas SQL).  
- **Vista**: Presenta la información al usuario (HTML + CSS).  
- **Controlador**: Recibe las peticiones, procesa la lógica y llama a modelo y vista.

## Créditos / Autor

- Autores: Juan Ignacio Silva, Lautaro Caballero, Santiago Peraza y Marcos Rodriguez. 
- Materia: Programación
- Fecha: 21/06/2025 (Creación del repo)

## Licencia (opcional)

Este proyecto está bajo licencia MIT / GPL / etc.
