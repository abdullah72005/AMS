<?php
session_start();
session_unset();
session_destroy();
?>

<?php
require_once("../src/User.php");
require_once("../src/Admin.php");
require_once("../src/Alumni.php");
require_once("../src/FacultyStaff.php");
require_once("../src/Student.php");
?>

<?php
$childView = 'views/register-view.php';
$titleView = 'Register';
include_once('layout.php');
?>