<?php
session_start();
include '../conexionBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_COOKIE['user_id'])) {
    $id_usuario = $_COOKIE['user_id'];
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];

    // Check if the product is already in the cart
    $stmt = $conn->prepare("SELECT * FROM carrito WHERE id_usuario = ? AND id_producto = ?");
    $stmt->bind_param("ii", $id_usuario, $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if the product already exists in the cart
        $stmt = $conn->prepare("UPDATE carrito SET cantidad = cantidad + ? WHERE id_usuario = ? AND id_producto = ?");
        $stmt->bind_param("iii", $cantidad, $id_usuario, $id_producto);
        $stmt->execute();
    } else {
        // Insert new item into the cart
        $stmt = $conn->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad, fecha_agregado) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iii", $id_usuario, $id_producto, $cantidad);
        $stmt->execute();
    }

    // Return a success response
    echo json_encode(['success' => true]);
} else {
    // Return an error response
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
}
?>