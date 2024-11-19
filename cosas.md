# Al azar

## BD

### Tablas Principales

#### usuarios

* id_usuario (PK)
* nombre
* correo_electronico (UNIQUE)
* contrasena
* telefono
* direccion

#### productos

* id_producto (PK)
* nombre_producto
* descripcion
* precio
* categoria (FK a tabla categorias)
* imagen
* stock

#### categorias

* id_categoria (PK)
* nombre_categoria

#### pedidos

* id_pedido (PK)
* id_usuario (FK)
* fecha_pedido
* estado_pedido
* metodo_pago

#### detalle_pedidos

* id_detalle (PK)
* id_pedido (FK)
* id_producto (FK)
* cantidad
* subtotal

#### reservas

* id_reserva (PK)
* id_usuario (FK)
* fecha_reserva
* hora_reserva
* numero_personas
* estado_reserva

#### comentarios

* id_comentario (PK)
* id_producto (FK)
* id_usuario (FK)
* comentario
* calificacion
* fecha_comentario

#### eventos

* id_evento (PK)
* nombre_evento
* descripcion_evento
* fecha_evento
* imagen_evento

#### boletines

* id_boletin (PK)
* correo_suscriptor
* fecha_suscripcion

#### metodos_pago

* id_metodo (PK)
* descripcion_metodo

#### programa_lealtad

* id_usuario (PK, FK)
* puntos_acumulados

#### facturas

* id_factura (PK)
* id_pedido (FK)
* fecha_emision
* total

#### imagenes_galeria

* id_imagen (PK)
* url_imagen
* descripcion_imagen

#### soporte

* id_ticket (PK)
* id_usuario (FK)
* asunto
* mensaje
* fecha_envio
* estado_ticket

## Cambios

### Agregar Empleados

#### Antes

``` php
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
```

#### Despues

``` php
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

if (!isset($_SESSION['empleado']) || empty($_SESSION['empleado'])) {
    header('Location: login_admin.php');
    exit;
}

$empleado = $_SESSION['empleado'];
$isAdmin = ($empleado['puesto'] === 'admin');

$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
$modules = [];

foreach ($tables as $table) {
    $stmt = $pdo->prepare("DESCRIBE $table");
    $stmt->execute();
    $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $fieldsArray = array_column($fields, 'Field');
    $labels = array_map('ucfirst', $fieldsArray);
    $types = array_fill(0, count($fieldsArray), 'text');
    $primaryKey = array_column($fields, 'Key');
    $primaryKeyField = $fieldsArray[array_search('PRI', $primaryKey)];

    $modules[$table] = [
        'table' => $table,
        'fields' => $fieldsArray,
        'labels' => $labels,
        'types' => $types,
        'primaryKey' => $primaryKeyField
    ];
}

// Set default module to 'empleados' if none is specified
$moduleToShow = $_GET['module'] ?? 'empleados';

if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['module'])) {
    $module = $_POST['module'];
    if (array_key_exists($module, $modules)) {
        $fields = $modules[$module]['fields'];
        $primaryKeyField = $modules[$module]['primaryKey'];
        $params = [];
        $query = "INSERT INTO {$modules[$module]['table']} (";

        // Prepare the fields and values, excluding the primary key
        foreach ($fields as $field) {
            if ($field !== $primaryKeyField && !empty($_POST[$field])) {
                $query .= "$field, ";
                $params[] = htmlspecialchars($_POST[$field]);
            }
        }

        // Remove the last comma and space
        $query = rtrim($query, ', ') . ") VALUES (";
        $query .= implode(',', array_fill(0, count($params), '?')) . ")";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $successMessage = ucfirst($module) . " registrado exitosamente.";
    }
}

if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id']) && isset($_POST['module'])) {
    $moduleId = $_POST['update_id'];
    $module = $_POST['module'];
    if (array_key_exists($module, $modules)) {
        $fields = $modules[$module]['fields'];
        $primaryKeyField = $modules[$module]['primaryKey'];
        $query = "UPDATE {$modules[$module]['table']} SET ";
        $params = [];

        foreach ($fields as $field) {
            if ($field !== $primaryKeyField && !empty($_POST[$field])) {
                $query .= "$field = ?, ";
                $params[] = htmlspecialchars($_POST[$field]);
            }
        }

        $query = rtrim($query, ', ') . " WHERE {$primaryKeyField} = ?";
        $params[] = $moduleId;

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $successMessage = ucfirst($module) . " actualizado exitosamente.";
    }
}

if ($isAdmin && isset($_GET['delete_id']) && isset($_GET['module'])) {
    $moduleId = $_GET['delete_id'];
    $module = $_GET['module'];
    if (array_key_exists($module, $modules)) {
        $stmt = $pdo->prepare("DELETE FROM {$modules[$module]['table']} WHERE {$modules[$module]['primaryKey']} = ?");
        $stmt->execute([$moduleId]);
        $successMessage = ucfirst($module) . " eliminado exitosamente.";
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
    <style>
        .carousel {
            display: flex;
            overflow: hidden;
            width: 100%;
            position: relative;
        }

        .carousel-inner {
            display: flex;
            transition: transform 0.5s ease;
        }

        .carousel-item {
            min-width: 100px;
            margin: 0 5px;
        }

        .carousel button {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Dashboard - <?php echo SITE_NAME; ?></h1>
    <nav>
        <div class="carousel">
            <div class="carousel-inner">
                <?php foreach ($modules as $module => $config): ?>
                    <div class="carousel-item">
                        <a href="?module=<?php echo $module; ?>"><?php echo ucfirst($module); ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button onclick="prevSlide()">&#10094;</button>
        <button onclick="nextSlide()">&#10095;</button>
    </nav>

    <h3><?php echo ucfirst($moduleToShow); ?> - Registrar</h3>
    <form action="" method="POST">
        <input type="hidden" name="module" value="<?php echo $moduleToShow; ?>">

        <?php if (isset($successMessage)): ?>
            <p class="success"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>

        <?php foreach ($modules[$moduleToShow]['fields'] as $index => $field): ?>
            <?php if ($field !== $modules[$moduleToShow]['primaryKey']): ?>
                <label for="<?php echo $field; ?>"><?php echo $modules[$moduleToShow]['labels'][$index]; ?>:</label>
                <?php if ($field === 'fecha'): ?>
                    <input type="date" name="<?php echo $field; ?>" id="<?php echo $field; ?>"
                        value="<?php echo date('Y-m-d'); ?>"><br><br>
                <?php else: ?>
                    <input type="<?php echo $modules[$moduleToShow]['types'][$index]; ?>" name="<?php echo $field; ?>"
                        id="<?php echo $field; ?>" required><br><br>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <button type="submit">Registrar</button>
    </form>

    <h3><?php echo ucfirst($moduleToShow); ?> - Listado</h3>
    <table>
        <thead>
            <tr>
                <?php foreach ($modules[$moduleToShow]['labels'] as $label): ?>
                    <th><?php echo htmlspecialchars($label); ?></th>
                <?php endforeach; ?>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM {$modules[$moduleToShow]['table']}");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <?php foreach ($modules[$moduleToShow]['fields'] as $field): ?>
                        <td><?php echo htmlspecialchars($row[$field]); ?></td>
                    <?php endforeach; ?>
                    <td>
                        <a href="?module=<?php echo $moduleToShow; ?>&delete_id=<?php echo $row[$modules[$moduleToShow]['primaryKey']]; ?>"
                            onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?');">Eliminar</a>
                        <a href="#updateModal" data-id="<?php echo $row[$modules[$moduleToShow]['primaryKey']]; ?>"
                            class="update-btn">Actualizar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>
```

> UHHHHHHHHGGGGGG

## Errores

### Conexiones

``` plaintext
// ========================================== // index.php // ========================================== // ========================================== // configuracion.php // ==========================================
Warning: ini_set(): Session ini settings cannot be changed when a session is active in C:\xampp\htdocs\latrenza\configuracion.php on line 16

Warning: ini_set(): Session ini settings cannot be changed when a session is active in C:\xampp\htdocs\latrenza\configuracion.php on line 17
// ========================================== // conexionBD.php // ========================================== // ========================================== // funciones.php // ========================================== // ========================================== // home.php // ==========================================
Warning: require_once(includes/header.php): Failed to open stream: No such file or directory in C:\xampp\htdocs\latrenza\home.php on line 6

Fatal error: Uncaught Error: Failed opening required 'includes/header.php' (include_path='C:\xampp\php\PEAR') in C:\xampp\htdocs\latrenza\home.php:6 Stack trace: #0 C:\xampp\htdocs\latrenza\index.php(10): require_once() #1 {main} thrown in C:\xampp\htdocs\latrenza\home.php on line 6
```
