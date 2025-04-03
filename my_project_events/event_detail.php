<?php
// Start session
session_start();
// Set page title
$page_title = 'Détail de l\'événement';

// Include database connection
require_once 'config/db_connect.php';

// Check if ID parameter exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: events.php');
    exit;
}

// Get event ID
$event_id = $conn->real_escape_string($_GET['id']);

// Get event details
$sql = "SELECT * FROM events WHERE id = '$event_id'";
$result = $conn->query($sql);

// Check if event exists
if (!$result || $result->num_rows == 0) {
    header('Location: events.php');
    exit;
}

// Get event data
$event = $result->fetch_assoc();

// Include header
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="mb-4">
        <a href="events.php" class="btn btn-outline-primary mb-4">
            <i class="fas fa-arrow-left me-2"></i>
            Retour aux événements
        </a>
    </div>
    
    <div class="card shadow border-0">
        <img src="<?php echo htmlspecialchars($event['image']); ?>" class="event-detail-img" alt="<?php echo htmlspecialchars($event['title']); ?>">
        
        <div class="card-body p-4">
            <h1 class="mb-4"><?php echo htmlspecialchars($event['title']); ?></h1>
            
            <div class="event-meta">
                <div class="event-meta-item">
                    <i class="far fa-calendar-alt"></i>
                    <div>
                        <p class="text-muted mb-0">Date</p>
                        <p class="mb-0 fw-medium"><?php echo date('d F Y', strtotime($event['date'])); ?></p>
                    </div>
                </div>
                
                <div class="event-meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <p class="text-muted mb-0">Lieu</p>
                        <p class="mb-0 fw-medium"><?php echo htmlspecialchars($event['location']); ?></p>
                    </div>
                </div>
                
                <div class="event-meta-item">
                    <i class="fas fa-tag"></i>
                    <div>
                        <p class="text-muted mb-0">Prix</p>
                        <p class="mb-0 fw-medium"><?php echo htmlspecialchars($event['price']); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="mb-5">
                <h2 class="h4 mb-3">À propos de cet événement</h2>
                <p class="text-muted"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
            </div>
            
            <div class="text-center">
                <a href="participate.php?id=<?php echo $event['id']; ?>" class="btn btn-primary btn-lg">
                    Participer à cet événement
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';

// Close database connection
$conn->close();
?>
