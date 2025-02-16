<?php
session_start();
include '../configuracion.php';
include_once('../header.php');

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    echo "Por favor, inicia sesión para actualizar tu perfil.";
    exit();
}

$id_usuario = $_SESSION['usuario']['id'];

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
                echo "Imagen de perfil actualizada correctamente.";
            } else {
                echo "Error al subir la imagen. Código de error: " . $_FILES['foto_perfil']['error'];
            }
        } catch (Exception $e) {
            echo "Error al procesar la acción: " . $e->getMessage();
        }
    }

    if (isset($_POST['nuevo_nombre']) && isset($_POST['contrasena'])) {
        try {
            $nuevo_nombre = $_POST['nuevo_nombre'];
            $contrasena = $_POST['contrasena'];
            $conexion = Conexion::getInstance()->getConnection();
            $query = "SELECT contrasena FROM usuarios WHERE id_usuario = :id_usuario";
            $stmt = $conexion->prepare($query);
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
            } else {
                echo "Contraseña incorrecta.";
            }
        } catch (Exception $e) {
            echo "Error al actualizar el nombre: " . $e->getMessage();
        }
    }
}

try {
    $conexion = Conexion::getInstance()->getConnection();
    $query = "SELECT foto_perfil FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt = $conexion->prepare($query);
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
    echo "Error al obtener la foto de perfil: " . $e->getMessage();
    $ruta_imagen = '';
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

        #pagina-perfil input[type="file"] {
            border: 2px dashed var(--color-primario);
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            font-size: 1rem;
        }

        #pagina-perfil .btn {
            display: inline-block;
            padding: 1rem 2rem;
            color: var(--color-primario);
            text-decoration: none;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.1rem;
            margin: 0.5rem 0;
        }

        #pagina-perfil .btn-exito {
            color: var(--color-primario);
        }

        @media (max-width: 600px) {
            #pagina-perfil .contenedor {
                width: 95%;
                padding: 2rem;
            }

            #pagina-perfil h2 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>

    <div id="pagina-perfil">
        <div class="contenedor">
            <h2>Perfil</h2>
            <?php if ($ruta_imagen): ?>
                <div class="perfil-info" style="display: flex; align-items: center; justify-content: center;">
                    <img src="<?php echo $ruta_imagen; ?>" alt="Foto de Perfil" class="foto-perfil">
                    <div style="margin-left: 10px;">
                        <h3 style="font-weight: bold; font-size: 3.5rem; position: relative; text-align: center;">
                            Bienvenido, <?php echo $_SESSION['usuario']['nombre']; ?></h3>
                        <p style="font-size: 1.3rem; margin-top: 10px; font-weight: lighter; text-align: center;">PANNNNNNN
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <p style="text-align: center;">No tienes una foto de perfil asignada.</p>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data"
                style="display: flex; flex-direction: column; align-items: center;">
                <label for="foto_perfil" style="text-align: center;">Selecciona una imagen:</label>
                <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" required>
                <button type="submit" class="btn btn-exito" style="margin-top: 10px;">Subir Imagen</button>
            </form>

            <form method="POST" style="margin-top: 20px;">
                <label for="nuevo_nombre">Nuevo nombre de usuario:</label>
                <input type="text" name="nuevo_nombre" id="nuevo_nombre" required style="border: 1px solid var(--color-primario);">
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" required style="border: 1px solid var(--color-primario);">
                <button type="submit" class="btn btn-exito">Actualizar Nombre</button>
            </form>

            <a href="<?php echo SITE_URL; ?>/index.php" class="btn" style="margin-top: 10px;">Volver</a>
        </div>
    </div>
</body>

</html>

<?php include('../footer.php'); ?>