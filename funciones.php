<?php
// funciones.php

// Limpiar entrada de usuario para prevenir XSS y otros ataques
function limpiarInput($data)
{
    $data = trim($data); // Elimina espacios en blanco al inicio y final
    $data = stripslashes($data); // Elimina barras invertidas
    $data = htmlspecialchars($data); // Convierte caracteres especiales en entidades HTML
    return $data;
}

// Obtener los productos destacados desde la base de datos
function getProductosDestacados()
{
    $db = Conexion::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM productos LIMIT 4"); // Obtener los primeros 4 productos
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devolver todos los resultados
}

// Obtener las categorías de productos desde la base de datos
function getCategorias()
{
    $db = Conexion::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM categorias"); // Obtener todas las categorías
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devolver todos los resultados
}

// Verificar si el usuario está logueado
function isLoggedIn()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Iniciar sesión si aún no está iniciada
    }
    return isset($_SESSION['usuario']) && // Verificar que la sesión esté activa
        isset($_SESSION['usuario']['id']) &&
        !empty($_SESSION['usuario']['id']);
}

// Obtener la información del usuario actual si está logueado
function getCurrentUser()
{
    if (isLoggedIn()) {
        return $_SESSION['usuario']; // Devolver la información del usuario
    }
    return null; // Si no está logueado, devolver null
}

// Verificar si el usuario tiene rol de administrador
function isAdmin()
{
    if (isLoggedIn() && isset($_SESSION['usuario']['rol'])) {
        return $_SESSION['usuario']['rol'] === 'admin'; // Verificar si el rol es 'admin'
    }
    return false; // Si no es admin, devolver false
}

// Requerir que el usuario esté logueado para acceder a ciertas páginas
function requireLogin()
{
    if (!isLoggedIn()) {
        redirect('/admin/login.php'); // Redirigir al login si no está logueado
    }
}

// Requerir que el usuario sea administrador para acceder a ciertas páginas
function requireAdmin()
{
    if (!isAdmin()) {
        redirect('/index.php'); // Redirigir a la página principal si no es admin
    }
}

// Cerrar sesión y redirigir al usuario
function logOut()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Iniciar sesión si aún no está iniciada
    }
    $_SESSION = array(); // Limpiar todas las variables de sesión
    session_destroy(); // Destruir la sesión
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/'); // Eliminar cookie de sesión
    }
    redirect('/index.php'); // Redirigir al inicio
}

// Redirigir a una URL específica
function redirect($url)
{
    header("Location: " . SITE_URL . $url); // Redirigir a la URL proporcionada
    exit();
}

// Formatear el precio para que tenga 2 decimales y separador de miles
function formatearPrecio($precio)
{
    return number_format($precio, 2, '.', ','); // Formatear el precio
}

// Obtener el número de artículos en el carrito de compras
function getCarritoCount()
{
    return isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; // Contar los elementos en el carrito
}

// Enviar un correo electrónico
function enviarEmail($para, $asunto, $mensaje)
{
    $headers = "From: " . SITE_NAME . " <" . ADMIN_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . ADMIN_EMAIL . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    return mail($para, $asunto, $mensaje, $headers); // Enviar el correo
}

// Validar si el correo electrónico tiene un formato correcto
function validarEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL); // Verificar si el email es válido
}

// Hashear la contraseña para almacenarla de manera segura
function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT); // Hashear la contraseña
}

// Verificar si la contraseña proporcionada coincide con el hash
function verificarPassword($password, $hash)
{
    return password_verify($password, $hash); // Verificar la contraseña
}

// Generar un token CSRF para proteger formularios
function generarCSRFToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Crear un token seguro
    }
    return $_SESSION['csrf_token']; // Devolver el token CSRF
}

// Validar el token CSRF para prevenir ataques CSRF
function validarCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token); // Verificar el token
}
?>
