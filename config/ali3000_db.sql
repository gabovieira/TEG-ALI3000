-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS ali3000_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ali3000_db;

-- Tabla: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_usuario VARCHAR(10) NOT NULL UNIQUE,
    tipo_usuario ENUM('consultor', 'validador', 'admin') NOT NULL,
    primer_nombre VARCHAR(50) NOT NULL,
    segundo_nombre VARCHAR(50) DEFAULT NULL,
    primer_apellido VARCHAR(50) NOT NULL,
    segundo_apellido VARCHAR(50) DEFAULT NULL,
    cedula VARCHAR(15) NOT NULL UNIQUE,
    fecha_nacimiento DATE NOT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) UNIQUE DEFAULT NULL,
    password_hash VARCHAR(255) NOT NULL,
    tarifa_por_hora DECIMAL(8,2) DEFAULT NULL,
    nivel_desarrollo ENUM('junior', 'semi-senior', 'senior') DEFAULT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    creado_por INT DEFAULT NULL,
    
    INDEX idx_codigo_usuario (codigo_usuario),
    INDEX idx_tipo_usuario (tipo_usuario),
    INDEX idx_estado (estado),
    INDEX idx_email (email),
    INDEX idx_cedula (cedula),
    INDEX idx_telefono (telefono),
    INDEX idx_fecha_creacion (fecha_creacion),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id)
);

-- Tabla: empresas
CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    rif VARCHAR(20) UNIQUE DEFAULT NULL,
    direccion TEXT DEFAULT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    validador_id INT NOT NULL,
    estado ENUM('activa', 'inactiva') DEFAULT 'activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_nombre (nombre),
    INDEX idx_validador (validador_id),
    INDEX idx_estado (estado),
    INDEX idx_rif (rif),
    INDEX idx_email (email),
    INDEX idx_telefono (telefono),
    INDEX idx_fecha_creacion (fecha_creacion),
    FOREIGN KEY (validador_id) REFERENCES usuarios(id)
);

-- Tabla: relación usuario-empresa
CREATE TABLE usuario_empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    empresa_id INT NOT NULL,
    fecha_asignacion DATE NOT NULL,
    fecha_desasignacion DATE DEFAULT NULL,
    estado ENUM('activa', 'inactiva') DEFAULT 'activa',
    observaciones TEXT DEFAULT NULL,
    
    UNIQUE KEY unique_asignacion_activa (usuario_id, empresa_id, estado),
    INDEX idx_usuario (usuario_id),
    INDEX idx_empresa (empresa_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha_asignacion (fecha_asignacion),
    INDEX idx_fecha_desasignacion (fecha_desasignacion),
    INDEX idx_usuario_empresa_activa (usuario_id, empresa_id, estado),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (empresa_id) REFERENCES empresas(id)
);

-- Tabla: registros de horas
CREATE TABLE registros_horas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    empresa_id INT NOT NULL,
    fecha_trabajo DATE NOT NULL,
    actividades TEXT NOT NULL,
    horas_normales DECIMAL(3,1) NOT NULL DEFAULT 0,
    horas_extra DECIMAL(3,1) NOT NULL DEFAULT 0,
    estado ENUM('borrador', 'pendiente_validacion', 'validado', 'rechazado', 'modificacion_solicitada') 
           DEFAULT 'borrador',
    comentarios_validador TEXT DEFAULT NULL,
    fecha_envio_validacion TIMESTAMP NULL,
    fecha_validacion TIMESTAMP NULL,
    validador_id INT DEFAULT NULL,
    quincena VARCHAR(20) NOT NULL,
    periodo_pago VARCHAR(20) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_fecha_usuario_empresa (usuario_id, empresa_id, fecha_trabajo),
    INDEX idx_usuario (usuario_id),
    INDEX idx_empresa (empresa_id),
    INDEX idx_fecha_trabajo (fecha_trabajo),
    INDEX idx_estado (estado),
    INDEX idx_quincena (quincena),
    INDEX idx_periodo_pago (periodo_pago),
    INDEX idx_validador (validador_id),
    INDEX idx_fecha_envio (fecha_envio_validacion),
    INDEX idx_fecha_validacion (fecha_validacion),
    INDEX idx_usuario_periodo (usuario_id, periodo_pago),
    INDEX idx_empresa_periodo (empresa_id, periodo_pago),
    INDEX idx_validador_estado (validador_id, estado),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (empresa_id) REFERENCES empresas(id),
    FOREIGN KEY (validador_id) REFERENCES usuarios(id),
    
    CONSTRAINT chk_horas_normales CHECK (horas_normales >= 0 AND horas_normales <= 8.0),
    CONSTRAINT chk_horas_extra CHECK (horas_extra >= 0 AND horas_extra <= 12.0)
);

-- Tabla: pagos
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_pago VARCHAR(20) NOT NULL UNIQUE,
    usuario_id INT NOT NULL,
    periodo_inicio DATE NOT NULL,
    periodo_fin DATE NOT NULL,
    total_horas_normales DECIMAL(5,1) NOT NULL,
    total_horas_extra DECIMAL(5,1) NOT NULL,
    tarifa_por_hora DECIMAL(8,2) NOT NULL,
    subtotal_usd DECIMAL(10,2) NOT NULL,
    iva_porcentaje DECIMAL(4,2) NOT NULL DEFAULT 16.00,
    iva_monto DECIMAL(10,2) NOT NULL,
    islr_porcentaje DECIMAL(4,2) NOT NULL DEFAULT 3.00,
    islr_monto DECIMAL(10,2) NOT NULL,
    total_usd DECIMAL(10,2) NOT NULL,
    tasa_cambio DECIMAL(8,2) NOT NULL,
    total_bs DECIMAL(12,2) NOT NULL,
    fecha_pago DATE NOT NULL,
    numero_comprobante VARCHAR(50) DEFAULT NULL,
    estado ENUM('pendiente', 'pagado', 'anulado') DEFAULT 'pendiente',
    metodo_pago VARCHAR(50) DEFAULT 'Banesco',
    observaciones TEXT DEFAULT NULL,
    procesado_por INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_usuario (usuario_id),
    INDEX idx_codigo_pago (codigo_pago),
    INDEX idx_periodo (periodo_inicio, periodo_fin),
    INDEX idx_estado (estado),
    INDEX idx_fecha_pago (fecha_pago),
    INDEX idx_procesado_por (procesado_por),
    INDEX idx_usuario_periodo (usuario_id, periodo_inicio, periodo_fin),
    INDEX idx_estado_fecha (estado, fecha_pago),
    INDEX idx_total_bs (total_bs),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (procesado_por) REFERENCES usuarios(id)
);

-- Tabla: facturas
CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_factura VARCHAR(20) NOT NULL UNIQUE,
    usuario_id INT NOT NULL,
    pago_id INT NOT NULL,
    fecha_emision DATE NOT NULL,
    descripcion TEXT NOT NULL,
    subtotal_usd DECIMAL(10,2) NOT NULL,
    iva_monto DECIMAL(10,2) NOT NULL,
    total_usd DECIMAL(10,2) NOT NULL,
    tasa_cambio DECIMAL(8,2) NOT NULL,
    total_bs DECIMAL(12,2) NOT NULL,
    estado ENUM('generada', 'enviada', 'pagada') DEFAULT 'generada',
    ruta_archivo VARCHAR(255) DEFAULT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_numero_factura (numero_factura),
    INDEX idx_usuario (usuario_id),
    INDEX idx_pago (pago_id),
    INDEX idx_fecha_emision (fecha_emision),
    INDEX idx_estado (estado),
    INDEX idx_total_bs (total_bs),
    INDEX idx_usuario_fecha (usuario_id, fecha_emision),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (pago_id) REFERENCES pagos(id)
);

-- Tabla: configuraciones
CREATE TABLE configuraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(50) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    descripcion TEXT DEFAULT NULL,
    tipo ENUM('texto', 'numero', 'booleano', 'json') DEFAULT 'texto',
    categoria VARCHAR(50) DEFAULT 'general',
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    actualizado_por INT DEFAULT NULL,
    
    INDEX idx_clave (clave),
    INDEX idx_categoria (categoria),
    INDEX idx_tipo (tipo),
    INDEX idx_fecha_actualizacion (fecha_actualizacion),
    FOREIGN KEY (actualizado_por) REFERENCES usuarios(id)
);

-- Tabla: notificaciones
CREATE TABLE notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo ENUM('whatsapp', 'email', 'sistema') NOT NULL,
    asunto VARCHAR(255) NOT NULL,
    mensaje TEXT NOT NULL,
    estado ENUM('pendiente', 'enviada', 'fallida', 'leida') DEFAULT 'pendiente',
    datos_extra JSON DEFAULT NULL,
    fecha_programada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_enviada TIMESTAMP NULL,
    intentos INT DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_usuario (usuario_id),
    INDEX idx_tipo (tipo),
    INDEX idx_estado (estado),
    INDEX idx_fecha_programada (fecha_programada),
    INDEX idx_fecha_enviada (fecha_enviada),
    INDEX idx_usuario_estado (usuario_id, estado),
    INDEX idx_tipo_estado (tipo, estado),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla: auditoría
CREATE TABLE auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT DEFAULT NULL,
    accion VARCHAR(100) NOT NULL,
    tabla_afectada VARCHAR(50) NOT NULL,
    registro_id INT DEFAULT NULL,
    datos_anteriores JSON DEFAULT NULL,
    datos_nuevos JSON DEFAULT NULL,
    direccion_ip VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    fecha_accion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_usuario (usuario_id),
    INDEX idx_accion (accion),
    INDEX idx_tabla (tabla_afectada),
    INDEX idx_fecha (fecha_accion),
    INDEX idx_usuario_fecha (usuario_id, fecha_accion),
    INDEX idx_tabla_fecha (tabla_afectada, fecha_accion),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Índices para optimización

CREATE INDEX idx_mensajes_conversacion ON mensajes(conversacion_id);
CREATE INDEX idx_mensajes_fecha ON mensajes(fecha_envio);
CREATE INDEX idx_conversaciones_contacto ON conversaciones(contacto_id);
CREATE INDEX idx_conversaciones_usuario ON conversaciones(usuario_id);
CREATE INDEX idx_notificaciones_usuario ON notificaciones(usuario_id);
CREATE INDEX idx_logs_fecha ON logs_sistema(fecha_creacion);
CREATE INDEX idx_estadisticas_fecha ON estadisticas_diarias(fecha);

-- Datos iniciales
-- Usuario administrador por defecto
INSERT INTO usuarios (
    codigo_usuario, tipo_usuario, primer_nombre, primer_apellido, 
    cedula, fecha_nacimiento, password_hash, estado
) VALUES (
    'A-00001', 'admin', 'Administrador', 'Sistema', 
    '12345678', '1990-01-01', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: admin123
    'activo'
);

-- Configuraciones básicas
INSERT INTO configuraciones (clave, valor, descripcion, tipo, categoria) VALUES
('iva_porcentaje', '16.00', 'Porcentaje de IVA aplicado a facturas', 'numero', 'impuestos'),
('islr_porcentaje', '3.00', 'Porcentaje de ISLR retenido en pagos', 'numero', 'impuestos'),
('horas_maximas_normales', '8.0', 'Máximo de horas normales por día', 'numero', 'horas'),
('horas_maximas_extra', '12.0', 'Máximo de horas extra por día', 'numero', 'horas'),
('dias_limite_validacion', '8', 'Días límite para validar horas', 'numero', 'validacion'),
('whatsapp_api_token', '', 'Token de API de WhatsApp', 'texto', 'notificaciones'),
('bcv_api_url', 'https://api.bcv.org.ve/', 'URL de API del BCV para tasa de cambio', 'texto', 'api'),
('empresa_nombre', 'ALI 3000, C.A.', 'Nombre de la empresa', 'texto', 'empresa'),
('empresa_rif', 'J-123456789', 'RIF de la empresa', 'texto', 'empresa'),
('prefijo_codigo_pago', 'ALI', 'Prefijo para códigos de pago', 'texto', 'pagos'); 