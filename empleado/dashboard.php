<?php
session_start();
require_once('../configuracion.php');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Redirect to login if not logged in
if (!isset($_SESSION['empleado']) || empty($_SESSION['empleado'])) {
    header('Location: login_admin.php');
    exit;
}

// Get the logged-in employee's information
$empleado = $_SESSION['empleado'];

// Check if the logged-in user is an admin
$isAdmin = ($empleado['puesto'] === 'admin');

// If the form is submitted to register an empleado, process the registration
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['correo_electronico'], $_POST['puesto'], $_POST['contrasena'], $_POST['salario'])) {
    try {
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
        $correo = filter_var($_POST['correo_electronico'], FILTER_SANITIZE_EMAIL);
        $puesto = filter_var($_POST['puesto'], FILTER_SANITIZE_STRING);
        $contrasena = $_POST['contrasena'];
        $salario = $_POST['salario'];

        // Hash the employee's password
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insert the new empleado into the database
        $stmt = $pdo->prepare("INSERT INTO empleados (nombre, correo_electronico, puesto, contrasena, fecha_contratacion, salario) 
                               VALUES (?, ?, ?, ?, NOW(), ?)");
        $stmt->execute([$nombre, $correo, $puesto, $hashedPassword, $salario]);

        $successMessage = "Empleado registrado exitosamente.";
    } catch (Exception $e) {
        $errorMessage = "Error al registrar el empleado: " . $e->getMessage();
    }
}

// Handle despedir (fire/terminate) an empleado
if ($isAdmin && isset($_GET['despedir_id'])) {
    try {
        $despedirId = $_GET['despedir_id'];

        // Delete the empleado from the database
        $stmt = $pdo->prepare("DELETE FROM empleados WHERE id_empleado = ?");
        $stmt->execute([$despedirId]);

        $despedirMessage = "Empleado despedido exitosamente.";
    } catch (Exception $e) {
        $despedirError = "Error al despedir al empleado: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
    <link href="../css/dashboard.css" rel="stylesheet">
</head>

<body>
    <h1>Bienvenido <?php echo htmlspecialchars($empleado['puesto'] === 'admin' ? 'Admin' : 'Trabajador'); ?>,
        "<?php echo htmlspecialchars($empleado['nombre']); ?>"</h1>

    <?php if ($isAdmin): ?>
        <h2 style="padding: 10px;">Opciones del Administrador</h2>
        <form action="" method="POST">
            <h3>Registrar Nuevo Empleado</h3>

            <?php if (isset($successMessage)): ?>
                <p style="color: green;"><?php echo htmlspecialchars($successMessage); ?></p>
            <?php endif; ?>

            <?php if (isset($errorMessage)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php endif; ?>

            <label for="nombre">Nombre del Empleado:</label>
            <input type="text" name="nombre" id="nombre" required><br><br>

            <label for="correo_electronico">Correo Electrónico:</label>
            <input type="email" name="correo_electronico" id="correo_electronico" required><br><br>

            <label for="puesto">Puesto:</label>
            <input type="text" name="puesto" id="puesto" required><br><br>

            <label for="salario">Salario:</label>
            <input type="number" name="salario" id="salario" min="1000" step="0.01" required><br><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" required><br><br>

            <button type="submit">Registrar Empleado</button>
        </form>

        <h3 style="padding: 10px;">Empleados Registrados</h3>
        <table border="1" style="padding: 10px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Puesto</th>
                    <th>Salario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all empleados
                $stmt = $pdo->prepare("SELECT id_empleado, nombre, puesto, salario FROM empleados");
                $stmt->execute();
                $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($empleados as $emp):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($emp['id_empleado']); ?></td>
                        <td><?php echo htmlspecialchars($emp['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($emp['puesto']); ?></td>
                        <td><?php echo htmlspecialchars($emp['salario']); ?></td>
                        <td>
                            <a href="?despedir_id=<?php echo $emp['id_empleado']; ?>"
                                onclick="return confirm('¿Estás seguro de que deseas despedir a este empleado?');">Despedir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!$isAdmin && $empleado['puesto'] === 'trabajador'): ?>
        <h3 style="padding: 10px;">Información de tu cuenta</h3>
        <table border="1" style="padding: 10px;">
            <thead>
                <tr>
                    <th>Correo Electrónico</th>
                    <th>Puesto</th>
                    <th>Fecha de Contratación</th>
                    <th>Salario</th>
                    <th>Contraseña (Hash)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($empleado['id_empleado'])) {
                    $stmt = $pdo->prepare("SELECT correo_electronico, puesto, fecha_contratacion, salario, contrasena 
                                       FROM empleados WHERE id_empleado = ?");
                    $stmt->execute([$empleado['id_empleado']]);
                    $trabajadorInfo = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($trabajadorInfo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($trabajadorInfo['correo_electronico']); ?></td>
                            <td><?php echo htmlspecialchars($trabajadorInfo['puesto']); ?></td>
                            <td><?php echo htmlspecialchars($trabajadorInfo['fecha_contratacion']); ?></td>
                            <td><?php echo htmlspecialchars($trabajadorInfo['salario']); ?></td>
                            <td><?php echo htmlspecialchars($trabajadorInfo['contrasena']); ?></td>
                        </tr>
                    <?php endif;
                } else {
                    echo "<tr><td colspan='5'>Error: No se encontró el ID del empleado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <a href="logout.php">Cerrar sesión</a>

    <!-- Show despedir (fired) message -->
    <?php if (isset($despedirMessage)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($despedirMessage); ?></p>
    <?php endif; ?>

    <?php if (isset($despedirError)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($despedirError); ?></p>
    <?php endif; ?>
</body>

</html>