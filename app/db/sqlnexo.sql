USE nexodb;

DROP TABLE IF EXISTS reseña;
DROP TABLE IF EXISTS favoritos;
DROP TABLE IF EXISTS carrito_items;
DROP TABLE IF EXISTS pedido_items;
DROP TABLE IF EXISTS carrito;
DROP TABLE IF EXISTS pedido;
DROP TABLE IF EXISTS vendedor;
DROP TABLE IF EXISTS producto;
DROP TABLE IF EXISTS usuarios;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id_usuarios VARCHAR(36) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(256) NOT NULL,
    telefono VARCHAR(20),
    role VARCHAR(20) NOT NULL,
    ciudad VARCHAR(50) NOT NULL,
    nombre_calle VARCHAR(100) NOT NULL,
    numero_casa INT NOT NULL,
    PRIMARY KEY (id_usuarios)
);

-- Tabla de vendedores
CREATE TABLE vendedor (
    id_vendedor VARCHAR(36) NOT NULL,
    id_usuarios VARCHAR(36) NOT NULL,
    nombre_empresa VARCHAR(100) NOT NULL,
    rut_empresa VARCHAR(20),
    descripcion TEXT,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado_aprobacion ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    PRIMARY KEY (id_vendedor),
    FOREIGN KEY (id_usuarios) REFERENCES usuarios(id_usuarios)
);

-- Tabla de productos
CREATE TABLE producto (
    id_producto VARCHAR(36) NOT NULL,
    id_vendedor VARCHAR(36) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    etiqueta VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_producto),
    FOREIGN KEY (id_vendedor) REFERENCES vendedor(id_vendedor)
);

-- Tabla de pedidos
CREATE TABLE pedido (
    id_pedido VARCHAR(36) NOT NULL,
    id_usuarios VARCHAR(36) NOT NULL,
    ubicacion VARCHAR(100) NOT NULL,
    fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado_pedido ENUM('pendiente', 'procesando', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente', -- Opcional: para seguimiento
    PRIMARY KEY (id_pedido),
    FOREIGN KEY (id_usuarios) REFERENCES usuarios(id_usuarios)
);

-- Tabla de ítems del pedido
CREATE TABLE pedido_items (
    id_pedido_item VARCHAR(36) NOT NULL,
    id_pedido VARCHAR(36) NOT NULL,
    id_producto VARCHAR(36) NOT NULL,
    cantidad INT NOT NULL,
    PRIMARY KEY (id_pedido_item),
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);

-- Tabla de carritos
CREATE TABLE carrito (
    id_carrito VARCHAR(36) NOT NULL,
    id_usuarios VARCHAR(36) NOT NULL,
    fecha_creacion DATETIME NOT NULL,
    PRIMARY KEY (id_carrito),
    FOREIGN KEY (id_usuarios) REFERENCES usuarios(id_usuarios)
);

-- Tabla de ítems del carrito
CREATE TABLE carrito_items (
    id_carrito_item VARCHAR(36) NOT NULL,
    id_producto VARCHAR(36) NOT NULL,
    id_carrito VARCHAR(36) NOT NULL,
    cantidad INT NOT NULL,
    precio_total DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_carrito_item),
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto),
    FOREIGN KEY (id_carrito) REFERENCES carrito(id_carrito)
);

-- Tabla de favoritos
CREATE TABLE favoritos (
    id_favorito VARCHAR(36) NOT NULL,
    id_usuarios VARCHAR(36) NOT NULL,
    id_producto VARCHAR(36) NOT NULL,
    PRIMARY KEY (id_favorito),
    FOREIGN KEY (id_usuarios) REFERENCES usuarios(id_usuarios),
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);

-- Tabla de reseñas
CREATE TABLE reseña (
    id_resena VARCHAR(36) NOT NULL,
    id_usuarios VARCHAR(36) NOT NULL,
    id_producto VARCHAR(36) NOT NULL,
    comentario TEXT NOT NULL,
    calificacion INT CHECK (calificacion BETWEEN 1 AND 5),
    PRIMARY KEY (id_resena),
    FOREIGN KEY (id_usuarios) REFERENCES usuarios(id_usuarios),
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);
