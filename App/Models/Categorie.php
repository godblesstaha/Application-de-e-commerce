<?php

class Categorie {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY nomCategorie ASC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getById(int $idCategorie) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories WHERE idCategorie = :id");
            $stmt->execute([':id' => $idCategorie]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function creer(string $nomCategorie, string $description) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO categories (nomCategorie, description)
                VALUES (:nom, :description)
            ");
            $stmt->execute([
                ':nom' => $nomCategorie,
                ':description' => $description
            ]);
            return Database::getInstance()->lastInsertId();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function update(int $idCategorie, string $nomCategorie, string $description): bool {
        try {
            $stmt = $this->db->prepare("
                UPDATE categories SET nomCategorie = :nom, description = :description WHERE idCategorie = :id
            ");
            return $stmt->execute([
                ':id' => $idCategorie,
                ':nom' => $nomCategorie,
                ':description' => $description
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete(int $idCategorie): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM categories WHERE idCategorie = :id");
            return $stmt->execute([':id' => $idCategorie]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
