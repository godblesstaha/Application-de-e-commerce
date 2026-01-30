<?php
class Commande {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function creer($idUtilisateur, $montantTotal) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO commandes (idUtilisateur, montantTotal, statut)
                VALUES (:utilisateur, :montant, 'en_attente')
            ");
            
            $stmt->execute([
                ':utilisateur' => $idUtilisateur,
                ':montant' => $montantTotal
            ]);
            
            return Database::getInstance()->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function ajouterLigne($idCommande, $idProduit, $quantite, $prixUnitaire) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO lignesCommandes (idCommande, idProduit, quantite, prixUnitaire)
                VALUES (:commande, :produit, :quantite, :prix)
            ");
            
            return $stmt->execute([
                ':commande' => $idCommande,
                ':produit' => $idProduit,
                ':quantite' => $quantite,
                ':prix' => $prixUnitaire
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getById($idCommande) {
        try {
            $stmt = $this->db->prepare("
                SELECT c.*, u.email, u.nom, u.prenom, u.telephone, u.adresse, u.ville
                FROM commandes c
                LEFT JOIN utilisateurs u ON c.idUtilisateur = u.id
                WHERE c.idCommande = :id
            ");
            $stmt->execute([':id' => $idCommande]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getLignes($idCommande) {
        try {
            $stmt = $this->db->prepare("
                SELECT lc.*, p.nomProduit, p.urlImage
                FROM lignesCommandes lc
                LEFT JOIN produits p ON lc.idProduit = p.idProduit
                WHERE lc.idCommande = :id
            ");
            $stmt->execute([':id' => $idCommande]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function getByUser($idUtilisateur) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM commandes 
                WHERE idUtilisateur = :utilisateur
                ORDER BY dateCommande DESC
            ");
            $stmt->execute([':utilisateur' => $idUtilisateur]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function updateStatut($idCommande, $statut) {
        try {
            $stmt = $this->db->prepare("
                UPDATE commandes SET statut = :statut WHERE idCommande = :id
            ");
            
            return $stmt->execute([
                ':id' => $idCommande,
                ':statut' => $statut
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
