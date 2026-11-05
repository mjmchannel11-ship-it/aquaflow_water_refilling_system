<?php
require_once __DIR__.'/includes/functions.php';
if (logged_in()) log_activity($pdo,'Logout','User logged out');
$_SESSION=[]; session_destroy(); header('Location: login.php'); exit;
