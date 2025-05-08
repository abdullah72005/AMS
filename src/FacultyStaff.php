<?php

require_once 'Event.php';
require_once 'User.php';

class FacultyStaff extends User{

    public function __construct($username)
    {
        parent::__construct($username);
        $this->dbCnx = require("db.php");
    }

    public function register_user($password, $role)
    {
        try {
            $id = parent::register_user($password, $role); 
            $stmt = $this->dbCnx->prepare("INSERT INTO FacultyStaff (user_id) VALUES (:user_id)");
            $stmt->bindParam(':user_id', $id);
            $stmt->execute();
            $this->login_user($password); // Log in the user after registration
            return $id;
        }
        catch (Exception $e) {
            return "Failed to register alumni: " . $e->getMessage();
        }
    }

    public function scheduleEvent(string $name, string $description, DateTime $date): int {
        // check if event exists with this name
        $this->dbCnx = require("db.php");

        $stmt = $this->dbCnx->prepare("SELECT eventId FROM `Event` WHERE name = ?");
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
        $stmt = $this->dbCnx->prepare("SELECT participant_id FROM EventParticipant WHERE event_id = ?");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function deleteEvent(int $eventId) {

        $stmt = $this->dbCnx->prepare("DELETE FROM `Event` WHERE eventId = ?");
        $stmt->execute([$eventId]);

    }

  

    public function editEventName($eventId, $newName): void {
        // check if event exists with this name
        $stmt = $this->dbCnx->prepare("SELECT eventId FROM `Event` WHERE name = ?");
        $stmt->execute([$newName]);
        $eventId = $stmt->fetchColumn();
        if ($eventId) {
            throw new Exception("Event with this name already exists.");
        }
        //update event name in db
        $stmt = $this->dbCnx->prepare("UPDATE `Event` SET name = ? WHERE eventId = ?");
        $stmt->execute([$newName, $eventId]);
    }

    public function editEventDescription($eventId, $newDescription): void {
        
        $stmt = $this->dbCnx->prepare("UPDATE `Event` SET description = ? WHERE eventId = ?");
        $stmt->execute([$newDescription, $eventId]);
    }

    public function editEventDate($eventId, $newDate): void {
        // check if event date is in the past
        $currentDate = new DateTime();
        if ($newDate <= $currentDate) {
            throw new Exception("Event date cannot be in the past.");
        }
        $stmt = $this->dbCnx->prepare("UPDATE `Event` SET date = ? WHERE eventId = ?");
        $stmt->execute([$newDate, $eventId]);
    }

    public function getEvents(): array {
        return Event::getEvents();
    }

}

?>
