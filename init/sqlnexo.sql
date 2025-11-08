-- =====================================================
-- LIMPIEZA DE TABLAS EXISTENTES
-- =====================================================
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
DROP TABLE IF EXISTS secuencias CASCADE;
DROP TABLE IF EXISTS ordenes_temporales CASCADE;

-- =====================================================
-- TABLA: usuarios
-- =====================================================
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

-- =====================================================
-- TABLA: vendedor
-- =====================================================
CREATE TABLE vendedor (
    id_vendedor VARCHAR(36) NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios) ON DELETE CASCADE,
    nombre_empresa VARCHAR(100) NOT NULL,
    rut_empresa VARCHAR(20),
    descripcion TEXT,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado_aprobacion VARCHAR(20) DEFAULT 'pendiente' CHECK (estado_aprobacion IN ('pendiente','aprobado','rechazado')),
    mp_access_token TEXT,
    mp_user_id TEXT
);

-- =====================================================
-- TABLA: producto
-- =====================================================
CREATE TABLE producto (
    id_producto VARCHAR(36) NOT NULL PRIMARY KEY,
    id_vendedor VARCHAR(36) NOT NULL REFERENCES vendedor(id_vendedor) ON DELETE CASCADE,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    etiqueta VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    cantidad INT NOT NULL
);

-- =====================================================
-- TABLA: producto_imagen
-- =====================================================
CREATE TABLE producto_imagen (
    id_imagen VARCHAR(36) NOT NULL PRIMARY KEY,
    id_producto VARCHAR(36) NOT NULL REFERENCES producto(id_producto) ON DELETE CASCADE,
    url VARCHAR(255) NOT NULL,
    orden INT DEFAULT 0
);

-- =====================================================
-- TABLA: carrito
-- =====================================================
CREATE TABLE carrito (
    id_carrito VARCHAR(36) NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios) ON DELETE CASCADE,
    fecha_creacion TIMESTAMP NOT NULL
);

-- =====================================================
-- TABLA: carrito_items
-- =====================================================
CREATE TABLE carrito_items (
    id_carrito_item VARCHAR(36) NOT NULL PRIMARY KEY,
    id_producto VARCHAR(36) NOT NULL REFERENCES producto(id_producto) ON DELETE CASCADE,
    id_carrito VARCHAR(36) NOT NULL REFERENCES carrito(id_carrito) ON DELETE CASCADE,
    cantidad INT NOT NULL,
    precio_total DECIMAL(10,2) NOT NULL
);

-- =====================================================
-- TABLA: favoritos
-- =====================================================
CREATE TABLE favoritos (
    id_favorito SERIAL NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios) ON DELETE CASCADE,
    id_producto VARCHAR(36) NOT NULL REFERENCES producto(id_producto) ON DELETE CASCADE
);

-- =====================================================
-- TABLA: direccion
-- =====================================================
CREATE TABLE direccion (
    id_direccion VARCHAR(36) NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios) ON DELETE CASCADE,
    calle VARCHAR(100),
    numero INT,
    ciudad VARCHAR(50),
    codigo_postal VARCHAR(20),
    pais VARCHAR(50)
);

-- =====================================================
-- TABLA: pedido
-- =====================================================
CREATE TABLE pedido (
    id_pedido VARCHAR(36) NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios),
    ubicacion VARCHAR(100) NOT NULL,
    fecha_pedido TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado_pedido VARCHAR(20) DEFAULT 'pendiente' CHECK (estado_pedido IN ('pendiente','procesando','enviado','entregado','cancelado'))
);

-- =====================================================
-- TABLA: pedido_items
-- =====================================================
CREATE TABLE pedido_items (
    id_pedido_item VARCHAR(36) NOT NULL PRIMARY KEY,
    id_pedido VARCHAR(36) NOT NULL REFERENCES pedido(id_pedido),
    id_producto VARCHAR(36) NOT NULL REFERENCES producto(id_producto),
    cantidad INT NOT NULL
);

-- =====================================================
-- TABLA: pago
-- =====================================================
CREATE TABLE pago (
    id_pago VARCHAR(36) PRIMARY KEY,
    id_usuario VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios) ON DELETE CASCADE,
    id_transaccion_mp VARCHAR(100),
    monto_total DECIMAL(10,2) NOT NULL,
    estado_pago VARCHAR(20) DEFAULT 'pendiente' CHECK (estado_pago IN ('pendiente','pagado','fallido','rechazado','cancelado')),
    productos JSONB NOT NULL,
    pedido_info JSONB NOT NULL,
    fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =====================================================
-- TABLA: resena
-- =====================================================
CREATE TABLE resena (
    id_resena VARCHAR(36) NOT NULL PRIMARY KEY,
    id_usuarios VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios),
    id_producto VARCHAR(36) NOT NULL REFERENCES producto(id_producto),
    comentario TEXT NOT NULL,
    calificacion INT CHECK (calificacion BETWEEN 1 AND 5)
);

-- =====================================================
-- TABLA: secuencias
-- =====================================================
CREATE TABLE secuencias (
    tipo VARCHAR(50) PRIMARY KEY,
    ultimo_numero INT NOT NULL
);

-- Tabla de códigos de verificación
CREATE TABLE codigos_verificacion (
    id_usuarios VARCHAR(36) REFERENCES usuarios(id_usuarios) ON DELETE CASCADE,
    codigo VARCHAR(6) NOT NULL,
    creado_en TIMESTAMP NOT NULL DEFAULT NOW(),
    PRIMARY KEY (id_usuarios)
);

-- =====================================================
-- TABLA: ordenes_temporales
-- =====================================================
CREATE TABLE ordenes_temporales (
    id_orden VARCHAR(100) PRIMARY KEY,
    id_usuario VARCHAR(36) NOT NULL REFERENCES usuarios(id_usuarios) ON DELETE CASCADE,
    productos JSON NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    direccion VARCHAR(255),
    departamento VARCHAR(100),
    localidad VARCHAR(100),
    apartamento VARCHAR(100),
    indicaciones TEXT,
    nombre VARCHAR(150),
    telefono VARCHAR(50),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);