<?php



class Event {
    private int $eventId;
    private string $name;
    private string $description;
    private DateTime $date;
    private int $creatorId;
    private $dbCnx;

    public function __construct(int $eventId, string $name, string $description, DateTime $date) {
        $this->eventId = $eventId;
        $this->name = $name;
        $this->description = $description;
        $this->date = $date;
        $this->dbCnx=require("db.php");
    }

    public function addEvent($creatorId){
        $this->creatorId=$creatorId;
        $stmt = $this->dbCnx->prepare("INSERT INTO events (name, description, date, creatorId) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->name, $this->description, $this->date->format('d-m-y H:i:s'), $this->creatorId]);
        $this->eventId = (int)$this->dbCnx->lastInsertId();
        return $this->eventId;
    }

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

  

    public static function Edit(int $eventId, string $newData): void {
        $stmt = $this->dbCnx->prepare("UPDATE events SET name = ?, description = ?, date = ? WHERE event_id = ?");
        $stmt->execute([$newData, $newData, (new DateTime($newData))->format('Y-m-d H:i:s'), $eventId]);
    }

    public static function delete(int $eventId): void {
        $stmt = $this->dbCnx->prepare("DELETE FROM events WHERE event_id = ?");
        $stmt->execute([$eventId]);
    }

    public static function getEventsByCreator(int $creatorId): array {
    
        $stmt = $this->dbCnx->prepare("SELECT event_id FROM events WHERE creator_id = ?");
        $stmt->execute([$creatorId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getParticipants(int $eventId): array {
        $stmt = $this->dbCnx->prepare("SELECT alumni_id FROM participants WHERE event_id = ?");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function addParticipant(int $eventId, int $alumniId): void {
        $stmt = $this->dbCnx->prepare("INSERT INTO participants (event_id, alumni_id) VALUES (?, ?)");
        $stmt->execute([$eventId, $alumniId]);
    }
}

?>
