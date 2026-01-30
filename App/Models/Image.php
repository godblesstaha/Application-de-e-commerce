<?php

class Image {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getById($idImage) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM images WHERE idImage = :id");
            $stmt->execute([':id' => $idImage]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getByType($type) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM images WHERE type = :type");
            $stmt->execute([':type' => $type]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getLogo() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM images WHERE type = 'logo' LIMIT 1");
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function ajouter($urlImage, $type) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO images (urlImage, type)
                VALUES (:url, :type)
            ");
            
            return $stmt->execute([
                ':url' => $urlImage,
                ':type' => $type
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
