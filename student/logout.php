<?php
session_start();
session_destroy();
session_abort();
// Redirect to login page
header("Location: ../index.php");
exit();
