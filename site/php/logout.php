<?php
session_start();

//Remove all session variables then destroy the session
session_unset();
session_destroy();
//Redirect to login/register page
header('location: ../index.php');
exit();
?>
