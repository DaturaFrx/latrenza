<?php
if (!defined('SITE_URL')) {
    die('Acceso directo no permitido');
}

$ruta_imagen_perfil = SITE_URL . "/files/cot.jpg";

if (isset($_SESSION['usuario']['id'])) {
    $id_usuario = $_SESSION['usuario']['id'];

    try {
        $conexion = Conexion::getInstance()->getConnection();
        $query = "SELECT foto_perfil FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch();

        if ($usuario && !empty($usuario['foto_perfil'])) {
            $foto_perfil = $usuario['foto_perfil'];
            $ruta_imagen_perfil = SITE_URL . "/imagenes_perfil/" . $foto_perfil;
        }
    } catch (Exception $e) {
        error_log("Error al obtener la imagen de perfil: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="<?php echo SITE_URL; ?>/css/home-styles.css" rel="stylesheet">
    <link href="<?php echo SITE_URL; ?>/css/header.css" rel="stylesheet">
</head>

<body class="bg-amber-50">
    <nav class="bg-amber-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?php echo SITE_URL; ?>" class="text-2xl font-bold"><?php echo SITE_NAME; ?></a>
            <div class="hidden md:flex space-x-6">
                <a href="<?php echo SITE_URL; ?>/productos/categorias.php">Productos</a>
                <a href="<?php echo SITE_URL; ?>/reservas/reservas.php">Reservas</a>
                <a href="<?php echo SITE_URL; ?>/eventos/eventos.php">Eventos</a>
                <a href="<?php echo SITE_URL; ?>/blog/blog.php">Noticias y Opiniones</a>
                <a href="<?php echo SITE_URL; ?>/contacto/contacto.php">Contacto</a>
            </div>
            <div class="flex items-center space-x-4">
                <?php if (isLoggedIn()): ?>
                    <div class="relative">
                        <img src="<?php echo $ruta_imagen_perfil; ?>" alt="Imagen de perfil"
                            class="w-8 h-8 rounded-full cursor-pointer" id="profile-pic" onclick="toggleProfileMenu()">
                        <div class="absolute right-0 hidden bg-white text-black rounded shadow-lg z-10" id="profile-menu">
                            <div class="p-2">
                                <p class="font-bold"><?php echo $_SESSION['usuario']['nombre']; ?></p>
                                <p><?php echo $_SESSION['usuario']['email']; ?></p>
                                <a href="<?php echo SITE_URL; ?>/usuario/perfil.php" class="block mt-2 text-blue-500">Ver
                                    Perfil</a>
                                <a href="<?php echo SITE_URL; ?>/lealtad/puntosLealtad.php"
                                    class="block mt-2 text-green-500">Puntos de Lealtad</a>
                                <a href="<?php echo SITE_URL; ?>/admin/logout.php" class="block mt-2 text-red-500">Cerrar
                                    Sesión</a>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo SITE_URL; ?>/carrito/carrito.php" class="relative">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if (getCarritoCount() > 0): ?>
                            <span
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                <?php echo getCarritoCount(); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php elseif (isset($_COOKIE['login_cookie'])): ?>
                    <a href="<?php echo SITE_URL; ?>/carrito/carrito.php" class="relative">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if (getCarritoCount() > 0): ?>
                            <span
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                <?php echo getCarritoCount(); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/admin/login.php">Iniciar Sesión</a>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/admin/login.php">Iniciar Sesión</a>
                    <a href="<?php echo SITE_URL; ?>/admin/registrarse.php">Crear Cuenta</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script>
        function toggleProfileMenu() {
            var menu = document.getElementById("profile-menu");
            if (menu.classList.contains("hidden")) {
                menu.classList.remove("hidden");
            } else {
                menu.classList.add("hidden");
            }
        }

        window.onclick = function (event) {
            var menu = document.getElementById("profile-menu");
            var profilePic = document.getElementById("profile-pic");

            if (event.target !== profilePic && !menu.contains(event.target)) {
                menu.classList.add("hidden");
            }
        };
    </script>
</body>

</html>