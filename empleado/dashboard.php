<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        :root {
            --pink-100: #FFE5F3;
            --pink-200: #FFB3DC;
            --pink-300: #FF80C6;
            --pink-400: #FF4DAF;
            --pink-500: #E4007C;
            --pink-600: #B30061;
            --pink-700: #800046;
            --pink-800: #4D002A;
            --pink-900: #1A000E;
        }

        .bg-primary {
            background-color: var(--pink-500);
        }

        .bg-primary-light {
            background-color: var(--pink-100);
        }

        .hover\:bg-primary-dark:hover {
            background-color: var(--pink-600);
        }

        .text-primary {
            color: var(--pink-500);
        }

        .border-primary {
            border-color: var(--pink-500);
        }

        .hover\:bg-primary-light:hover {
            background-color: var(--pink-200);
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .custom-shadow {
            box-shadow: 0 4px 6px -1px rgba(228, 0, 124, 0.1),
                0 2px 4px -1px rgba(228, 0, 124, 0.06);
        }

        .table-container {
            scrollbar-width: thin;
            scrollbar-color: var(--pink-500) #f3f4f6;
        }

        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        .table-container::-webkit-scrollbar-thumb {
            background-color: var(--pink-500);
            border-radius: 4px;
        }

        .btn-primary {
            background-color: var(--pink-500);
            color: white;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--pink-600);
        }

        .btn-outline {
            border: 1px solid var(--pink-500);
            color: var(--pink-500);
        }

        .btn-outline:hover {
            background-color: var(--pink-100);
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php
    session_start();
    require_once('../configuracion.php');

    if (!isset($_SESSION['empleado']) || empty($_SESSION['empleado'])) {
        header('Location: login_admin.php');
        exit;
    }

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

    $empleado = $_SESSION['empleado'];
    $isAdmin = ($empleado['puesto'] === 'admin');

    // Get tables and create module configuration
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $modules = [];

    foreach ($tables as $table) {
        $stmt = $pdo->prepare("DESCRIBE $table");
        $stmt->execute();
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $fieldsArray = array_column($fields, 'Field');
        $labels = array_map(function ($field) {
            return ucfirst(str_replace('_', ' ', $field));
        }, $fieldsArray);

        $types = array_map(function ($field) {
            switch ($field) {
                case 'email':
                    return 'email';
                case 'password':
                case 'contrasena':
                    return 'password';
                case 'fecha':
                    return 'date';
                case 'telefono':
                    return 'tel';
                default:
                    return 'text';
            }
        }, $fieldsArray);

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

    $moduleToShow = $_GET['module'] ?? 'empleados';

    // Handle form submissions
    if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['module'])) {
            $module = $_POST['module'];
            if (array_key_exists($module, $modules)) {
                $fields = [];
                $values = [];
                $params = [];

                foreach ($modules[$module]['fields'] as $index => $field) {
                    if (
                        $field !== $modules[$module]['primaryKey'] &&
                        isset($_POST[$field]) && $_POST[$field] !== ''
                    ) {
                        $fields[] = $field;
                        $values[] = '?';

                        if ($field === 'password' || $field === 'contrasena') {
                            $params[] = password_hash($_POST[$field], PASSWORD_BCRYPT);
                        } else {
                            $params[] = htmlspecialchars($_POST[$field]);
                        }
                    }
                }

                if (isset($_POST['update_id'])) {
                    $sets = array_map(function ($field) {
                        return "$field = ?";
                    }, $fields);

                    $query = "UPDATE {$module} SET " . implode(', ', $sets) .
                        " WHERE {$modules[$module]['primaryKey']} = ?";
                    $params[] = $_POST['update_id'];
                } else {
                    $query = "INSERT INTO {$module} (" . implode(', ', $fields) .
                        ") VALUES (" . implode(', ', $values) . ")";
                }

                $stmt = $pdo->prepare($query);
                $stmt->execute($params);
                $successMessage = ucfirst($module) . " guardado exitosamente.";
            }
        }
    }

    // Handle deletions
    if ($isAdmin && isset($_GET['delete_id']) && isset($_GET['module'])) {
        $moduleId = $_GET['delete_id'];
        $module = $_GET['module'];
        if (array_key_exists($module, $modules)) {
            $stmt = $pdo->prepare("DELETE FROM {$module} WHERE {$modules[$module]['primaryKey']} = ?");
            $stmt->execute([$moduleId]);
            $successMessage = ucfirst($module) . " eliminado exitosamente.";
        }
    }
    ?>

    <div class="min-h-screen p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header with Dropdown -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Bienvenido
                    <?php echo htmlspecialchars($empleado['puesto'] === 'admin' ? 'Admin' : 'Trabajador'); ?>,
                    "<?php echo htmlspecialchars($empleado['nombre']); ?>"
                </h1>
                <div class="flex items-center">
                    <div class="relative dropdown">
                        <button class="btn-primary px-4 py-2 rounded-lg flex items-center space-x-2" onclick="toggleDropdown(event)">
                            <span><?php echo ucfirst($moduleToShow); ?></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white z-50">
                            <?php foreach ($modules as $module => $config): ?>
                                <a href="?module=<?php echo $module; ?>"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-light">
                                    <?php echo ucfirst($module); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <a href="logout.php" class="btn-outline px-4 py-2 rounded-lg ml-4">
                        Cerrar Sesión
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            <?php if (isset($successMessage)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">
                    <?php echo ucfirst($moduleToShow); ?> - Nuevo Registro
                </h2>
                <form action="" method="POST">
                    <input type="hidden" name="module" value="<?php echo $moduleToShow; ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($modules[$moduleToShow]['fields'] as $index => $field): ?>
                            <?php if ($field !== $modules[$moduleToShow]['primaryKey']): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <?php echo $modules[$moduleToShow]['labels'][$index]; ?>
                                    </label>
                                    <input type="<?php echo $modules[$moduleToShow]['types'][$index]; ?>"
                                        name="<?php echo $field; ?>"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                        required>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="btn-primary px-6 py-2 rounded-lg">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">
                    <?php echo ucfirst($moduleToShow); ?> - Listado
                </h2>
                <div class="table-container overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <?php foreach ($modules[$moduleToShow]['labels'] as $label): ?>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php echo htmlspecialchars($label); ?>
                                    </th>
                                <?php endforeach; ?>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $stmt = $pdo->query("SELECT * FROM {$modules[$moduleToShow]['table']}");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <?php foreach ($modules[$moduleToShow]['fields'] as $field): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php
                                            echo ($field === 'password' || $field === 'contrasena')
                                                ? '********'
                                                : htmlspecialchars($row[$field]);
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="?module=<?php echo $moduleToShow; ?>&delete_id=<?php
                                           echo $row[$modules[$moduleToShow]['primaryKey']]; ?>"
                                            onclick="return confirm('¿Estás seguro?')"
                                            class="btn-outline px-3 py-1 rounded-lg mr-2">
                                            Eliminar
                                        </a>
                                        <button onclick="prepareUpdate(<?php echo htmlspecialchars(json_encode($row)); ?>)"
                                            class="btn-primary px-3 py-1 rounded-lg">
                                            Editar
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function prepareUpdate(data) {
            const form = document.querySelector('form');
            const fields = <?php echo json_encode($modules[$moduleToShow]['fields']); ?>;

            fields.forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input && data[field]) {
                    input.value = data[field];
                }
            });

            // Add update_id field
            let updateInput = form.querySelector('input[name="update_id"]');
            if (!updateInput) {
                updateInput = document.createElement('input');
                updateInput.type = 'hidden';
                updateInput.name = 'update_id';
                form.appendChild(updateInput);
            }
            updateInput.value = data[<?php echo json_encode($modules[$moduleToShow]['primaryKey']); ?>];

            // Change button text
            form.querySelector('button[type="submit"]').textContent = 'Actualizar';

            // Scroll to form
            form.scrollIntoView({ behavior: 'smooth' });
        }

        function toggleDropdown(event) {
            event.stopPropagation(); // Prevent the click event from bubbling up
            const dropdownMenu = event.currentTarget.nextElementSibling; // Get the dropdown menu
            dropdownMenu.classList.toggle('hidden'); // Toggle the visibility
        }

        // Close the dropdown if clicked outside
        document.addEventListener('click', function() {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            dropdowns.forEach(dropdown => {
                dropdown.classList.add('hidden'); // Hide all dropdowns
            });
        });
    </script>
</body>

</html>