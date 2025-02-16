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

    if (empty($errores)) {
        try {
            $password_hash = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

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
            header("Location: " . SITE_URL . "/admin/login.php?registro=exitoso");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
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

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
    }

    .container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 16px;
    }

    .card {
        padding: 24px;
        max-width: 1000px;
        margin: 0 auto;
    }

    h2 {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        color: #E4007C;
        margin-bottom: 24px;
    }

    .error {
        background-color: #F8D7DA;
        border: 1px solid #F5C6CB;
        color: #721C24;
        padding: 16px;
        border-radius: 4px;
        margin-bottom: 16px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #555;
        margin-bottom: 8px;
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: #E4007C;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
    }

    button:hover {
        background-color: #D4006A;
    }

    .login-link {
        text-align: center;
        font-size: 14px;
        color: #555;
    }

    .login-link a {
        color: #E4007C;
        text-decoration: none;
    }

    .login-link a:hover {
        color: #D4006A;
    }
</style>

<div class="container">
    <div class="card">
        <h2>Registro de Usuario</h2>

        <?php if (!empty($errores)): ?>
            <div class="error">
                <?php foreach ($errores as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" name="nombre" id="nombre" required
                    value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="correo_electronico">Correo Electrónico *</label>
                <input type="email" name="correo_electronico" id="correo_electronico" required
                    value="<?php echo isset($_POST['correo_electronico']) ? htmlspecialchars($_POST['correo_electronico']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña *</label>
                <input type="password" name="contrasena" id="contrasena" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" name="telefono" id="telefono"
                    value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <textarea name="direccion" id="direccion"
                    rows="3"><?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <button type="submit">Registrarse</button>
            </div>
        </form>

        <p class="login-link">
            ¿Ya tienes una cuenta? <a href="<?php echo SITE_URL; ?>/admin/login.php">Inicia sesión aquí</a>
        </p>
    </div>
</div>

<?php include(__DIR__ . '/../footer.php'); ?>