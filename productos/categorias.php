<?php
require_once '../conexionBD.php';

// Obtener la instancia de conexión PDO
$conexion = Conexion::getInstance()->getConnection();
$default_image = '../files/cot.jpg';

// Consulta para obtener las categorías
$sql_categorias = "SELECT * FROM categorias ORDER BY nombre_categoria";
$stmt_categorias = $conexion->query($sql_categorias);
?>

<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos por Categoría</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/categorias.css" rel="stylesheet">
</head>
<body>
    <?php include '../header.php'; ?>

    <div class="categorias-container">
        <?php while ($categoria = $stmt_categorias->fetch()): ?>
            <div class="categoria">
                <?php
                $sql_count = "SELECT COUNT(*) as total FROM productos WHERE categoria = :categoria_id";
                $stmt_count = $conexion->prepare($sql_count);
                $stmt_count->execute(['categoria_id' => $categoria['id_categoria']]);
                $total_productos = $stmt_count->fetch()['total'];
                ?>

                <h2 class="categoria-titulo">
                    <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                    <span class="categoria-contador"><?php echo $total_productos; ?></span>
                </h2>
                <p><?php echo htmlspecialchars($categoria['descripcion']); ?></p>
                
                <?php
                $sql_productos = "
                    SELECT p.*, 
                           (SELECT f.url_foto FROM fotos f WHERE f.id_producto = p.id_producto LIMIT 1) AS url_foto 
                    FROM productos p 
                    WHERE p.categoria = :categoria_id 
                    ORDER BY p.nombre_producto";
                $stmt_productos = $conexion->prepare($sql_productos);
                $stmt_productos->execute(['categoria_id' => $categoria['id_categoria']]);
                ?>

                <?php while ($producto = $stmt_productos->fetch()): ?>
                    <div class="producto">
                        <img 
                            src="<?php echo !empty($producto['url_foto']) ? $producto['url_foto'] : $default_image; ?>" 
                            alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                            class="producto-imagen"
                        >
                        <div class="producto-detalles">
                            <div class="producto-nombre">
                                <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                            </div>
                            <div class="producto-descripcion">
                                <?php echo htmlspecialchars($producto['descripcion']); ?>
                            </div>
                            <div class="producto-precio">
                                $<?php echo number_format($producto['precio'], 2); ?>
                            </div>
                            <div class="producto-stock <?php
                                if ($producto['stock'] <= 10) echo 'stock-bajo';
                                elseif ($producto['stock'] <= 30) echo 'stock-medio';
                                else echo 'stock-alto';
                            ?>">
                                Stock: <span class="badge"><?php echo $producto['stock']; ?> unidades</span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <?php include '../footer.php'; ?>
</body>
</html>
