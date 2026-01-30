<?php
class AccueilController {
    private $produitModel;
    private $categorieModel;
    private $promotionModel;
    private $publiciteModel;
    private $imageModel;
    
    public function __construct() {
        $this->produitModel = new Produit();
        $this->categorieModel = new Categorie();
        $this->promotionModel = new Promotion();
        $this->publiciteModel = new Publicite();
        $this->imageModel = new Image();
    }
    
    public function index() {
    $data = [
        'categories' => $this->categorieModel->getAll(),
        'promotions' => $this->promotionModel->getActive(),
        'publicites' => $this->publiciteModel->getActive(),
        'produits' => $this->produitModel->getAll(),
        'logo' => $this->imageModel->getLogo()
    ];
    
    foreach ($data['promotions'] as &$promo) {
        if (!empty($promo['urlImage'])) {
            if (!preg_match('/^https?:\/\//', $promo['urlImage']) && 
                !preg_match('/^public\//', $promo['urlImage'])) {
                $promo['urlImage'] = 'public/' . $promo['urlImage'];
            }
        }
    }
    
    foreach ($data['publicites'] as &$pub) {
        if (!empty($pub['urlImage'])) {
            if (!preg_match('/^https?:\/\//', $pub['urlImage']) && 
                !preg_match('/^public\//', $pub['urlImage'])) {
                $pub['urlImage'] = 'public/' . $pub['urlImage'];
            }
        }
    }
    
    return view('accueil', $data);
}
}
