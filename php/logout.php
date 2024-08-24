<?php
// Start the session
session_start();


session_unset();
session_destroy();

// Redirect to the login page
header('Location: ../index.php');
exit();
?>
