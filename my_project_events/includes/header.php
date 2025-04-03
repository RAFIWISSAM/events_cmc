<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMC Event Hub - <?php echo $page_title ?? 'Événements'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo isset($admin_page) ? '../css/style.css' : 'css/style.css'; ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo isset($admin_page) ? '../index.php' : 'index.php'; ?>">
                <div class="logo-container bg-white rounded d-flex align-items-center justify-content-center me-2">
                    <img src="https://cdn-06.9rayti.com/rsrc/cache/widen_750/uploads/2022/09/cmc-ofppt-feuille-route-developpement-formation-professionnelle.png" alt="CMC Logo" style="height: 40px;">
                </div>&nbsp;
                <span>Event Hub</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'home') ? 'active' : ''; ?>" href="<?php echo isset($admin_page) ? '../index.php' : 'index.php'; ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'events') ? 'active' : ''; ?>" href="<?php echo isset($admin_page) ? '../events.php' : 'events.php'; ?>">Événements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'contact') ? 'active' : ''; ?>" href="<?php echo isset($admin_page) ? '../contact.php' : 'contact.php'; ?>">Contact</a>
                    </li>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'admin') ? 'active' : ''; ?>" href="<?php echo isset($admin_page) ? 'index.php' : 'admin/index.php'; ?>">Administration</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i> <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="<?php echo isset($admin_page) ? '../profile.php' : 'profile.php'; ?>"><i class="fas fa-id-card me-2"></i>Mon profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo isset($admin_page) ? '../logout.php' : 'logout.php'; ?>"><i class="fas fa-sign-out-alt me-2"></i>Se déconnecter</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'login') ? 'active' : ''; ?>" href="<?php echo isset($admin_page) ? '../login.php' : 'login.php'; ?>">Se Connecter</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
