<?php   
require_once 'User.php';
require_once __DIR__ . '/Donation.php';
class Alumni extends User
{   
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
        // init db
        try {        
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM donation WHERE donor_id = :user_id");
        $stmt->bindValue(':user_id', $this->getId());
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);}
        catch (Exception $e) {
            return "Failed to get donations: " . $e->getMessage();
        }
    }
    public function register_user($password, $role)
    {
        // init db
        $dbCnx = require('db.php');
        try {
            $id = parent::register_user($password, $role); 
            $stmt = $dbCnx->prepare("INSERT INTO Alumni (userId) VALUES (:user_id)");
            $stmt->bindParam(':user_id', $id);
            $stmt->execute();
            $this->login_user($password); // Log in the user after registration
            return $id;
        }
        catch (Exception $e) {
            return "Failed to register alumni: " . $e->getMessage();
        }

    }


    public function signupForEvent(int $eventId): void {
        // init db
        $dbCnx = require('db.php');
        // check if event exists
        $stmt = $dbCnx->prepare("SELECT eventId FROM `Event` WHERE eventId = ?");
        $stmt->execute([$eventId]);
        $eventId = $stmt->fetchColumn();
        if (!$eventId) {
            throw new Exception("Event with this ID does not exist.");
        }

        // check if user is already signed up
        $stmt = $dbCnx->prepare("SELECT participant_id FROM EventParticipant WHERE event_id = ? AND participant_id = ?");
        $stmt->execute([$eventId, $this->getId()]);
        $participantId = $stmt->fetchColumn();  
        if ($participantId) {
            throw new Exception("You are already signed up for this event.");
        }

        // check if event is in the past
        $stmt = $dbCnx->prepare("SELECT date FROM `Event` WHERE eventId = ?");
        $stmt->execute([$eventId]);
        $eventDate = $stmt->fetchColumn();
        $currentDate = new DateTime();
        if ($eventDate <= $currentDate) {
            throw new Exception("Event date cannot be in the past.");
        }

        $stmt = $dbCnx->prepare("INSERT INTO EventParticipant (event_id, participant_id) VALUES (?, ?)");
        $stmt->execute([$eventId, $this->getId()]);
    }

    public function login_user($password)
    {
        parent::login_user($password);

        $_SESSION['userObj'] = $this;
    }
    
}

?>
