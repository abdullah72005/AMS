<?php

require_once 'Event.php';
require_once 'User.php';
class FacultyStaff extends User{

    public function __construct($username)
    {
        parent::__construct($username);
    }

    public function register_user($password, $role)
    {
        // init db
        $dbCnx = require('db.php');

        try {
            $id = parent::register_user($password, $role); 
            $stmt = $dbCnx->prepare("INSERT INTO FacultyStaff (user_id) VALUES (:user_id)");
            $stmt->bindParam(':user_id', $id);
            $stmt->execute();
            $this->login_user($password); // Log in the user after registration
            return $id;
        }
        catch (Exception $e) {
            throw new Exception("Failed to register student: " . $e->getMessage());
        }
    }

    public function scheduleEvent(string $name, string $description, DateTime $date): int {
        // init db
        $dbCnx = require('db.php');

        // check if event exists with this name
        $stmt = $dbCnx->prepare("SELECT eventId FROM `Event` WHERE name = ?");
        $stmt->execute([$name]);
        $eventId = $stmt->fetchColumn();
        if ($eventId) {
            throw new Exception("Event with this name already exists.");
        }
        // check if event date is in the past
        $currentDate = new DateTime();
        if ($date <= $currentDate) {
            throw new Exception("Event date cannot be in the past.");
        }
        $event = new Event($name, $description, $date);

        $creatorId = $this->getId();

        return $event->addEvent($creatorId);
    }

    public function getEventParticipants($eventId) {
        // init db
        $dbCnx = require('db.php');

        $stmt = $dbCnx->prepare("
            SELECT u.username 
            FROM EventParticipant ep
            INNER JOIN User u ON ep.participant_id = u.user_id
            WHERE ep.event_id = ?
            ");
        $stmt->execute([$eventId]);

        // Fetch all usernames as an array
        $usernames = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $usernames;
    }
        

    public function deleteEvent(int $eventId) {
        // init db
        $dbCnx = require('db.php');

        $stmt = $dbCnx->prepare("DELETE FROM `Event` WHERE eventId = ?");
        $stmt->execute([$eventId]);

    }

  

    public function editEventName($eventId, $newName): void {
        // init db
        $dbCnx = require('db.php');

        // check if event exists with this name
        $stmt = $dbCnx->prepare("SELECT eventId FROM `Event` WHERE name = ?");
        $stmt->execute([$newName]);
        if ($stmt->fetchColumn()) {
            throw new Exception("Event with this name already exists.");
        }
        //update event name in db
        $stmt = $dbCnx->prepare("UPDATE `Event` SET name = ? WHERE eventId = ?");
        $stmt->execute([$newName, $eventId]);
    }

    public function editEventDescription($eventId, $newDescription): void {
        // init db
        $dbCnx = require('db.php');

        $stmt = $dbCnx->prepare("UPDATE `Event` SET description = ? WHERE eventId = ?");
        $stmt->execute([$newDescription, $eventId]);
    }

    public function editEventDate($eventId, $newDate): void {
        // init db
        $dbCnx = require('db.php');
    
        $currentDate = new DateTime();
        $inputDate = new DateTime($newDate); // Convert string to DateTime
    
        if ($inputDate < $currentDate) {
            throw new Exception("Event date cannot be in the past.");
        }
    
        $stmt = $dbCnx->prepare("UPDATE Event SET date = ? WHERE eventId = ?");
        $stmt->execute([$inputDate->format('Y-m-d'),$eventId]);
    }

    public static function getEvents(): array {
        return Event::getEvents();
    }

    public function login_user($password)
    {
        parent::login_user($password);

        $_SESSION['userObj'] = $this;
    }
    public function getAllDonations(){
    try {        
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM donation order by donation_id desc");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);}
        catch (Exception $e) {
            return "Failed to get donations: " . $e->getMessage();
        }
    }
    public function createNewsletter($title, $body,  $state, $id = null) {
        return new Newsletter($this->getId(), $title,  $body, $state, $id);
    }
    public function getNewsletter($id) {
        // init db
        try {        
            $dbCnx = require('db.php');
            $stmt = $dbCnx->prepare("SELECT * FROM Newsletter WHERE Newsletter_id = ?");
            $stmt->execute([$id]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$res) {
                throw new Exception("Newsletter not found.");
            }
            if ($res['publishedState'] === 0) {
                $state = new DraftState();
            } else {
                $state = new PublishedState();
            }
            $newsletter = new Newsletter($res['creatorId'], $res['title'], $res['body'],$state,$id);
            return $newsletter;
        }
        catch (Exception $e) {
            return $e;
        }

    }

    public static function getAllUserData($userId)
    {
        $arr1 = parent::getAllUserData($userId);

        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM `FacultyStaff` WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $arr2 = $stmt->fetch(PDO::FETCH_ASSOC);

        return array_merge($arr1, $arr2);
    }

    public function verifyAlumni($Id): void {
        // init db
        $dbCnx = require('db.php');

        // check if alumni exists
        $stmt = $dbCnx->prepare("SELECT userId FROM `Alumni` WHERE userId = ?");
        $stmt->execute([$Id]);
        $alumniId = $stmt->fetchColumn();
        if (!$alumniId) {
            throw new Exception("Alumni with this ID does not exist.");
        }

        // verify alumni
        $stmt = $dbCnx->prepare("UPDATE `Alumni` SET verified = 1 WHERE userId = ?");
        $stmt->execute([$alumniId]);
    }

}

?>
