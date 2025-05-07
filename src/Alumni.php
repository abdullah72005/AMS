<?php

require_once('User.php');


class Alumni extends User
{

    public function __construct($username)
    {
        parent::__construct($username);
    }

    
}








// class AlumniSignupForEvent {
//     private int $alumniId;

//     public function __construct(int $alumniId) {
//         $this->alumniId = $alumniId;
//     }

//     public function signupForEvent(int $eventId, array &$events): void {
//         if (isset($events[$eventId])) {
//             $events[$eventId]->addParticipant($this->alumniId);
//         } else {
//             echo "Event not found.\n";
//         }
//     }
// }


