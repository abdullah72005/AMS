<?php   
require_once 'User.php';
require_once 'Donation.php';
class alumni extends User
{   
    private $donations = [];
    public function __construct($username){
        parent::__construct($username);
    }

    public function makeDonation($amount, $cause)
    { 
        $donation = new Donation($this->getId(), $amount, $cause);
        $donation->donate();
    }

    public function getDonations()
    {
        $stmt = $this->dbCnx->prepare("SELECT * FROM donations WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $this->getId());
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

?>