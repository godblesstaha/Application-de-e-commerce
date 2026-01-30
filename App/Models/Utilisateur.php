<?php
class Utilisateur {
    private $db;
    private $id;
    private $email;
    private $motdepasse;
    private $nom;
    private $prenom;
    private $telephone;
    private $adresse;
    private $ville;
    private $statut;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    public function creer($email, $motdepasse, $nom, $prenom, $telephone, $adresse, $ville) {
        try {
            $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("
                INSERT INTO utilisateurs (email, motdepasse, nom, prenom, telephone, adresse, ville, statut)
                VALUES (:email, :motdepasse, :nom, :prenom, :telephone, :adresse, :ville, 'actif')
            ");
            
            $stmt->execute([
                ':email' => $email,
                ':motdepasse' => $hashedPassword,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':telephone' => $telephone,
                ':adresse' => $adresse,
                ':ville' => $ville
            ]);
            
            return Database::getInstance()->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    function getByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = :email");
            $stmt->execute([':email' => $email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
    
    public function update($id, $nom, $prenom, $telephone, $adresse, $ville) {
        try {
            $stmt = $this->db->prepare("
                UPDATE utilisateurs 
                SET nom = :nom, prenom = :prenom, telephone = :telephone, 
                    adresse = :adresse, ville = :ville
                WHERE id = :id
            ");
            
            return $stmt->execute([
                ':id' => $id,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':telephone' => $telephone,
                ':adresse' => $adresse,
                ':ville' => $ville
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function emailExists($email) {
        try {
            $stmt = $this->db->prepare("SELECT id FROM utilisateurs WHERE email = :email");
            $stmt->execute([':email' => $email]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
