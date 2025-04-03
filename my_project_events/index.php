<?php
// Set current page for navbar active state
$current_page = 'home';
$page_title = 'Accueil';
// Start session
session_start();
// Include database connection
require_once 'config/db_connect.php';

// Get upcoming events (limit to 3)
$sql = "SELECT * FROM events ORDER BY date ASC LIMIT 3";
$result = $conn->query($sql);
$upcoming_events = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $upcoming_events[] = $row;
    }
}

// Include header
include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 hero-content">
                <h1 class="display-4 fw-bold mb-4">Découvrez les événements de la Cité des Métiers et des Compétences</h1>
                <p class="lead mb-5">Formations, conférences, ateliers et bien plus pour enrichir votre parcours professionnel.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="events.php" class="btn btn-light btn-lg">
                        Voir les événements
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="contact.php" class="btn btn-outline-light btn-lg">Nous contacter</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Events Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Événements à venir</h2>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                Ne manquez pas nos prochains rendez-vous. Inscrivez-vous dès maintenant pour réserver votre place.
            </p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($upcoming_events as $event): ?>
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
        
        <div class="text-center mt-5">
            <a href="events.php" class="btn btn-primary">
                Voir tous les événements
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Pourquoi choisir nos événements</h2>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                Nous nous engageons à offrir des expériences enrichissantes qui répondent aux besoins du marché du travail.
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="feature-icon">
                    <i class="far fa-calendar-alt"></i>
                </div>
                <h3 class="h5 mb-3">Événements Variés</h3>
                <p class="text-muted">
                    Des conférences aux ateliers pratiques, nous proposons une variété d'événements pour tous les intérêts.
                </p>
            </div>
            
            <div class="col-md-4 text-center">
                <div class="feature-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="h5 mb-3">Experts du Domaine</h3>
                <p class="text-muted">
                    Nos intervenants sont des professionnels reconnus qui partagent leur expertise et leur expérience.
                </p>
            </div>
            
            <div class="col-md-4 text-center">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="h5 mb-3">Networking</h3>
                <p class="text-muted">
                    Rencontrez d'autres professionnels et étudiants pour élargir votre réseau et créer des opportunités.
                </p>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include 'includes/footer.php';

// Close database connection
$conn->close();
?>
