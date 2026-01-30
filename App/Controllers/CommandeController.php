<?php

class CommandeController {
    private $commandeModel;
    private $panierModel;
    private $utilisateurModel;
    private $produitModel;
    private $imageModel;
    private $categorieModel;
    
    public function __construct() {
        $this->commandeModel = new Commande();
        $this->panierModel = new Panier();
        $this->utilisateurModel = new Utilisateur();
        $this->produitModel = new Produit();
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
        
        if ($this->panierModel->getCount() == 0) {
            $_SESSION['erreur'] = 'Votre panier est vide';
            header('Location: index.php?page=panier');
            exit;
        }
        
        $utilisateur = $this->utilisateurModel->getById($_SESSION['utilisateur_id']);
        $panier = $this->panierModel->getPanier();
        $total = $this->panierModel->getTotal();
        
        $data = [
            'utilisateur' => $utilisateur,
            'panier' => $panier,
            'total' => $total,
            'logo' => $this->imageModel->getLogo(),
            'categories' => $this->categorieModel->getAll()
        ];
        
        return view('commande', $data);
    }
    public function placer() {
        $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=commande');
            exit;
        }
        
        if ($this->panierModel->getCount() == 0) {
            $_SESSION['erreur'] = 'Votre panier est vide';
            header('Location: index.php?page=panier');
            exit;
        }
        
        $idUtilisateur = $_SESSION['utilisateur_id'];
        $montantTotal = $this->panierModel->getTotal();
        $panier = $this->panierModel->getPanier();
        
        $idCommande = $this->commandeModel->creer($idUtilisateur, $montantTotal);
        
        if (!$idCommande) {
            $_SESSION['erreur'] = 'Erreur lors de la création de la commande';
            header('Location: index.php?page=commande');
            exit;
        }
        
        foreach ($panier as $idProduit => $item) {
            $this->commandeModel->ajouterLigne($idCommande, $idProduit, $item['quantite'], $item['prix']);
        }
        
        $commande = $this->commandeModel->getById($idCommande);
        $utilisateur = $this->utilisateurModel->getById($idUtilisateur);
        $lignes = $this->commandeModel->getLignes($idCommande);
        $logo = $this->imageModel->getLogo();
        
        $shipping = null;
        if (!empty($_POST['useDifferent'])) {
            $shipping = [
                'prenom' => trim($_POST['shipping_prenom'] ?? ''),
                'nom' => trim($_POST['shipping_nom'] ?? ''),
                'email' => trim($_POST['shipping_email'] ?? ''),
                'telephone' => trim($_POST['shipping_telephone'] ?? ''),
                'adresse' => trim($_POST['shipping_adresse'] ?? ''),
                'ville' => trim($_POST['shipping_ville'] ?? '')
            ];
        }

        $pdfGenerator = new PDFGenerator($commande, $utilisateur, $lignes, $logo, $shipping);
        
        $this->panierModel->vider();
        
        $downloaded = $pdfGenerator->download('commande_' . $idCommande . '.pdf');
        if (!$downloaded) {
            header('Location: index.php?page=commande&error=pdf_missing');
            exit;
        }
        exit;
    }
}
?>