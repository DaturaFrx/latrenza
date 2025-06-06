<?php
require_once '../conexionBD.php';
include '../header.php';

$conexion = Conexion::getInstance()->getConnection();
$default_image = '../files/cot.jpg';

$sql_categorias = "SELECT * FROM categorias ORDER BY nombre_categoria";
$stmt_categorias = $conexion->query($sql_categorias);
?>

<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías de Productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/categorias.css">
</head>

<body>

    <div class="categorias-container">
        <?php while ($categoria = $stmt_categorias->fetch()): ?>
            <?php
            $sql_count = "SELECT COUNT(*) as total FROM productos WHERE categoria = :categoria_id";
            $stmt_count = $conexion->prepare($sql_count);
            $stmt_count->execute(['categoria_id' => $categoria['id_categoria']]);
            $total_productos = $stmt_count->fetch()['total'];

            $sql_productos = "
                    SELECT p.*, 
                           (SELECT f.url_foto FROM fotos f WHERE f.id_producto = p.id_producto LIMIT 1) AS url_foto 
                    FROM productos p 
                    WHERE p.categoria = :categoria_id 
                    ORDER BY p.nombre_producto";
            $stmt_productos = $conexion->prepare($sql_productos);
            $stmt_productos->execute(['categoria_id' => $categoria['id_categoria']]);
            $productos = $stmt_productos->fetchAll();
            ?>

            <section class="categoria-seccion">
                <div class="categoria-header">
                    <h2 class="categoria-titulo">
                        <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                        <span class="categoria-contador"><?php echo $total_productos; ?></span>
                    </h2>
                    <p class="categoria-descripcion"><?php echo htmlspecialchars($categoria['descripcion']); ?></p>
                </div>

                <div class="productos-de-categoria">
                    <?php if (count($productos) > 0): ?>
                        <?php foreach ($productos as $producto): ?>
                            <div class="producto-card">
                                <div class="producto-imagen-contenedor">
                                    <img src="<?php echo !empty($producto['url_foto']) ? $producto['url_foto'] : $default_image; ?>"
                                        alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                        class="producto-imagen">
                                </div>
                                <div class="producto-info">
                                    <div class="producto-nombre">
                                        <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                                    </div>
                                    <div class="producto-descripcion-corta">
                                        <?php echo htmlspecialchars($producto['descripcion']); ?>
                                    </div>
                                    <div class="producto-detalles-inferiores">
                                        <div class="producto-precio">
                                            $<?php echo number_format($producto['precio'], 2); ?>
                                        </div>
                                        <div class="producto-stock <?php
                                                                    if ($producto['stock'] <= 10) echo 'stock-bajo';
                                                                    elseif ($producto['stock'] <= 30) echo 'stock-medio';
                                                                    else echo 'stock-alto';
                                                                    ?>">
                                            Stock: <?php echo $producto['stock']; ?> unidades
                                        </div>
                                    </div>
                                    <div class="producto-boton-container">
                                        <a href="producto.php?id=<?php echo $producto['id_producto']; ?>" class="btn-ver-detalle">
                                            Ver detalle
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="sin-productos">No hay productos en esta categoría.</div>
                    <?php endif; ?>
                </div>
            </section>
        <?php endwhile; ?>
    </div>

</body>

</html>

<?php include '../footer.php'; ?>