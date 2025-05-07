<?php

require_once('User.php');


class Alumni extends User
{
    private $mentorStatus; 
    private $verfied;

    public function __construct($username)
    {
        parent::__construct($username);
        $mentorStatus = false;
        $verfied = false;
    }
    public function serveAsMentor($verfied, $mentor)
    {
        
    }
    public function updateMentorStatus($newMentorStatus)
    {
        this->$verfied = true;
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


