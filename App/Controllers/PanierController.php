<?php

class PanierController {
    private $produitModel;
    private $panierModel;

    public function __construct() {
        $this->produitModel = new Produit();
        $this->panierModel = new Panier();
    }

    public function index() {
        $panierSession = $this->panierModel->getPanier();
        $panierDetails = [];

        foreach ($panierSession as $idProduit => $item) {
            $produit = $this->produitModel->getById($idProduit);

            if ($produit) {
                $panierDetails[] = [
                    'idProduit'  => $idProduit,
                    'nomProduit' => $produit['nomProduit'],
                    'urlImage'   => $produit['urlImage'],
                    'quantite'   => $item['quantite'],
                    'prix'       => $item['prix']
                ];
            }
        }

        return view('panier', [
            'panier' => $panierDetails,
            'total'  => $this->panierModel->getTotal(),
            'count'  => $this->panierModel->getCount()
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
    public function updateQuantite() {
    $idProduit = (int)($_GET['id'] ?? 0);
    $quantite = (int)($_GET['quantite'] ?? 1);

    $panier = $this->panierModel->getPanier();
    if(isset($panier[$idProduit])) {
        $this->panierModel->ajouter($idProduit, $quantite - $panier[$idProduit]['quantite'], $panier[$idProduit]['prix']);
        echo json_encode([
            'success' => true,
            'prix' => $panier[$idProduit]['prix'],
            'total' => $this->panierModel->getTotal()
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
}

}
