<?php
class FacultyStaffEvent_Management {
    private array $events = []; // [eventId => Event]
    private int $eventCounter = 1;

    public function scheduleEvent(string $title, string $description, DateTime $date, int $creatorId): int {
        $eventId = $this->eventCounter++;
        $event = new Event($eventId, $title, $description, $date, $creatorId);
        $this->events[$eventId] = $event;
        return $eventId;
    }

    public function getEventParticipants(int $eventId): string {
        if (isset($this->events[$eventId])) {
            return implode(', ', $this->events[$eventId]->getParticipants());
        }
        return "Event not found.";
    }
    

  
        public function deleteEvent(int $eventId): void {
            if (isset($this->events[$eventId])) {
                unset($this->events[$eventId]);
            }
        }
        public function editEvent(int $eventId, string $newData): void {
            if (isset($this->events[$eventId])) {
                $this->events[$eventId]->setDescription($newData);
                $this->events[$eventId]->setName($newData);
                $this->events[$eventId]->setDate(new DateTime($newData));
            }
        }
    


    public function getEvents(int $userId): array {
        $userEvents = [];
        foreach ($this->events as $event) {
            if ((int)$event->getMadeBy() === $userId) {
                $userEvents[] = $event->getEventId();
            }
        }
        return $userEvents;
    }
}


?>