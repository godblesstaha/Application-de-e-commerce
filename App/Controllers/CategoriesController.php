<?php

class CategoriesController {
    private $categorieModel;
    private $imageModel;
    
    public function __construct() {
        $this->categorieModel = new Categorie();
        $this->imageModel = new Image();
    }
    
    public function index() {
        $data = [
            'categories' => $this->categorieModel->getAll(),
            'logo' => $this->imageModel->getLogo()
        ];
        
        return view('categories', $data);
    }
}
