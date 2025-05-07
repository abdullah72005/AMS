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

    }

}
?>