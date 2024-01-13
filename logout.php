<!--
* Author: Beulah Nwokotubo
* File Name: logout.php
* Date Created: 23 November 2023
* Description: Logout php functionality to redirect to login page.
 -->

<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to the login page or any other page you want
header("Location: login.php");
exit;
?>