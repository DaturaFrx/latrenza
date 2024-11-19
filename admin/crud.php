<?php
// admin/crud.php
require_once('../configuracion.php');

session_start();

// Check if the user is logged in
if (!isset($_SESSION['nombre'])) {
    header('Location: ' . SITE_URL . '/admin/login.php');
    exit;
}

// If the logged-in user is "admin", ask for password verification
if ($_SESSION['nombre'] === 'admin' && !isset($_SESSION['admin_verified'])) {
    header('Location: ' . SITE_URL . '/admin/verify_password.php');
    exit;
}

$modules = [
    'productos' => [
        'table' => 'productos',
        'fields' => ['nombre_producto', 'descripcion', 'precio'],
        'labels' => ['Nombre', 'Descripción', 'Precio'],
        'types' => ['text', 'textarea', 'number']
    ],
    'fotos' => [
        'table' => 'fotos',
        'fields' => ['id_producto', 'url_foto'],
        'labels' => ['ID Producto', 'URL de la Foto'],
        'types' => ['number', 'url']
    ],
    'usuarios' => [
        'table' => 'usuarios',
        'fields' => ['nombre', 'email', 'rol'],
        'labels' => ['Nombre', 'Email', 'Rol'],
        'types' => ['text', 'email', 'select'],
        'options' => ['rol' => ['admin', 'usuario']]
    ]
];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );

        $action = $_POST['action'] ?? '';
        $module = $_POST['module'] ?? '';
        $id = $_POST['id'] ?? '';

        if (isset($modules[$module])) {
            switch ($action) {
                case 'create':
                    $fields = $modules[$module]['fields'];
                    $values = array_map(fn($field) => $_POST[$field] ?? '', $fields);
                    $sql = "INSERT INTO {$modules[$module]['table']} (" . implode(',', $fields) . ") VALUES (" . str_repeat('?,', count($fields) - 1) . "?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($values);
                    $_SESSION['message'] = 'Registro creado exitosamente';
                    break;

                case 'update':
                    $fields = $modules[$module]['fields'];
                    $values = array_map(fn($field) => $_POST[$field] ?? '', $fields);
                    $values[] = $id;
                    $sql = "UPDATE {$modules[$module]['table']} SET " . implode('=?,', $fields) . "=? WHERE id_{$module}=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($values);
                    $_SESSION['message'] = 'Registro actualizado exitosamente';
                    break;

                case 'delete':
                    $sql = "DELETE FROM {$modules[$module]['table']} WHERE id_{$module}=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$id]);
                    $_SESSION['message'] = 'Registro eliminado exitosamente';
                    break;
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }

    header('Location: ' . $_SERVER['PHP_SELF'] . '?module=' . $module);
    exit;
}

// Get current module data
$currentModule = $_GET['module'] ?? 'productos';
$data = [];

if (isset($modules[$currentModule])) {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );

        $stmt = $pdo->query("SELECT * FROM {$modules[$currentModule]['table']}");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin CRUD - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold"><?php echo SITE_NAME; ?> - Panel Admin</h1>
            <div class="flex gap-4">
                <select onchange="window.location.href='?module='+this.value" class="px-4 py-2 border rounded-lg">
                    <?php foreach ($modules as $key => $module): ?>
                        <option value="<?php echo $key; ?>" <?php echo $currentModule === $key ? 'selected' : ''; ?>>
                            <?php echo ucfirst($key); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <a href="<?php echo SITE_URL; ?>/admin/logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                    Cerrar Sesión
                </a>
            </div>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Create Form -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-bold mb-4">Crear Nuevo <?php echo ucfirst($currentModule); ?></h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="grid grid-cols-1 gap-4">
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="module" value="<?php echo $currentModule; ?>">

                <?php foreach ($modules[$currentModule]['fields'] as $i => $field): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <?php echo $modules[$currentModule]['labels'][$i]; ?>
                        </label>
                        <?php if ($modules[$currentModule]['types'][$i] === 'textarea'): ?>
                            <textarea name="<?php echo $field; ?>" class="w-full px-3 py-2 border rounded-lg"
                                required></textarea>
                        <?php elseif ($modules[$currentModule]['types'][$i] === 'select'): ?>
                            <select name="<?php echo $field; ?>" class="w-full px-3 py-2 border rounded-lg" required>
                                <?php foreach ($modules[$currentModule]['options'][$field] as $option): ?>
                                    <option value="<?php echo $option; ?>"><?php echo ucfirst($option); ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <input type="<?php echo $modules[$currentModule]['types'][$i]; ?>" name="<?php echo $field; ?>"
                                class="w-full px-3 py-2 border rounded-lg" required>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                    Crear
                </button>
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Lista de <?php echo ucfirst($currentModule); ?></h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <?php foreach ($modules[$currentModule]['labels'] as $label): ?>
                                <th class="px-4 py-2"><?php echo $label; ?></th>
                            <?php endforeach; ?>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?php echo $row["id_{$currentModule}"]; ?></td>
                                <?php foreach ($modules[$currentModule]['fields'] as $field): ?>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($row[$field]); ?></td>
                                <?php endforeach; ?>
                                <td class="px-4 py-2">
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="module" value="<?php echo $currentModule; ?>">
                                        <input type="hidden" name="id" value="<?php echo $row["id_{$currentModule}"]; ?>">
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded"
                                            onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                            Eliminar
                                        </button>
                                    </form>
                                    <button onclick="editRecord(<?php echo htmlspecialchars(json_encode($row)); ?>)"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded ml-2">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Editar <?php echo ucfirst($currentModule); ?></h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="grid grid-cols-1 gap-4">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="module" value="<?php echo $currentModule; ?>">
                <input type="hidden" name="id" id="editId">

                <?php foreach ($modules[$currentModule]['fields'] as $i => $field): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <?php echo $modules[$currentModule]['labels'][$i]; ?>
                        </label>
                        <?php if ($modules[$currentModule]['types'][$i] === 'textarea'): ?>
                            <textarea name="<?php echo $field; ?>" id="edit_<?php echo $field; ?>"
                                class="w-full px-3 py-2 border rounded-lg" required></textarea>
                        <?php elseif ($modules[$currentModule]['types'][$i] === 'select'): ?>
                            <select name="<?php echo $field; ?>" id="edit_<?php echo $field; ?>"
                                class="w-full px-3 py-2 border rounded-lg" required>
                                <?php foreach ($modules[$currentModule]['options'][$field] as $option): ?>
                                    <option value="<?php echo $option; ?>"><?php echo ucfirst($option); ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <input type="<?php echo $modules[$currentModule]['types'][$i]; ?>" name="<?php echo $field; ?>"
                                id="edit_<?php echo $field; ?>" class="w-full px-3 py-2 border rounded-lg" required>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editRecord(record) {
            document.getElementById('editId').value = record[`id_${currentModule}`];
            <?php foreach ($modules[$currentModule]['fields'] as $field): ?>
                document.getElementById('edit_<?php echo $field; ?>').value = record['<?php echo $field; ?>'];
            <?php endforeach; ?>
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>

</html>