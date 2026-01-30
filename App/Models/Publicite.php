<?php

class Publicite {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getActive(): array {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM publicites
                WHERE dateDebut <= NOW() AND dateFin >= NOW()
                ORDER BY dateDebut DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getAll(): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM publicites ORDER BY dateDebut DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getById(int $idPub) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM publicites WHERE idPub = :id");
            $stmt->execute([':id' => $idPub]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function ajouter(string $urlImage, string $dateDebut, string $dateFin): bool {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO publicites (urlImage, dateDebut, dateFin)
                VALUES (:url, :debut, :fin)
            ");
            return $stmt->execute([
                ':url' => $urlImage,
                ':debut' => $dateDebut,
                ':fin' => $dateFin
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function update(int $idPub, string $urlImage, string $dateDebut, string $dateFin): bool {
        try {
            $stmt = $this->db->prepare("
                UPDATE publicites
                SET urlImage = :url, dateDebut = :debut, dateFin = :fin
                WHERE idPub = :id
            ");
            return $stmt->execute([
                ':id' => $idPub,
                ':url' => $urlImage,
                ':debut' => $dateDebut,
                ':fin' => $dateFin
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete(int $idPub): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM publicites WHERE idPub = :id");
            return $stmt->execute([':id' => $idPub]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
