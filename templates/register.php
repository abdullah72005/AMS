<?php
session_start();
session_unset();
session_destroy();
?>

<?php
$childView = 'views/register-view.php';
$titleView = 'Register';
include_once('layout.php');
?>