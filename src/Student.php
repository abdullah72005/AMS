<?php

require_once('User.php');


class Student extends User
{
    private $major; 
    public function __construct($username)
    {
        parent::__construct($username);
    }

    public function selectMentor($mentorId)
    {
        // init db
        $dbCnx = require('db.php');

        $stmt = $dbCnx->prepare("INSERT INTO Student_Mentor (student_id, mentor_id) VALUES (?, ?)");
        $stmt->execute([$this->getID(), $mentorId]);

    }

    public function search($fieldOfstudy)
    {
        // init db
        $dbCnx = require('db.php');

        $stmt = $dbCnx->prepare("SELECT User.username, Alumni.graduationDate, Alumni.major FROM Alumni INNER JOIN User ON Alumni.userId = User.user_id WHERE Alumni.mentor = 1 AND Alumni.major = ?;");
        $stmt->execute([$fieldOfstudy]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function register_user($password, $role)
    {
        // init db
        $dbCnx = require('db.php');

        try {
            $id = parent::register_user($password, $role); 
            $stmt = $dbCnx->prepare("INSERT INTO Student (userId) VALUES (:user_id)");
            $stmt->bindParam(':user_id', $id);
            $stmt->execute();
            $this->login_user($password); // Log in the user after registration
            return $id;
        }
        catch (Exception $e) {
            throw new Exception("Failed to register student: " . $e->getMessage());
        }
    }

    public function seeAllMentors()
    {
        // init db
        $dbCnx = require('db.php');

        $stmt = $dbCnx->prepare("SELECT Alumni.userId, User.username, Alumni.graduationDate, Alumni.major, Mentorship.description FROM Alumni INNER JOIN User ON Alumni.userId = User.user_id INNER JOIN Mentorship ON Alumni.userId = Mentorship.mentor_id WHERE Alumni.mentor = 1;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCurrentMentorship()
    {
        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT COUNT(*) FROM Student_Mentor WHERE student_id = ?");
        $stmt->execute([$this->getID()]);
        $hasMentor = (bool)$stmt->fetchColumn();

        if($hasMentor)
        {
            $stmt = $dbCnx->prepare("SELECT User.username, Mentorship.description From Student_Mentor INNER JOIN User ON Student_Mentor.mentor_id = User.user_id INNER JOIN Mentorship ON Student_Mentor.mentor_id = Mentorship.mentor_id WHERE Student_Mentor.student_id = ?");
            $stmt->execute([$this->getID()]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return null;
        }
    }

    public function login_user($password)
    {
        parent::login_user($password);
        $_SESSION['userObj'] = $this;
    }
}
?>