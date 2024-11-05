<?php
require_once('../configuracion.php');

session_start(); // Start the session to store user data

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );

        $correo = filter_var($_POST['correo_electronico'], FILTER_SANITIZE_EMAIL);
        $contrasena = $_POST['contrasena'];

        if (empty($correo) || empty($contrasena)) {
            throw new Exception('Por favor, complete todos los campos.');
        }

        // Check user credentials in the usuarios table
        $stmt = $pdo->prepare("SELECT id_usuario, nombre, correo_electronico, contrasena, telefono, direccion FROM usuarios WHERE correo_electronico = ? LIMIT 1");
        $stmt->execute([$correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $contrasena === $user['contrasena']) {
            // Store user info in session variables
            $_SESSION['user_id'] = $user['id_usuario']; // Changed from admin_id to user_id
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_email'] = $user['correo_electronico'];
            $_SESSION['user_telefono'] = $user['telefono'];
            $_SESSION['user_direccion'] = $user['direccion'];

            error_log("User login successful: {$user['correo_electronico']} at " . date('Y-m-d H:i:s'));

            // Redirect to the homepage or user dashboard
            header('Location: ' . SITE_URL); // Change to the desired redirect page
            exit;
        } else {
            throw new Exception('Contraseña incorrecta.');
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Login error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-bold text-gray-900">
                <?php echo SITE_NAME; ?>
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Iniciar Sesión
            </p>
        </div>

        <div class="bg-white py-8 px-6 shadow rounded-lg">
            <?php if ($error): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form class="space-y-6" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div>
                    <label for="correo_electronico" class="block text-sm font-medium text-gray-700">
                        Correo Electrónico
                    </label>
                    <div class="mt-1">
                        <input id="correo_electronico" name="correo_electronico" type="email" autocomplete="email"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                            value="<?php echo isset($_POST['correo_electronico']) ? htmlspecialchars($_POST['correo_electronico']) : ''; ?>">
                    </div>
                </div>

                <div>
                    <label for="contrasena" class="block text-sm font-medium text-gray-700">
                        Contraseña
                    </label>
                    <div class="mt-1">
                        <input id="contrasena" name="contrasena" type="password" autocomplete="current-password"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        Iniciar Sesión
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="<?php echo SITE_URL; ?>" class="text-sm text-gray-600 hover:text-gray-900">
                ← Volver al sitio principal
            </a>
        </div>
    </div>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        document.querySelector('form').addEventListener('submit', function (e) {
            const correo = document.getElementById('correo_electronico').value.trim();
            const contrasena = document.getElementById('contrasena').value;

            if (!correo || !contrasena) {
                e.preventDefault();
                alert('Por favor, complete todos los campos.');
                return false;
            }

            if (!correo.includes('@')) {
                e.preventDefault();
                alert('Por favor, ingrese un correo electrónico válido.');
                return false;
            }
        });
    </script>
</body>

</html>