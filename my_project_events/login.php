<?php
// Start session
session_start();

// Set current page for navbar active state
$current_page = 'login';
$page_title = 'Connexion';

// Include database connection
require_once 'config/db_connect.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to home page or admin dashboard based on user role
    if ($_SESSION['is_admin']) {
        header('Location: admin/index.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

// Process form submission
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate form data
    if (empty($email) || empty($password)) {
        $error_message = 'Veuillez remplir tous les champs obligatoires.';
    } else {
        // Check user credentials
        $email = $conn->real_escape_string($email);
        $sql = "SELECT id, name, email, password, is_admin FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['is_admin'] = $user['is_admin'];
                
                // Redirect based on user role
                if ($user['is_admin']) {
                    header('Location: admin/index.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $error_message = 'Identifiants incorrects. Veuillez réessayer.';
            }
        } else {
            $error_message = 'Identifiants incorrects. Veuillez réessayer.';
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
                    <h1 class="h3 mb-4 text-center">Connexion</h1>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text text-end">
                                <a href="#" class="text-decoration-none">Mot de passe oublié?</a>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">Vous n'avez pas de compte? <a href="register.php" class="text-decoration-none">S'inscrire</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>
