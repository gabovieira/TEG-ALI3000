🚀 ALI 3000 - Guía Completa de Instalación y Setup
📋 Lista de Descargas Necesarias
1. 💻 XAMPP (Servidor Local)
¿Qué es? Paquete que incluye Apache, MySQL, PHP y phpMyAdmin
¿Para qué? Crear el servidor local donde funcionará ALI 3000
Descarga:

Ve a: https://www.apachefriends.org/download.html
Descarga: XAMPP para Windows (última versión con PHP 8.1+)
Tamaño: ~150MB

2. 🔧 Cursor IDE (Editor de Código)
¿Qué es? IDE con integración de Claude AI
¿Para qué? Escribir código con ayuda de IA
Descarga:

Ve a: https://cursor.sh/
Descarga: Cursor para Windows
Tamaño: ~100MB

3. 📁 Archivos del Proyecto ALI 3000
¿Qué son? Los archivos de contexto que ya te creé
¿Para qué? Tener toda la información del proyecto en Cursor

⚙️ Instalación Paso a Paso
PASO 1: Instalar XAMPP

Ejecutar el instalador

Doble clic en el archivo descargado
Si Windows pregunta por permisos, acepta


Configuración de instalación
✅ Apache (obligatorio)
✅ MySQL (obligatorio) 
✅ PHP (obligatorio)
✅ phpMyAdmin (recomendado)
❌ FileZilla (no necesario)
❌ Mercury (no necesario)
❌ Tomcat (no necesario)

Carpeta de instalación

Recomendado: C:\xampp\
NO cambies la carpeta a menos que sepas lo que haces


Finalizar instalación

Deja marcado "Iniciar Panel de Control"
Clic en "Finish"



PASO 2: Configurar XAMPP

Abrir Panel de Control XAMPP

Se abre automáticamente o busca "XAMPP Control Panel"


Iniciar servicios necesarios
Apache: Clic en "Start" ✅
MySQL: Clic en "Start" ✅

Verificar que funciona

Abre tu navegador
Ve a: http://localhost
Deberías ver la página de bienvenida de XAMPP



PASO 3: Instalar Cursor IDE

Ejecutar instalador

Doble clic en el archivo descargado
Acepta los permisos si Windows pregunta


Configuración básica

Acepta la licencia
Deja la carpeta de instalación por defecto
Clic en "Install"


Primera configuración de Cursor

Al abrir por primera vez, te pedirá login
Puedes crear cuenta o usar con GitHub/Google
Acepta los términos de servicio



PASO 4: Crear el Proyecto ALI 3000

Crear carpeta del proyecto

Ve a: C:\xampp\htdocs\
Crea una nueva carpeta llamada: ali3000
Ruta final: C:\xampp\htdocs\ali3000\


Crear estructura de carpetas
Dentro de ali3000, crea estas carpetas:
ali3000/
├── config/
├── includes/
├── modules/
│   ├── auth/
│   ├── dashboard/
│   ├── contacts/
│   ├── chats/
│   ├── automation/
│   └── reports/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── api/
│   ├── whatsapp/
│   └── endpoints/
└── docs/


PASO 5: Configurar Base de Datos

Abrir phpMyAdmin

Ve a: http://localhost/phpmyadmin
Usuario: root
Contraseña: (dejar vacío)


Crear la base de datos

Clic en "Nueva" en el menú izquierdo
Nombre: ali3000_db
Cotejamiento: utf8mb4_unicode_ci
Clic en "Crear"


Crear las tablas

Selecciona la base de datos ali3000_db
Ve a la pestaña "SQL"
Copia y pega el código SQL del archivo database-schema.md
Clic en "Continuar"



PASO 6: Configurar Cursor para ALI 3000

Abrir el proyecto en Cursor

Abre Cursor
File → Open Folder
Selecciona: C:\xampp\htdocs\ali3000\


Instalar extensiones recomendadas

Ve a Extensions (Ctrl+Shift+X)
Busca e instala:
✅ PHP Intelephense
✅ MySQL
✅ Bootstrap IntelliSense
✅ Auto Rename Tag
✅ Bracket Pair Colorizer



Configurar los archivos de contexto

En la carpeta docs/, crea estos archivos:

project-context.md (copia el contenido del primer documento)
database-schema.md (copia el contenido del segundo documento)
daily-progress.md (copia el contenido del tercer documento)





PASO 7: Crear Archivo de Configuración Inicial

Crear config/database.php
php<?php
// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'ali3000_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Crear conexión
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

Crear index.php básico
php<?php
session_start();
require_once 'config/database.php';

// Redireccionar a login si no está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: modules/auth/login.php');
    exit;
}

// Redireccionar al dashboard
header('Location: modules/dashboard/index.php');
exit;
?>



✅ Verificación de Instalación
Prueba 1: XAMPP Funcionando

Ve a: http://localhost
Deberías ver la página de XAMPP ✅

Prueba 2: Base de Datos Creada

Ve a: http://localhost/phpmyadmin
Deberías ver ali3000_db en la lista ✅

Prueba 3: Proyecto Accesible

Ve a: http://localhost/ali3000
Deberías ser redirigido a login ✅

Prueba 4: Cursor Configurado

Abre Cursor
Ve que el proyecto ali3000 esté abierto
Ve que los archivos de contexto estén en docs/ ✅


🔧 Solución de Problemas Comunes
❌ Error: "Apache no inicia"
Causa: Puerto 80 ocupado por otro programa
Solución:

En XAMPP Control Panel, clic en "Config" junto a Apache
Selecciona "Apache (httpd.conf)"
Busca Listen 80 y cámbialo por Listen 8080
Busca ServerName localhost:80 y cámbialo por ServerName localhost:8080
Guarda y reinicia Apache
Usa http://localhost:8080 en lugar de http://localhost

❌ Error: "MySQL no inicia"
Causa: Puerto 3306 ocupado
Solución:

Abre Administrador de Tareas
Busca procesos de MySQL y termínalos
Reinicia XAMPP
Inicia MySQL nuevamente

❌ Error: "No se puede conectar a la base de datos"
Causa: Configuración incorrecta
Solución:

Verifica que MySQL esté corriendo en XAMPP
Verifica que la base de datos ali3000_db exista
Revisa el archivo config/database.php

❌ Error: "Cursor no puede leer el proyecto"
Causa: Permisos de carpeta
Solución:

Cierra Cursor
Clic derecho en la carpeta ali3000
Propiedades → Seguridad → Editar
Dale permisos completos a tu usuario
Reabre Cursor


🎯 Preparación para Mañana - Día 1
Lista de Verificación Final

 XAMPP instalado y funcionando
 Cursor instalado y configurado
 Proyecto ALI 3000 creado en htdocs/ali3000/
 Base de datos ali3000_db creada con todas las tablas
 Archivos de contexto en la carpeta docs/
 Archivo config/database.php creado
 Conexión a base de datos funcionando

Al estar listo, simplemente escribe:
"Día 1 ALI 3000 - 2 horas disponibles"
Y te daré las tareas específicas del primer día con código listo para implementar.

📞 ¿Necesitas Ayuda?
Si tienes algún problema durante la instalación:

Toma captura de pantalla del error
Dime exactamente en qué paso estás
Copia el mensaje de error completo
Te ayudaré a solucionarlo inmediatamente

¡Todo está listo para que mañana empieces a desarrollar ALI 3000! 🚀