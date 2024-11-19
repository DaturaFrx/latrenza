<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar compra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.16/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">Finalizar compra</h1>

        <?php
        session_start();
        include '../configuracion.php';
        include_once('../header.php');

        if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
            echo "<p class='text-red-500 mb-4'>Por favor, inicie sesión para proceder con la compra.</p>";
            echo "<a href='index.php' class='bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg shadow-md hover:shadow-xl transition'>Ir a la página de inicio</a>";
            exit();
        }

        $id_usuario = $_SESSION['usuario']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Establecer la conexión a la base de datos
                $conexion = Conexion::getInstance()->getConnection();

                // Iniciar una transacción
                $conexion->beginTransaction();

                // Eliminar los productos del carrito
                $query = "DELETE FROM carrito WHERE id_usuario = :id_usuario";
                $stmt = $conexion->prepare($query);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();

                // Confirmar la transacción
                $conexion->commit();

                // Mensaje de éxito
                echo "<p class='text-green-500 mb-4'>¡Gracias por realizar tu compra en La Trenza! Redirigiéndote a la página de inicio.</p>";
                echo "<script>setTimeout(function() { window.location.href = '" . SITE_URL . "'; }, 3000);</script>";
                exit();

            } catch (Exception $e) {
                $conexion->rollBack();
                echo "<p class='text-red-500 mb-4'>Error al procesar la eliminación: " . $e->getMessage() . "</p>";
            }
        }
        ?>

        <div class="bg-white shadow-md rounded-lg p-6">
            <?php
            try {
                $conexion = Conexion::getInstance()->getConnection();

                $query = "
                SELECT p.id_producto, p.nombre_producto, p.precio, c.cantidad 
                FROM productos p
                INNER JOIN carrito c ON p.id_producto = c.id_producto
                WHERE c.id_usuario = :id_usuario
                ";
                $stmt = $conexion->prepare($query);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
                $productos = $stmt->fetchAll();

                if ($productos) {
                    echo "<table class='w-full table-auto mb-6'>
                            <thead>
                                <tr class='bg-gray-200'>
                                    <th class='px-4 py-2'>Producto</th>
                                    <th class='px-4 py-2'>Precio</th>
                                    <th class='px-4 py-2'>Cantidad</th>
                                    <th class='px-4 py-2'>Total</th>
                                </tr>
                            </thead>
                            <tbody>";
                    $total = 0;
                    foreach ($productos as $producto) {
                        $subtotal = $producto['precio'] * $producto['cantidad'];
                        $total += $subtotal;
                        echo "
                        <tr class='border-b'>
                            <td class='px-4 py-2'>{$producto['nombre_producto']}</td>
                            <td class='px-4 py-2'>{$producto['precio']} USD</td>
                            <td class='px-4 py-2'>{$producto['cantidad']}</td>
                            <td class='px-4 py-2'>{$subtotal} USD</td>
                        </tr>";
                    }
                    echo "</tbody></table>";

                    echo "<div class='flex justify-end mb-6'>
                            <p class='text-lg font-bold'>Total: {$total} USD</p>
                          </div>";

                    echo "<form method='POST' action=''>
                            <div class='flex space-x-4'>
                                <button type='submit' name='comprar' class='bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg shadow-md hover:shadow-xl transition w-full sm:w-auto'>Confirmar compra</button>
                                <a href='carrito.php' class='bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg shadow-md hover:shadow-xl transition w-full sm:w-auto'>Ir al carrito</a>
                            </div>
                          </form>";
                } else {
                    echo "<p class='text-red-500 mb-4'>Tu carrito está vacío. Agrega productos antes de proceder.</p>";
                }
            } catch (Exception $e) {
                echo "<p class='text-red-500 mb-4'>Error al recuperar productos: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php include('../footer.php'); ?>
