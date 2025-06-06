<?php
// contacto.php
session_start();
include '../configuracion.php';
include '../header.php';
?>

<div class="container mx-auto px-4 py-12 space-y-16">
    <!-- Hero Section -->
    <div class="text-center">
        <h1 class="text-5xl font-bold text-gray-800 mb-4">🍞 Contáctanos</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Desde el rincón más remoto del océano, horneamos los mejores panes del mundo
        </p>
    </div>

    <!-- Contact Information -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-8 shadow-lg">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">📞 Información de Contacto</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-amber-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🛒</span>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Pedidos y Reservas</h3>
                <p class="text-gray-600">+52 (664) 123-4567</p>
                <p class="text-sm text-gray-500">Lun-Dom: 6:00 AM - 8:00 PM</p>
            </div>
            <div class="text-center">
                <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">💬</span>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Atención al Cliente</h3>
                <p class="text-gray-600">+52 (664) 765-4321</p>
                <p class="text-sm text-gray-500">24/7 disponible</p>
            </div>
            <div class="text-center">
                <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🔧</span>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Soporte Técnico</h3>
                <p class="text-gray-600">+52 (664) 987-6543</p>
                <p class="text-sm text-gray-500">Para pedidos online</p>
            </div>
        </div>
    </div>

    <div>
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-8">🌾 Datos Curiosos del Pan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="text-4xl mb-4">🏺</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Antigüedad Milenaria</h3>
                <p class="text-gray-600">El pan existe desde hace más de 14,000 años. Los primeros panes eran planos y se hacían con granos silvestres molidos.</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="text-4xl mb-4">🇫🇷</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Ley Francesa</h3>
                <p class="text-gray-600">En Francia existe una ley que regula el precio del pan desde 1993. ¡El pan es tan importante que está regulado por el gobierno!</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="text-4xl mb-4">🍞</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Consumo Mundial</h3>
                <p class="text-gray-600">Una persona promedio consume 53 kg de pan al año. ¡Eso equivale a aproximadamente 1,500 rebanadas!</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="text-4xl mb-4">🌍</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Variedad Global</h3>
                <p class="text-gray-600">Existen más de 200 tipos diferentes de pan en todo el mundo, desde el naan indio hasta el pumpernickel alemán.</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="text-4xl mb-4">🎯</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Expresión Popular</h3>
                <p class="text-gray-600">"El mejor pan desde rebanadas" es una expresión que se originó en 1928 cuando se inventó el pan pre-rebanado.</p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="text-4xl mb-4">🧬</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Ciencia del Pan</h3>
                <p class="text-gray-600">La levadura utilizada en el pan es un hongo vivo. Una cucharadita contiene aproximadamente 20 mil millones de células de levadura.</p>
            </div>
        </div>
    </div>

    <!-- Gallery -->
    <div>
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-8">📸 Nuestra Panadería en Imágenes</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="group rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                <div class="relative overflow-hidden">
                    <img src="<?php echo SITE_URL; ?>/files/bread.jpg" alt="Pan Artesanal"
                        class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-white">
                            <h3 class="font-bold text-lg">Pan Artesanal</h3>
                            <p class="text-sm">Horneado diariamente</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="group rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                <div class="relative overflow-hidden">
                    <img src="<?php echo SITE_URL; ?>/files/cot.jpg" alt="Fachada de la Panadería"
                        class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-white">
                            <h3 class="font-bold text-lg">Nuestra Fachada</h3>
                            <p class="text-sm">Acogedora y tradicional</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="group rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                <div class="relative overflow-hidden">
                    <img src="<?php echo SITE_URL; ?>/files/pan_integral.jpg" alt="Interior de la Panadería"
                        class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-white">
                            <h3 class="font-bold text-lg">Ambiente Acogedor</h3>
                            <p class="text-sm">Disfruta nuestro espacio</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl p-8 shadow-lg">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-6">Donde nos encontramos</h2>
        <div class="text-center mb-6">
            <p class="text-lg text-gray-600 mb-2">
                Desde Point Nemo, el punto más remoto del océano, horneamos para el mundo
            </p>
            <p class="text-gray-500">
                Coordenadas: <span class="font-mono font-bold text-blue-600">48°52.6′S 123°23.6′W</span>
            </p>
            <p class="text-sm text-gray-400 mt-2">
                A 2,700 km de cualquier masa terrestre – ¡Pero nuestro pan llega fresco a tu mesa!
            </p>
        </div>

        <!-- Mapa incrustado de Google Maps -->
        <div class="w-full h-96 rounded-xl shadow-lg border-4 border-white overflow-hidden">
            <iframe
                width="100%"
                height="100%"
                frameborder="0"
                style="border:0"
                src="https://www.google.com/maps?q=-48.876667,-123.393333&hl=es;z=3&output=embed"
                allowfullscreen
                loading="lazy">
            </iframe>
        </div>
    </div>

</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-sA+e2oRQTmk6tW9HZsKfYDJZcVQH1L2ebxXw2fQp9A4=" crossorigin="" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-o9N1j5c9nqY65uU9GuehT0kqL9zzWONm8q+9gQN02hI=" crossorigin=""></script>

<style>
    .custom-popup .leaflet-popup-content-wrapper {
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .custom-popup .leaflet-popup-tip {
        background: white;
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-10px);
        }

        60% {
            transform: translateY(-5px);
        }
    }

    .custom-bakery-icon:hover {
        animation: bounce 1s;
    }
</style>

<?php include '../footer.php'; ?>