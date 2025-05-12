<?php   
require_once 'User.php';
require_once 'Observer.php';
require_once __DIR__ . '/Donation.php';

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
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("UPDATE Alumni SET mentor = ? WHERE userId = ?");
        $stmt->execute([$newMentorStatus, $this->getID()]);
        $this->mentorStatus = $newMentorStatus;
    }

    public function isMentor()
    {
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
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("UPDATE Alumni SET major = ? WHERE userId = ?");
        $stmt->execute([$fieldOfStudy, $this->getId()]);
        $this->fieldOfstudy = $fieldOfStudy;
    }

    public function getFieldOfStudy()
    {
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
        try {        
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM Donation WHERE donor_id = :user_id order by donation_id desc");
        $stmt->bindValue(':user_id', $this->getId());
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);}
        catch (Exception $e) {
            return "Failed to get donations: " . $e->getMessage();
        }
    }
    public function register_user($password, $role)
    {
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
        $dbCnx = require('db.php');

        $stmt = $dbCnx->prepare("SELECT eventId FROM `Event` WHERE eventId = ?");
        $stmt->execute([$eventId]);
        $eventId = $stmt->fetchColumn();
        if (!$eventId) {
            throw new Exception("Event with this ID does not exist.");
        }

        $stmt = $dbCnx->prepare("SELECT participant_id FROM EventParticipant WHERE event_id = ? AND participant_id = ?");
        $stmt->execute([$eventId, $this->getId()]);
        $participantId = $stmt->fetchColumn();  
        if ($participantId) {
            throw new Exception("You are already signed up for this event.");
        }

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

        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM Alumni WHERE userId = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $arr2 = $stmt->fetch(PDO::FETCH_ASSOC);

        return array_merge($arr1, $arr2);
    }

    public function setGraduationDate($newGraduationDate)
    {
        $dbCnx = require('db.php');

        $date = DateTime::createFromFormat('Y-m-d', $newGraduationDate);
        if (!$date || $date->format('Y-m-d') !== $newGraduationDate) {
            throw new Exception("Invalid date format. Please use YYYY-MM-DD.");
        }

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
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("UPDATE Alumni SET major = :major WHERE userId = :user_id");
        $stmt->bindParam(':major', $newMajor);
        $stmt->bindParam(':user_id', $this->getId());
        $stmt->execute();
        $this->major = $newMajor;
    }

    public static function getUnverifiedAlumni()
    {
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT userId FROM Alumni WHERE verified = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function update($message, $calledClass)
    {
        try{
            $subscriptionMap = [
                'Newsletter' => 'subscribed_newsletter',
                'Mentorship' => 'subscribed_mentorship',
                'Event' => 'subscribed_events'
            ];
            
            if (!isset($subscriptionMap[$calledClass])) {
                throw new Exception("No subscription mapping for class: $calledClass");
            }
            
            $subscriptionName = $subscriptionMap[$calledClass];
            $dbCnx = require('db.php');
            $stmt = $dbCnx->prepare("SELECT * from user_subscriptions where $subscriptionName = 1");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $userId = $row['user_id'];
                $stmt = $dbCnx->prepare("INSERT INTO Notification (user_id, notification) VALUES (?, ?)");
                $stmt->execute([(int) $userId, $message]);
            }
        }
        catch (Exception $e) {
            throw new Exception("Error updating subscription: " . $e->getMessage());
        }
    }

    public function getNotifications()
    {
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM Notification WHERE user_id = ? ORDER BY notification_id DESC");
        $stmt->execute([$this->getId()]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
