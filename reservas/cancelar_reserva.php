<?php
session_start();
include '../configuracion.php';

if (!isset($_SESSION['usuario']['id'])) {
    header('Location: ' . SITE_URL . '/login.php');
    exit;
}

$id_usuario = $_SESSION['usuario']['id'];
$id_reserva = isset($_GET['id_reserva']) ? (int) $_GET['id_reserva'] : 0;

if ($id_reserva > 0) {
    try {
        $conexion = Conexion::getInstance()->getConnection();
        // Asegurarse de que la reserva pertenezca al usuario
        $check = $conexion->prepare("
            SELECT COUNT(*) 
            FROM reservas 
            WHERE id_reserva = :id_reserva 
              AND id_cliente = :id_cliente
        ");
        $check->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
        $check->bindParam(':id_cliente', $id_usuario, PDO::PARAM_INT);
        $check->execute();

        if ($check->fetchColumn() > 0) {
            // Eliminar la reserva
            $del = $conexion->prepare("
                DELETE FROM reservas 
                WHERE id_reserva = :id_reserva
            ");
            $del->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
            $del->execute();
        }
    } catch (Exception $e) {
        // Aquí podrías registrar el error en un log
    }
}

// Redirigir de vuelta al perfil (o a donde corresponda)
header('Location: ' . SITE_URL . '../usuario/perfil.php');
exit;
