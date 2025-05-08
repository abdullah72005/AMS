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
        $dbCnx = require_once('db.php');
        try {
            $stmt = $dbCnx->prepare("INSERT INTO Donation (donor_id, amount, DATE, cause) VALUES (:user_id, :amount,:DATE, :cause)");
            $stmt->bindValue(':DATE', $this->date);
            $stmt->bindParam(':user_id', $this->donorId);
            $stmt->bindParam(':amount', $this->amount);
            $stmt->bindParam(':cause', $this->cause);
            $stmt->execute();
            return "Donation of $this->cause for $this->cause has been made successfully. on " . date('Y-m-d H:i:s');
        } catch (Exception $e) {
            return "Failed to make donation: " . $e->getMessage();
        }
    }
}
?>