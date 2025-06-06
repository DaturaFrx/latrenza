<?php
require_once '../conexionBD.php';

// Obtener la instancia de conexión PDO
$conexion = Conexion::getInstance()->getConnection();
$default_image = 'files/cot.jpg';

// 1. Obtenemos todas las categorías ordenadas por nombre
$sqlCategorias = "SELECT * FROM categorias ORDER BY nombre_categoria";
$stmtCategorias = $conexion->query($sqlCategorias);
?>

<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Categorías de Productos</title>
    <!-- Font Awesome para íconos -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet" />
    <!-- Mismo CSS que usa productos.php -->
    <link href="../css/categorias.css" rel="stylesheet" />
</head>

<body>
    <?php include '../header.php'; ?>

    <main class="productos-container">
        <h1 class="page-title">Categorías de Productos</h1>

        <?php while ($categoria = $stmtCategorias->fetch()): ?>
            <?php
            // 1.a. Contamos cuántos productos tiene esta categoría
            $sqlCount = "SELECT COUNT(*) AS total FROM productos WHERE categoria = :catId";
            $stmtCount = $conexion->prepare($sqlCount);
            $stmtCount->execute(['catId' => $categoria['id_categoria']]);
            $totalProductos = $stmtCount->fetchColumn();
            ?>

            <div class="categoria-card">
                <div class="categoria-header">
                    <h2 class="categoria-titulo">
                        <?php echo htmlspecialchars($categoria['nombre_categoria'], ENT_QUOTES, 'UTF-8'); ?>
                        <span class="categoria-badge"><?php echo $totalProductos; ?></span>
                    </h2>
                    <?php if (!empty($categoria['descripcion'])): ?>
                        <p class="categoria-descripcion">
                            <?php echo nl2br(htmlspecialchars($categoria['descripcion'], ENT_QUOTES, 'UTF-8')); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <?php
                // 1.b. Consultamos los productos de esta categoría
                $sqlProductos = "
                    SELECT 
                        p.id_producto,
                        p.nombre_producto,
                        p.descripcion,
                        p.precio,
                        p.stock,
                        COALESCE(
                          (SELECT f.url_foto 
                           FROM fotos f 
                           WHERE f.id_producto = p.id_producto 
                           ORDER BY f.id_foto ASC 
                           LIMIT 1),
                          :imagenDefecto
                        ) AS url_foto
                    FROM productos p 
                    WHERE p.categoria = :catId
                    ORDER BY p.nombre_producto
                ";
                $stmtProductos = $conexion->prepare($sqlProductos);
                $stmtProductos->execute([
                    'catId'          => $categoria['id_categoria'],
                    'imagenDefecto'  => $default_image
                ]);
                ?>

                <div class="productos-row">
                    <?php if ($stmtProductos->rowCount() === 0): ?>
                        <div class="sin-productos">No hay productos en esta categoría.</div>
                    <?php else: ?>
                        <?php while ($prod = $stmtProductos->fetch()): ?>
                            <div class="producto-card">
                                <div class="producto-imagen-contenedor">
                                    <img
                                        src="<?php echo htmlspecialchars($prod['url_foto'], ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="<?php echo htmlspecialchars($prod['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?>"
                                        class="producto-imagen" />
                                </div>
                                <div class="producto-info">
                                    <h3 class="producto-nombre">
                                        <?php echo htmlspecialchars($prod['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?>
                                    </h3>
                                    <p class="producto-descripcion-corta">
                                        <?php echo htmlspecialchars($prod['descripcion'], ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                    <div class="producto-detalles-inferiores">
                                        <span class="producto-precio">
                                            $<?php echo number_format($prod['precio'], 2); ?>
                                        </span>
                                        <span class="producto-stock 
                                            <?php
                                            if ($prod['stock'] <= 10) echo 'stock-bajo';
                                            elseif ($prod['stock'] <= 30) echo 'stock-medio';
                                            else echo 'stock-alto';
                                            ?>
                                        ">
                                            Stock: <?php echo (int)$prod['stock']; ?>
                                        </span>
                                    </div>
                                    <div class="producto-boton-container">
                                        <a
                                            href="producto.php?id=<?php echo $prod['id_producto']; ?>"
                                            class="btn-ver-detalle">
                                            Ver detalle
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>

    </main>

    <?php include '../footer.php'; ?>
</body>

</html>