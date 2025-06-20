ALI 3000 - Esquema de Base de Datos
📋 Información General
Nombre de la Base de Datos: ali3000_db
Motor: MySQL 8.0+
Charset: utf8mb4
Collation: utf8mb4_unicode_ci

🗂️ Estructura Completa de Tablas
1. 👥 Tabla: usuarios
Almacena información de todos los usuarios del sistema (consultores, validadores y administradores).

sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_usuario VARCHAR(10) NOT NULL UNIQUE, -- C-XXXXX / V-XXXXX / A-XXXXX
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
    tarifa_por_hora DECIMAL(8,2) DEFAULT NULL, -- Solo para consultores
    nivel_desarrollo ENUM('junior', 'semi-senior', 'senior') DEFAULT NULL, -- Solo consultores
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    creado_por INT DEFAULT NULL,
    
    INDEX idx_codigo_usuario (codigo_usuario),
    INDEX idx_tipo_usuario (tipo_usuario),
    INDEX idx_estado (estado),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id)
);
2. 🏢 Tabla: empresas
Almacena información de las empresas clientes.

sql
CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    rif VARCHAR(20) UNIQUE DEFAULT NULL,
    direccion TEXT DEFAULT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    validador_id INT NOT NULL, -- Un validador por empresa
    estado ENUM('activa', 'inactiva') DEFAULT 'activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_nombre (nombre),
    INDEX idx_validador (validador_id),
    INDEX idx_estado (estado),
    FOREIGN KEY (validador_id) REFERENCES usuarios(id)
);
3. 🔗 Tabla: usuario_empresas
Relación many-to-many entre consultores y empresas.

sql
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
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (empresa_id) REFERENCES empresas(id)
);
4. ⏰ Tabla: registros_horas
Almacena los registros de horas laborales de los consultores.

sql
CREATE TABLE registros_horas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    empresa_id INT NOT NULL,
    fecha_trabajo DATE NOT NULL,
    actividades TEXT NOT NULL,
    horas_normales DECIMAL(3,1) NOT NULL DEFAULT 0, -- Máximo 8.0
    horas_extra DECIMAL(3,1) NOT NULL DEFAULT 0,
    estado ENUM('borrador', 'pendiente_validacion', 'validado', 'rechazado', 'modificacion_solicitada') 
           DEFAULT 'borrador',
    comentarios_validador TEXT DEFAULT NULL,
    fecha_envio_validacion TIMESTAMP NULL,
    fecha_validacion TIMESTAMP NULL,
    validador_id INT DEFAULT NULL,
    quincena VARCHAR(20) NOT NULL, -- "1-15 Jun 2025" / "16-30 Jun 2025"
    periodo_pago VARCHAR(20) NOT NULL, -- "Jun 2025"
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
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (empresa_id) REFERENCES empresas(id),
    FOREIGN KEY (validador_id) REFERENCES usuarios(id),
    
    CONSTRAINT chk_horas_normales CHECK (horas_normales >= 0 AND horas_normales <= 8.0),
    CONSTRAINT chk_horas_extra CHECK (horas_extra >= 0 AND horas_extra <= 12.0)
);
5. 💰 Tabla: pagos
Almacena información de pagos procesados.

sql
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_pago VARCHAR(20) NOT NULL UNIQUE, -- ALI001, ALI002, etc.
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
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (procesado_por) REFERENCES usuarios(id)
);
6. 📄 Tabla: facturas
Almacena facturas generadas por consultores.

sql
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
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (pago_id) REFERENCES pagos(id)
);
7. ⚙️ Tabla: configuraciones
Almacena configuraciones del sistema.

sql
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
    FOREIGN KEY (actualizado_por) REFERENCES usuarios(id)
);
8. 📱 Tabla: notificaciones
Registro de notificaciones enviadas.

sql
CREATE TABLE notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo ENUM('whatsapp', 'email', 'sistema') NOT NULL,
    asunto VARCHAR(255) NOT NULL,
    mensaje TEXT NOT NULL,
    estado ENUM('pendiente', 'enviada', 'fallida', 'leida') DEFAULT 'pendiente',
    datos_extra JSON DEFAULT NULL, -- Para almacenar información adicional
    fecha_programada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_enviada TIMESTAMP NULL,
    intentos INT DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_usuario (usuario_id),
    INDEX idx_tipo (tipo),
    INDEX idx_estado (estado),
    INDEX idx_fecha_programada (fecha_programada),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
9. 📊 Tabla: auditoria
Registro de acciones importantes del sistema.

sql
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
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
🔧 Configuraciones Iniciales
Datos de Configuración por Defecto
sql
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
Usuario Administrador por Defecto
sql
INSERT INTO usuarios (
    codigo_usuario, tipo_usuario, primer_nombre, primer_apellido, 
    cedula, fecha_nacimiento, password_hash, estado
) VALUES (
    'A-00001', 'admin', 'Administrador', 'Sistema', 
    '12345678', '1990-01-01', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: admin123
    'activo'
);
📋 Índices y Optimizaciones
Índices Compuestos Adicionales
sql
-- Para optimizar consultas de reportes
CREATE INDEX idx_registros_periodo_usuario ON registros_horas(periodo_pago, usuario_id, estado);
CREATE INDEX idx_registros_validador_estado ON registros_horas(validador_id, estado, fecha_trabajo);
CREATE INDEX idx_pagos_periodo_usuario ON pagos(usuario_id, periodo_inicio, periodo_fin);

-- Para optimizar dashboard
CREATE INDEX idx_usuarios_activos_tipo ON usuarios(estado, tipo_usuario);
CREATE INDEX idx_asignaciones_activas ON usuario_empresas(estado, usuario_id, empresa_id);
🚀 Scripts de Mantenimiento
Limpieza de Datos Antiguos
sql
-- Eliminar notificaciones enviadas de más de 6 meses
DELETE FROM notificaciones 
WHERE estado = 'enviada' 
  AND fecha_enviada < DATE_SUB(NOW(), INTERVAL 6 MONTH);

-- Comprimir registros de auditoría antiguos
DELETE FROM auditoria 
WHERE fecha_accion < DATE_SUB(NOW(), INTERVAL 1 YEAR);
Validaciones y Constraints
sql
-- Validar que las fechas de trabajo sean coherentes
ALTER TABLE registros_horas 
ADD CONSTRAINT chk_fecha_trabajo 
CHECK (fecha_trabajo <= CURDATE());

-- Validar que los períodos de pago sean coherentes
ALTER TABLE pagos 
ADD CONSTRAINT chk_periodo_pago 
CHECK (periodo_fin >= periodo_inicio);
📈 Estadísticas y Vistas Útiles
Vista: Resumen de Horas por Usuario
sql
CREATE VIEW vista_resumen_horas AS
SELECT 
    u.codigo_usuario,
    CONCAT(u.primer_nombre, ' ', u.primer_apellido) as nombre_completo,
    rh.periodo_pago,
    SUM(rh.horas_normales) as total_horas_normales,
    SUM(rh.horas_extra) as total_horas_extra,
    COUNT(DISTINCT rh.fecha_trabajo) as dias_trabajados,
    rh.estado
FROM usuarios u
JOIN registros_horas rh ON u.id = rh.usuario_id
WHERE u.tipo_usuario = 'consultor'
GROUP BY u.id, rh.periodo_pago, rh.estado;
Vista: Dashboard Administrativo
sql
CREATE VIEW vista_dashboard_admin AS
SELECT 
    (SELECT COUNT(*) FROM usuarios WHERE tipo_usuario = 'consultor' AND estado = 'activo') as consultores_activos,
    (SELECT COUNT(*) FROM usuarios WHERE tipo_usuario = 'validador' AND estado = 'activo') as validadores_activos,
    (SELECT COUNT(*) FROM empresas WHERE estado = 'activa') as empresas_activas,
    (SELECT COUNT(*) FROM registros_horas WHERE estado = 'pendiente_validacion') as horas_pendientes_validacion,
    (SELECT COUNT(*) FROM registros_horas WHERE estado = 'validado' AND id NOT IN (SELECT DISTINCT registro_id FROM pagos WHERE registro_id IS NOT NULL)) as horas_pendientes_pago,
    (SELECT SUM(total_bs) FROM pagos WHERE estado = 'pagado' AND MONTH(fecha_pago) = MONTH(CURRENT_DATE)) as total_pagado_mes_actual;
🔐 Backup y Seguridad
Script de Backup Recomendado
bash
#!/bin/bash
# Backup diario de la base de datos ALI 3000
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u root -p ali3000_db > backup_ali3000_$DATE.sql
Configuraciones de Seguridad
sql
-- Crear usuario específico para la aplicación
CREATE USER 'ali3000_app'@'localhost' IDENTIFIED BY 'password_seguro_aqui';
GRANT SELECT, INSERT, UPDATE, DELETE ON ali3000_db.* TO 'ali3000_app'@'localhost';
FLUSH PRIVILEGES;
