<?php

class Event {
    private int $eventId;
    private string $name;
    private string $description;
    private DateTime $date;
    private int $creatorId;
    private $dbCnx;

    public function __construct($name, $description, $date){
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

    public function addEvent($creatorId){
        $this->creatorId=$creatorId;
        $stmt = $this->dbCnx->prepare("INSERT INTO events (name, description, date, creatorId) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->name, $this->description, $this->date->format('d-m-y H:i:s'), $this->creatorId]);
        
        $this->eventId = (int)$this->dbCnx->lastInsertId();
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

    public function getMadeBy(): string {
        if (!isset($this->creatorId)) {
            throw new Exception("This event has not been added to db yet.");
        }
        return (string)$this->creatorId;
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
}

?>
