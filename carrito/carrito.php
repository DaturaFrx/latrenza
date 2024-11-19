<?php
session_start();
include '../configuracion.php';
include_once('../header.php');

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    echo "Por favor, inicia sesión para ver tu carrito.";
    exit();
}

$id_usuario = $_SESSION['usuario']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conexion = Conexion::getInstance()->getConnection();

        if (isset($_POST['eliminar'])) {
            $id_producto = $_POST['id_producto'];
            $query = "DELETE FROM carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->execute();
        }

        if (isset($_POST['agregar'])) {
            $id_producto = $_POST['id_producto'];
            $query = "UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->execute();
        }

        if (isset($_POST['comprar'])) {
            header("Location: comprar.php");
            exit();
        }
    } catch (Exception $e) {
        echo "Error al procesar la acción: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" type="text/css" href="../css/carrito.css">
</head>

<body>

    <header>
        <h1>Bienvenido, <?php echo $_SESSION['usuario']['nombre']; ?>!</h1>
    </header>

    <div class="container">
        <?php
        try {
            $conexion = Conexion::getInstance()->getConnection();

            $query = "
            SELECT p.id_producto, p.nombre_producto, p.precio, c.cantidad FROM productos p
            INNER JOIN carrito c ON p.id_producto = c.id_producto
            WHERE c.id_usuario = :id_usuario
            ";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $productos = $stmt->fetchAll();

            if ($productos) {
                foreach ($productos as $producto) {
                    echo "
                    <div class='product'>
                        <p>{$producto['nombre_producto']} - {$producto['precio']} USD x {$producto['cantidad']}</p>
                        <div class='product-buttons'>
                            <form method='POST' action=''>
                                <input type='hidden' name='id_producto' value='{$producto['id_producto']}'>
                                <button type='submit' name='eliminar' class='btn btn-danger'>Eliminar</button>
                            </form>
                            <form method='POST' action=''>
                                <input type='hidden' name='id_producto' value='{$producto['id_producto']}'>
                                <button type='submit' name='agregar' class='btn btn-success'>Agregar</button>
                            </form>
                        </div>
                    </div>";
                }
            } else {
                echo "<p>No tienes productos en tu carrito.</p>";
            }
        } catch (Exception $e) {
            echo "Error al obtener productos: " . $e->getMessage();
        }
        ?>

        <form method="POST" action="">
            <button type="submit" name="comprar" class="btn btn-success">Avanzar a Comprar</button>
        </form>

        <a href="<?php echo SITE_URL; ?>/index.php" class="btn">Volver</a>
    </div>

</body>

</html>

<?php include('../footer.php'); ?>