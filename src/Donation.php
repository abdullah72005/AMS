<?php 

class Donation
{
    private $donorId;
    private $amount;
    private $date;
    private $cause;
    public function __construct($donorId, $amount, $cause)
    {
        $this->donorId = $donorId;
        $this->amount = $amount;
        $this->date = date('Y-m-d H:i:s');
        $this->cause = $cause;
    }
    public function donate()
    {
        $dbCnx = require('db.php');
        try {
            $stmt = $dbCnx->prepare("INSERT INTO Donation (donor_id, amount, date, cause) VALUES (:user_id, :amount,:DATE, :cause)");
            $db = require('db.php');
            $stmt = $db->prepare("INSERT INTO donation (donor_id, amount, DATE, cause) VALUES (:user_id, :amount,:DATE, :cause)");
            $stmt->bindValue(':DATE', $this->date);
            $stmt->bindParam(':user_id', $this->donorId);
            $stmt->bindParam(':amount', $this->amount);
            $stmt->bindParam(':cause', $this->cause);
            $stmt->execute();
            return "Donation of $this->amount for $this->cause has been made successfully. on " . date('Y-m-d H:i:s');
        } catch (Exception $e) {
            return "Failed to make donation: " . $e->getMessage() . serialize($db);
        }
    }
    public function getDonorId()
    {
        return $this->donorId;
    }
    public function getAmount()
    {
        return $this->amount;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function getCause()
    {
        return $this->cause;
    }
}
?>