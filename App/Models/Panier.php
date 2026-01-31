<?php

class Panier {
    private $session_key = 'panier';

    public function __construct() {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION[$this->session_key])) {
        $_SESSION[$this->session_key] = [];
    }
}

    public function ajouter($idProduit, $quantite, $prix) {
        if (isset($_SESSION[$this->session_key][$idProduit])) {
            $_SESSION[$this->session_key][$idProduit]['quantite'] += $quantite;
        } else {
            $_SESSION[$this->session_key][$idProduit] = [
                'quantite' => $quantite,
                'prix'     => $prix
            ];
        }
    }

    public function supprimer($idProduit) {
        unset($_SESSION[$this->session_key][$idProduit]);
    }

    public function vider() {
        $_SESSION[$this->session_key] = [];
    }

    public function getPanier() {
        return $_SESSION[$this->session_key];
    }

    public function getTotal() {
        $total = 0;
        foreach ($_SESSION[$this->session_key] as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        return $total;
    }

    public function getCount() {
        $count = 0;
        foreach ($_SESSION[$this->session_key] as $item) {
            $count += $item['quantite'];
        }
        return $count;
    }

    public function isEmpty() {
        return empty($_SESSION[$this->session_key]);
    }
}
