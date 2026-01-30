<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'E-Commerce'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .banner-img {
            max-height: 300px;
            object-fit: cover;
            width: 100%;
        }
        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .promo-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .categories-slider {
            overflow-x: auto;
            white-space: nowrap;
            padding: 10px 0;
        }
        .category-item {
            display: inline-block;
            margin-right: 15px;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }
        .category-item:hover {
            background: #007bff;
            color: white;
        }
        footer {
            background: #343a40;
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
        .logo-img {
            max-height: 50px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?page=accueil">
                <?php if (isset($logo) && $logo): ?>
                    <img src="public/uploads/logo.png" alt="Logo" class="logo-img me-2">
                <?php endif; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=accueil">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=produits">Produits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=categories">Catégories</a>
                    </li>
                    <?php if (isset($_SESSION['utilisateur_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=profil">
                                <i class="bi bi-person-circle"></i> Mon Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=connexion&action=logout">Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=connexion">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=inscription">S'inscrire</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <a href="index.php?page=panier" class="btn btn-outline-light position-relative ms-2">
                <i class="bi bi-cart3"></i> Panier
                <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php 
                    $panier = new Panier();
                    echo $panier->getCount();
                    ?>
                </span>
            </a>
        </div>
    </nav>
    <div class="container mt-3">
        <?php if (isset($_SESSION['succes'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['succes']; unset($_SESSION['succes']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['erreur'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['erreur']; unset($_SESSION['erreur']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['erreurs'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php 
                    foreach ($_SESSION['erreurs'] as $erreur) {
                        echo '<li>' . htmlspecialchars($erreur) . '</li>';
                    }
                    unset($_SESSION['erreurs']);
                    ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <main class="container my-4">
