<?php
session_start();
session_unset();
session_destroy();
?>

<?php
$childView = 'views/login-view.php';
$titleView = 'Login';
include_once('layout.php');
?>