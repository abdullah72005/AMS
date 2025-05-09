<?php

require_once('User.php');


class Student extends User
{
    private $major;

    public function __construct($username)
    {
        parent::__construct($username);
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

    public function login_user($password)
    {
        parent::login_user($password);

        $_SESSION['userObj'] = $this;
    }

    public static function getAllUserData($userId)
    {
        $arr1 = parent::getAllUserData($userId);

        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("SELECT * FROM `Student` WHERE userId = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $arr2 = $stmt->fetch(PDO::FETCH_ASSOC);

        return array_merge($arr1, $arr2);
    }

    public  function setMajor($newMajor)
    {
        $valid_majors = User::$validMajors;
        if (!in_array($newMajor, $valid_majors)) {
            throw new Exception("Invalid major. Please choose from the following: " . implode(", ", $valid_majors));
        }
        // init db
        $dbCnx = require('db.php');
        $stmt = $dbCnx->prepare("UPDATE Student SET major = :major WHERE userId = :user_id");
        $stmt->bindParam(':major', $newMajor);
        $stmt->bindParam(':user_id', $this->getId());
        $stmt->execute();
        $this->major = $newMajor;
    }
}
?>