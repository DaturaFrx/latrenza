<?php
// ==========================================
// configuracion.php
// ==========================================

// Prevenir acceso directo
defined('ABSPATH') || define('ABSPATH', dirname(__FILE__));

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'latrenza');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuración del sitio
define('SITE_NAME', 'La Trenza');
define('SITE_URL', 'http://localhost/latrenza');
define('ADMIN_EMAIL', 'admin@pandelhogar.com');

// Rutas de directorios
define('ROOT_PATH', dirname(__FILE__));
define('FILES_PATH', ROOT_PATH . '/files');

// Configuración de correo
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'tu_correo@gmail.com');
define('SMTP_PASS', 'tu_contraseña');
define('SMTP_PORT', 587);

// Zona horaria
date_default_timezone_set('America/Tijuana');

// Configuración de errores
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Configuración de la sesión
if (session_status() === PHP_SESSION_NONE) {
    // Configuración de seguridad de sesión
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    ini_set('session.cookie_samesite', 'Strict');

    // Duración de la sesión
    ini_set('session.cookie_lifetime', 60 * 60 * 24); // 24 horas
    ini_set('session.gc_maxlifetime', 60 * 60 * 24);  // 24 horas

    session_start();
}

// Incluir archivos necesarios
require_once(ROOT_PATH . '/funciones.php');
require_once(ROOT_PATH . '/conexionBD.php');

// Establecer conexión a la base de datos
try {
    $conexion = Conexion::getInstance();
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Headers de seguridad básicos
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');