<?php
require_once 'config/database.php';

class Wine {
    private $conn;
    private $table_name = "wines";

    public $id;
    public $user_id;
    public $name;
    public $year;
    public $grapes;
    public $country;
    public $region;
    public $description;
    public $picture;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Créer une bouteille
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET user_id=:user_id, name=:name, year=:year, grapes=:grapes, 
                      country=:country, region=:region, description=:description, picture=:picture";

        $stmt = $this->conn->prepare($query);

        // Bind des paramètres
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":year", $this->year);
        $stmt->bindParam(":grapes", $this->grapes);
        $stmt->bindParam(":country", $this->country);
        $stmt->bindParam(":region", $this->region);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":picture", $this->picture);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Récupérer toutes les bouteilles d'un utilisateur
    public function getByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une bouteille par ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->name = $row['name'];
            $this->year = $row['year'];
            $this->grapes = $row['grapes'];
            $this->country = $row['country'];
            $this->region = $row['region'];
            $this->description = $row['description'];
            $this->picture = $row['picture'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Mettre à jour une bouteille
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=:name, year=:year, grapes=:grapes, country=:country, 
                      region=:region, description=:description, picture=:picture 
                  WHERE id=:id AND user_id=:user_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":year", $this->year);
        $stmt->bindParam(":grapes", $this->grapes);
        $stmt->bindParam(":country", $this->country);
        $stmt->bindParam(":region", $this->region);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":picture", $this->picture);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }

    // Supprimer une bouteille
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":user_id", $user_id);
        
        return $stmt->execute();
    }

    // Compter les bouteilles d'un utilisateur
    public function countByUserId($user_id) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                  WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Récupérer toutes les bouteilles (pour admin)
    public function getAllWines() {
        $query = "SELECT w.*, u.name as user_name, u.email as user_email 
                  FROM " . $this->table_name . " w 
                  LEFT JOIN users u ON w.user_id = u.id 
                  ORDER BY w.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>