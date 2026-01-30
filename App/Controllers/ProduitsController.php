<?php
class ProduitsController {
    private $produitModel;
    private $categorieModel;
    private $promotionModel;
    private $imageModel;
    
    public function __construct() {
        $this->produitModel = new Produit();
        $this->categorieModel = new Categorie();
        $this->promotionModel = new Promotion();
        $this->imageModel = new Image();
    }
    
    public function index() {
    $produits = [];
    $categorie = null;
    
    if (isset($_GET['categorie'])) {
        $idCategorie = (int)$_GET['categorie'];
        $categorie = $this->categorieModel->getById($idCategorie);
        $produits = $this->produitModel->getByCategory($idCategorie);
    } else {
        $produits = $this->produitModel->getAll();
    }
    
    foreach ($produits as &$produit) {
        $produit['promotion'] = $this->promotionModel->getByProduct($produit['idProduit']);
    }
    
    $data = [
        'produits' => $produits,
        'categorie' => $categorie,
        'categories' => $this->categorieModel->getAll(),
        'logo' => $this->imageModel->getLogo()
    ];
    
    return view('produits', $data);
}

public function detail() {
    if (!isset($_GET['id'])) {
        header('Location: index.php?page=produits');
        exit;
    }
    
    $idProduit = (int)$_GET['id'];
    $produit = $this->produitModel->getById($idProduit);
    
    if (!$produit) {
        header('Location: index.php?page=produits');
        exit;
    }
    
    $produit['promotion'] = $this->promotionModel->getByProduct($idProduit);
    
    $categorie = $this->categorieModel->getById($produit['idCategorie']);
    $produit['nomCategorie'] = $categorie['nomCategorie'] ?? 'Non catégorisé';
    
    $data = [
        'produit' => $produit,
        'categories' => $this->categorieModel->getAll(),
        'logo' => $this->imageModel->getLogo()
    ];
    
    return view('produit_detail', $data);
}
}