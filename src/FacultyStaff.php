<?php

require_once 'Event.php';
require_once 'User.php';

class FacultyStaff extends User{

    public function scheduleEvent(string $title, string $description, DateTime $date): int {
        $event = new Event(0, $title, $description, $date);
        $creatorId=$this->getId();
        $event->addEvent(creatorId);
        return $event->save();

    }

    public function getEventParticipants(int $eventId): string {
        $participants = Event::getParticipants($eventId);
        return !empty($participants) ? implode(', ', $participants) : "No participants found.";
    }

    public function deleteEvent(int $eventId): void {
        Event::delete($eventId);
    }

    public function editEvent(int $eventId, string $newData): void {
        Event::update($eventId, $newData);
    }

    public function getEvents(int $userId): array {
        return Event::getEventsByCreator($userId);
    }

}

?>
