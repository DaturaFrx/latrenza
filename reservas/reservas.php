<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../configuracion.php';

$usuarioId = $_SESSION['usuario']['id'] ?? null;
$error = '';
$success = '';

// Si no está autenticado
if (!$usuarioId) {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head><meta charset="UTF-8"><title>Acceso Restringido</title></head>
    <body><h2>Debe iniciar sesión</h2><a href="' . SITE_URL . '/login.php">Iniciar Sesión</a></body>
    </html>';
    exit;
}

// Obtener valores por GET (si se viene desde eventos.php)
$fecha_evento = $_GET['fecha'] ?? '';
$hora_evento = $_GET['hora'] ?? '';
$id_evento = $_GET['id_evento'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha       = trim($_POST['fecha_reserva'] ?? '');
    $hora        = trim($_POST['hora_reserva'] ?? '');
    $cantidad    = filter_var($_POST['cantidad_personas'] ?? '', FILTER_VALIDATE_INT);
    $comentarios = trim($_POST['comentarios'] ?? '');

    if (empty($fecha) || empty($hora) || !$cantidad) {
        $error = 'Complete todos los campos obligatorios.';
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

            $success = 'Reserva realizada con éxito.';
            $_SESSION['reserva_realizada'] = true;
        } catch (PDOException $e) {
            $error = 'Error al registrar: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Hacer Reserva</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-3xl font-bold text-center mb-6">Hacer Reserva</h2>

        <?php if ($error): ?>
            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="mb-4 bg-green-100 text-green-700 p-3 rounded"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="reservas.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha</label>
                <input type="date" name="fecha_reserva" required
                    value="<?php echo htmlspecialchars($_POST['fecha_reserva'] ?? $fecha_evento); ?>"
                    class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Hora</label>
                <input type="time" name="hora_reserva" required
                    value="<?php echo htmlspecialchars($_POST['hora_reserva'] ?? $hora_evento); ?>"
                    class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Cantidad de Personas</label>
                <input type="number" name="cantidad_personas" min="1" required
                    value="<?php echo htmlspecialchars($_POST['cantidad_personas'] ?? 1); ?>"
                    class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Comentarios</label>
                <textarea name="comentarios" class="w-full border rounded px-3 py-2"><?php echo htmlspecialchars($_POST['comentarios'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700">Reservar Ahora</button>
        </form>

        <div class="text-center mt-4">
            <a href="../eventos/eventos.php" class="text-sm text-gray-600 hover:underline">← Volver a eventos</a>
        </div>
    </div>
</body>

</html>