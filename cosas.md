# Al azar

## BD

### Tablas Principales

#### usuarios

* id_usuario (PK)
* nombre
* correo_electronico (UNIQUE)
* contrasena
* telefono
* direccion

#### productos

* id_producto (PK)
* nombre_producto
* descripcion
* precio
* categoria (FK a tabla categorias)
* imagen
* stock

#### categorias

* id_categoria (PK)
* nombre_categoria

#### pedidos

* id_pedido (PK)
* id_usuario (FK)
* fecha_pedido
* estado_pedido
* metodo_pago

#### detalle_pedidos

* id_detalle (PK)
* id_pedido (FK)
* id_producto (FK)
* cantidad
* subtotal

#### reservas

* id_reserva (PK)
* id_usuario (FK)
* fecha_reserva
* hora_reserva
* numero_personas
* estado_reserva

#### comentarios

* id_comentario (PK)
* id_producto (FK)
* id_usuario (FK)
* comentario
* calificacion
* fecha_comentario

#### eventos

* id_evento (PK)
* nombre_evento
* descripcion_evento
* fecha_evento
* imagen_evento

#### boletines

* id_boletin (PK)
* correo_suscriptor
* fecha_suscripcion

#### metodos_pago

* id_metodo (PK)
* descripcion_metodo

#### programa_lealtad

* id_usuario (PK, FK)
* puntos_acumulados

#### facturas

* id_factura (PK)
* id_pedido (FK)
* fecha_emision
* total

#### imagenes_galeria

* id_imagen (PK)
* url_imagen
* descripcion_imagen

#### soporte

* id_ticket (PK)
* id_usuario (FK)
* asunto
* mensaje
* fecha_envio
* estado_ticket

## Errores

### Conexiones

``` plaintext
// ========================================== // index.php // ========================================== // ========================================== // configuracion.php // ==========================================
Warning: ini_set(): Session ini settings cannot be changed when a session is active in C:\xampp\htdocs\latrenza\configuracion.php on line 16

Warning: ini_set(): Session ini settings cannot be changed when a session is active in C:\xampp\htdocs\latrenza\configuracion.php on line 17
// ========================================== // conexionBD.php // ========================================== // ========================================== // funciones.php // ========================================== // ========================================== // home.php // ==========================================
Warning: require_once(includes/header.php): Failed to open stream: No such file or directory in C:\xampp\htdocs\latrenza\home.php on line 6

Fatal error: Uncaught Error: Failed opening required 'includes/header.php' (include_path='C:\xampp\php\PEAR') in C:\xampp\htdocs\latrenza\home.php:6 Stack trace: #0 C:\xampp\htdocs\latrenza\index.php(10): require_once() #1 {main} thrown in C:\xampp\htdocs\latrenza\home.php on line 6
```
