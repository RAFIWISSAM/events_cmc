<?php
// Start session
session_start();

// Set admin page flag
$admin_page = true;

// Set current page for navbar active state
$current_page = 'admin';
$page_title = 'Participants';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}

// Include database connection
require_once '../config/db_connect.php';

// Get event filter
$event_filter = isset($_GET['event_id']) ? $_GET['event_id'] : '';

// Get all events for filter
$events_sql = "SELECT id, title FROM events ORDER BY date DESC";
$events_result = $conn->query($events_sql);
$events = [];

if ($events_result && $events_result->num_rows > 0) {
    while ($row = $events_result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Build the SQL query
$sql = "SELECT p.*, e.title as event_title 
        FROM participants p 
        JOIN events e ON p.event_id = e.id 
        WHERE 1=1";

if (!empty($event_filter)) {
    $event_filter = $conn->real_escape_string($event_filter);
    $sql .= " AND p.event_id = '$event_filter'";
}

$sql .= " ORDER BY p.registration_date DESC";

// Execute the query
$result = $conn->query($sql);
$participants = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $participants[] = $row;
    }
}

// Include header
include '../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Participants</h1>
        <a href="index.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
        </a>
    </div>
    
    <!-- Filter Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="participants.php" method="GET" class="row g-3">
                <div class="col-md-6">
                    <select class="form-select" name="event_id">
                        <option value="">Tous les événements</option>
                        <?php foreach ($events as $event): ?>
                            <option value="<?php echo $event['id']; ?>" <?php echo ($event_filter == $event['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($event['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary me-2">Filtrer</button>
                    <a href="participants.php" class="btn btn-outline-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Participants Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Événement</th>
                            <th>Date d'inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($participants) > 0): ?>
                            <?php foreach ($participants as $participant): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($participant['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($participant['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($participant['email']); ?></td>
                                    <td><?php echo htmlspecialchars($participant['event_title']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($participant['registration_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">Aucun participant trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
