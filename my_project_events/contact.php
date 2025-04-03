<?php
// Set current page for navbar active state
session_start();
$current_page = 'contact';
$page_title = 'Contact';

// Process form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validate form data
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Veuillez fournir une adresse email valide.';
    } else {
        // In a real application, you would send an email here
        // For this demo, we'll just show a success message
        $success_message = 'Votre message a été envoyé avec succès! Nous vous répondrons dans les plus brefs délais.';
    }
}

// Include header
include 'includes/header.php';
?>

<div class="container py-5">
    <h1 class="mb-4">Contactez-nous</h1>
    <p class="lead text-muted mb-5">
        Nous sommes là pour répondre à toutes vos questions concernant nos événements.
    </p>
    
    <div class="row g-5">
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h4 mb-4">Informations de contact</h2>
                    
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold">Email</h3>
                            <p class="text-muted">contact@cmc.edu</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold">Téléphone</h3>
                            <p class="text-muted">+212 555-1234</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold">Adresse</h3>
                            <p class="text-muted">
                                Cité des Métiers et des Compétences<br>
                                123 Avenue de l'Innovation<br>
                                Casablanca, Maroc
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h4 mb-4">Suivez-nous</h2>
                    
                    <div class="social-icons d-flex gap-3">
                        <a href="#" class="text-muted fs-4"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h4 mb-4">Envoyez-nous un message</h2>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php else: ?>
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="contact.php">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nom complet *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                
                                <div class="col-12">
                                    <label for="subject" class="form-label">Sujet *</label>
                                    <input type="text" class="form-control" id="subject" name="subject" required>
                                </div>
                                
                                <div class="col-12">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Envoyer le message
                                    </button>
                                </div>
                            </div>
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
?>
