<?php
require_once '../conexionBD.php';
require_once '../header.php';

$conexion = Conexion::getInstance()->getConnection();

// Comentarios
$sql_comments = "SELECT c.*, p.nombre_producto as producto_nombre, u.nombre as usuario_nombre 
                 FROM comentarios c 
                 JOIN productos p ON c.id_producto = p.id_producto 
                 JOIN usuarios u ON c.id_usuario = u.id_usuario 
                 ORDER BY fecha_comentario DESC";
$stmt_comments = $conexion->prepare($sql_comments);
$stmt_comments->execute();
$result_comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

// Blotenines
$sql_bulletins = "SELECT * FROM boletines WHERE estado = 'activo' ORDER BY fecha_publicacion DESC";
$stmt_bulletins = $conexion->prepare($sql_bulletins);
$stmt_bulletins->execute();
$result_bulletins = $stmt_bulletins->fetchAll(PDO::FETCH_ASSOC);


// Blog
$sql_blog = "SELECT * FROM blog ORDER BY creado_en DESC";
$stmt_blog = $conexion->prepare($sql_blog);
$stmt_blog->execute();
$result_blog = $stmt_blog->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bulletin-card {
            transition: transform 0.2s;
            border-left: 4px solid #0d6efd;
        }

        .bulletin-card:hover {
            transform: translateY(-5px);
        }

        .comment-card {
            border-left: 4px solid #198754;
        }

        .rating {
            color: #ffd700;
        }

        .date-badge {
            background-color: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .product-badge {
            background-color: #e9ecef;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            color: #495057;
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <!-- Pestañas -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="tab1-tab" data-bs-toggle="tab" href="#tab1" role="tab"
                    aria-controls="tab1" aria-selected="true">Blog</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="opiniones-tab" data-bs-toggle="tab" href="#opiniones" role="tab"
                    aria-controls="opiniones" aria-selected="false">Opiniones de Nuestros Clientes</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="boletines-tab" data-bs-toggle="tab" href="#boletines" role="tab"
                    aria-controls="boletines" aria-selected="false">Noticias y Promociones</a>
            </li>
        </ul>
        <div class="tab-content mt-4" id="myTabContent">
            <!-- Pestaña 1: Blog -->
            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                <h2 class="mb-4">
                    <i class="fas fa-blog text-primary"></i>
                    Blog
                </h2>
                <div class="row">
                    <?php foreach ($result_blog as $entrada): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 rounded-3">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">
                                        <?php echo htmlspecialchars($entrada['titulo']); ?>
                                    </h5>
                                    <p class="card-text mb-3">
                                        <?php echo nl2br(htmlspecialchars($entrada['contenido'])); ?>
                                    </p>

                                    <!-- Check if imagen is set (mediumblob) -->
                                    <?php if (!empty($entrada['imagen'])): ?>
                                        <div class="image-container mb-3">
                                            <a href="data:image/jpeg;base64,<?php echo base64_encode($entrada['imagen']); ?>"
                                                target="_blank">
                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($entrada['imagen']); ?>"
                                                    class="card-img-top rounded-3" alt="Imagen del blog"
                                                    style="width: 100%; height: auto; max-height: 200px; object-fit: contain;">
                                            </a>
                                        </div>
                                        <!-- Check if url-img is set (external URL) -->
                                    <?php elseif (!empty($entrada['url-img'])): ?>
                                        <div class="image-container mb-3">
                                            <a href="<?php echo htmlspecialchars($entrada['url-img']); ?>" target="_blank">
                                                <img src="<?php echo htmlspecialchars($entrada['url-img']); ?>"
                                                    class="card-img-top rounded-3" alt="Imagen externa"
                                                    style="width: 100%; height: auto; max-height: 200px; object-fit: contain;">
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="date-badge text-muted">
                                            <i class="far fa-calendar-alt"></i>
                                            <?php echo date('d M Y', strtotime($entrada['creado_en'])); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Add some custom styles -->
            <style>
                .card {
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                .card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
                }

                .card-title {
                    font-size: 1.25rem;
                    font-weight: bold;
                }

                .card-text {
                    font-size: 1rem;
                    color: #555;
                }

                .date-badge {
                    font-size: 0.875rem;
                    color: #777;
                }

                .image-container img {
                    border-radius: 10px;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                    cursor: pointer;
                }

                /* Responsive Layout */
                @media (max-width: 768px) {
                    .col-md-6 {
                        flex: 0 0 100%;
                        max-width: 100%;
                    }
                }
            </style>

            <!-- Pestaña 2: Opiniones de Nuestros Clientes -->
            <div class="tab-pane fade" id="opiniones" role="tabpanel" aria-labelledby="opiniones-tab">
                <h2 class="mb-4">
                    <i class="fas fa-comments text-success"></i>
                    Opiniones de Nuestros Clientes
                </h2>
                <div class="row">
                    <?php foreach ($result_comments as $comentario): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card comment-card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-user-circle text-secondary"></i>
                                            <?php echo htmlspecialchars($comentario['usuario_nombre'] ?? 'Usuario'); ?>
                                        </h5>
                                        <div class="rating">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $comentario['calificacion'] ?
                                                    '<i class="fas fa-star"></i>' :
                                                    '<i class="far fa-star"></i>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars($comentario['comentario']); ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="date-badge">
                                            <i class="far fa-clock"></i>
                                            <?php echo date('d M Y', strtotime($comentario['fecha_comentario'])); ?>
                                        </span>
                                        <span class="product-badge">
                                            <i class="fas fa-tag"></i>
                                            <?php echo htmlspecialchars($comentario['producto_nombre'] ?? 'Producto'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Pestaña 3: Noticias y Promociones -->
            <div class="tab-pane fade" id="boletines" role="tabpanel" aria-labelledby="boletines-tab">
                <h2 class="mb-4">
                    <i class="fas fa-newspaper text-primary"></i>
                    Noticias y Promociones
                </h2>
                <div class="row">
                    <?php foreach ($result_bulletins as $boletin): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card bulletin-card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo htmlspecialchars($boletin['titulo']); ?>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars($boletin['contenido']); ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="date-badge">
                                            <i class="far fa-calendar-alt"></i>
                                            <?php echo date('d M Y', strtotime($boletin['fecha_publicacion'])); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php require_once '../footer.php'; ?>