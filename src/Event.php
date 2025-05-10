<?php
require_once('Subject.php');
class Event extends Subject {
    private int $eventId;
    private string $name;
    private string $description;
    private DateTime $date;
    private int $creatorId;
    private $dbCnx;

    public function __construct($name = null, $description = null, $date = null){
        if (func_num_args() === 0) 
        {
            // Default constructor logic
            $this->name = '';
            $this->description = '';
            $this->date = new DateTime();
        } 
        else 
        {
            $this->name = $name;
            $this->description = $description;
            $this->date = $date;
            $this->dbCnx=require("db.php");    

            // check if event is already created
            $stmt = $this->dbCnx->prepare("SELECT eventId FROM `Event` WHERE name = ?");
            $stmt->execute([$this->name]);
            $eventId = $stmt->fetchColumn();
            if ($eventId) {
                $this->eventId = $eventId;

                // get creatorId
                $stmt = $this->dbCnx->prepare("SELECT creatorId FROM `Event` WHERE name = ?");
                $stmt->execute([$this->name]);
                $creatorId = $stmt->fetchColumn();
                $this->creatorId = $creatorId;
            }
        }
    }

    public function addEvent($creatorId){
        $this->creatorId=$creatorId;
        $stmt = $this->dbCnx->prepare("INSERT INTO `Event` (name, description, date, creatorId) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->name, $this->description, $this->date->format('y-m-d H:i:s'), $this->creatorId]);
        
        $this->eventId = (int)$this->dbCnx->lastInsertId();
        $message = "New Event has been scheduled ". $this->name . $this->date;
        $this->notify($message);
        return $this->eventId;
    }

    public function getEventId(): int {
        if (!isset($this->eventId)) {
            throw new Exception("This event has not been added to db yet.");
        }
        return $this->eventId;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public static function getMadeBy($creatorId): string {
        if (!isset($creatorId)) {
            throw new Exception("This event has not been added to db yet.");
        }
        // get user name from db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT username FROM `User` WHERE user_id = ?");
        $stmt->execute([$creatorId]);   
        $username = $stmt->fetchColumn();
        if (!$username) {
            throw new Exception("Creator not found.");
        }
        return $username;
    }




    public static function getEvents() {
        // Get DB connection (assuming db.php returns a PDO instance)
        $dbCnx = require('db.php');

        $stmt = $dbCnx->prepare("SELECT eventId FROM `Event`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function addParticipant($alumniId) {
        // get event id 
        $stmt = $this->dbCnx->prepare("SELECT event_id FROM `Event` WHERE name = ?");
        $stmt->execute([$this->name]);
        $eventId = $stmt->fetchColumn();
        if (!$eventId) {
            throw new Exception("Event not found.");
        }
        $this->eventId = $eventId;
        $stmt = $this->dbCnx->prepare("INSERT INTO EventParticipant (event_id, participant_id) VALUES (?, ?)");
        $stmt->execute([$this->eventId, $alumniId]);
    }

    public static function getEventById($eventId) {
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM `Event` WHERE eventId = ?");
        $stmt->execute([$eventId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>