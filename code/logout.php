<?php
session_start();
if (isset($_SESSION['email']) && isset($_SESSION['uid'])) {
    session_unset();
    session_destroy();
    header("Location: Login.php");
    exit();
} 
else 
{
    header("Location: Login.php");
    exit();
}
?>