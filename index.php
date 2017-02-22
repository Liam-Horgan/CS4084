<?php
ob_start();
session_start();
require_once 'dbconnect.php';

if(isset($_SESSION['user'])!=""{
	header("Location: home.php");
	exit;
}
?>