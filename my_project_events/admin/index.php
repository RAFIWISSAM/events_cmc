<?php
// Start session
session_start();

// Set admin page flag
$admin_page = true;

// Set current page for navbar active state
$current_page = 'admin';
$page_title = 'Administration';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}

// Include database connection
require_once '../config/db_connect.php';

// Get total events count
$events_sql = "SELECT COUNT(*) as total FROM events";
$events_result = $conn->query($events_sql);
$events_total = $events_result->fetch_assoc()['total'];

// Get total participants count
$participants_sql = "SELECT COUNT(*) as total FROM participants";
$participants_result = $conn->query($participants_sql);
$participants_total = $participants_result->fetch_assoc()['total'];

// Include header
include '../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Espace Administration</h1>
    </div>
    
    <div class="alert alert-success mb-4">
        <h4 class="alert-heading">Bienvenue, <?php echo $_SESSION['user_name']; ?>!</h4>
        <p>Bienvenue sur votre espace d'administration. Vous pouvez gérer les événements, voir les participants, et plus encore.</p>
    </div>
    
    <div class="row g-4 mb-5">
        <!-- Events Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-bg bg-primary rounded-circle p-3 me-3">
                            <i class="fas fa-calendar-alt text-white fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Événements</h5>
                            <p class="card-text text-muted">Total des événements</p>
                        </div>
                    </div>
                    <h2 class="display-4 fw-bold text-center mb-3"><?php echo $events_total; ?></h2>
                    <div class="d-grid">
                        <a href="events.php" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>Gérer les événements
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Participants Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-bg bg-success rounded-circle p-3 me-3">
                            <i class="fas fa-users text-white fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Participants</h5>
                            <p class="card-text text-muted">Total des participants</p>
                        </div>
                    </div>
                    <h2 class="display-4 fw-bold text-center mb-3"><?php echo $participants_total; ?></h2>
                    <div class="d-grid">
                        <a href="participants.php" class="btn btn-success">
                            <i class="fas fa-user-friends me-2"></i>Voir les participants
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title">Actions rapides</h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="events.php" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2 w-100 py-3">
                        <i class="fas fa-list"></i>
                        <span>Liste des événements</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="add_event.php" class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2 w-100 py-3">
                        <i class="fas fa-plus"></i>
                        <span>Ajouter un événement</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="participants.php" class="btn btn-outline-info d-flex align-items-center justify-content-center gap-2 w-100 py-3">
                        <i class="fas fa-users"></i>
                        <span>Voir les participants</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include '../includes/footer.php';

// Close database connection
$conn->close();
?>
