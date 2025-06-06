<?php
// =======================================
// producto.php
// =======================================

// Iniciar sesión (si no está ya iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la base de datos
require_once __DIR__ . '/../conexionBD.php';

$default_image = 'files/cot.jpg';
$id_producto   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$usuarioId     = $_SESSION['usuario']['id'] ?? null; // ID de usuario (o null si no hay sesión)

if ($id_producto > 0) {
    $producto = getProductoById($id_producto);
} else {
    $producto = null;
}

function getProductoById($id_producto)
{
    $conn = Conexion::getInstance()->getConnection();

    $query = '
        SELECT 
            p.id_producto, 
            p.nombre_producto, 
            p.descripcion, 
            p.precio, 
            f.url_foto
        FROM productos p
        LEFT JOIN fotos f ON p.id_producto = f.id_producto
        WHERE p.id_producto = :id_producto
    ';

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Procesar petición AJAX para “Agregar al Carrito”
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'], $_POST['id_usuario'], $_POST['cantidad'])) {
    // Si no hay usuario en sesión, devolvemos error inmediato
    if (!$usuarioId) {
        echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión para agregar productos al carrito.']);
        exit;
    }

    $id_producto_post = (int) $_POST['id_producto'];
    $id_usuario_post  = (int) $_POST['id_usuario'];
    $cantidad         = (int) $_POST['cantidad'];

    try {
        $conn = Conexion::getInstance()->getConnection();

        // Verificar si el producto ya existe en el carrito del usuario
        $checkQuery = '
            SELECT * 
            FROM carrito 
            WHERE id_usuario = :id_usuario 
              AND id_producto = :id_producto
        ';
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(':id_usuario', $id_usuario_post, PDO::PARAM_INT);
        $checkStmt->bindParam(':id_producto', $id_producto_post, PDO::PARAM_INT);
        $checkStmt->execute();

        $cartItem = $checkStmt->fetch();

        if ($cartItem) {
            // Actualizar cantidad existente
            $updateQuery = '
                UPDATE carrito 
                   SET cantidad = cantidad + :cantidad 
                 WHERE id_usuario = :id_usuario 
                   AND id_producto = :id_producto
            ';
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindParam(':id_usuario', $id_usuario_post, PDO::PARAM_INT);
            $updateStmt->bindParam(':id_producto', $id_producto_post, PDO::PARAM_INT);
            $updateStmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $updateStmt->execute();
        } else {
            // Insertar nuevo registro en carrito
            $insertQuery = '
                INSERT INTO carrito (id_usuario, id_producto, cantidad, fecha_agregado) 
                VALUES (:id_usuario, :id_producto, :cantidad, NOW())
            ';
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bindParam(':id_usuario', $id_usuario_post, PDO::PARAM_INT);
            $insertStmt->bindParam(':id_producto', $id_producto_post, PDO::PARAM_INT);
            $insertStmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $insertStmt->execute();
        }

        echo json_encode(['success' => true]);
        exit();
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al agregar al carrito: ' . $e->getMessage()
        ]);
        exit();
    }
}

include __DIR__ . '/../header.php';
?>

<style>
    .toast {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        color: white;
        background-color: #333;
        opacity: 0.9;
        z-index: 9999;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .toast.success {
        background-color: #28a745;
    }

    .toast.error {
        background-color: #dc3545;
    }
</style>

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="section-header text-3xl font-bold text-center mb-12 reveal text-black">
            Detalles del Producto
        </h2>

        <a href="javascript:history.back()"
            class="inline-block mb-6 py-2 px-4 bg-pink-600 text-white font-bold rounded-full hover:bg-pink-700 transition-colors duration-300">
            Volver
        </a>

        <?php if ($producto && !empty($producto)): ?>
            <?php
            $prod = $producto[0];
            $fotos_urls = [];
            foreach ($producto as $prodItem) {
                if (!empty($prodItem['url_foto'])) {
                    $fotos_urls[] = $prodItem['url_foto'];
                }
            }
            $primary_image = !empty($fotos_urls[0]) ? $fotos_urls[0] : $default_image;
            ?>

            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden text-center hover:shadow-lg transition-shadow duration-300">
                <div class="h-48 overflow-hidden relative group">
                    <a href="<?php echo htmlspecialchars($primary_image); ?>" target="_blank" class="block h-full">
                        <img src="<?php echo htmlspecialchars($primary_image); ?>"
                            alt="<?php echo htmlspecialchars($prod['nombre_producto']); ?>"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                            loading="lazy" onerror="this.src='<?php echo $default_image; ?>'">
                    </a>

                    <?php if (count($fotos_urls) > 1): ?>
                        <div class="absolute bottom-2 left-0 right-0 flex justify-center gap-2 z-10">
                            <?php foreach ($fotos_urls as $index => $foto): ?>
                                <?php if (!empty($foto)): ?>
                                    <button class="w-2 h-2 rounded-full bg-white opacity-70 hover:opacity-100 transition-opacity duration-200
                                               <?php echo $index === 0 ? 'opacity-100' : ''; ?>"
                                        data-image="<?php echo htmlspecialchars($foto); ?>"
                                        data-default="<?php echo htmlspecialchars($default_image); ?>"
                                        onclick="changeProductImage(this)"
                                        aria-label="Ver imagen <?php echo $index + 1; ?>"></button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 text-black">
                        <?php echo htmlspecialchars($prod['nombre_producto']); ?>
                    </h3>

                    <?php if (!empty($prod['descripcion'])): ?>
                        <p class="text-gray-600 mb-4 line-clamp-2">
                            <?php echo htmlspecialchars($prod['descripcion']); ?>
                        </p>
                    <?php endif; ?>

                    <p class="text-lg font-bold text-pink-600 mb-4">
                        $<?php echo number_format($prod['precio'], 2); ?>
                    </p>

                    <!-- Si no hay usuario en sesión, mostramos mensaje en lugar del formulario -->
                    <?php if ($usuarioId): ?>
                        <form id="add-to-cart-form" action="producto.php" method="POST">
                            <input type="hidden" name="id_producto" value="<?php echo $prod['id_producto']; ?>">
                            <input type="hidden" name="id_usuario" value="<?php echo $usuarioId; ?>">

                            <label for="cantidad" class="text-gray-600 font-semibold">Cantidad:</label>
                            <input type="number" id="cantidad" name="cantidad" min="1" value="1"
                                class="border rounded py-2 px-3 mb-4 w-full" required>

                            <button type="submit"
                                class="bg-pink-600 text-white py-2 px-4 rounded-full hover:bg-pink-700 transition-colors duration-300">
                                Agregar al Carrito
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="mb-4 text-center">
                            <p class="text-red-600 font-medium mb-2">Debes iniciar sesión para poder comprar este producto.</p>
                            <a href="<?php echo SITE_URL; ?>../admin/login.php"
                                class="inline-block bg-pink-600 text-white px-4 py-2 rounded-full hover:bg-pink-700 transition-colors duration-300">
                                Iniciar Sesión
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="col-span-3 text-center text-gray-600 py-8">
                <p>No se encontró el producto.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    // Solo se adjunta el listener de AJAX si el usuario está registrado
    <?php if ($usuarioId): ?>
        document.getElementById('add-to-cart-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('producto.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Producto agregado al carrito!', 'success');
                    } else {
                        showToast('Error al agregar al carrito: ' + data.message, 'error');
                    }
                })
                .catch(() => {
                    showToast('Error de conexión.', 'error');
                });
        });

        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.classList.add('toast', type);
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    <?php endif; ?>

    function changeProductImage(button) {
        const newImageUrl = button.getAttribute('data-image');
        const defaultImage = button.getAttribute('data-default');
        const card = button.closest('.product-card');
        const mainImage = card.querySelector('img');

        mainImage.src = newImageUrl;
        mainImage.onerror = function() {
            this.src = defaultImage;
        };

        card.querySelectorAll('button').forEach(dot => dot.classList.remove('opacity-100'));
        button.classList.add('opacity-100');
    }
</script>

<?php include __DIR__ . '/../footer.php'; ?>