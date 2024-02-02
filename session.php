<?php
session_start();
$_SESSION['user_id'] = $_REQUEST['user_id'];
$_SESSION['role_id'] = $_REQUEST['role_id'];
?>
