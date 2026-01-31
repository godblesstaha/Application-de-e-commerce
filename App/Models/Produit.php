<?php

class Produit {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
    try {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nomCategorie FROM produits p
            LEFT JOIN categories c ON p.idCategorie = c.idCategorie
            ORDER BY p.idProduit DESC
        ");
        $stmt->execute();
        $results = $stmt->fetchAll();

        return array_map(function($p) {
            if (!empty($p['urlImage'])) {
                if (preg_match('/^https?:\/\//', $p['urlImage'])) {
                    return $p;
                }
                if (preg_match('/^(public\/|uploads\/)/', $p['urlImage'])) {
                    return $p;
                }
                $p['urlImage'] = 'public/uploads/' . basename($p['urlImage']);
            } else {
                $p['urlImage'] = 'public/uploads/default.png';
            }
            return $p;
        }, $results);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}
public function getByCategory(int $idCategorie): array {
    try {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nomCategorie FROM produits p
            LEFT JOIN categories c ON p.idCategorie = c.idCategorie
            WHERE p.idCategorie = :id
            ORDER BY p.nomProduit ASC
        ");
        $stmt->execute([':id' => $idCategorie]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}
    public function getById($idProduit) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    idProduit,
                    nomProduit,
                    description,
                    prix,
                    urlImage,
                    idCategorie,
                    quantiteStock,
                    marque
                FROM produits
                WHERE idProduit = ?
            ");
            $stmt->execute([$idProduit]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($produit) {
                if (!empty($produit['urlImage'])) {
                    // Si c'est déjà une URL externe, ne rien changer
                    if (!preg_match('/^https?:\/\//', $produit['urlImage'])) {
                        $produit['urlImage'] = 'public/uploads/' . basename($produit['urlImage']);
                    }
                } else {
                    $produit['urlImage'] = 'public/uploads/default.png';
                }
            }
            
            return $produit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function getWithPromotions(): array {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, c.nomCategorie, pr.idPromotion, pr.pourcentageReduction, pr.montantReduction, pr.dateLimite
                FROM produits p
                LEFT JOIN categories c ON p.idCategorie = c.idCategorie
                LEFT JOIN promotions pr ON p.idProduit = pr.idProduit
                WHERE pr.idPromotion IS NOT NULL AND pr.dateLimite > NOW()
                ORDER BY p.idProduit DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function creer(string $nomProduit, string $description, float $prix, string $urlImage, int $idCategorie, int $quantiteStock, string $marque) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO produits (nomProduit, description, prix, urlImage, idCategorie, quantiteStock, marque)
                VALUES (:nom, :desc, :prix, :image, :cat, :stock, :marque)
            ");
            $stmt->execute([
                ':nom' => $nomProduit,
                ':desc' => $description,
                ':prix' => $prix,
                ':image' => $urlImage,
                ':cat' => $idCategorie,
                ':stock' => $quantiteStock,
                ':marque' => $marque
            ]);
            return Database::getInstance()->lastInsertId();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function update(int $idProduit, string $nomProduit, string $description, float $prix, string $urlImage, int $idCategorie, int $quantiteStock, string $marque) {
        try {
            $stmt = $this->db->prepare("
                UPDATE produits
                SET nomProduit = :nom, description = :desc, prix = :prix,
                    urlImage = :image, idCategorie = :cat, quantiteStock = :stock, marque = :marque
                WHERE idProduit = :id
            ");
            return $stmt->execute([
                ':id' => $idProduit,
                ':nom' => $nomProduit,
                ':desc' => $description,
                ':prix' => $prix,
                ':image' => $urlImage,
                ':cat' => $idCategorie,
                ':stock' => $quantiteStock,
                ':marque' => $marque
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete(int $idProduit): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM produits WHERE idProduit = :id");
            return $stmt->execute([':id' => $idProduit]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function search(string $keyword): array {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, c.nomCategorie FROM produits p
                LEFT JOIN categories c ON p.idCategorie = c.idCategorie
                WHERE p.nomProduit LIKE :keyword OR p.description LIKE :keyword OR p.marque LIKE :keyword
                ORDER BY p.nomProduit ASC
            ");
            $stmt->execute([':keyword' => "%$keyword%"]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
?>
