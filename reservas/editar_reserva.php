<?php
session_start();
include '../configuracion.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    echo "Acceso denegado.";
    exit();
}

$id_usuario = $_SESSION['usuario']['id'];

if (!isset($_GET['id_reserva']) || empty($_GET['id_reserva'])) {
    echo "ID de reserva no especificado.";
    exit();
}

$id_reserva = intval($_GET['id_reserva']);
$conexion = Conexion::getInstance()->getConnection();

try {
    // Verificar que la reserva pertenece al usuario (columna id_cliente)
    $stmt = $conexion->prepare("
        SELECT id_reserva, fecha_reserva, cantidad_personas, comentarios 
        FROM reservas 
        WHERE id_reserva = :id_reserva 
          AND id_cliente = :id_cliente
    ");
    $stmt->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
    $stmt->bindParam(':id_cliente', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reserva) {
        echo "Reserva no encontrada o no autorizada.";
        exit();
    }

    // Procesar el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fecha_reserva'], $_POST['hora_reserva'], $_POST['cantidad_personas'], $_POST['comentarios'])) {
        $nueva_fecha = trim($_POST['fecha_reserva']);
        $nueva_hora = trim($_POST['hora_reserva']);
        $nueva_cantidad = filter_var($_POST['cantidad_personas'], FILTER_VALIDATE_INT);
        $nuevos_comentarios = trim($_POST['comentarios']);

        if (empty($nueva_fecha) || empty($nueva_hora) || !$nueva_cantidad) {
            echo "Por favor, complete todos los campos correctamente.";
            exit();
        }

        // Combinar fecha y hora
        $fechaHoraActualizada = $nueva_fecha . ' ' . $nueva_hora . ':00';

        $update = $conexion->prepare("
            UPDATE reservas 
               SET fecha_reserva    = :fecha_reserva,
                   cantidad_personas = :cantidad_personas,
                   comentarios       = :comentarios
             WHERE id_reserva = :id_reserva 
               AND id_cliente = :id_cliente
        ");
        $update->bindParam(':fecha_reserva', $fechaHoraActualizada, PDO::PARAM_STR);
        $update->bindParam(':cantidad_personas', $nueva_cantidad, PDO::PARAM_INT);
        $update->bindParam(':comentarios', $nuevos_comentarios, PDO::PARAM_STR);
        $update->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
        $update->bindParam(':id_cliente', $id_usuario, PDO::PARAM_INT);
        $update->execute();

        echo "<script>
            alert('Reserva actualizada con éxito.');
            window.location.href = 'perfil.php';
        </script>";
        exit();
    }
} catch (Exception $e) {
    echo "Error al cargar o actualizar la reserva: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Reserva</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .overlay {
            background: url('<?php echo SITE_URL; ?>/files/bread.jpg') no-repeat center center;
            background-size: cover;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .overlay::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal {
            position: fixed;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 500px;
            z-index: 2;
        }

        .modal h2 {
            color: #E4007C;
            margin-bottom: 1rem;
        }

        .modal label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
        }

        .modal input,
        .modal textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 0.75rem;
        }

        .modal button {
            background-color: #E4007C;
            color: white;
            padding: 0.7rem 1.2rem;
            border: none;
            border-radius: 30px;
            margin-top: 1rem;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }

        .modal a {
            display: inline-block;
            margin-top: 1rem;
            text-decoration: none;
            color: #E4007C;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Capa oscurecedora sobre la imagen de fondo -->
    <div class="overlay"></div>

    <div class="modal">
        <h2>Editar Reserva</h2>
        <form method="POST">
            <label for="fecha_reserva">Fecha:</label>
            <input
                type="date"
                name="fecha_reserva"
                id="fecha_reserva"
                value="<?php echo date('Y-m-d', strtotime($reserva['fecha_reserva'])); ?>"
                required>

            <label for="hora_reserva">Hora:</label>
            <input
                type="time"
                name="hora_reserva"
                id="hora_reserva"
                value="<?php echo date('H:i', strtotime($reserva['fecha_reserva'])); ?>"
                required>

            <label for="cantidad_personas">Cant. Personas:</label>
            <input
                type="number"
                name="cantidad_personas"
                id="cantidad_personas"
                min="1"
                value="<?php echo htmlspecialchars($reserva['cantidad_personas']); ?>"
                required>

            <label for="comentarios">Comentarios:</label>
            <textarea
                name="comentarios"
                id="comentarios"
                rows="3"
                placeholder="Por ejemplo: Mesa cerca de la ventana, por favor."><?php echo htmlspecialchars($reserva['comentarios']); ?></textarea>

            <button type="submit">Actualizar Reserva</button>
        </form>

        <a href="../usuario/perfil.php">Cancelar</a>
    </div>
</body>

</html>