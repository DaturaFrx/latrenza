<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../configuracion.php');

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
        $recordar = isset($_POST['recordar']) ? true : false;

        if (empty($correo) || empty($contrasena)) {
            throw new Exception('Por favor, complete todos los campos.');
        }

        // Retrieve the user data from the database
        $stmt = $pdo->prepare("SELECT id_usuario, nombre, correo_electronico, contrasena, telefono, direccion FROM usuarios WHERE correo_electronico = ? LIMIT 1");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists and verify the password
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            // Password is correct, set session
            $_SESSION['usuario'] = array(
                'id' => $usuario['id_usuario'],
                'nombre' => $usuario['nombre'],
                'email' => $usuario['correo_electronico'],
                'telefono' => $usuario['telefono'],
                'direccion' => $usuario['direccion']
            );

            // If "remember me" is checked, create a persistent session
            if ($recordar) {
                crearSesionPersistente($usuario['id_usuario']);
            }

            error_log("Inicio de sesión exitoso: {$usuario['correo_electronico']} en " . date('Y-m-d H:i:s'));
            header('Location: ' . SITE_URL);
            exit;
        } else {
            throw new Exception('Credenciales incorrectas.');
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Error de inicio de sesión: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - <?php echo SITE_NAME; ?></title>
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

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="recordar" name="recordar" type="checkbox"
                            class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                        <label for="recordar" class="ml-2 block text-sm text-gray-900">
                            Recordar mi sesión
                        </label>
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