<?php

require_once('User.php');


class Alumni extends User
{
    private $mentorStatus; 
    private $verfied;
    private $fieldOfstudy;

    public function __construct($username)
    {
        parent::__construct($username);
        $stmt = $this->dbCnx->prepare("SELECT * FROM Alumni WHERE userId = ?");
        $stmt->execute([$this->getID()]);
        $alumniData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($alumniData) {
            $this->mentorStatus = $alumniData['mentor'];
            $this->verfied = $alumniData['verified'];
            $this->fieldOfstudy = $alumniData['major'];
        } else {
            throw new Exception("Alumni data not found.");
        }
    }
    public function serveAsMentor($verfied, $mentorStatus)
    {
        if ($verfied && !$mentorStatus) {
            $this->updateMentorStatus(true);
        } 
        elseif($verfied == false) 
        {
            throw new Exception("You are not verified to serve as a mentor.");
        }
        else
        {
            throw new Exception("You are already serving as a mentor.");
        }
    }
    public function updateMentorStatus($newMentorStatus)
    {
        $stmt = $this->dbCnx->prepare("UPDATE Alumni SET mentor = ? WHERE userId = ?");
        $stmt->execute([$this->mentorStatus, $this->getID()]);
        $this->mentorStatus = $newMentorStatus;
    }
    public function isMentor()
    {
        return $this->mentorStatus;
    }
    public function isVerfied()
    {
        return $this->verfied;
    }
    public function updateFieldOfstudy()
    {
        $stmt = $this->dbCnx->prepare("UPDATE Alumni SET major = ? WHERE userId = ?");
        $stmt->execute([$this->fieldOfstudy, $this->getId()]);
    }
    public function getFieldOfStudy()
    {
        $stmt = $this->dbCnx->prepare("SELECT major FROM Alumni WHERE userId = ?");
        $stmt->execute([$this->getId()]);
        return $stmt->fetchColumn();
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


