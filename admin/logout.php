<?php
// admin/logout.php
require_once('../configuracion.php');

// Log admin logout if the session is active
if (isset($_SESSION['admin_email'])) {
    error_log("Admin logout: {$_SESSION['admin_email']} at " . date('Y-m-d H:i:s'));
}

// Log user logout if a user session is active
if (isset($_SESSION['usuario'])) {
    error_log("User logout: {$_SESSION['usuario']['nombre']} at " . date('Y-m-d H:i:s'));
}

// Destroy the session for both admin and regular users
session_destroy();

// Clear the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to the home page or login page
header('Location: ' . SITE_URL . '/admin/login.php');
exit;
?>