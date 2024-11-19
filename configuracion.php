<?php
// ==========================================
// configuracion.php
// ==========================================
// Prevenir acceso directo
defined('ABSPATH') || define('ABSPATH', dirname(__FILE__));

// Configuración de la base de datos
defined('DB_HOST') || define('DB_HOST', 'localhost');
defined('DB_NAME') || define('DB_NAME', 'latrenza');
defined('DB_USER') || define('DB_USER', 'root');
defined('DB_PASS') || define('DB_PASS', '');

// Configuración del sitio
defined('SITE_NAME') || define('SITE_NAME', 'La Trenza');
defined('SITE_URL') || define('SITE_URL', 'http://localhost/latrenza');
defined('ADMIN_EMAIL') || define('ADMIN_EMAIL', 'admin@pandelhogar.com');

// Rutas de directorios
defined('ROOT_PATH') || define('ROOT_PATH', dirname(__FILE__));
defined('FILES_PATH') || define('FILES_PATH', ROOT_PATH . '/files');

// Configuración de correo
defined('SMTP_HOST') || define('SMTP_HOST', 'smtp.gmail.com');
defined('SMTP_USER') || define('SMTP_USER', 'tu_correo@gmail.com');
defined('SMTP_PASS') || define('SMTP_PASS', 'tu_contraseña');
defined('SMTP_PORT') || define('SMTP_PORT', 587);

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

// Función para validar cookie de sesión persistente
if (!function_exists('validarCookieLogin')) {
    function validarCookieLogin() {
        if (isset($_COOKIE['sesion_persistente'])) {
            $token = $_COOKIE['sesion_persistente'];
            
            try {
                $pdo = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                    DB_USER,
                    DB_PASS,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                );

                $stmt = $pdo->prepare("
                    SELECT u.* FROM usuarios u
                    JOIN tokens_usuario t ON u.id_usuario = t.id_usuario
                    WHERE t.token = ? AND t.fecha_expiracion > NOW()
                    LIMIT 1
                ");
                $stmt->execute([$token]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    $_SESSION['usuario'] = array(
                        'id' => $usuario['id_usuario'],
                        'nombre' => $usuario['nombre'],
                        'email' => $usuario['correo_electronico'],
                        'telefono' => $usuario['telefono'],
                        'direccion' => $usuario['direccion']
                    );
                    return true;
                }
            } catch (Exception $e) {
                error_log("Error de validación de cookie: " . $e->getMessage());
            }
        }
        return false;
    }
}

// Función para crear una sesión persistente
if (!function_exists('crearSesionPersistente')) {
    function crearSesionPersistente($idUsuario) {
        try {
            $token = bin2hex(random_bytes(32));
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
            
            $stmt = $pdo->prepare("
                INSERT INTO tokens_usuario (id_usuario, token, fecha_expiracion) 
                VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))
            ");
            $stmt->execute([$idUsuario, $token]);
            
            setcookie(
                'sesion_persistente',
                $token,
                [
                    'expires' => time() + (30 * 24 * 60 * 60),
                    'path' => '/',
                    'secure' => isset($_SERVER['HTTPS']),
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]
            );
            
            return true;
        } catch (Exception $e) {
            error_log("Error al crear sesión persistente: " . $e->getMessage());
            return false;
        }
    }
}

// Función para limpiar tokens expirados
if (!function_exists('limpiarTokensExpirados')) {
    function limpiarTokensExpirados() {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
            
            $stmt = $pdo->prepare("DELETE FROM tokens_usuario WHERE fecha_expiracion < NOW()");
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al limpiar tokens: " . $e->getMessage());
        }
    }
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
    
    // Verificar cookie de sesión persistente si no hay sesión activa
    if (!isset($_SESSION['usuario']) && !validarCookieLogin()) {
        // Limpiar tokens expirados periódicamente (1 de cada 100 solicitudes)
        if (rand(1, 100) === 1) {
            limpiarTokensExpirados();
        }
    }
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
?>
