-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS formulario_contacto
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE formulario_contacto;

-- Tabla principal de contactos
CREATE TABLE IF NOT EXISTS contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    edad TINYINT UNSIGNED NOT NULL,
    nacionalidad VARCHAR(50) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    asunto VARCHAR(200) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado_lectura ENUM('no_leido', 'leido', 'respondido') DEFAULT 'no_leido',
    
    -- Índice para filtrar por estado
    INDEX idx_estado (estado_lectura)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;