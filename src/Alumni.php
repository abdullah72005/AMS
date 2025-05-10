<?php   
require_once 'User.php';
require_once __DIR__ . '/Donation.php';
require_once 'Observer.php';

class Alumni extends User implements Observer
{
    private $mentorStatus; 
    private $verfied;
    private $fieldOfstudy;
    private $major;
    private $graduationDate;

    public function __construct($username)
    {
        parent::__construct($username);

    }

    public function serveAsMentor()
    {
        if(!$this->verfied) 
        {
            throw new Exception("You must be verified to serve as a mentor");
        }
        
        $this->updateMentorStatus(true);
        new Mentorship($this->getID()); 
    }

    public function updateMentorStatus($newMentorStatus)
    {
        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("UPDATE Alumni SET mentor = ? WHERE userId = ?");
        $stmt->execute([$newMentorStatus, $this->getID()]);
        $this->mentorStatus = $newMentorStatus;
    }

    public function isMentor()
    {
        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT mentor FROM Alumni WHERE userId = ?");
        $stmt->execute([$this->getId()]);
        $mentorStatus = $stmt->fetchColumn();
        if ($mentorStatus == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function isVerfied()
    {
        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT verified FROM Alumni WHERE userId = ?");
        $stmt->execute([$this->getId()]);
        $verfied = $stmt->fetchColumn();
        if ($verfied == 1) {
            return true;
        } 
            
        return false;
        
    }

    public function setFieldOfstudy($fieldOfStudy)
    {
        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("UPDATE Alumni SET major = ? WHERE userId = ?");
        $stmt->execute([$fieldOfStudy, $this->getId()]);
        $this->fieldOfstudy = $fieldOfStudy;
    }

    public function getFieldOfStudy()
    {
        // init db
        $dbCnx = require('db.php');

        $stmt = $dbCnx->prepare("SELECT major FROM Alumni WHERE userId = ?");
        $stmt->execute([$this->getId()]);
        return $stmt->fetchColumn();
    }

    public function makeDonation( $amount, $cause)
    { 
        $donation = new Donation($this->getId(), $amount, $cause);
        return $donation->donate();
    }

    public function getDonations()
    {
        // init db
        try {        
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM donation WHERE donor_id = :user_id order by donation_id desc");
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
            $stmt = $dbCnx->prepare("INSERT INTO Alumni (userId) VALUES (:userId)");
            $stmt->bindParam(':userId', $id);
            $stmt->execute();
            $this->login_user($password);
        }
        catch (Exception $e) {
            throw new Exception("Failed to register student: " . $e->getMessage());
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
        if ($eventDate > $currentDate) {
            throw new Exception("Event date cannot be in the past.");
        }

        $stmt = $dbCnx->prepare("INSERT INTO EventParticipant (event_id, participant_id) VALUES (?, ?)");
        $stmt->execute([$eventId, $this->getId()]);
    }

    public function login_user($password)
    {
        // init db
        $dbCnx = require('db.php');

        parent::login_user($password);
        $stmt = $dbCnx->prepare("SELECT * FROM Alumni WHERE userId = ?");
        $stmt->execute([$this->getID()]);
        $alumniData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($alumniData) 
        {
            $this->mentorStatus = $alumniData['mentor'];
            $this->verfied = $alumniData['verified'];
            $this->fieldOfstudy = $alumniData['major'];
        } 
        else 
        {
            throw new Exception("Alumni data not found.");
        }


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

    public function setMajor($newMajor)
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

    public static function getUnverifiedAlumni()
    {
        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT userId FROM Alumni WHERE verified = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch as a flat array of userIds
    }
    public function update($message)
    {
        // init db
        $dbCnx = require('db.php');
        $id = $this::getIDFromUsername($this->getUsername());
        $stmt = $dbCnx->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->execute([$id,$message]);
    }
    public function getNotifications()
    {
        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM notifications WHERE user_id = ?");
        $stmt->execute([$this->getId()]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
