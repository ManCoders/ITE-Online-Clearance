<?php
session_start(); // Start the session
session_abort();
session_destroy();

// Redirect to login page
header("Location: ../index.php");
exit();
?>