<?php
require_once '../conexionBD.php';
require_once '../header.php';

try {
    $conexion = Conexion::getInstance()->getConnection();
    $query = "SELECT * FROM eventos WHERE estado = 'programado' ORDER BY fecha_evento";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<link href="../css/eventos.css" rel="stylesheet">
<head>
    <style>
        .padding {
            padding-bottom: 50px;
        }
    </style>
</head>
<div class="events-container">
    <div class="container">
        <h6 class="section-title">
             Contactanos
             <Center>Esta es nuestra ubicación
        </h6>

        <Center><iframe class="padding" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1682.6555945403302!2d-116.90641335573888!3d32.49111086762779!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80d949f4a0113b05%3A0x605bf87b0832963d!2sPanader%C3%ADa%20el%20Lobo!5e0!3m2!1ses!2smx!4v1748483494581!5m2!1ses!2smx" width="1000" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>  
             
          
<?php require_once '../footer.php'; ?>


