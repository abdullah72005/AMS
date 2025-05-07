<?php 
class alumni 
{   
    private $donations = [];
    private $id;
    private $pdo;
    public function __construct($id){
        $this->id = $id;
        $this->pdo = require('db.php');
    }
    public function makeDonation($amount, $cause)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO donation (donor_id, amount, DATE, cause) VALUES (:user_id, :amount,:DATE, :cause)");
            $stmt->bindValue(':DATE', date('Y-m-d H:i:s'));
            $stmt->bindParam(':user_id', $this->id);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':cause', $cause);
            $stmt->execute();
            return "Donation of $amount for $cause has been made successfully. on " . date('Y-m-d H:i:s');
        } catch (Exception $e) {
            return "Failed to make donation: " . $e->getMessage();
        }
    }
    public function getDonations()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM donations WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>