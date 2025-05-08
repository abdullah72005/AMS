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

        $stmt = $dbCnx->prepare("SELECT User.username, Alumni.graduationDate, Alumni.major FROM Alumni INNER JOIN User ON Alumni.userId User.user_id WHERE Alumni.mentor = 1 AND Alumni.major = ?;");
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
            return "Failed to register alumni: " . $e->getMessage();
        }
    }

    public function seeAllMentors()
    {
        // init db
        $dbCnx = require('db.php');

        $stmt = $dbCnx->prepare("SELECT User.username, Alumni.graduationDate, Alumni.major FROM Alumni INNER JOIN User ON Alumni.userId User.user_id WHERE Alumni.mentor = 1;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function login_user($password)
    {
        parent::login_user($password);
        $_SESSION['userObj'] = $this;
    }
}
?>