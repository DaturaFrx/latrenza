<?php
session_start();
include '../configuracion.php';
include_once('../header.php');

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    echo "Por favor, inicia sesión para actualizar tu perfil.";
    exit();
}

$id_usuario = $_SESSION['usuario']['id'];
$error      = '';
$success    = '';

/* ============================================
   1) Procesamiento de POST para foto de perfil 
      y cambio de nombre (mantener tu lógica).
   ============================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['foto_perfil'])) {
        try {
            $conexion = Conexion::getInstance()->getConnection();
            if ($_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $nombre_archivo = basename($_FILES['foto_perfil']['name']);
                $query = "UPDATE usuarios SET foto_perfil = :foto_perfil WHERE id_usuario = :id_usuario";
                $stmt = $conexion->prepare($query);
                $stmt->bindParam(':foto_perfil', $nombre_archivo, PDO::PARAM_STR);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();

                $directorio_imagenes = '../imagenes_perfil/';
                move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $directorio_imagenes . $nombre_archivo);
                $success = "Imagen de perfil actualizada correctamente.";
            } else {
                $error = "Error al subir la imagen. Código de error: " . $_FILES['foto_perfil']['error'];
            }
        } catch (Exception $e) {
            $error = "Error al procesar la acción: " . $e->getMessage();
        }
    }

    if (isset($_POST['nuevo_nombre']) && isset($_POST['contrasena'])) {
        try {
            $nuevo_nombre = trim($_POST['nuevo_nombre']);
            $contrasena   = $_POST['contrasena'];
            $conexion     = Conexion::getInstance()->getConnection();
            $query        = "SELECT contrasena FROM usuarios WHERE id_usuario = :id_usuario";
            $stmt         = $conexion->prepare($query);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $stored_password = $stmt->fetchColumn();

            if (password_verify($contrasena, $stored_password)) {
                $query = "UPDATE usuarios SET nombre = :nuevo_nombre WHERE id_usuario = :id_usuario";
                $stmt = $conexion->prepare($query);
                $stmt->bindParam(':nuevo_nombre', $nuevo_nombre, PDO::PARAM_STR);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['usuario']['nombre'] = $nuevo_nombre;
                $success = "Nombre de usuario actualizado correctamente.";
            } else {
                $error = "Contraseña incorrecta para actualizar el nombre.";
            }
        } catch (Exception $e) {
            $error = "Error al actualizar el nombre: " . $e->getMessage();
        }
    }
}

/* ============================================
   2) Consultar foto de perfil
   ============================================ */
try {
    $conexion = Conexion::getInstance()->getConnection();
    $query    = "SELECT foto_perfil FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt     = $conexion->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $foto_perfil = $stmt->fetchColumn();

    if ($foto_perfil) {
        $ruta_imagen = '../imagenes_perfil/' . $foto_perfil;
        if (!file_exists($ruta_imagen)) {
            $ruta_imagen = '';
        }
    } else {
        $ruta_imagen = '';
    }
} catch (Exception $e) {
    $error      = "Error al obtener la foto de perfil: " . $e->getMessage();
    $ruta_imagen = '';
}

/* ============================================
   3) Consultar todas las reservas del usuario
   ============================================ */
$reservas = [];
try {
    $conexion = Conexion::getInstance()->getConnection();
    $sql      = "
        SELECT 
            id_reserva,
            fecha_reserva,
            cantidad_personas,
            comentarios
        FROM reservas
        WHERE id_cliente = :id_cliente
        ORDER BY fecha_reserva DESC
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_cliente', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error .= " Error al obtener reservas: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --color-primario: #E4007C;
            --color-secundario: #2ecc71;
            --color-texto: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: var(--color-texto);
            background-color: transparent;
        }

        #pagina-perfil {
            padding: 2rem;
        }

        #pagina-perfil .contenedor {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 3rem;
            text-align: center;
        }

        #pagina-perfil h2 {
            color: var(--color-primario);
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 600;
        }

        #pagina-perfil .foto-perfil {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid var(--color-primario);
            margin-bottom: 1.5rem;
        }

        #pagina-perfil form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        #pagina-perfil label {
            color: var(--color-texto);
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        #pagina-perfil input[type="file"],
        #pagina-perfil input[type="text"],
        #pagina-perfil input[type="password"] {
            border: 2px dashed var(--color-primario);
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            font-size: 1rem;
        }

        #pagina-perfil .btn {
            display: inline-block;
            padding: 1rem 2rem;
            color: var(--color-primario);
            text-decoration: none;
            border: 2px solid var(--color-primario);
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.1rem;
            margin: 0.5rem 0;
            background-color: white;
            transition: background-color 0.3s, color 0.3s;
        }

        #pagina-perfil .btn:hover {
            background-color: var(--color-primario);
            color: white;
        }

        /* Tabla de reservas */
        #mis-reservas {
            margin-top: 3rem;
            text-align: left;
        }

        #mis-reservas h3 {
            color: var(--color-primario);
            margin-bottom: 1rem;
            font-size: 1.75rem;
        }

        #mis-reservas table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        #mis-reservas th,
        #mis-reservas td {
            padding: 0.75rem;
            border: 1px solid #ddd;
        }

        #mis-reservas th {
            background-color: var(--color-primario);
            color: white;
            text-align: left;
        }

        #mis-reservas td {
            background-color: #f9f9f9;
        }

        #mis-reservas .accion-btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border-radius: 6px;
            text-decoration: none;
            margin-right: 0.5rem;
        }

        #mis-reservas .editar-btn {
            background-color: var(--color-secundario);
            color: white;
        }

        #mis-reservas .cancelar-btn {
            background-color: #dc3545;
            color: white;
        }

        @media (max-width: 600px) {
            #pagina-perfil .contenedor {
                width: 95%;
                padding: 2rem;
            }

            #pagina-perfil h2 {
                font-size: 1.6rem;
            }

            #mis-reservas table,
            #mis-reservas thead,
            #mis-reservas tbody,
            #mis-reservas th,
            #mis-reservas td,
            #mis-reservas tr {
                display: block;
            }

            #mis-reservas tr {
                margin-bottom: 1rem;
            }

            #mis-reservas th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            #mis-reservas td {
                position: relative;
                padding-left: 50%;
                white-space: normal;
                text-align: left;
            }

            #mis-reservas td:before {
                position: absolute;
                top: 0;
                left: 0;
                width: 45%;
                padding-right: 1rem;
                white-space: nowrap;
                font-weight: bold;
            }

            #mis-reservas td:nth-of-type(1):before {
                content: "Fecha y Hora";
            }

            #mis-reservas td:nth-of-type(2):before {
                content: "Personas";
            }

            #mis-reservas td:nth-of-type(3):before {
                content: "Comentarios";
            }

            #mis-reservas td:nth-of-type(4):before {
                content: "Acciones";
            }
        }
    </style>
</head>

<body>

    <div id="pagina-perfil">
        <div class="contenedor">
            <h2>Perfil</h2>

            <?php if ($success): ?>
                <p style="color: var(--color-secundario); font-weight: bold; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($success); ?>
                </p>
            <?php endif; ?>
            <?php if ($error): ?>
                <p style="color: #dc3545; font-weight: bold; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php endif; ?>

            <!-- Foto de perfil y bienvenida -->
            <?php if ($ruta_imagen): ?>
                <div class="perfil-info" style="display: flex; align-items: center; justify-content: center; margin-bottom: 2rem;">
                    <img src="<?php echo $ruta_imagen; ?>" alt="Foto de Perfil" class="foto-perfil">
                    <div style="margin-left: 10px; text-align: left;">
                        <h3 style="font-weight: bold; font-size: 2.5rem;">
                            Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>
                        </h3>
                    </div>
                </div>
            <?php else: ?>
                <p style="text-align: center; margin-bottom: 2rem;">No tienes una foto de perfil asignada.</p>
            <?php endif; ?>

            <!-- Formulario para subir foto -->
            <form method="POST" enctype="multipart/form-data">
                <label for="foto_perfil">Selecciona una imagen de perfil:</label>
                <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" required>
                <button type="submit" class="btn btn-exito" style="margin-top: 0.5rem;">Subir Imagen</button>
            </form>

            <!-- Formulario para cambiar nombre -->
            <form method="POST" style="margin-top: 2rem;">
                <label for="nuevo_nombre">Nuevo nombre de usuario:</label>
                <input type="text" name="nuevo_nombre" id="nuevo_nombre" required>
                <label for="contrasena">Contraseña actual:</label>
                <input type="password" name="contrasena" id="contrasena" required>
                <button type="submit" class="btn btn-exito" style="margin-top: 0.5rem;">Actualizar Nombre</button>
            </form>

            <a href="<?php echo SITE_URL; ?>/index.php" class="btn" style="margin-top: 2rem;">Volver al inicio</a>

            <!-- ===========================
                 Sección: Mis Reservas
                 =========================== -->
            <div id="mis-reservas">
                <h3>Mis Reservas</h3>

                <?php if (empty($reservas)): ?>
                    <p>Aún no tienes reservas registradas.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Cant. Personas</th>
                                <th>Comentarios</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $r): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($r['fecha_reserva'])); ?></td>
                                    <td><?php echo htmlspecialchars($r['cantidad_personas']); ?></td>
                                    <td><?php echo htmlspecialchars($r['comentarios']); ?></td>
                                    <td>
                                        <!-- Enlace a editar_reserva.php?id=… -->
                                        <a
                                            href="<?php echo SITE_URL; ?>/reservas/editar_reserva.php?id_reserva=<?php echo $r['id_reserva']; ?>"
                                            class="accion-btn editar-btn">
                                            Editar
                                        </a>

                                        <!-- Enlace a cancelar_reserva.php?id=… -->
                                        <a
                                            href="<?php echo SITE_URL; ?>/reservas/cancelar_reserva.php?id_reserva=<?php echo $r['id_reserva']; ?>"
                                            class="accion-btn cancelar-btn"
                                            onclick="return confirm('¿Seguro que deseas cancelar esta reserva?');">
                                            Cancelar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <!-- FIN sección Mis Reservas -->

        </div>
    </div>
</body>

</html>

<?php include('../footer.php'); ?>