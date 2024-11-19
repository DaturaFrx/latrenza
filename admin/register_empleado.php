<?php
session_start();

// Check if the user is an admin, else redirect to login page
if (!isset($_SESSION['admin']) || $_SESSION['admin']['puesto'] !== 'admin') {
    header("Location: " . SITE_URL . "/admin/login_admin.php");
    exit;
}

require_once('../configuracion.php');

$errores = [];
$exito = false;

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
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
    if (empty($_POST['puesto'])) {
        $errores[] = "El puesto es obligatorio";
    }

    // Si no hay errores, proceder con el registro
    if (empty($errores)) {
        try {
            // Hashing the password using bcrypt
            $password_hash = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO empleados (nombre, correo_electronico, contrasena, puesto, fecha_contratacion, salario) 
                                 VALUES (:nombre, :correo_electronico, :contrasena, :puesto, :fecha_contratacion, :salario)");

            $stmt->execute([
                ':nombre' => $_POST['nombre'],
                ':correo_electronico' => $_POST['correo_electronico'],
                ':contrasena' => $password_hash,
                ':puesto' => $_POST['puesto'],
                ':fecha_contratacion' => $_POST['fecha_contratacion'],
                ':salario' => $_POST['salario']
            ]);

            $exito = true;
            // Redirigir al login después del registro exitoso
            header("Location: " . SITE_URL . "/admin/login.php?registro=exitoso");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Código de error para duplicate entry
                $errores[] = "Este correo electrónico ya está registrado";
            } else {
                $errores[] = "Error al registrar el empleado";
            }
        }
    }
}

$pageTitle = "Registro de Empleado";
include(__DIR__ . '/../header.php');
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-center text-amber-800">Registro de Empleado</h2>

        <?php if (!empty($errores)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php foreach ($errores as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <!-- Form fields here for employee registration -->
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
