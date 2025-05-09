<?php   
require_once 'User.php';
require_once __DIR__ . '/Donation.php';
class Alumni extends User
{   
    private $donations = [];
    private $major;
    private $graduationDate;

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
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM donations WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $this->getId());
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public static function getAllUserData($userId)
    {
        $arr1 = parent::getAllUserData($userId);

        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM Alumni WHERE userId = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $arr2 = $stmt->fetch(PDO::FETCH_ASSOC);

        return array_merge($arr1, $arr2);
    }

    public function setGraduationDate($newGraduationDate)
    {
        // init db
        $dbCnx = require('db.php');

        // check if new graduation date is valid DateTime formatting
        $date = DateTime::createFromFormat('Y-m-d', $newGraduationDate);
        if (!$date || $date->format('Y-m-d') !== $newGraduationDate) {
            throw new Exception("Invalid date format. Please use YYYY-MM-DD.");
        }

        // check if graduation date is in the past
        $currentDate = new DateTime();
        if ($date > $currentDate) {
            throw new Exception("Graduation date must be in the past.");
        }

        $stmt = $dbCnx->prepare("UPDATE Alumni SET graduationDate = :graduationDate WHERE userId = :user_id");
        $stmt->bindParam(':graduationDate', $date->format('Y-m-d'));
        $stmt->bindParam(':user_id', $this->getId());
        $stmt->execute();
        $this->graduationDate = $date->format('Y-m-d');
    }

    public  function setMajor($newMajor)
    {
        $valid_majors = User::$validMajors;
        if (!in_array($newMajor, $valid_majors)) {
            throw new Exception("Invalid major. Please choose from the following: " . implode(", ", $valid_majors));
        }
        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("UPDATE Alumni SET major = :major WHERE userId = :user_id");
        $stmt->bindParam(':major', $newMajor);
        $stmt->bindParam(':user_id', $this->getId());
        $stmt->execute();
        $this->major = $newMajor;
    }
    
}

?>
