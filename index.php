<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', dirname(dirname(__FILE__)));
// needs spacenames
require_once BASE_PATH . '/E-comerce/App/Config/Database.php';

require_once BASE_PATH . '/E-comerce/App/Models/Categorie.php';
require_once BASE_PATH . '/E-comerce/App/Models/Produit.php';
require_once BASE_PATH . '/E-comerce/App/Models/Promotion.php';
require_once BASE_PATH . '/E-comerce/App/Models/Publicite.php';
require_once BASE_PATH . '/E-comerce/App/Models/Image.php';
require_once BASE_PATH . '/E-comerce/App/Models/Panier.php';
require_once BASE_PATH . '/E-comerce/App/Models/Commande.php';
require_once BASE_PATH . '/E-comerce/App/Models/Utilisateur.php';
require_once BASE_PATH . '/E-comerce/App/Models/PDFGenerator.php';

require_once BASE_PATH . '/E-comerce/App/Controllers/AccueilController.php';
require_once BASE_PATH . '/E-comerce/App/Controllers/ProduitsController.php';
require_once BASE_PATH . '/E-comerce/App/Controllers/CategoriesController.php';
require_once BASE_PATH . '/E-comerce/App/Controllers/PanierController.php';
require_once BASE_PATH . '/E-comerce/App/Controllers/AuthController.php';
require_once BASE_PATH . '/E-comerce/App/Controllers/ProfilController.php';
require_once BASE_PATH . '/E-comerce/App/Controllers/CommandeController.php';

function view($viewName, $data = []) {
    extract($data);
    $viewPath = BASE_PATH . '/E-comerce/App/Views/' . $viewName . '.php';
    
    if (file_exists($viewPath)) {
        ob_start();
        include($viewPath);
        return ob_get_clean();
    } else {
        return "<h3>Erreur: Vue {$viewName} non trouvée</h3>";
    }
}
$page = $_GET['page'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

$jsonActions = ['add', 'remove', 'updateQuantite', 'vider'];

try {
    $output = '';
    switch ($page) {
        case 'accueil':
            $controller = new AccueilController();
            $output = $controller->index();
            break;
            
        case 'produits':
            $controller = new ProduitsController();
            if ($action === 'detail') {
                $output = $controller->detail();
            } else {
                $output = $controller->index();
            }
            break;
            
        case 'produit':
            $controller = new ProduitsController();
            $output = $controller->detail();
            break;
            
        case 'categories':
            $controller = new CategoriesController();
            $output = $controller->index();
            break;
            
        case 'panier':
            $controller = new PanierController();
            
            if (in_array($action, $jsonActions)) {
                header('Content-Type: application/json');
                
                switch ($action) {
                    case 'add':
                        echo $controller->add();
                        exit;
                    case 'remove':
                        echo $controller->remove();
                        exit;
                    case 'updateQuantite':
                        echo $controller->updateQuantite();
                        exit;
                    case 'vider':
                        echo $controller->vider();
                        exit;
                }
            } else {
                $output = $controller->index();
            }
            break;
            
        case 'connexion':
            $controller = new AuthController();
            
            if ($action === 'logout') {
                $controller->logout();
            } elseif ($action === 'traiter') {
                $controller->traiterConnexion();
            } else {
                $output = $controller->connexion();
            }
            break;
            
        case 'inscription':
            $controller = new AuthController();
            
            if ($action === 'traiter') {
                $controller->traiterInscription();
            } else {
                $output = $controller->inscription();
            }
            break;
            
        case 'profil':
            $controller = new ProfilController();
            
            if ($action === 'update') {
                $controller->update();
            } elseif ($action === 'commande') {
                $output = $controller->commande();
            } else {
                $output = $controller->index();
            }
            break;
            
        case 'commande':
            $controller = new CommandeController();
            
            if ($action === 'placer') {
                $controller->placer();
            } else {
                $output = $controller->index();
            }
            break;
            
        default:
            header("HTTP/1.0 404 Not Found");
            $output = view('404');
    }
    
    echo $output;
    
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "<div class='container mt-5'><div class='alert alert-danger'>Erreur: Une erreur s'est produite.</div></div>";
}
