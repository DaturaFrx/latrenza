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

// Procesar petición AJAX para "Agregar al Carrito"
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
    :root {
        --primary-color: #E4007C;
        --secondary-color: #FF69B4;
        --background-color: #FFF0F5;
        --text-color: #333;
        --card-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        background: linear-gradient(135deg, #FFF0F5 0%, #FFE4E1 100%);
        min-height: 100vh;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }

    .product-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 4px 15px rgba(228, 0, 124, 0.3);
        transition: var(--transition);
        margin-bottom: 1.5rem;
    }

    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(228, 0, 124, 0.4);
    }

    .product-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 500px;
        transition: var(--transition);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .image-section {
        position: relative;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .main-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
        cursor: pointer;
    }

    .main-image:hover {
        transform: scale(1.05);
    }

    .image-dots {
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.5rem;
        background: rgba(0, 0, 0, 0.5);
        padding: 0.5rem;
        border-radius: 20px;
        backdrop-filter: blur(10px);
    }

    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: var(--transition);
        border: none;
        outline: none;
    }

    .dot.active,
    .dot:hover {
        background: white;
        transform: scale(1.3);
    }

    .info-section {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .product-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }

    .product-price {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 2rem;
    }

    .quantity-section {
        margin-bottom: 2rem;
    }

    .quantity-label {
        display: block;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 50px;
        padding: 0.25rem;
        width: fit-content;
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .quantity-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .quantity-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: scale(1.1);
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .quantity-input {
        border: none;
        background: transparent;
        text-align: center;
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-color);
        width: 60px;
        outline: none;
    }

    .add-to-cart-btn {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 6px 20px rgba(228, 0, 124, 0.3);
        width: 100%;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(228, 0, 124, 0.4);
    }

    .login-section {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, #fff3f3, #ffe8e8);
        border-radius: 15px;
        border: 2px dashed var(--primary-color);
    }

    .login-message {
        color: #dc2626;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .login-btn {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(228, 0, 124, 0.3);
    }

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(228, 0, 124, 0.4);
    }

    .not-found {
        text-align: center;
        padding: 4rem 2rem;
        color: #666;
        font-size: 1.2rem;
    }

    .toast {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        padding: 1rem 1.5rem;
        border-radius: 15px;
        font-weight: 600;
        color: white;
        z-index: 1000;
        transform: translateX(400px);
        transition: var(--transition);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .toast.show {
        transform: translateX(0);
    }

    .toast.success {
        background: linear-gradient(135deg, #10b981, #34d399);
    }

    .toast.error {
        background: linear-gradient(135deg, #ef4444, #f87171);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product-card {
            grid-template-columns: 1fr;
            min-height: auto;
        }

        .info-section {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.5rem;
        }

        .product-price {
            font-size: 2rem;
        }

        .toast {
            bottom: 1rem;
            right: 1rem;
            left: 1rem;
            transform: translateY(100px);
        }

        .toast.show {
            transform: translateY(0);
        }
    }
</style>

<div class="product-container">
    <a href="javascript:history.back()" class="back-button">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 12H5m0 0l7 7m-7-7l7-7" />
        </svg>
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

        <div class="product-card">
            <!-- Image Section -->
            <div class="image-section">
                <img src="<?php echo htmlspecialchars($primary_image); ?>"
                    alt="<?php echo htmlspecialchars($prod['nombre_producto']); ?>"
                    class="main-image"
                    id="main-product-image"
                    onclick="openImageModal(this.src)"
                    onerror="this.src='<?php echo $default_image; ?>'">

                <?php if (count($fotos_urls) > 1): ?>
                    <div class="image-dots">
                        <?php foreach ($fotos_urls as $index => $foto): ?>
                            <?php if (!empty($foto)): ?>
                                <button class="dot <?php echo $index === 0 ? 'active' : ''; ?>"
                                    data-image="<?php echo htmlspecialchars($foto); ?>"
                                    onclick="changeProductImage(this, <?php echo $index; ?>)"></button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Info Section -->
            <div class="info-section">
                <div>
                    <h1 class="product-title">
                        <?php echo htmlspecialchars($prod['nombre_producto']); ?>
                    </h1>

                    <?php if (!empty($prod['descripcion'])): ?>
                        <p class="product-description">
                            <?php echo nl2br(htmlspecialchars($prod['descripcion'])); ?>
                        </p>
                    <?php endif; ?>

                    <div class="product-price">
                        $<?php echo number_format($prod['precio'], 2); ?>
                    </div>
                </div>

                <div>
                    <?php if ($usuarioId): ?>
                        <form id="add-to-cart-form" onsubmit="addToCart(event)">
                            <input type="hidden" name="id_producto" value="<?php echo $prod['id_producto']; ?>">
                            <input type="hidden" name="id_usuario" value="<?php echo $usuarioId; ?>">

                            <div class="quantity-section">
                                <label class="quantity-label">Cantidad:</label>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">−</button>
                                    <input type="number"
                                        id="cantidad"
                                        name="cantidad"
                                        value="1"
                                        min="1"
                                        max="99"
                                        class="quantity-input"
                                        readonly>
                                    <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                                </div>
                            </div>

                            <button type="submit" class="add-to-cart-btn">
                                Agregar al Carrito
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="login-section">
                            <p class="login-message">🔒 Inicia sesión para comprar este producto</p>
                            <a href="<?php echo SITE_URL; ?>../admin/login.php" class="login-btn">
                                Iniciar Sesión
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="not-found">
            <h2>Producto no encontrado</h2>
            <p>El producto que buscas no existe o ha sido eliminado.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    // Quantity control functions
    function changeQuantity(delta) {
        const input = document.getElementById('cantidad');
        const currentValue = parseInt(input.value) || 1;
        const newValue = Math.max(1, Math.min(99, currentValue + delta));
        input.value = newValue;

        // Update button states
        const minusBtn = document.querySelector('.quantity-btn:first-child');
        const plusBtn = document.querySelector('.quantity-btn:last-child');

        minusBtn.disabled = newValue <= 1;
        plusBtn.disabled = newValue >= 99;
    }

    // Image gallery functions
    function changeProductImage(button, index) {
        const newImageUrl = button.getAttribute('data-image');
        const mainImage = document.getElementById('main-product-image');
        const allDots = document.querySelectorAll('.dot');

        mainImage.src = newImageUrl;

        allDots.forEach(dot => dot.classList.remove('active'));
        button.classList.add('active');
    }

    // Modal for full-size image viewing
    function openImageModal(src) {
        const modal = document.createElement('div');
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            cursor: pointer;
        `;

        const img = document.createElement('img');
        img.src = src;
        img.style.cssText = `
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            border-radius: 10px;
        `;

        modal.appendChild(img);
        document.body.appendChild(modal);

        modal.addEventListener('click', () => {
            document.body.removeChild(modal);
        });
    }

    // Toast notification system
    function showToast(message, type) {
        const existingToast = document.querySelector('.toast');
        if (existingToast) {
            existingToast.remove();
        }

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        // Trigger animation
        setTimeout(() => toast.classList.add('show'), 100);

        // Auto remove
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    // Add to cart function
    <?php if ($usuarioId): ?>

        function addToCart(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const button = form.querySelector('.add-to-cart-btn');

            // Disable button and show loading state
            button.disabled = true;
            button.textContent = 'Agregando...';

            fetch('producto.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('¡Producto agregado al carrito!', 'success');
                    } else {
                        showToast(data.message || 'Error al agregar al carrito', 'error');
                    }
                })
                .catch(() => {
                    showToast('Error de conexión. Inténtalo de nuevo.', 'error');
                })
                .finally(() => {
                    // Reset button
                    button.disabled = false;
                    button.textContent = 'Agregar al Carrito';
                });
        }
    <?php endif; ?>

    // Initialize quantity controls
    document.addEventListener('DOMContentLoaded', function() {
        changeQuantity(0); // Set initial button states
    });
</script>

<?php include __DIR__ . '/../footer.php'; ?>