<?php
require_once(__DIR__ . '/../configuracion.php');

$errores = [];
$exito = false;

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación de campos obligatorios
    if (empty($_POST['nombre'])) {
        $errores[] = "El nombre es obligatorio";
    }
    if (empty($_POST['correo_electronico'])) {
        $errores[] = "El correo electrónico es obligatorio";
    } elseif (!filter_var($_POST['correo_electronico'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido";
    }
    if (empty($_POST['contrasena'])) {
        $errores[] = "La contraseña es obligatoria";
    }

    // Si no hay errores, proceder con el registro
    if (empty($errores)) {
        try {
            // Hashing de la contraseña usando password_hash() con bcrypt
            $password_hash = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // bcrypt es el predeterminado

            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo_electronico, contrasena, telefono, direccion) 
                                 VALUES (:nombre, :correo_electronico, :contrasena, :telefono, :direccion)");

            $stmt->execute([
                ':nombre' => $_POST['nombre'],
                ':correo_electronico' => $_POST['correo_electronico'],
                ':contrasena' => $password_hash,
                ':telefono' => $_POST['telefono'] ?? null,
                ':direccion' => $_POST['direccion'] ?? null
            ]);

            $exito = true;
            // Redirigir al login después del registro exitoso
            header("Location: " . SITE_URL . "/admin/login.php?registro=exitoso");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Código de error para duplicate entry
                $errores[] = "Este correo electrónico ya está registrado";
            } else {
                $errores[] = "Error al registrar el usuario";
            }
        }
    }
}

$pageTitle = "Registro de Usuario";
include(__DIR__ . '/../header.php');
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-center text-amber-800">Registro de Usuario</h2>

        <?php if (!empty($errores)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php foreach ($errores as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                <input type="text" name="nombre" id="nombre" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                    value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
            </div>

            <div>
                <label for="correo_electronico" class="block text-sm font-medium text-gray-700">Correo Electrónico
                    *</label>
                <input type="email" name="correo_electronico" id="correo_electronico" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                    value="<?php echo isset($_POST['correo_electronico']) ? htmlspecialchars($_POST['correo_electronico']) : ''; ?>">
            </div>

            <div>
                <label for="contrasena" class="block text-sm font-medium text-gray-700">Contraseña *</label>
                <input type="password" name="contrasena" id="contrasena" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="tel" name="telefono" id="telefono"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                    value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>">
            </div>

            <div>
                <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                <textarea name="direccion" id="direccion"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                    rows="3"><?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?></textarea>
            </div>

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-800 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    Registrarse
                </button>
            </div>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            ¿Ya tienes una cuenta?
            <a href="<?php echo SITE_URL; ?>/admin/login.php" class="font-medium text-amber-800 hover:text-amber-700">
                Inicia sesión aquí
            </a>
        </p>
    </div>
</div>

<?php include(__DIR__ . '/../footer.php'); ?>