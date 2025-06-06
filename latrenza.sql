-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2025 at 05:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `latrenza`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `imagen` mediumblob NOT NULL,
  `url-img` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `titulo`, `contenido`, `imagen`, `url-img`, `creado_en`) VALUES
(1, 'Entrada de Blog 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '', 'https://pbs.twimg.com/media/E3j9mNNX0AQIaQj.jpg', '2024-11-19 00:50:52'),
(2, 'Entrada de Blog 2', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '', '', '2024-11-19 00:50:52'),
(3, 'prueba', 'gagaga gato coooool', '', 'https://i.redd.it/3ajy6xo5hrib1.png', '2024-11-19 01:39:00'),
(4, 'Titulo 4', 'Contenido del cuarto artículo.', '', 'https://www.w3schools.com/howto/img_parallax.jpg', '2024-11-15 08:00:00'),
(5, 'Titulo 5', 'Contenido del quinto artículo.', '', 'https://www.w3schools.com/w3images/snow.jpg', '2024-11-14 08:00:00'),
(6, 'Titulo 6', 'Contenido del sexto artículo.', '', 'https://www.w3schools.com/w3images/mountains.jpg', '2024-11-13 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `boletines`
--

CREATE TABLE `boletines` (
  `id_boletin` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_publicacion` datetime DEFAULT current_timestamp(),
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `boletines`
--

INSERT INTO `boletines` (`id_boletin`, `titulo`, `contenido`, `fecha_publicacion`, `estado`) VALUES
(1, 'Promoción de Panes', '¡Este mes, 20% de descuento en todos los panes!', '2024-11-03 16:06:52', 'activo'),
(2, 'Taller de Panadería', 'Inscríbete a nuestro taller de panadería, comienza el 15 de noviembre.', '2024-11-03 16:06:52', 'activo'),
(3, 'Nuevos Productos', 'Ya están disponibles nuestros nuevos sabores de galletas.', '2024-11-03 16:06:52', 'activo'),
(4, 'Feria del Pan', 'Te invitamos a la Feria del Pan el 1 de diciembre. ¡No te lo pierdas!', '2024-11-03 16:06:52', 'activo'),
(5, 'Recetas de la Semana', 'Comparte tus recetas favoritas utilizando nuestros productos.', '2024-11-03 16:06:52', 'activo'),
(6, 'Día del Pan de Muerto', 'Celebra el Día de Muertos con nuestro pan especial.', '2024-11-03 16:06:52', 'activo'),
(7, 'Eventos de Navidad', 'Consulta nuestros eventos especiales para Navidad.', '2024-11-03 16:06:52', 'activo'),
(8, 'Noche de Cata', 'Únete a nuestra cata de vinos y panes el 15 de diciembre.', '2024-11-03 16:06:52', 'activo'),
(9, 'Rebajas de Año Nuevo', 'Gran rebaja en todos los productos después de Año Nuevo.', '2024-11-03 16:06:52', 'activo'),
(10, 'Boletín de Noviembre', 'Mira nuestras ofertas especiales para el mes de noviembre.', '2024-11-03 16:06:52', 'activo'),
(11, 'Concurso de Panadería', 'Participa en nuestro concurso de panadería el 25 de noviembre.', '2024-11-03 16:06:52', 'activo'),
(12, 'Cuidado del Pan', 'Consejos para conservar mejor tu pan en casa.', '2024-11-03 16:06:52', 'activo'),
(13, 'Recetas Fáciles', 'Recetas sencillas para hacer en casa con nuestros productos.', '2024-11-03 16:06:52', 'activo'),
(14, 'Novedades del Mes', 'Nuevos productos y sabores cada mes, ¡mantente al tanto!', '2024-11-03 16:06:52', 'activo'),
(15, 'Taller de Postres', 'Inscríbete para nuestro taller de postres, solo quedan unos pocos lugares.', '2024-11-03 16:06:52', 'activo'),
(16, 'Celebración del Día de la Mujer', 'Eventos especiales y promociones para el Día de la Mujer.', '2024-11-03 16:06:52', 'activo'),
(17, 'Pan de Ajo', 'Prueba nuestro nuevo pan de ajo, disponible este mes.', '2024-11-03 16:06:52', 'activo'),
(18, 'Eventos para Niños', 'Consulta nuestros eventos especiales para niños durante las vacaciones.', '2024-11-03 16:06:52', 'activo'),
(19, 'Noche de Juegos', 'Ven a disfrutar de una noche de juegos y pan el 10 de diciembre.', '2024-11-03 16:06:52', 'activo'),
(20, 'Recorrido de la Panadería', 'Te invitamos a conocer el proceso de elaboración de nuestros panes.', '2024-11-03 16:06:52', 'activo'),
(21, 'Muestras Gratis', 'Ven y prueba nuestras nuevas variedades de pan en la panadería.', '2024-11-03 16:06:52', 'activo');

-- --------------------------------------------------------

--
-- Table structure for table `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1,
  `fecha_agregado` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carrito`
--

INSERT INTO `carrito` (`id_carrito`, `id_usuario`, `id_producto`, `cantidad`, `fecha_agregado`) VALUES
(24, 29, 1, 1, '2024-12-03 10:42:48'),
(39, 28, 1, 1, '2025-06-06 07:57:11');

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`, `descripcion`) VALUES
(1, 'Pan', 'Variedad de panes, incluyendo pan blanco, integral, de masa madre y sin gluten.'),
(2, 'Integral', 'Productos elaborados con ingredientes integrales, como panes y galletas saludables.'),
(3, 'Dulces', 'Productos de panadería dulce, bollos y pasteles.'),
(4, 'Tartas', 'Tartas y postres.'),
(5, 'Galletas', 'Variedad de galletas.');

-- --------------------------------------------------------

--
-- Table structure for table `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `comentario` text NOT NULL,
  `fecha_comentario` datetime DEFAULT current_timestamp(),
  `calificacion` int(11) DEFAULT NULL CHECK (`calificacion` between 1 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comentarios`
--

INSERT INTO `comentarios` (`id_comentario`, `id_producto`, `id_usuario`, `comentario`, `fecha_comentario`, `calificacion`) VALUES
(1, 1, 1, 'Excelente pan, siempre fresco y suave.', '2024-11-03 16:05:54', 5),
(2, 2, 2, 'Muy bueno, aunque me gustaría que tuviera más chocolate.', '2024-11-03 16:05:54', 4),
(3, 3, 1, 'Ideal para el desayuno.', '2024-11-03 16:05:54', 5),
(4, 4, 3, 'El pan integral es un poco seco.', '2024-11-03 16:05:54', 3),
(5, 5, 2, 'Deliciosa focaccia, la mejor que he probado.', '2024-11-03 16:05:54', 5),
(6, 6, 4, 'El pan dulce es un poco más caro de lo esperado.', '2024-11-03 16:05:54', 4),
(7, 7, 5, 'No me gusta, estaba un poco quemado.', '2024-11-03 16:05:54', 2),
(8, 8, 3, 'Muy sabroso y buena presentación.', '2024-11-03 16:05:54', 5),
(9, 9, 4, 'El sabor es bueno, pero el tamaño es pequeño.', '2024-11-03 16:05:54', 3),
(10, 10, 5, 'Perfecto para las meriendas.', '2024-11-03 16:05:54', 4),
(11, 11, 6, 'Gran variedad, me encanta.', '2024-11-03 16:05:54', 5),
(12, 12, 7, 'Podría mejorar la textura.', '2024-11-03 16:05:54', 3),
(13, 13, 8, 'Los mejores panes de la ciudad.', '2024-11-03 16:05:54', 5),
(14, 14, 9, 'Algo caro, pero vale la pena.', '2024-11-03 16:05:54', 4),
(15, 15, 10, 'No compré suficiente, muy sabroso.', '2024-11-03 16:05:54', 5),
(16, 16, 11, 'Recomiendo el pan de ajo, ¡delicioso!', '2024-11-03 16:05:54', 5),
(17, 17, 12, 'Buen servicio, aunque el pan no estaba a la altura.', '2024-11-03 16:05:54', 2),
(18, 18, 13, 'El pan de maíz es genial.', '2024-11-03 16:05:54', 5),
(19, 19, 14, 'Los productos son frescos, pero el servicio es lento.', '2024-11-03 16:05:54', 3),
(20, 20, 15, 'Siempre vuelvo por el pan de chocolate.', '2024-11-03 16:05:54', 4);

-- --------------------------------------------------------

--
-- Table structure for table `destacados`
--

CREATE TABLE `destacados` (
  `id_destacado` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `tipo_destacado` enum('producto','evento','promocion') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destacados`
--

INSERT INTO `destacados` (`id_destacado`, `id_producto`, `tipo_destacado`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(1, 1, 'producto', '2024-01-01', '2024-01-31', 'activo'),
(2, 2, 'producto', '2024-01-15', '2024-02-15', 'activo'),
(3, 3, 'promocion', '2024-01-10', '2024-01-20', 'activo'),
(4, 4, 'evento', '2024-01-25', '2024-01-26', 'activo'),
(5, 5, 'producto', '2024-02-01', '2024-02-28', 'activo'),
(6, 6, 'promocion', '2024-02-10', '2024-02-20', 'activo'),
(7, 7, 'evento', '2024-02-15', '2024-02-15', 'activo'),
(8, 1, 'producto', '2024-03-01', '2024-03-31', 'inactivo'),
(9, 8, 'promocion', '2024-03-05', '2024-03-12', 'activo'),
(10, 2, 'evento', '2024-03-10', '2024-03-10', 'activo'),
(11, 9, 'producto', '2024-03-15', '2024-04-15', 'activo'),
(12, 10, 'promocion', '2024-03-20', '2024-03-30', 'inactivo'),
(13, 11, 'producto', '2024-04-01', '2024-04-30', 'activo'),
(14, 12, 'evento', '2024-04-05', '2024-04-06', 'activo'),
(15, 13, 'promocion', '2024-04-10', '2024-04-15', 'activo'),
(16, 3, 'producto', '2024-04-15', '2024-05-15', 'inactivo'),
(17, 14, 'promocion', '2024-05-01', '2024-05-10', 'activo'),
(18, 15, 'evento', '2024-05-15', '2024-05-16', 'activo'),
(19, 16, 'producto', '2024-05-20', '2024-06-20', 'activo'),
(20, 17, 'promocion', '2024-06-01', '2024-06-10', 'activo');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id_detalle` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id_detalle`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 2, 1.50),
(2, 1, 3, 1, 1.75),
(3, 2, 5, 1, 3.00),
(4, 2, 4, 2, 2.50),
(5, 3, 6, 1, 2.25),
(6, 4, 7, 3, 2.00),
(7, 5, 8, 1, 4.00),
(8, 6, 2, 2, 2.00),
(9, 7, 1, 5, 1.50),
(10, 8, 9, 1, 2.75),
(11, 9, 10, 2, 1.80),
(12, 10, 11, 3, 1.90),
(13, 11, 12, 4, 1.60),
(14, 12, 13, 1, 2.00),
(15, 13, 14, 2, 2.75),
(16, 14, 15, 1, 4.50),
(17, 15, 16, 2, 3.50),
(18, 16, 17, 1, 3.00),
(19, 17, 18, 3, 2.00),
(20, 18, 19, 1, 1.20),
(21, 19, 20, 4, 2.50);

-- --------------------------------------------------------

--
-- Table structure for table `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `puesto` enum('admin','trabajador') NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `salario` decimal(10,2) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `nombre`, `correo_electronico`, `puesto`, `fecha_contratacion`, `salario`, `contrasena`) VALUES
(12, 'Admin', 'rivera@gmail.com', 'admin', '2024-11-18', 5000.00, '$2y$10$KYNCyEVC9oN91c6znOT.GetXbbwNt8W/6sWJFEZLx22xD7WneEaB2'),
(25, 'prueba', 'prueba@gmail.com', 'admin', '2024-11-18', 100000.00, '$2y$10$LDlHZszbUNlHuaI0cnv8Dexq08jA7XAxKIw32foWX4QYHatGea2Pm');

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `nombre_evento` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_evento` datetime NOT NULL,
  `capacidad` int(11) NOT NULL,
  `costo` decimal(10,2) NOT NULL,
  `estado` enum('programado','cancelado','finalizado') DEFAULT 'programado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventos`
--

INSERT INTO `eventos` (`id_evento`, `nombre_evento`, `descripcion`, `fecha_evento`, `capacidad`, `costo`, `estado`) VALUES
(1, 'Taller de Panadería', 'Aprende a hacer tu propio pan.', '2024-11-15 10:00:00', 20, 50.00, 'programado'),
(2, 'Degustación de Panes', 'Ven a probar diferentes tipos de panes.', '2024-11-20 17:00:00', 30, 20.00, 'programado'),
(3, 'Feria del Pan', 'Celebra el Día del Pan con nosotros.', '2024-12-01 12:00:00', 100, 0.00, 'programado'),
(4, 'Curso de Postres', 'Aprende a hacer deliciosos postres.', '2024-12-05 15:00:00', 15, 75.00, 'programado'),
(5, 'Concierto en la Panadería', 'Disfruta de música en vivo mientras comes pan.', '2024-12-10 19:00:00', 50, 30.00, 'programado'),
(6, 'Cata de Vinos y Pan', 'Combinación perfecta de vinos y panes.', '2024-12-15 18:00:00', 25, 40.00, 'programado'),
(7, 'Navidad en la Panadería', 'Celebra la Navidad con nosotros.', '2024-12-24 16:00:00', 60, 0.00, 'programado'),
(8, 'Taller de Galletas', 'Aprende a hacer galletas de diferentes sabores.', '2024-12-30 10:00:00', 20, 45.00, 'programado'),
(9, 'Celebración de Año Nuevo', 'Fiesta de Año Nuevo con pan y champán.', '2024-12-31 21:00:00', 100, 100.00, 'programado'),
(10, 'Taller de Cocina para Niños', 'Diviértete haciendo pan con tus hijos.', '2024-11-10 11:00:00', 15, 30.00, 'programado'),
(11, 'Mercado de Productores', 'Apoya a los productores locales.', '2024-11-22 09:00:00', 50, 0.00, 'programado'),
(12, 'Día de la Mujer', 'Evento especial para celebrar a las mujeres.', '2024-11-08 14:00:00', 30, 20.00, 'programado'),
(13, 'Feria de Comida', 'Muestra de los mejores platillos de la ciudad.', '2024-12-18 11:00:00', 80, 10.00, 'programado'),
(14, 'Exhibición de Panes Tradicionales', 'Muestra de panes de diferentes culturas.', '2024-11-29 12:00:00', 40, 15.00, 'programado'),
(15, 'Taller de Decoración de Pasteles', 'Aprende a decorar pasteles como un profesional.', '2024-11-18 15:00:00', 10, 60.00, 'programado'),
(16, 'Festival de la Cerveza y Pan', 'Disfruta de cervezas artesanales y panes.', '2024-12-05 17:00:00', 50, 25.00, 'programado'),
(17, 'Concursos de Panadería', 'Compite por el mejor pan de la ciudad.', '2024-11-25 14:00:00', 30, 0.00, 'programado'),
(18, 'Cuentacuentos para Niños', 'Disfruta de historias mientras comes pan.', '2024-12-12 11:00:00', 20, 5.00, 'programado'),
(19, 'Día del Pan de Muerto', 'Celebra el Día de los Muertos con pan especial.', '2024-11-02 09:00:00', 100, 0.00, 'programado'),
(20, 'Maratón de Pan', 'Corre y disfruta de una rebanada de pan al final.', '2024-11-30 08:00:00', 200, 10.00, 'programado');

-- --------------------------------------------------------

--
-- Table structure for table `facturas`
--

CREATE TABLE `facturas` (
  `id_factura` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pagada','pendiente','cancelada') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facturas`
--

INSERT INTO `facturas` (`id_factura`, `id_pedido`, `id_cliente`, `fecha`, `total`, `estado`) VALUES
(1, 1, 1, '2024-01-05', 150.00, 'pagada'),
(2, 2, 2, '2024-01-06', 200.00, 'pendiente'),
(3, 3, 1, '2024-01-07', 80.00, 'pagada'),
(4, 4, 3, '2024-01-08', 120.00, 'cancelada'),
(5, 5, 2, '2024-01-09', 220.00, 'pagada'),
(6, 6, 1, '2024-01-10', 180.00, 'pendiente'),
(7, 7, 4, '2024-01-11', 90.00, 'pagada'),
(8, 8, 5, '2024-01-12', 110.00, 'pagada'),
(9, 9, 2, '2024-01-13', 300.00, 'cancelada'),
(10, 10, 3, '2024-01-14', 60.00, 'pendiente'),
(11, 11, 1, '2024-01-15', 50.00, 'pagada'),
(12, 12, 2, '2024-01-16', 75.00, 'pagada'),
(13, 13, 4, '2024-01-17', 200.00, 'pendiente'),
(14, 14, 3, '2024-01-18', 85.00, 'pagada'),
(15, 15, 5, '2024-01-19', 130.00, 'cancelada'),
(16, 16, 1, '2024-01-20', 170.00, 'pagada'),
(17, 17, 2, '2024-01-21', 250.00, 'pendiente'),
(18, 18, 3, '2024-01-22', 90.00, 'pagada'),
(19, 19, 4, '2024-01-23', 150.00, 'pagada'),
(20, 20, 5, '2024-01-24', 400.00, 'cancelada');

-- --------------------------------------------------------

--
-- Table structure for table `fotos`
--

CREATE TABLE `fotos` (
  `id_foto` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `url_foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fotos`
--

INSERT INTO `fotos` (`id_foto`, `id_producto`, `url_foto`) VALUES
(2, 1, 'https://www.debate.com.mx/__export/1697647053120/sites/debate/img/2023/10/18/pan_blanco_y_salud.jpg_1902800913.jpg'),
(3, 2, 'https://static01.nyt.com/images/2023/07/21/multimedia/21baguettesrex-hbkc/21baguettesrex-hbkc-superJumbo.jpg'),
(4, 3, 'https://assets.tmecosys.com/image/upload/t_web767x639/img/recipe/ras/Assets/D5666EAA-F144-4205-B5D2-AE84080AA898/Derivates/18FA3AC2-CE9E-4F97-8D75-F3B67D43C599.jpg'),
(5, 4, 'https://www.jonathanmelendez.com/wp-content/uploads/2023/05/0S9A7599.jpg'),
(6, 5, 'https://assets.elgourmet.com/wp-content/uploads/2024/04/E8A7999-1024x683.jpg.webp'),
(7, 6, 'https://assets.zuckerjagdwurst.com/u4q97pkqszcdxj62j8adg1z9xdpz/1110/701/55/true/center/R870-Vegane-Schoko-Croissants-01.jpg?animated=false'),
(8, 7, 'https://i.blogs.es/8e3bfe/pan_ajo/840_560.jpg'),
(9, 8, 'https://content-cocina.lecturas.com/medio/2023/03/23/el-mejor-pastel-de-chocolate_24bd9cda_1200x1200.jpg'),
(10, 9, 'https://comedera.com/wp-content/uploads/sites/9/2022/03/pan-de-pasas.jpg?w=500&h=500&crop=1'),
(11, 10, 'https://okdiario.com/img/2016/11/29/receta-de-pan-de-aceite-de-oliva-con-romero-y-aceitunas.jpg'),
(13, 11, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ4itbyjpFXsCSnzfdKGvcsaObU3x0tKyYUsQ&s'),
(14, 12, 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/Bagel_with_sesame_3.jpg/640px-Bagel_with_sesame_3.jpg'),
(15, 13, 'https://comedera.com/wp-content/uploads/sites/9/2022/04/Pan-de-leche-shutterstock_1845006109.jpg'),
(16, 14, 'https://recetasarabes.com/wp-content/uploads/2022/11/pan-pita-autentico.jpg'),
(17, 15, 'https://recetaamericana.com/wp-content/uploads/2021/11/perfecto-pan-de-maiz-cornbread-500x500.jpg'),
(18, 16, 'https://t1.uc.ltmcdn.com/es/posts/4/1/0/como_hacer_pie_de_manzana_31014_orig.jpg'),
(19, 17, 'https://7diasdesabor.com/wp-content/uploads/2021/09/10794d0b83681d545c8a22682726fb3f8e2.jpg'),
(20, 18, 'https://www.daisybrand.com/wp-content/uploads/2019/12/double-chocolate-zucchini-bread-770x628_4689.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id_metodo` int(11) NOT NULL,
  `nombre_metodo` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `metodos_pago`
--

INSERT INTO `metodos_pago` (`id_metodo`, `nombre_metodo`, `descripcion`, `estado`) VALUES
(1, 'Efectivo', 'Pago en efectivo al momento de la compra.', 'activo'),
(2, 'Tarjeta de Crédito', 'Aceptamos todas las tarjetas de crédito principales.', 'activo'),
(3, 'Tarjeta de Débito', 'Aceptamos todas las tarjetas de débito principales.', 'activo'),
(4, 'Transferencia Bancaria', 'Realiza tu pago a través de transferencia bancaria.', 'activo'),
(5, 'PayPal', 'Puedes pagar utilizando tu cuenta de PayPal.', 'activo'),
(6, 'Venmo', 'Aceptamos pagos a través de Venmo.', 'activo'),
(7, 'Apple Pay', 'Paga con Apple Pay desde tu dispositivo.', 'activo'),
(8, 'Google Pay', 'Paga con Google Pay desde tu dispositivo.', 'activo'),
(9, 'Pago Móvil', 'Realiza pagos móviles a través de nuestra aplicación.', 'activo'),
(10, 'Criptomonedas', 'Aceptamos pagos en criptomonedas como Bitcoin.', 'activo'),
(11, 'Cheque', 'Aceptamos pagos mediante cheque previo acuerdo.', 'activo'),
(12, 'Financiamiento', 'Opciones de financiamiento disponibles para grandes pedidos.', 'activo'),
(13, 'Billetera Electrónica', 'Usa tu billetera electrónica para realizar el pago.', 'activo'),
(14, 'Pago a Plazos', 'Opciones de pago a plazos disponibles para productos seleccionados.', 'activo'),
(15, 'Códigos de Descuento', 'Aplica tus códigos de descuento en el momento del pago.', 'activo'),
(16, 'Gift Card', 'Aceptamos tarjetas de regalo para compras en la panadería.', 'activo'),
(17, 'Pago por Orden de Compra', 'Para empresas, aceptamos pagos por orden de compra.', 'activo'),
(18, 'Método de Pago en Línea', 'Opciones de pago en línea disponibles en nuestro sitio web.', 'activo'),
(19, 'Pagos Automáticos', 'Configura pagos automáticos para suscripciones.', 'activo'),
(20, 'Pago con Puntos de Lealtad', 'Usa tus puntos de lealtad para descuentos en tus compras.', 'activo'),
(21, 'Pago por Aplicación', 'Paga a través de nuestra aplicación móvil.', 'activo');

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_pedido` datetime DEFAULT current_timestamp(),
  `estado` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `direccion_envio` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_cliente`, `fecha_pedido`, `estado`, `total`, `direccion_envio`) VALUES
(1, 1, '2024-11-03 16:04:17', 'Pendiente', 15.00, 'Calle Falsa 123, Tijuana'),
(2, 2, '2024-11-03 16:04:17', 'Completado', 25.50, 'Avenida Siempre Viva 742, Tijuana'),
(3, 3, '2024-11-03 16:04:17', 'Cancelado', 10.00, 'Boulevard de los Sueños 456, Tijuana'),
(4, 4, '2024-11-03 16:04:17', 'Pendiente', 30.75, 'Calle de los Abetos 89, Tijuana'),
(5, 5, '2024-11-03 16:04:17', 'Completado', 20.00, 'Calle de la Paz 101, Tijuana'),
(6, 6, '2024-11-03 16:04:17', 'Pendiente', 45.25, 'Avenida Revolución 555, Tijuana'),
(7, 7, '2024-11-03 16:04:17', 'Completado', 12.00, 'Calle del Sol 234, Tijuana'),
(8, 8, '2024-11-03 16:04:17', 'Pendiente', 18.50, 'Calle de los Ríos 78, Tijuana'),
(9, 9, '2024-11-03 16:04:17', 'Completado', 27.30, 'Calle de la Luna 34, Tijuana'),
(10, 10, '2024-11-03 16:04:17', 'Pendiente', 14.60, 'Calle de la Esperanza 200, Tijuana'),
(11, 11, '2024-11-03 16:04:17', 'Completado', 22.00, 'Calle del Cielo 5, Tijuana'),
(12, 12, '2024-11-03 16:04:17', 'Pendiente', 11.75, 'Calle de los Pinos 88, Tijuana'),
(13, 13, '2024-11-03 16:04:17', 'Completado', 17.90, 'Calle del Amor 12, Tijuana'),
(14, 14, '2024-11-03 16:04:17', 'Pendiente', 29.99, 'Avenida del Mar 666, Tijuana'),
(15, 15, '2024-11-03 16:04:17', 'Completado', 35.00, 'Calle de la Felicidad 777, Tijuana'),
(16, 16, '2024-11-03 16:04:17', 'Pendiente', 19.95, 'Calle de las Flores 14, Tijuana'),
(17, 17, '2024-11-03 16:04:17', 'Completado', 28.40, 'Calle de la Libertad 222, Tijuana'),
(18, 18, '2024-11-03 16:04:17', 'Pendiente', 8.99, 'Calle de los Ángeles 999, Tijuana'),
(19, 19, '2024-11-03 16:04:17', 'Completado', 23.10, 'Calle de la Alegría 31, Tijuana'),
(20, 20, '2024-11-03 16:04:17', 'Pendiente', 16.70, 'Calle del Océano 1234, Tijuana');

-- --------------------------------------------------------

--
-- Table structure for table `personalizaciones`
--

CREATE TABLE `personalizaciones` (
  `id_personalizacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `personalizacion` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personalizaciones`
--

INSERT INTO `personalizaciones` (`id_personalizacion`, `id_usuario`, `id_producto`, `personalizacion`, `fecha`) VALUES
(1, 28, NULL, 'hola', '2025-03-21 06:04:12'),
(2, 28, NULL, 'hola2', '2025-03-21 06:08:38'),
(3, 28, NULL, 'HOLS', '2025-03-21 06:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `contador` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `descripcion`, `precio`, `categoria`, `stock`, `contador`) VALUES
(1, 'Pan Blanco', 'Pan suave y esponjoso, corteza dorada, perfecto para sándwiches', 1.50, 1, 100, 10000000),
(2, 'Baguette', 'Tradicional pan francés, crujiente por fuera y suave por dentro.', 2.00, 1, 50, 0),
(3, 'Pan Integral', 'Saludable pan integral, perfecto para sándwiches.', 1.75, 2, 80, 0),
(4, 'Pan Dulce', 'Suave pan dulce con un sabor exquisito.', 2.50, 3, 60, 0),
(5, 'Focaccia', 'Pan italiano con hierbas y aceite de oliva.', 3.00, 1, 30, 0),
(6, 'Croissant', 'Crujiente croissant de mantequilla, ideal para el desayuno.', 2.25, 1, 40, 0),
(7, 'Pan de Ajo', 'Pan con un delicioso sabor a ajo y perejil.', 2.00, 2, 50, 0),
(8, 'Pastel de Chocolate', 'Pastel suave y húmedo de chocolate.', 4.00, 4, 20, 0),
(9, 'Pan de Pasas', 'Dulce pan con pasas y canela.', 2.50, 3, 55, 0),
(10, 'Pan de Oliva', 'Pan rústico con trozos de aceitunas.', 2.75, 2, 25, 0),
(11, 'Pan de Centeno', 'Pan denso y nutritivo hecho de harina de centeno.', 1.80, 2, 45, 0),
(12, 'Bagel', 'Bagel clásico, perfecto para untar.', 1.50, 1, 70, 0),
(13, 'Pan de Leche', 'Suave y dulce pan de leche.', 1.90, 3, 65, 0),
(14, 'Pita', 'Pan plano de origen mediterráneo.', 1.60, 1, 40, 0),
(15, 'Pan de Maíz', 'Delicioso pan de maíz, ideal para acompañar.', 2.00, 2, 50, 0),
(16, 'Tarta de Manzana', 'Tarta de manzana con canela, exquisita.', 4.50, 4, 15, 0),
(17, 'Pan de Fruta', 'Pan dulce con trozos de fruta.', 2.75, 3, 30, 0),
(18, 'Pan de Chocolate', 'Pan dulce de chocolate, ideal para los amantes del cacao.', 2.80, 3, 25, 0),
(19, 'Pan Sourdough', 'Pan de masa madre, crujiente y con sabor fuerte.', 3.50, 1, 20, 0),
(20, 'Galletas', 'Galletas recién horneadas, crujientes y deliciosas.', 1.20, 5, 100, 1),
(21, 'Brownie', 'Delicioso brownie de chocolate, perfecto para compartir.', 3.00, 4, 35, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `programa_actual`
--

CREATE TABLE `programa_actual` (
  `id_usuario` int(11) DEFAULT NULL,
  `id_programa` int(11) DEFAULT NULL,
  `fecha_asignacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programa_actual`
--

INSERT INTO `programa_actual` (`id_usuario`, `id_programa`, `fecha_asignacion`) VALUES
(28, 8, NULL),
(28, 8, NULL),
(28, 5, NULL),
(28, 7, NULL),
(28, 1, NULL),
(28, 13, NULL),
(28, 4, NULL),
(28, 4, NULL),
(28, 6, NULL),
(28, 6, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `programa_lealtad`
--

CREATE TABLE `programa_lealtad` (
  `id_programa` int(11) NOT NULL,
  `nombre_programa` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `puntos_requeridos` int(11) DEFAULT 0,
  `beneficios` text DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programa_lealtad`
--

INSERT INTO `programa_lealtad` (`id_programa`, `nombre_programa`, `descripcion`, `puntos_requeridos`, `beneficios`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(1, 'Programa Básico', 'Programa de lealtad básico para clientes frecuentes.', 100, 'Descuentos en futuras compras.', '2024-01-01', '2024-12-31', 'activo'),
(2, 'Programa Oro', 'Nivel avanzado que ofrece más beneficios.', 250, 'Descuentos más altos y productos exclusivos.', '2024-01-01', '2024-12-31', 'activo'),
(3, 'Programa Platino', 'El nivel más alto de nuestro programa de lealtad.', 500, 'Regalos especiales y acceso anticipado a eventos.', '2024-01-01', '2024-12-31', 'activo'),
(4, 'Programa Familiar', 'Beneficios para familias que compran juntos.', 150, 'Descuentos en productos familiares.', '2024-02-01', '2024-12-31', 'activo'),
(5, 'Programa Estudiante', 'Beneficios especiales para estudiantes.', 50, 'Descuentos al presentar identificación estudiantil.', '2024-03-01', '2024-12-31', 'activo'),
(6, 'Programa Aniversario', 'Celebra tu aniversario con nosotros.', 200, 'Regalos y descuentos especiales en tu aniversario.', '2024-01-01', '2024-12-31', 'activo'),
(7, 'Programa de Referidos', 'Invita a amigos y gana recompensas.', 75, 'Puntos adicionales por cada referido que compre.', '2024-04-01', '2024-12-31', 'activo'),
(8, 'Programa de Cumpleaños', 'Celebra tu cumpleaños con descuentos.', 0, 'Descuento especial en el mes de tu cumpleaños.', '2024-01-01', '2024-12-31', 'activo'),
(9, 'Programa de Compras Frecuentes', 'Beneficios para clientes que compran regularmente.', 300, 'Descuentos acumulativos por compras frecuentes.', '2024-01-01', '2024-12-31', 'activo'),
(10, 'Programa Ecológico', 'Premiamos a quienes traen sus propios envases.', 100, 'Descuento por cada compra con envase reutilizable.', '2024-05-01', '2024-12-31', 'activo'),
(11, 'Programa de Lealtad de Temporada', 'Beneficios especiales en temporadas festivas.', 150, 'Descuentos en productos de temporada.', '2024-11-01', '2024-12-31', 'activo'),
(12, 'Programa de Productos Nuevos', 'Premiamos a quienes prueban nuestros nuevos productos.', 200, 'Descuentos en nuevos productos durante el primer mes.', '2024-06-01', '2024-12-31', 'activo'),
(13, 'Programa de Compras Online', 'Beneficios para compras realizadas en línea.', 120, 'Descuentos especiales por compras en línea.', '2024-01-01', '2024-12-31', 'activo'),
(14, 'Programa de Participación en Eventos', 'Premios por asistir a eventos de la panadería.', 80, 'Puntos por cada evento al que asistas.', '2024-07-01', '2024-12-31', 'activo'),
(15, 'Programa de Sugerencias', 'Premiamos las mejores sugerencias de nuestros clientes.', 100, 'Puntos adicionales por sugerencias que se implementen.', '2024-08-01', '2024-12-31', 'activo'),
(16, 'Programa de Rescates de Puntos', 'Utiliza tus puntos acumulados en promociones.', 0, 'Promociones especiales para redimir puntos acumulados.', '2024-01-01', '2024-12-31', 'activo'),
(17, 'Programa de Lealtad Infantil', 'Beneficios especiales para nuestros clientes más jóvenes.', 50, 'Descuentos en productos para niños.', '2024-09-01', '2024-12-31', 'activo'),
(18, 'Programa de Alimentos Saludables', 'Premiamos la elección de productos saludables.', 120, 'Descuentos en productos saludables.', '2024-10-01', '2024-12-31', 'activo'),
(19, 'Programa de Clientes VIP', 'Beneficios exclusivos para nuestros mejores clientes.', 300, 'Acceso a productos limitados y eventos VIP.', '2024-01-01', '2024-12-31', 'activo'),
(20, 'Programa de Compromiso Social', 'Premiamos a quienes apoyan causas sociales.', 150, 'Descuentos en productos al participar en actividades comunitarias.', '2024-11-01', '2024-12-31', 'activo'),
(21, 'Programa de Lealtad por Suscripción', 'Beneficios por suscribirte a nuestras ofertas.', 100, 'Puntos adicionales por cada suscripción mensual.', '2024-12-01', '2024-12-31', 'activo');

-- --------------------------------------------------------

--
-- Table structure for table `puntos_acumulados`
--

CREATE TABLE `puntos_acumulados` (
  `id_usuario` int(11) DEFAULT NULL,
  `puntos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `puntos_acumulados`
--

INSERT INTO `puntos_acumulados` (`id_usuario`, `puntos`) VALUES
(29, 50),
(28, 250);

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_reserva` datetime NOT NULL,
  `cantidad_personas` int(11) NOT NULL,
  `comentarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_cliente`, `fecha_reserva`, `cantidad_personas`, `comentarios`) VALUES
(1, 1, '2024-11-10 10:00:00', 4, 'Mesa cerca de la ventana, por favor.'),
(2, 2, '2024-11-12 12:30:00', 2, 'Celebración de cumpleaños.'),
(3, 3, '2024-11-15 18:00:00', 6, 'Reservar la sala privada.'),
(4, 4, '2024-11-20 14:00:00', 3, 'Mesa en la terraza.'),
(5, 5, '2024-11-25 09:00:00', 1, 'Solo un café para llevar.'),
(6, 6, '2024-11-28 17:30:00', 5, 'Reservación para una reunión.'),
(7, 7, '2024-12-01 19:00:00', 8, 'Mesa para 8, celebración de fin de año.'),
(8, 8, '2024-12-03 15:00:00', 4, 'Mesa cerca de la entrada.'),
(9, 9, '2024-12-05 11:00:00', 2, 'Café y galletas para dos.'),
(10, 10, '2024-12-08 10:30:00', 3, 'Mesa en el jardín.'),
(11, 11, '2024-12-10 13:00:00', 2, 'Quiero probar el pan especial.'),
(12, 12, '2024-12-12 16:00:00', 7, 'Reservar para un grupo grande.'),
(13, 13, '2024-12-15 18:30:00', 5, 'Celebración de aniversario.'),
(14, 14, '2024-12-18 14:00:00', 3, 'Mesa tranquila.'),
(15, 15, '2024-12-20 09:00:00', 1, 'Solo un desayuno rápido.'),
(16, 16, '2024-12-22 12:00:00', 4, 'Mesa al aire libre, si es posible.'),
(17, 17, '2024-12-25 19:00:00', 10, 'Cena de Navidad, mesa larga.'),
(18, 18, '2024-12-28 15:30:00', 2, 'Mesa con vista a la panadería.'),
(19, 19, '2024-12-30 18:00:00', 6, 'Reunión familiar.'),
(20, 20, '2024-12-31 20:00:00', 5, 'Mesa para la fiesta de Año Nuevo.'),
(24, 28, '2025-06-25 07:00:00', 3, 'wawe');

-- --------------------------------------------------------

--
-- Table structure for table `session_tokens`
--

CREATE TABLE `session_tokens` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_expiracion` datetime NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `soporte`
--

CREATE TABLE `soporte` (
  `id_soporte` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `tipo_solicitud` enum('consulta','queja','sugerencia') NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_solicitud` datetime DEFAULT current_timestamp(),
  `estado` enum('pendiente','en proceso','resuelto') DEFAULT 'pendiente',
  `respuesta` text DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soporte`
--

INSERT INTO `soporte` (`id_soporte`, `id_cliente`, `tipo_solicitud`, `mensaje`, `fecha_solicitud`, `estado`, `respuesta`, `fecha_respuesta`) VALUES
(1, 1, 'consulta', '¿Cuáles son los horarios de apertura?', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(2, 2, 'queja', 'El pan que compré estaba seco.', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(3, 3, 'sugerencia', 'Sería genial que incluyeran más opciones sin gluten.', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(4, 1, 'consulta', '¿Hacen pedidos personalizados?', '2024-11-03 16:11:07', 'en proceso', 'Sí, hacemos pedidos personalizados para eventos.', '2024-01-01 10:00:00'),
(5, 2, 'queja', 'El servicio fue muy lento el día de mi visita.', '2024-11-03 16:11:07', 'resuelto', 'Lamentamos la experiencia. Estamos trabajando para mejorar.', '2024-01-02 11:00:00'),
(6, 3, 'sugerencia', 'Me gustaría ver más opciones de pasteles veganos.', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(7, 4, 'consulta', '¿Tienen opciones de entrega a domicilio?', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(8, 5, 'queja', 'La última vez que compré pan, estaba en mal estado.', '2024-11-03 16:11:07', 'en proceso', NULL, NULL),
(9, 6, 'sugerencia', 'Podrían ofrecer descuentos en pedidos grandes.', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(10, 1, 'consulta', '¿Tienen opciones para dietas especiales?', '2024-11-03 16:11:07', 'resuelto', 'Sí, ofrecemos opciones para dietas especiales. Pueden preguntar en la tienda.', '2024-01-03 09:00:00'),
(11, 2, 'queja', 'El postre que pedí no se parecía a la imagen.', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(12, 3, 'sugerencia', 'Sería útil tener un menú en línea.', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(13, 4, 'consulta', '¿Ofrecen clases de repostería?', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(14, 5, 'queja', 'No pude encontrar el sabor que quería.', '2024-11-03 16:11:07', 'resuelto', 'Estamos trabajando para aumentar nuestra variedad de sabores.', '2024-01-04 14:00:00'),
(15, 6, 'sugerencia', 'La música en la panadería podría ser más suave.', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(16, 7, 'consulta', '¿Hay estacionamiento disponible?', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(17, 8, 'queja', 'La caja estaba desorganizada.', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(18, 9, 'sugerencia', 'Ofrecer combos podría atraer más clientes.', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(19, 10, 'consulta', '¿Tienen recetas disponibles para comprar?', '2024-11-03 16:11:07', 'pendiente', NULL, NULL),
(20, 1, 'queja', 'No me atendieron correctamente.', '2024-11-03 16:11:07', 'resuelto', 'Lamentamos que no haya tenido una buena experiencia, tomaremos medidas.', '2024-01-05 12:00:00'),
(21, 2, 'sugerencia', 'Me encantaría ver más opciones de pan integral.', '2024-11-03 16:11:07', 'pendiente', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `foto_perfil` mediumblob NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `foto_perfil`, `correo_electronico`, `contrasena`, `telefono`, `direccion`) VALUES
(1, 'Juan Pérez', '', 'juan.perez@example.com', 'hashed_password1', '1234567890', 'Calle Falsa 123, Ciudad A'),
(2, 'María García', '', 'maria.garcia@example.com', 'hashed_password2', '0987654321', 'Av. Principal 45, Ciudad B'),
(3, 'Carlos López', '', 'carlos.lopez@example.com', 'hashed_password3', '1122334455', 'Calle Secundaria 56, Ciudad C'),
(4, 'Ana Ruiz', '', 'ana.ruiz@example.com', 'hashed_password4', '5566778899', 'Callejón 7, Ciudad D'),
(5, 'Luis Torres', '', 'luis.torres@example.com', 'hashed_password5', '6677889900', 'Av. Central 32, Ciudad E'),
(6, 'Sofía Martínez', '', 'sofia.martinez@example.com', 'hashed_password6', '7788990011', 'Calle Las Rosas 123, Ciudad F'),
(7, 'Diego Fernández', '', 'diego.fernandez@example.com', 'hashed_password7', '9988776655', 'Paseo Los Olivos 23, Ciudad G'),
(8, 'Elena Sánchez', '', 'elena.sanchez@example.com', 'hashed_password8', '8899001122', 'Bulevar Verde 45, Ciudad H'),
(9, 'José Gómez', '', 'jose.gomez@example.com', 'hashed_password9', '2233445566', 'Calle del Río 21, Ciudad I'),
(10, 'Laura Castro', '', 'laura.castro@example.com', 'hashed_password10', '3344556677', 'Camino de Flores 87, Ciudad J'),
(11, 'Ricardo Hernández', '', 'ricardo.hernandez@example.com', 'hashed_password11', '4455667788', 'Av. del Sol 10, Ciudad K'),
(12, 'Marta Jiménez', '', 'marta.jimenez@example.com', 'hashed_password12', '5566778899', 'Calle Luna 12, Ciudad L'),
(13, 'Pablo Díaz', '', 'pablo.diaz@example.com', 'hashed_password13', '6677889900', 'Camino Real 14, Ciudad M'),
(14, 'Clara Ríos', '', 'clara.rios@example.com', 'hashed_password14', '7788990011', 'Calle Primavera 9, Ciudad N'),
(15, 'David Morales', '', 'david.morales@example.com', 'hashed_password15', '8899001122', 'Calle Jardín 3, Ciudad O'),
(16, 'Lucía Paredes', '', 'lucia.paredes@example.com', 'hashed_password16', '2233445566', 'Av. del Bosque 11, Ciudad P'),
(17, 'Andrés Herrera', '', 'andres.herrera@example.com', 'hashed_password17', '3344556677', 'Paseo Los Pinos 67, Ciudad Q'),
(18, 'Carolina Mendoza', '', 'carolina.mendoza@example.com', 'hashed_password18', '4455667788', 'Calle Azul 42, Ciudad R'),
(19, 'Fernando Vargas', '', 'fernando.vargas@example.com', 'hashed_password19', '5566778899', 'Av. Libertad 8, Ciudad S'),
(20, 'Isabel Ortiz', '', 'isabel.ortiz@example.com', 'hashed_password20', '6677889900', 'Calle Esperanza 13, Ciudad T'),
(28, 'Erick', 0x494d475f303038322e4a5047, 'correo@gmail.com', '$2y$10$UDNAvVKsweop5OpOPqpvXuR0iz2lVyr8R8HB4GdCm680W.ePPchN.', '', ''),
(29, 'Pongame 10, Profe', 0x32303735783331333078322e6a7067, 'prueba@gmail.com', '$2y$10$6AFkWTa2GH6o0QSsQpkX2.KJOQYSEgrBLqIX5lxoaO9o3TMXT6SAC', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boletines`
--
ALTER TABLE `boletines`
  ADD PRIMARY KEY (`id_boletin`);

--
-- Indexes for table `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre_categoria` (`nombre_categoria`);

--
-- Indexes for table `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `destacados`
--
ALTER TABLE `destacados`
  ADD PRIMARY KEY (`id_destacado`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indexes for table `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`);

--
-- Indexes for table `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indexes for table `fotos`
--
ALTER TABLE `fotos`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id_metodo`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indexes for table `personalizaciones`
--
ALTER TABLE `personalizaciones`
  ADD PRIMARY KEY (`id_personalizacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `categoria` (`categoria`);

--
-- Indexes for table `programa_actual`
--
ALTER TABLE `programa_actual`
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_programa` (`id_programa`);

--
-- Indexes for table `programa_lealtad`
--
ALTER TABLE `programa_lealtad`
  ADD PRIMARY KEY (`id_programa`);

--
-- Indexes for table `puntos_acumulados`
--
ALTER TABLE `puntos_acumulados`
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indexes for table `session_tokens`
--
ALTER TABLE `session_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_token` (`token`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `idx_token` (`token`,`activo`);

--
-- Indexes for table `soporte`
--
ALTER TABLE `soporte`
  ADD PRIMARY KEY (`id_soporte`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `boletines`
--
ALTER TABLE `boletines`
  MODIFY `id_boletin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `destacados`
--
ALTER TABLE `destacados`
  MODIFY `id_destacado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `fotos`
--
ALTER TABLE `fotos`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `personalizaciones`
--
ALTER TABLE `personalizaciones`
  MODIFY `id_personalizacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `programa_lealtad`
--
ALTER TABLE `programa_lealtad`
  MODIFY `id_programa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `session_tokens`
--
ALTER TABLE `session_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `soporte`
--
ALTER TABLE `soporte`
  MODIFY `id_soporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Constraints for table `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Constraints for table `destacados`
--
ALTER TABLE `destacados`
  ADD CONSTRAINT `destacados_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE SET NULL;

--
-- Constraints for table `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `detalle_pedidos_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_pedidos_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Constraints for table `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE,
  ADD CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Constraints for table `fotos`
--
ALTER TABLE `fotos`
  ADD CONSTRAINT `fotos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Constraints for table `personalizaciones`
--
ALTER TABLE `personalizaciones`
  ADD CONSTRAINT `personalizaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL;

--
-- Constraints for table `programa_actual`
--
ALTER TABLE `programa_actual`
  ADD CONSTRAINT `programa_actual_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `programa_actual_ibfk_2` FOREIGN KEY (`id_programa`) REFERENCES `programa_lealtad` (`id_programa`);

--
-- Constraints for table `puntos_acumulados`
--
ALTER TABLE `puntos_acumulados`
  ADD CONSTRAINT `puntos_acumulados_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Constraints for table `session_tokens`
--
ALTER TABLE `session_tokens`
  ADD CONSTRAINT `session_tokens_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `soporte`
--
ALTER TABLE `soporte`
  ADD CONSTRAINT `soporte_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
