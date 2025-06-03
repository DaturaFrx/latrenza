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

<div class="events-container">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-calendar-alt me-2"></i>
            Próximos Eventos
        </h2>

        <div class="row">
            <?php while ($evento = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="event-card">
                        <div class="event-header">
                            <h3><?php echo htmlspecialchars($evento['nombre_evento']); ?></h3>
                            <div class="event-price">
                                <?php if ($evento['costo'] > 0): ?>
                                    $<?php echo number_format($evento['costo'], 2); ?>
                                <?php else: ?>
                                    <span class="free-event">Gratis</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="event-body">
                            <p><?php echo htmlspecialchars($evento['descripcion']); ?></p>
                            <div class="event-details">
                                <div class="event-detail-item">
                                    <i class="far fa-calendar"></i>
                                    <?php 
                                        $fecha = new DateTime($evento['fecha_evento']);
                                        echo $fecha->format('d/m/Y'); 
                                    ?>
                                </div>
                                <div class="event-detail-item">
                                    <i class="far fa-clock"></i>
                                    <?php echo $fecha->format('H:i'); ?> hrs
                                </div>
                                <div class="event-detail-item">
                                    <i class="fas fa-users"></i>
                                    Capacidad: <?php echo htmlspecialchars($evento['capacidad']); ?> personas
                                </div>
                            </div>
                            <?php?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php require_once '../footer.php'; ?>
