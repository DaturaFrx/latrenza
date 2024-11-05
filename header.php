<?php
// Asegurarse de que no se acceda directamente
if (!defined('SITE_URL')) {
    die('Acceso directo no permitido');
}
?>
<!DOCTYPE html>
<html lang="es">
<link href="<?php echo SITE_URL; ?>/css/header.css" rel="stylesheet">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="<?php echo SITE_URL; ?>/css/home-styles.css" rel="stylesheet">
</head>

<body class="bg-amber-50">
    <nav class="bg-amber-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?php echo SITE_URL; ?>" class="text-2xl font-bold"><?php echo SITE_NAME; ?></a>
            <div class="hidden md:flex space-x-6">
                <a href="<?php echo SITE_URL; ?>/productos.php">Productos</a>
                <a href="<?php echo SITE_URL; ?>/reservas.php">Reservas</a>
                <a href="<?php echo SITE_URL; ?>/eventos.php">Eventos</a>
                <a href="<?php echo SITE_URL; ?>/blog.php">Blog</a>
                <a href="<?php echo SITE_URL; ?>/contacto.php">Contacto</a>
            </div>
            <div class="flex items-center space-x-4">
                <?php if (isLoggedIn()): ?>
                    <div class="relative">
                        <img src="<?php echo SITE_URL; ?>/images/dummy-profile-pic.png" alt="Profile Picture"
                            class="w-8 h-8 rounded-full cursor-pointer" id="profile-pic">
                        <div class="absolute right-0 hidden bg-white text-black rounded shadow-lg z-10" id="profile-menu">
                            <div class="p-2">
                                <p class="font-bold"><?php echo $_SESSION['usuario']['nombre']; ?></p>
                                <p><?php echo $_SESSION['usuario']['email']; ?></p>
                                <a href="<?php echo SITE_URL; ?>/perfil.php" class="block mt-2 text-blue-500">Ver Perfil</a>
                                <a href="<?php echo SITE_URL; ?>/admin/logout.php" class="block mt-2 text-red-500">Cerrar
                                    Sesión</a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/admin/login.php">Iniciar Sesión</a>
                    <a href="<?php echo SITE_URL; ?>/admin/registrarse.php">Crear Cuenta</a>
                <?php endif; ?>
                <a href="<?php echo SITE_URL; ?>/carrito.php" class="relative">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if (getCarritoCount() > 0): ?>
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                            <?php echo getCarritoCount(); ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </nav>

    <script>
        // Toggle profile menu on profile picture click
        document.getElementById('profile-pic').addEventListener('click', function () {
            const profileMenu = document.getElementById('profile-menu');
            profileMenu.classList.toggle('hidden');
        });

        // Close the profile menu if clicked outside
        window.addEventListener('click', function (event) {
            const profileMenu = document.getElementById('profile-menu');
            if (!event.target.closest('#profile-pic') && !event.target.closest('#profile-menu')) {
                profileMenu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>