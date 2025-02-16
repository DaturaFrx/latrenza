<?php
session_start();
include '../configuracion.php';
include_once('../header.php');

// Exchange rate constant
const EXCHANGE_RATE = 19; // 19 pesos per 1 USD

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    echo "<p class='text-red-500 mb-4'>Por favor, inicie sesión para proceder con la compra.</p>";
    echo "<a href='index.php' class='bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg shadow-md hover:shadow-xl transition'>Ir a la página de inicio</a>";
    exit();
}

$id_usuario = $_SESSION['usuario']['id'];

$descuentos_por_programa = [
    1 => 2,
    2 => 4,
    3 => 6,
    4 => 8,
    5 => 100,
    6 => 12,
    7 => 14,
    8 => 16,
    9 => 18,
    10 => 20,
    11 => 22,
    12 => 24,
    13 => 26,
    14 => 28,
    15 => 30,
    16 => 32,
    17 => 34,
    18 => 36,
    19 => 38,
    20 => 40,
    21 => 42,
    22 => 44,
    23 => 46,
    24 => 48,
    25 => 50
];

try {
    $conexion = Conexion::getInstance()->getConnection();

    $query_puntos = "SELECT puntos FROM Puntos_Acumulados WHERE id_usuario = :id_usuario";
    $stmt_puntos = $conexion->prepare($query_puntos);
    $stmt_puntos->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt_puntos->execute();
    $puntos_usuario = $stmt_puntos->fetchColumn() ?: 0;

    $query_descuento = "
    SELECT id_programa, puntos_requeridos
    FROM programa_lealtad
    WHERE puntos_requeridos <= :puntos
    ORDER BY puntos_requeridos DESC
    LIMIT 1
    ";
    $stmt_descuento = $conexion->prepare($query_descuento);
    $stmt_descuento->bindParam(':puntos', $puntos_usuario, PDO::PARAM_INT);
    $stmt_descuento->execute();
    $nivel_descuento = $stmt_descuento->fetch(PDO::FETCH_ASSOC);

    $descuento = 0;
    $id_programa_descuento = null;
    $porcentaje_descuento = 0;

    if ($nivel_descuento) {
        $id_programa_descuento = $nivel_descuento['id_programa'];

        $porcentaje_descuento = $descuentos_por_programa[$id_programa_descuento] ?? 0;
    }

} catch (Exception $e) {
    echo "<p class='text-red-500 mb-4'>Error al calcular descuento: " . $e->getMessage() . "</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conexion = Conexion::getInstance()->getConnection();
        $conexion->beginTransaction();

        $query = "DELETE FROM carrito WHERE id_usuario = :id_usuario";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        $query_update = "UPDATE Puntos_Acumulados 
                 SET puntos = puntos + 25 
                 WHERE id_usuario = :id_usuario";
        $stmt_update = $conexion->prepare($query_update);
        $stmt_update->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_update->execute();

        if ($stmt_update->rowCount() == 0) {
            $query_insert = "INSERT INTO Puntos_Acumulados (id_usuario, puntos) 
                     VALUES (:id_usuario, 25)";
            $stmt_insert = $conexion->prepare($query_insert);
            $stmt_insert->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_insert->execute();
        }

        if ($id_programa_descuento) {
            $query_programa_actual = "
            INSERT INTO Programa_Actual (id_usuario, id_programa) 
            VALUES (:id_usuario, :id_programa)
            ON DUPLICATE KEY UPDATE id_programa = :id_programa
            ";
            $stmt_programa_actual = $conexion->prepare($query_programa_actual);
            $stmt_programa_actual->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_programa_actual->bindParam(':id_programa', $id_programa_descuento, PDO::PARAM_INT);
            $stmt_programa_actual->execute();
        }

        $conexion->commit();

        echo "<p class='text-green-500 mb-4'>¡Gracias por realizar tu compra en La Trenza! Redirigiéndote a la página de inicio.</p>";
        echo "<script>setTimeout(function () { window.location.href = '" . SITE_URL . "'; }, 3000);</script>";
        exit();

    } catch (Exception $e) {
        $conexion->rollBack();
        echo "<p class='text-red-500 mb-4'>Error al procesar la eliminación: " . $e->getMessage() . "</p>";
    }
}

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
        $total = 0;
        foreach ($productos as $producto) {

            $precio_mxn = $producto['precio'] * EXCHANGE_RATE;
            $subtotal = $precio_mxn * $producto['cantidad'];
            $total += $subtotal;
        }

        $descuento = $total * ($porcentaje_descuento / 100);
        $total_con_descuento = $total - $descuento;
    } else {
        echo "<p class='text-red-500 mb-4'>Tu carrito está vacío. Agrega productos antes de proceder.</p>";
    }
} catch (Exception $e) {
    echo "<p class='text-red-500 mb-4'>Error al recuperar productos: " . $e->getMessage() . "</p>";
}
?>

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

        <div class="bg-white shadow-md rounded-lg p-6">
            <?php if ($productos): ?>
                <table class="w-full table-auto mb-6">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2">Producto</th>
                            <th class="px-4 py-2">Precio (MXN)</th>
                            <th class="px-4 py-2">Cantidad</th>
                            <th class="px-4 py-2">Total (MXN)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?php echo $producto['nombre_producto']; ?></td>
                                <td class="px-4 py-2"><?php echo number_format($producto['precio'] * EXCHANGE_RATE, 2); ?> MXN
                                </td>
                                <td class="px-4 py-2"><?php echo $producto['cantidad']; ?></td>
                                <td class="px-4 py-2"><?php
                                $subtotal = $producto['precio'] * EXCHANGE_RATE * $producto['cantidad'];
                                echo number_format($subtotal, 2); ?> MXN
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <div class="flex justify-between items-center">
                        <p class="text-lg font-semibold">Subtotal:</p>
                        <p class="text-lg"><?php echo number_format($total, 2); ?> MXN</p>
                    </div>
                    <?php if ($porcentaje_descuento > 0): ?>
                        <div class="flex justify-between items-center">
                            <p class="text-lg font-semibold text-green-600">Descuento (<?php echo $porcentaje_descuento; ?>%):
                            </p>
                            <p class="text-lg text-green-600">-<?php echo number_format($descuento, 2); ?> MXN</p>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-300">
                        <p class="text-xl font-bold">Total a pagar:</p>
                        <p class="text-xl font-bold text-red-600"><?php echo number_format($total_con_descuento, 2); ?> MXN
                        </p>
                    </div>
                </div>

                <form method="POST" action="">
                    <div class="flex space-x-4">
                        <button type="submit" name="comprar"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg shadow-md hover:shadow-xl transition w-full sm:w-auto">
                            Confirmar compra
                        </button>
                        <a href="carrito.php"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg shadow-md hover:shadow-xl transition w-full sm:w-auto">
                            Ir al carrito
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-red-500 mb-4">Tu carrito está vacío. Agrega productos antes de proceder.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>

<?php include('../footer.php'); ?>