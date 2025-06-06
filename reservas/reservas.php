<?php
// reservas.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../configuracion.php';

$usuarioId = $_SESSION['usuario']['id'] ?? null;
$error     = '';
$success   = '';

// Si no está autenticado, mostramos mensaje y bloqueamos la página
if (!$usuarioId) {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acceso Restringido - ' . SITE_NAME . '</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Acceso Restringido</h2>
            <p class="text-gray-700 mb-6">Debe iniciar sesión para acceder a esta sección.</p>
            <a
                href="' . SITE_URL . '/login.php"
                class="inline-block bg-pink-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-pink-700 transition"
            >
                Iniciar Sesión
            </a>
            <div class="mt-4">
                <a href="' . SITE_URL . '/home.php" class="text-sm text-gray-600 hover:underline">← Volver al inicio</a>
            </div>
        </div>
    </body>
    </html>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha       = trim($_POST['fecha_reserva'] ?? '');
    $hora        = trim($_POST['hora_reserva'] ?? '');
    $cantidad    = filter_var($_POST['cantidad_personas'] ?? '', FILTER_VALIDATE_INT);
    $comentarios = trim($_POST['comentarios'] ?? '');

    if (empty($fecha) || empty($hora) || !$cantidad) {
        $error = 'Por favor, complete todos los campos obligatorios y asegúrese de ingresar un número válido de personas.';
    } else {
        $fechaHoraReserva = $fecha . ' ' . $hora . ':00';

        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $stmt = $pdo->prepare("
                INSERT INTO reservas (id_cliente, fecha_reserva, cantidad_personas, comentarios)
                VALUES (:id_cliente, :fecha_reserva, :cantidad_personas, :comentarios)
            ");
            $stmt->execute([
                ':id_cliente'        => $usuarioId,
                ':fecha_reserva'     => $fechaHoraReserva,
                ':cantidad_personas' => $cantidad,
                ':comentarios'       => $comentarios
            ]);

            $success = 'Su reserva ha sido registrada correctamente.';
            $_SESSION['reserva_realizada'] = true;
        } catch (PDOException $e) {
            $error = 'Ocurrió un error al registrar la reserva: ' . $e->getMessage();
            error_log("Error al insertar en tabla reservas: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hacer Reserva - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Hacer Nueva Reserva</h2>

        <div class="bg-white p-8 rounded-lg shadow-md">
            <?php if ($error): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form action="reservas.php" method="POST" class="space-y-6">
                <!-- Fecha de reserva -->
                <div>
                    <label for="fecha_reserva" class="block text-sm font-medium text-gray-700">
                        Fecha de Reserva
                    </label>
                    <input
                        type="date"
                        id="fecha_reserva"
                        name="fecha_reserva"
                        required
                        class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                        value="<?php echo isset($_POST['fecha_reserva']) ? htmlspecialchars($_POST['fecha_reserva']) : ''; ?>">
                </div>

                <!-- Hora de reserva -->
                <div>
                    <label for="hora_reserva" class="block text-sm font-medium text-gray-700">
                        Hora de Reserva
                    </label>
                    <input
                        type="time"
                        id="hora_reserva"
                        name="hora_reserva"
                        required
                        class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                        value="<?php echo isset($_POST['hora_reserva']) ? htmlspecialchars($_POST['hora_reserva']) : ''; ?>">
                </div>

                <!-- Cantidad de personas -->
                <div>
                    <label for="cantidad_personas" class="block text-sm font-medium text-gray-700">
                        Cantidad de Personas
                    </label>
                    <input
                        type="number"
                        id="cantidad_personas"
                        name="cantidad_personas"
                        min="1"
                        required
                        class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                        value="<?php echo isset($_POST['cantidad_personas']) ? htmlspecialchars($_POST['cantidad_personas']) : ''; ?>">
                </div>

                <!-- Comentarios adicionales -->
                <div>
                    <label for="comentarios" class="block text-sm font-medium text-gray-700">
                        Comentarios (opcional)
                    </label>
                    <textarea
                        id="comentarios"
                        name="comentarios"
                        rows="3"
                        class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Por ejemplo: Mesa cerca de la ventana, por favor."><?php echo isset($_POST['comentarios']) ? htmlspecialchars($_POST['comentarios']) : ''; ?></textarea>
                </div>

                <!-- Botón de enviar -->
                <div>
                    <button
                        type="submit"
                        class="w-full bg-pink-600 text-white font-medium py-2 px-4 rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        Reservar Ahora
                    </button>
                </div>
            </form>

            <?php
            $backBtnClasses = 'inline-block text-sm font-medium px-4 py-2 rounded transition mt-4 ';
            if (!empty($_SESSION['reserva_realizada'])) {
                $backBtnClasses .= 'bg-green-100 text-green-800 hover:bg-green-200';
            } else {
                $backBtnClasses .= 'text-gray-600 hover:underline';
            }
            ?>
            <div class="text-center">
                <a href="javascript:history.go(-2)" class="<?php echo $backBtnClasses; ?>">
                    ← Volver atrás
                </a>
            </div>

            <?php
            // Si solo debe durar una vez:
            // unset($_SESSION['reserva_realizada']);
            ?>
        </div>
    </div>
</body>

</html>