<?php
session_start();
session_unset();
session_destroy();
?>

<?php
$childView = 'views/mentorship-view.php';
$titleView = 'Mentorship';
include('layout.php');
?>