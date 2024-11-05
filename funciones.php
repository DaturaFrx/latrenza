<?php
// funciones.php

function limpiarInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getProductosDestacados()
{
    $db = Conexion::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM productos LIMIT 4");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCategorias()
{
    $db = Conexion::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM categorias");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function isLoggedIn()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['usuario']) &&
        isset($_SESSION['usuario']['id']) &&
        !empty($_SESSION['usuario']['id']);
}

function getCurrentUser()
{
    if (isLoggedIn()) {
        return $_SESSION['usuario'];
    }
    return null;
}

function isAdmin()
{
    if (isLoggedIn() && isset($_SESSION['usuario']['rol'])) {
        return $_SESSION['usuario']['rol'] === 'admin';
    }
    return false;
}

function requireLogin()
{
    if (!isLoggedIn()) {
        redirect('/admin/login.php');
    }
}

function requireAdmin()
{
    if (!isAdmin()) {
        redirect('/index.php');
    }
}

function logOut()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = array();
    session_destroy();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    redirect('/index.php');
}

function redirect($url)
{
    header("Location: " . SITE_URL . $url);
    exit();
}

function formatearPrecio($precio)
{
    return number_format($precio, 2, '.', ',');
}

function getCarritoCount()
{
    return isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
}

function enviarEmail($para, $asunto, $mensaje)
{
    $headers = "From: " . SITE_NAME . " <" . ADMIN_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . ADMIN_EMAIL . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    return mail($para, $asunto, $mensaje, $headers);
}

function validarEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function verificarPassword($password, $hash)
{
    return password_verify($password, $hash);
}

function generarCSRFToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validarCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}