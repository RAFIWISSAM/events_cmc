<?php
// Set page title
$page_title = 'Participer';
// Start session
session_start();
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

// Process form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $first_name = $conn->real_escape_string($_POST['firstName']);
    $last_name = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    
    // Validate form data
    if (empty($first_name) || empty($last_name) || empty($email)) {
        $error_message = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Veuillez fournir une adresse email valide.';
    } else {
        // Insert participant into database
        $insert_sql = "INSERT INTO participants (event_id, first_name, last_name, email) 
                       VALUES ('$event_id', '$first_name', '$last_name', '$email')";
        
        if ($conn->query($insert_sql) === TRUE) {
            $success_message = 'Votre inscription a été enregistrée avec succès! Un email de confirmation a été envoyé à votre adresse.';
            
            // Send confirmation email (in a real application)
            // mail($email, 'Confirmation de participation', '...'); 
            
            // For this demo, we'll just simulate the email
            // In a real application, you would use the mail() function or a library like PHPMailer
        } else {
            $error_message = 'Une erreur est survenue. Veuillez réessayer.';
        }
    }
}

// Include header
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="mb-4">
        <a href="event_detail.php?id=<?php echo $event_id; ?>" class="btn btn-outline-primary mb-4">
            <i class="fas fa-arrow-left me-2"></i>
            Retour aux détails de l'événement
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h1 class="h3 mb-2">Participer à l'événement</h1>
                    <h2 class="h5 text-muted mb-4"><?php echo htmlspecialchars($event['title']); ?></h2>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="events.php" class="btn btn-primary">Retour aux événements</a>
                        </div>
                    <?php else: ?>
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="participate.php?id=<?php echo $event_id; ?>" onsubmit="return validateForm()">
                            <div class="mb-3">
                                <label for="firstName" class="form-label">Prénom *</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">S'inscrire</button>
                            </div>
                            
                            <p class="text-muted small mt-3">
                                En vous inscrivant, vous acceptez de recevoir des informations concernant cet événement.
                            </p>
                        </form>
                    <?php endif; ?>
                </div>
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
