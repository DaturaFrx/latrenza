<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo SITE_URL; ?>/css/footer.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" async></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .social-links a:hover {
            transition: color 0.3s ease;
        }
    </style>
</head>

<body>
    <footer class="bg-amber-900 text-white py-12">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 px-6">
            <div>
                <h3 class="text-xl font-bold mb-4">Mi Panadería</h3>
                <p>Tu panadería artesanal de confianza desde 1990.</p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Horario</h4>
                <p class="mb-2">Lunes a Viernes: 7:00 - 20:00</p>
                <p>Sábados y Domingos: 8:00 - 18:00</p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Contacto</h4>
                <p class="mb-2">Teléfono: (123) 456-7890</p>
                <p class="mb-2">Email: info@mipanaderia.com</p>
                <p>Dirección: Calle Principal #123</p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Síguenos</h4>
                <div class="flex flex-col space-y-3 social-links">
                    <a href="https://www.facebook.com/dummy" class="hover:text-green-500 flex items-center">
                        <i class="fab fa-facebook fa-2x"></i>
                        <span class="ml-2">Facebook</span>
                    </a>
                    <a href="https://www.instagram.com/dummy" class="hover:text-white-300 flex items-center">
                        <i class="fab fa-instagram fa-2x"></i>
                        <span class="ml-2">Instagram</span>
                    </a>
                    <a href="https://www.twitter.com/dummy" class="hover:text-red-500 flex items-center">
                        <i class="fab fa-twitter fa-2x"></i>
                        <span class="ml-2">Twitter</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="container mx-auto mt-8 pt-8 border-t border-amber-800 text-center px-6">
            <p id="clickable-rights">&copy; 2024 Mi Panadería. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        const audio = new Audio('files/jt.mp3');
        audio.preload = 'auto';

        let hoverTimeout;

        // Start the hover timer when the user begins hovering over the element
        document.getElementById('clickable-rights').addEventListener('mouseenter', () => {
            hoverTimeout = setTimeout(() => {
                audio.play().catch(error => {
                    console.log('Audio playback failed:', error);
                });
            }, 5000); // 5 seconds
        });

        // Clear the hover timer if the user moves the mouse away before 5 seconds
        document.getElementById('clickable-rights').addEventListener('mouseleave', () => {
            clearTimeout(hoverTimeout);
        });
    </script>

</body>

</html>