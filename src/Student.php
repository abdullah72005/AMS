<?php

require_once('User.php');


class Student extends User
{
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
}
?>