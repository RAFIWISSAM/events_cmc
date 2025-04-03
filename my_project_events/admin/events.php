<?php
// Start session
session_start();

// Set admin page flag
$admin_page = true;

// Set current page for navbar active state
$current_page = 'admin';
$page_title = 'Gestion des Événements';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}

// Include database connection
require_once '../config/db_connect.php';

// Initialize search parameters
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$search_date = isset($_GET['date']) ? $_GET['date'] : '';

// Process event deletion
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $event_id = $conn->real_escape_string($_GET['delete']);
    
    // Delete event
    $delete_sql = "DELETE FROM events WHERE id = '$event_id'";
    
    if ($conn->query($delete_sql)) {
        $success_message = "L'événement a été supprimé avec succès.";
    } else {
        $error_message = "Une erreur est survenue lors de la suppression de l'événement.";
    }
}

// Build the SQL query based on search parameters
$sql = "SELECT * FROM events WHERE 1=1";

if (!empty($search_term)) {
    $search_term = $conn->real_escape_string($search_term);
    $sql .= " AND (title LIKE '%$search_term%' OR description LIKE '%$search_term%')";
}

if (!empty($search_date)) {
    $search_date = $conn->real_escape_string($search_date);
    $sql .= " AND date = '$search_date'";
}

$sql .= " ORDER BY date ASC";

// Execute the query
$result = $conn->query($sql);
$events = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Include header
include '../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des Événements</h1>
        <a href="add_event.php" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Ajouter un événement
        </a>
    </div>
    
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <!-- Search Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="events.php" method="GET" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Rechercher par nom..." name="search" value="<?php echo htmlspecialchars($search_term); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <input type="date" class="form-control" name="date" value="<?php echo htmlspecialchars($search_date); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2">Rechercher</button>
                    <a href="events.php" class="btn btn-outline-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Events Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Lieu</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Invités</th>
                            <th>Participants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($events) > 0): ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                                    <td><?php echo htmlspecialchars($event['location']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($event['date'])); ?></td>
                                    <td><?php echo htmlspecialchars($event['category']); ?></td>
                                    <td><?php echo $event['invited_count']; ?></td>
                                    <td><?php echo $event['attendee_count']; ?></td>
                                    <td>
                                        <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $event['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal<?php echo $event['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Êtes-vous sûr de vouloir supprimer l'événement "<?php echo htmlspecialchars($event['title']); ?>" ?</p>
                                                        <p class="text-danger">Cette action est irréversible.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <a href="events.php?delete=<?php echo $event['id']; ?>" class="btn btn-danger">Supprimer</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">Aucun événement ne correspond à votre recherche.</td>
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
