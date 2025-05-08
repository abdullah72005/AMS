<?php   
require_once 'User.php';
require_once __DIR__ . '/Donation.php';

class Alumni extends User
{
    private $mentorStatus; 
    private $verfied;
    private $fieldOfstudy;
    private $donations = [];

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

    public function serveAsMentor()
    {
        if (!$this->verfied) {
            throw new Exception("You must be verified to serve as a mentor");
        }
        
        $this->updateMentorStatus(true);
        new Mentorship($this->getID()); 
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

    public function setFieldOfstudy($fieldOfStudy)
    {
        $stmt = $this->dbCnx->prepare("UPDATE Alumni SET major = ? WHERE userId = ?");
        $stmt->execute([$fieldOfStudy, $this->getId()]);
        $this->fieldOfstudy = $fieldOfStudy;
    }

    public function getFieldOfStudy()
    {
        $stmt = $this->dbCnx->prepare("SELECT major FROM Alumni WHERE userId = ?");
        $stmt->execute([$this->getId()]);
        return $stmt->fetchColumn();
    }

    public function makeDonation($id , $amount, $cause)
    { 
        $donation = new Donation($id, $amount, $cause);
        return $donation->donate();
    }

    public function getDonations()
    {
        $stmt = $this->dbCnx->prepare("SELECT * FROM donations WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $this->getId());
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function register_user($password, $role)
    {
        try {
            $id = parent::register_user($password, $role); 
            $stmt = $this->dbCnx->prepare("INSERT INTO Alumni (userId) VALUES (:user_id)");
            $stmt->bindParam(':user_id', $id);
            $stmt->execute();
            $this->login_user($password);
            return $id;
        }
        catch (Exception $e) {
            return "Failed to register alumni: " . $e->getMessage();
        }
    }
    
}

?>