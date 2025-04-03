<?php
// Start session
session_start();

// Set admin page flag
$admin_page = true;

// Set current page for navbar active state
$current_page = 'admin';
$page_title = 'Ajouter un Événement';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}

// Include database connection
require_once '../config/db_connect.php';

// Get all locations
$locations_sql = "SELECT * FROM locations ORDER BY name ASC";
$locations_result = $conn->query($locations_sql);
$locations = [];

if ($locations_result && $locations_result->num_rows > 0) {
    while ($row = $locations_result->fetch_assoc()) {
        $locations[] = $row;
    }
}

// Get all categories
$categories_sql = "SELECT * FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_sql);
$categories = [];

if ($categories_result && $categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Process form submission
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $location = $_POST['location'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $invited_count = $_POST['invited_count'] ?? 0;
    $attendee_count = $_POST['attendee_count'] ?? 0;
    
    // Validate form data
    if (empty($title) || empty($date) || empty($location) || empty($category) || empty($price) || empty($description)) {
        $error_message = 'Veuillez remplir tous les champs obligatoires.';
    } else {
        // Handle file upload
        $image_path = '';
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($_FILES['image']['type'], $allowed_types)) {
                $error_message = 'Seuls les fichiers JPG, PNG et GIF sont autorisés.';
            } elseif ($_FILES['image']['size'] > $max_size) {
                $error_message = 'La taille du fichier ne doit pas dépasser 5 MB.';
            } else {
                $file_name = time() . '_' . basename($_FILES['image']['name']);
                $upload_dir = '../img/events/';
                $image_path = 'img/events/' . $file_name;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $file_name)) {
                    // File uploaded successfully
                } else {
                    $error_message = 'Une erreur est survenue lors du téléchargement de l\'image.';
                }
            }
        } else {
            $image_path = 'img/events/default.jpg';
        }
        
        if (empty($error_message)) {
            // Prepare data for insertion
            $title = $conn->real_escape_string($title);
            $date = $conn->real_escape_string($date);
            $location = $conn->real_escape_string($location);
            $category = $conn->real_escape_string($category);
            $price = $conn->real_escape_string($price);
            $description = $conn->real_escape_string($description);
            $image_path = $conn->real_escape_string($image_path);
            $invited_count = (int) $invited_count;
            $attendee_count = (int) $attendee_count;
            
            // Insert event
            $sql = "INSERT INTO events (title, date, location, category, price, description, image, invited_count, attendee_count) 
                    VALUES ('$title', '$date', '$location', '$category', '$price', '$description', '$image_path', $invited_count, $attendee_count)";
            
            if ($conn->query($sql)) {
                $success_message = 'L\'événement a été ajouté avec succès.';
                
                // Clear form data
                $title = $date = $location = $category = $price = $description = '';
                $invited_count = $attendee_count = 0;
            } else {
                $error_message = 'Une erreur est survenue lors de l\'ajout de l\'événement.';
            }
        }
    }
}

// Include header
include '../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Ajouter un Événement</h1>
        <a href="events.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
    
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
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="add_event.php" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Nom de l'événement *</label>
                            <input type="text" class="form-control" id="title" name="title" required value="<?php echo htmlspecialchars($title ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">Date *</label>
                            <input type="date" class="form-control" id="date" name="date" required value="<?php echo htmlspecialchars($date ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="location" class="form-label">Lieu *</label>
                            <select class="form-select" id="location" name="location" required>
                                <option value="">Sélectionner un lieu</option>
                                <?php foreach ($locations as $loc): ?>
                                    <option value="<?php echo htmlspecialchars($loc['name']); ?>" <?php echo (isset($location) && $location == $loc['name']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($loc['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Type d'événement *</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Sélectionner un type</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['name']); ?>" <?php echo (isset($category) && $category == $cat['name']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix *</label>
                            <input type="text" class="form-control" id="price" name="price" required value="<?php echo htmlspecialchars($price ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <div class="form-text">Formats acceptés: JPG, PNG, GIF. Taille max: 5 MB.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="invited_count" class="form-label">Nombre d'invités</label>
                            <input type="number" class="form-control" id="invited_count" name="invited_count" min="0" value="<?php echo htmlspecialchars($invited_count ?? 0); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="attendee_count" class="form-label">Nombre de participants</label>
                            <input type="number" class="form-control" id="attendee_count" name="attendee_count" min="0" value="<?php echo htmlspecialchars($attendee_count ?? 0); ?>">
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Ajouter l'événement
                        </button>
                        <a href="events.php" class="btn btn-outline-secondary ms-2">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
include '../includes/footer.php';

// Close database connection
$conn->close();
?>
