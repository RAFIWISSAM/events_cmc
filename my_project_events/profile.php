<?php
// Start session
session_start();

// Set current page for navbar active state
$current_page = 'profile';
$page_title = 'Mon Profil';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once 'config/db_connect.php';

// Get user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    header('Location: logout.php');
    exit;
}

$user = $result->fetch_assoc();

// Process form submission
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate form data
    if (empty($name) || empty($email)) {
        $error_message = 'Le nom et l\'email sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Veuillez fournir une adresse email valide.';
    } else {
        // Check if email exists (if changed)
        if ($email != $user['email']) {
            $check_email = $conn->real_escape_string($email);
            $check_sql = "SELECT id FROM users WHERE email = '$check_email' AND id != '$user_id'";
            $check_result = $conn->query($check_sql);
            
            if ($check_result && $check_result->num_rows > 0) {
                $error_message = 'Cette adresse email est déjà utilisée.';
            }
        }
        
        // Check password if changing
        if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
            if (empty($current_password)) {
                $error_message = 'Veuillez entrer votre mot de passe actuel.';
            } elseif (empty($new_password) || empty($confirm_password)) {
                $error_message = 'Veuillez entrer et confirmer votre nouveau mot de passe.';
            } elseif ($new_password != $confirm_password) {
                $error_message = 'Les nouveaux mots de passe ne correspondent pas.';
            } elseif (strlen($new_password) < 8) {
                $error_message = 'Le nouveau mot de passe doit contenir au moins 8 caractères.';
            } elseif (!password_verify($current_password, $user['password'])) {
                $error_message = 'Le mot de passe actuel est incorrect.';
            }
        }
        
        if (empty($error_message)) {
            // Prepare data for update
            $name = $conn->real_escape_string($name);
            $email = $conn->real_escape_string($email);
            
            // Update user
            $sql = "UPDATE users SET name = '$name', email = '$email'";
            
            // Update password if changing
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql .= ", password = '$hashed_password'";
            }
            
            $sql .= " WHERE id = '$user_id'";
            
            if ($conn->query($sql)) {
                $success_message = 'Votre profil a été mis à jour avec succès.';
                
                // Update session data
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                
                // Update user data
                $user['name'] = $name;
                $user['email'] = $email;
                if (!empty($new_password)) {
                    $user['password'] = $hashed_password;
                }
            } else {
                $error_message = 'Une erreur est survenue lors de la mise à jour de votre profil.';
            }
        }
    }
}

// Include header
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h1 class="h3 mb-4">Mon Profil</h1>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="profile.php">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <hr class="my-4">
                                <h4 class="h5 mb-3">Changer le mot de passe</h4>
                                <p class="text-muted mb-3">Laissez ces champs vides si vous ne souhaitez pas changer votre mot de passe.</p>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                    <div class="form-text">Minimum 8 caractères.</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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
