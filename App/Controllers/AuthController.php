<?php

class AuthController {
    private $utilisateurModel;
    private $imageModel;
    private $categorieModel;
    
    public function __construct() {
        $this->utilisateurModel = new Utilisateur();
        $this->imageModel = new Image();
        $this->categorieModel = new Categorie();
    }
    
    public function connexion() {
        $data = [
            'logo' => $this->imageModel->getLogo(),
            'categories' => $this->categorieModel->getAll()
        ];
        
        return view('connexion', $data);
    }
    
    public function traiterConnexion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=connexion');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        $motdepasse = $_POST['motdepasse'] ?? '';
        if (empty($email) || empty($motdepasse)) {
            $_SESSION['erreur'] = 'Email et mot de passe requis';
            header('Location: index.php?page=connexion');
            exit;
        }
        
        $utilisateur = $this->utilisateurModel->getByEmail($email);
        
        if (!$utilisateur || !$this->utilisateurModel->verifyPassword($motdepasse, $utilisateur['motdepasse'])) {
            $_SESSION['erreur'] = 'Email ou mot de passe incorrect';
            header('Location: index.php?page=connexion');
            exit;
        }
        
        if ($utilisateur['statut'] !== 'actif') {
            $_SESSION['erreur'] = 'Compte inactif';
            header('Location: index.php?page=connexion');
            exit;
        }
        
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['utilisateur_email'] = $utilisateur['email'];
        $_SESSION['utilisateur_nom'] = $utilisateur['prenom'] . ' ' . $utilisateur['nom'];
        
        $_SESSION['succes'] = 'Connexion réussie!';
        header('Location: index.php?page=accueil');
        exit;
    }
    
    public function inscription() {
        $data = [
            'logo' => $this->imageModel->getLogo(),
            'categories' => $this->categorieModel->getAll()
        ];
        
        return view('inscription', $data);
    }
    
    public function traiterInscription() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=inscription');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        $motdepasse = $_POST['motdepasse'] ?? '';
        $confirmation = $_POST['confirmation'] ?? '';
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        $ville = trim($_POST['ville'] ?? '');
        
        $erreurs = [];
        
        if (empty($email)) {
            $erreurs[] = 'Email requis';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'Email invalide';
        } elseif ($this->utilisateurModel->emailExists($email)) {
            $erreurs[] = 'Email déjà utilisé';
        }
        
        if (empty($motdepasse)) {
            $erreurs[] = 'Mot de passe requis';
        } elseif (strlen($motdepasse) < 6) {
            $erreurs[] = 'Mot de passe minimum 6 caractères';
        } elseif ($motdepasse !== $confirmation) {
            $erreurs[] = 'Les mots de passe ne correspondent pas';
        }
        
        if (empty($nom)) {
            $erreurs[] = 'Nom requis';
        }
        
        if (empty($prenom)) {
            $erreurs[] = 'Prénom requis';
        }
        
        if (!empty($erreurs)) {
            $_SESSION['erreurs'] = $erreurs;
            header('Location: index.php?page=inscription');
            exit;
        }
        
        $userId = $this->utilisateurModel->creer($email, $motdepasse, $nom, $prenom, $telephone, $adresse, $ville);
        
        if (!$userId) {
            $_SESSION['erreur'] = 'Erreur lors de l\'inscription';
            header('Location: index.php?page=inscription');
            exit;
        }
        
        $_SESSION['succes'] = 'Inscription réussie! Veuillez vous connecter.';
        header('Location: index.php?page=connexion');
        exit;
    }
    
    public function logout() {
        unset($_SESSION['utilisateur_id']);
        unset($_SESSION['utilisateur_email']);
        unset($_SESSION['utilisateur_nom']);
        
        $_SESSION['succes'] = 'Déconnexion réussie';
        header('Location: index.php?page=accueil');
        exit;
    }
}
