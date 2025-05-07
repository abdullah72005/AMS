<?php   
require_once 'User.php';
require_once __DIR__ . '/Donation.php';
class Alumni extends User
{   
    private $donations = [];
    public function __construct($username){
        parent::__construct($username);
    }

    public function makeDonation($id , $amount, $cause)
    { 
        $donation = new Donation($id, $amount, $cause);
        return $donation->donate();
    }

    public function getDonations()
    {
        $stmt = $this->dbCnx->prepare("SELECT * FROM donations WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $this->getId());
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function register_user($password, $role)
    {
        try {
            $id = parent::register_user($password, $role); 
            $stmt = $this->dbCnx->prepare("INSERT INTO Alumni (userId) VALUES (:user_id)");
            $stmt->bindParam(':user_id', $id);
            $stmt->execute();
            $this->login_user($password); // Log in the user after registration
            return $id;
        }
        catch (Exception $e) {
            return "Failed to register alumni: " . $e->getMessage();
        }

    }
    
}

?>