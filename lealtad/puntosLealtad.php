<?php
session_start();
include '../configuracion.php';
include_once('../header.php');

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    echo "<p class='text-red-500 mb-4'>Por favor, inicie sesión para ver su información.</p>";
    echo "<a href='index.php' class='bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg shadow-md hover:shadow-xl transition'>Ir a la página de inicio</a>";
    exit();
}

$id_usuario = $_SESSION['usuario']['id'];

try {
    $conexion = Conexion::getInstance()->getConnection();

    // Obtener los puntos totales del usuario
    $query_puntos = "SELECT puntos FROM Puntos_Acumulados WHERE id_usuario = :id_usuario";
    $stmt_puntos = $conexion->prepare($query_puntos);
    $stmt_puntos->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt_puntos->execute();
    $puntos = $stmt_puntos->fetchColumn() ?: 0;

    // Obtener el programa actual basado en los puntos acumulados
    $query_programa = "
    SELECT nombre_programa, descripcion, puntos_requeridos, beneficios
    FROM programa_lealtad
    WHERE puntos_requeridos <= :puntos
      AND estado = 'activo'
      AND fecha_inicio <= CURRENT_DATE 
      AND fecha_fin >= CURRENT_DATE
    ORDER BY puntos_requeridos DESC
    LIMIT 1
    ";
    $stmt_programa = $conexion->prepare($query_programa);
    $stmt_programa->bindParam(':puntos', $puntos, PDO::PARAM_INT);
    $stmt_programa->execute();
    $programa = $stmt_programa->fetch(PDO::FETCH_ASSOC);

    // Determinar el mensaje del programa
    if ($programa) {
        $mensaje_programa = "Programa actual: {$programa['nombre_programa']}
                             Puntos requeridos: {$programa['puntos_requeridos']}
                             Beneficios: {$programa['descripcion']}";
    } else {
        $mensaje_programa = "No has alcanzado ningún programa de lealtad aún.
                             Sigue acumulando puntos para desbloquear beneficios.";
    }

    $query_proximo_programa = "
    SELECT nombre_programa, puntos_requeridos
    FROM programa_lealtad
    WHERE puntos_requeridos > :puntos
      AND estado = 'activo'
      AND fecha_inicio <= CURRENT_DATE 
      AND fecha_fin >= CURRENT_DATE
    ORDER BY puntos_requeridos ASC
    LIMIT 1
    ";
    $stmt_proximo_programa = $conexion->prepare($query_proximo_programa);
    $stmt_proximo_programa->bindParam(':puntos', $puntos, PDO::PARAM_INT);
    $stmt_proximo_programa->execute();
    $proximo_programa = $stmt_proximo_programa->fetch(PDO::FETCH_ASSOC);

    $puntos_actuales = $puntos;
    $puntos_para_siguiente = $proximo_programa
        ? ($proximo_programa['puntos_requeridos'] - $puntos_actuales)
        : 0;

} catch (Exception $e) {
    echo "<p class='text-red-500 mb-4'>Error al recuperar la información: " . $e->getMessage() . "</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Puntos y Programa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.16/tailwind.min.css">
</head>

<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-4xl font-bold mb-6 text-center">Programa de Lealtad</h1>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Puntos -->
                <div class="bg-red-50 rounded-lg p-6 text-center">
                    <h2 class="text-xl font-semibold mb-4">Puntos Acumulados</h2>
                    <p class="text-5xl font-bold text-red-600">
                        <?= $puntos_actuales ? number_format($puntos_actuales) : '0' ?>
                    </p>
                </div>

                <!-- Programa Actual -->
                <div class="bg-blue-50 rounded-lg p-6 text-center">
                    <h2 class="text-xl font-semibold mb-4">Programa Actual</h2>
                    <?php if ($programa && $programa['nombre_programa'] !== 'Programa de Cumpleaños'): ?>
                        <p class="text-2xl font-bold text-blue-800"><?= $programa['nombre_programa'] ?></p>
                        <p class="text-sm text-blue-600 mt-2"><?= $programa['descripcion'] ?></p>
                    <?php else: ?>
                        <p class="text-xl text-blue-600">Ningún programa activo</p>
                    <?php endif; ?>
                </div>

                <!-- Próximo Programa -->
                <div class="bg-green-50 rounded-lg p-6 text-center">
                    <h2 class="text-xl font-semibold mb-4">Próximo Programa</h2>
                    <?php if ($proximo_programa): ?>
                        <p class="text-2xl font-bold text-green-800"><?= $proximo_programa['nombre_programa'] ?></p>
                        <p class="text-sm text-green-600 mt-2">
                            <?= $puntos_para_siguiente ?> puntos restantes
                        </p>
                    <?php else: ?>
                        <p class="text-xl text-green-600">Has alcanzado el nivel máximo</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Barra de Progreso -->
            <div class="mt-8 w-full bg-gray-200 rounded-full h-4">
                <?php
                $porcentaje_progreso = $proximo_programa
                    ? ($puntos_actuales / $proximo_programa['puntos_requeridos']) * 100
                    : 100;
                $porcentaje_progreso = min($porcentaje_progreso, 100);
                ?>
                <div class="bg-red-500 h-4 rounded-full" style="width: <?= $porcentaje_progreso ?>%"></div>
            </div>

            <!-- Información Detallada -->
            <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                <h3 class="text-2xl font-semibold mb-4">Detalles del Programa</h3>
                <?php if ($programa && $programa['nombre_programa'] !== 'Programa de Cumpleaños'): ?>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <p class="font-bold">Beneficios:</p>
                            <p><?= $programa['beneficios'] ?></p>
                        </div>
                        <div>
                            <p class="font-bold">Puntos Requeridos:</p>
                            <p><?= $programa['puntos_requeridos'] ?> puntos</p>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-gray-600">Continúa acumulando puntos para desbloquear beneficios.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>

<?php include('../footer.php'); ?>