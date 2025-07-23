<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $email;
    public $password;
    public $name;
    public $role;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Créer un utilisateur
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET email=:email, password=:password, name=:name, role=:role";

        $stmt = $this->conn->prepare($query);

        // Hash du mot de passe
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind des paramètres
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":role", $this->role);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Authentifier un utilisateur
    public function login($email, $password) {
        $query = "SELECT id, email, password, name, role 
                  FROM " . $this->table_name . " 
                  WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->email = $row['email'];
                $this->name = $row['name'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }

    // Récupérer un utilisateur par ID
    public function getUserById($id) {
        $query = "SELECT id, email, name, role, created_at 
                  FROM " . $this->table_name . " 
                  WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->email = $row['email'];
            $this->name = $row['name'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Récupérer tous les utilisateurs (pour admin)
    public function getAllUsers() {
        $query = "SELECT id, email, name, role, created_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer un utilisateur
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
}
?>