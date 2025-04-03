<?php
// Set current page for navbar active state
session_start();
$current_page = 'events';
$page_title = 'Événements';

// Include database connection
require_once 'config/db_connect.php';

// Initialize search parameters
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$search_date = isset($_GET['date']) ? $_GET['date'] : '';

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
include 'includes/header.php';
?>

<div class="container py-5">
    <h1 class="mb-4">Événements</h1>
    <p class="lead text-muted mb-5">
        Découvrez tous nos événements et inscrivez-vous pour réserver votre place.
    </p>
    
    <!-- Search Form -->
    <div class="card shadow-sm mb-5">
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
    
    <!-- Events Grid -->
    <?php if (count($events) > 0): ?>
        <div class="row g-4">
            <?php foreach ($events as $event): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card event-card shadow-sm h-100">
                        <img src="<?php echo htmlspecialchars($event['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($event['title']); ?>">
                        <div class="card-body d-flex flex-column">
                            <div class="event-info">
                                <div class="mb-1">
                                    <i class="far fa-calendar-alt"></i>
                                    <?php echo date('d F Y', strtotime($event['date'])); ?>
                                </div>
                                <div>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($event['location']); ?>
                                </div>
                            </div>
                            <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                            <p class="card-text text-muted mb-4" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?php echo htmlspecialchars($event['description']); ?>
                            </p>
                            <a href="event_detail.php?id=<?php echo $event['id']; ?>" class="btn btn-outline-primary mt-auto">
                                Voir plus
                                <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <p class="fs-5 text-muted mb-4">Aucun événement ne correspond à votre recherche.</p>
            <a href="events.php" class="btn btn-primary">Voir tous les événements</a>
        </div>
    <?php endif; ?>
</div>

<?php
// Include footer
include 'includes/footer.php';

// Close database connection
$conn->close();
?>
