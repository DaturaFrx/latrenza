<?php
session_start();
require_once('../configuracion.php');

$error = '';
$firstAdmin = false;

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

        // Check if an admin already exists
        $stmt = $pdo->prepare("SELECT id_empleado FROM empleados WHERE puesto = 'admin' LIMIT 1");
        $stmt->execute();
        $adminExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$adminExists) {
            // If no admin exists, it's the first admin registration
            $firstAdmin = true;
            // Hash the password for the first admin
            $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);

            // Insert the first admin into the empleados table
            $stmt = $pdo->prepare("INSERT INTO empleados (nombre, correo_electronico, puesto, contrasena, fecha_contratacion, salario) 
                VALUES (?, ?, ?, ?, NOW(), ?)");
            $stmt->execute(['Admin', $correo, 'admin', $hashedPassword, 5000]);

            // Log the first admin in
            $_SESSION['empleado'] = array(
                'id' => $pdo->lastInsertId(),
                'nombre' => 'Admin',
                'correo_electronico' => $correo,
                'puesto' => 'admin'
            );

            header('Location: ' . SITE_URL . '/empleado/dashboard.php');
            exit;
        } else {
            // If admin exists, proceed with login
            $stmt = $pdo->prepare("SELECT id_empleado, nombre, correo_electronico, contrasena, puesto FROM empleados WHERE correo_electronico = ? AND puesto = 'admin' LIMIT 1");
            $stmt->execute([$correo]);
            $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($empleado && password_verify($contrasena, $empleado['contrasena'])) {
                // Admin login successful
                $_SESSION['empleado'] = array(
                    'id' => $empleado['id_empleado'],
                    'nombre' => $empleado['nombre'],
                    'correo_electronico' => $empleado['correo_electronico'],
                    'puesto' => $empleado['puesto']
                );

                header('Location: ' . SITE_URL . '/empleado/dashboard.php');
                exit;
            } else {
                throw new Exception('Credenciales incorrectas.');
            }
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
    <title>Iniciar Sesión - Administrador</title>
</head>

<body>
    <h2><?php echo $firstAdmin ? 'Registrar primer admin' : 'Iniciar sesión como administrador'; ?></h2>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="correo_electronico">Correo Electrónico</label>
        <input type="email" name="correo_electronico" id="correo_electronico" required>

        <label for="contrasena">Contraseña</label>
        <input type="password" name="contrasena" id="contrasena" required>

        <button type="submit">
            <?php echo $firstAdmin ? 'Crear Admin' : 'Iniciar sesión'; ?>
        </button>
    </form>

    <?php if (!$firstAdmin): ?>
        <p><a href="<?php echo SITE_URL; ?>/login.php">¿Eres un empleado? Inicia sesión aquí.</a></p>
    <?php endif; ?>
</body>

</html>