<?php
require_once '../conexionBD.php';
require_once '../header.php';

function fetchUpcomingEvents()
{
    try {
        $conexion = Conexion::getInstance()->getConnection();
        $query = "SELECT * FROM eventos WHERE estado = 'programado' ORDER BY fecha_evento";
        $stmt = $conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

$eventos = fetchUpcomingEvents();
?>

<style>
    :root {
        --primary-color: #E4007C;
        --primary-light: #ff339d;
        --primary-dark: #b1005f;
        --primary-bg-light: #fff0f7;
    }

    body {
        background-color: var(--primary-bg-light);
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .events-container {
        padding: 4rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-title {
        color: var(--primary-color);
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 3rem;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        justify-content: center;
    }

    .event-card {
        background-color: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        max-width: 350px;
        flex: 1 1 300px;
        display: flex;
        flex-direction: column;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .event-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: #fff;
        padding: 1.5rem;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        position: relative;
    }

    .event-header h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .event-price {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #fff;
        color: var(--primary-color);
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .free-event {
        background-color: var(--primary-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.9rem;
    }

    .event-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .event-body p {
        color: #444;
        margin: 0;
    }

    .event-details {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .event-detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #555;
    }

    .event-detail-item i {
        color: var(--primary-color);
        width: 20px;
        text-align: center;
    }

    .btn {
        margin-top: auto;
        padding: 0.75rem;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    .btn-secondary {
        background-color: #ccc;
        color: #666;
        cursor: not-allowed;
    }
</style>

<div class="events-container">
    <h2 class="section-title">
        <i class="fas fa-calendar-alt me-2"></i>
        Próximos Eventos
    </h2>

    <div class="row">
        <?php foreach ($eventos as $evento): ?>
            <?php
            $fecha = new DateTime($evento['fecha_evento']);
            $formattedDate = $fecha->format('d/m/Y');
            $formattedTime = $fecha->format('H:i');
            $url = sprintf(
                '../reservas/reservas.php?id=%d&fecha=%s&hora=%s',
                $evento['id_evento'],
                $fecha->format('Y-m-d'),
                $fecha->format('H:i')
            );
            ?>
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
                            <i class="far fa-calendar"></i> <?php echo $formattedDate; ?>
                        </div>
                        <div class="event-detail-item">
                            <i class="far fa-clock"></i> <?php echo $formattedTime; ?> hrs
                        </div>
                        <div class="event-detail-item">
                            <i class="fas fa-users"></i> Capacidad: <?php echo htmlspecialchars($evento['capacidad']); ?> personas
                        </div>
                    </div>
                    <?php if ($evento['capacidad'] > 0): ?>
                        <a href="<?php echo $url; ?>" class="btn btn-primary">Reservar Lugar</a>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>Evento Lleno</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once '../footer.php'; ?>