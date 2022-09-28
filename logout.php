<?php
// start session
session_start();

// unset all session variable
$_SESSION = array();

// destroy session
session_destroy();

// redirect to login page
header("location: index.php");
exit;
?>
