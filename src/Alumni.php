<?php

require_once 'Event.php';

    private int $alumniId;

    public function __construct(int $alumniId) {
        $this->alumniId = $alumniId;
    }

    public function signupForEvent(int $eventId): void {
        Event::addParticipant($eventId, $this->alumniId);
    }

?>
