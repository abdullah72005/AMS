<?php

require_once('User.php');


class Student extends User
{
    private $major;
    public function __construct($username)
    {
        parent::__construct($username);
    }

    public function selectMentor($mentor)
    {
        $stmt = $this->dbCnx->prepare("UPDATE Alumni SET mentor = ? WHERE userId = ?");
        $stmt->execute([$mentor, $this->getID()]);
    }
    public function search($fieldOfstudy)
    {
        $stmt = $this->dbCnx->prepare("SELECT * FROM Alumni WHERE major = ? and mentor = 1");
        $stmt->execute([$fieldOfstudy]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function register_user($password, $role)
    {
        try {
            $id = parent::register_user($password, $role); 
            $stmt = $this->dbCnx->prepare("INSERT INTO student (userId) VALUES (:user_id)");
            $stmt->bindParam(':user_id', $id);
            $stmt->execute();
            $this->login_user($password); // Log in the user after registration
            return $id;
        }
        catch (Exception $e) {
            return "Failed to register alumni: " . $e->getMessage();
        }
    }
}
?>