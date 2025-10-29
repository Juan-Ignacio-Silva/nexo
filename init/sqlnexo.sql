-- Eliminar tablas si existen
DROP TABLE IF EXISTS resena CASCADE;
DROP TABLE IF EXISTS favoritos CASCADE;
DROP TABLE IF EXISTS carrito_items CASCADE;
DROP TABLE IF EXISTS pedido_items CASCADE;
DROP TABLE IF EXISTS carrito CASCADE;
DROP TABLE IF EXISTS pedido CASCADE;
DROP TABLE IF EXISTS vendedor CASCADE;
DROP TABLE IF EXISTS producto_imagen CASCADE;
DROP TABLE IF EXISTS producto CASCADE;
DROP TABLE IF EXISTS direccion CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;
DROP TABLE IF EXISTS pago CASCADE;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id_usuarios VARCHAR(36) NOT NULL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(256) NOT NULL,
    telefono VARCHAR(20),
    role VARCHAR(20) NOT NULL DEFAULT 'usuario',
    tipo_login VARCHAR(20) NOT NULL DEFAULT 'email/password',
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de vendedores
CREATE TABLE vendedor (
    id_vendedor VARCHAR(36) NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios),
    nombre_empresa VARCHAR(100) NOT NULL,
    rut_empresa VARCHAR(20),
    descripcion TEXT,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado_aprobacion VARCHAR(20) DEFAULT 'pendiente' CHECK (estado_aprobacion IN ('pendiente','aprobado','rechazado')),
);

-- Tabla de productos
CREATE TABLE producto (
    id_producto VARCHAR(36) NOT NULL PRIMARY KEY,
    id_vendedor VARCHAR(36) NOT NULL REFERENCES vendedor(id_vendedor),
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    etiqueta VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    cantidad INT NOT NULL
);

-- Tabla de imágenes adicionales de productos
CREATE TABLE producto_imagen (
    id_imagen VARCHAR(36) NOT NULL PRIMARY KEY,
    id_producto VARCHAR(36) NOT NULL REFERENCES producto(id_producto),
    url VARCHAR(255) NOT NULL,
    orden INT DEFAULT 0
);

-- Tabla de pedidos
CREATE TABLE pedido (
    id_pedido VARCHAR(36) NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios),
    ubicacion VARCHAR(100) NOT NULL,
    fecha_pedido TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado_pedido VARCHAR(20) DEFAULT 'pendiente' CHECK (estado_pedido IN ('pendiente','procesando','enviado','entregado','cancelado'))
);

-- Tabla de ítems del pedido
CREATE TABLE pedido_items (
    id_pedido_item VARCHAR(36) NOT NULL PRIMARY KEY,
    id_pedido VARCHAR(36) NOT NULL REFERENCES pedido(id_pedido),
    id_producto VARCHAR(36) NOT NULL REFERENCES producto(id_producto),
    cantidad INT NOT NULL
);

-- Tabla de carritos
CREATE TABLE carrito (
    id_carrito VARCHAR(36) NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios),
    fecha_creacion TIMESTAMP NOT NULL
);

-- Tabla de ítems del carrito
CREATE TABLE carrito_items (
    id_carrito_item VARCHAR(36) NOT NULL PRIMARY KEY,
    id_producto VARCHAR(36) NOT NULL REFERENCES producto(id_producto),
    id_carrito VARCHAR(36) NOT NULL REFERENCES carrito(id_carrito),
    cantidad INT NOT NULL,
    precio_total DECIMAL(10,2) NOT NULL
);

-- Tabla de favoritos
CREATE TABLE favoritos (
    id_favorito VARCHAR(36) NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios),
    id_producto VARCHAR(36) NOT NULL REFERENCES producto(id_producto)
);

-- Tabla de reseñas
CREATE TABLE resena (
    id_resena VARCHAR(36) NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios),
    id_producto VARCHAR(36) NOT NULL REFERENCES producto(id_producto),
    comentario TEXT NOT NULL,
    calificacion INT CHECK (calificacion BETWEEN 1 AND 5)
);

-- Tabla de direcciones de usuarios
CREATE TABLE direccion (
    id_direccion VARCHAR(36) NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios),
    calle VARCHAR(100),
    numero INT,
    ciudad VARCHAR(50),
    codigo_postal VARCHAR(20),
    pais VARCHAR(50)
);

-- Tabla de pagos
CREATE TABLE pago (
    id_pago VARCHAR(36) NOT NULL PRIMARY KEY,
    id_pedido VARCHAR(36) NOT NULL REFERENCES pedido(id_pedido),
    metodo_pago VARCHAR(50),
    monto DECIMAL(10,2) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado_pago VARCHAR(20) DEFAULT 'pendiente' CHECK (estado_pago IN ('pendiente','pagado','fallido'))
);

-- Tabla de secuencia para idGenerator
CREATE TABLE secuencias (
  tipo VARCHAR(50) PRIMARY KEY,
  ultimo_numero INT NOT NULL
);
