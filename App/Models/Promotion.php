<?php

class Promotion {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getActive(): array {
    try {
        $stmt = $this->db->prepare("
            SELECT pr.*, p.nomProduit, p.prix, p.urlImage, c.nomCategorie
            FROM promotions pr
            LEFT JOIN produits p ON pr.idProduit = p.idProduit
            LEFT JOIN categories c ON p.idCategorie = c.idCategorie
            WHERE pr.dateLimite > NOW()
            ORDER BY pr.dateCreation DESC
        ");
        $stmt->execute();
        $promotions = $stmt->fetchAll();
        
        // Correction des chemins d'images
        foreach ($promotions as &$promo) {
            if (!empty($promo['urlImage'])) {
                if (!preg_match('/^https?:\/\//', $promo['urlImage']) && 
                    !preg_match('/^public\//', $promo['urlImage'])) {
                    $promo['urlImage'] = 'public/uploads/' . basename($promo['urlImage']);
                }
            } else {
                $promo['urlImage'] = 'public/uploads/default.png';
            }
        }
        
        return $promotions;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}

    public function getByProduct(int $idProduit) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM promotions WHERE idProduit = :id AND dateLimite > NOW() LIMIT 1");
            $stmt->execute([':id' => $idProduit]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function creer(int $idProduit, float $pourcentageReduction, float $montantReduction, string $dateLimite) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO promotions (idProduit, pourcentageReduction, montantReduction, dateLimite)
                VALUES (:produit, :pourcentage, :montant, :date)
            ");
            $stmt->execute([
                ':produit' => $idProduit,
                ':pourcentage' => $pourcentageReduction,
                ':montant' => $montantReduction,
                ':date' => $dateLimite
            ]);
            return Database::getInstance()->lastInsertId();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function update(int $idPromotion, float $pourcentageReduction, float $montantReduction, string $dateLimite): bool {
        try {
            $stmt = $this->db->prepare("
                UPDATE promotions
                SET pourcentageReduction = :pourcentage, montantReduction = :montant, dateLimite = :date
                WHERE idPromotion = :id
            ");
            return $stmt->execute([
                ':id' => $idPromotion,
                ':pourcentage' => $pourcentageReduction,
                ':montant' => $montantReduction,
                ':date' => $dateLimite
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete(int $idPromotion): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM promotions WHERE idPromotion = :id");
            return $stmt->execute([':id' => $idPromotion]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function calculateFinalPrice(float $prix, ?array $promotion): float {
        $finalPrice = $prix;
        if ($promotion) {
            if (!empty($promotion['pourcentageReduction'])) {
                $finalPrice = $prix - ($prix * $promotion['pourcentageReduction'] / 100);
            } elseif (!empty($promotion['montantReduction'])) {
                $finalPrice = $prix - $promotion['montantReduction'];
            }
        }
        return max($finalPrice, 0);
    }
}
