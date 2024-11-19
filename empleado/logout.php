<?php
session_start();
session_unset();
session_destroy();

// Define SITE_URL if it's not already defined
if (!defined('SITE_URL')) {
    define('SITE_URL', 'http://localhost/latrenza'); // Replace with your actual site URL
}

// Redirect to login page
header('Location: ' . SITE_URL . '/empleado/login_empleado.php');
exit;
?>