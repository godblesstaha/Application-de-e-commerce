<?php

class ProfilController {
    private $utilisateurModel;
    private $commandeModel;
    private $imageModel;
    private $categorieModel;
    
    public function __construct() {
        $this->utilisateurModel = new Utilisateur();
        $this->commandeModel = new Commande();
        $this->imageModel = new Image();
        $this->categorieModel = new Categorie();
    }
    
    private function checkLogin() {
        if (!isset($_SESSION['utilisateur_id'])) {
            header('Location: index.php?page=connexion');
            exit;
        }
    }
    
    public function index() {
        $this->checkLogin();
        
        $utilisateur = $this->utilisateurModel->getById($_SESSION['utilisateur_id']);
        $commandes = $this->commandeModel->getByUser($_SESSION['utilisateur_id']);
        
        $data = [
            'utilisateur' => $utilisateur,
            'commandes' => $commandes,
            'logo' => $this->imageModel->getLogo(),
            'categories' => $this->categorieModel->getAll()
        ];
        
        return view('profil', $data);
    }
    public function update() {
        $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=profil');
            exit;
        }
        
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        $ville = trim($_POST['ville'] ?? '');
        
        if (empty($nom) || empty($prenom)) {
            $_SESSION['erreur'] = 'Nom et prénom requis';
            header('Location: index.php?page=profil');
            exit;
        }
        
        $success = $this->utilisateurModel->update(
            $_SESSION['utilisateur_id'],
            $nom,
            $prenom,
            $telephone,
            $adresse,
            $ville
        );
        
        if ($success) {
            $_SESSION['succes'] = 'Profil mis à jour avec succès';
            $_SESSION['utilisateur_nom'] = $prenom . ' ' . $nom;
        } else {
            $_SESSION['erreur'] = 'Erreur lors de la mise à jour';
        }
        
        header('Location: index.php?page=profil');
        exit;
    }
    
    public function commande() {
        $this->checkLogin();
        
        if (!isset($_GET['id'])) {
            header('Location: index.php?page=profil');
            exit;
        }
        
        $idCommande = (int)$_GET['id'];
        $commande = $this->commandeModel->getById($idCommande);
        
        if (!$commande || $commande['idUtilisateur'] != $_SESSION['utilisateur_id']) {
            header('Location: index.php?page=profil');
            exit;
        }
        
        $lignes = $this->commandeModel->getLignes($idCommande);
        
        $data = [
            'commande' => $commande,
            'lignes' => $lignes,
            'logo' => $this->imageModel->getLogo(),
            'categories' => $this->categorieModel->getAll()
        ];
        
        return view('commande_detail', $data);
    }
}
