<?php

require_once('User.php');


class FacultyStaff extends User
{

    public function __construct($username)
    {
        parent::__construct($username);
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
}
























// class FacultyStaffEvent_Management {
//     private array $events = []; // [eventId => Event]
//     private int $eventCounter = 1;

//     public function scheduleEvent(string $title, string $description, DateTime $date, int $creatorId): int {
//         $eventId = $this->eventCounter++;
//         $event = new Event($eventId, $title, $description, $date, $creatorId);
//         $this->events[$eventId] = $event;
//         return $eventId;
//     }

//     public function getEventParticipants(int $eventId): string {
//         if (isset($this->events[$eventId])) {
//             return implode(', ', $this->events[$eventId]->getParticipants());
//         }
//         return "Event not found.";
//     }
    

  
//         public function deleteEvent(int $eventId): void {
//             if (isset($this->events[$eventId])) {
//                 unset($this->events[$eventId]);
//             }
//         }
//         public function editEvent(int $eventId, string $newData): void {
//             if (isset($this->events[$eventId])) {
//                 $this->events[$eventId]->setDescription($newData);
//                 $this->events[$eventId]->setName($newData);
//                 $this->events[$eventId]->setDate(new DateTime($newData));
//             }
//         }
    


//     public function getEvents(int $userId): array {
//         $userEvents = [];
//         foreach ($this->events as $event) {
//             if ((int)$event->getMadeBy() === $userId) {
//                 $userEvents[] = $event->getEventId();
//             }
//         }
//         return $userEvents;
//     }
// }


?>