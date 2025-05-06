<?php

class Event {
    private int $eventId;
    private string $name;
    private string $description;
    private DateTime $date;
    private int $creatorId;
    private array $participantsId;

    // Constructs a new Event instance.
    public function __construct(int $eventId, string $name, string $description, DateTime $date, int $creatorId) {
        $this->eventId = $eventId;
        $this->name = $name;
        $this->description = $description;
        $this->date = $date;
        $this->creatorId = $creatorId;
        $this->participantsId = [];
    }
//getters
    public function getEventId(): int {
        return $this->eventId;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getMadeBy(): string {
        return (string)$this->creatorId;
    }
//setters
    private function setEventId(int $eventId): void {
        $this->eventId = $eventId;
    }
    public function setDate(DateTime $date): void {
        $this->date = $date;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setCreator(string $creator): void {
        $this->creatorId = (int)$creator;
    }
// Adds a participant to the event.
    public function addParticipant(int $participantId): void {
        $this->participantsId[] = $participantId;
    }
//gets the participants of the event.
    public function getParticipants(): array {
        return $this->participantsId;
    }
}

?>
