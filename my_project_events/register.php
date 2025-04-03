<?php
// Start session
session_start();

// Set current page for navbar active state
$current_page = 'register';
$page_title = 'Inscription';

// Include database connection
require_once 'config/db_connect.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Process form submission
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validate form data
    if (empty($name) || empty($email) || empty($password) || empty($password_confirm)) {
        $error_message = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Veuillez fournir une adresse email valide.';
    } elseif ($password !== $password_confirm) {
        $error_message = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($password) < 8) {
        $error_message = 'Le mot de passe doit contenir au moins 8 caractères.';
    } else {
        // Check if email already exists
        $email = $conn->real_escape_string($email);
        $check_sql = "SELECT id FROM users WHERE email = '$email'";
        $check_result = $conn->query($check_sql);
        
        if ($check_result && $check_result->num_rows > 0) {
            $error_message = 'Cette adresse email est déjà utilisée.';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $name = $conn->real_escape_string($name);
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
            
            if ($conn->query($sql)) {
                $success_message = 'Votre compte a été créé avec succès! Vous pouvez maintenant vous connecter.';
            } else {
                $error_message = 'Une erreur est survenue lors de la création de votre compte.';
            }
        }
    }
}

// Include header
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h1 class="h3 mb-4 text-center">Créer un compte</h1>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                            <div class="text-center mt-3">
                                <a href="login.php" class="btn btn-primary">Se connecter</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="register.php">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Le mot de passe doit contenir au moins 8 caractères.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>S'inscrire
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0">Vous avez déjà un compte? <a href="login.php" class="text-decoration-none">Se connecter</a></p>
                        </div>
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
