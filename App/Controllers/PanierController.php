<?php

class PanierController {
    private $produitModel;
    private $panierModel;
    private $imageModel;
    private $categorieModel;

    public function __construct() {
        $this->produitModel = new Produit();
        $this->panierModel = new Panier();
        $this->imageModel = new Image();
        $this->categorieModel = new Categorie();
    }

    public function index() {
    $panierSession = $this->panierModel->getPanier();
    $panierDetails = [];

    foreach ($panierSession as $idProduit => $item) {
        $produit = $this->produitModel->getById($idProduit);

        if ($produit) {
            $imagePath = $produit['urlImage'] ?? 'public/uploads/default.png';
            
            $panierDetails[] = [
                'idProduit'  => $idProduit,
                'nomProduit' => $produit['nomProduit'],
                'urlImage'   => $imagePath,
                'quantite'   => $item['quantite'],
                'prix'       => $item['prix']
            ];
        }
    }

    return view('panier', [
        'panier' => $panierDetails,
        'total'  => $this->panierModel->getTotal(),
        'count'  => $this->panierModel->getCount(),
        'logo' => $this->imageModel->getLogo(),
        'categories' => $this->categorieModel->getAll()
    ]);
}


    public function add() {
        $idProduit = (int)($_GET['id'] ?? 0);
        $quantite = (int)($_GET['quantite'] ?? 1);

        $produit = $this->produitModel->getById($idProduit);
        if (!$produit) {
            echo json_encode(['success' => false]);
            return;
        }

        $this->panierModel->ajouter($idProduit, $quantite, $produit['prix']);

        echo json_encode([
            'success' => true,
            'count' => $this->panierModel->getCount()
        ]);
    }

    public function remove() {
        $idProduit = (int)($_GET['id'] ?? 0);
        $this->panierModel->supprimer($idProduit);
        echo json_encode(['success' => true]);
    }

    public function vider() {
        $this->panierModel->vider();
        echo json_encode(['success' => true]);
    }
}
